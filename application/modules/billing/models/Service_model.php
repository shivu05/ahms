<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Service Model - Handle Billing Services & Categories
 */
class Service_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get all active service categories
     * @return array Categories
     */
    public function get_categories() {
        return $this->db->where('is_active', 1)
                        ->order_by('category_name', 'ASC')
                        ->get('billing_service_categories')
                        ->result_array();
    }

    /**
     * Get services by category
     * @param int $category_id
     * @return array Services
     */
    public function get_services_by_category($category_id) {
        return $this->db->where('category_id', $category_id)
                        ->where('is_active', 1)
                        ->order_by('service_name', 'ASC')
                        ->get('billing_services')
                        ->result_array();
    }

    /**
     * Get service details
     * @param int $service_id
     * @return array Service
     */
    public function get_service($service_id) {
        return $this->db->where('service_id', $service_id)
                        ->get('billing_services')
                        ->row_array();
    }

    /**
     * Search services
     * @param string $search_term
     * @return array Services
     */
    public function search_services($search_term) {
        return $this->db->where('is_active', 1)
                        ->group_start()
                        ->like('service_code', $search_term)
                        ->or_like('service_name', $search_term)
                        ->group_end()
                        ->limit(20)
                        ->get('billing_services')
                        ->result_array();
    }

    /**
     * Create service category
     * @param array $data
     * @return int Category ID
     */
    public function create_category($data) {
        $this->db->insert('billing_service_categories', $data);
        return $this->db->insert_id();
    }

    /**
     * Create service
     * @param array $data
     * @return int Service ID
     */
    public function create_service($data) {
        $this->db->insert('billing_services', $data);
        return $this->db->insert_id();
    }

    /**
     * Update service
     * @param int $service_id
     * @param array $data
     * @return bool
     */
    public function update_service($service_id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->where('service_id', $service_id)
                        ->update('billing_services', $data);
    }

    /**
     * Get all packages
     * @return array Packages
     */
    public function get_packages() {
        return $this->db->where('is_active', 1)
                        ->order_by('package_name', 'ASC')
                        ->get('billing_service_packages')
                        ->result_array();
    }

    /**
     * Get package details with services
     * @param int $package_id
     * @return array Package with services
     */
    public function get_package_details($package_id) {
        $package = $this->db->where('package_id', $package_id)
                           ->get('billing_service_packages')
                           ->row_array();
        
        if ($package) {
            $package['services'] = $this->db->select('ps.*, bs.service_name')
                                           ->from('billing_package_services ps')
                                           ->join('billing_services bs', 'ps.service_id = bs.service_id')
                                           ->where('ps.package_id', $package_id)
                                           ->get()
                                           ->result_array();
        }
        
        return $package;
    }

    /**
     * Create package
     * @param array $data
     * @return int Package ID
     */
    public function create_package($data) {
        $this->db->insert('billing_service_packages', $data);
        return $this->db->insert_id();
    }

    /**
     * Add services to package
     * @param int $package_id
     * @param array $services
     * @return bool
     */
    public function add_package_services($package_id, $services) {
        try {
            $this->db->trans_start();
            
            foreach ($services as $service) {
                $service['package_id'] = $package_id;
                $this->db->insert('billing_package_services', $service);
            }
            
            $this->db->trans_complete();
            return $this->db->trans_status();
        } catch (Exception $e) {
            log_message('error', 'Error adding package services: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get service pricing for invoice
     * @param int $service_id
     * @return array Service pricing details
     */
    public function get_service_pricing($service_id) {
        $service = $this->db->select('bs.*, bsc.gst_applicable, bsc.gst_rate')
                           ->from('billing_services bs')
                           ->join('billing_service_categories bsc', 'bs.category_id = bsc.category_id', 'left')
                           ->where('bs.service_id', $service_id)
                           ->get()
                           ->row_array();
        
        if ($service) {
            $service['calculated_gst'] = 0;
            if ($service['gst_applicable']) {
                $service['calculated_gst'] = ($service['unit_price'] * $service['gst_rate']) / 100;
            }
        }
        
        return $service;
    }

    /**
     * Apply discount calculation
     * @param float $amount
     * @param int $discount_id
     * @return array Discount details
     */
    public function calculate_discount($amount, $discount_id = null, $discount_code = null) {
        $discount = [];
        
        if ($discount_id) {
            $discount = $this->db->where('discount_id', $discount_id)
                                 ->where('is_active', 1)
                                 ->where('CURDATE() BETWEEN valid_from AND valid_to', NULL, FALSE)
                                 ->get('billing_discounts')
                                 ->row_array();
        } elseif ($discount_code) {
            $discount = $this->db->where('discount_code', $discount_code)
                                 ->where('is_active', 1)
                                 ->where('CURDATE() BETWEEN valid_from AND valid_to', NULL, FALSE)
                                 ->get('billing_discounts')
                                 ->row_array();
        }
        
        $result = [
            'discount_amount' => 0,
            'discount_applied' => false,
            'discount_id' => null
        ];
        
        if ($discount) {
            if ($discount['discount_type'] == 'PERCENTAGE') {
                $result['discount_amount'] = ($amount * $discount['discount_value']) / 100;
            } else {
                $result['discount_amount'] = $discount['discount_value'];
            }
            
            if (!empty($discount['maximum_discount_amount'])) {
                $result['discount_amount'] = min($result['discount_amount'], $discount['maximum_discount_amount']);
            }
            
            $result['discount_amount'] = min($result['discount_amount'], $amount);
            $result['discount_applied'] = true;
            $result['discount_id'] = $discount['discount_id'];
        }
        
        return $result;
    }

    /**
     * Validate service availability
     * @param int $service_id
     * @param string $invoice_type OPD/IPD
     * @return bool
     */
    public function is_service_available($service_id, $invoice_type = 'OPD') {
        $service = $this->db->where('service_id', $service_id)
                           ->where('is_active', 1)
                           ->get('billing_services')
                           ->row_array();
        
        if (!$service) {
            return false;
        }
        
        if ($invoice_type == 'OPD' && !$service['applicable_for_opd']) {
            return false;
        }
        
        if ($invoice_type == 'IPD' && !$service['applicable_for_ipd']) {
            return false;
        }
        
        return true;
    }
}
?>
