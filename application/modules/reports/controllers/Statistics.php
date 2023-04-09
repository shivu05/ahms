<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Statistics extends SHV_Controller {

    private $_is_admin = false;

    public function __construct() {
        parent::__construct();
        $this->layout->navIcon = 'fa fa-users';

        $this->_is_admin = $this->rbac->is_admin();
        $this->load->model('reports/statistics_model');
    }

    function index() {
        
    }

    function other_procedures() {
        $this->layout->title = "Other procedures";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "";
        $this->layout->navDescr = "";
        $this->scripts_include->includePlugins(array('datatables'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'reports/statistics/export_other_proc_stat', false, false);
        $data['dept_list'] = $this->get_department_list('array');
        $data['is_admin'] = $this->_is_admin;
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_other_proc_stats() {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $dept = $this->input->post('department');
        $data['data'] = $this->statistics_model->get_other_procedure_statistics($start_date, $end_date, $dept);
        //echo json_encode(array('status' => 'ok', array('data' => $data)));
        $this->load->view('reports/statistics/data_grid', $data);
    }

    function export_other_proc_stat() {
        $input_array = $this->input->post();
        $data['data'] = $this->statistics_model->get_other_procedure_statistics($input_array['start_date'], $input_array['end_date'], $input_array['department']);
        $content = $this->load->view('reports/statistics/data_grid', $data, true);
        $print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);
        $title = array(
            'report_title' => 'Other procedure statistics',
            'department' => $print_dept,
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );
        generate_pdf($content, 'L', $title, SHORT_NAME . '_OTHER_PROCEDURE_STATS', true, true, 'I');
        exit;
    }

    function physiotherapy() {
        $this->layout->title = "Physiotherapy report";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "";
        $this->layout->navDescr = "";
        $this->scripts_include->includePlugins(array('datatables'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'reports/statistics/export_other_proc_stat', false, false);
        $data['dept_list'] = $this->get_department_list('array');
        $data['is_admin'] = $this->_is_admin;
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_physiotherapy_stats() {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $dept = $this->input->post('department');
        $data['data'] = $this->statistics_model->get_physiotherapy_statistics($start_date, $end_date, $dept);
        //echo json_encode(array('status' => 'ok', array('data' => $data)));
        $this->load->view('reports/statistics/data_grid', $data);
    }

    function export_physiotherapy_stat() {
        $input_array = $this->input->post();
        $data['data'] = $this->statistics_model->get_physiotherapy_statistics($input_array['start_date'], $input_array['end_date'], $input_array['department']);
        $content = $this->load->view('reports/statistics/data_grid', $data, true);
        $print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);
        $title = array(
            'report_title' => 'PHYSIOTHERAPY STATISTICS',
            'department' => $print_dept,
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );
        generate_pdf($content, 'L', $title, SHORT_NAME . '_PHYSIOTHERAPY_STATS', true, true, 'I');
        exit;
    }

}
