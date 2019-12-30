<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Ecg
 *
 * @author hp
 */
class Ecg extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('patient/ecg_model');
    }

    function index() {
        $this->layout->title = "ECG";
        $this->layout->navTitleFlag = FALSE;
        $this->layout->navTitle = "ECG";
        $this->layout->navDescr = "Electrocardiogram";
        $this->scripts_include->includePlugins(array('datatables', 'js'));
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'reports/Test/export_xray_to_pdf');
        $data['dept_list'] = $this->get_department_list('array');
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_pending_ecg_list() {
        $input_array = array();
        /* foreach ($this->input->post('search_form') as $search_data) {
          $input_array[$search_data['name']] = $search_data['value'];
          }
          $input_array['start'] = $this->input->post('start');
          $input_array['length'] = $this->input->post('length');
          $input_array['order'] = $this->input->post('order'); */
        $data = $this->ecg_model->get_pending_ecgs($input_array);
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

    public function save_ecg_data() {
        $test_id = $this->input->post('test_id');
        $test_date = $this->input->post('test_date');
        $form_data = array(
            'ecgDate' => $test_date
        );
        $is_updated = $this->ecg_model->update($form_data, $test_id);
        if ($is_updated) {
            echo TRUE;
        } else {
            echo FALSE;
        }
    }

}
