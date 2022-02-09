<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Stock_model
 *
 * @author Shivaraj
 */
class Stock_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function get_batch_no($product_id) {
        $this->db->where('product_id', $product_id);
        $this->db->limit(1);
        return $this->db->get('product_master')->row_array();
    }

    function save_stock($post_values) {
        $i = 0;
        $form_data = array(
            'billno' => $post_values['bill_no'],
            'supplier_id' => $post_values['supplier'],
            'refno' => $post_values['reference_no'],
            'price' => $post_values['price'],
            'opbal' => $post_values['op_bal'],
            'product' => $post_values['products'][$i],
            'batch' => $post_values['batch'][$i],
            'pty' => $post_values['p_qty'][$i],
            'fty' => $post_values['f_qty'][$i],
            'prate' => $post_values['p_rate'][$i],
            'mrp' => $post_values['mrp'][$i],
            'discount' => $post_values['discount'][$i],
            'vat' => $post_values['vat'][$i],
            'btype' => $post_values['btype'][$i],
            'total' => $post_values['total'][$i],
            'date' => $post_values['date'],
            'user_id' => 1,
        );

        $this->db->insert('purchase_entry', $form_data);

        $cstock = ($post_values['p_qty'][$i]) + $post_values['f_qty'][$i];
        $stock_data = array(
            'billno' => $post_values['bill_no'],
            'supplier_id' => $post_values['supplier'],
            'batchno' => $post_values['batch'][$i],
            'cstock' => $cstock,
            'price' => $post_values['mrp'][$i],
            'amount' => $post_values['total'][$i],
            'purchasetype' => 'purchase',
            'refno' => $post_values['reference_no'],
            'date' => $post_values['date'],
            'vat' => $post_values['vat'][$i],
            'product_id' => $post_values['products'][$i],
        );
        $this->db->insert('stock', $stock_data);
        redirect('stock-entry');
    }

    function get_stock_list($conditions, $export_flag = false) {
        $return = array();
        $columns = array('p.id as stock_id', 'supplier_id', 'product', 'batchno', 'mhf', 'pack', 'cstock', 'expdate', 'price', 'amount', 'dmonths', 'billno', 'purchasetype', 'vat', 'refno',
            'date', 'pgroup', 'category', 'product_id', 'status','pv1.name as product_name', 'pv2.name as supplier_name');
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

        $query = "SELECT @a:=@a+1 serial_number, " . join(',', $columns) . " FROM stock p
            join purchase_variables pv1 on p.product=pv1.id
            join purchase_variables pv2 on p.supplier_id=pv2.id
            ,(SELECT @a:= 0) AS a $where_cond";

        
        $result = $this->db->query($query . ' ' . $limit);

        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query("SELECT * FROM stock")->num_rows();
        return $return;
    }

}

?>
