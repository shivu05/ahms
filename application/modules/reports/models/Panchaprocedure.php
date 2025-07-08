<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Panchaprocedure extends CI_Model {

    private $table_name = 'panchaprocedure';

    public function __construct() {
        parent::__construct();
    }

    // Example method to fetch all records
    public function get_all_records() {
        $query = $this->db->get($this->table_name);
        return $query->result_array();
    }

    // Example method to insert a record
    public function insert_record($data) {
        return $this->db->insert($this->table_name, $data);
    }

    // Example method to update a record
    public function update_record($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table_name, $data);
    }

    // Example method to delete a record
    public function delete_record($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table_name);
    }

    // Method to get unique procedures
    public function get_unique_procedures() {
        $this->db->select('treatment, `procedure`, COUNT(*) as procedure_count');
        $this->db->from($this->table_name);
        $this->db->where('`procedure` !=', '');
        $this->db->group_by(['treatment', '`procedure`']);
        $this->db->order_by('treatment, `procedure`');
        $query = $this->db->get();
        return $query->result_array();
    }
}