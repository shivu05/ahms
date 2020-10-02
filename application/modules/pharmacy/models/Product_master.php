<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Product_master
 *
 * @author Shivaraj
 */
class Product_master extends SHV_Model {

    private $_table;

    function __construct() {
        parent::__construct();
        $this->_table = get_class($this);
    }

    function get_product_info($where, $columns = NULL, $limit = 1) {
        if (!empty($columns)) {
            $this->db->select('' . implode(',', $columns) . '');
        }
        $this->db->limit($limit);
        $this->db->where($where);
        return $this->db->get($this->_table)->result_array();
    }

    function fetch_products_list($columns = array('*')) {
        $this->db->select($columns);
        $this->db->from($this->_table . ' pm');
        $this->db->join('purchase_variables pv', 'pv.id=pm.product_master_id');
        $this->db->where('pv.type', 'product');
        return $this->db->get()->result_array();
    }

    function fetch_temp_purchase_data() {
        $columns = array(
            't.id','p.supplier_id','billno','date','refno','price','opbal','batch','pty','fty','prate','p.mrp','p.discount','p.vat','btype','total',
            'user_id','product_id','product_master_id','pv.name'
        );
        $user_id = $this->rbac->get_uid();
        $this->db->select($columns);
        $this->db->where('user_id', $user_id);
        $this->db->from('temp_purchase_entry t');
        $this->db->join('product_master p', 't.product=p.product_id');
        $this->db->join('purchase_variables pv', 'p.product_master_id=pv.id');
        $this->db->where('pv.type', 'product');
        return $this->db->get()->result_array();
    }

}

?>
