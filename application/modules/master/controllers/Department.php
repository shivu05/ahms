<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Department
 *
 * @author Shivaraj
 */
class Department extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->rbac->is_login()) {
            redirect('Login');
        }
        $this->layout->title = 'Department';
        $this->load->model('master/department_model');
    }

    function index() {
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "Department list";
        $data = array();
        $data['depts'] = $this->get_department_list('array');
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_department_list($response_type = 'json') {
        $departments = $this->department_model->get_departments();
        if ($response_type == 'json') {
            echo json_encode($departments);
            exit;
        } else {
            return $departments;
        }
    }

    function get_sub_departments() {
        $dept_id = $this->input->post('dept_id');
        $sub_departments = $this->department_model->get_sub_departments($dept_id);
        $doctors = $this->get_doctors($dept_id);
        echo json_encode(array('sub_departments' => $sub_departments, 'doctors' => $doctors));
    }

    function get_doctors($dept) {
        $doctors = $this->department_model->get_doctors_by_dept($dept);
        return $doctors;
    }

}
