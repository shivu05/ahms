<?php

/**
 * Description of Opd
 *
 * @author Shivaraj
 */
ini_set("memory_limit", "-1");
set_time_limit(0);

class Opd extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('reports/opd_model');
        $this->layout->navIcon = 'fa fa-users';
        $this->layout->title = "OPD";
    }

    function index() {
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "OPD patients";
        $this->layout->navDescr = "Out Patient Department";
        $this->scripts_include->includePlugins(array('datatables', 'js'));
        $data = array();
        $data['dept_list'] = $this->get_department_list('array');
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_patients_list() {
        $input_array = array();
        foreach ($this->input->post('search_form') as $search_data) {
            $input_array[$search_data['name']] = $search_data['value'];
        }
        $input_array['start'] = $this->input->post('start');
        $input_array['length'] = $this->input->post('length');
        $input_array['order'] = $this->input->post('order');
        $data = $this->opd_model->get_opd_patients($input_array);
        $return = $this->db->query("call get_opd_patients_count('" . $input_array['department'] . "','" . $input_array['start_date'] . "','" . $input_array['end_date'] . "')")->result_array();
        mysqli_next_result($this->db->conn_id); //imp
        //return $return;
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data'], 'statistics' => $return);
        echo json_encode($response);
    }

    function get_statistics() {
        $input_array = array();
        $input_array['start_date'] = $this->input->post('start_date');
        $input_array['end_date'] = $this->input->post('end_date');
        $input_array['department'] = $this->input->post('department');
        $return = $this->db->query("call get_opd_patients_count('" . $input_array['department'] . "','" . $input_array['start_date'] . "','" . $input_array['end_date'] . "')")->result_array();
        mysqli_next_result($this->db->conn_id); //imp
        echo json_encode(array('statistics' => $return));
    }

    function export_to_pdf() {
        $this->layout->title = 'OPD Report';
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        $input_array = array();

        foreach ($this->input->post() as $search_data => $val) {
            $input_array[$search_data] = $val;
        }

        $result = $this->opd_model->get_opd_patients($input_array, true);
        $patients = $result['data'];

        $headers['serial_number'] = array('name' => 'Sl. No', 'align' => 'C', 'width' => '5');
        $headers['OpdNo'] = array('name' => 'C.OPD', 'align' => 'C', 'width' => '7');
        if ($input_array['department'] != 1) {
            $headers['deptOpdNo'] = array('name' => 'D.OPD', 'align' => 'C', 'width' => '5');
        }
        $headers['PatType'] = array('name' => 'Type', 'align' => 'C', 'width' => '5');
        $headers['name'] = array('name' => 'Name', 'width' => '20');
        $headers['Age'] = array('name' => 'Age', 'align' => 'C', 'width' => '3');
        $headers['gender'] = array('name' => 'Gender', 'width' => '5');
        $headers['city'] = array('name' => 'Place', 'width' => '10');
        if ($input_array['department'] != 1) {
            $headers['diagnosis'] = array('name' => 'Diagnosis', 'width' => '10');
            $headers['Trtment'] = array('name' => 'Treatment', 'width' => '27');
            $headers['AddedBy'] = array('name' => 'Doctor', 'width' => '12');
        } else {
            $headers['diagnosis'] = array('name' => 'Complaints', 'width' => '17');
        }
        $headers['department'] = array('name' => 'Department', 'width' => '13');
        //$headers['ref_room'] = array('name' => 'Ref. room', 'align' => 'C', 'width' => '3');
        $headers['CameOn'] = array('name' => 'Date', 'width' => '7');


        $html = generate_table_pdf($headers, $patients);
        $return = $this->db->query("call get_opd_patients_count('" . $input_array['department'] . "','" . $input_array['start_date'] . "','" . $input_array['end_date'] . "')")->result_array();
        mysqli_next_result($this->db->conn_id); //imp
        $stats_headers = array(
            'department' => array('name' => 'Department', 'align' => 'L', 'width' => '20'),
            'OLD' => array('name' => 'OLD', 'align' => 'C', 'width' => '10'),
            'NEW' => array('name' => 'NEW', 'align' => 'C', 'width' => '10'),
            'Total' => array('name' => 'Total', 'align' => 'C', 'width' => '10'),
            'Male' => array('name' => 'Male', 'align' => 'C', 'width' => '10'),
            'Female' => array('name' => 'Female', 'align' => 'C', 'width' => '10'),
            'NRV' => array('name' => 'Netra-Roga Vibhaga', 'align' => 'C', 'width' => '15'),
            'KNMDV' => array('name' => 'karna-Nasa-Mukha & Danta Vibhaga', 'align' => 'C', 'width' => '15'),
        );
        $stats_html = generate_table_pdf($stats_headers, $return);

        $print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);

        $title = array(
            'report_title' => 'OPD REGISTER',
            'department' => $print_dept,
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );
        $content = $html . '<br/><br/>' . $stats_html;
        pdf_create($title, $content);
        exit;
    }

}
