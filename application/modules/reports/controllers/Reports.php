<?php

/**
 * Description of Reports
 *
 * @author shivarajkumar.badige
 */
class Reports extends SHV_Controller
{

    private $_is_admin = false;

    public function __construct()
    {
        parent::__construct();
        $this->layout->navIcon = 'fa fa-users';
        $this->layout->title = "IPD";
        $this->load->model('reports/patient_reports');
        $this->_is_admin = $this->rbac->is_admin();
    }

    public function show_lab_report($id = null)
    {
        $lab_data = $this->patient_reports->fetch_patient_lab_details($id);
        $testname = explode(",", $lab_data['testName']);
        $lab_test_type = explode(",", $lab_data['lab_test_type']);
        $lab_cat_name = explode(",", $lab_data['lab_test_cat']);
        $testrange = explode("#", $lab_data['testrange']);
        $testvalue = explode("#", $lab_data['testvalue']);
        $table = '';
        $table .= '<table class="table table-bordered" style="width:100%"><tr><td>Name: ' . $lab_data['name'] . '</td><td>Age: ' . $lab_data['Age'] . '</td><td>Gender: ' . $lab_data['gender'] . '</td></tr>';
        $table .= '<tr><td>C.OPD: ' . $lab_data['OpdNo'] . '</td><td>C.IPD: ' . $lab_data['ipdno'] . '</td><td>Date : ' . $lab_data['testDate'] . '</td></tr>';
        $table .= '</table>';
        $category_list = array();
        $sub_category_list = array();
        $i = 0;
        $tr = '<br/><table nowrap="nowrap" class="table table-bordered" style="width:100%;background:#d3d3d3;">';
        $tr .= '<tr><td style="width:33%;">Test</td>';
        $tr .= '<td style="width:33%;">Values</td>';
        $tr .= '<td style="width:33%;">Range</td>';
        $tr .= '</tr>';
        $tr .= '</table>';
        $table .= $tr;
        foreach ($lab_cat_name as $row) {
            if (!in_array($row, $category_list)) {
                $table .= '<h3 style="text-align:center;">' . $row . '</h3>';
                array_push($category_list, $row);
            }
            if (!in_array($lab_test_type[$i], $sub_category_list)) {
                $table .= '<h5 style="text-align:left;">' . $lab_test_type[$i] . '</h5>';
                array_push($sub_category_list, $lab_test_type[$i]);
            }
            $tr = '<table nowrap="nowrap" class="table table-bordered" style="width:100%">';
            $tr .= '<tr><td style="width:33%;">' . $testname[$i] . '</td>';
            $tr .= '<td style="width:33%;">' . $testvalue[$i] . '</td>';
            $tr .= '<td style="width:33%;">' . @$testrange[$i] . '</td>';
            $tr .= '</tr>';
            $tr .= '</table>';
            $i++;
            $table .= $tr;
        }

        $title = array(
            'report_title' => 'LAB REPORT',
        );
        $c_date = date('d_m_Y');
        generate_pdf($table, 'P', $title, 'LAB_REPORT_' . $c_date, true, true, 'I');
        exit;
    }

    function swarnaprashana()
    {
        $this->layout->title = "Swarnaprashana";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Swarnaprashana";
        $this->layout->navDescr = "Swarnaprashana";
        $this->scripts_include->includePlugins(array('datatables'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'export-swarnaprashana', false, false);
        $data['dept_list'] = $this->get_department_list('array');
        $data['is_admin'] = $this->_is_admin;
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_swarnaprashana_patients_list()
    {
        $input_array = array();
        foreach ($this->input->post('search_form') as $search_data) {
            $input_array[$search_data['name']] = $search_data['value'];
        }
        $input_array['start'] = $this->input->post('start');
        $input_array['length'] = $this->input->post('length');
        $input_array['order'] = $this->input->post('order');
        $data = $this->patient_reports->get_swarnaprashana_report($input_array);
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

    function export_swarnaprashana()
    {
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        $input_array = array();

        foreach ($this->input->post() as $search_data => $val) {
            $input_array[$search_data] = $val;
        }

        $result = $this->patient_reports->get_swarnaprashana_report($input_array, true);
        $this->layout->data = $result;
        $html = $this->layout->render(array('view' => 'reports/reports/swarnaprashana/export_swarnaprashana'), true);
        $print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);

        $title = array(
            'report_title' => 'SWARNAPRASHANA',
            'department' => $print_dept,
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );

        $current_date = format_date($input_array['start_date']);
        generate_pdf($html, 'L', $title, 'SWARNAPRASHANA_REPORT_' . $current_date, true, true, 'D');
        exit;
    }

    function agnikarma()
    {
        $this->layout->title = "Agnikarma";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Agnikarma";
        $this->layout->navDescr = "Agnikarma";
        $this->scripts_include->includePlugins(array('datatables'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'export-agnikarma', false, false);
        $data['dept_list'] = $this->get_department_list('array');
        $data['is_admin'] = $this->_is_admin;
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_agnikarma_patients_list()
    {
        $input_array = array();
        foreach ($this->input->post('search_form') as $search_data) {
            $input_array[$search_data['name']] = $search_data['value'];
        }
        $input_array['start'] = $this->input->post('start');
        $input_array['length'] = $this->input->post('length');
        $input_array['order'] = $this->input->post('order');
        $data = $this->patient_reports->get_agnikarma_report($input_array);
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

    function export_agnikarma()
    {
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        $input_array = array();

        foreach ($this->input->post() as $search_data => $val) {
            $input_array[$search_data] = $val;
        }

        $result = $this->patient_reports->get_agnikarma_report($input_array, true);
        $this->layout->data = $result;
        $html = $this->layout->render(array('view' => 'reports/reports/agnikarma/export_agnikarma'), true);
        $print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);

        $title = array(
            'report_title' => 'AGNIKARMA',
            'department' => $print_dept,
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );

        $current_date = format_date($input_array['start_date']);
        generate_pdf($html, 'L', $title, 'AGNIKARMA_REPORT_' . $current_date, true, true, 'D');
        exit;
    }

    function cupping()
    {
        $this->layout->title = "Cupping";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Cupping";
        $this->layout->navDescr = "Cupping";
        $this->scripts_include->includePlugins(array('datatables'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'export-cupping', false, false);
        $data['dept_list'] = $this->get_department_list('array');
        $data['is_admin'] = $this->_is_admin;
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_cupping_patients_list()
    {
        $input_array = array();
        foreach ($this->input->post('search_form') as $search_data) {
            $input_array[$search_data['name']] = $search_data['value'];
        }
        $input_array['start'] = $this->input->post('start');
        $input_array['length'] = $this->input->post('length');
        $input_array['order'] = $this->input->post('order');
        $data = $this->patient_reports->get_cupping_report($input_array);
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

    function export_cupping()
    {
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        $input_array = array();

        foreach ($this->input->post() as $search_data => $val) {
            $input_array[$search_data] = $val;
        }

        $result = $this->patient_reports->get_cupping_report($input_array, true);
        $this->layout->data = $result;
        $html = $this->layout->render(array('view' => 'reports/reports/cupping/export_cupping'), true);
        $print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);

        $title = array(
            'report_title' => 'CUPPING REGISTER',
            'department' => $print_dept,
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );

        $current_date = format_date($input_array['start_date']);
        generate_pdf($html, 'L', $title, 'CUPPING_REPORT_' . $current_date, true, true, 'D');
        exit;
    }
}
