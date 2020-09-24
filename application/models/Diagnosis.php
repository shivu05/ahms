<?php

class Diagnosis extends CI_Model {

    private $_table;

    public function __construct() {
        parent::__construct();
        $this->_table = 'diagnosis';
    }

    function all() {
        return $this->db->get($this->_table)->result_array();
    }

    function fetch($where = NULL) {
        if ($where) {
            $this->db->where($where);
        }
        return $this->db->get($this->_table)->result_array();
    }

    function store($post_vals) {
        return $this->db->insert($this->_table, $post_vals);
    }

}
