<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Panchakarma_model
 *
 * @author Shivaraj
 */
class Panchakarma_model extends CI_Model {

    private $_panchkarmaTable = "master_panchakarma_procedures";
    private $_panchkarmasubProcTable = "master_panchakarma_sub_procedures";

    public function get_procedures() {
        //return $this->db->order_by('id')->get($this->_panchkarmaTable)->result_array();
        $query = "SELECT mp.id,proc_name,GROUP_CONCAT(msp.id) as sub_ids,GROUP_CONCAT(sub_proc_name) as sub_grp_name,mp.last_modified_date 
            FROM master_panchakarma_procedures mp 
            LEFT JOIN master_panchakarma_sub_procedures msp ON mp.id=msp.procecure_id 
            GROUP BY proc_name";
        return $this->db->query($query)->result_array();
    }

    public function get_sub_procedures($name) {
        $this->db->from($this->_panchkarmasubProcTable . ' msp');
        $this->db->join($this->_panchkarmaTable . ' mp', 'mp.id=msp.procecure_id');
        $this->db->where('UPPER(mp.proc_name)', strtoupper($name));
        return $this->db->get()->result_array();
    }

    public function save_pancha_procedure($proc_name) {
        return $this->db->insert($this->_panchkarmaTable, array('proc_name' => $proc_name));
    }

}
