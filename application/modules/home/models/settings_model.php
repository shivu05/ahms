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
class settings_model extends CI_Model {

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

}
