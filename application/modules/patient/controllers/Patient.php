<?php

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
        $this->layout->title = "OPD Registration";
        $this->scripts_include->includePlugins(array('jq_validation'), 'js');
        $this->scripts_include->includePlugins(array('jq_validation'), 'css');
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "OPD";
        $this->layout->navDescr = "Add new patient";
        $data = array();
        $data['dept_list'] = $this->get_department_list('array');
        $this->layout->data = $data;
        $this->layout->render();
    }

    function patient_list() {
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "OPD";
        $this->layout->navDescr = "Out Patient Department";
        $this->scripts_include->includePlugins(array('datatables', 'jq_validation'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $data = array();
        $this->layout->data = $data;
        $this->layout->render();
    }

    function update_patient_personal_information() {
        if ($this->input->is_ajax_request()) {
            $this->load->model('patient/patient_model');
            $post_values = $this->input->post();
            $is_updated = $this->patient_model->save_patient($post_values, $post_values['OpdNo']);
            if ($is_updated) {
                echo json_encode(array('msg' => 'Updated Successfully', 'status' => 'ok'));
            } else {
                echo json_encode(array('msg' => 'Faied to updat', 'status' => 'nok'));
            }
        }
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
        $this->treatment_model->add_patient_for_treatment($this->input->post());
        redirect('patient');
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
        $this->load->helper('pdf');
        $search_criteria = NULL;
        $input_array = array();

        foreach ($this->input->post() as $search_data => $val) {
            $input_array[$search_data] = $val;
            //$input_array[$search_data['name']] = $search_data['value'];
        }
        $result = $this->opd_model->get_patients($input_array, true);
        $headers = array(
            'OpdNo' => array('name' => 'OPD No', 'align' => 'C', 'width' => '10'),
            'FirstName' => array('name' => 'First name', 'align' => 'L', 'width' => '15'),
            'LastName' => array('name' => 'Last name', 'align' => 'L', 'width' => '15'),
            'Age' => array('name' => 'Age', 'align' => 'C', 'width' => '5'),
            'gender' => array('name' => 'Gender', 'align' => 'L', 'width' => '10'),
            'city' => array('name' => 'Place', 'align' => 'L', 'width' => '10'),
            'occupation' => array('name' => 'Ocuupation', 'align' => 'L', 'width' => '15'),
            'address' => array('name' => 'Address', 'align' => 'L', 'width' => '20'),
        );
        $html = generate_table_pdf($headers, $result['data']);
        $title = array(
            'report_title' => 'Patients list',
        );

        pdf_create($title, $html);
        exit;
    }

    function get_patient_by_opd() {
        $opd_id = $this->input->post('opd_id');
        $data['patient_data'] = $this->opd_model->get_patient_by_opd($opd_id);
        //$data['dept_list'] = $this->department_model->get_department_list();
        echo json_encode($data);
    }

    function followup() {
        $pat_type = OLD_PATIENT;
        $dept_opd = $this->get_next_dept_opd($this->input->post('department'));
        $treat_data = array(
            'OpdNo' => $this->input->post('opd'),
            'deptOpdNo' => $dept_opd,
            'PatType' => $pat_type,
            'department' => $this->input->post('department'),
            'AddedBy' => $this->input->post('doctor_name'),
            'CameOn' => $this->input->post('date'),
        );
        echo $this->opd_model->store_treatment("add", $treat_data);
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
        $this->layout->navTitleFlag = false;
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
        $this->add_nursing_indent($ipd);
        if ($is_updated) {
            echo 1;
        } else {
            echo 0;
        }
    }

    function add_nursing_indent($ipd) {
        $this->db->from('ipdtreatment it');
        $this->db->join('inpatientdetails ip', 'it.ipdno=ip.IpNo');
        $this->db->where('it.ipdno', $ipd);
        $treat_data = $this->db->get()->result_array();

        foreach ($treat_data as $treat) {
            $products = explode(',', $treat['Trtment']);
            $days = $treat['NofDays'];
            $doa = $treat['DoAdmission'];
            foreach ($products as $product) {
                if (strpos($product, 'BD')) {
                    $int = intval(preg_replace('/[^0-9]+/', '', $product), 10);
                    for ($i = 0; $i < $days; $i++) {
                        $doi = date('Y-m-d', strtotime($treat['DoAdmission'] . ' + ' . ( $i) . ' days'));
                        $form_data = array(
                            'indentdate' => $doi,
                            'ipdno' => $treat['IpNo'],
                            'opdno' => $treat['OpdNo'],
                            'product' => $product,
                            'morning' => $int,
                            'afternoon' => 0,
                            'night' => $int,
                            'totalqty' => 2,
                            'treatid' => $treat['ID'],
                        );
                        $this->db->insert('indent', $form_data);
                    }
                } else if (strpos($product, 'TD')) {
                    $int = intval(preg_replace('/[^0-9]+/', '', $product), 10);
                    for ($i = 0; $i < $days; $i++) {
                        $doi = date('Y-m-d', strtotime($treat['DoAdmission'] . ' + ' . ( $i + 1) . ' days'));
                        $form_data = array(
                            'indentdate' => $doi,
                            'ipdno' => $treat['IpNo'],
                            'opdno' => $treat['OpdNo'],
                            'product' => $product,
                            'morning' => $int,
                            'afternoon' => $int,
                            'night' => $int,
                            'totalqty' => 3,
                            'treatid' => $treat['ID'],
                        );
                        $this->db->insert('indent', $form_data);
                    }
                }
            }
            $this->db->where('ID', $treat['IpNo']);
            $this->db->update('ipdtreatment', array('status' => 'Treated'));
        }
    }

    function indent_list() {
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "IPD patients";
        $this->layout->navDescr = "In Patient Department";
        $this->scripts_include->includePlugins(array('datatables', 'js'));
        $data = array();
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_indent_patients() {
        $this->load->model('patient/ipd_model');
        $input_array = array();
        foreach ($this->input->post('search_form') as $search_data) {
            $input_array[$search_data['name']] = $search_data['value'];
        }
        $input_array['start'] = $this->input->post('start');
        $input_array['length'] = $this->input->post('length');
        $input_array['order'] = $this->input->post('order');
        $data = $this->ipd_model->get_indent_patients($input_array);
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

    function search_patient() {
        $this->layout->render();
    }

    function fetch_patient_info() {
        $this->load->model('patient_model');
        $opd = $this->input->post('opd_id');
        $data["patient_data"] = $this->patient_model->get_patient_info($opd);
        $this->layout->data = $data;
        $this->load->view('patient/patient/patient_view_ajax', $data);
    }

}
