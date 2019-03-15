<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Lab
 *
 * @author hp
 */
class Lab extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('patient/lab_model');
    }

    function index() {
        $this->layout->title = "Lab";
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "Laboratory";
        $this->layout->navDescr = "";
        $this->scripts_include->includePlugins(array('datatables', 'js'));
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'reports/Test/export_xray_to_pdf');
        $data['dept_list'] = $this->get_department_list('array');
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_pending_lab_list() {
        $input_array = array();
        /* foreach ($this->input->post('search_form') as $search_data) {
          $input_array[$search_data['name']] = $search_data['value'];
          }
          $input_array['start'] = $this->input->post('start');
          $input_array['length'] = $this->input->post('length');
          $input_array['order'] = $this->input->post('order'); */
        $data = $this->lab_model->get_pending_labs($input_array);
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

    public function save_lab_data() {
        $test_id = $this->input->post('test_id');
        $testvalue = $this->input->post('test_value');
        $testrange = $this->input->post('test_range');
        $test_date = $this->input->post('test_date');
        $form_data = array(
            'testvalue' => $testvalue,
            'testrange' => $testrange,
            'tested_date' => $test_date
        );
        $is_updated = $this->lab_model->update($form_data, $test_id);
        if ($is_updated) {
            echo TRUE;
        } else {
            echo FALSE;
        }
    }

}
