<?php

/**
 * Description of Patient
 *
 * @author Shivaraj
 */
class Patient extends SHV_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('reports/opd_model');
        $this->load->helper('aadhaar_abha');
        $this->layout->navIcon = 'fa fa-users';
        $this->layout->title = "OPD";
    }

    function index()
    {
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

    function opd_registration()
    {
        $this->layout->title = "OPD Registration";
        $this->scripts_include->includePlugins(array('jq_validation'), 'js');
        $this->scripts_include->includePlugins(array('jq_validation'), 'css');
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "OPD";
        $this->layout->navDescr = "OPD Screening";
        $data = array();
        $data['dept_list'] = $this->get_department_list('array');
        $this->layout->data = $data;
        $this->layout->render();
    }

    function store_patient_info()
    {
        $this->load->model('patient/patient_model');
        $this->load->model('patient/treatment_model');
        $this->load->library('form_validation');
        $this->output->set_content_type('application/json');

        $raw_consultation_date = trim((string) $this->input->post('consultation_date'));
        $normalized_consultation_date = preg_match('/^\d{4}-\d{2}-\d{2}/', $raw_consultation_date, $matches) ? $matches[0] : $raw_consultation_date;
        $normalized_post = array(
            'first_name' => trim((string) $this->input->post('first_name')),
            'last_name' => trim((string) $this->input->post('last_name')),
            'age' => trim((string) $this->input->post('age')),
            'gender' => trim((string) $this->input->post('gender')),
            'mobile' => preg_replace('/\D+/', '', (string) $this->input->post('mobile')),
            'place' => trim((string) $this->input->post('place')),
            'occupation' => trim((string) $this->input->post('occupation')),
            'address' => trim((string) $this->input->post('address')),
            'consultation_date' => $normalized_consultation_date,
            'department' => trim((string) $this->input->post('department')),
            'sub_department' => trim((string) $this->input->post('sub_department')),
            'doctor' => trim((string) $this->input->post('doctor')),
            'aadhaar_number' => preg_replace('/\D+/', '', (string) $this->input->post('aadhaar_number')),
            'abha_id' => preg_replace('/\s+/u', '', trim((string) $this->input->post('abha_id')))
        );
        $normalized_post['aadhaar_masked'] = $normalized_post['aadhaar_number'] !== '' ? mask_aadhaar($normalized_post['aadhaar_number']) : null;

        $this->form_validation->set_data($normalized_post);
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required|max_length[256]');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|max_length[256]');
        $this->form_validation->set_rules('age', 'Age', 'trim|required|integer|greater_than[0]|less_than_equal_to[120]');
        $this->form_validation->set_rules('gender', 'Gender', 'trim|required|in_list[Male,Female,others]');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim');
        $this->form_validation->set_rules('place', 'Place', 'trim|required|max_length[256]');
        $this->form_validation->set_rules('occupation', 'Occupation', 'trim|required|max_length[256]');
        $this->form_validation->set_rules('address', 'Address', 'trim|required|max_length[1000]');
        $this->form_validation->set_rules('department', 'Department', 'trim|required');
        $this->form_validation->set_rules('doctor', 'Doctor', 'trim|required');
        $this->form_validation->set_rules('consultation_date', 'Consultation Date', 'trim|required|regex_match[/^\d{4}-\d{2}-\d{2}$/]');
        $this->form_validation->set_rules('aadhaar_number', 'Aadhaar Number', 'trim');
        $this->form_validation->set_rules('abha_id', 'ABHA ID', 'trim|max_length[50]');
        $this->form_validation->set_message('required', 'The {field} field is required.');
        $this->form_validation->set_message('integer', 'The {field} field must be a whole number.');
        $this->form_validation->set_message('regex_match', 'The {field} format is invalid.');
        $this->form_validation->set_message('in_list', 'The {field} field contains an invalid value.');

        if ($this->form_validation->run() == false) {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $this->form_validation->error_array()
            ));
            return;
        }

        $manual_errors = array();
        if ($normalized_post['aadhaar_number'] !== '' && !$this->validate_aadhaar($normalized_post['aadhaar_number'])) {
            $manual_errors['aadhaar_number'] = 'Aadhaar must be exactly 12 digits and cannot start with 0.';
        }
        if ($normalized_post['mobile'] !== '' && !preg_match('/^[6-9][0-9]{9}$/', $normalized_post['mobile'])) {
            $manual_errors['mobile'] = 'Please enter a valid 10-digit mobile number.';
        }
        if ($normalized_post['abha_id'] !== '' && !$this->validate_abha_format($normalized_post['abha_id'])) {
            $manual_errors['abha_id'] = 'The ABHA ID format is invalid. Use either 14 digits or username@abdm format.';
        }
        if (!$this->validate_consultation_date($normalized_post['consultation_date'])) {
            $manual_errors['consultation_date'] = 'Consultation Date must be in YYYY-MM-DD format.';
        }
        if ($normalized_post['aadhaar_number'] !== '' && $this->patient_model->has_aadhaar_column() && $this->patient_model->check_aadhaar_exists($normalized_post['aadhaar_number'])) {
            $manual_errors['aadhaar_number'] = 'This Aadhaar number is already registered in the system.';
        }
        if (!empty($manual_errors)) {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $manual_errors
            ));
            return;
        }

        $has_identity_input = ($normalized_post['aadhaar_number'] !== '' || $normalized_post['abha_id'] !== '');
        $missing_columns = $this->patient_model->get_missing_identity_columns();
        if ($has_identity_input && !empty($missing_columns)) {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Patient identity columns are missing in the database.',
                'errors' => array(
                    'aadhaar_number' => 'Database schema is incomplete. Missing columns: ' . implode(', ', $missing_columns) . '. Run `database_migrations/add_aadhaar_abha_to_patient.sql` before saving Aadhaar and ABHA details.',
                    'abha_id' => 'Database schema is incomplete. Missing columns: ' . implode(', ', $missing_columns) . '. Run `database_migrations/add_aadhaar_abha_to_patient.sql` before saving Aadhaar and ABHA details.'
                )
            ));
            return;
        }

        $insert_result = $this->treatment_model->add_patient_for_treatment($normalized_post);
        if (!empty($insert_result['status'])) {
            $this->log_aadhaar_access($insert_result['opd_no'], $this->rbac->get_uid(), 'insert');
            echo json_encode(array(
                'status' => 'success',
                'message' => 'Patient registered successfully!',
                'opd_no' => $insert_result['opd_no']
            ));
            return;
        }

        echo json_encode(array(
            'status' => 'error',
            'message' => !empty($insert_result['message']) ? $insert_result['message'] : 'Failed to register patient. Please try again.'
        ));
    }

    public function validate_aadhaar($aadhaar)
    {
        return validate_aadhaar_format($aadhaar);
    }

    public function validate_abha_format($abha_id)
    {
        return validate_abha_id_format($abha_id);
    }

    public function validate_consultation_date($consultation_date)
    {
        $consultation_date = trim((string) $consultation_date);
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $consultation_date)) {
            return false;
        }
        list($year, $month, $day) = array_map('intval', explode('-', $consultation_date));
        return checkdate($month, $day, $year);
    }

    private function log_aadhaar_access($opd_no, $user_id, $action)
    {
        if (!$this->patient_model->has_aadhaar_access_log_table()) {
            return;
        }

        $this->db->insert('aadhaar_access_log', array(
            'opd_no' => $opd_no,
            'accessed_by' => $user_id,
            'action' => $action
        ));
    }

    function generate_opd_card()
    {
        require_once './vendor/autoload.php';
        $mpdf = new \Mpdf\Mpdf([
            'format' => [90, 80], // Width x Height in mm, customize as per card size
            'margin_top' => 5,
            'margin_bottom' => 5,
            'margin_left' => 5,
            'margin_right' => 5,
        ]);
        ini_set("pcre.backtrack_limit", "10000000");

        $this->load->model('patient/patient_model');
        $opd_id = $this->input->get('opd_id');
        $patient_data = $this->patient_model->get_patient_info($opd_id);
        $patient_data = $patient_data['opd_data'][0];
        if ($patient_data) {
            $config = $this->db->get('config');
            $config = $config->row_array();

            //Html page design
            $header = '<table width="100%" style="margin-bottom: 2px; " border="0" cellspacing="0" cellpadding="0">'
                . '<tr>'
                . '<td width="15%" style="text-align: center;" border="0" padding="1px">'
                . '<img src="data:image/png;base64,' . base64_encode($config['logo_img']) . '" width="60px" height="60px" />'
                . '</td>'
                . '<td width="85%" style="text-align: left;" border="0">'
                . '<h6 style="margin: 1pt; font-size: 10pt;">' . $config["college_name"] . '</h6>'
                . '</td>'
                . '</tr>'
                . '</table>
                <span style="font-size:6pt;text-align:justify;">(Permitted by Govt. of karnataka, Affiliated to Rajiv Ghandhi University of Heath Science (RGUHS) Karnataka
                 Bangaluru, Recognized by National Commission for Indian System of Medicine(NCISM) New Delhi & Ministry of AYUSH New Delhi)</span>
                <hr style="margin: 2px 0; border: none; border-top: 1px solid #000;" />';

            $opdBox = '
<div style="text-align: center; margin-bottom: 10px; margin-left:35px;">
    <div style="
        display: inline-block;
        border: 1px solid #000;
        border-radius: 8px;
        padding: 8px;
        width: 200px;
        font-family: sans-serif;
        text-align: center;
    ">
        <div style="
            font-size: 10pt;
            font-weight: bold;
            background-color: #e6f2ff;
            padding: 4px 0;
            border-bottom: 1px solid #ccc;
        ">
            Out Patient Department
        </div>
        <div style="
            font-size: 14pt;
            margin-top: 8px;
            font-weight: bold;
        ">
            ' . $patient_data['OpdNo'] . '
        </div>
    </div>
</div>
';
            $qr = '
            <div style="text-align: right; margin-top: 2px;">
                <barcode code="' . $patient_data['OpdNo'] . '" type="QR" size="1" error="M" />
            </div>';
            $html = '<div style="background-color: #e6f2ff; border: 1px solid #000; border-radius: 8px; padding: 5px; height: 100%;">'
                . $header . '
            <p style="margin: 2px 4px;"><b>Name:</b> ' . $patient_data['name'] . ' (' . $patient_data['gender'] . ')' . ' (' . $patient_data['Age'] . ') </p>'
                . $opdBox .
                '<p style="margin: 2px 4px;font-size:12px"><b>Date:</b>' . $patient_data['entrydate'] . '</p>' .
                '<p style="margin: 2px 4px;font-size:10px"><b>Please bring this card for every visit.</b></p>' .
                '</div>';

            $mpdf->WriteHTML($html);
            $mpdf->AddPage();

            // Page 2: Back Side – Instructions
            $back = '
<div style="background-color: #e6f2ff; border: 1px solid #000; border-radius: 8px; padding: 5px; height: 100%;">
    <h3 style="text-align:center;">-: Information for Public :-</h3>
    <ol style="font-size:10px">
        <li>This hospital is a public run institution.</li>
        <li>The services here is your right, their growth is your responsibility.</li>
        <li>Seek medical advice for treatment of disease and protection of heath</li>
        <li>Special ayurveda, Yoga and Naturopathy available</li>
    </ol>
    ' . $qr . '
</div>';


            $mpdf->WriteHTML($back);

            $mpdf->Output('opd_card.pdf', 'I'); // 'I' for inline display; use 'D' to force download

            exit;
        } else {
            echo 'No patient data found for the given OPD ID.';
        }
    }

    function print_opd_card()
    {
        require_once './vendor/autoload.php';
        $mpdf = new \Mpdf\Mpdf([
            'format' => [210, 80], // Width x Height in mm, customize as per card size
            'margin_top' => 5,
            'margin_bottom' => 5,
            'margin_left' => 5,
            'margin_right' => 5,
        ]);
        ini_set("pcre.backtrack_limit", "10000000");

        $this->load->model('patient/patient_model');
        $opd_id = $this->input->get('opd_id');
        if (empty($opd_id) || $opd_id == null) {
            echo "Empty OPD please try again!";
            exit;
        }
        $patient_data = $this->patient_model->get_patient_info($opd_id);
        $patient_data = $patient_data['opd_data'][0];
        if ($patient_data) {
            $config = $this->db->get('config');
            $config = $config->row_array();

            //Html page design
            $header = '<table width="100%" style="margin-bottom: 2px; " border="0" cellspacing="0" cellpadding="0">'
                . '<tr>'
                . '<td width="15%" style="text-align: center;" border="0" padding="1px">'
                . '<img src="data:image/png;base64,' . base64_encode($config['logo_img']) . '" width="60px" height="60px" />'
                . '</td>'
                . '<td width="85%" style="text-align: left;" border="0">'
                . '<h6 style="margin: 1pt; font-size: 10pt;">' . $config["college_name"] . '</h6>'
                . '</td>'
                . '</tr>'
                . '</table>
                <span style="font-size:6pt;text-align:justify;">(Permitted by Govt. of karnataka, Affiliated to Rajiv Ghandhi University of Heath Science (RGUHS) Karnataka
                 Bangaluru, Recognized by National Commission for Indian System of Medicine(NCISM) New Delhi & Ministry of AYUSH New Delhi)</span>
                <hr style="margin: 2px 0; border: none; border-top: 1px solid #000;" />';

            $opdBox = '
<div style="text-align: center; margin-bottom: 10px; margin-left:35px;">
    <div style="
        display: inline-block;
        border: 1px solid #000;
        border-radius: 8px;
        padding: 8px;
        width: 200px;
        font-family: sans-serif;
        text-align: center;
    ">
        <div style="
            font-size: 10pt;
            font-weight: bold;
            background-color: #e6f2ff;
            padding: 4px 0;
            border-bottom: 1px solid #ccc;
        ">
            Out Patient Department
        </div>
        <div style="
            font-size: 14pt;
            margin-top: 8px;
            font-weight: bold;
        ">
            ' . $patient_data['OpdNo'] . '
        </div>
    </div>
</div>
';
            $qr = '
            <div style="text-align: right; margin-top: 2px;">
                <barcode code="' . $patient_data['UHID'] . '" type="QR" size="1" error="M" />
            </div>';
            $html = '<div style="background-color: #e6f2ff; border: 1px solid #000; border-radius: 8px; padding: 8px; font-size:12px;">'
                . $header
                . '<table width="100%" cellpadding="4" cellspacing="0" style="font-size:12px;">'
                . '  <tr>'
                . '    <td><b>UHID:</b> ' . $patient_data['UHID'] . '</td>'
                . '    <td><b>Date:</b> ' . $patient_data['entrydate'] . '</td>'
                . '  </tr>'
                . '  <tr>'
                . '    <td  colspan="2"><b>OPD No:</b> ' . $patient_data['OpdNo'] . '</td>'
                . '  </tr>'
                . '  <tr>'
                . '    <td colspan="2"><b>Name:</b> ' . $patient_data['name'] . ' (' . $patient_data['gender'] . '), ' . $patient_data['Age'] . ' yrs</td>'
                . '  </tr>'
                . '  <tr>'
                . '    <td colspan="2" style="font-size:10px;"><b>Note:</b> Please bring this card for every visit.</td>'
                . '  </tr>'
                . '</table>'
                . '</div>';


            $mpdf->WriteHTML($html);
            $mpdf->AddPage();

            // Page 2: Back Side – Instructions
            $back = '
<div style="background-color: #e6f2ff; border: 1px solid #000; border-radius: 8px; padding: 5px;">
    <h3 style="text-align:center;">-: Information for Public :-</h3>
    <ol style="font-size:10px">
        <li>This hospital is a public run institution.</li>
        <li>The services here is your right, their growth is your responsibility.</li>
        <li>Seek medical advice for treatment of disease and protection of heath</li>
        <li>Special ayurveda, Yoga and Naturopathy available</li>
    </ol>
    ' . $qr . '
</div>';


            $mpdf->WriteHTML($back);

            $mpdf->Output('opd_card.pdf', 'I'); // 'I' for inline display; use 'D' to force download

            exit;
        } else {
            echo 'No patient data found for the given OPD ID.';
        }
    }

    function patient_list()
    {
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "OPD";
        $this->layout->navDescr = "Out Patient Department";
        $this->scripts_include->includePlugins(array('datatables', 'jq_validation'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $data = array();
        $this->layout->data = $data;
        $this->layout->render();
    }

    function opd_screening_list()
    {
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "OPD";
        $this->layout->navDescr = "Out Patient Department";
        $this->scripts_include->includePlugins(array('datatables', 'jq_validation'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $data = array();
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_patients_list_screening()
    {
        $input_array = array();
        foreach ($this->input->post('search_form') as $search_data) {
            $input_array[$search_data['name']] = $search_data['value'];
        }
        $input_array['start'] = $this->input->post('start');
        $input_array['length'] = $this->input->post('length');
        $input_array['order'] = $this->input->post('order');
        $data = $this->opd_model->get_patients_screening($input_array);
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

    function update_patient_personal_information()
    {
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

    function sub_dept()
    {
        return $this->get_sub_department();
    }

    function get_patients_list()
    {
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

    function export_patients_list()
    {
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

    function save()
    {
        $this->store_patient_info();
    }

    public function check_aadhaar_duplicate()
    {
        $this->load->model('patient/patient_model');
        $this->output->set_content_type('application/json');
        $aadhaar = preg_replace('/\D+/', '', (string) $this->input->post('aadhaar_number'));

        if (!$this->patient_model->has_aadhaar_column()) {
            echo json_encode(array(
                'exists' => false,
                'schema_ready' => false,
                'message' => 'Aadhaar column is missing in patientdata table.'
            ));
            return;
        }

        $exists = $this->patient_model->check_aadhaar_exists($aadhaar);
        echo json_encode(array(
            'exists' => $exists,
            'schema_ready' => true,
            'message' => $exists ? 'Aadhaar already registered' : 'Aadhaar is available'
        ));
    }

    function export_pdf()
    {
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

    function export_patients_list_pdf()
    {

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

    function get_patient_by_opd()
    {
        $opd_id = $this->input->post('opd_id');
        $data['patient_data'] = $this->opd_model->get_patient_by_opd($opd_id);
        //$data['dept_list'] = $this->department_model->get_department_list();
        echo json_encode($data);
    }

    function followup()
    {
        $pat_type = OLD_PATIENT;
        $dept_opd = $this->get_next_dept_opd($this->input->post('department'));
        $treat_data = array(
            'OpdNo' => $this->input->post('opd'),
            'deptOpdNo' => $dept_opd,
            'PatType' => $pat_type,
            'department' => $this->input->post('department'),
            'sub_department' => $this->input->post('sub_branch'),
            'AddedBy' => $this->input->post('doctor_name'),
            'CameOn' => $this->input->post('date'),
        );
        echo $this->opd_model->store_treatment("add", $treat_data);
    }

    function followup_opd_screening()
    {
        $pat_type = NEW_PATIENT;
        $dept_opd = $this->get_next_dept_opd($this->input->post('department'));
        $treat_data = array(
            'OpdNo' => $this->input->post('opd'),
            'deptOpdNo' => $dept_opd,
            'PatType' => $pat_type,
            'department' => $this->input->post('department'),
            'sub_department' => $this->input->post('sub_branch'),
            'AddedBy' => $this->input->post('doctor_name'),
            'CameOn' => $this->input->post('date'),
        );

        $vitals_data = array(
            'opd_no' => $this->input->post('opd'),
            'date' => $this->input->post('date'),
            'blood_pressure' => $this->input->post('blood_pressure'),
            'pulse_rate' => $this->input->post('pulse_rate'),
            'respiratory_rate' => $this->input->post('respiratory_rate'),
            'body_temperature' => $this->input->post('body_temperature'),
            'spo2' => $this->input->post('oxygen_saturation'),
            'weight' => $this->input->post('weight'),
        );

        $this->db->insert('patient_vitals', $vitals_data);

        echo $this->opd_model->store_treatment("add", $treat_data);
    }

    function old_patient_entry()
    {
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

    function ipd_list()
    {
        $this->load->model('patient/treatment_model');
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "IPD patients";
        $this->layout->navDescr = "In Patient Department";
        $this->layout->title = "IPD";
        $this->scripts_include->includePlugins(array('datatables'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $data = array();
        $data['wards'] = $this->treatment_model->getBedno(true);
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_ipd_patients_list()
    {
        $this->load->model('patient/ipd_model');
        $input_array = array();
        foreach ($this->input->post('search_form') as $search_data) {
            $input_array[$search_data['name']] = $search_data['value'];
        }
        $search_key = $this->input->post('search');
        $input_array['keyword'] = $search_key['value'];
        $input_array['start'] = $this->input->post('start');
        $input_array['length'] = $this->input->post('length');
        $input_array['order'] = $this->input->post('order');
        $data = $this->ipd_model->get_patients($input_array);
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

    function date_difference()
    {
        date_default_timezone_set("Asia/Kolkata");
        //        echo $from = strtotime($this->input->post('dod'));
        //        echo $to = strtotime($this->input->post('doa'));
        //        $difference = $from - $to;
        //        $days = floor($difference / (60 * 60 * 24));
        // Convert to DateTime objects
        $to = $this->input->post('dod');
        $start_date = $this->input->post('doa');
        $start = new DateTime($start_date);
        $end = new DateTime($to);
        $end->modify('+1 day');
        $interval = $start->diff($end);
        $total_days = $interval->days;
        echo json_encode($total_days);
    }

    function discharge_patient()
    {
        $this->load->model('patient/treatment_model');
        $ipd = $this->input->post('dis_ipd');
        $discharge_date = $this->input->post('dod');
        $discharge_time = $this->input->post('discharge_time');
        $note = $this->input->post('notes');
        $no_of_days = $this->input->post('days');
        $discharge_by = $this->input->post('treated');

        $discharge_arr = array(
            'DoDischarge' => $discharge_date,
            'discharge_time' => $discharge_time,
            'DischargeNotes' => $note,
            'NofDays' => $no_of_days,
            'DischBy' => $discharge_by,
            'status' => 'discharge'
        );

        $opd_data = $this->treatment_model->get_opd_by_ipd($ipd);
        $this->treatment_model->update_bed_details($opd_data['BedNo']);
        $is_updated = $this->treatment_model->discharge_patient($ipd, $discharge_arr);
        $this->add_nursing_indent($ipd);
        if ($is_updated) {
            echo 1;
        } else {
            echo 0;
        }
    }

    function add_nursing_indent($ipd)
    {
        $this->db->from('ipdtreatment it');
        $this->db->join('inpatientdetails ip', 'it.ipdno=ip.IpNo');
        $this->db->where('it.ipdno', $ipd);
        $treat_data = $this->db->get()->result_array();

        foreach ($treat_data as $treat) {
            $products = explode(',', $treat['Trtment']);
            $days = $treat['NofDays'];
            $doa = $treat['DoAdmission'];
            foreach ($products as $product) {
                if (strpos($product, 'BD') || strpos($product, 'BID')) {
                    $int = intval(preg_replace('/[^0-9]+/', '', $product), 10);
                    for ($i = 0; $i < $days; $i++) {
                        $doi = date('Y-m-d', strtotime($treat['DoAdmission'] . ' + ' . ($i) . ' days'));
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
                } else if (strpos($product, 'TD') || strpos($product, 'TID')) {
                    $int = intval(preg_replace('/[^0-9]+/', '', $product), 10);
                    for ($i = 0; $i < $days; $i++) {
                        $doi = date('Y-m-d', strtotime($treat['DoAdmission'] . ' + ' . ($i + 1) . ' days'));
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

    function indent_list()
    {
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "IPD patients";
        $this->layout->navDescr = "In Patient Department";
        $this->scripts_include->includePlugins(array('datatables'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $data = array();
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_indent_patients()
    {
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

    function search_patient()
    {
        $this->layout->render();
    }

    function fetch_patient_info()
    {
        $this->load->model('patient_model');
        $opd = $this->input->post('opd_id');
        $data["patient_data"] = $this->patient_model->get_patient_info($opd);
        $this->layout->data = $data;
        $this->load->view('patient/patient/patient_view_ajax', $data);
    }

    function print_ipd_case_sheet($ipd = null)
    {
        if ($ipd) {
            $this->load->model('patient/treatment_model');
            $data = array();
            $app_settings = $this->application_settings(array('college_name'));
            $data['ipd'] = $ipd;
            $opd_data = $this->treatment_model->get_opd_by_ipd($ipd);
            $opd = $opd_data['OpdNo'];
            $patient_details = $this->treatment_model->get_ipd_patient_basic_details($opd, $ipd);
            $data['treatment_details'] = $this->treatment_model->get_ipd_treatment_history($ipd);

            $config = $this->db->get('config');
            $config = $config->row_array();
            $header = '<table width="100%" style="border:1px red solid"><tr>
                    <td width="10%"><img src="' . base_url('assets/your_logo.png') . '" width="80" height="80" alt="logo"></td>
                    <td width="90%"><h2 align="center">' . $config["college_name"] . '</h2></td>
                  </tr></table>';
            $pat_table = '';
            $pat_table .= $header;
            $pat_table .= '<table class="table"  width="100%"><tr><td align="center" width="100%" style="font-size:14pt">IN PATIENT CASE SHEET</td></tr></table><br/>';
            $pat_table .= "<table class='' width='100%' style='font-size:10pt'>";
            $pat_table .= "<tr>";
            $pat_table .= "<td width='50%'><b>UHID:</b> " . $patient_details['UHID'] . "</td>";
            $pat_table .= "<td  width='25%'><b>OPD NO:</b> " . $opd . "</td>";
            $pat_table .= "<td width='25%'><b>IPD NO:</b> " . $ipd . "</td>";
            $pat_table .= "</tr>";
            $pat_table .= "<tr>";
            $pat_table .= "<td width='50%'><b>BED:</b> " . $patient_details['BedNo'] . "</td>";
            $pat_table .= "<td width='50%'><b>WARD NO:</b> " . $patient_details['WardNo'] . "</td>";
            $pat_table .= "</tr>";
            $pat_table .= "<tr><td width='100%' colspan=3></td></tr>";
            $pat_table .= "<tr>";
            $pat_table .= "<td width='50%'><b>NAME:</b>  " . $patient_details['FirstName'] . "</td>";
            $pat_table .= "<td width='25%'><b>AGE:</b> " . $patient_details['Age'] . "</td>";
            $pat_table .= "<td width='25%'><b>SEX:</b> " . $patient_details['gender'] . "</td>";
            $pat_table .= "</tr>";
            $pat_table .= "<tr>";
            $pat_table .= "<td width='50%'><b>ABHA ID:</b> " . (!empty($patient_details['abha_id']) ? $patient_details['abha_id'] : 'N/A') . "</td>";
            $pat_table .= "<td width='50%' colspan='2'><b>AADHAAR:</b> " . (!empty($patient_details['aadhaar_masked']) ? $patient_details['aadhaar_masked'] : 'N/A') . "</td>";
            $pat_table .= "</tr>";
            $pat_table .= "<tr>";
            $pat_table .= "<td width='50%'><b>OCCUPATION:</b> " . $patient_details['occupation'] . "</td>";
            $pat_table .= "<td width='50%'><b>ADDRESS:</b>  " . $patient_details['address'] . $patient_details['city'] . "</td>";
            $pat_table .= "</tr>";
            $pat_table .= "<tr><td></td></tr>";
            $pat_table .= "<tr>";
            $pat_table .= "<td width='50%'><b>DOA:</b> " . $patient_details['DoAdmission'] . " : " . @$patient_details['admit_time'] . "</td>";
            $pat_table .= "<td width='50%'><b>DEPARTMENT:</b> " . ucfirst(strtolower(str_replace('_', ' ', $patient_details['department']))) . "</td>";
            $pat_table .= "</tr>";
            $pat_table .= "<tr>";
            $pat_table .= "<td width='50%'><b>DOD:</b> " . $patient_details['DoDischarge'] . " : " . @$patient_details['discharge_time'] . "</td>";
            $pat_table .= "<td width='50%'><b>REFERRED DR:</b> " . $patient_details['Doctor'] . "</td>";
            $pat_table .= "</tr>";
            $pat_table .= "<tr>";
            $pat_table .= "<td width='50%'><b>DIAGNOSIS:</b> " . $patient_details['diagnosis'] . " </td>";
            $pat_table .= "</tr>";
            $pat_table .= "</table><hr/>";
            $pat_table .= "<h3 style='text-align:center'>CONSENT FORM</h3>";
            $pat_table .= "<p style='text-align:justify;border:1px black solid;'>The under signed patient and/or responsible relatives here by consent/ or authorize to 
                " . $app_settings['college_name'] . " , physicians and medical persons to administer and perform medicaltreatment/ 
                panchakarma procedures/ surgery/ Ksharasutra/ ksharakarma/ agnikarma/ raktamokshana/and if necessary anesthesia.The doctors explained me about the disease, 
                condition of the disease,prognosis,line of treatment and procedures ofsurgery and panchakarma in regional language. 
                The Physicians ,Hospital faculty and hospital are not responsible for any sort of complications.</p>";
            $pat_table .= "<h6 style='text-align:right'>SIGNATURE</h6>";
            $pat_table .= "<h3 style='text-align:center'>HISTORY</h3>";

            $treat_table = "<table class='' width='100%' style='font-size:10pt'>";
            $treat_table .= "<tr>";
            $treat_table .= "<td width='50%' style='height: 100px; overflow:hidden;'><div>PRADHANA VEDANA:</div></td>";
            $treat_table .= "</tr>";
            $treat_table .= "<tr>";
            $treat_table .= "<td width='50%'  style='height: 100px; overflow:hidden;'>ANUBANDHI VEDANA:</td>";
            $treat_table .= "</tr>";
            $treat_table .= "<tr>";
            $treat_table .= "<td width='50%' style='height: 100px; overflow:hidden;'>VEDANA VRITTANTA:</td>";
            $treat_table .= "</tr>";
            $treat_table .= "<tr>";
            $treat_table .= "<td width='50%' style='height: 100px; overflow:hidden;'>POORVA VYADHI VRITTANT:</td>";
            $treat_table .= "</tr>";
            $treat_table .= "</table>";
            $html = $pat_table . $treat_table . '</div>';
            //echo $html;
            generate_pdf($html, 'P', '', 'ipd_case_sheets_' . $ipd . '_' . time(), false, false, 'I');
            exit;
        }
    }
}
