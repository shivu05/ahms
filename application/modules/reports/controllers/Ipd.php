<?php

/**
 * Description of Ipd
 *
 * @author Shivaraj
 */
class Ipd extends SHV_Controller {

    private $_is_admin = false;
    public function __construct() {
        parent::__construct();
        $this->layout->navIcon = 'fa fa-users';
        $this->layout->title = "IPD";
        $this->load->model('reports/ipd_model');
        $this->_is_admin = $this->rbac->is_admin();
    }

    function index() {
        $this->scripts_include->includePlugins(array('datatables'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $data = array();
        $data['dept_list'] = $this->get_department_list('array');
        $data['is_admin'] = $this->_is_admin;
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
        $data = $this->ipd_model->get_ipd_patients($input_array);
        $return = $this->ipd_model->get_ipd_patients_count($input_array);
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data'], 'statistics' => $return);
        echo json_encode($response);
    }

    function get_statistics() {
        $input_array = array();
        $input_array['start_date'] = $this->input->post('start_date');
        $input_array['end_date'] = $this->input->post('end_date');
        $input_array['department'] = $this->input->post('department');
        $return = $this->ipd_model->get_ipd_patients_count($input_array);
        echo json_encode(array('statistics' => $return));
    }

    function export_to_pdf() {
        $this->load->helper('mpdf');
        $this->layout->title = 'OPD Report';
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        ini_set("pcre.backtrack_limit", "5000000");
        foreach ($this->input->post() as $search_data => $val) {
            $input_array[$search_data] = $val;
        }

        $result = $this->ipd_model->get_ipd_patients($input_array, true);
        $data['ipd_count'] = $this->ipd_model->get_ipd_patients_count($input_array);

        $data['ipd_patients'] = $result['data'];
        $data['department'] = $input_array['department'];

        $this->layout->data = $data;
        $this->layout->headerFlag = false;
        $html = $this->layout->render(array('view' => 'reports/ipd/export_ipd'), true);
        $print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);

        $title = array(
            'report_title' => 'IPD REGISTER',
            'department' => $print_dept,
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );
        generate_pdf($html, 'L', $title, 'vhms_ipd_report_' . $input_array['start_date'] . '', TRUE, TRUE, 'I');
        exit;
    }

    function export_to_tcpdf() {
        $this->layout->title = 'IPD Report';
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        $input_array = array();

        foreach ($this->input->post() as $search_data => $val) {
            $input_array[$search_data] = $val;
        }

        $result = $this->ipd_model->get_ipd_patients($input_array, true);
        //pma($result, 1);
        $patients = $result['data'];

        $headers['serial_number'] = array('name' => 'Sl. No', 'align' => 'C', 'width' => '5');
        $headers['OpdNo'] = array('name' => 'C.OPD', 'align' => 'C', 'width' => '5');
        $headers['IpNo'] = array('name' => 'C.IPD', 'align' => 'C', 'width' => '5');
        $headers['FName'] = array('name' => 'Name', 'width' => '20');
        $headers['Age'] = array('name' => 'Age', 'align' => 'C', 'width' => '3');
        $headers['Gender'] = array('name' => 'Gender', 'width' => '5');
        $headers['WardNo'] = array('name' => 'Ward', 'align' => 'C', 'width' => '5');
        $headers['BedNo'] = array('name' => 'Bed', 'align' => 'C', 'width' => '5');
        $headers['DoAdmission'] = array('name' => 'Admission', 'align' => 'C', 'width' => '10');
        $headers['DoDischarge'] = array('name' => 'Discharge', 'align' => 'C', 'width' => '10');
        $headers['NofDays'] = array('name' => 'Days', 'align' => 'C', 'width' => '6');
        $headers['Doctor'] = array('name' => 'Doctor', 'width' => '15');
        $headers['department'] = array('name' => 'Department', 'width' => '18');

        $html = generate_table_pdf($headers, $patients);
        $return = $this->ipd_model->get_ipd_patients_count($input_array);
        $stats_headers = array(
            'department' => array('name' => 'Department', 'width' => '15'),
            'Total' => array('name' => 'Total', 'align' => 'C', 'width' => '7'),
            'Male' => array('name' => 'Male', 'align' => 'C', 'width' => '7'),
            'Female' => array('name' => 'Female', 'align' => 'C', 'width' => '7'),
        );
        $stats_html = generate_table_pdf($stats_headers, $return);

        $print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);
        $title = array(
            'report_title' => 'IPD REGISTER',
            'department' => $print_dept,
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );
        $content = $html . '<br/>' . $stats_html;
        pdf_create($title, $content);
        exit;
    }

    public function bed_occupied_report() {
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "IPD BED";
        $this->layout->navDescr = "";
        $this->layout->navIcon = 'fa fa-bed';
        $this->scripts_include->includePlugins(array('datatables', 'jq_validation'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $data = array();
        $data['dept_list'] = $this->get_department_list('array');
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_bed_patients_list() {
        $input_array = array();
        foreach ($this->input->post('search_form') as $search_data) {
            $input_array[$search_data['name']] = $search_data['value'];
        }
        $input_array['start'] = $this->input->post('start');
        $input_array['length'] = $this->input->post('length');
        $input_array['order'] = $this->input->post('order');
        $data = $this->ipd_model->get_bed_occpd_patients($input_array);

        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

    function export_bed_to_pdf() {
        $this->load->helper('mpdf');
        $this->layout->title = 'OPD Report';
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        ini_set("pcre.backtrack_limit", "5000000");
        foreach ($this->input->post() as $search_data => $val) {
            $input_array[$search_data] = $val;
        }

        $result = $this->ipd_model->get_bed_occpd_patients($input_array, true);
        $data['pat_count'] = $this->ipd_model->get_bed_occupied_statistics($input_array);

        $data['patient'] = $result['data'];
        $data['department'] = $input_array['department'];

        $this->layout->data = $data;
        $this->layout->headerFlag = false;
        $html = $this->layout->render(array('view' => 'reports/ipd/export_bed_occ_report'), true);
        $print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);

        $title = array(
            'report_title' => 'IPD REGISTER',
            'department' => $print_dept,
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );
        generate_pdf($html, 'L', $title, 'IPD_BED_OCCU_REPORT_' . $input_array['start_date'] . '', TRUE, TRUE, 'I');
        exit;
    }

    function export_bed_to_tcpdf() {
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        $input_array = array();

        foreach ($this->input->post() as $search_data => $val) {
            $input_array[$search_data] = $val;
        }

        $result = $this->ipd_model->get_bed_occpd_patients($input_array, true);

        $headers = array(
            'serial_number' => array('name' => 'Sl. No', 'width' => 5, 'align' => 'C'),
            'OpdNo' => array('name' => 'C.OPD', 'width' => 5),
            'IpNo' => array('name' => 'C.IPD', 'width' => 5),
            'deptOpdNo' => array('name' => 'Dept.OPD', 'width' => 5),
            'FName' => array('name' => 'Name', 'width' => 20, 'align' => 'L'),
            'Age' => array('name' => 'Age', 'width' => 3, 'align' => 'C'),
            'Gender' => array('name' => 'Sex', 'width' => 5),
            'WardNo' => array('name' => 'W.No', 'width' => 2, 'align' => 'C'),
            'BedNo' => array('name' => 'B.No', 'width' => 2, 'align' => 'C'),
            'diagnosis' => array('name' => 'Diagnosis', 'width' => 26, 'align' => 'L'),
            'DoAdmission' => array('name' => 'DOA', 'width' => 6),
            'DoDischarge' => array('name' => 'DOD', 'width' => 6),
            //'NofDays' => array('name' => 'Days', 'width' => 8),
            'Doctor' => array('name' => 'Doctor', 'width' => 15, 'align' => 'L'),
            'department' => array('name' => 'Department', 'align' => 'L', 'width' => 20)
        );
        $html = generate_table_pdf($headers, $result['data']);
        $stats_headers = array(
            'department' => array('name' => 'Department', 'align' => 'L', 'width' => 20)
        );
        $statistics = $this->ipd_model->get_bed_occupied_statistics($input_array);
        $stats_tbl = '<table style="margin-left: auto;margin-right: auto;" width="50%" class="table table-bordered">';
        $stats_tbl .= '<thead>';
        $stats_tbl .= '<tr>';
        $stats_tbl .= '<th>Department</th>';
        $stats_tbl .= '<th>Total</th>';
        $stats_tbl .= '<th>Male</th>';
        $stats_tbl .= '<th>Female</th>';
        $stats_tbl .= '<th>Discharged</th>';
        $stats_tbl .= '<th>BA</th>';
        $stats_tbl .= '</tr>';
        $stats_tbl .= '</thead>';
        $stats_tbl .= '<tbody>';
        $total = $female = $male = $discharged = $onbed = 0;
        foreach ($statistics as $stat) {
            $stats_tbl .= '<tr>';
            $stats_tbl .= '<td>' . $stat['department'] . '</td>';
            $stats_tbl .= '<td align="center">' . $stat['total'] . '</td>';
            $stats_tbl .= '<td align="center">' . $stat['Male'] . '</td>';
            $stats_tbl .= '<td align="center">' . $stat['Female'] . '</td>';
            $stats_tbl .= '<td align="center">' . $stat['discharged'] . '</td>';
            $stats_tbl .= '<td align="center">' . $stat['onbed'] . '</td>';
            $stats_tbl .= '</tr>';
            $total = $total + $stat['total'];
            $female = $female + $stat['Female'];
            $male = $male + $stat['Male'];
            $discharged = $discharged + $stat['discharged'];
            $onbed = $onbed + $stat['onbed'];
        }
        $stats_tbl .= '<tr style="background-color:#D9D9D9">';
        $stats_tbl .= '<td>Total</td>';
        $stats_tbl .= '<td align="center">' . $total . '</td>';
        $stats_tbl .= '<td align="center">' . $male . '</td>';
        $stats_tbl .= '<td align="center">' . $female . '</td>';
        $stats_tbl .= '<td align="center">' . $discharged . '</td>';
        $stats_tbl .= '<td align="center">' . $onbed . '</td>';
        $stats_tbl .= '</tr>';
        $stats_tbl .= '</tbody>';
        $stats_tbl .= '</table>';

        $print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);

        $title = array(
            'report_title' => 'BED OCCUPIED REGISTER',
            'department' => $print_dept,
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );

        pdf_create($title, $html . '<br/>' . $stats_tbl);
        exit;
    }

    function export_bed_to_pdf_tcp() {
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        $data = array();
        $input_array = array();
        $head = array(
            'serial_number' => array('name' => 'Sl. No', 'width' => 10, 'align' => 'C'),
            'OpdNo' => array('name' => 'C.OPD', 'width' => 12),
            'IpNo' => array('name' => 'C.IPD', 'width' => 12),
            'deptOpdNo' => array('name' => 'Dept.OPD', 'width' => 15),
            'FName' => array('name' => 'Name', 'width' => 50, 'align' => 'L', 'breakat' => 28),
            'Age' => array('name' => 'Age', 'width' => 10),
            'Gender' => array('name' => 'Sex', 'width' => 10),
            'WardNo' => array('name' => 'W.No', 'width' => 10),
            'BedNo' => array('name' => 'B.No', 'width' => 10),
            'diagnosis' => array('name' => 'Diagnosis', 'width' => 40, 'align' => 'L', 'breakat' => 17),
            'DoAdmission' => array('name' => 'DOA', 'width' => 20),
            'DoDischarge' => array('name' => 'DOD', 'width' => 20),
            //'NofDays' => array('name' => 'Days', 'width' => 8),
            'Doctor' => array('name' => 'Doctor', 'width' => 36, 'align' => 'L', 'breakat' => 18),
            'department' => array('name' => 'Department', 'align' => 'L', 'width' => 32)
        );

        foreach ($this->input->post() as $search_data => $val) {
            $input_array[$search_data] = $val;
        }

        $result = $this->ipd_model->get_bed_occpd_patients($input_array, true);

        $statistics = $this->ipd_model->get_bed_occupied_statistics($input_array);
        //pma($statistics, 1);
        $patients = $result['data'];

        $this->load->library('pdf');

        $pdf = new Pdf('L', 'mm', 'A4', true, 'UTF-8', false);
        $rep_meta_data = array(
            'report_title' => 'BED OCCUPIED REGISTER',
        );
        $pdf->setData($rep_meta_data);

        // set document information
        $pdf->setPrintHeader(TRUE);
        // set header and footer fonts
        $pdf->SetFont('helveticaB', '', 9);
        // ---------------------------------------------------------
        // add a page
        // set some text to print
        // print a block of text using Write()
        $pdf->SetFillColor(140, 142, 145);
        $pdf->SetTextColor(255);
        $pdf->SetDrawColor(140, 142, 145);
        $pdf->SetLineWidth(0.3);
        $pdf->SetFont('', 'B');
        $pdf->SetMargins(5, 32, 10, true);
        $pdf->AddPage('L');
        foreach ($head as $h) {
            $pdf->cell($h['width'], 6, $h['name'], 1, 0, 'C', 1);
        }
        $pdf->Ln();
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('helveticaB', '', 9);
        // Data
        $fill = 0;
        foreach ($patients as $patient) {
            foreach ($head as $h => $v) {
                if ($pdf->gety() == 176) {
                    $pdf->AddPage();
                }
                $c_width = 20; // cell width 
                $c_height = 12; // cell height 
                $x_axis = $pdf->getx(); // now get current pdf x axis value
                $breakat = NULL;
                if (isset($v['breakat'])) {
                    $breakat = $v['breakat'];
                }
                $align = 'C';
                if (isset($v['align'])) {
                    $align = $v['align'];
                }
                $text = $patient[$h];
                if ($h == 'Gender') {
                    $text = get_short_gender($patient[$h]);
                }
                $pdf->vcell($v['width'], $c_height, $x_axis, $text, $breakat, $align);
            }
            $pdf->Ln();
            $fill = !$fill;
        }
        $pdf->Ln();
        $pdf->AddPage();
        $pdf->SetX(60);
        $pdf->Cell(160, 6, 'Bed occupied Statistics', 1, 0, 'C', 1);
        $pdf->Ln();
        $pdf->SetX(60);
        $pdf->cell(35, 6, 'Department', 1, 0, 'C', 1);
        $pdf->cell(25, 6, 'Total', 1, 0, 'C', 1);
        $pdf->cell(25, 6, 'Male', 1, 0, 'C', 1);
        $pdf->cell(25, 6, 'Female', 1, 0, 'C', 1);
        $pdf->cell(25, 6, 'Discharged', 1, 0, 'C', 1);
        $pdf->cell(25, 6, 'BA', 1, 0, 'C', 1);
        $pdf->Ln();
        $total = $female = $male = $discharged = $onbed = 0;
        foreach ($statistics as $stat) {
            $pdf->SetX(60);
            $pdf->cell(35, 6, $stat['department'], 1, 0, 'L', 0);
            $pdf->cell(25, 6, $stat['total'], 1, 0, 'C', 0);
            $pdf->cell(25, 6, $stat['Male'], 1, 0, 'C', 0);
            $pdf->cell(25, 6, $stat['Female'], 1, 0, 'C', 0);
            $pdf->cell(25, 6, $stat['discharged'], 1, 0, 'C', 0);
            $pdf->cell(25, 6, $stat['onbed'], 1, 0, 'C', 0);
            $pdf->Ln();
            $total = $total + $stat['total'];
            $female = $female + $stat['Female'];
            $male = $male + $stat['Male'];
            $discharged = $discharged + $stat['discharged'];
            $onbed = $onbed + $stat['onbed'];
        }
        $pdf->SetX(60);
        $pdf->cell(35, 6, 'Total', 1, 0, 'R', 1);
        $pdf->cell(25, 6, $total, 1, 0, 'C', 1);
        $pdf->cell(25, 6, $male, 1, 0, 'C', 1);
        $pdf->cell(25, 6, $female, 1, 0, 'C', 1);
        $pdf->cell(25, 6, $discharged, 1, 0, 'C', 1);
        $pdf->cell(25, 6, $onbed, 1, 0, 'C', 1);
        $pdf->Ln();
        ob_end_clean();
        return $pdf->Output('bed_occupied_ipd_report.pdf', 'I');
    }

    function bed_occupancy_chart() {
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "IPD";
        $this->layout->navDescr = "";
        $this->layout->navIcon = 'fa fa-bed';
        $this->scripts_include->includePlugins(array('datatables'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $data = array();
        $data['departments'] = $this->get_department_list('array');

        $data['dept_bed_count'] = $this->ipd_model->get_departmentwise_bed_count();
        //$data['dept_bed_count'] = $this->ipd_model->get_bed_count_by_dept();
        $months_arr = array(
            'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
        );
        $data_bed = array();
        foreach ($months_arr as $month) {
            $dept_counts = array();
            foreach ($data['departments'] as $dept) {
                $result = $this->ipd_model->get_monthwise_bed_occupancy($month, $dept['dept_unique_code']);
                array_push($dept_counts, array($dept['dept_unique_code'] => $result));
            }
            array_push($data_bed, array($month => $dept_counts));
        }
        //pma($data_bed, 1);
        $data['deptbed'] = $data_bed;
        $this->layout->data = $data;
        $this->layout->render();
    }

    function bed_occupancy_chart_pdf() {
        $db_name = '';
        $sess_arr = $this->session->userdata('user_data');
        if ($sess_arr['randkey'] != "") {
            $db_name = substr(base64_decode($sess_arr['randkey']), -4);
        }
        $data = array();
        $data['departments'] = $this->get_department_list('array');

        $data['dept_bed_count'] = $this->ipd_model->get_departmentwise_bed_count();
        //$data['dept_bed_count'] = $this->ipd_model->get_bed_count_by_dept();
        $months_arr = array(
            'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
        );
        $data_bed = array();
        foreach ($months_arr as $month) {
            $dept_counts = array();
            foreach ($data['departments'] as $dept) {
                $result = $this->ipd_model->get_monthwise_bed_occupancy($month, $dept['dept_unique_code']);
                array_push($dept_counts, array($dept['dept_unique_code'] => $result));
            }
            array_push($data_bed, array($month => $dept_counts));
        }
        //pma($data_bed, 1);
        $data['deptbed'] = $data_bed;
        $this->load->helper('pdf');
        $content = $this->load->view('reports/ipd/bed_occ_chart_print_view', $data, true);
        $title = array(
            'report_title' => 'BED OCCUPANCY REGISTER',
            'start_date' => $db_name,
        );
        generate_pdf($content, 'L', $title, 'vhms_bed_occupancy_count_report', TRUE, TRUE, 'I');
        exit;
    }

    function monthly_io_report() {
        $this->layout->title = "OPD-IPD";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "OPD-IPD Report";
        $this->layout->navDescr = "Monthly OPD-IPD report";
        $this->layout->navIcon = 'fa fa-bed';
        $this->scripts_include->includePlugins(array('datatables', 'js'));
        $data = array();
        $data['result'] = $this->ipd_model->get_month_wise_opd_ipd_report();
        $data['dept_list'] = $this->get_department_list('array');
        $this->layout->data = $data;
        $this->layout->render();
    }

    function monthly_ipd_report() {
        $this->layout->title = "IPD";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "IPD Report";
        $this->layout->navDescr = "Monthly IPD report";
        $this->layout->navIcon = 'fa fa-bed';
        $this->scripts_include->includePlugins(array('datatables'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $data = array();
        $data['result'] = $this->ipd_model->get_month_wise_ipd_report();
        $data['dept_list'] = $this->get_department_list('array');
        $this->layout->data = $data;
        $this->layout->render();
    }

    function monthly_ipd_report_pdf() {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1'); //300 seconds = 5 minutes
        $db_name = '';
        $sess_arr = $this->session->userdata('user_data');
        if ($sess_arr['randkey'] != "") {
            $db_name = substr(base64_decode($sess_arr['randkey']), -4);
        }
        $data['result'] = $this->ipd_model->get_month_wise_ipd_report();
        $data['show_date'] = 0;
        $this->load->helper('pdf');
        $content = $this->load->view('reports/ipd/monthly_ipd_report_print', $data, true);
        $title = array(
            'report_title' => 'MONTHLY IPD PATIENTS REGISTER',
            'start_date' => $db_name
        );
        generate_pdf($content, 'L', $title, 'MONTHWISE_IPD_REPORT', TRUE, TRUE, 'I');
        exit;
    }

    function monthly_ipd_opd_report_pdf() {
        $data['result'] = $this->ipd_model->get_month_wise_opd_ipd_report();
        $data['show_date'] = 0;
        $this->load->helper('pdf');
        $content = $this->load->view('reports/ipd/monthly_opd_ipd_count_print', $data, true);

        $db_name = '';
        $sess_arr = $this->session->userdata('user_data');
        if ($sess_arr['randkey'] != "") {
            $db_name = substr(base64_decode($sess_arr['randkey']), -4);
        }
        $title = array(
            'report_title' => 'IPD-OPD REGISTER',
            'start_date' => $db_name
        );
        generate_pdf($content, 'L', $title, 'MONTHWISE_IPD_REPORT', TRUE, TRUE, 'I');
        exit;
    }
}
