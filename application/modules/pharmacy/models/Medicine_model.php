<?php

class Medicine_model extends CI_Model {

    public function get_medicines() {
        $query = $this->db->get('medicines');
        return $query->result();
    }

    public function add_medicine($data) {
        return $this->db->insert('medicines', $data);
    }

    public function get_medicine($id) {
        $query = $this->db->get_where('medicines', array('id' => $id));
        return $query->row();
    }

    public function update_medicine($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('medicines', $data);
    }

    public function delete_medicine($id) {
        $this->db->where('id', $id);
        return $this->db->delete('medicines');
    }
}
