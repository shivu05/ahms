<?php

class Reports extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('department');
        $this->load->model('months_list');
        $this->load->model('archived_data');
        $this->layout->title = "Archived reports";
        $this->load->model('archive/archive_reports');
    }

    function index() {
        $data['dept_list'] = $this->department->all();
        $data['months_list'] = $this->months_list->all();
        $columns = array(
            'db_name', 'name'
        );
        $data['archived_data'] = $this->archived_data->filter($columns, array('status' => 'active'));
        $this->layout->data = $data;
        $this->layout->render();
    }

    function export_to_pdf() {
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        $this->load->model('reports/opd_model');
        $selected_year = substr($this->input->post('arch_year'), -4);
        $days = $this->get_first_last_day_of_month($this->input->post('selected_month'), $selected_year);
        if ($this->input->post('selected_month') == 'year') {
            $days['first_day'] = '01-01-2020';
            $days['last_day'] = '31-12-2020';
        }
        $input_array = array(
            'db' => $this->input->post('arch_year'),
            'department' => $this->input->post('selected_dept'),
            'start_date' => (isset($days['first_day']) ? $days['first_day'] : ''),
            'end_date' => (isset($days['last_day']) ? $days['last_day'] : ''),
            'month' => $this->input->post('selected_month')
        );
        $report_type = $this->input->post('report_type');
        if (strtolower($report_type) == 'opd') {
            $this->opd($input_array);
            exit;
        } else {
            die();
        }
    }

    function tree_operations() {
        $dir = './public/';
        $filename = $this->input->post('file_path');
        if (file_exists($dir . $filename) && !is_dir($dir . $filename)) {
            unlink($dir . $filename);
        } else if (is_dir($dir . $filename)) {
            rmdir($dir . $filename);
        }
        return false;
    }

    function opd($input_array = array()) {
        if ($input_array) {
            ini_set("memory_limit", "-1");
            set_time_limit(0);
            $result = $this->archive_reports->fetch_opd_details($input_array);
            $data['opd_patients'] = $result['data'];
            $data['department'] = $input_array['department'];
            $data['opd_stats'] = $result['opd_stats'];
            $this->layout->data = $data;
            $this->layout->headerFlag = false;
            $html = $this->layout->render(array('view' => 'archive/reports/opd/export_opd'), true);
            $print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);

            $title = array(
                'report_title' => 'OPD REGISTER',
                'department' => $print_dept,
                'start_date' => format_date($input_array['start_date']),
                'end_date' => format_date($input_array['end_date'])
            );

            $year = substr($input_array['db'], -4);
            $path = './public/' . $year . '/opd';
            create_folder($path);
            $file_name = $path . '/OPD_REPORT_' . $data['department'] . '_' . strtoupper($input_array['month']) . $year;
            generate_pdf($html, 'L', $title, $file_name, true, true, 'F');
            exit;
        }
        return false;
    }

}
