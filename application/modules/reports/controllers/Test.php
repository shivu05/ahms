<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Test extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        $this->layout->navIcon = 'fa fa-users';
        $this->load->model('reports/nursing_model');
    }

    function xray() {
        $this->layout->title = "X-Ray";
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "X-Ray";
        $this->layout->navDescr = "";
        $this->scripts_include->includePlugins(array('datatables', 'js'));
        $data = array();
        $data['dept_list'] = $this->get_department_list('array');
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_xray_patients_list() {
        $input_array = array();
        foreach ($this->input->post('search_form') as $search_data) {
            $input_array[$search_data['name']] = $search_data['value'];
        }
        $input_array['start'] = $this->input->post('start');
        $input_array['length'] = $this->input->post('length');
        $input_array['order'] = $this->input->post('order');
        $data = $this->nursing_model->get_xray_data($input_array);
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

    function usg() {
        $this->layout->title = "USG";
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "USG";
        $this->layout->navDescr = "";
        $this->scripts_include->includePlugins(array('datatables', 'js'));
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'reports/Test/export_usg_to_pdf');
        $data['dept_list'] = $this->get_department_list('array');
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_usg_patients_list() {
        $input_array = array();
        foreach ($this->input->post('search_form') as $search_data) {
            $input_array[$search_data['name']] = $search_data['value'];
        }
        $input_array['start'] = $this->input->post('start');
        $input_array['length'] = $this->input->post('length');
        $input_array['order'] = $this->input->post('order');
        $data = $this->nursing_model->get_xray_data($input_array);
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

}
