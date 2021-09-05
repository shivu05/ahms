<?php

class Reports extends SHV_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('department');
        $this->load->model('months_list');
        $this->load->model('archived_data');
        $this->layout->title = "Archived reports";
    }

    function index()
    {
        $data['dept_list'] = $this->department->all();
        $data['months_list'] = $this->months_list->all();
        $columns = array(
            'db_name', 'name'
        );
        $data['archived_data'] = $this->archived_data->filter($columns, array('status' => 'active'));
        $this->layout->data = $data;
        $this->layout->render();
    }

    function export_to_pdf()
    {
        $this->load->model('reports/opd_model');
        //pma($this->input->post('report_type'));
        $report_type = $this->input->post('report_type');
        $days = $this->get_first_last_day_of_month($this->input->post('selected_month'));
        $title = array(
            'report_title' => 'OPD REGISTER',
            'department' => $this->input->post('selected_dept'),
            'start_date' => (isset($days['first_day']) ? $days['first_day'] : ''),
            'end_date' => (isset($days['last_day']) ? $days['last_day'] : '')
        );
        $input_array = array(
            'department' => $this->input->post('selected_dept'),
            'start_date' => (isset($days['first_day']) ? $days['first_day'] : ''),
            'end_date' => (isset($days['last_day']) ? $days['last_day'] : '')
        );
        $result = $this->opd_model->get_opd_patients($input_array, true);
        $return = $this->db->query("call get_opd_patients_count('" . $input_array['department'] . "','" . $input_array['start_date'] . "','" . $input_array['end_date'] . "')")->result_array();
        mysqli_next_result($this->db->conn_id); //imp

        $data['opd_patients'] = $result['data'];
        $data['department'] = $input_array['department'];
        $data['opd_stats'] = $return;
        $this->layout->data = $data;
        $this->layout->headerFlag = false;
        $html = $this->layout->render(array('view' => 'reports/opd/export_opd'), true);
        $print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);

        $title = array(
            'report_title' => 'OPD REGISTER',
            'department' => $print_dept,
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );

        $current_date = date('d_m_Y');
        generate_pdf($html, 'L', $title, 'OPD_REPORT_' . $current_date, true, true, 'D');
        exit;
    }
}
