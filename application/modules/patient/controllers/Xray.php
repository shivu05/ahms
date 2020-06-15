<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Xray extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('patient/xray_model');
    }

    function index() {
        $this->layout->title = "X-Ray";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "X-Ray";
        $this->layout->navDescr = "";
        $this->scripts_include->includePlugins(array('datatables', 'jq_validation'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'reports/Test/export_xray_to_pdf');
        $data['dept_list'] = $this->get_department_list('array');
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_pending_xray_list() {
        $input_array = array();
        /* foreach ($this->input->post('search_form') as $search_data) {
          $input_array[$search_data['name']] = $search_data['value'];
          }
          $input_array['start'] = $this->input->post('start');
          $input_array['length'] = $this->input->post('length');
          $input_array['order'] = $this->input->post('order'); */
        $data = $this->xray_model->get_pending_xrays($input_array);
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

    public function save_xray_data() {
        $xray_id = $this->input->post('xray_id');
        $film_size = $this->input->post('film_size');
        $xray_date = $this->input->post('xray_date');
        $form_data = array(
            'filmSize' => $film_size,
            'xrayDate' => $xray_date
        );
        $is_updated = $this->xray_model->update($form_data, $xray_id);
        if ($is_updated) {
            echo TRUE;
        } else {
            echo FALSE;
        }
    }

}
