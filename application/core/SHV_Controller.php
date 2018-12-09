<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SHV_Baseclass
 *
 * @author Shivaraj
 */
class SHV_Controller extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('home/settings_model');
    }

    function application_settings($columns = NULL) {
        $data['app_settings'] = $this->settings_model->get_configuration_settings($columns);
        return $data['app_settings'];
    }

    function get_department_list($response_type = 'json') {
        $this->load->model('master/department_model');
        $departments = $this->department_model->get_departments();
        if ($response_type == 'json') {
            echo json_encode($departments);
            exit;
        } else {
            return $departments;
        }
    }

    function get_panchakarma_procedures() {
        return $this->db->get('panchakarma_procedures')->result_array();
    }

    function get_sub_department() {
        $this->load->model('master/department_model');
        $dept_id = $this->input->post('dept_id');
        $sub_depts = $this->department_model->get_sub_departments($dept_id);
        echo json_encode($sub_depts);
    }

    function get_roles() {
        $this->db->where('role_code !=', 'ADMIN');
        return $this->db->get('role_master')->result_array();
    }

}
