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

    function get_doctors_info($doc_id = NULL) {
        if ($doc_id != NULL) {
            $this->db->where('d.id', $doc_id);
        }
        $this->db->select('d.id as doc_id,d.doctortype,d.doctorname,dt.doc_id,dt.day,dt.added_date,w.*');
        $this->db->from('doctors d');
        $this->db->join('doctorsduty dt', 'd.id=dt.doc_id');
        $this->db->join('week_days w', 'w.week_id=dt.day');
        $this->db->order_by('d.doctortype,dt.day', 'asc');
        $result = $this->db->get();
        return $result->result_array();
    }

}
