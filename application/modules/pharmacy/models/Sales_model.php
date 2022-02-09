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

}
