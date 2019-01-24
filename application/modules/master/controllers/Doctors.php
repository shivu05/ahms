<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Doctors extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('master/doctors_model');
    }

    function index() {
        $this->scripts_include->includePlugins(array('datatables', 'jq_validation', 'js'));
        $this->scripts_include->includePlugins(array('datatables', 'css'));
        $this->layout->navTitleFlag = true;
        $this->layout->navIcon = "fa fa-users";
        $this->layout->navTitle = "Doctors duty";
        $this->layout->navDescr = "Duty doctos list";
        $data = array();
        $data['department_list'] = $this->get_department_list('array');
        $data['week_days'] = $this->doctors_model->get_week_days();
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_doctors_list() {
        $input_array = array();
        foreach ($this->input->post('search_form') as $search_data) {
            $input_array[$search_data['name']] = $search_data['value'];
        }
        $input_array['start'] = $this->input->post('start');
        $input_array['length'] = $this->input->post('length');
        $input_array['order'] = $this->input->post('order');
        $data = $this->doctors_model->get_doctos_duty_list($input_array);

        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

    function get_doctors_by_dept() {
        $dept = $this->input->post('dept');
        echo json_encode(array('data' => $this->doctors_model->get_doctors_by_department($dept)));
    }

    function save_doctors_duty() {
        $status = $this->doctors_model->save_doctors_duty($this->input->post());
        echo json_encode($status);
    }

    function delete_doctors_duty() {
        $duty_id = $this->input->post('duty_id');
        $is_deleted = $this->doctors_model->delete_doctors_duty($duty_id);
        if ($is_deleted) {
            echo json_encode(array('status' => TRUE));
        } else {
            echo json_encode(array('status' => FALSE));
        }
    }

}
