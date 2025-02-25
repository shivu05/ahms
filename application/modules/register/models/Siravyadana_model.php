<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Siravyadana_model extends CI_Model
{
    public function get_all_records()
    {
        $query = $this->db->get('siravyadana_opd_ipd_register');
        return $query->result();
    }

    public function create($data)
    {
        return $this->db->insert('siravyadana_opd_ipd_register', $data);
    }

    public function get_record_by_id($id)
    {
        $query = $this->db->get_where('siravyadana_opd_ipd_register', array('id' => $id));
        return $query->row();
    }

    public function update_record($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('siravyadana_opd_ipd_register', $data);
    }

    public function delete_record($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('siravyadana_opd_ipd_register');
    }
}