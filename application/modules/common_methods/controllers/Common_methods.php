<?php

class Common_methods extends SHV_Controller {
    private $_is_admin = false;

    public function __construct() {
        parent::__construct();
        $this->_is_admin = $this->rbac->is_admin();
    }

    function date_dept_selection_form($url, $dept_hide = false, $delete_btn_active = true, $is_arch_report = false) {
        $this->load->model('common_methods/common_model');
        $data['submit_url'] = base_url($url);
        $data['is_admin'] = $this->_is_admin;
        $data['dept_hide'] = $dept_hide;
        $data['delete_btn_active'] = $delete_btn_active;
        $data['dept_list'] = $this->get_department_list('array');
        $data['is_arch_report'] = $is_arch_report;
        $data['arch_years'] = $this->common_model->get_archived_years();
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

    function get_patients() {
        if ($this->input->is_ajax_request()) {
            $this->load->model('common_methods/common_model');
            $opd = $this->input->post('opd');
            echo json_encode(array('data' => $this->common_model->get_patient_info_by_opd($opd), 'status' => 'true'));
        } else {
            echo json_encode(array('data' => NULL, 'status' => 'false'));
        }
    }

    function get_ipd_patients() {
        if ($this->input->is_ajax_request()) {
            $this->load->model('common_methods/common_model');
            $ipd = $this->input->post('ipd');
            echo json_encode(array('data' => $this->common_model->get_patient_info_by_ipd(array('IpNo' => $ipd)), 'status' => 'true'));
        } else {
            echo json_encode(array('data' => NULL, 'status' => 'false'));
        }
    }

    function get_ipd_patient_details() {
        if ($this->input->is_ajax_request()) {
            $this->load->model('common_methods/common_model');
            $opd = $this->input->post('opd');
            $ipd = $this->input->post('ipd');
            $post_values = array(
                'p.OpdNo' => $opd,
                'i.IpNo' => $ipd
            );
            echo json_encode(array('data' => $this->common_model->get_patient_info_by_ipd($post_values), 'status' => 'true'));
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

    function update_ipd_details() {
        $this->load->model('common_methods/common_model');
        $post_values = $this->input->post();
        $is_updated = $this->common_model->update_ipd_data($post_values);
        $is_opd_updated = $this->common_model->update_patient_data($post_values);
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

    function delete_records() {
        if ($this->input->is_ajax_request()) {
            $this->load->model('common_methods/common_model');
            $table_name = base64_decode($this->input->post('tab'));
            $id = $this->input->post('id');
            $return = $this->common_model->delete($table_name, array('id' => $id));
            $msg = '';
            if ($return) {
                $msg = 'Deleted successfully';
            } else {
                $msg = 'Failed to delete';
            }
            echo json_encode(array('msg' => $msg, 'status' => 'true'));
        } else {
            echo json_encode(array('data' => NULL, 'status' => 'false'));
        }
    }
}
