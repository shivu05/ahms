<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Inventory Model - Stock and inventory management
 */
class Inventory_model extends SHV_Model {

    protected $table = 'medicine_batches';

    public function __construct() {
        parent::__construct();
    }

    /**
     * Get inventory with datatables support
     */
    public function get_inventory_datatables() {
        $this->_get_inventory_datatables_query();
        
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        
        $query = $this->db->get();
        return $query->result();
    }

    private function _get_inventory_datatables_query() {
        $this->db->select('mb.*, 
                          m.medicine_name, 
                          m.generic_name,
                          mc.category_name,
                          mm.company_name as manufacturer_name,
                          mu.unit_name');
        $this->db->from('medicine_batches mb');
        $this->db->join('medicines m', 'mb.medicine_id = m.id');
        $this->db->join('medicine_categories mc', 'm.category_id = mc.id', 'left');
        $this->db->join('medicine_manufacturers mm', 'm.manufacturer_id = mm.id', 'left');
        $this->db->join('medicine_units mu', 'm.unit_id = mu.id', 'left');
        $this->db->where('mb.status !=', 'exhausted');
        
        $search = $_POST['search']['value'];
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('m.medicine_name', $search);
            $this->db->or_like('m.generic_name', $search);
            $this->db->or_like('mb.batch_number', $search);
            $this->db->or_like('mc.category_name', $search);
            $this->db->or_like('mm.company_name', $search);
            $this->db->group_end();
        }
        
        if (isset($_POST['order'])) {
            $order_column = $_POST['order'][0]['column'];
            $order_dir = $_POST['order'][0]['dir'];
            $columns = array('m.medicine_name', 'mb.batch_number', 'mb.expiry_date', 'mb.current_stock', 'mb.mrp');
            if (isset($columns[$order_column])) {
                $this->db->order_by($columns[$order_column], $order_dir);
            }
        } else {
            $this->db->order_by('m.medicine_name', 'ASC');
        }
    }

    public function count_all_inventory() {
        $this->db->from('medicine_batches mb');
        $this->db->join('medicines m', 'mb.medicine_id = m.id');
        $this->db->where('mb.status !=', 'exhausted');
        return $this->db->count_all_results();
    }

    public function count_filtered_inventory() {
        $this->_get_inventory_datatables_query();
        return $this->db->count_all_results();
    }

    /**
     * Get available batches for a medicine
     */
    public function get_available_batches($medicine_id) {
        $this->db->select('mb.*, m.medicine_name');
        $this->db->from('medicine_batches mb');
        $this->db->join('medicines m', 'mb.medicine_id = m.id');
        $this->db->where('mb.medicine_id', $medicine_id);
        $this->db->where('mb.current_stock >', 0);
        $this->db->where('mb.expiry_date >', date('Y-m-d'));
        $this->db->where('mb.status', 'active');
        $this->db->order_by('mb.expiry_date', 'ASC'); // FIFO
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get low stock count
     */
    public function get_low_stock_count() {
        $this->db->select('COUNT(*) as count');
        $this->db->from('medicine_batches');
        $this->db->where('current_stock <= minimum_stock');
        $this->db->where('status', 'active');
        
        $query = $this->db->get();
        return $query->row()->count;
    }

    /**
     * Get low stock medicines
     */
    public function get_low_stock_medicines() {
        $this->db->select('mb.*, 
                          m.medicine_name, 
                          m.reorder_level,
                          SUM(mb.current_stock) as total_stock');
        $this->db->from('medicine_batches mb');
        $this->db->join('medicines m', 'mb.medicine_id = m.id');
        $this->db->where('mb.status', 'active');
        $this->db->group_by('m.id');
        $this->db->having('total_stock <= m.reorder_level');
        $this->db->order_by('total_stock', 'ASC');
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get expired medicines count
     */
    public function get_expired_medicines_count() {
        $this->db->select('COUNT(*) as count');
        $this->db->from('medicine_batches');
        $this->db->where('expiry_date <', date('Y-m-d'));
        $this->db->where('current_stock >', 0);
        
        $query = $this->db->get();
        return $query->row()->count;
    }

    /**
     * Get medicines expiring soon
     */
    public function get_medicines_expiring_soon($days = 30) {
        $expiry_date = date('Y-m-d', strtotime('+' . $days . ' days'));
        
        $this->db->select('mb.*, 
                          m.medicine_name,
                          mc.category_name,
                          DATEDIFF(mb.expiry_date, CURDATE()) as days_to_expiry');
        $this->db->from('medicine_batches mb');
        $this->db->join('medicines m', 'mb.medicine_id = m.id');
        $this->db->join('medicine_categories mc', 'm.category_id = mc.id', 'left');
        $this->db->where('mb.expiry_date <=', $expiry_date);
        $this->db->where('mb.expiry_date >=', date('Y-m-d'));
        $this->db->where('mb.current_stock >', 0);
        $this->db->where('mb.status', 'active');
        $this->db->order_by('mb.expiry_date', 'ASC');
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Add new batch
     */
    public function add_batch($data) {
        return $this->db->insert('medicine_batches', $data);
    }

    /**
     * Update batch
     */
    public function update_batch($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('medicine_batches', $data);
    }

    /**
     * Adjust stock
     */
    public function adjust_stock($batch_id, $adjustment_qty, $adjustment_type, $reason) {
        $this->db->trans_start();
        
        try {
            // Get current batch details
            $batch = $this->get_batch_by_id($batch_id);
            if (!$batch) {
                throw new Exception('Batch not found');
            }
            
            // Calculate new stock
            if ($adjustment_type == 'stock_in') {
                $new_stock = $batch['current_stock'] + $adjustment_qty;
            } else {
                $new_stock = $batch['current_stock'] - $adjustment_qty;
                if ($new_stock < 0) {
                    throw new Exception('Insufficient stock for adjustment');
                }
            }
            
            // Update batch stock
            $this->db->where('id', $batch_id);
            $this->db->update('medicine_batches', array('current_stock' => $new_stock));
            
            // Create adjustment record
            $adjustment_data = array(
                'adjustment_number' => $this->generate_adjustment_number(),
                'adjustment_date' => date('Y-m-d'),
                'adjustment_type' => $adjustment_type,
                'total_value' => abs($adjustment_qty) * $batch['purchase_price'],
                'reason' => $reason,
                'created_by' => $this->get_current_user_id()
            );
            
            $this->db->insert('medicine_stock_adjustments', $adjustment_data);
            $adjustment_id = $this->db->insert_id();
            
            // Create adjustment item
            $adjustment_item = array(
                'adjustment_id' => $adjustment_id,
                'medicine_id' => $batch['medicine_id'],
                'batch_id' => $batch_id,
                'adjustment_qty' => ($adjustment_type == 'stock_in') ? $adjustment_qty : -$adjustment_qty,
                'reason' => $reason
            );
            
            $this->db->insert('medicine_stock_adjustment_items', $adjustment_item);
            
            $this->db->trans_complete();
            
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Stock adjustment failed');
            }
            
            return array('status' => true, 'message' => 'Stock adjusted successfully');
            
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return array('status' => false, 'message' => $e->getMessage());
        }
    }

    /**
     * Get batch by ID
     */
    public function get_batch_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('medicine_batches');
        return $query->row_array();
    }

    /**
     * Generate adjustment number
     */
    private function generate_adjustment_number() {
        $prefix = 'ADJ' . date('Ymd');
        
        $this->db->select('adjustment_number');
        $this->db->from('medicine_stock_adjustments');
        $this->db->like('adjustment_number', $prefix, 'after');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            $last_adjustment = $query->row()->adjustment_number;
            $last_number = intval(substr($last_adjustment, -4));
            $new_number = $last_number + 1;
        } else {
            $new_number = 1;
        }
        
        return $prefix . str_pad($new_number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get current user ID
     */
    private function get_current_user_id() {
        // Get from your authentication system
        $CI =& get_instance();
        if (isset($CI->rbac)) {
            return $CI->rbac->get_uid();
        }
        return 1; // Default
    }

    /**
     * Get stock movements
     */
    public function get_stock_movements($medicine_id = null, $from_date = null, $to_date = null) {
        $this->db->select('
            "Sale" as movement_type,
            ms.sale_date as movement_date,
            ms.bill_number as reference_number,
            -msi.quantity as quantity_change,
            msi.unit_price,
            m.medicine_name,
            mb.batch_number
        ');
        $this->db->from('medicine_sale_items msi');
        $this->db->join('medicine_sales ms', 'msi.sale_id = ms.id');
        $this->db->join('medicines m', 'msi.medicine_id = m.id');
        $this->db->join('medicine_batches mb', 'msi.batch_id = mb.id');
        
        if ($medicine_id) {
            $this->db->where('msi.medicine_id', $medicine_id);
        }
        
        if ($from_date) {
            $this->db->where('DATE(ms.sale_date) >=', $from_date);
        }
        
        if ($to_date) {
            $this->db->where('DATE(ms.sale_date) <=', $to_date);
        }
        
        $sales_query = $this->db->get_compiled_select();
        
        // Union with purchase movements
        $this->db->select('
            "Purchase" as movement_type,
            mp.purchase_date as movement_date,
            mp.invoice_number as reference_number,
            mpi.quantity as quantity_change,
            mpi.unit_purchase_price as unit_price,
            m.medicine_name,
            mpi.batch_number
        ');
        $this->db->from('medicine_purchase_items mpi');
        $this->db->join('medicine_purchases mp', 'mpi.purchase_id = mp.id');
        $this->db->join('medicines m', 'mpi.medicine_id = m.id');
        
        if ($medicine_id) {
            $this->db->where('mpi.medicine_id', $medicine_id);
        }
        
        if ($from_date) {
            $this->db->where('DATE(mp.purchase_date) >=', $from_date);
        }
        
        if ($to_date) {
            $this->db->where('DATE(mp.purchase_date) <=', $to_date);
        }
        
        $purchase_query = $this->db->get_compiled_select();
        
        // Union with adjustments
        $this->db->select('
            CONCAT("Adjustment - ", msa.adjustment_type) as movement_type,
            msa.adjustment_date as movement_date,
            msa.adjustment_number as reference_number,
            msai.adjustment_qty as quantity_change,
            0 as unit_price,
            m.medicine_name,
            mb.batch_number
        ');
        $this->db->from('medicine_stock_adjustment_items msai');
        $this->db->join('medicine_stock_adjustments msa', 'msai.adjustment_id = msa.id');
        $this->db->join('medicines m', 'msai.medicine_id = m.id');
        $this->db->join('medicine_batches mb', 'msai.batch_id = mb.id');
        
        if ($medicine_id) {
            $this->db->where('msai.medicine_id', $medicine_id);
        }
        
        if ($from_date) {
            $this->db->where('msa.adjustment_date >=', $from_date);
        }
        
        if ($to_date) {
            $this->db->where('msa.adjustment_date <=', $to_date);
        }
        
        $adjustment_query = $this->db->get_compiled_select();
        
        // Combine all queries
        $final_query = "($sales_query) UNION ALL ($purchase_query) UNION ALL ($adjustment_query) ORDER BY movement_date DESC";
        
        $query = $this->db->query($final_query);
        return $query->result_array();
    }

    /**
     * Get stock valuation
     */
    public function get_stock_valuation() {
        $this->db->select('
            m.medicine_name,
            mc.category_name,
            SUM(mb.current_stock) as total_stock,
            SUM(mb.current_stock * mb.purchase_price) as cost_value,
            SUM(mb.current_stock * mb.selling_price) as selling_value,
            COUNT(mb.id) as batch_count
        ');
        $this->db->from('medicines m');
        $this->db->join('medicine_categories mc', 'm.category_id = mc.id', 'left');
        $this->db->join('medicine_batches mb', 'm.id = mb.medicine_id AND mb.current_stock > 0 AND mb.status = "active"', 'left');
        $this->db->group_by('m.id');
        $this->db->having('total_stock >', 0);
        $this->db->order_by('selling_value', 'DESC');
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Update expired batches status
     */
    public function update_expired_batches() {
        $this->db->where('expiry_date <', date('Y-m-d'));
        $this->db->where('status', 'active');
        return $this->db->update('medicine_batches', array('status' => 'expired'));
    }

    /**
     * Get batch-wise stock report
     */
    public function get_batch_wise_stock() {
        $this->db->select('
            m.medicine_name,
            mb.batch_number,
            mb.expiry_date,
            mb.current_stock,
            mb.minimum_stock,
            mb.mrp,
            mb.purchase_price,
            (mb.current_stock * mb.purchase_price) as stock_value,
            DATEDIFF(mb.expiry_date, CURDATE()) as days_to_expiry,
            CASE 
                WHEN mb.expiry_date < CURDATE() THEN "Expired"
                WHEN DATEDIFF(mb.expiry_date, CURDATE()) <= 30 THEN "Expiring Soon"
                WHEN mb.current_stock <= mb.minimum_stock THEN "Low Stock"
                ELSE "Active"
            END as status_label
        ');
        $this->db->from('medicine_batches mb');
        $this->db->join('medicines m', 'mb.medicine_id = m.id');
        $this->db->where('mb.current_stock >', 0);
        $this->db->order_by('m.medicine_name, mb.expiry_date');
        
        $query = $this->db->get();
        return $query->result_array();
    }
}
