<?php

ini_set("memory_limit", "-1");
set_time_limit(0);

class Auto extends SHV_Controller
{

    private $_is_admin = false;

    function __construct()
    {
        parent::__construct();
        if (!$this->rbac->is_admin()) {
            redirect('dashboard');
        }
        $this->load->helper('aadhaar_abha');
        $this->load->model('auto/m_auto');
        $this->_is_admin = $this->rbac->is_admin();
        $this->layout->title = "Record analysis";
    }

    function index()
    {
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Patients";
        $this->layout->title = "Record analysis";
        if ($this->input->post('key_word') == AUTO_PASS) {
            $n = $this->check_doctors_duty_records();
            $data['doc_duty_count'] = $n;
            $this->layout->data = $data;
            $this->layout->render(array('view' => 'auto/auto/move'));
        } else {
            echo "No Results Found";
            redirect('dashboard', 'refresh');
        }
    }

    function move()
    {
        $data['title'] = "Analysis";
        $this->layout->title = "Record analysis";
        $target = $this->input->post('target');
        $cdate = $this->input->post('cdate');
        $newpatient = $this->input->post('newpatient');
        $pancha_count = $this->input->post('pancha_count');
        $n = $this->check_doctors_duty_records();
        $data['doc_duty_count'] = $n;
        $data['message'] = $this->m_auto->auto_master($target, $cdate, $newpatient, $pancha_count);
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_doc()
    {
        echo $this->m_auto->get_day_doctor();
    }

    function show_reference_data()
    {
        $this->layout->title = "Reference data";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Reference data";
        $this->layout->navDescr = "";
        $this->scripts_include->includePlugins(array('datatables', 'jq_validation'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $data['is_admin'] = $this->_is_admin;
        $data['department_list'] = $this->get_department_list('array');
        $this->layout->data = $data;
        $this->layout->render();
    }

    function add_reference_data()
    {
        $this->layout->title = "Add reference data";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Add reference data";
        $this->layout->navDescr = "";
        $this->scripts_include->includePlugins(array('jq_validation'), 'js');

        $data['form_values'] = $this->session->flashdata('reference_form_values');
        $data['form_errors'] = $this->session->flashdata('reference_form_errors');
        $data['form_warnings'] = $this->session->flashdata('reference_form_warnings');
        $data['department_list'] = $this->get_department_list('array');
        $this->layout->data = $data;
        $this->layout->render(array('view' => 'auto/auto/reference_form'));
    }

    function get_reference_data()
    {
        $input_array = array();
        $search_key = $this->input->post('search');
        $input_array['start'] = $this->input->post('start');
        $input_array['length'] = $this->input->post('length');
        $input_array['order'] = $this->input->post('order');
        $input_array['keyword'] = $search_key['value'];
        $data = $this->m_auto->get_data($input_array);
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

    function save_patient_details()
    {
        $post_values = $this->input->post();
        $id = isset($post_values['ID']) ? (int) $post_values['ID'] : 0;
        if ($id <= 0) {
            echo json_encode(array('status' => false, 'msg' => 'Invalid reference record', 'type' => 'danger'));
            return;
        }

        $existing_record = $this->m_auto->get_reference_by_id($id);
        if (empty($existing_record)) {
            echo json_encode(array('status' => false, 'msg' => 'Reference record not found', 'type' => 'danger'));
            return;
        }

        $prepared = $this->_prepare_reference_payload($post_values, $existing_record, true);
        if (!$prepared['status']) {
            echo json_encode(array(
                'status' => false,
                'msg' => implode('<br/>', $prepared['errors']),
                'errors' => $prepared['errors'],
                'type' => 'danger'
            ));
            return;
        }

        $is_updated = $this->m_auto->update_patient_data($id, $prepared['data']);
        if ($is_updated) {
            echo json_encode(array(
                'status' => true,
                'msg' => 'Record updated successfully',
                'warnings' => $prepared['warnings'],
                'type' => 'success'
            ));
        } else {
            echo json_encode(array('status' => false, 'msg' => 'Failed to update, try again', 'type' => 'danger'));
        }
    }

    function store_reference_data()
    {
        if (!$this->input->post()) {
            redirect('show-ref/add');
        }

        $prepared = $this->_prepare_reference_payload($this->input->post());
        if (!$prepared['status']) {
            $this->session->set_flashdata('reference_form_values', $this->input->post());
            $this->session->set_flashdata('reference_form_errors', $prepared['errors']);
            $this->session->set_flashdata('reference_form_warnings', $prepared['warnings']);
            redirect('show-ref/add');
        }

        $insert_id = $this->m_auto->create_patient_data($prepared['data']);
        if ($insert_id) {
            $this->session->set_flashdata('reference_success', 'Reference record added successfully.');
            if (!empty($prepared['warnings'])) {
                $this->session->set_flashdata('reference_warnings', $prepared['warnings']);
            }
            redirect('show-ref');
        }

        $this->session->set_flashdata('reference_form_values', $this->input->post());
        $this->session->set_flashdata('reference_form_errors', array('Failed to save reference record. Please try again.'));
        redirect('show-ref/add');
    }

    function import_reference_data()
    {
        if (empty($_FILES['reference_csv']['name'])) {
            $this->session->set_flashdata('reference_errors', array('Please choose a CSV file to import.'));
            redirect('show-ref');
        }

        $upload_path = FCPATH . 'assets/uploads/reference_imports/';
        if (!is_dir($upload_path)) {
            @mkdir($upload_path, 0777, true);
        }

        $config = array(
            'upload_path' => $upload_path,
            'allowed_types' => 'csv',
            'max_size' => 4096,
            'encrypt_name' => true
        );

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('reference_csv')) {
            $this->session->set_flashdata('reference_errors', array(strip_tags($this->upload->display_errors())));
            redirect('show-ref');
        }

        $upload_data = $this->upload->data();
        $file_path = $upload_data['full_path'];
        $handle = fopen($file_path, 'r');
        if ($handle === false) {
            $this->session->set_flashdata('reference_errors', array('Unable to read the uploaded CSV file.'));
            @unlink($file_path);
            redirect('show-ref');
        }

        $header_row = fgetcsv($handle);
        if ($header_row === false) {
            fclose($handle);
            @unlink($file_path);
            $this->session->set_flashdata('reference_errors', array('The uploaded CSV file is empty.'));
            redirect('show-ref');
        }

        $header_map = $this->_build_csv_header_map($header_row);
        $required_headers = array('first name', 'age', 'gender', 'occupation', 'address', 'city', 'diagnosis', 'department', 'treatment', 'medicines');
        $missing_headers = array();
        foreach ($required_headers as $header) {
            if (!isset($header_map[$header])) {
                $missing_headers[] = $header;
            }
        }

        if (!empty($missing_headers)) {
            fclose($handle);
            @unlink($file_path);
            $this->session->set_flashdata('reference_errors', array('Missing CSV header(s): ' . implode(', ', $missing_headers)));
            redirect('show-ref');
        }

        $rows_to_insert = array();
        $errors = array();
        $warnings = array();
        $processed_count = 0;
        $skipped_count = 0;
        $line_number = 1;

        while (($row = fgetcsv($handle)) !== false) {
            ++$line_number;
            $row_data = $this->_map_csv_row_to_reference_input($row, $header_map);
            if ($this->_is_reference_row_empty($row_data)) {
                ++$skipped_count;
                continue;
            }

            ++$processed_count;
            $prepared = $this->_prepare_reference_payload($row_data);
            if (!$prepared['status']) {
                $errors[] = 'Row ' . $line_number . ': ' . implode('; ', $prepared['errors']);
                continue;
            }

            if (!empty($prepared['warnings'])) {
                $warnings[] = 'Row ' . $line_number . ': ' . implode('; ', $prepared['warnings']);
            }
            $rows_to_insert[] = $prepared['data'];
        }

        fclose($handle);
        @unlink($file_path);

        $inserted_count = 0;
        if (!empty($rows_to_insert)) {
            $inserted_count = (int) $this->m_auto->insert_reference_batch($rows_to_insert);
        }

        $summary = array(
            'processed_count' => $processed_count,
            'inserted_count' => $inserted_count,
            'skipped_count' => $skipped_count,
            'error_count' => count($errors),
            'warning_count' => count($warnings),
            'errors' => array_slice($errors, 0, 15),
            'warnings' => array_slice($warnings, 0, 15)
        );

        if ($inserted_count > 0) {
            $this->session->set_flashdata('reference_success', $inserted_count . ' reference record(s) imported successfully.');
        }
        if (!empty($errors)) {
            $this->session->set_flashdata('reference_errors', $summary['errors']);
        }
        if (!empty($warnings)) {
            $this->session->set_flashdata('reference_warnings', $summary['warnings']);
        }
        $this->session->set_flashdata('reference_import_summary', $summary);
        redirect('show-ref');
    }

    function download_reference_template()
    {
        $this->load->helper('download');
        $headers = array(
            'First Name',
            'Middle Name',
            'Last Name',
            'Age',
            'Gender',
            'Occupation',
            'Address',
            'City',
            'Mobile No',
            'Diagnosis',
            'Complaints',
            'Department',
            'Sub Department',
            'Procedures',
            'Treatment',
            'Medicines',
            'Aadhaar Number',
            'ABHA ID',
            'Notes'
        );
        $csv_content = implode(',', $headers) . PHP_EOL;
        force_download('reference_import_template.csv', $csv_content);
    }

    function delete_reference_data()
    {
        if (!$this->input->is_ajax_request()) {
            show_error('Invalid request', 400);
        }

        $id = (int) $this->input->post('id');
        if ($id <= 0) {
            echo json_encode(array('status' => false, 'msg' => 'Invalid reference record', 'type' => 'danger'));
            return;
        }

        $deleted = $this->m_auto->delete_patient_data($id);
        if ($deleted) {
            echo json_encode(array('status' => true, 'msg' => 'Record deleted successfully', 'type' => 'success'));
        } else {
            echo json_encode(array('status' => false, 'msg' => 'Failed to delete record', 'type' => 'danger'));
        }
    }

    function ipd()
    {
        $this->layout->title = "IPD";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "IPD";
        $this->layout->navDescr = "";
        $this->scripts_include->includePlugins(array('datatables'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $data['is_admin'] = $this->_is_admin;
        $this->load->model('auto/ipd_analysis_model');
        $data['departments'] = $this->ipd_analysis_model->fetch_department_data();
        $this->layout->data = $data;
        $this->layout->render();
    }

    function analyse_ipd_data()
    {
        $post_values = $this->input->post();
        $cdate = $post_values['cdate'];
        //unset($post_values['cdate']);
        pma($post_values, 1);
        // $this->load->model('auto/ipd_analysis_model');
        // $is_updated = $this->ipd_analysis_model->update_ipd_data($post_values, $cdate);
        // if ($is_updated) {
        //     echo json_encode(array('status' => true, 'msg' => 'IPD data updated successfully', 'type' => 'success'));
        // } else {
        //     echo json_encode(array('status' => false, 'msg' => 'Failed to update IPD data, try again', 'type' => 'danger'));
        // }
    }

    private function _prepare_reference_payload($input, $existing_record = array(), $is_update = false)
    {
        $fields = array(
            'FirstName', 'MidName', 'LastName', 'Age', 'gender', 'occupation', 'address', 'city',
            'Mobileno', 'diagnosis', 'complaints', 'department', 'sub_dept', 'procedures',
            'Trtment', 'medicines', 'notes', 'aadhaar_number', 'abha_id'
        );
        $merged_data = array();
        foreach ($fields as $field) {
            $value = isset($input[$field]) ? $input[$field] : (isset($existing_record[$field]) ? $existing_record[$field] : '');
            $merged_data[$field] = is_string($value) ? trim($value) : $value;
        }

        $errors = array();
        $warnings = array();

        if ($merged_data['FirstName'] === '') {
            $errors[] = 'First name is required.';
        }
        if ($merged_data['Age'] === '' || !ctype_digit((string) $merged_data['Age']) || (int) $merged_data['Age'] <= 0) {
            $errors[] = 'Age must be a valid positive number.';
        }

        $allowed_genders = array('Male', 'Female', 'Others');
        if (!in_array($merged_data['gender'], $allowed_genders, true)) {
            $errors[] = 'Gender must be Male, Female or Others.';
        }

        foreach (array('occupation' => 'Occupation', 'address' => 'Address', 'city' => 'City', 'diagnosis' => 'Diagnosis', 'department' => 'Department') as $field => $label) {
            if ($merged_data[$field] === '') {
                $errors[] = $label . ' is required.';
            }
        }

        if ($merged_data['Mobileno'] !== '' && !preg_match('/^[0-9]{10}$/', $merged_data['Mobileno'])) {
            $errors[] = 'Mobile number must contain exactly 10 digits when provided.';
        }

        $aadhaar = preg_replace('/\D+/', '', (string) $merged_data['aadhaar_number']);
        $abha_id = preg_replace('/\s+/u', '', trim((string) $merged_data['abha_id']));
        if ($aadhaar !== '' && !validate_aadhaar_format($aadhaar)) {
            $errors[] = 'Aadhaar must be exactly 12 digits and cannot start with 0.';
        }
        if ($abha_id !== '' && !validate_abha_id_format($abha_id)) {
            $errors[] = 'The ABHA ID format is invalid. Use either 14 digits or username@abdm format.';
        }
        if (($aadhaar !== '' || $abha_id !== '') && !$this->m_auto->has_oldtable_identity_columns()) {
            $missing_columns = $this->m_auto->get_missing_oldtable_identity_columns();
            $errors[] = 'Database schema is incomplete for old patient identity fields. Missing columns: '
                . implode(', ', $missing_columns)
                . '. Run `database_migrations/add_aadhaar_abha_to_oldtable.sql`.';
        }
        $merged_data['aadhaar_number'] = $aadhaar !== '' ? $aadhaar : null;
        $merged_data['abha_id'] = $abha_id !== '' ? $abha_id : null;
        if ($this->m_auto->has_oldtable_identity_columns()) {
            $merged_data['aadhaar_masked'] = $aadhaar !== '' ? mask_aadhaar($aadhaar) : null;
        }

        if ($merged_data['medicines'] === '' && $merged_data['Trtment'] !== '') {
            $merged_data['medicines'] = $merged_data['Trtment'];
            $warnings[] = 'Medicines was empty, so it was copied from Treatment.';
        }

        $treatment_result = $this->_normalize_reference_list_field($merged_data['Trtment'], 'Treatment');
        $medicine_result = $this->_normalize_reference_list_field($merged_data['medicines'], 'Medicines');
        $merged_data['Trtment'] = $treatment_result['value'];
        $merged_data['medicines'] = $medicine_result['value'];
        $warnings = array_merge($warnings, $treatment_result['warnings'], $medicine_result['warnings']);
        $errors = array_merge($errors, $treatment_result['errors'], $medicine_result['errors']);

        $merged_data['department'] = strtoupper(str_replace(' ', '_', preg_replace('/\s+/', ' ', $merged_data['department'])));
        $merged_data['sub_dept'] = preg_replace('/\s+/', ' ', $merged_data['sub_dept']);
        if ($merged_data['department'] === 'SHALAKYA_TANTRA') {
            if ($merged_data['sub_dept'] === '') {
                $errors[] = 'Sub Department is required for SHALAKYA_TANTRA.';
            }
        } else {
            $merged_data['sub_dept'] = '';
        }

        if (!$is_update) {
            $merged_data['AddedBy'] = $this->session->userdata('user_name') ? $this->session->userdata('user_name') : $this->rbac->get_logged_in_user_name();
            $merged_data['entrydate'] = date('Y-m-d');
        }

        return array(
            'status' => empty($errors),
            'errors' => $errors,
            'warnings' => array_values(array_unique($warnings)),
            'data' => $merged_data
        );
    }

    private function _normalize_reference_list_field($value, $label)
    {
        $result = array(
            'value' => '',
            'warnings' => array(),
            'errors' => array()
        );
        $value = trim((string) $value);
        if ($value === '') {
            $result['errors'][] = $label . ' is required.';
            return $result;
        }

        if (preg_match('/,\s*$/', $value)) {
            $result['warnings'][] = $label . ' had a trailing comma and was auto-cleaned.';
        }

        $parts = explode(',', $value);
        $clean_parts = array();
        foreach ($parts as $part) {
            $part = preg_replace('/\s+/', ' ', trim($part));
            if ($part === '') {
                continue;
            }
            $clean_parts[] = $part;
        }

        if (count($clean_parts) !== count($parts)) {
            $result['warnings'][] = $label . ' contained empty comma-separated values that were removed.';
        }

        if (empty($clean_parts)) {
            $result['errors'][] = $label . ' must contain at least one value.';
            return $result;
        }

        $result['value'] = implode(', ', $clean_parts);
        return $result;
    }

    private function _build_csv_header_map($header_row)
    {
        $header_map = array();
        foreach ($header_row as $index => $header) {
            $header = preg_replace('/^\xEF\xBB\xBF/', '', (string) $header);
            $normalized_header = strtolower(trim($header));
            if ($normalized_header !== '') {
                $header_map[$normalized_header] = $index;
            }
        }
        return $header_map;
    }

    private function _map_csv_row_to_reference_input($row, $header_map)
    {
        $field_map = array(
            'first name' => 'FirstName',
            'middle name' => 'MidName',
            'last name' => 'LastName',
            'age' => 'Age',
            'gender' => 'gender',
            'occupation' => 'occupation',
            'address' => 'address',
            'city' => 'city',
            'mobile no' => 'Mobileno',
            'diagnosis' => 'diagnosis',
            'complaints' => 'complaints',
            'department' => 'department',
            'sub department' => 'sub_dept',
            'procedures' => 'procedures',
            'treatment' => 'Trtment',
            'medicines' => 'medicines',
            'aadhaar number' => 'aadhaar_number',
            'abha id' => 'abha_id',
            'notes' => 'notes'
        );

        $mapped_data = array();
        foreach ($field_map as $header => $field) {
            $mapped_data[$field] = isset($header_map[$header], $row[$header_map[$header]]) ? trim($row[$header_map[$header]]) : '';
        }
        return $mapped_data;
    }

    private function _is_reference_row_empty($row_data)
    {
        foreach ($row_data as $value) {
            if (trim((string) $value) !== '') {
                return false;
            }
        }
        return true;
    }
}

//end of c_auto
