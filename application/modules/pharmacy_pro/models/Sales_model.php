<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Sales Model - Handle all sales operations
 */
class Sales_model extends SHV_Model {

    protected $table = 'medicine_sales';

    public function __construct() {
        parent::__construct();
    }

    /**
     * Process complete sale transaction
     */
    public function process_sale($sale_data, $sale_items) {
        $this->db->trans_start();
        
        try {
            // Generate bill number
            $sale_data['bill_number'] = $this->generate_bill_number();
            $sale_data['sale_date'] = date('Y-m-d H:i:s');
            $sale_data['balance_amount'] = $sale_data['total_amount'] - $sale_data['paid_amount'];
            
            // Insert sale record
            $this->db->insert('medicine_sales', $sale_data);
            $sale_id = $this->db->insert_id();
            
            // Process each sale item
            foreach ($sale_items as $item) {
                // Check stock availability
                $batch = $this->get_batch_details($item['batch_id']);
                
                if (!$batch || $batch['current_stock'] < $item['quantity']) {
                    throw new Exception('Insufficient stock for ' . $item['medicine_name']);
                }
                
                // Insert sale item
                $sale_item_data = array(
                    'sale_id' => $sale_id,
                    'medicine_id' => $item['medicine_id'],
                    'batch_id' => $item['batch_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount_percentage' => $item['discount_percentage'],
                    'discount_amount' => $item['discount_amount'],
                    'gst_percentage' => $item['gst_percentage'],
                    'gst_amount' => $item['gst_amount'],
                    'total_amount' => $item['total_amount'],
                    'expiry_date' => $batch['expiry_date']
                );
                
                $this->db->insert('medicine_sale_items', $sale_item_data);
                
                // Update batch stock
                $new_stock = $batch['current_stock'] - $item['quantity'];
                $this->db->where('id', $item['batch_id']);
                $this->db->update('medicine_batches', array('current_stock' => $new_stock));
                
                // Update batch status if exhausted
                if ($new_stock <= 0) {
                    $this->db->where('id', $item['batch_id']);
                    $this->db->update('medicine_batches', array('status' => 'exhausted'));
                }
            }
            
            $this->db->trans_complete();
            
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Transaction failed');
            }
            
            return array(
                'status' => true,
                'sale_id' => $sale_id,
                'bill_number' => $sale_data['bill_number']
            );
            
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return array('status' => false, 'message' => $e->getMessage());
        }
    }

    /**
     * Get batch details
     */
    private function get_batch_details($batch_id) {
        $this->db->where('id', $batch_id);
        $query = $this->db->get('medicine_batches');
        return $query->row_array();
    }

    /**
     * Generate bill number
     */
    private function generate_bill_number() {
        $prefix = 'PH' . date('Ymd');
        
        $this->db->select('bill_number');
        $this->db->from('medicine_sales');
        $this->db->like('bill_number', $prefix, 'after');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            $last_bill = $query->row()->bill_number;
            $last_number = intval(substr($last_bill, -4));
            $new_number = $last_number + 1;
        } else {
            $new_number = 1;
        }
        
        return $prefix . str_pad($new_number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get sale by ID
     */
    public function get_sale_by_id($sale_id) {
        $this->db->select('ms.*, 
                          COALESCE(
                              CONCAT_WS(" ", pd1.FirstName, pd1.LastName), 
                              ipd.FName, 
                              ms.patient_name
                          ) as patient_display_name,
                          u.user_name as doctor_name', FALSE);
        $this->db->from('medicine_sales ms');
        $this->db->join('patientdata pd1', 'ms.patient_id = pd1.OpdNo AND ms.patient_type = "opd"', 'left');
        $this->db->join('inpatientdetails ipd', 'ms.patient_id = ipd.IpNo AND ms.patient_type = "ipd"', 'left');
        $this->db->join('users u', 'ms.doctor_id = u.ID', 'left');
        $this->db->where('ms.id', $sale_id);
        
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * Get sale items
     */
    public function get_sale_items($sale_id) {
        $this->db->select('msi.*, m.medicine_name, m.strength, mb.batch_number');
        $this->db->from('medicine_sale_items msi');
        $this->db->join('medicines m', 'msi.medicine_id = m.id');
        $this->db->join('medicine_batches mb', 'msi.batch_id = mb.id');
        $this->db->where('msi.sale_id', $sale_id);
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get today's sales amount
     */
    public function get_todays_sales_amount() {
        $this->db->select('COALESCE(SUM(total_amount), 0) as total');
        $this->db->from('medicine_sales');
        $this->db->where('DATE(sale_date)', date('Y-m-d'));
        
        $query = $this->db->get();
        return $query->row()->total;
    }

    /**
     * Get monthly sales amount
     */
    public function get_monthly_sales_amount() {
        $this->db->select('COALESCE(SUM(total_amount), 0) as total');
        $this->db->from('medicine_sales');
        $this->db->where('MONTH(sale_date)', date('m'));
        $this->db->where('YEAR(sale_date)', date('Y'));
        
        $query = $this->db->get();
        return $query->row()->total;
    }

    /**
     * Get recent sales
     */
    public function get_recent_sales($limit = 10) {
        $this->db->select('ms.*, 
                          COALESCE(
                              CONCAT_WS(" ", pd1.FirstName, pd1.LastName), 
                              ipd.FName, 
                              ms.patient_name
                          ) as patient_display_name', FALSE);
        $this->db->from('medicine_sales ms');
        $this->db->join('patientdata pd1', 'ms.patient_id = pd1.OpdNo AND ms.patient_type = "opd"', 'left');
        $this->db->join('inpatientdetails ipd', 'ms.patient_id = ipd.IpNo AND ms.patient_type = "ipd"', 'left');
        $this->db->order_by('ms.sale_date', 'DESC');
        $this->db->limit($limit);
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get sales report data
     */
    public function get_sales_report($from_date, $to_date, $payment_mode = null) {
        $this->db->select('ms.*, 
                          COALESCE(
                              CONCAT_WS(" ", pd1.FirstName, pd1.LastName), 
                              ipd.FName, 
                              ms.patient_name
                          ) as patient_display_name,
                          u.user_name as cashier_name', FALSE);
        $this->db->from('medicine_sales ms');
        $this->db->join('patientdata pd1', 'ms.patient_id = pd1.OpdNo AND ms.patient_type = "opd"', 'left');
        $this->db->join('inpatientdetails ipd', 'ms.patient_id = ipd.IpNo AND ms.patient_type = "ipd"', 'left');
        $this->db->join('users u', 'ms.cashier_id = u.ID', 'left');
        
        $this->db->where('DATE(ms.sale_date) >=', $from_date);
        $this->db->where('DATE(ms.sale_date) <=', $to_date);
        
        if ($payment_mode) {
            $this->db->where('ms.payment_mode', $payment_mode);
        }
        
        $this->db->order_by('ms.sale_date', 'DESC');
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get patient medicine history
     */
    public function get_patient_medicine_history($patient_id) {
        $this->db->select('ms.sale_date, ms.bill_number, ms.total_amount,
                          msi.quantity, m.medicine_name, mb.batch_number');
        $this->db->from('medicine_sales ms');
        $this->db->join('medicine_sale_items msi', 'ms.id = msi.sale_id');
        $this->db->join('medicines m', 'msi.medicine_id = m.id');
        $this->db->join('medicine_batches mb', 'msi.batch_id = mb.id');
        $this->db->where('ms.patient_id', $patient_id);
        $this->db->order_by('ms.sale_date', 'DESC');
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get ABC Analysis
     */
    public function get_abc_analysis() {
        $this->db->select('m.medicine_name, 
                          SUM(msi.quantity) as total_quantity_sold,
                          SUM(msi.total_amount) as total_revenue,
                          COUNT(DISTINCT msi.sale_id) as times_sold');
        $this->db->from('medicine_sale_items msi');
        $this->db->join('medicines m', 'msi.medicine_id = m.id');
        $this->db->join('medicine_sales ms', 'msi.sale_id = ms.id');
        $this->db->where('ms.sale_date >=', date('Y-m-d', strtotime('-1 year')));
        $this->db->group_by('m.id');
        $this->db->order_by('total_revenue', 'DESC');
        
        $query = $this->db->get();
        $results = $query->result_array();
        
        // Calculate ABC classification
        $total_revenue = array_sum(array_column($results, 'total_revenue'));
        $cumulative_percentage = 0;
        
        foreach ($results as &$item) {
            $percentage = ($item['total_revenue'] / $total_revenue) * 100;
            $cumulative_percentage += $percentage;
            
            if ($cumulative_percentage <= 80) {
                $item['category'] = 'A';
            } elseif ($cumulative_percentage <= 95) {
                $item['category'] = 'B';
            } else {
                $item['category'] = 'C';
            }
            
            $item['percentage'] = $percentage;
            $item['cumulative_percentage'] = $cumulative_percentage;
        }
        
        return $results;
    }

    /**
     * Get top selling medicines
     */
    public function get_top_selling_medicines($limit = 20) {
        $this->db->select('m.medicine_name, 
                          SUM(msi.quantity) as total_sold,
                          SUM(msi.total_amount) as revenue');
        $this->db->from('medicine_sale_items msi');
        $this->db->join('medicines m', 'msi.medicine_id = m.id');
        $this->db->join('medicine_sales ms', 'msi.sale_id = ms.id');
        $this->db->where('ms.sale_date >=', date('Y-m-d', strtotime('-30 days')));
        $this->db->group_by('m.id');
        $this->db->order_by('total_sold', 'DESC');
        $this->db->limit($limit);
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get sales by hour for today
     */
    public function get_hourly_sales_today() {
        $this->db->select('HOUR(sale_date) as hour, 
                          COUNT(*) as bill_count,
                          SUM(total_amount) as total_amount');
        $this->db->from('medicine_sales');
        $this->db->where('DATE(sale_date)', date('Y-m-d'));
        $this->db->group_by('HOUR(sale_date)');
        $this->db->order_by('hour', 'ASC');
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Cancel/Return sale
     */
    public function cancel_sale($sale_id, $reason) {
        $this->db->trans_start();
        
        try {
            // Get sale items
            $sale_items = $this->get_sale_items($sale_id);
            
            // Return stock
            foreach ($sale_items as $item) {
                $this->db->set('current_stock', 'current_stock + ' . $item['quantity'], FALSE);
                $this->db->where('id', $item['batch_id']);
                $this->db->update('medicine_batches');
            }
            
            // Update sale status
            $this->db->where('id', $sale_id);
            $this->db->update('medicine_sales', array(
                'payment_status' => 'cancelled',
                'remarks' => 'Cancelled: ' . $reason
            ));
            
            $this->db->trans_complete();
            
            if ($this->db->trans_status() === FALSE) {
                return array('status' => false, 'message' => 'Cancellation failed');
            }
            
            return array('status' => true, 'message' => 'Sale cancelled successfully');
            
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return array('status' => false, 'message' => $e->getMessage());
        }
    }
}
