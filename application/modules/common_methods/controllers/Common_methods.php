<?php

class Common_methods extends SHV_Controller {

    public function __construct() {
        parent::__construct();
    }

    function date_dept_selection_form($url, $dept_hide = false) {
        $data['submit_url'] = base_url($url);
        $data['dept_hide'] = $dept_hide;
        $data['dept_list'] = $this->get_department_list('array');
        $this->load->view('common_methods/common_methods/date_dept_selection_form', $data);
    }

    function get_patient_details() {
        if ($this->input->is_ajax_request()) {
            $this->load->model('common_methods/common_model');
            $opd = $this->input->post('opd');
            echo json_encode(array('data' => $this->common_model->get_patient_info_by_opd($opd), 'status' => 'true'));
        } else {
            echo json_encode(array('data' => NULL, 'status' => 'false'));
        }
    }

    function update_patient_info() {
        $this->load->model('common_methods/common_model');
        $post_values = $this->input->post();
        $is_updated = $this->common_model->update_patient_data($post_values);
        if ($is_updated) {
            echo json_encode(array('status' => 'true'));
        } else {
            echo json_encode(array('status' => 'false'));
        }
    }

    function fetch_laboratory_test_list() {
        if ($this->input->is_ajax_request()) {
            $this->load->model('common_methods/common_model');
            $category = $this->input->post('category');
            $data = $this->common_model->get_laboratory_test_list($category);
            echo json_encode(array('data' => $data, 'status' => 'true'));
        } else {
            echo json_encode(array('data' => NULL, 'status' => 'false'));
        }
    }

    function fetch_laboratory_investigation_list() {
        if ($this->input->is_ajax_request()) {
            $this->load->model('common_methods/common_model');
            $tests = $this->input->post('tests');
            $data = $this->common_model->get_laboratory_investigation_list($tests);
            echo json_encode(array('data' => $data, 'status' => 'true'));
        } else {
            echo json_encode(array('data' => NULL, 'status' => 'false'));
        }
    }

}
