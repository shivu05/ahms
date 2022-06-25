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
        $this->layout->navTitleFlag = false;
        $this->layout->navIcon = "fa fa-users";
        $this->layout->navTitle = "Doctors duty";
        $this->layout->title = "Doctors duty";
        $this->layout->navDescr = "Duty doctos list";
        $data = array();
        $data['department_list'] = $this->get_department_list('array');
        $data['week_days'] = $this->doctors_model->get_week_days();
        $data['roles'] = $this->doctors_model->fetch_roles();
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

    function get_doctor_by_id() {
        $doc_id = $this->input->post('doc_id');
        $doc_data = $this->doctors_model->get_doctors_info($doc_id);
        echo json_encode($doc_data);
    }

    function edit_doctors_duty() {
        $post_values = $this->input->post();
        $is_updated = $this->doctors_model->edit_doctors_duty($post_values);
        if ($is_updated) {
            echo json_encode(array('status' => TRUE));
        } else {
            echo json_encode(array('status' => FALSE));
        }
    }

    function export_duty_chart_pdf() {
        $this->layout->title = "Doctors duty";
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        $input_array = array();

        foreach ($this->input->post() as $search_data => $val) {
            $input_array[$search_data] = $val;
        }

        $result = $this->doctors_model->get_doctos_duty_list($input_array, true);

        $headers = array(
            'serial_number' => array('name' => 'Sl. No', 'align' => 'C', 'width' => '10'),
            'user_name' => array('name' => 'Name', 'align' => 'L', 'width' => '30'),
            'user_department' => array('name' => 'Department', 'align' => 'L', 'width' => '30'),
            'week_day' => array('name' => 'Day', 'align' => 'L', 'width' => '30')
        );
        $html = generate_table_pdf($headers, $result['data']);

        $title = array(
            'report_title' => 'DOCTORS DUTY CHART',
        );
        $today = date('dmY');
        generate_pdf($html, 'L', $title, 'doctors_duty_chart_' . $today . '', true, true, 'I');
        exit;
    }

    function export_to_excel() {
        $this->load->helper('to_excel');
        $search_criteria = NULL;
        $input_array = array();

        foreach ($this->input->post() as $search_data => $val) {
            $input_array[$search_data] = $val;
        }
        $result = $this->doctors_model->get_doctos_duty_list($input_array, true);
        $export_array = $result['data'];

        $headings = array(
            'serial_number' => 'Sl. No',
            'user_name' => 'Name',
            'user_department' => 'Department',
            'week_day' => 'Day'
        );
        $date = date('d_m_Y');
        $file_name = 'doctors_duty_chart_' . $date . '.xlsx';
        $freeze_column = '';
        $worksheet_name = 'Doctors duty chart';
        download_to_excel($export_array, $file_name, $headings, $worksheet_name, null, $search_criteria, TRUE);
        ob_end_flush();
    }

}
