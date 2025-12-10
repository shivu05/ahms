<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Medicine Model - Medicine master data management
 */
class Medicine_model extends SHV_Model {

    protected $table = 'medicines';

    public function __construct() {
        parent::__construct();
    }

    /**
     * Get all medicines with pagination and search
     */
    public function get_medicines_datatables() {
        $this->_get_medicines_datatables_query();
        
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        
        $query = $this->db->get();
        return $query->result();
    }

    private function _get_medicines_datatables_query() {
        $this->db->select('m.*, 
                          mc.category_name, 
                          mm.company_name as manufacturer_name,
                          mu.unit_name,
                          COALESCE(SUM(mb.current_stock), 0) as total_stock');
        $this->db->from('medicines m');
        $this->db->join('medicine_categories mc', 'm.category_id = mc.id', 'left');
        $this->db->join('medicine_manufacturers mm', 'm.manufacturer_id = mm.id', 'left');
        $this->db->join('medicine_units mu', 'm.unit_id = mu.id', 'left');
        $this->db->join('medicine_batches mb', 'm.id = mb.medicine_id AND mb.status = "active"', 'left');
        $this->db->group_by('m.id');
        
        $search = $_POST['search']['value'];
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('m.medicine_name', $search);
            $this->db->or_like('m.generic_name', $search);
            $this->db->or_like('m.medicine_code', $search);
            $this->db->or_like('mc.category_name', $search);
            $this->db->or_like('mm.company_name', $search);
            $this->db->group_end();
        }
        
        if (isset($_POST['order'])) {
            $order_column = $_POST['order'][0]['column'];
            $order_dir = $_POST['order'][0]['dir'];
            $columns = array('m.medicine_name', 'mc.category_name', 'mm.company_name', 'total_stock');
            if (isset($columns[$order_column])) {
                $this->db->order_by($columns[$order_column], $order_dir);
            }
        } else {
            $this->db->order_by('m.medicine_name', 'ASC');
        }
    }

    public function count_all_medicines() {
        $this->db->where('status', 'active');
        return $this->db->count_all_results('medicines');
    }

    public function count_filtered_medicines() {
        $this->_get_medicines_datatables_query();
        return $this->db->count_all_results();
    }

    /**
     * Search medicines for POS
     */
    public function search_medicines_for_pos($search_term, $limit = 20) {
        $this->db->select('m.id, m.medicine_name, m.generic_name, m.strength, 
                          mc.category_name, mm.company_name,
                          COUNT(mb.id) as available_batches');
        $this->db->from('medicines m');
        $this->db->join('medicine_categories mc', 'm.category_id = mc.id', 'left');
        $this->db->join('medicine_manufacturers mm', 'm.manufacturer_id = mm.id', 'left');
        $this->db->join('medicine_batches mb', 'm.id = mb.medicine_id AND mb.current_stock > 0 AND mb.status = "active" AND mb.expiry_date > CURDATE()', 'left');
        
        $this->db->group_start();
        $this->db->like('m.medicine_name', $search_term);
        $this->db->or_like('m.generic_name', $search_term);
        $this->db->or_like('m.medicine_code', $search_term);
        $this->db->group_end();
        
        $this->db->where('m.status', 'active');
        $this->db->group_by('m.id');
        $this->db->having('available_batches >', 0);
        $this->db->order_by('m.medicine_name', 'ASC');
        $this->db->limit($limit);
        
        $query = $this->db->get();
        $results = $query->result_array();
        
        // Format for autocomplete
        $formatted_results = array();
        foreach ($results as $medicine) {
            $formatted_results[] = array(
                'id' => $medicine['id'],
                'label' => $medicine['medicine_name'] . ' - ' . $medicine['strength'] . ' (' . $medicine['company_name'] . ')',
                'value' => $medicine['medicine_name'],
                'medicine_name' => $medicine['medicine_name'],
                'generic_name' => $medicine['generic_name'],
                'strength' => $medicine['strength'],
                'category' => $medicine['category_name'],
                'manufacturer' => $medicine['company_name']
            );
        }
        
        return $formatted_results;
    }

    /**
     * Get medicine by ID
     */
    public function get_medicine_by_id($id) {
        $this->db->select('m.*, 
                          mc.category_name, 
                          mm.company_name as manufacturer_name,
                          mu.unit_name');
        $this->db->from('medicines m');
        $this->db->join('medicine_categories mc', 'm.category_id = mc.id', 'left');
        $this->db->join('medicine_manufacturers mm', 'm.manufacturer_id = mm.id', 'left');
        $this->db->join('medicine_units mu', 'm.unit_id = mu.id', 'left');
        $this->db->where('m.id', $id);
        
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * Add new medicine
     */
    public function add_medicine($data) {
        return $this->db->insert('medicines', $data);
    }

    /**
     * Update medicine
     */
    public function update_medicine($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('medicines', $data);
    }

    /**
     * Delete medicine
     */
    public function delete_medicine($id) {
        // Check if medicine has any stock or sales
        $this->db->where('medicine_id', $id);
        $batch_count = $this->db->count_all_results('medicine_batches');
        
        if ($batch_count > 0) {
            return array('status' => false, 'message' => 'Cannot delete medicine with existing batches');
        }
        
        $this->db->where('id', $id);
        return $this->db->delete('medicines');
    }

    /**
     * Generate medicine code
     */
    public function generate_medicine_code() {
        $prefix = 'MED';
        $year = date('y');
        
        $this->db->select('medicine_code');
        $this->db->from('medicines');
        $this->db->like('medicine_code', $prefix . $year, 'after');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            $last_code = $query->row()->medicine_code;
            $last_number = intval(substr($last_code, -4));
            $new_number = $last_number + 1;
        } else {
            $new_number = 1;
        }
        
        return $prefix . $year . str_pad($new_number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get categories
     */
    public function get_categories() {
        $this->db->where('status', 'active');
        $this->db->order_by('category_name', 'ASC');
        $query = $this->db->get('medicine_categories');
        return $query->result_array();
    }

    /**
     * Get manufacturers
     */
    public function get_manufacturers() {
        $this->db->select('id, company_name as manufacturer_name');
        $this->db->where('status', 'active');
        $this->db->order_by('company_name', 'ASC');
        $query = $this->db->get('medicine_manufacturers');
        return $query->result_array();
    }

    /**
     * Get units
     */
    public function get_units() {
        $this->db->where('status', 'active');
        $this->db->order_by('unit_name', 'ASC');
        $query = $this->db->get('medicine_units');
        return $query->result_array();
    }

    /**
     * Get all medicines for dropdown
     */
    public function get_all_medicines() {
        $this->db->select('id, medicine_name, generic_name, strength');
        $this->db->where('status', 'active');
        $this->db->order_by('medicine_name', 'ASC');
        $query = $this->db->get('medicines');
        return $query->result_array();
    }

    /**
     * Add category
     */
    public function add_category($data) {
        return $this->db->insert('medicine_categories', $data);
    }

    /**
     * Add manufacturer
     */
    public function add_manufacturer($data) {
        return $this->db->insert('medicine_manufacturers', $data);
    }

    /**
     * Add unit
     */
    public function add_unit($data) {
        return $this->db->insert('medicine_units', $data);
    }

    /**
     * Get drug information
     */
    public function get_drug_information($medicine_name) {
        $this->db->select('m.*, mc.category_name, mm.company_name');
        $this->db->from('medicines m');
        $this->db->join('medicine_categories mc', 'm.category_id = mc.id', 'left');
        $this->db->join('medicine_manufacturers mm', 'm.manufacturer_id = mm.id', 'left');
        $this->db->where('m.medicine_name', $medicine_name);
        $this->db->or_where('m.generic_name', $medicine_name);
        
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * Check medicine name exists
     */
    public function check_medicine_name_exists($medicine_name, $exclude_id = null) {
        $this->db->where('medicine_name', $medicine_name);
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        $query = $this->db->get('medicines');
        return $query->num_rows() > 0;
    }

    /**
     * Get medicine therapeutic alternatives
     */
    public function get_therapeutic_alternatives($medicine_id) {
        $medicine = $this->get_medicine_by_id($medicine_id);
        
        if (!$medicine || !$medicine['therapeutic_class']) {
            return array();
        }
        
        $this->db->select('m.*, mm.company_name');
        $this->db->from('medicines m');
        $this->db->join('medicine_manufacturers mm', 'm.manufacturer_id = mm.id', 'left');
        $this->db->where('m.therapeutic_class', $medicine['therapeutic_class']);
        $this->db->where('m.id !=', $medicine_id);
        $this->db->where('m.status', 'active');
        $this->db->order_by('m.medicine_name', 'ASC');
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get medicine statistics
     */
    public function get_medicine_statistics() {
        $stats = array();
        
        // Total medicines by category
        $this->db->select('mc.category_name, COUNT(m.id) as count');
        $this->db->from('medicines m');
        $this->db->join('medicine_categories mc', 'm.category_id = mc.id');
        $this->db->where('m.status', 'active');
        $this->db->group_by('mc.id');
        $this->db->order_by('count', 'DESC');
        $query = $this->db->get();
        $stats['by_category'] = $query->result_array();
        
        // Total medicines by manufacturer
        $this->db->select('mm.company_name, COUNT(m.id) as count');
        $this->db->from('medicines m');
        $this->db->join('medicine_manufacturers mm', 'm.manufacturer_id = mm.id');
        $this->db->where('m.status', 'active');
        $this->db->group_by('mm.id');
        $this->db->order_by('count', 'DESC');
        $this->db->limit(10);
        $query = $this->db->get();
        $stats['by_manufacturer'] = $query->result_array();
        
        return $stats;
    }
}
