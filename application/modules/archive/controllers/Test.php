<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends SHV_Controller {

    private $_is_admin = false;

    public function __construct() {
        parent::__construct();
        $this->layout->navIcon = 'fa fa-users';
        $this->load->model('archive/nursing_model');
        $this->_is_admin = $this->rbac->is_admin();
    }

    function xray() {
        $this->layout->title = "X-Ray";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "X-Ray";
        $this->layout->navDescr = "";
        $this->scripts_include->includePlugins(array('datatables'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'archive/Test/export_xray_to_pdf', false, false, true);
        $data['dept_list'] = $this->get_department_list('array');
        $data['is_admin'] = $this->_is_admin;
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
        $this->load->model('custom_db');
        $db_name = base64_decode($input_array['arch_year']);
        $db = $this->custom_db->getdatabase($db_name);
        if (!isset($input_array['arch_year']) || $input_array['arch_year'] == '') {
            $response = array("recordsTotal" => 0, "recordsFiltered" => 0, 'data' => array());
            echo json_encode($response);
            exit;
        }
        $data = $this->nursing_model->get_xray_data($db, $input_array);
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

    public function update_xray() {
        if ($this->input->is_ajax_request()) {
            $post_values = $this->input->post();
            $is_updated = $this->nursing_model->update_xray_info($post_values, $post_values['ID']);
            if ($is_updated) {
                echo json_encode(array('msg' => 'Updated Successfully', 'status' => 'ok'));
            } else {
                echo json_encode(array('msg' => 'Failed to update', 'status' => 'nok'));
            }
        }
    }

    function export_xray_to_pdf() {
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        $input_array = array();

        foreach ($this->input->post() as $search_data => $val) {
            $input_array[$search_data] = $val;
        }
        $this->load->model('custom_db');
        $db_name = base64_decode($input_array['arch_year']);
        $db = $this->custom_db->getdatabase($db_name);

        $result = $this->nursing_model->get_xray_data($db, $input_array, true);
        $this->layout->data = $result;
        $html = $this->layout->render(array('view' => 'archive/test/xray/xray_table_vw'), true);
        $print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);

        $title = array(
            'report_title' => 'X-RAY REGISTER',
            'department' => $print_dept,
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );

        $current_date = format_date($input_array['start_date']);
        generate_pdf($html, 'L', $title, 'XRAY_REPORT_' . $current_date, true, true, 'I');
        exit;
    }

    function usg() {
        $this->layout->title = "Archived USG";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "USG";
        $this->layout->navDescr = "";
        $this->scripts_include->includePlugins(array('datatables'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'archive/Test/export_usg_to_pdf', false, false, true);
        $data['dept_list'] = $this->get_department_list('array');
        $data['is_admin'] = $this->_is_admin;
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
        $this->load->model('custom_db');
        $db_name = base64_decode($input_array['arch_year']);
        $db = $this->custom_db->getdatabase($db_name);
        if (!isset($input_array['arch_year']) || $input_array['arch_year'] == '') {
            $response = array("recordsTotal" => 0, "recordsFiltered" => 0, 'data' => array());
            echo json_encode($response);
            exit;
        }
        $data = $this->nursing_model->get_usg_data($db, $input_array);
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

    function export_usg_to_pdf() {
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        $input_array = array();

        foreach ($this->input->post() as $search_data => $val) {
            $input_array[$search_data] = $val;
        }

        $this->load->model('custom_db');
        $db_name = base64_decode($input_array['arch_year']);
        $db = $this->custom_db->getdatabase($db_name);

        $result = $this->nursing_model->get_usg_data($db, $input_array, true);
        $this->layout->data = $result;
        $html = $this->layout->render(array('view' => 'archive/test/usg/usg_grid_vw'), true);
        $print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);

        $title = array(
            'report_title' => 'USG REGISTER',
            'department' => $print_dept,
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );

        $current_date = format_date($input_array['start_date']);
        generate_pdf($html, 'L', $title, 'USG_REPORT_' . $current_date, true, true, 'I');
        exit;
    }

    function ecg() {
        $this->layout->title = "Archived ECG";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "ECG Reports";
        $this->layout->navDescr = "Electrocardiogram";
        $this->scripts_include->includePlugins(array('datatables'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'archive/Test/export_ecg_to_pdf', false, false, true);
        $data['dept_list'] = $this->get_department_list('array');
        $data['is_admin'] = $this->_is_admin;
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_ecg_patients_list() {
        $input_array = array();
        foreach ($this->input->post('search_form') as $search_data) {
            $input_array[$search_data['name']] = $search_data['value'];
        }
        $input_array['start'] = $this->input->post('start');
        $input_array['length'] = $this->input->post('length');
        $input_array['order'] = $this->input->post('order');
        $this->load->model('custom_db');
        $db_name = base64_decode($input_array['arch_year']);
        $db = $this->custom_db->getdatabase($db_name);
        $data = $this->nursing_model->get_ecg_data($db, $input_array);
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

    function export_ecg_to_pdf() {
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        $input_array = array();

        foreach ($this->input->post() as $search_data => $val) {
            $input_array[$search_data] = $val;
        }
        $this->load->model('custom_db');
        $db_name = base64_decode($input_array['arch_year']);
        $db = $this->custom_db->getdatabase($db_name);
        $result = $this->nursing_model->get_ecg_data($db, $input_array, true);
        $this->layout->data = $result;
        $html = $this->layout->render(array('view' => 'archive/test/ecg/ecg_grid_vw'), true);
        $print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);

        $title = array(
            'report_title' => 'ECG REGISTER',
            'department' => $print_dept,
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );

        $current_date = format_date($input_array['start_date']);
        generate_pdf($html, 'L', $title, 'ECG_REPORT_' . $current_date, true, true, 'I');
        exit;
    }

    function birth() {
        $this->layout->title = "Archived Birth";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Birth";
        $this->layout->navDescr = "Birth register";
        $this->scripts_include->includePlugins(array('datatables'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'archive/Test/export_birth_to_pdf', false, false, true);
        $data['dept_list'] = $this->get_department_list('array');
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_birth_register_data() {
        $input_array = array();
        foreach ($this->input->post('search_form') as $search_data) {
            $input_array[$search_data['name']] = $search_data['value'];
        }
        $input_array['start'] = $this->input->post('start');
        $input_array['length'] = $this->input->post('length');
        $input_array['order'] = $this->input->post('order');
        $this->load->model('custom_db');
        $db_name = base64_decode($input_array['arch_year']);
        $db = $this->custom_db->getdatabase($db_name);
        $data = $this->nursing_model->get_birth_data($db, $input_array);
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

    function export_birth_to_pdf() {
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        $input_array = array();

        foreach ($this->input->post() as $search_data => $val) {
            $input_array[$search_data] = $val;
        }
        $this->load->model('custom_db');
        $db_name = base64_decode($input_array['arch_year']);
        $db = $this->custom_db->getdatabase($db_name);
        $data['patient'] = $this->nursing_model->get_birth_data($db, $input_array, true);
        $this->layout->data = $data;
        $content = $this->layout->render(array('view' => 'archive/test/birth/birth_grid'), true);
        $print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);

        $title = array(
            'report_title' => 'Birth REGISTER',
            'department' => $print_dept,
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );

        $date = format_date($input_array['start_date']);
        generate_pdf($content, 'L', $title, 'birth_report_' . $date, true, true, 'I');
        exit;
    }

    function diet_register() {
        $this->layout->title = "Archived Diet";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Diet";
        $this->layout->navDescr = "Diet register";
        $this->scripts_include->includePlugins(array('datatables', 'js'));
        $this->scripts_include->includePlugins(array('datatables', 'css'));
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'archive/Test/export_diet_to_pdf', false, false, true);
        $data['dept_list'] = $this->get_department_list('array');
        $data['is_admin'] = $this->_is_admin;
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_diet_register_data() {
        $input_array = array();
        foreach ($this->input->post('search_form') as $search_data) {
            $input_array[$search_data['name']] = $search_data['value'];
        }
        $input_array['start'] = $this->input->post('start');
        $input_array['length'] = $this->input->post('length');
        $input_array['order'] = $this->input->post('order');
        $this->load->model('custom_db');
        $db_name = base64_decode($input_array['arch_year']);
        $db = $this->custom_db->getdatabase($db_name);
        $data = $this->nursing_model->get_diet_data($db, $input_array);
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

    function export_diet_to_pdf() {
        $input_array = $this->input->post();
        $this->load->model('custom_db');
        $db_name = base64_decode($input_array['arch_year']);
        $db = $this->custom_db->getdatabase($db_name);
        $return = $this->nursing_model->get_diet_data($db, $input_array, true);
        $data['patient'] = $return['data'];
        $this->layout->data = $data;
        $content = $this->layout->render(array('view' => 'archive/test/ipd/diet_report_ajax'), true);
        $print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);
        $title = array(
            'report_title' => 'DIET REGISTER',
            'department' => $print_dept,
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );
        $date = format_date($input_array['start_date']);
        generate_pdf($content, 'L', $title, 'diet_report_' . $date, true, true, 'I');
        exit;
    }

    function update_diet() {
        $update = array(
            'morning' => $this->input->post('morning'),
            'after_noon' => $this->input->post('after_noon'),
            'evening' => $this->input->post('evening')
        );
        $where = array(
            'id' => $this->input->post('id')
        );
        $is_updated = $this->nursing_model->update_diet_register($update, $where);
        if ($is_updated) {
            echo json_encode(array('msg' => 'Updated Successfully', 'status' => 'ok'));
        } else {
            echo json_encode(array('msg' => 'Failed to update', 'status' => 'nok'));
        }
    }

    function ksharasutra() {
        $this->layout->title = "Archived Ksharasutra";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Ksharasutra";
        $this->layout->navDescr = "Ksharasutra register";
        $this->scripts_include->includePlugins(array('datatables'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'archive/Test/export_ksharasutra', true, false, true);
        $data['dept_list'] = $this->get_department_list('array');
        $data['is_admin'] = $this->_is_admin;
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_ksharasutra_report() {
        $input_array = array();
        foreach ($this->input->post('search_form') as $search_data) {
            $input_array[$search_data['name']] = $search_data['value'];
        }
        $input_array['start'] = $this->input->post('start');
        $input_array['length'] = $this->input->post('length');
        $input_array['order'] = $this->input->post('order');
        $this->load->model('custom_db');
        $db_name = base64_decode($input_array['arch_year']);
        $db = $this->custom_db->getdatabase($db_name);
        $data = $this->nursing_model->get_ksharasutra_data($db, $input_array);
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

    public function update_ksharasutra() {
        if ($this->input->is_ajax_request()) {
            $post_values = $this->input->post();
            $is_updated = $this->nursing_model->update_ksharasutra_info($post_values, $post_values['ID']);
            if ($is_updated) {
                echo json_encode(array('msg' => 'Updated Successfully', 'status' => 'ok'));
            } else {
                echo json_encode(array('msg' => 'Failed to update', 'status' => 'nok'));
            }
        }
    }

    function export_ksharasutra() {
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        $input_array = $this->input->post();
        $this->load->model('custom_db');
        $db_name = base64_decode($input_array['arch_year']);
        $db = $this->custom_db->getdatabase($db_name);
        $data = $this->nursing_model->get_ksharasutra_data($db, $input_array, true);
        $data['patient'] = $data['data'];
        $this->layout->data = $data;
        $content = $this->layout->render(array('view' => 'archive/test/ksharasutra/kshara_report_ajax'), true);
        $print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);

        $title = array(
            'report_title' => 'KSHARASUTRA REGISTER',
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );
        $date = format_date($input_array['start_date']);
        generate_pdf($content, 'L', $title, 'ksharasutra_report_' . $date . '.pdf', true, true, 'I');
        exit;
    }

    function surgery() {
        $this->layout->title = "Archived Surgery";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Surgery";
        $this->layout->navDescr = "Surgery register";
        $this->scripts_include->includePlugins(array('datatables'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'archive/Test/export_surgery', false, false, true);
        $data['dept_list'] = $this->get_department_list('array');
        $data['is_admin'] = $this->_is_admin;
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_surgery_report() {
        $input_array = array();
        foreach ($this->input->post('search_form') as $search_data) {
            $input_array[$search_data['name']] = $search_data['value'];
        }
        $input_array['start'] = $this->input->post('start');
        $input_array['length'] = $this->input->post('length');
        $input_array['order'] = $this->input->post('order');
        $this->load->model('custom_db');
        $db_name = base64_decode($input_array['arch_year']);
        $db = $this->custom_db->getdatabase($db_name);
        $data = $this->nursing_model->get_surgery_data($db, $input_array);
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

    public function update_surgery() {
        if ($this->input->is_ajax_request()) {
            $post_values = $this->input->post();
            $is_updated = $this->nursing_model->update_sugery_info($post_values, $post_values['ID']);
            if ($is_updated) {
                echo json_encode(array('msg' => 'Updated Successfully', 'status' => 'ok'));
            } else {
                echo json_encode(array('msg' => 'Failed to update', 'status' => 'nok'));
            }
        }
    }

    function export_surgery() {
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        $input_array = $this->input->post();
        $this->load->model('custom_db');
        $db_name = base64_decode($input_array['arch_year']);
        $db = $this->custom_db->getdatabase($db_name);
        $data = $this->nursing_model->get_surgery_data($db, $input_array, true);
        $this->layout->data = $data;
        $content = $this->layout->render(array('view' => 'archive/test/surgery/surgery_report_ajax'), true);
        $print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);

        $title = array(
            'report_title' => 'SURGERY REGISTER',
            'department' => $print_dept,
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );
        $date = format_date($input_array['start_date']);
        generate_pdf($content, 'L', $title, 'surgery_report_' . $date, true, true, 'I');
        exit;
    }

    function panchakarma() {
        $this->layout->title = "Archived Panchakarma";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Panchakarma";
        $this->layout->navDescr = "Panchakarma register";
        $this->scripts_include->includePlugins(array('datatables'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'archive/Test/export_panchakarma_report', false, false, true);
        $data['dept_list'] = $this->get_department_list('array');
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_panchakarma_report() {
        $input_array = $this->input->post();
        $this->load->model('custom_db');
        $db_name = base64_decode($input_array['arch_year']);
        $db = $this->custom_db->getdatabase($db_name);
        $data = $this->nursing_model->get_panchakarma_data($db, $input_array);
        $data['is_print'] = false;
        $this->layout->data = $data;
        echo $this->layout->render(array('view' => 'archive/test/panchakarma/dt_panchakarma'), true);
    }

    function export_panchakarma_report() {
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        $input_array = $this->input->post();
        $this->load->model('custom_db');
        $db_name = base64_decode($input_array['arch_year']);
        $db = $this->custom_db->getdatabase($db_name);
        $data = $this->nursing_model->get_panchakarma_data($db, $input_array);
        $data['is_print'] = true;
        $this->layout->data = $data;
        $content = $this->layout->render(array('view' => 'archive/test/panchakarma/dt_panchakarma'), true);
        $print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);

        $title = array(
            'report_title' => 'PANCHAKARMA REGISTER',
            'department' => $print_dept,
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );
        $date = format_date($input_array['start_date']);
        generate_pdf($content, 'L', $title, 'panchakarma_report_' . $date, true, true, 'I');
        exit;
    }

    function export_panchakarma_report_tcf() {
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        $input_array = array();

        foreach ($this->input->post() as $search_data => $val) {
            $input_array[$search_data] = $val;
        }

        $result = $this->nursing_model->get_panchakarma_data($input_array, true);
        $headers = array(
            'serial_number' => array('name' => '#', 'align' => 'C', 'width' => '5'),
            'opdno' => array('name' => 'C.OPD', 'align' => 'C', 'width' => '7'),
            'deptOpdNo' => array('name' => 'D.OPD', 'align' => 'C', 'width' => '7'),
            'name' => array('name' => 'Patient name', 'width' => '20'),
            'Age' => array('name' => 'Age', 'align' => 'C', 'width' => '5'),
            'gender' => array('name' => 'Sex', 'width' => '5'),
            'dept' => array('name' => 'Department', 'width' => '18'),
            'docname' => array('name' => 'Ref. doctor', 'width' => '20'),
            'disease' => array('name' => 'Diesease', 'align' => 'C', 'width' => '15'),
            //'testDate' => array('name' => 'Ref. date', 'align' => 'C', 'width' => '10'),
            //'tested_date' => array('name' => 'Lab date', 'align' => 'C', 'width' => '10'),
            'extra_row' => array(
                'treatment' => array('name' => 'Treatment', 'align' => 'C', 'width' => '15', 'colspan' => 2),
                'procedure' => array('name' => 'Procedure name', 'align' => 'C', 'width' => '15', 'colspan' => 2),
                'date' => array('name' => 'Start date', 'align' => 'C', 'width' => '15', 'colspan' => 2),
                'proc_end_date' => array('name' => 'End date', 'align' => 'C', 'width' => '15', 'colspan' => 2),
            )
        );
        //$array = json_decode(json_encode($result), true);
        $html = generate_table_pdf($headers, $result['data'], true);

        $print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);

        $title = array(
            'report_title' => 'PANCHAKARMA REGISTER',
            'department' => $print_dept,
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );

        pdf_create($title, $html);
        exit;
    }

    function panchakarma_proc_count() {
        $this->layout->title = "Archived Panchakarma procedures";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Panchakarma";
        $this->layout->navDescr = "Panchakarma procedure count";
        $this->scripts_include->includePlugins(array('datatables'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'archive/Test/panchakarma_procedure_count_print', false, false, true);
        $data['dept_list'] = $this->get_department_list('array');
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_panchakarma_procedure_count() {
        $input_array = $this->input->post();
        $this->load->model('custom_db');
        $db_name = base64_decode($input_array['arch_year']);
        $db = $this->custom_db->getdatabase($db_name);
        $data["patient"] = $this->nursing_model->get_panchakarma_procedure_count($db, $input_array);
        //$data['pancha_proces'] = $this->get_panchakarma_procedures();
        $this->load->view('archive/test/panchakarma_proc_count_ajax', $data);
    }

    function panchakarma_procedure_count_print() {
        $input_array = $this->input->post();
        $this->load->model('custom_db');
        $db_name = base64_decode($input_array['arch_year']);
        $db = $this->custom_db->getdatabase($db_name);
        $data["patient"] = $this->nursing_model->get_panchakarma_procedure_count($db, $input_array);
        $html = $this->load->view('archive/test/panchakarma_proc_count_ajax', $data, true);
        $print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);

        $title = array(
            'report_title' => 'PANCHAKARMA PROCEDURE COUNT',
            'department' => $print_dept,
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );
        $date = format_date($input_array['start_date']);
        generate_pdf($html, 'L', $title, 'panchakarma_count_' . $date, true, true, 'I');
        exit;
    }

    function surgery_count() {
        $this->layout->title = "Surgery";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Surgery";
        $this->layout->navDescr = "Surgery count";
        $this->scripts_include->includePlugins(array('datatables'), 'js');
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'archive/Test/export_surgery_count', false, false, true);
        $data['dept_list'] = $this->get_department_list('array');
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_surgery_count_data() {
        $input_array = $this->input->post();
        $this->load->model('custom_db');
        $db_name = base64_decode($input_array['arch_year']);
        $db = $this->custom_db->getdatabase($db_name);
        $data["patient"] = $this->nursing_model->get_surgery_count($db, $input_array);
        $this->load->view('archive/test/surgery_count_ajax', $data);
    }

    function export_surgery_count() {
        $input_array = $this->input->post();
        $this->load->model('custom_db');
        $db_name = base64_decode($input_array['arch_year']);
        $db = $this->custom_db->getdatabase($db_name);
        $data["patient"] = $this->nursing_model->get_surgery_count($db, $input_array);
        $content = $this->load->view('archive/test/surgery_count_ajax', $data, true);
        $print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);

        $title = array(
            'report_title' => 'SURGERY COUNT REGISTER',
            'department' => $print_dept,
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );
        $date = format_date($input_array['start_date']);
        generate_pdf($content, 'L', $title, 'surgery_count_report_' . $date, true, true, 'I');
        exit;
    }

    function lab() {
        $this->layout->title = "Archived Lab";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Lab";
        $this->layout->navDescr = "Laboratory";
        $this->scripts_include->includePlugins(array('datatables'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'archive/test/export_lab_report', false, false, true);
        $data['dept_list'] = $this->get_department_list('array');
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_lab_records() {
        $post_data = $this->input->post();
        $input_array = array();
        foreach ($post_data as $key => $val) {
            $input_array[$key] = $val;
        }
        $input_array['start'] = $this->input->post('start');
        $input_array['length'] = $this->input->post('length');
        $input_array['order'] = $this->input->post('order');
        $this->load->model('custom_db');
        $db_name = base64_decode($input_array['arch_year']);
        $db = $this->custom_db->getdatabase($db_name);
        $data["patient"] = $this->nursing_model->get_lab_report($db, $input_array);
        $this->load->view('archive/test/lab_report', $data);
    }

    function export_lab_report() {
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        $input_array = array();

        foreach ($this->input->post() as $search_data => $val) {
            $input_array[$search_data] = $val;
        }
        $this->load->model('custom_db');
        $db_name = base64_decode($input_array['arch_year']);
        $db = $this->custom_db->getdatabase($db_name);
        $data["patient"] = $this->nursing_model->get_lab_report($db, $input_array, true);
        $content = $this->load->view('archive/test/lab_report', $data, true);

        $print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);

        $title = array(
            'report_title' => 'LAB REGISTER',
            'department' => $print_dept,
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );
        $date = format_date($input_array['start_date']);
        generate_pdf($content, 'L', $title, 'lab_report_' . $date, true, true, 'I');
        exit;
    }

    function lab_count() {
        $this->layout->title = "Archived LAB Count";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Lab";
        $this->layout->navDescr = "Laboratory test count";
        $this->scripts_include->includePlugins(array('datatables'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'archive/Test/lab_count_print', false, false, true);
        $data['dept_list'] = $this->get_department_list('array');
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_lab_count() {
        $input_array = $this->input->post();
        $this->load->model('custom_db');
        $db_name = base64_decode($input_array['arch_year']);
        $db = $this->custom_db->getdatabase($db_name);
        $data["patient"] = $this->nursing_model->get_lab_report_count($db, $input_array);
        $this->load->view('archive/test/lab_report_count', $data);
    }

    function lab_count_print() {
        $input_array = $this->input->post();
        $this->load->model('custom_db');
        $db_name = base64_decode($input_array['arch_year']);
        $db = $this->custom_db->getdatabase($db_name);
        $data["patient"] = $this->nursing_model->get_lab_report_count($db, $input_array);
        $html = $this->load->view('archive/test/lab_report_count', $data, true);
        $print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);

        $title = array(
            'report_title' => 'LAB COUNT',
            'department' => $print_dept,
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );
        $date = format_date($input_array['start_date']);
        generate_pdf($html, 'L', $title, 'lab_count_report_' . $date, true, true, 'I');
        exit;
    }

    function delete_records() {
        $checkboxes = $this->input->post('check_del');
        $table_name = base64_decode($this->input->post('tab'));
        foreach ($checkboxes as $checkbox) {
            $where = array('ID' => $checkbox);
            $this->nursing_model->delete_record($table_name, $where);
        }
        echo TRUE;
        exit;
    }

    function kriyakalp() {
        $this->layout->title = "Archived Kriyakalpa";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Kriyakalpa";
        $this->layout->navDescr = "Kriyakalpa";
        $this->scripts_include->includePlugins(array('datatables'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'archive/Test/export_kriyalapa', true, false, true);
        $data['dept_list'] = $this->get_department_list('array');
        $data['is_admin'] = $this->_is_admin;
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_kriyakalp_patients_list() {
        $input_array = array();
        foreach ($this->input->post('search_form') as $search_data) {
            $input_array[$search_data['name']] = $search_data['value'];
        }
        $input_array['start'] = $this->input->post('start');
        $input_array['length'] = $this->input->post('length');
        $input_array['order'] = $this->input->post('order');
        $this->load->model('custom_db');
        $db_name = base64_decode($input_array['arch_year']);
        $db = $this->custom_db->getdatabase($db_name);
        $data = $this->nursing_model->get_kriyakalp_data($db, $input_array);
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

    function export_kriyalapa() {
        $input_array = $this->input->post();
        $this->load->model('custom_db');
        $db_name = base64_decode($input_array['arch_year']);
        $db = $this->custom_db->getdatabase($db_name);
        $data = $this->nursing_model->get_kriyakalp_data($db, $input_array, true);
        $this->layout->data = $data;
        $content = $this->layout->render(array('view' => 'reports/test/kriyakalpa/export_kriyakalpa'), true);
        //$print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);

        $title = array(
            'report_title' => 'KRIYAKALPA REGISTER',
            'department' => strtoupper('Shalakya tantra'),
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );
        $date = format_date($input_array['start_date']);
        generate_pdf($content, 'L', $title, 'kriyalapa_report_' . $date, true, true, 'I');
        exit;
    }

    function delivery() {
        $this->layout->title = "Archived Delivery";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Delivery";
        $this->layout->navDescr = "Delivery";
        $this->scripts_include->includePlugins(array('datatables'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'archive/Test/export_delivery', false, false, true);
        $data['dept_list'] = $this->get_department_list('array');
        $data['is_admin'] = $this->_is_admin;
        $this->layout->data = $data;
        $this->layout->render();
    }

    function export_delivery() {
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        $input_array = array();

        foreach ($this->input->post() as $search_data => $val) {
            $input_array[$search_data] = $val;
        }
        $this->load->model('custom_db');
        $db_name = base64_decode($input_array['arch_year']);
        $db = $this->custom_db->getdatabase($db_name);
        $data['patient'] = $this->nursing_model->get_birth_data($db, $input_array, true);
        $this->layout->data = $data;
        $content = $this->layout->render(array('view' => 'archive/test/birth/delivery_grid'), true);
        $print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);

        $title = array(
            'report_title' => 'DELIVERY REGISTER',
            'department' => $print_dept,
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );
        $date = format_date($input_array['start_date']);

        generate_pdf($content, 'L', $title, 'delivery_report_' . $date, true, true, 'I');
        exit;
    }

    function physiotherapy() {
        $this->layout->title = "Physiotherapy";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Physiotherapy";
        $this->layout->navDescr = "Physiotherapy";
        $this->scripts_include->includePlugins(array('datatables'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'archive/Test/export_physiotherapy', false, false, true);
        $data['dept_list'] = $this->get_department_list('array');
        $data['is_admin'] = $this->_is_admin;
        $this->layout->data = $data;
        $this->layout->render();
    }

    function fetch_physiotherapy_records() {
        $this->load->model('archive/physiotherapy_treatments');
        $post_values = $this->input->post();
        $this->load->model('custom_db');
        $db_name = base64_decode($post_values['arch_year']);
        $db = $this->custom_db->getdatabase($db_name);
        $data['physic_list'] = $this->physiotherapy_treatments->get_physiotherapy($db, $post_values);
        $this->load->view('archive/test/physiotherapy/data_grid', $data);
    }

    function export_physiotherapy() {
        $this->load->model('archive/physiotherapy_treatments');
        ini_set("memory_limit", "-1");
        set_time_limit(0);

        $input_array = $this->input->post();
        $this->load->model('custom_db');
        $db_name = base64_decode($input_array['arch_year']);
        $db = $this->custom_db->getdatabase($db_name);
        $data['physic_list'] = $this->physiotherapy_treatments->get_physiotherapy($db, $input_array, true);
        $this->layout->data = $data;
        $content = $this->layout->render(array('view' => 'archive/test/physiotherapy/data_grid'), true);
        $print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);

        $title = array(
            'report_title' => 'PHYSIOTHERAPY REGISTER',
            'department' => $print_dept,
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );

        generate_pdf($content, 'L', $title, 'physiotherapy_report', true, true, 'I');
        exit;
    }

    function otherprocedures() {
        $this->layout->title = "Other Procedures";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Other Procedures";
        $this->layout->navDescr = "Other Procedures";
        $this->scripts_include->includePlugins(array('datatables'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'archive/Test/export_otherprocedures', false, false, true);
        $data['dept_list'] = $this->get_department_list('array');
        $data['is_admin'] = $this->_is_admin;
        $this->layout->data = $data;
        $this->layout->render();
    }

    function fetch_oherprocedures_records() {
        $this->load->model('archive/other_procedures_treatments');
        $post_values = $this->input->post();
        $this->load->model('custom_db');
        $db_name = base64_decode($post_values['arch_year']);
        $db = $this->custom_db->getdatabase($db_name);
        $data['physic_list'] = $this->other_procedures_treatments->get_other_procedures($db, $post_values);
        $this->load->view('archive/test/otherprocedures/data_grid', $data);
    }

    function export_otherprocedures() {
        $this->load->model('archive/other_procedures_treatments');
        ini_set("memory_limit", "-1");
        set_time_limit(0);

        $input_array = $this->input->post();
        $this->load->model('custom_db');
        $db_name = base64_decode($input_array['arch_year']);
        $db = $this->custom_db->getdatabase($db_name);
        $data['physic_list'] = $this->other_procedures_treatments->get_other_procedures($db, $input_array, true);
        $this->layout->data = $data;
        $content = $this->layout->render(array('view' => 'archive/test/otherprocedures/data_grid'), true);
        $print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);

        $title = array(
            'report_title' => 'OTHER PROCEDURE REGISTER',
            'department' => $print_dept,
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );

        generate_pdf($content, 'L', $title, 'other_procedure_report', true, true, 'I');
        exit;
    }

    function update_kriyakalpa() {
        $post_values = $this->input->post();
        if ($this->input->is_ajax_request()) {
            $is_updated = $this->nursing_model->update_kriyakalpa_info($post_values, array('id' => $post_values['id']));
            if ($is_updated) {
                echo json_encode(array('msg' => 'Updated Successfully', 'status' => 'ok'));
            } else {
                echo json_encode(array('msg' => 'Failed to update', 'status' => 'nok'));
            }
        } else {
            echo json_encode(array('msg' => 'Invalid request', 'status' => 'nok'));
        }
    }

}
