<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Patient
 *
 * @author Shivaraj
 */
class Patient extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('reports/opd_model');
        $this->layout->navIcon = 'fa fa-users';
        $this->layout->title = "OPD";
    }

    function index() {
        $this->scripts_include->includePlugins(array('jq_validation', 'js'));
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "OPD patients";
        $this->layout->navDescr = "Add new patient";
        $data = array();
        $data['dept_list'] = $this->get_department_list('array');
        $this->layout->data = $data;
        $this->layout->render();
    }

    function opd_list() {
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "OPD patients";
        $this->layout->navDescr = "Out Patient Department";
        $this->scripts_include->includePlugins(array('datatables', 'js'));
        $data = array();
        $this->layout->data = $data;
        $this->layout->render();
    }

    function sub_dept() {
        return $this->get_sub_department();
    }

    function get_patients_list() {
        $input_array = array();
        foreach ($this->input->post('search_form') as $search_data) {
            $input_array[$search_data['name']] = $search_data['value'];
        }
        $input_array['start'] = $this->input->post('start');
        $input_array['length'] = $this->input->post('length');
        $input_array['order'] = $this->input->post('order');
        $data = $this->opd_model->get_patients($input_array);
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

    function export_patients_list() {
        $this->load->helper('to_excel');
        $search_criteria = NULL;
        $input_array = array();

        foreach ($this->input->post('search_form') as $search_data) {
            //$input_array[$search_data] = $val;
            $input_array[$search_data['name']] = $search_data['value'];
        }
        $result = $this->opd_model->get_patients($input_array, true);
        $export_array = $result['data'];

        $headings = array(
            'OpdNo' => 'opd no',
            'FirstName' => 'First Name',
            'LastName' => 'Last Name',
            'Age' => 'Age',
            'gender' => 'gender',
            'address' => 'Address',
            'city' => 'City',
            'occupation' => 'Occupation',
        );
        $file_name = 'opd_patient_list_.xlsx';
        $freeze_column = '';
        $worksheet_name = 'Patients';
        download_to_excel($export_array, $file_name, $headings, $worksheet_name, null, $search_criteria, TRUE);
        ob_end_flush();
    }

    function save() {
        $this->load->model('patient/treatment_model');
        $max = $this->get_next_dept_opd($this->input->post('department'));
        if ($max != NULL) {
            $dept_opd_count = $max + 1;
        } else {
            $dept_opd_count = 1;
        }

        $user_id = $this->rbac->get_uid();
        $this->treatment_model->add_patient_for_treatment($this->input->post());
        pma($user_id, 1);
    }

    function export_pdf() {
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        $this->load->helper('pdf');
        $data = array();
        $input_array = array();

        foreach ($this->input->post() as $search_data => $val) {
            $input_array[$search_data] = $val;
            //$input_array[$search_data['name']] = $search_data['value'];
        }
        $result = $this->opd_model->get_patients($input_array, true);
        $data['patients'] = $result['data'];

        $this->layout->data = $data;
        $content = $this->layout->render(null, true);
        generate_pdf($content);
    }

    function export_patients_list_pdf() {
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        $data = array();
        $input_array = array();

        foreach ($this->input->post() as $search_data => $val) {
            $input_array[$search_data] = $val;
            //$input_array[$search_data['name']] = $search_data['value'];
        }
        $result = $this->opd_model->get_patients($input_array, true);
        $patients = $result['data'];

        $this->load->library('pdf');
        $pdf = new Pdf('L', 'mm', 'A4', true, 'UTF-8', false);

        // set document information
        $pdf->setPrintHeader(TRUE);
        // set header and footer fonts
        $pdf->SetFont('Helvetica', '', 10);
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
        $pdf->cell(20, 6, 'OPD', 1, 0, 'C', 1);
        $pdf->cell(80, 6, 'Name', 1, 0, 'L', 1);
        $pdf->cell(20, 6, 'Age', 1, 0, 'C', 1);
        $pdf->cell(20, 6, 'Sex', 1, 0, 'C', 1);
        $pdf->cell(50, 6, 'Address', 1, 0, 'L', 1);
        $pdf->cell(40, 6, 'City', 1, 0, 'L', 1);
        $pdf->cell(55, 6, 'Occupation', 1, 0, 'L', 1);
        $pdf->Ln();
        // Color and font restoration
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        // Data
        $fill = 0;
        foreach ($patients as $patient) {
            $pdf->Cell(20, 6, $patient['OpdNo'], '1', 0, 'R');
            $pdf->Cell(80, 6, $patient['FirstName'] . ' ' . $patient['LastName'], '1', 0, 'L');
            $pdf->Cell(20, 6, $patient['Age'], '1', 0, 'C');
            $pdf->Cell(20, 6, $patient['gender'], '1', 0, 'C');
            $pdf->Cell(50, 6, $patient['address'], '1', 0, 'L');
            $pdf->Cell(40, 6, $patient['city'], '1', 0, 'L');
            $pdf->Cell(55, 6, $patient['occupation'], '1', 0, 'L');
            $pdf->Ln();
            $fill = !$fill;
        }
        ob_end_clean();
        return $pdf->Output('Test_pdf.pdf', 'I');
    }

    function get_patient_by_opd() {
        $opd_id = $this->input->post('opd_id');
        $data['patient_data'] = $this->opd_model->get_patient_by_opd($opd_id);
        //$data['dept_list'] = $this->department_model->get_department_list();
        echo json_encode($data);
    }

    function followup($pat_type = 'O') {

        $treat_data = array(
            'OpdNo' => $this->input->post('opd'),
            'deptOpdNo' => '',
            'PatType' => $pat_type,
            'department' => $this->input->post('department'),
            'AddedBy' => $this->input->post('doctor_name'),
            'CameOn' => $this->input->post('date'),
        );
        $this->opd_model->store_treatment("add", $treat_data);
    }

    function old_patient_entry() {
        $dept_opd_count = 0;
        $deptname = $this->input->post('department');
        $opd = $this->input->post('opd');
        $dept_count = $this->patient_model->get_dept_opd_count($deptname, $opd);
        if (empty($dept_count)) {
            $max = $this->patient_model->get_next_dept_opd($deptname);
            if (empty($max)) {
                $dept_opd_count = 1;
            } else {
                $dept_opd_count = $max + 1;
            }
        } else {
            $dept_opd_count = $dept_count;
        }
        $treat_data = array(
            'OpdNo' => $opd,
            'deptOpdNo' => $dept_opd_count,
            'PatType' => 'Old Patient',
            'department' => $deptname,
            'AddedBy' => '',
            'CameOn' => strtotime($this->input->post('date')),
        );
        $is_added = $this->treatment_model->store_treatment("add", $treat_data);
        redirect('patient/view', 'refresh');
    }

    function ipd_list() {
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "IPD patients";
        $this->layout->navDescr = "In Patient Department";
        $this->scripts_include->includePlugins(array('datatables', 'js'));
        $data = array();
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_ipd_patients_list() {
        $this->load->model('patient/ipd_model');
        $input_array = array();
        foreach ($this->input->post('search_form') as $search_data) {
            $input_array[$search_data['name']] = $search_data['value'];
        }
        $input_array['start'] = $this->input->post('start');
        $input_array['length'] = $this->input->post('length');
        $input_array['order'] = $this->input->post('order');
        $data = $this->ipd_model->get_patients($input_array);
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

    function date_difference() {
        date_default_timezone_set("Asia/Kolkata");
        $from = strtotime($this->input->post('dod'));
        $to = strtotime($this->input->post('doa'));
        $difference = $from - $to;
        $days = floor($difference / (60 * 60 * 24));
        echo json_encode($days);
    }

    function discharge_patient() {
        $this->load->model('patient/treatment_model');
        $ipd = $this->input->post('dis_ipd');
        $discharge_date = $this->input->post('dod');
        $note = $this->input->post('note');
        $no_of_days = $this->input->post('days');
        $discharge_by = $this->input->post('treated');

        $discharge_arr = array(
            'DoDischarge' => $discharge_date,
            'DischargeNotes' => $note,
            'NofDays' => $no_of_days,
            'DischBy' => $discharge_by,
            'status' => 'discharge'
        );

        $opd_data = $this->treatment_model->get_opd_by_ipd($ipd);
        $this->treatment_model->update_bed_details($opd_data['OpdNo']);
        $is_updated = $this->treatment_model->discharge_patient($ipd, $discharge_arr);
        //$this->add_nursing_indent($ipd);
        if ($is_updated) {
            echo 1;
        } else {
            echo 0;
        }
    }

}
