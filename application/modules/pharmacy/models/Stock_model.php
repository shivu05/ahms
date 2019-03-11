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
        redirect('stick-entry');
    }

}

?>
