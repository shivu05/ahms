<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Sales_model
 *
 * @author Abhilasha
 */
class Sales_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_patient_data($opd = null) {
        if ($opd) {
            $return['data'] = $this->db->select("CONCAT(p.FirstName,p.LastName) as name,p.Age,p.gender,p.city,t.OpdNo,GROUP_CONCAT(t.CameOn) as CameOn,GROUP_CONCAT(t.ID) as treat_id,
                ,GROUP_CONCAT(t.diagnosis SEPARATOR '##') as diagnosis,GROUP_CONCAT(t.Trtment SEPARATOR '##') as treatment", FALSE)
                            ->from('treatmentdata t')
                            ->join('patientdata p', 't.OpdNo=p.OpdNo')
                            ->where('t.OpdNo', $opd)->get()->row_array();
            $return['products_list'] = $this->db->query("select st.id as st_id,pv1.name,prod.product_id,cstock,sale_rate FROM product_master prod 
                JOIN purchase_variables pv1 on prod.product_master_id=pv1.id 
                JOIN stock st on prod.product_id=st.product")->result_array();
            return $return;
        }
        return null;
    }

    public function get_product_list() {
        return $this->db->query("select st.id as st_id,pv1.name,prod.product_id,cstock,sale_rate,CASE WHEN cstock>0 THEN 'y' ELSE 'n' END current_stock 
            FROM product_master prod 
            JOIN purchase_variables pv1 on prod.product_master_id=pv1.id 
            JOIN stock st on prod.product_id=st.product")->result_array();
    }

    public function update_stock($post_values) {
        if ($post_values) {
            $sales_count = (int) $post_values['qty'];
            $sub_toal = (int) $post_values['sub_total'];
            $query = "UPDATE stock SET cstock= (cstock - $sales_count),amount = (amount - $sub_toal ) where product='" . $post_values['product_id'] . "'";
            $is_inserted = $this->db->query($query);
            //echo $this->db->last_query();exit;
            if ($is_inserted) {
                $product_query = "select product_id,product_master_id,pv.name product_name,product_batch
                    from product_master p 
                    join purchase_variables pv on p.product_master_id=pv.id 
                    where product_id='" . $post_values['product_id'] . "'";
                $row_data = $this->db->query($product_query)->row_array();
                $insert_arr = array(
                    'billno' => $post_values['billno'],
                    'opdno' => $post_values['opd_no'],
                    'treat_id' => $post_values['treat_id'],
                    'product' => $row_data['product_name'],
                    'batch' => $row_data['product_batch'],
                    'qty' => $post_values['qty'],
                    'date' => $post_values['date'],
                    'price' => $post_values['unit_price']
                );
                $is_inserted_p = $this->db->insert('sales_entry', $insert_arr);
                if ($is_inserted_p) {
                    return true;
                } else {
                    return false;
                }
            }
        }
        return false;
    }

    public function get_sales() {
        $this->db->select('sales.*, medicines.name as medicine_name, customers.name as customer_name');
        $this->db->from('sales');
        $this->db->join('medicines', 'sales.medicine_id = medicines.id');
        $this->db->join('customers', 'sales.customer_id = customers.id');
        $query = $this->db->get();
        return $query->result();
    }

    public function add_sale($data) {
        return $this->db->insert('sales', $data);
    }
}
