<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends SHV_Controller {

    private $_is_admin = false;

    public function __construct() {
        parent::__construct();
        $this->layout->navIcon = 'fa fa-users';
        $this->load->model('reports/nursing_model');
        $this->_is_admin = $this->rbac->is_admin();
    }

    function xray() {
        $this->layout->title = "X-Ray";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "X-Ray";
        $this->layout->navDescr = "";
        $this->scripts_include->includePlugins(array('datatables', 'js'));
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'reports/Test/export_xray_to_pdf');
        $data['dept_list'] = $this->get_department_list('array');
        $data['is_admin'] = $this->_is_admin;
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

    public function update_xray() {
        if ($this->input->is_ajax_request()) {
            $post_values = $this->input->post();
            $is_updated = $this->nursing_model->update_xray_info($post_values, $post_values['ID']);
            if ($is_updated) {
                echo json_encode(array('msg' => 'Updated Successfully', 'status' => 'ok'));
            } else {
                echo json_encode(array('msg' => 'Failed to update', 'status' => 'nok'));
            }
        }
    }

    function export_xray_to_pdf() {
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        $input_array = array();

        foreach ($this->input->post() as $search_data => $val) {
            $input_array[$search_data] = $val;
        }

        $result = $this->nursing_model->get_xray_data($input_array, true);

        $headers = array(
            'serial_number' => array('name' => '#', 'align' => 'C', 'width' => '4'),
            'OpdNo' => array('name' => 'C.OPD', 'align' => 'C', 'width' => '6'),
            'deptOpdNo' => array('name' => 'D.OPD', 'align' => 'C', 'width' => '6'),
            'name' => array('name' => 'Patient name', 'width' => '18'),
            'Age' => array('name' => 'Age', 'align' => 'C', 'width' => '4'),
            'gender' => array('name' => 'Sex', 'width' => '7'),
            'address' => array('name' => 'Place', 'width' => '8'),
            'department' => array('name' => 'Department', 'width' => '15'),
            //'ID' => array('name' => 'X-Ray No', 'align' => 'C', 'width' => '8'),
            'partOfXray' => array('name' => 'Part', 'width' => '15'),
            'filmSize' => array('name' => 'F.size', 'width' => '7'),
            ///'refDate' => array('name' => 'Ref.Date', 'align' => 'C', 'width' => '8'),
            'xrayDate' => array('name' => 'Date', 'align' => 'C', 'width' => '10'),
        );
        $html = generate_table_pdf($headers, $result['data']);

        $print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);

        $title = array(
            'report_title' => 'X-Ray REGISTER',
            'department' => $print_dept,
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );

        pdf_create($title, $html);
        exit;
    }

    function usg() {
        $this->layout->title = "USG";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "USG";
        $this->layout->navDescr = "";
        $this->scripts_include->includePlugins(array('datatables'), 'js');
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'reports/Test/export_usg_to_pdf');
        $data['dept_list'] = $this->get_department_list('array');
        $data['is_admin'] = $this->_is_admin;
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

    function export_usg_to_pdf() {
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        $input_array = array();

        foreach ($this->input->post() as $search_data => $val) {
            $input_array[$search_data] = $val;
        }

        $result = $this->nursing_model->get_usg_data($input_array, true);

        $headers = array(
            'serial_number' => array('name' => 'Sl. No', 'align' => 'C', 'width' => '5'),
            'OpdNo' => array('name' => 'C.OPD', 'align' => 'C', 'width' => '7'),
            'deptOpdNo' => array('name' => 'D.OPD', 'align' => 'C', 'width' => '5'),
            'name' => array('name' => 'Patient name', 'width' => '20'),
            'Age' => array('name' => 'Age', 'align' => 'C', 'width' => '5'),
            'gender' => array('name' => 'Gender', 'width' => '5'),
            'address' => array('name' => 'Place', 'width' => '10'),
            'department' => array('name' => 'Department', 'width' => '12'),
            'refDocName' => array('name' => 'Ref. doctor', 'width' => '15'),
            'entrydate' => array('name' => 'Ref. date', 'align' => 'C', 'width' => '6'),
            'usgDate' => array('name' => 'USG date', 'align' => 'C', 'width' => '6'),
        );
        $html = generate_table_pdf($headers, $result['data']);

        $print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);

        $title = array(
            'report_title' => 'USG REGISTER',
            'department' => $print_dept,
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );

        pdf_create($title, $html);
        exit;
    }

    function ecg() {
        $this->layout->title = "ECG";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "ECG Reports";
        $this->layout->navDescr = "Electrocardiogram";
        $this->scripts_include->includePlugins(array('datatables'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'reports/Test/export_ecg_to_pdf');
        $data['dept_list'] = $this->get_department_list('array');
        $data['is_admin'] = $this->_is_admin;
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

    function export_ecg_to_pdf() {
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        $input_array = array();

        foreach ($this->input->post() as $search_data => $val) {
            $input_array[$search_data] = $val;
        }

        $result = $this->nursing_model->get_ecg_data($input_array, true);

        $headers = array(
            'serial_number' => array('name' => '#', 'align' => 'C', 'width' => '5'),
            'OpdNo' => array('name' => 'C.OPD', 'align' => 'C', 'width' => '7'),
            'deptOpdNo' => array('name' => 'D.OPD', 'align' => 'C', 'width' => '7'),
            'name' => array('name' => 'Patient name', 'width' => '18'),
            'Age' => array('name' => 'Age', 'align' => 'C', 'width' => '5'),
            'gender' => array('name' => 'Sex', 'width' => '5'),
            'address' => array('name' => 'Place', 'width' => '10'),
            'department' => array('name' => 'Department', 'width' => '15'),
            'refDocName' => array('name' => 'Ref. doctor', 'width' => '19'),
            //'refDate' => array('name' => 'Ref. date', 'align' => 'C', 'width' => '6'),
            'ecgDate' => array('name' => 'ECG date', 'align' => 'C', 'width' => '10'),
        );
        $html = generate_table_pdf($headers, $result['data']);

        $print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);

        $title = array(
            'report_title' => 'ECG REGISTER',
            'department' => $print_dept,
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );

        pdf_create($title, $html);
        exit;
    }

    function birth() {
        $this->layout->title = "Birth";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Birth";
        $this->layout->navDescr = "Birth register";
        $this->scripts_include->includePlugins(array('datatables'), 'js');
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'reports/Test/export_birth_to_pdf');
        $data['dept_list'] = $this->get_department_list('array');
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_birth_register_data() {
        $input_array = array();
        foreach ($this->input->post('search_form') as $search_data) {
            $input_array[$search_data['name']] = $search_data['value'];
        }
        $input_array['start'] = $this->input->post('start');
        $input_array['length'] = $this->input->post('length');
        $input_array['order'] = $this->input->post('order');
        $data = $this->nursing_model->get_birth_data($input_array);
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

    function export_birth_to_pdf() {
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        $input_array = array();

        foreach ($this->input->post() as $search_data => $val) {
            $input_array[$search_data] = $val;
        }

        $result = $this->nursing_model->get_birth_data($input_array, true);

        $headers = array(
            'serial_number' => array('name' => 'Sl. No', 'align' => 'C', 'width' => '5'),
            'OpdNo' => array('name' => 'C.OPD', 'align' => 'C', 'width' => '7'),
            'IpNo' => array('name' => 'C.IPD', 'align' => 'C', 'width' => '7'),
            'FName' => array('name' => 'Patient name', 'width' => '15'),
            'Age' => array('name' => 'Age', 'align' => 'C', 'width' => '5'),
            'diagnosis' => array('name' => 'Diagnosis', 'width' => '15'),
            'deliveryDetail' => array('name' => 'Delivery details', 'width' => '12'),
            'babyBirthDate' => array('name' => 'Birth date', 'width' => '5'),
            'birthtime' => array('name' => 'Birth time', 'width' => '5'),
            'babyWeight' => array('name' => 'Weight', 'width' => '5'),
            'deliverytype' => array('name' => 'Del. type', 'align' => 'C', 'width' => '6'),
            'treatby' => array('name' => 'Doctor', 'align' => 'C', 'width' => '10'),
        );
        $html = generate_table_pdf($headers, $result['data']);

        $print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);

        $title = array(
            'report_title' => 'Birth REGISTER',
            'department' => $print_dept,
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );

        pdf_create($title, $html);
        exit;
    }

    function diet_register() {
        $this->layout->title = "Diet";
        $this->layout->navTitleFlag = false;
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
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Ksharasutra";
        $this->layout->navDescr = "Ksharasutra register";
        $this->scripts_include->includePlugins(array('datatables'), 'js');
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
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Surgery";
        $this->layout->navDescr = "Surgery register";
        $this->scripts_include->includePlugins(array('datatables'), 'js');
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
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Panchakarma";
        $this->layout->navDescr = "Panchakarma register";
        $this->scripts_include->includePlugins(array('datatables'), 'js');
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
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Panchakarma";
        $this->layout->navDescr = "Panchakarma procedure count";
        $this->scripts_include->includePlugins(array('datatables'), 'js');
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
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Surgery";
        $this->layout->navDescr = "Surgery count";
        $this->scripts_include->includePlugins(array('datatables'), 'js');
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
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Lab";
        $this->layout->navDescr = "Laboratory";
        $this->scripts_include->includePlugins(array('datatables'), 'js');
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'export-lab-report');
        $data['dept_list'] = $this->get_department_list('array');
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_lab_records() {
        $post_data = $this->input->post();
        $input_array = array();
        foreach ($post_data as $key => $val) {
            $input_array[$key] = $val;
        }
        $input_array['start'] = $this->input->post('start');
        $input_array['length'] = $this->input->post('length');
        $input_array['order'] = $this->input->post('order');
        $data["patient"] = $this->nursing_model->get_lab_report($input_array);
        $this->load->view('reports/test/lab_report', $data);
    }

    function export_lab_report() {
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        $input_array = array();

        foreach ($this->input->post() as $search_data => $val) {
            $input_array[$search_data] = $val;
        }

        $result = $this->nursing_model->get_lab_report($input_array, true);
        //pma($result,1);
        /* 'l.OpdNo', 'p.FirstName', 'p.LastName', 'p.Age', 'p.gender', 'p.deptOpdNo', 't.diagnosis as labdisease',
          ' GROUP_CONCAT(testrange) testrange','GROUP_CONCAT(testvalue) testvalue', 'GROUP_CONCAT(lt.lab_test_name) lab_test_type',
          'GROUP_CONCAT(lc.lab_cat_name) lab_test_cat', 'GROUP_CONCAT(li.lab_inv_name) testName', 'l.testDate', 'l.refDocName'
         * 
         */
        $headers = array(
            'serial_number' => array('name' => '#', 'align' => 'C', 'width' => '5'),
            'OpdNo' => array('name' => 'C.OPD', 'align' => 'C', 'width' => '7'),
            'deptOpdNo' => array('name' => 'D.OPD', 'align' => 'C', 'width' => '7'),
            'name' => array('name' => 'Patient name', 'width' => '18'),
            'Age' => array('name' => 'Age', 'align' => 'C', 'width' => '5'),
            'gender' => array('name' => 'Sex', 'width' => '5'),
            'department' => array('name' => 'Department', 'width' => '15'),
            'refDocName' => array('name' => 'Ref. doctor', 'width' => '19'),
            'testDate' => array('name' => 'Ref. date', 'align' => 'C', 'width' => '10'),
            'tested_date' => array('name' => 'Lab date', 'align' => 'C', 'width' => '10'),
            'extra_row' => array(
                'lab_test_cat' => array('name' => 'Category', 'align' => 'C', 'width' => '15', 'colspan' => 2),
                'lab_test_type' => array('name' => 'Type', 'align' => 'C', 'width' => '15', 'colspan' => 2),
                'testName' => array('name' => 'Test', 'align' => 'C', 'width' => '15', 'colspan' => 2),
                'testvalue' => array('name' => 'Value', 'align' => 'C', 'width' => '15', 'colspan' => 2),
                'testrange' => array('name' => 'Range', 'align' => 'C', 'width' => '15', 'colspan' => 2),
            )
        );
        $array = json_decode(json_encode($result), true);
        $html = generate_table_pdf($headers, $array, true);

        $print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);

        $title = array(
            'report_title' => 'LAB REGISTER',
            'department' => $print_dept,
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );

        pdf_create($title, $html);
        exit;
    }

    function lab_count() {
        $this->layout->title = "Lab";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Lab";
        $this->layout->navDescr = "Laboratory test count";
        $this->scripts_include->includePlugins(array('datatables', 'js'));
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'reports/Test/export_surgery_count');
        $data['dept_list'] = $this->get_department_list('array');
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_lab_count() {
        $input_array = $this->input->post();
        $data["patient"] = $this->nursing_model->get_lab_report_count($input_array);
        $this->load->view('reports/test/lab_report_count', $data);
    }

    function delete_records() {
        $checkboxes = $this->input->post('check_del');
        $table_name = base64_decode($this->input->post('tab'));
        foreach ($checkboxes as $checkbox) {
            $where = array('ID' => $checkbox);
            $this->nursing_model->delete_record($table_name, $where);
        }
        echo TRUE;
        exit;
    }

    function kriyakalp() {
        $this->layout->title = "Kriyakalpa";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Kriyakalpa";
        $this->layout->navDescr = "Kriyakalpa";
        $this->scripts_include->includePlugins(array('datatables'), 'js');
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'reports/Test/export_surgery_count', true);
        $data['dept_list'] = $this->get_department_list('array');
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_kriyakalp_patients_list() {
        $input_array = array();
        foreach ($this->input->post('search_form') as $search_data) {
            $input_array[$search_data['name']] = $search_data['value'];
        }
        $input_array['start'] = $this->input->post('start');
        $input_array['length'] = $this->input->post('length');
        $input_array['order'] = $this->input->post('order');
        $data = $this->nursing_model->get_kriyakalp_data($input_array);
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

}
