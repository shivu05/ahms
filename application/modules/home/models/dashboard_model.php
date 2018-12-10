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
        $query = "SELECT SUM(case when p.gender='Male' then 1 else 0 end) males,SUM(case when p.gender='Female' then 1 else 0 end) females,
            count(*) total FROM patientdata p,treatmentdata t WHERE t.OpdNo = p.OpdNo;";
        return $this->db->query($query)->result_array();
    }

    function get_departmentwise_patient_count() {
        $result = $this->db->query("CALL get_patient_count()");
        $return = $result->result_array();
        mysqli_next_result($this->db->conn_id); //imp
        return $return;
    }

}
