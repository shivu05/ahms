<?php

class Complaints extends CI_Model {

    private $_table;

    public function __construct() {
        parent::__construct();
        $this->_table = strtolower(get_class($this));
    }

    public function all() {
        return $this->db->get($this->_table)->result_array();
    }

    public function fetch($where = NULL) {
        if ($where) {
            $this->db->where($where);
        }
        return $this->db->get($this->_table)->result_array();
    }

    public function store($post_values) {
        return $this->db->insert($this->_table, $post_values);
    }

}
