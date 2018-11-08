<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Test extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        $this->layout->navIcon = 'fa fa-users';
        $this->load->model('reports/nursing_model');
    }

    function xray() {
        $this->layout->title = "X-Ray";
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "X-Ray";
        $this->layout->navDescr = "";
        $this->scripts_include->includePlugins(array('datatables', 'js'));
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'reports/Test/export_xray_to_pdf');
        $data['dept_list'] = $this->get_department_list('array');
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
        $data = $this->nursing_model->get_xray_data($input_array);
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

    function usg() {
        $this->layout->title = "USG";
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "USG";
        $this->layout->navDescr = "";
        $this->scripts_include->includePlugins(array('datatables', 'js'));
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'reports/Test/export_usg_to_pdf');
        $data['dept_list'] = $this->get_department_list('array');
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
        $data = $this->nursing_model->get_usg_data($input_array);
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

    function ecg() {
        $this->layout->title = "ECG";
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "ECG";
        $this->layout->navDescr = "";
        $this->scripts_include->includePlugins(array('datatables', 'js'));
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'reports/Test/export_ecg_to_pdf');
        $data['dept_list'] = $this->get_department_list('array');
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
        $data = $this->nursing_model->get_ecg_data($input_array);
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

    function birth() {
        $this->layout->title = "Birth";
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "Birth";
        $this->layout->navDescr = "Birth register";
        $this->scripts_include->includePlugins(array('datatables', 'js'));
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'reports/Test/export_birth_to_pdf');
        $data['dept_list'] = $this->get_department_list('array');
        $this->layout->data = $data;
        $this->layout->render();
    }

    function diet_register() {
        $this->layout->title = "Diet";
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "Diet";
        $this->layout->navDescr = "Diet register";
        $this->scripts_include->includePlugins(array('datatables', 'js'));
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'reports/Test/export_diet_to_pdf');
        $data['dept_list'] = $this->get_department_list('array');
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
        $data = $this->nursing_model->get_diet_data($input_array);
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

    function ksharasutra() {
        $this->layout->title = "Ksharasutra";
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "Ksharasutra";
        $this->layout->navDescr = "Ksharasutra register";
        $this->scripts_include->includePlugins(array('datatables', 'js'));
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'reports/Test/export_ksharasutra');
        $data['dept_list'] = $this->get_department_list('array');
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
        $data = $this->nursing_model->get_ksharasutra_data($input_array);
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

    function export_ksharasutra() {
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        $data = array();
        $input_array = array();
        $head = array(
            'serial_number' => array('name' => 'Sl. No', 'width' => 10, 'align' => 'C'),
            'OpdNo' => array('name' => 'C.OPD', 'width' => 12),
            'name' => array('name' => 'Patient', 'width' => 40),
            'diagnosis' => array('name' => 'Diagnosis', 'width' => 30),
            'ksharaname' => array('name' => 'Name of Ksharasutra', 'width' => 43, 'breakat' => 20),
            'ksharsType' => array('name' => 'Type of Ksharasutra', 'width' => 42),
            'surgeon' => array('name' => 'Surgeon', 'width' => 30, 'breakat' => 12),
            'asssurgeon' => array('name' => 'Asst.Surgeon', 'width' => 30, 'breakat' => 16),
            'anaesthetic' => array('name' => 'Anesthetist', 'width' => 30, 'breakat' => 12),
            'ksharsDate' => array('name' => 'Date', 'width' => 20),
        );

        foreach ($this->input->post() as $search_data => $val) {
            $input_array[$search_data] = $val;
        }

        $result = $this->nursing_model->get_ksharasutra_data($input_array, true);
        $patients = $result['data'];

        $this->load->library('pdf');

        $pdf = new Pdf('L', 'mm', 'A4', true, 'UTF-8', false);
        $rep_meta_data = array(
            'report_title' => 'KSHARASUTRA REPORT',
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
        $pdf->SetFont('helveticaB', '', 8);
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
                $align = 'L';
                if (isset($v['align'])) {
                    $align = $v['align'];
                }
                $text = $patient[$h];
                if ($h == 'gender') {
                    $text = get_short_gender($patient[$h]);
                }
                $pdf->vcell($v['width'], $c_height, $x_axis, $text, $breakat, $align);
            }
            $pdf->Ln();
            $fill = !$fill;
        }
        $pdf->Ln();
        ob_end_clean();
        return $pdf->Output('opd_report.pdf', 'I');
    }

    function surgery() {
        $this->layout->title = "Surgery";
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "Surgery";
        $this->layout->navDescr = "Surgery register";
        $this->scripts_include->includePlugins(array('datatables', 'js'));
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'reports/Test/export_surgery');
        $data['dept_list'] = $this->get_department_list('array');
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
        $data = $this->nursing_model->get_surgery_data($input_array);
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

    function panchakarma() {
        $this->layout->title = "Panchakarma";
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "Panchakarma";
        $this->layout->navDescr = "Panchakarma register";
        $this->scripts_include->includePlugins(array('datatables', 'js'));
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'reports/Test/export_panchakarma');
        $data['dept_list'] = $this->get_department_list('array');
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_panchakarma_report() {
        $input_array = array();
        foreach ($this->input->post('search_form') as $search_data) {
            $input_array[$search_data['name']] = $search_data['value'];
        }
        $input_array['start'] = $this->input->post('start');
        $input_array['length'] = $this->input->post('length');
        $input_array['order'] = $this->input->post('order');
        $data = $this->nursing_model->get_panchakarma_data($input_array);
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

    function panchakarma_proc_count() {
        $this->layout->title = "Panchakarma procedures";
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "Panchakarma";
        $this->layout->navDescr = "Panchakarma procedure count";
        $this->scripts_include->includePlugins(array('datatables', 'js'));
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'reports/Test/export_panchakarma');
        $data['dept_list'] = $this->get_department_list('array');
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_panchakarma_procedure_count() {
        $input_array = $this->input->post();
        $data["patient"] = $this->nursing_model->get_panchakarma_procedure_count($input_array);
        $data['pancha_proces'] = $this->get_panchakarma_procedures();
        $this->load->view('reports/test/panchakarma_proc_count_ajax', $data);
    }

    function surgery_count() {
        $this->layout->title = "Surgery";
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "Surgery";
        $this->layout->navDescr = "Surgery count";
        $this->scripts_include->includePlugins(array('datatables', 'js'));
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'reports/Test/export_surgery_count');
        $data['dept_list'] = $this->get_department_list('array');
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_surgery_count_data() {
        $input_array = $this->input->post();
        $data["patient"] = $this->nursing_model->get_surgery_count($input_array);
        $this->load->view('reports/test/surgery_count_ajax', $data);
    }

    function lab() {
        $this->layout->title = "Lab";
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "Lab";
        $this->layout->navDescr = "Laboratory";
        $this->scripts_include->includePlugins(array('datatables', 'js'));
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'reports/Test/export_surgery_count');
        $data['dept_list'] = $this->get_department_list('array');
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_lab_records() {
        $input_array = $this->input->post();
        $data["patient"] = $this->nursing_model->get_lab_report($input_array);
        $this->load->view('reports/test/lab_report', $data);
    }

    function lab_count() {
        $this->layout->title = "Lab";
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "Lab";
        $this->layout->navDescr = "Laboratory test count";
        $this->scripts_include->includePlugins(array('datatables', 'js'));
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'reports/Test/export_surgery_count');
        $data['dept_list'] = $this->get_department_list('array');
        $this->layout->data = $data;
        $this->layout->render();
    }
    
    function get_lab_count(){
        $input_array = $this->input->post();
        $data["patient"] = $this->nursing_model->get_lab_report_count($input_array);
        $this->load->view('reports/test/lab_report_count', $data);
    }

}
