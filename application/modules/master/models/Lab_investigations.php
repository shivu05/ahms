<?php

/**
 * Description of Lab_investigations
 *
 * @author Abhilasha
 */
class Lab_investigations extends CI_Model {

    public function get_lab_data() {
        $query = "SELECT (CASE WHEN test_status='ACTIVE' THEN 'Y' ELSE 'N' END) inv_color,lv.*,lt.*,lc.*
            FROM lab_investigations lv 
            JOIN lab_tests lt ON lv.lab_test_id=lt.lab_test_id 
            JOIN lab_categories lc ON lc.lab_cat_id=lt.lab_cat_id";
        return $this->db->query($query)->result_array();
    }

    public function update($update, $id) {
        $this->db->where('lab_inv_id', $id);
        return $this->db->update('lab_investigations', $update);
    }

    public function get_lab_categories() {
        return $this->db->get('lab_categories')->result_array();
    }

    public function get_lab_test_by_cat($cat_id) {
        $this->db->where('lab_cat_id', $cat_id);
        return $this->db->get('lab_tests')->result_array();
    }

    public function insert_lab_tests($inser_arr) {
        return $this->db->insert_batch('lab_tests', $inser_arr);
    }

    public function insert_lab_invs($inser_arr) {
        return $this->db->insert_batch('lab_investigations', $inser_arr);
    }
}
