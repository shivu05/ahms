<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Pharmacy Model - Core pharmacy operations
 */
class Pharmacy_model extends SHV_Model {

    protected $medicine_table = 'medicines';
    protected $batches_table = 'medicine_batches';
    protected $sales_table = 'medicine_sales';
    protected $categories_table = 'medicine_categories';

    public function __construct() {
        parent::__construct();
    }

    /**
     * Get medicine frequencies
     */
    public function get_medicine_frequencies() {
        $this->db->where('status', 'active');
        $query = $this->db->get('medicine_frequencies');
        return $query->result_array();
    }

    /**
     * Get patient details by ID and type
     */
    public function get_patient_details($patient_id, $patient_type = 'opd') {
        if ($patient_type == 'opd') {
            $this->db->select('pd.OpdNo as patient_id, CONCAT_WS(" ", pd.FirstName, pd.LastName) as name, pd.mob as phone, pd.Age as age, pd.gender');
            $this->db->from('patientdata pd');
            $this->db->where('pd.OpdNo', $patient_id);
        } else {
            $this->db->select('ipd.IpNo as patient_id, ipd.FName as name, "" as phone, ipd.Age as age, ipd.Gender as gender');
            $this->db->from('inpatientdetails ipd');
            $this->db->where('ipd.IpNo', $patient_id);
        }
        
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * Get prescriptions with datatables support
     */
    public function get_prescriptions_datatables() {
        $this->_get_prescriptions_datatables_query();
        
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        
        $query = $this->db->get();
        return $query->result();
    }

    private function _get_prescriptions_datatables_query() {
        $this->db->select('p.*, 
                          COALESCE(
                              CONCAT_WS(" ", pd1.FirstName, pd1.LastName), 
                              ipd.FName
                          ) as patient_name,
                          u.user_name as doctor_name', FALSE);
        $this->db->from('prescriptions p');
        $this->db->join('patientdata pd1', 'p.patient_id = pd1.OpdNo AND p.patient_type = "opd"', 'left');
        $this->db->join('inpatientdetails ipd', 'p.patient_id = ipd.IpNo AND p.patient_type = "ipd"', 'left');
        $this->db->join('users u', 'p.doctor_id = u.ID', 'left');
        
        $search = $_POST['search']['value'];
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('p.prescription_number', $search);
            $this->db->or_like('CONCAT_WS(" ", pd1.FirstName, pd1.LastName)', $search, FALSE);
            $this->db->or_like('ipd.FName', $search);
            $this->db->or_like('u.user_name', $search);
            $this->db->group_end();
        }
        
        $this->db->order_by('p.prescription_date', 'DESC');
    }

    public function count_all_prescriptions() {
        $this->db->from('prescriptions');
        return $this->db->count_all_results();
    }

    public function count_filtered_prescriptions() {
        $this->_get_prescriptions_datatables_query();
        return $this->db->count_all_results();
    }

    /**
     * Get dashboard statistics
     */
    public function get_dashboard_stats() {
        $stats = array();
        
        // Total medicines count
        $this->db->where('status', 'active');
        $stats['total_medicines'] = $this->db->count_all_results('medicines');
        
        // Low stock medicines
        $this->db->select('COUNT(*) as count');
        $this->db->from('medicine_batches mb');
        $this->db->join('medicines m', 'mb.medicine_id = m.id');
        $this->db->where('mb.current_stock <= mb.minimum_stock');
        $this->db->where('mb.status', 'active');
        $query = $this->db->get();
        $stats['low_stock'] = $query->row()->count;
        
        // Expired medicines
        $this->db->select('COUNT(*) as count');
        $this->db->from('medicine_batches');
        $this->db->where('expiry_date <', date('Y-m-d'));
        $this->db->where('current_stock >', 0);
        $query = $this->db->get();
        $stats['expired'] = $query->row()->count;
        
        // Today's sales
        $this->db->select('COALESCE(SUM(total_amount), 0) as total');
        $this->db->from('medicine_sales');
        $this->db->where('DATE(sale_date)', date('Y-m-d'));
        $query = $this->db->get();
        $stats['todays_sales'] = $query->row()->total;
        
        // This month's sales
        $this->db->select('COALESCE(SUM(total_amount), 0) as total');
        $this->db->from('medicine_sales');
        $this->db->where('MONTH(sale_date)', date('m'));
        $this->db->where('YEAR(sale_date)', date('Y'));
        $query = $this->db->get();
        $stats['monthly_sales'] = $query->row()->total;
        
        return $stats;
    }

    /**
     * Get recent sales for dashboard
     */
    public function get_recent_sales($limit = 10) {
        $this->db->select('ms.*, 
                          COALESCE(
                              CONCAT_WS(" ", pd1.FirstName, pd1.LastName), 
                              ipd.FName, 
                              ms.patient_name
                          ) as patient_name', FALSE);
        $this->db->from('medicine_sales ms');
        $this->db->join('patientdata pd1', 'ms.patient_id = pd1.OpdNo AND ms.patient_type = "opd"', 'left');
        $this->db->join('inpatientdetails ipd', 'ms.patient_id = ipd.IpNo AND ms.patient_type = "ipd"', 'left');
        $this->db->order_by('ms.sale_date', 'DESC');
        $this->db->limit($limit);
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Generate next bill number
     */
    public function generate_bill_number() {
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
     * Generate prescription number
     */
    public function generate_prescription_number() {
        $prefix = 'RX' . date('Ymd');
        
        $this->db->select('prescription_number');
        $this->db->from('prescriptions');
        $this->db->like('prescription_number', $prefix, 'after');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            $last_prescription = $query->row()->prescription_number;
            $last_number = intval(substr($last_prescription, -4));
            $new_number = $last_number + 1;
        } else {
            $new_number = 1;
        }
        
        return $prefix . str_pad($new_number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get medicine interactions
     */
    public function get_medicine_interactions($medicine_ids) {
        if (empty($medicine_ids)) return array();
        
        $this->db->select('m1.medicine_name as medicine1, m2.medicine_name as medicine2, 
                          "Potential drug interaction - consult pharmacist" as interaction_note');
        $this->db->from('medicines m1');
        $this->db->join('medicines m2', 'm1.therapeutic_class = m2.therapeutic_class AND m1.id != m2.id');
        $this->db->where_in('m1.id', $medicine_ids);
        $this->db->where_in('m2.id', $medicine_ids);
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Create data backup
     */
    public function create_data_backup() {
        try {
            $backup_date = date('Y-m-d_H-i-s');
            $backup_tables = array(
                'medicines', 'medicine_batches', 'medicine_sales', 'medicine_sale_items',
                'medicine_purchases', 'medicine_purchase_items', 'prescriptions', 'prescription_items'
            );
            
            $backup_path = FCPATH . 'backups/pharmacy/';
            
            if (!is_dir($backup_path)) {
                mkdir($backup_path, 0777, true);
            }
            
            $backup_file = $backup_path . 'pharmacy_backup_' . $backup_date . '.sql';
            
            // Create backup using mysqldump
            $command = "mysqldump --user=" . DB_USER . " --password=" . DB_PASS . " --host=" . DB_HOST . " " . DB_NAME . " " . implode(' ', $backup_tables) . " > " . $backup_file;
            
            exec($command, $output, $return_code);
            
            if ($return_code === 0) {
                return array('status' => true, 'message' => 'Backup created successfully', 'file' => $backup_file);
            } else {
                return array('status' => false, 'message' => 'Backup failed');
            }
            
        } catch (Exception $e) {
            return array('status' => false, 'message' => $e->getMessage());
        }
    }

    /**
     * Get sales summary for reports
     */
    public function get_sales_summary($from_date, $to_date) {
        $this->db->select('
            DATE(sale_date) as sale_date,
            COUNT(*) as total_bills,
            SUM(total_amount) as total_sales,
            SUM(discount_amount) as total_discount,
            AVG(total_amount) as average_bill_amount
        ');
        $this->db->from('medicine_sales');
        $this->db->where('DATE(sale_date) >=', $from_date);
        $this->db->where('DATE(sale_date) <=', $to_date);
        $this->db->group_by('DATE(sale_date)');
        $this->db->order_by('sale_date', 'ASC');
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get inventory valuation
     */
    public function get_inventory_valuation() {
        $this->db->select('
            m.medicine_name,
            mb.batch_number,
            mb.current_stock,
            mb.purchase_price,
            mb.selling_price,
            (mb.current_stock * mb.purchase_price) as stock_value_cost,
            (mb.current_stock * mb.selling_price) as stock_value_selling
        ');
        $this->db->from('medicine_batches mb');
        $this->db->join('medicines m', 'mb.medicine_id = m.id');
        $this->db->where('mb.current_stock >', 0);
        $this->db->where('mb.status', 'active');
        
        $query = $this->db->get();
        return $query->result_array();
    }
}
