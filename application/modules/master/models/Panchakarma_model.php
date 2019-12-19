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
        return $this->db->order_by('id')->get($this->_panchkarmaTable)->result_array();
    }

    public function get_sub_procedures($name) {
        $this->db->from($this->_panchkarmasubProcTable . ' msp');
        $this->db->join($this->_panchkarmaTable. ' mp', 'mp.id=msp.procecure_id');
        $this->db->where('UPPER(mp.proc_name)', strtoupper($name));
        return $this->db->get()->result_array();
    }

}
