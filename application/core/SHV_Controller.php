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
        $return['sub_dept'] = $this->department_model->get_sub_departments($dept_id);
        $return['doctors'] = $this->get_doctors();
        echo json_encode($return);
    }

    function get_doctors($dept = null) {
        $dept_name = (empty($this->input->post('dept_id'))) ? $dept : $this->input->post('dept_id');
        return $this->settings_model->get_doctors_by_dept($dept_name);
    }

    function get_roles() {
        $this->db->where('role_code !=', 'ADMIN');
        return $this->db->get('role_master')->result_array();
    }

    function get_next_dept_opd($dept) {
        $dept_max_id = $this->db->select_max('deptOpdNo')->where('department', $dept)->get('treatmentdata');
        if (empty($dept_max_id->row()->deptOpdNo)) {
            return 1;
        } else {
            return $dept_max_id->row()->deptOpdNo + 1;
        }
    }

    function get_packaging_types() {
        return $this->settings_model->get_packaging_types();
    }

    function get_uuid() {
        return $this->uuid->v4();
    }

    function get_medicine_frequency() {
        return $this->settings_model->get_medicine_frequency();
    }

    function get_product_list() {
        return $this->settings_model->get_medicine_list();
    }

}
