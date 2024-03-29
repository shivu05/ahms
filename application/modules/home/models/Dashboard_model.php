<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of dashboard_model
 *
 * @author Shivaraj
 */
class Dashboard_model extends CI_Model {

    function get_gender_wise_patients() {
        $where = '';
        if ($this->rbac->is_doctor()) {
            $where = " AND t.attndedby='" . $this->rbac->get_name() . "'";
        }
        $query = "SELECT COALESCE(SUM(case when p.gender='Male' then 1 else 0 end),0) males,
            COALESCE(SUM(case when p.gender='Female' then 1 else 0 end),0) females,
            count(*) total FROM patientdata p,treatmentdata t WHERE t.OpdNo = p.OpdNo and p.gender is not null $where;";
        return $this->db->query($query)->result_array();
    }

    function get_departmentwise_patient_count() {
        $result = $this->db->query("CALL get_patient_count()");
        $return = $result->result_array();
        mysqli_next_result($this->db->conn_id); //imp
        return $return;
    }
    
    function get_ipd_patients_count(){
        $result = $this->db->query("CALL get_ipd_patient_count()");
        $return = $result->result_array();
        mysqli_next_result($this->db->conn_id); //imp
        return $return;
    }

}
