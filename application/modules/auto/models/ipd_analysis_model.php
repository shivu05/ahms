<?php

class Ipd_analysis_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function fetch_department_data()
    {
        $this->db->from('deptper');
        $query = $this->db->get();
        return $query->result_array();
    }
}