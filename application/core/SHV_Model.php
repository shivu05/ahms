<?php

/**
 * Description of SHV_Model
 *
 * @author Shiv
 */
class SHV_Model extends CI_Model {

    private $_table;
    private $CI;

    public function __construct($table = '') {
        parent::__construct();
        $this->_table = (empty($table)) ? strtolower(get_class($this)) : $table;
        $this->CI = &get_instance();
    }

    public function all($columns = array('*')) {
        return $this->CI->db->select($columns)->get($this->_table)->result_array();
    }

    public function filter($columns = array('*'), $where_condition = NULL) {
        return $this->CI->db->select($columns)->where($where_condition)->get($this->_table)->result_array();
    }

    public function store($values) {
        return $this->CI->db->insert($this->_table, $values);
    }

    public function update($values, $where) {
        $this->CI->db->where($where);
        return $this->CI->db->update($this->_table, $values);
    }

    public function delete($where) {
        $this->db->where($where);
        return $this->CI->db->delete($this->_table);
    }

}
