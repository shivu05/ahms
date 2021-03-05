<?php

ini_set("memory_limit", "-1");
set_time_limit(0);

class Nursing extends SHV_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master/department_model');
        $this->load->model('reports/nursing_model');
    }

    function index() {
        $this->layout->title = "Nursing indent report";
        $data = array();
        $data['dept_list'] = $this->get_department_list('array');
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', '');
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_nursing_report() {
        $data['title'] = "NURSING REGISTER";
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $dept = $this->input->post('department');
        $data["patient"] = $this->nursing_model->get_nursing_indent($start_date, $end_date, $dept);
        $this->load->view('reports/nursing/nursing_report_ajax', $data);
    }

    function get_nursing_report_print() {
        $data['title'] = "Nursing report";
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $dept = $this->input->post('department');
        $data["patient"] = $this->nursing_model->get_nursing_indent($start_date, $end_date, $dept);
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1'); //300 seconds = 5 minutes
        $print_dept = ($dept == 1) ? "CENTRAL" : $dept;
        $table = '<table width="100%">';
        $table .= '<tr>';
        $table .= '<td width="33%"><b>DEPARTMENT</b>:' . strtoupper($print_dept) . '</td><td width="33%" text-align:"center"; align="center"><h2>NURSING IPD REGISTER</h2></td><td width="33%" style="text-align:center;"><b>FROM:</b>' . format_date($start_date) . '&nbsp; <b>TO: </b>' . format_date($end_date) . '</td>';
        $table .= '</tr>';
        $table .= '</table>';
        $this->load->helper('pdf');
        $content = $this->load->view('reports/nursing/nursing_report_ajax', $data, true);
        pdf_create($table, $content, 'ahms_nurisng_ipd_report', 'L');
        return;
    }

    function nursing_indent_report() {
        $data["title"] = "Nursing Indent Report";
        $data['dept_list'] = $this->department_model->get_department_list();
        $this->load->view('reports/nursing/nursing_indent_report', $data);
    }

    function get_nursing_indent_report() {
        $data['title'] = "Nursing report";
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $dept = $this->input->post('department');
        $data["patient"] = $this->nursing_model->getIndentReport($start_date, $end_date, $dept);
        $this->load->view('reports/nursing/nursing_indent_report_ajax', $data);
    }

    function get_nursing_indent_report_print() {
        $data['title'] = "Nursing indent report";
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $dept = $this->input->post('department');
        $data["patient"] = $this->nursing_model->getIndentReport($start_date, $end_date, $dept);
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1'); //300 seconds = 5 minutes
        $table = "<h3 align='center'></h3>";
        $print_dept = ($dept == 1) ? "CENTRAL" : $dept;
        $table .= '<table width="100%">';
        $table .= '<tr>';
        $table .= '<td width="33%"><b>DEPARTMENT</b>:' . strtoupper($print_dept) . '</td><td width="33%" style="text-align:right;"><h3>NURSING INDENT REGISTER</h3></td><td width="33%" style="text-align:right"><b>FROM:</b>' . format_date($start_date) . '&nbsp;&nbsp;&nbsp;&nbsp; <b>TO: </b>' . format_date($end_date) . '</td>';
        $table .= '</tr>';
        $table .= '</table>';
        $this->load->helper('pdf');
        $content = $this->load->view('reports/nursing/nursing_indent_report_ajax', $data, true);
        pdf_create($table, $content, 'ahms_nurisng_indent_report', 'L');
        return;
    }

    function diet_registry() {
        $data["title"] = "Nursing Indent Report";
        $data['dept_list'] = $this->department_model->get_department_list();
        $this->load->view('reports/nursing/diet_report', $data);
    }

    function get_diet_report() {
        $data["title"] = "Diet report";
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $dept = $this->input->post('department');
        $data["patient"] = $this->nursing_model->getDietReport($start_date, $end_date, $dept);
        $this->load->view('reports/nursing/diet_report_ajax', $data);
    }

    function get_diet_report_print() {
        $data['title'] = "Diet report";
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $dept = $this->input->post('department');
        $data["patient"] = $this->nursing_model->getDietReport($start_date, $end_date, $dept);
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1'); //300 seconds = 5 minutes
        $print_dept = ($dept == 1) ? "CENTRAL" : strtoupper($dept);
        $table = '<table width="100%">';
        $table .= '<tr>';
        $table .= '<td width="33%"><b>DEPARTMENT</b>:' . $print_dept . '</td><td width="33%" text-align:"center"; align="center"><h2>DIET REGISTER</h2></td><td width="33%" style="text-align:center;"><b>FROM:</b>' . format_date($start_date) . '&nbsp; <b>TO: </b>' . format_date($end_date) . '</td>';
        $table .= '</tr>';
        $table .= '</table>';
        $this->load->helper('pdf');
        $content = $this->load->view('reports/nursing/diet_report_ajax', $data, true);
        pdf_create($table, $content, 'ahms_diet_report', 'L');
        return;
    }

    function birth_registry() {
        $data["title"] = "Birth Report";
        $this->load->view('reports/nursing/birth_report', $data);
    }

    function get_birth_report() {
        $data["title"] = "Birth report";
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $dept = $this->input->post('department');
        $data["patient"] = $this->nursing_model->getBirthReports($start_date, $end_date);
        $this->load->view('reports/nursing/birth_report_ajax', $data);
    }

    function get_birth_report_print() {
        $data['title'] = "Birth report";
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $dept = $this->input->post('department');
        $data["patient"] = $this->nursing_model->getBirthReports($start_date, $end_date, $dept);
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1'); //300 seconds = 5 minutes
        $print_dept = ($dept == 1) ? "CENTRAL" : strtoupper($dept);
        $table = '<table width="100%">';
        $table .= '<tr>';
        $table .= '<td width="33%"></td><td width="33%" text-align:"center"; align="center"><h2>BIRTH REGISTER</h2></td><td width="33%" style="text-align:center;"><b>FROM:</b>' . format_date($start_date) . '&nbsp; <b>TO: </b>' . format_date($end_date) . '</td>';
        $table .= '</tr>';
        $table .= '</table>';
        $this->load->helper('pdf');
        $content = $this->load->view('reports/nursing/birth_report_ajax', $data, true);
        pdf_create($table, $content, 'ahms_birth_report', 'L');
        return;
    }

    function ecg_registry() {
        $data["title"] = "ECG registry";
        $data['settings'] = get_application_settings(array('edit_flag'));
        $this->load->view('reports/nursing/ecg_report', $data);
    }

    function get_ecg_report() {
        $data["title"] = "ECG report";
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $data['is_print'] = FALSE;
        $data["patient"] = $this->nursing_model->getEcgReports($start_date, $end_date);
        $this->load->view('reports/nursing/ecg_report_ajax', $data);
    }

    function get_ecg_report_print() {
        $data['title'] = "ECG report";
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $dept = $this->input->post('department');
        $data['is_print'] = true;
        $data["patient"] = $this->nursing_model->getEcgReports($start_date, $end_date, $dept);
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1'); //300 seconds = 5 minutes
        $table = '<table width="100%">';
        $table .= '<tr>';
        $table .= '<td width="33%"></td><td width="33%" text-align:"center"; align="center"><h2>ECG REGISTER</h2></td><td width="33%" style="text-align:center;"><b>FROM:</b>' . format_date($start_date) . '&nbsp; <b>TO: </b>' . format_date($end_date) . '</td>';
        $table .= '</tr>';
        $table .= '</table>';
        $this->load->helper('pdf');
        $content = $this->load->view('reports/nursing/ecg_report_ajax', $data, true);
        pdf_create($table, $content, 'ahms_ecg_report', 'L');
        return;
    }

    function usg_registry() {
        $data["title"] = "USG report";
        $data['settings'] = get_application_settings(array('edit_flag'));
        $this->load->view('reports/nursing/usg_report', $data);
    }

    function get_usg_report() {
        $data["title"] = "USG report";
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $data["patient"] = $this->nursing_model->getUsgReports($start_date, $end_date);
        $data['is_print'] = FALSE;
        $this->load->view('reports/nursing/usg_report_ajax', $data);
    }

    function get_usg_report_print() {
        $data['title'] = "USG report";
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $dept = $this->input->post('department');
        $data['is_print'] = TRUE;
        $data["patient"] = $this->nursing_model->getUsgReports($start_date, $end_date, $dept);
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1'); //300 seconds = 5 minutes
        $table = '<table width="100%">';
        $table .= '<tr>';
        $table .= '<td width="33%"></td><td width="33%" text-align:"center"; align="center"><h2>USG REGISTER</h2></td><td width="33%" style="text-align:center;"><b>FROM: </b>' . format_date($start_date) . '&nbsp; <b>TO: </b>' . format_date($end_date) . '</td>';
        $table .= '</tr>';
        $table .= '</table>';
        $this->load->helper('pdf');
        $content = $this->load->view('reports/nursing/usg_report_ajax', $data, true);
        pdf_create($table, $content, 'ahms_usg_report', 'L');
        return;
    }

    function xray_registry() {
        $data["title"] = "X-Ray registry";
        $data['settings'] = get_application_settings(array('edit_flag'));
        $this->load->view('reports/nursing/xray_report', $data);
    }

    function get_xray_report() {
        $data["title"] = "X-ray report";
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $data["patient"] = $this->nursing_model->getXrayReports($start_date, $end_date);
        $data['is_print'] = FALSE;
        $this->load->view('reports/nursing/xray_report_ajax', $data);
    }

    function delete_record() {
        $xray_checkboxes = $this->input->post('check_del');
        $table_name = $this->input->post('tab');
        foreach ($xray_checkboxes as $checkbox) {
            $where = array('ID' => $checkbox);
            $this->nursing_model->delete_record($table_name, $where);
        }
    }

    function get_xray_report_print() {
        $data['title'] = "Xray report";
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $dept = $this->input->post('department');
        $data['is_print'] = TRUE;
        $data["patient"] = $this->nursing_model->getXrayReports($start_date, $end_date, $dept);
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1'); //300 seconds = 5 minutes
        $table = '<table width="100%">';
        $table .= '<tr>';
        $table .= '<td width="33%"></td><td width="33%" text-align:"center"; align="center"><h2>X-RAY REGISTER</h2></td><td width="33%" style="text-align:center;"><b>FROM: </b>' . format_date($start_date) . '&nbsp; <b>TO: </b>' . format_date($end_date) . '</td>';
        $table .= '</tr>';
        $table .= '</table>';
        $this->load->helper('pdf');
        $content = $this->load->view('reports/nursing/xray_report_ajax', $data, true);
        pdf_create($table, $content, 'ahms_xray_report', 'L');
        return;
    }

    function ksharasutra_registry() {
        $data["title"] = "Ksharasutra registry";
        $this->load->view('reports/nursing/kshara_report', $data);
    }

    function get_ksharasutra_report() {
        $data["title"] = "Ksharasutra report";
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $data["patient"] = $this->nursing_model->getKsharaReports($start_date, $end_date);
        $this->load->view('reports/nursing/kshara_report_ajax', $data);
    }

    function get_ksharasutra_report_print() {
        $data['title'] = "Ksharasutra report";
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $dept = $this->input->post('department');
        $data["patient"] = $this->nursing_model->getKsharaReports($start_date, $end_date, $dept);
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1'); //300 seconds = 5 minutes
        $table = '<table width="100%">';
        $table .= '<tr>';
        $table .= '<td width="33%"></td><td width="33%" text-align:"center"; align="center"><h2>KSHARASUTRA REGISTER</h2></td><td width="33%" style="text-align:center;"><b>FROM: </b>' . format_date($start_date) . '&nbsp; <b>TO: </b>' . format_date($end_date) . '</td>';
        $table .= '</tr>';
        $table .= '</table>';
        $this->load->helper('pdf');
        $content = $this->load->view('reports/nursing/kshara_report_ajax', $data, true);
        pdf_create($table, $content, 'ahms_xray_report', 'L');
        return;
    }

    function surgery_registry() {
        $data["title"] = "Surgery registry";
        $this->load->view('reports/nursing/surgery_report', $data);
    }

    function get_surgery_report() {
        $data["title"] = "Surgery report";
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $data["patient"] = $this->nursing_model->getSurgeryReports($start_date, $end_date);
        $this->load->view('reports/nursing/surgery_report_ajax', $data);
    }

    function get_surgery_report_print() {
        $data['title'] = "Surgery report";
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $dept = $this->input->post('department');
        $data["patient"] = $this->nursing_model->getSurgeryReports($start_date, $end_date, $dept);
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1'); //300 seconds = 5 minutes
        $table = '<table width="100%">';
        $table .= '<tr>';
        $table .= '<td width="33%"></td><td width="33%" text-align:"center"; align="center"><h2>SURGERY REGISTER</h2></td><td width="33%" style="text-align:center;"><b>FROM: </b>' . format_date($start_date) . '&nbsp; <b>TO: </b>' . format_date($end_date) . '</td>';
        $table .= '</tr>';
        $table .= '</table>';
        $this->load->helper('pdf');
        $content = $this->load->view('reports/nursing/surgery_report_ajax', $data, true);
        pdf_create($table, $content, 'ahms_surgery_report', 'L');
        return;
    }

    function panchakarma_registry() {
        $data["title"] = "Panchakarma registry";
        $this->load->view('reports/nursing/panchakarma_report', $data);
    }

    function get_panchakarma_report() {
        $data["title"] = "Panchakarma report";
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $data['is_print'] = FALSE;
        $data["patient"] = $this->nursing_model->getPanchaReports($start_date, $end_date);
        $this->load->view('reports/nursing/panchakarma_report_ajax', $data);
    }

    function get_panchakarma_report_print() {
        $data['title'] = "Panchakarma report";
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $dept = $this->input->post('department');
        $data["patient"] = $this->nursing_model->getPanchaReports($start_date, $end_date, $dept);
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1'); //300 seconds = 5 minutes
        $table = '<table width="100%">';
        $table .= '<tr>';
        $table .= '<td width="33%"></td><td width="33%" text-align:"center"; align="center"><h2>PANCHAKARMA REGISTER</h2></td><td width="33%" style="text-align:center;"><b>FROM: </b>' . format_date($start_date) . '&nbsp; <b>TO: </b>' . format_date($end_date) . '</td>';
        $table .= '</tr>';
        $table .= '</table>';
        $this->load->helper('pdf');
        $content = $this->load->view('reports/nursing/panchakarma_report_ajax', $data, true);
        pdf_create($table, $content, 'ahms_panchakarma_report', 'L');
        return;
    }

    function panchakarma_procedure() {
        $data["title"] = "Panchakarma procedure";
        $data['settings'] = get_application_settings(array('edit_flag'));
        $this->load->view('reports/nursing/panchakarma_procedure', $data);
    }

    function get_panchakarma_procedure_report() {
        $data["title"] = "Panchakarma procedure report";
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $data['is_print'] = FALSE;
        $data["patient"] = $this->nursing_model->getPanchaprocedureReports($start_date, $end_date);
        $this->load->view('reports/nursing/panchakarma_procedure_ajax', $data);
    }

    function get_panchakarma_procedure_report_print() {
        $data['title'] = "Panchakarma procedure report";
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $dept = $this->input->post('department');
        $data["patient"] = $this->nursing_model->getPanchaprocedureReports($start_date, $end_date, $dept);
        $data['is_print'] = TRUE;
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1'); //300 seconds = 5 minutes
        $table = '<table width="100%">';
        $table .= '<tr>';
        $table .= '<td width="13%"></td><td width="53%" text-align:"center"; align="center"><h2>PANCHAKARMA PROCEDURE REGISTER</h2></td><td width="33%" style="text-align:center;"><b>FROM: </b>' . format_date($start_date) . '&nbsp; <b>TO: </b>' . format_date($end_date) . '</td>';
        $table .= '</tr>';
        $table .= '</table>';
        $this->load->helper('pdf');
        $content = $this->load->view('reports/nursing/panchakarma_procedure_ajax', $data, true);
        pdf_create($table, $content, 'ahms_panchakarma_procedure_report', 'L');
        return;
    }

    function surgery_count() {
        $data["title"] = "Surgery count";
        $this->load->view('reports/nursing/surgery_count', $data);
    }

    function get_surgery_count_report() {
        $data["title"] = "Surgery count report";
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $data["patient"] = $this->nursing_model->getSurgeryCount($start_date, $end_date);
        $this->load->view('reports/nursing/surgery_count_ajax', $data);
    }

    function get_surgery_count_report_print() {
        $data['title'] = "Surgery count report";
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $dept = $this->input->post('department');
        $data["patient"] = $this->nursing_model->getSurgeryCount($start_date, $end_date, $dept);
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1'); //300 seconds = 5 minutes
        $table = '<table width="100%">';
        $table .= '<tr>';
        $table .= '<td width="23%"></td><td width="43%" text-align:"center"; align="center"><h2>SURGERY COUNT REGISTER</h2></td><td width="33%" style="text-align:center;"><b>FROM: </b>' . format_date($start_date) . '&nbsp; <b>TO: </b>' . format_date($end_date) . '</td>';
        $table .= '</tr>';
        $table .= '</table>';
        $this->load->helper('pdf');
        $content = $this->load->view('reports/nursing/surgery_count_ajax', $data, true);
        pdf_create($table, $content, 'ahms_surgery_counts_report', 'L');
        return;
    }

    function lab_count_registry() {
        $data["title"] = "Lab count";
        $this->load->view('reports/nursing/lab_count', $data);
    }

    function get_lab_count_report() {
        $data["title"] = "Lab count report";
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $data["patient"] = $this->nursing_model->getLabcountReports($start_date, $end_date);
        $this->load->view('reports/nursing/lab_count_ajax', $data);
    }

    function get_lab_count_report_print() {
        $data['title'] = "Lab count report";
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $dept = $this->input->post('department');
        $data["patient"] = $this->nursing_model->getLabcountReports($start_date, $end_date, $dept);
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1'); //300 seconds = 5 minutes
        $table = '<table width="100%">';
        $table .= '<tr>';
        $table .= '<td width="23%"></td><td width="43%" text-align:"center"; align="center"><h2>LAB COUNT REGISTER</h2></td><td width="33%" style="text-align:center;"><b>FROM: </b>' . format_date($start_date) . '&nbsp; <b>TO: </b>' . format_date($end_date) . '</td>';
        $table .= '</tr>';
        $table .= '</table>';
        $this->load->helper('pdf');
        $content = $this->load->view('reports/nursing/lab_count_ajax', $data, true);
        pdf_create($table, $content, 'ahms_lab_counts_report', 'L');
        return;
    }

    function lab_registry() {
        $data["title"] = "Lab Registry";
        $data['settings'] = get_application_settings(array('edit_flag'));
        if ($this->rbac->has_role('ADMIN')) {
            $this->load->view('includes/header', $data);
        } else {
            $this->load->view('lab/common/header', $data);
        }
        $this->load->view('reports/nursing/lab_registry', $data);
    }

    function get_lab_report() {
        $data["title"] = "Lab report";
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $data['is_print'] = FALSE;
        $data["patient"] = $this->nursing_model->getLabReports($start_date, $end_date);
        $this->load->view('reports/nursing/lab_registry_ajax', $data);
    }

    function get_lab_report_print() {
        $data['title'] = "Lab Report report";
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $dept = $this->input->post('department');
        $data['is_print'] = TRUE;
        $data["patient"] = $this->nursing_model->getLabReports($start_date, $end_date, $dept);
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1'); //300 seconds = 5 minutes
        $table = '<table width="100%">';
        $table .= '<tr>';
        $table .= '<td width="23%"></td><td width="43%" text-align:"center"; align="center"><h2>LABORATORY REGISTER</h2></td><td width="33%" style="text-align:center;"><b>FROM: </b>' . format_date($start_date) . '&nbsp; <b>TO: </b>' . format_date($end_date) . '</td>';
        $table .= '</tr>';
        $table .= '</table>';
        $this->load->helper('pdf');
        $content = $this->load->view('reports/nursing/lab_registry_ajax', $data, true);
        pdf_create($table, $content, 'ahms_lab_report', 'L');
        return;
    }

    function update_lab_data() {
        $test_name = $this->input->post('lab_test_name');
        $test_value = $this->input->post('lab_test_value');
        $test_range = $this->input->post('lab_test_range');

        if (substr_count($test_name, ",") == substr_count($test_value, ",")) {
            $update = array(
                'testName' => $test_name,
                'testvalue' => $test_value,
                'testrange' => $test_range,
            );
            $is_updated = $this->nursing_model->update_lab($update, $this->input->post('lab_id'));
            if ($is_updated) {
                echo json_encode(array('status' => "Updated sucessfully", 'code' => '1'));
            } else {
                echo json_encode(array('status' => "Failed to update please try agaib"));
            }
        } else {
            echo json_encode(array('status' => "Data mismatch in test name and test value"));
        }
    }

}

//class ends