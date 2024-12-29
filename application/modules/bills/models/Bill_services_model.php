<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Bill_services_model extends CI_Model {

    // Fetch all services with their group names
    public function get_all_services() {
        $this->db->select('bill_services.*, service_groups.group_name');
        $this->db->from('bill_services');
        $this->db->join('service_groups', 'bill_services.group_id = service_groups.group_id');
        return $this->db->get()->result_array();
    }

    public function get_service_groups() {
        $this->db->select('*');
        $this->db->from('service_groups');
        return $this->db->get()->result_array();
    }

    // Fetch single service by ID
    public function get_service_by_id($service_id) {
        return $this->db->get_where('bill_services', ['service_id' => $service_id])->row_array();
    }

    public function get_services_by_group($group_id) {
        $this->db->select('service_id, service_name,price');
        $this->db->where('group_id', $group_id);
        return $this->db->get('bill_services')->result_array();
    }

    // Insert new service
    public function insert_service($data) {
        $this->db->insert('bill_services', $data);
    }

    // Update existing service
    public function update_service($service_id, $data) {
        $this->db->where('service_id', $service_id);
        $this->db->update('bill_services', $data);
    }

    // Delete a service
    public function delete_service($service_id) {
        $this->db->delete('bill_services', ['service_id' => $service_id]);
    }
}
