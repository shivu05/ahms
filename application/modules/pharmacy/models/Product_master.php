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
class Product_master extends CI_Model {

    function get_product_info($where, $columns=NULL, $limit=1) {
        if (!empty($columns)) {
            $this->db->select('' . implode(',', $columns) . '');
        }
        $this->db->limit($limit);
        $this->db->where($where);
        return $this->db->get('product_master')->result_array();
    }

}

?>
