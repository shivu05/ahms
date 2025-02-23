<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jaloukavacharana_model extends SHV_Model {

    private $table = 'jaloukavacharana_opd_ipd_register';

    public function __construct() {
        parent::__construct();
    }

    // CREATE: Insert new record
    public function insert($data) {
        return $this->db->insert($this->table, $data);
    }

    // READ: Get all records or specific record by ID
    public function get($id = null) {
        if ($id === null) {
            return $this->db->get($this->table)->result_array();
        } else {
            return $this->db->get_where($this->table, ['id' => $id])->row_array();
        }
    }

    // UPDATE: Update record by ID
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    // DELETE: Delete record by ID
    public function delete($id) {
        return $this->db->delete($this->table, ['id' => $id]);
    }
}
