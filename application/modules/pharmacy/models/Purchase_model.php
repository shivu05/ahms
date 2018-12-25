<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Purchase_model extends CI_Model {

    function get_purchase_variables($conditions, $export_flag = false) {
        $return = array();
        $columns = array('id', 'type', 'name', 'extrainfo');
        $where_cond = " WHERE name is NOT NULL ";
        $limit = '';
        if (!$export_flag) {
            $start = (isset($conditions['start'])) ? $conditions['start'] : 0;
            $length = (isset($conditions['length'])) ? $conditions['length'] : 25;
            $limit = ' LIMIT ' . $start . ',' . ($length);
            unset($conditions['start'], $conditions['length'], $conditions['order']);
        }

        foreach ($conditions as $col => $val) {
            $val = trim($val);
            if ($val !== '') {
                switch ($col):
                    case 'type':
                        $where_cond .= " AND LOWER(type)=LOWER('$val')";
                        break;
                    case 'name':
                        $where_cond .= " AND name LIKE '%$val%'";
                /* default:
                  $where_cond .= " AND $col = '$val'"; */
                endswitch;
            }
        }

        $query = "SELECT @a:=@a+1 serial_number, " . join(',', $columns) . " FROM purchase_variables, (SELECT @a:= 0) AS a $where_cond";
        $result = $this->db->query($query . ' ' . $limit);

        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query("SELECT * FROM purchase_variables")->num_rows();
        return $return;
    }

    function get_purchase_types_master() {
        return $this->db->get('purchase_types_master')->result_array();
    }

    function save_purchase_type_master($post_data) {
        return $this->db->insert('purchase_variables', $post_data);
    }

    function get_purchase_items($type = '') {
        $this->db->where('type', $type);
        return $this->db->get('purchase_variables')->result_array();
    }

    function save_product($post_values) {
        return $this->db->insert('product_details', $post_values);
    }

    function get_temp_products_return_data($conditions, $export_flag = false) {

        $return = array();
        $columns = array(
            'billno', 'date', 'refno', 'price', 'opbal', 'product', 'batch', 'prate', 'mrp', 'discount'
        );
        $where_cond = " WHERE 1=1 ";
        $limit = '';
        if (!$export_flag) {
            $start = (isset($conditions['start'])) ? $conditions['start'] : 0;
            $length = (isset($conditions['length'])) ? $conditions['length'] : 25;
            $limit = ' LIMIT ' . $start . ',' . ($length);
            unset($conditions['start'], $conditions['length'], $conditions['order']);
        }

        foreach ($conditions as $col => $val) {
            $val = trim($val);
            if ($val !== '') {
                switch ($col):
                    default:
                        $where_cond .= " AND $col = '$val'";
                endswitch;
            }
        }

        $query = "SELECT @a:=@a+1 serial_number, " . join(',', $columns) . " FROM purchase_return_temp,(SELECT @a:= 0) AS a $where_cond";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->count_all('purchase_return_temp');
        return $return;
    }

    function get_stock_variables($column = null) {
        if ($column) {
            $this->db->distinct();
            $this->db->select($column);
            return $this->db->get('stock')->result_array();
        }
        return false;
    }

    function get_products_by_supplier($supplies_id = NULL) {
        $this->db->from('product_details pd');
        $this->db->join('purchase_variables pv', 'pd.product_id=pv.id');
        if ($supplies_id) {
            $this->db->where('supplier', $supplies_id);
        }
        return $this->db->get()->result_array();
    }

    function get_product_variables($column = null) {
        if ($column) {
            $this->db->distinct();
            $this->db->select($column);
            return $this->db->get('product_details')->result_array();
        }
        return false;
    }

    function get_product_info($product_id, $batch) {
        $this->db->where('product_id', $product_id);
        $this->db->where('batch', $batch);
        return $this->db->get('product_details')->result_array();
    }

}
