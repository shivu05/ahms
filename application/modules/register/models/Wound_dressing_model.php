<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Wound_dressing_model extends SHV_Model
{
    public function __construct()
    {
        parent::__construct();
    }


    public function get_all_records()
    {
        $query = $this->db->get('wound_dressing_opd_ipd_register');
        return $query->result();
    }

    public function create($data)
    {
        return $this->db->insert('wound_dressing_opd_ipd_register', $data);
    }

    public function get_record_by_id($id)
    {
        $query = $this->db->get_where('wound_dressing_opd_ipd_register', array('id' => $id));
        return $query->row();
    }

    public function update_record($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('wound_dressing_opd_ipd_register', $data);
    }

    public function delete_record($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('wound_dressing_opd_ipd_register');
    }
}
