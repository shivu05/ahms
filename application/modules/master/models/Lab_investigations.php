<?php

/**
 * Description of Lab_investigations
 *
 * @author Abhilasha
 */
class Lab_investigations extends CI_Model {

    public function get_lab_data() {
        $query = "SELECT * FROM lab_investigations lv 
            JOIN lab_tests lt ON lv.lab_test_id=lt.lab_test_id 
            JOIN lab_categories lc ON lc.lab_cat_id=lt.lab_cat_id";
        return $this->db->query($query)->result_array();
    }

}
