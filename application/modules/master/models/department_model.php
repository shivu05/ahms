<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of department_model
 *
 * @author Shivaraj
 */
class department_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function get_departments() {
        return $this->db->get('deptper')->result_array();
    }

    function get_sub_departments($dept) {
        $this->db->from('sub_department s');
        $this->db->join('deptper d', 's.parent_dept_id=d.ID');
        $this->db->where('d.dept_unique_code', $dept);
        return $this->db->get()->result_array();
    }

    function get_doctors_by_dept($dept) {
        $this->db->distinct();
        $this->db->select('DISTINCT(doctorname)');
        return $this->db->get_where('doctors', array('doctortype' => $dept))->result_array();
    }

}
