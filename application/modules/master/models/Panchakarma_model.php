<?php

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
        $query = "SELECT mp.id,proc_name,GROUP_CONCAT(msp.id) as sub_ids,GROUP_CONCAT(sub_proc_name) as sub_grp_name,
            GROUP_CONCAT(no_of_treatment_days) as tdays,mp.last_modified_date 
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

    public function save_sub_pancha_procedure($input_arr) {
        return $this->db->insert($this->_panchkarmasubProcTable, $input_arr);
    }

    public function update_sub_procedure($input_arr, $where) {
        $this->db->where($where);
        return $this->db->update($this->_panchkarmasubProcTable, $input_arr);
    }

}
