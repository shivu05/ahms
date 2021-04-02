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
        $this->scripts_include->includePlugins(array('datatables'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
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
        $this->scripts_include->includePlugins(array('datatables'), 'css');
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
            'serial_number' => array('name' => '#', 'align' => 'C', 'width' => '5'),
            'OpdNo' => array('name' => 'C.OPD', 'align' => 'C', 'width' => '6'),
            'deptOpdNo' => array('name' => 'D.OPD', 'align' => 'C', 'width' => '6'),
            'name' => array('name' => 'Patient name', 'width' => '18'),
            'Age' => array('name' => 'Age', 'align' => 'C', 'width' => '5'),
            'gender' => array('name' => 'Sex', 'width' => '5'),
            'address' => array('name' => 'Place', 'width' => '15'),
            'department' => array('name' => 'Department', 'width' => '15'),
            'refDocName' => array('name' => 'Ref. doctor', 'width' => '15'),
            //'entrydate' => array('name' => 'Ref. date', 'align' => 'C', 'width' => '7'),
            'usgDate' => array('name' => 'USG date', 'align' => 'C', 'width' => '10'),
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
        $this->scripts_include->includePlugins(array('datatables'), 'css');
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
        $this->scripts_include->includePlugins(array('datatables', 'css'));
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
        $this->scripts_include->includePlugins(array('datatables'), 'css');
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
        $input_array = array();
        foreach ($this->input->post() as $search_data => $val) {
            $input_array[$search_data] = $val;
        }

        $result = $this->nursing_model->get_ksharasutra_data($input_array, true);

        $headers = array(
            'serial_number' => array('name' => '#', 'width' => '5', 'align' => 'C'),
            'OpdNo' => array('name' => 'C.OPD', 'width' => '6', 'align' => 'C'),
            'name' => array('name' => 'Patient', 'width' => '13'),
            'diagnosis' => array('name' => 'Diagnosis', 'width' => '10'),
            'ksharaname' => array('name' => 'Name of Ksharasutra', 'width' => '12'),
            'ksharsType' => array('name' => 'Type of Ksharasutra', 'width' => '10'),
            'surgeon' => array('name' => 'Surgeon', 'width' => '12'),
            'asssurgeon' => array('name' => 'Asst.Surgeon', 'width' => '12'),
            'anaesthetic' => array('name' => 'Anesthetist', 'width' => '12'),
            'ksharsDate' => array('name' => 'Date', 'width' => '10')
        );


        $html = generate_table_pdf($headers, $result['data']);
        $print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);

        $title = array(
            'report_title' => 'KSHARASUTRA REPORT',
            'department' => $print_dept,
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );

        pdf_create($title, $html);
        exit;
    }

    function surgery() {
        $this->layout->title = "Surgery";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Surgery";
        $this->layout->navDescr = "Surgery register";
        $this->scripts_include->includePlugins(array('datatables'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
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

    function export_surgery() {
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        $input_array = $this->input->post();

        $data = $this->nursing_model->get_surgery_data($input_array, true);
        $this->layout->data = $data;
        $content = $this->layout->render(array('view' => 'reports/test/surgery/surgery_report_ajax'), true);
        $print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);

        $title = array(
            'report_title' => 'SURGERY REGISTER',
            'department' => $print_dept,
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );

        generate_pdf($content, 'L', $title, 'panchakarma_report.pdf', true, true, 'I');
        exit;
    }

    function panchakarma() {
        $this->layout->title = "Panchakarma";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Panchakarma";
        $this->layout->navDescr = "Panchakarma register";
        $this->scripts_include->includePlugins(array('datatables'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'reports/Test/export_panchakarma_report', false, false);
        $data['dept_list'] = $this->get_department_list('array');
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_panchakarma_report() {
        $input_array = $this->input->post();
        $data = array();
        $data = $this->nursing_model->get_panchakarma_data($input_array);
        $this->layout->data = $data;
        echo $this->layout->render(array('view' => 'reports/test/panchakarma/dt_panchakarma'), true);
    }

    function export_panchakarma_report() {
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        $input_array = $this->input->post();

        $data = $this->nursing_model->get_panchakarma_data($input_array);
        $this->layout->data = $data;
        $content = $this->layout->render(array('view' => 'reports/test/panchakarma/dt_panchakarma'), true);
        $print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);

        $title = array(
            'report_title' => 'PANCHAKARMA REGISTER',
            'department' => $print_dept,
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );

        generate_pdf($content, 'L', $title, 'panchakarma_report.pdf', true, true, 'I');
        exit;
    }

    function export_panchakarma_report_tcf() {
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        $input_array = array();

        foreach ($this->input->post() as $search_data => $val) {
            $input_array[$search_data] = $val;
        }

        $result = $this->nursing_model->get_panchakarma_data($input_array, true);
        //pma($result,1);
        /*
         * 'l.opdno', 'p.deptOpdNo', 'CONCAT(p.FirstName," ",p.LastName) as name', 'p.FirstName', 't.AddedBy', 'p.LastName', 'p.Age', 'p.gender', 'p.address',
          'p.deptOpdNo', 'p.dept', 'GROUP_CONCAT(disease) as disease', 'GROUP_CONCAT(treatment) as treatment',
          'GROUP_CONCAT(`procedure`) as `procedure`', 'GROUP_CONCAT(l.date) as `date`', 't.notes', 'GROUP_CONCAT(docname) as docname',
          'GROUP_CONCAT(proc_end_date) as proc_end_date'
         */
        $headers = array(
            'serial_number' => array('name' => '#', 'align' => 'C', 'width' => '5'),
            'opdno' => array('name' => 'C.OPD', 'align' => 'C', 'width' => '7'),
            'deptOpdNo' => array('name' => 'D.OPD', 'align' => 'C', 'width' => '7'),
            'name' => array('name' => 'Patient name', 'width' => '20'),
            'Age' => array('name' => 'Age', 'align' => 'C', 'width' => '5'),
            'gender' => array('name' => 'Sex', 'width' => '5'),
            'dept' => array('name' => 'Department', 'width' => '18'),
            'docname' => array('name' => 'Ref. doctor', 'width' => '20'),
            'disease' => array('name' => 'Diesease', 'align' => 'C', 'width' => '15'),
            //'testDate' => array('name' => 'Ref. date', 'align' => 'C', 'width' => '10'),
            //'tested_date' => array('name' => 'Lab date', 'align' => 'C', 'width' => '10'),
            'extra_row' => array(
                'treatment' => array('name' => 'Treatment', 'align' => 'C', 'width' => '15', 'colspan' => 2),
                'procedure' => array('name' => 'Procedure name', 'align' => 'C', 'width' => '15', 'colspan' => 2),
                'date' => array('name' => 'Start date', 'align' => 'C', 'width' => '15', 'colspan' => 2),
                'proc_end_date' => array('name' => 'End date', 'align' => 'C', 'width' => '15', 'colspan' => 2),
            )
        );
        //$array = json_decode(json_encode($result), true);
        $html = generate_table_pdf($headers, $result['data'], true);

        $print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);

        $title = array(
            'report_title' => 'PANCHAKARMA REGISTER',
            'department' => $print_dept,
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );

        pdf_create($title, $html);
        exit;
    }

    function panchakarma_proc_count() {
        $this->layout->title = "Panchakarma procedures";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Panchakarma";
        $this->layout->navDescr = "Panchakarma procedure count";
        $this->scripts_include->includePlugins(array('datatables'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'reports/Test/panchakarma_procedure_count_print');
        $data['dept_list'] = $this->get_department_list('array');
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_panchakarma_procedure_count() {
        $input_array = $this->input->post();
        $data["patient"] = $this->nursing_model->get_panchakarma_procedure_count($input_array);
        //$data['pancha_proces'] = $this->get_panchakarma_procedures();
        $this->load->view('reports/test/panchakarma_proc_count_ajax', $data);
    }

    function panchakarma_procedure_count_print() {
        $input_array = $this->input->post();
        $data["patient"] = $this->nursing_model->get_panchakarma_procedure_count($input_array);
        $html = $this->load->view('reports/test/panchakarma_proc_count_ajax', $data, true);
        $print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);

        $title = array(
            'report_title' => 'PANCHAKARMA PROCEDURE COUNT',
            'department' => $print_dept,
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );

        pdf_create($title, $html);
        exit;
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

    function export_surgery_count() {
        $input_array = $this->input->post();
        $data["patient"] = $this->nursing_model->get_surgery_count($input_array);
        $content = $this->load->view('reports/test/surgery_count_ajax', $data, true);
        $print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);

        $title = array(
            'report_title' => 'SURGERY COUNT REGISTER',
            'department' => $print_dept,
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );

        generate_pdf($content, 'L', $title, 'surgery_count_report.pdf', true, true, 'I');
        exit;
    }

    function lab() {
        $this->layout->title = "Lab";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Lab";
        $this->layout->navDescr = "Laboratory";
        $this->scripts_include->includePlugins(array('datatables'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
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

        $data["patient"] = $this->nursing_model->get_lab_report($input_array, true);
        $content = $this->load->view('reports/test/lab_report', $data, true);

        $print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);

        $title = array(
            'report_title' => 'LAB REGISTER',
            'department' => $print_dept,
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );

        generate_pdf($content, 'L', $title, 'lab_report.pdf', true, true, 'I');
        exit;
    }

    function lab_count() {
        $this->layout->title = "Lab";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Lab";
        $this->layout->navDescr = "Laboratory test count";
        $this->scripts_include->includePlugins(array('datatables', 'js'));
        $this->scripts_include->includePlugins(array('datatables', 'css'));
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'reports/Test/lab_count_print');
        $data['dept_list'] = $this->get_department_list('array');
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_lab_count() {
        $input_array = $this->input->post();
        $data["patient"] = $this->nursing_model->get_lab_report_count($input_array);
        $this->load->view('reports/test/lab_report_count', $data);
    }

    function lab_count_print() {
        $input_array = $this->input->post();
        $data["patient"] = $this->nursing_model->get_lab_report_count($input_array);
        $html = $this->load->view('reports/test/lab_report_count', $data, true);
        $print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);

        $title = array(
            'report_title' => 'LAB COUNT',
            'department' => $print_dept,
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );

        pdf_create($title, $html);
        exit;
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
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'reports/Test/export_kriyalapa', true);
        $data['dept_list'] = $this->get_department_list('array');
        $data['is_admin'] = $this->_is_admin;
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

    function export_kriyalapa() {
        $input_array = $this->input->post();
        $data = $this->nursing_model->get_kriyakalp_data($input_array, true);
        $this->layout->data = $data;
        $content = $this->layout->render(array('view' => 'reports/test/kriyakalpa/export_kriyakalpa'), true);
        //$print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);

        $title = array(
            'report_title' => 'KRIYAKALPA REGISTER',
            'department' => strtoupper('Shalakya tantra'),
            'start_date' => format_date($input_array['start_date']),
            'end_date' => format_date($input_array['end_date'])
        );
        generate_pdf($content, 'L', $title, 'panchakarma_report.pdf', true, true, 'I');
        exit;
    }

}
