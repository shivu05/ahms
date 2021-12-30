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
            return $this->db->select("CONCAT(p.FirstName,p.LastName) as name,p.Age,p.gender,p.city,t.OpdNo,GROUP_CONCAT(t.CameOn) as CameOn,GROUP_CONCAT(t.ID) as treat_id,
                ,GROUP_CONCAT(t.diagnosis SEPARATOR '##') as diagnosis,GROUP_CONCAT(t.Trtment SEPARATOR '##') as treatment", FALSE)
                            ->from('treatmentdata t')
                            ->join('patientdata p', 't.OpdNo=p.OpdNo')
                            ->where('t.OpdNo', $opd)->get()->row_array();
        }
        return null;
    }

}
