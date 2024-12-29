<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Service_group_model extends CI_Model {

    // Fetch all groups
    public function get_all_groups() {
        return $this->db->get('service_groups')->result_array();
    }

    // Fetch a single group by ID
    public function get_group_by_id($group_id) {
        return $this->db->get_where('service_groups', ['group_id' => $group_id])->row_array();
    }

    // Insert new group
    public function insert_group($data) {
        $this->db->insert('service_groups', $data);
    }

    // Update group
    public function update_group($group_id, $data) {
        $this->db->where('group_id', $group_id);
        $this->db->update('service_groups', $data);
    }

    // Delete group
    public function delete_group($group_id) {
        $this->db->delete('service_groups', ['group_id' => $group_id]);
    }
}
