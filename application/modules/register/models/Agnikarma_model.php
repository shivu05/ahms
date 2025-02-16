<?php

class Agnikarma_model extends SHV_Model
{

    private $table = 'agnikarma_opd_ipd_register';

    public function __construct()
    {
        parent::__construct();
    }

    // Create a new record
    public function create($data)
    {
        return $this->db->insert($this->table, $data);
    }

    // Read a record by ID
    public function getById($id)
    {
        $query = $this->db->get_where($this->table, array('id' => $id));
        return $query->row_array();
    }

    // Read all records
    public function getAll()
    {
        $query = $this->db->get($this->table);
        return $query->result_array();
    }

    // Update a record by ID
    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    // Delete a record by ID
    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }
}
