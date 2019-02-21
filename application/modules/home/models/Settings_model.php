<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of settings_model
 *
 * @author Shivaraj
 */
class Settings_model extends CI_Model {

    public function get_configuration_settings($column = NULL) {
        if ($column) {
            $this->db->select($column);
        }
        return $this->db->get('config')->row_array();
    }

    function save_settings($form_data) {
        return $this->db->update('config', $form_data);
    }

    function get_user_profile($id = NULL) {
        if ($id) {
            $this->db->where('ID', $id);
        }
        return $this->db->get('users')->result_array();
    }

    function get_doctors_by_dept($dept) {
        $query = "SELECT * FROM users u JOIN doctorsduty d ON u.id=d.doc_id where LOWER(user_department)=LOWER('$dept') and d.day=DAYOFWEEK(SYSDATE())";
        return $this->db->query($query)->result_array();
    }

    function get_packaging_types() {
        return $this->db->get('packagin_type')->result_array();
    }

    function get_medicine_frequency() {
        return $this->db->get('medicine_frequency')->result_array();
    }

    function get_medicine_list() {
        $query = "SELECT name,product_master_id as id FROM product_master p JOIN purchase_variables pv where p.product_master_id=pv.id";
        return $this->db->query($query)->result_array();
    }

    function get_config_variable_value($var_name='') {
        return $this->db->get_where('config_variables', array('config_var_name' => $var_name))->row()->config_var_value;
    }

}
