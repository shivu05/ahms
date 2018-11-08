<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Ipd
 *
 * @author Shivaraj
 */
class Ipd extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        $this->layout->navIcon = 'fa fa-users';
        $this->layout->title = "IPD";
        $this->load->model('reports/ipd_model');
    }

    function index() {
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "IPD patients";
        $this->layout->navDescr = "In Patient Department";
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
        $data = $this->ipd_model->get_ipd_patients($input_array);

        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

    function export_to_pdf() {
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        $data = array();
        $input_array = array();
        $head = array(
            'serial_number' => array('name' => 'Sl. No', 'width' => 10, 'align' => 'C'),
            'OpdNo' => array('name' => 'C.OPD', 'width' => 12),
            'IpNo' => array('name' => 'C.IPD', 'width' => 12),
            'deptOpdNo' => array('name' => 'Dept.OPD', 'width' => 15),
            'FName' => array('name' => 'Name', 'width' => 46, 'align' => 'L', 'breakat' => 26),
            'Age' => array('name' => 'Age', 'width' => 10),
            'Gender' => array('name' => 'Sex', 'width' => 10),
            'WardNo' => array('name' => 'W.No', 'width' => 10),
            'BedNo' => array('name' => 'B.No', 'width' => 10),
            'diagnosis' => array('name' => 'Diagnosis', 'width' => 40, 'align' => 'L', 'breakat' => 17),
            'DoAdmission' => array('name' => 'DOA', 'width' => 20),
            'DoDischarge' => array('name' => 'DOD', 'width' => 20),
            'NofDays' => array('name' => 'Days', 'width' => 8),
            'Doctor' => array('name' => 'Doctor', 'width' => 32, 'align' => 'L', 'breakat' => 16),
            'department' => array('name' => 'Department', 'align' => 'L', 'width' => 32)
        );

        foreach ($this->input->post() as $search_data => $val) {
            $input_array[$search_data] = $val;
        }

        $result = $this->ipd_model->get_ipd_patients($input_array, true);
        $statistics = $this->ipd_model->get_ipd_patients_count($input_array);
        //pma($statistics,1);
        $patients = $result['data'];

        $this->load->library('pdf');

        $pdf = new Pdf('L', 'mm', 'A4', true, 'UTF-8', false);
        $rep_meta_data = array(
            'report_title' => 'IPD REPORT',
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
        $pdf->SetX(80);
        $pdf->Cell(110, 6, 'IPD Statistics', 1, 0, 'C', 1);
        $pdf->Ln();
        $pdf->SetX(80);
        $pdf->cell(35, 6, 'Department', 1, 0, 'C', 1);
        $pdf->cell(25, 6, 'Total', 1, 0, 'C', 1);
        $pdf->cell(25, 6, 'Male', 1, 0, 'C', 1);
        $pdf->cell(25, 6, 'Female', 1, 0, 'C', 1);
        $pdf->Ln();
        $total = $female = $male = 0;
        foreach ($statistics as $stat) {
            $pdf->SetX(80);
            $pdf->cell(35, 6, $stat['department'], 1, 0, 'L', 0);
            $pdf->cell(25, 6, $stat['Total'], 1, 0, 'C', 0);
            $pdf->cell(25, 6, $stat['Male'], 1, 0, 'C', 0);
            $pdf->cell(25, 6, $stat['Female'], 1, 0, 'C', 0);
            $pdf->Ln();
            $total = $total + $stat['Total'];
            $female = $female + $stat['Female'];
            $male = $male + $stat['Male'];
        }
        $pdf->SetX(80);
        $pdf->cell(35, 6, 'Total', 1, 0, 'R', 1);
        $pdf->cell(25, 6, $total, 1, 0, 'C', 1);
        $pdf->cell(25, 6, $male, 1, 0, 'C', 1);
        $pdf->cell(25, 6, $female, 1, 0, 'C', 1);
        $pdf->Ln();
        ob_end_clean();
        return $pdf->Output('opd_report.pdf', 'I');
    }

    public function bed_occupied_report() {
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "Bed occupied report";
        $this->layout->navDescr = "";
        $this->layout->navIcon = 'fa fa-bed';
        $this->scripts_include->includePlugins(array('datatables', 'js'));
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
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "Bed occupancy report";
        $this->layout->navDescr = "";
        $this->layout->navIcon = 'fa fa-bed';
        $this->scripts_include->includePlugins(array('datatables', 'js'));
        $data = array();
        $data['departments'] = $this->get_department_list('array');

        $data['dept_bed_count'] = $this->ipd_model->get_departmentwise_bed_count();
        $months_arr = array(
            'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
        );
        $data_bed = array();
        foreach ($months_arr as $month) {
            $dept_counts = array();
            foreach ($data['departments'] as $dept) {
                $result = $this->ipd_model->get_monthwise_bed_occupancy($month, $dept['department']);
                array_push($dept_counts, array($dept['department'] => $result));
            }
            array_push($data_bed, array($month => $dept_counts));
        }
        $data['deptbed'] = $data_bed;
        $this->layout->data = $data;
        $this->layout->render();
    }

    function monthly_io_report() {
        $this->layout->title = "OPD-IPD";
        $this->layout->navTitleFlag = true;
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
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "IPD Report";
        $this->layout->navDescr = "Monthly IPD report";
        $this->layout->navIcon = 'fa fa-bed';
        $this->scripts_include->includePlugins(array('datatables', 'js'));
        $data = array();
        $data['result'] = $this->ipd_model->get_month_wise_ipd_report();
        $data['dept_list'] = $this->get_department_list('array');
        $this->layout->data = $data;
        $this->layout->render();
    }

}
