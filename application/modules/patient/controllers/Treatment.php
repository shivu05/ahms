<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Treatment
 *
 * @author Shivaraj
 */
class Treatment extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('patient/treatment_model');
        $this->layout->navIcon = "fa fa-user-md ";
        $this->layout->title = "OPD Treatment";
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "OPD Treatment";
        $this->layout->navDescr = "Out Patient Department";
    }

    function show_patients() {
        $this->scripts_include->includePlugins(array('datatables', 'js'));
        $data = array();
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_patients_for_treatment() {
        $input_array = array();
        foreach ($this->input->post('search_form') as $search_data) {
            $input_array[$search_data['name']] = $search_data['value'];
        }
        $input_array['start'] = $this->input->post('start');
        $input_array['length'] = $this->input->post('length');
        $input_array['order'] = $this->input->post('order');
        $data = $this->treatment_model->get_patients($input_array);
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

    function add_treatment($opd = NULL, $treat_id = NULL) {
        $this->scripts_include->includePlugins(array('chosen', 'typeahead'), 'js');
        $this->scripts_include->includePlugins(array('chosen'), 'css');
        $this->load->model('patient/lab_model');
        $data = array();
        $data['opd'] = $opd;
        $data['treat_id'] = $treat_id;
        $data['patient_details'] = $this->treatment_model->get_patient_basic_details($opd);
        $data['treatment_details'] = $this->treatment_model->get_treatment_history($opd);
        $data['wards'] = $this->treatment_model->getBedno();
        $treatment_details = $this->treatment_model->get_current_treatment_info($opd, $treat_id);
        $data['med_freqs'] = $this->get_medicine_frequency();
        $data['medicines'] = $this->get_product_list();
        $data['lab_categories'] = $this->lab_model->get_lab_categories();
        $data['current_treatment'] = $treatment_details['treatment_data'];
        //$data['doctors'] = $treatment_details['doctors'];
        $data['doctors'] = $this->get_doctors($treatment_details['treatment_data']['department']);
        //pma($data['doctors'], 1);
        $this->layout->data = $data;
        $this->layout->render();
    }

    function add_pharmcy($treat_id, $type = 'opd') {
        $this->treatment_model->add_to_pharmacy($treat_id, $type);
    }

    function save() {
        $treat_id = $this->input->post('treat_id');
        $is_admit = ($this->input->post('admit') == 'on') ? 'Admit' : 'FollowUp';
        //Treatment data
        $treatpatientdata = array(
            'Trtment' => $this->input->post('treatment'),
            'diagnosis' => $this->input->post('diagnosis'),
            'complaints' => $this->input->post('complaints'),
            'procedures' => $this->input->post('panch_procedures'),
            'notes' => $this->input->post('notes'),
            'InOrOutPat' => $is_admit,
            'attndedby' => $this->input->post('doctor_name'),
            'attndedon' => $this->input->post('attened_date'),
        );

        $status = $this->treatment_model->store_treatment($treatpatientdata, $treat_id);

        if ($status) {
            $this->add_pharmcy($treat_id);
            if ($this->input->post('ecg_check') == 'on') {
                $ecgdata = array(
                    'OpdNo' => $this->input->post('opd_no'),
                    'refDocName' => $this->input->post('ecgdocname'),
                    'refDate' => $this->input->post('ecgdate'),
                    'treatId' => $treat_id,
                );
                $this->treatment_model->add_ecg_info($ecgdata);
            }

            if ($this->input->post('usg_check') == 'on') {
                $usgdata = array(
                    'OpdNo' => $this->input->post('opd_no'),
                    'refDocName' => $this->input->post('usgdocname'),
                    'refDate' => $this->input->post('usgdate'),
                    'treatId' => $treat_id
                );
                $this->treatment_model->add_usg_info($usgdata);
            }

            if ($this->input->post('xray_check') == 'on') {
                $xraydata = array(
                    'OpdNo' => $this->input->post('opd_no'),
                    'refDocName' => $this->input->post('xraydocname'),
                    'partOfXray' => $this->input->post('partxray'),
                    //'filmSize' => $this->input->post('filmsize'),
                    'refDate' => $this->input->post('xraydate'),
                    'treatID' => $treat_id
                );
                $this->treatment_model->add_xray_info($xraydata);
            }

            if ($this->input->post('kshara_check') == 'on') {
                $ksharadata = array(
                    'OpdNo' => $this->input->post('opd_no'),
                    'ksharsType' => $this->input->post('ksharatype'),
                    'ksharaname' => $this->input->post('ksharaname'),
                    'surgeon' => $this->input->post('ksharasurgeonname'),
                    'anaesthetic' => $this->input->post('ksharaanaesthetist'),
                    'asssurgeon' => $this->input->post('assksharasurgeonname'),
                    'ksharsDate' => $this->input->post('ksharadate'),
                    'treatId' => $treat_id
                );
                $this->treatment_model->add_kshara_info($ksharadata);
            }

            if ($this->input->post('surgery_check') == 'on') {
                $surgerydata = array(
                    'OpdNo' => $this->input->post('opd_no'),
                    'surgName' => $this->input->post('SurgeryDocname'),
                    'surgType' => $this->input->post('surgerytype'),
                    'surgDate' => $this->input->post('surgerydate'),
                    'treatId' => $treat_id,
                    'anaesthetic' => $this->input->post('surganaesthetist'),
                    'asssurgeon' => $this->input->post('AssSurgeryDocname'),
                    'surgeryname' => $this->input->post('surgeryname'),
                );
                $this->treatment_model->add_surgery_info($surgerydata);
            }

            if ($this->input->post('lab_check') == 'on') {
                $labdata = array(
                    'OpdNo' => $this->input->post('opd_no'),
                    'refDocName' => $this->input->post('labdocname'),
                    'testName' => $this->input->post('testname'),
                    'testrange' => $this->input->post('testrange'),
                    'testvalue' => $this->input->post('testvalue'),
                    'testDate' => $this->input->post('testdate'),
                    'treatID' => $treat_id
                );
                $this->treatment_model->add_lab_info($labdata);
            }

            if ($this->input->post('admit') == 'on') {
                $beddata = array(
                    'OpdNo' => $this->input->post('opd_no'),
                    'bedstatus' => 'Not Available',
                    'treatId' => $treat_id,
                );
                $this->treatment_model->update_bed_info($beddata, $this->input->post('bed_no'));

                $inpatientdata = array(
                    'OpdNo' => $this->input->post('opd_no'),
                    'deptOpdNo' => $this->input->post('dopd'),
                    'FName' => $this->input->post('fname'),
                    'Age' => $this->input->post('age'),
                    'Gender' => $this->input->post('gender'),
                    'diagnosis' => $this->input->post('diagnosis'),
                    'DoAdmission' => $this->input->post('admit_date'),
                    'Doctor' => $this->input->post('doctor_name'),
                    'department' => $this->input->post('department'),
                    'BedNo' => $this->input->post('bed_no'),
                    'WardNo' => '',
                    'treatId' => $treat_id
                );
                $last_ipd_number = $this->treatment_model->add_patient_to_ipd($inpatientdata);

                $ipd_treatment = array(
                    'ipdno' => $last_ipd_number,
                    'Trtment' => $this->input->post('treatment'),
                    'diagnosis' => $this->input->post('diagnosis'),
                    'complaints' => $this->input->post('complaints'),
                    'procedures' => $this->input->post('panch_procedures'),
                    'notes' => $this->input->post('notes'),
                    'AddedBy' => $this->input->post('doctor_name'),
                    'attndedon' => $this->input->post('admit_date'),
                );

                $this->treatment_model->add_ipd_treatment_data($ipd_treatment);
            }
        }
        redirect('patient/treatment/show_patients', 'refresh');
    }

    /*
     * Add IPD treatment
     */

    function add_ipd_treatment($ipd = NULL) {
        $this->layout->navIcon = "fa fa-user-md ";
        $this->layout->title = "IPD Treatment";
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "IPD Treatment";
        $this->layout->navDescr = "In Patient Department";

        $data = array();
        $data['ipd'] = $ipd;
        $opd_data = $this->treatment_model->get_opd_by_ipd($ipd);
        $opd = $opd_data['OpdNo'];
        $data['patient_details'] = $this->treatment_model->get_ipd_patient_basic_details($opd, $ipd);
        $data['treatment_details'] = $this->treatment_model->get_treatment_history($ipd);
        /*  $data['wards'] = $this->treatment_model->getBedno();
          $treatment_details = $this->treatment_model->get_current_treatment_info($opd, $treat_id);
          $data['current_treatment'] = $treatment_details['treatment_data'];
          $data['doctors'] = $treatment_details['doctors']; */
        $this->layout->data = $data;
        $this->layout->render();
    }

    function ipd_save() {
        $treat_id = NULL;
        if ($this->input->post('add_prescription') == 'on') {
            $treatment_data = array(
                'ipdno' => $this->input->post('ipd_no'),
                'Trtment' => $this->input->post('treatment'),
                'diagnosis' => $this->input->post('diagnosis'),
                'complaints' => $this->input->post('complaints'),
                'procedures' => $this->input->post('panch_procedures'),
                'department' => $this->input->post('department'),
                'notes' => $this->input->post('notes'),
                'AddedBy' => $this->input->post('doctor_name'),
                'attndedon' => $this->input->post('attened_date'),
            );
            $last_ipd_id = $this->treatment_model->store_ipd_treatment($treatment_data);
            $treat_id = $last_ipd_id;
        }
        $this->add_pharmcy($treat_id);
        if ($this->input->post('birth_check') == 'on') {
            $birth_data = array(
                'OpdNo' => $this->input->post('opd_no'),
                'deliveryDetail' => $this->input->post('delivery'),
                'babyBirthDate' => $this->input->post('birthdate') . ' ' . $this->input->post('birthtime'),
                'babyWeight' => $this->input->post('weight'),
                'treatby' => $this->input->post('surgeonname'),
                'babygender' => $this->input->post('babygender'),
                'babyblood' => $this->input->post('babyblood'),
                'fatherName' => $this->input->post('fathername'),
                'motherblood' => $this->input->post('motherblood'),
                'anaesthetic' => $this->input->post('anaesthetic'),
                'deliverytype' => $this->input->post('deliverytype'),
            );
            $this->treatment_model->store_birth_info($birth_data);
        }

        if ($this->input->post('ecg_check') == 'on') {
            $ecgdata = array(
                'OpdNo' => $this->input->post('opd_no'),
                'refDocName' => $this->input->post('ecgdocname'),
                'refDate' => $this->input->post('ecgdate'),
                'treatId' => $treat_id,
            );
            $this->treatment_model->add_ecg_info($ecgdata);
        }

        if ($this->input->post('usg_check') == 'on') {
            $usgdata = array(
                'OpdNo' => $this->input->post('opd_no'),
                'refDocName' => $this->input->post('usgdocname'),
                'refDate' => $this->input->post('usgdate'),
                'treatId' => $treat_id
            );
            $this->treatment_model->add_usg_info($usgdata);
        }

        if ($this->input->post('xray_check') == 'on') {
            $xraydata = array(
                'OpdNo' => $this->input->post('opd_no'),
                'refDocName' => $this->input->post('xraydocname'),
                'partOfXray' => $this->input->post('partxray'),
                'filmSize' => $this->input->post('filmsize'),
                'refDate' => $this->input->post('xraydate'),
                'treatID' => $treat_id
            );
            $this->treatment_model->add_xray_info($xraydata);
        }

        if ($this->input->post('kshara_check') == 'on') {
            $ksharadata = array(
                'OpdNo' => $this->input->post('opd_no'),
                'ksharsType' => $this->input->post('ksharatype'),
                'ksharaname' => $this->input->post('ksharaname'),
                'surgeon' => $this->input->post('ksharasurgeonname'),
                'anaesthetic' => $this->input->post('ksharaanaesthetist'),
                'asssurgeon' => $this->input->post('assksharasurgeonname'),
                'ksharsDate' => $this->input->post('ksharadate'),
                'treatId' => $treat_id
            );
            $this->treatment_model->add_kshara_info($ksharadata);
        }

        if ($this->input->post('surgery_check') == 'on') {
            $surgerydata = array(
                'OpdNo' => $this->input->post('opd_no'),
                'surgName' => $this->input->post('SurgeryDocname'),
                'surgType' => $this->input->post('surgerytype'),
                'surgDate' => $this->input->post('surgerydate'),
                'treatId' => $treat_id,
                'anaesthetic' => $this->input->post('surganaesthetist'),
                'asssurgeon' => $this->input->post('AssSurgeryDocname'),
                'surgeryname' => $this->input->post('surgeryname'),
            );
            $this->treatment_model->add_surgery_info($surgerydata);
        }

        if ($this->input->post('lab_check') == 'on') {
            $labdata = array(
                'OpdNo' => $this->input->post('opd_no'),
                'refDocName' => $this->input->post('labdocname'),
                'testName' => $this->input->post('testname'),
                'testrange' => $this->input->post('testrange'),
                'testvalue' => $this->input->post('testvalue'),
                'testDate' => $this->input->post('testdate'),
                'treatID' => $treat_id
            );
            $this->treatment_model->add_lab_info($labdata);
        }

        $this->session->set_flashdata('noty_msg', 'Successfully saved');
        redirect('patient/patient/ipd_list');
    }

    function display_all_patients() {
        $this->scripts_include->includePlugins(array('datatables', 'js'));
        $this->layout->navIcon = "fa fa-users ";
        $this->layout->title = "OPD list";
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "OPD patients";
        $this->layout->navDescr = "Print OPD bills";
        $data = array();
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_all_patients() {
        $input_array = array();
        foreach ($this->input->post('search_form') as $search_data) {
            $input_array[$search_data['name']] = $search_data['value'];
        }
        $input_array['start'] = $this->input->post('start');
        $input_array['length'] = $this->input->post('length');
        $input_array['order'] = $this->input->post('order');
        $data = $this->treatment_model->get_all_opd_patients($input_array);
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

    function print_bill() {
        $result = $this->treatment_model->get_all_opd_patients($this->input->post(), true);
        $patients = $result['data'];
        if (!empty($patients)) {
            $html = "";
            foreach ($patients as $patient) {
                $opd = $patient['OpdNo'];
                $treat_id = $patient['ID'];
                $opd_charges = $this->get_configuration_variable('OPD_CHARGES');
                $opd_charges_in_words = $this->get_configuration_variable('OPD_CHARGES_IN_WORDS');
                $patient_info = $this->treatment_model->get_patient_basic_details($opd);
                $where = array(
                    't.OpdNo' => $opd,
                    'ID' => $treat_id
                );
                $treatment_info = $this->treatment_model->get_treatment_information($where);
                $config = $this->db->get('config');
                $config = $config->row_array();
                $header = '<div style="width:100%">
                    <div style="width:20%;float:left;padding-left:1%;padding-top:1%;">'
                        . '<img src="' . base_url('assets/your_logo.png') . '" width="60" height="60" alt="logo">
                    </div>
                    <div style="width:70%;float:left;padding-left:1%;padding-top:1%;">
                    <h3 align="left">' . $config["college_name"] . '</h3>
                    </div></div><hr>';
                $content = "<table width='100%' class='table table-bordered'>
                    <thead><tr style='text-align: center;'><th>Sl.No</th><th>Perticulars</th><th>Amount</th></tr></thead>
                    <tbody><tr><td style='text-align: center;' height='60' width='10%'>1</td><td  width='70%'>OPD Consulatation Fees</td><td style='text-align: right;padding-right:2%;'>" . $opd_charges . "</td></tr>
                    <tr><td colspan=2 style='text-align: right;' width='66.66%'>Total <b></b></td><td style='text-align: right;padding-right:2%;' width='33.33%'>" . $opd_charges . "</td></tr>
                    <tr><td colspan=3 width='100%' height='35'>Rupees in words: <b>" . $opd_charges_in_words . "</b></td></tr>
                    <tr><td colspan=3 width='100%' height='35' style='text-align: right;padding-top:3%;padding-right:2%;'>Receivers sign</td></tr>    
                    </tbody>
                    </table>";
                $html .= "<div style='margin-bottom:2% !important;'>";
                $html .= "<div style='width:100% !important;border: 1px solid black;'>
            " . $header .
                        "<div style='width:100%'><h3 align='center'>OPD BILL</h3></div><hr><table width='100%'><tr><td width='33.33%'>No: <b>" . $treat_id . "</b></td>
                    <td width='33.33%' style='text-align: center;'>OPD: <b>" . $opd . "</b></td>
                    <td width='33.33%' style='text-align: right;'>Date: <b>" . format_date($patient['CameOn']) . "</b></td></tr>
                <tr><td colspan=3 width='100%'>Patient Name: <b>" . $patient['FirstName'] . ' ' . $patient['LastName'] . "</b></td></tr>    
                </table><br/>" . $content .
                        "</div>";
                $html .= "</div>";
            }//foreach
        }//if
        $filename = 'opd_bills_' . $c_date;
        pdf_create('', $html, $filename, 'P', 'I', FALSE, TRUE);
        exit;
    }

    public function print_prescription_bill() {
        $c_date = '2019-03-08';
        $c_date = '2019-02-16';
        $query = "SELECT * FROM treatmentdata t JOIN patientdata p ON t.OpdNo=p.OpdNo WHERE CameOn >='$c_date' AND CameOn <='$c_date' AND t.department !='Swasthavritta' LIMIT 10";
        $patients = $this->db->query($query)->result_array();
        if (!empty($patients)) {
            $html = "";
            foreach ($patients as $patient) {
                $opd = $patient['OpdNo'];
                $treat_id = $patient['ID'];
                $config = $this->db->get('config');
                $config = $config->row_array();
                $patient_info = $this->treatment_model->get_patient_basic_details($opd);
                $where = array(
                    't.OpdNo' => $opd,
                    't.ID' => $treat_id
                );
                $treatment_info = $this->treatment_model->get_treatment_information($where);
                $medicines = explode(',', rtrim(trim($treatment_info['Trtment']), ','));
                $med_table = '<table width="100%" class="table table-bordered">';
                $med_table .= "<thead><tr style='text-align: center;'><th>Sl.No</th><th>Perticulars</th><th>QTY</th><th>Amount</th></tr></thead>";
                $i = 0;
                $find = array('BD', 'TID', 'TDS', 'ML', 'MG', 'GM', '.', '10ML');
                $total_amount = 0;

                foreach ($medicines as $med) {
                    $str = str_replace($find, '', trim($med));
                    $clean_str = trim(preg_replace('/[0-9]+/', '', $str));
                    $query = "SELECT * FROM product_list WHERE product LIKE '%" . $clean_str . "%' LIMIT 1";
                    $data = $this->db->query($query)->row_array();
                    $med_table .= '<tr>';
                    $med_table .= '<td style="text-align:center; ">' . ++$i . '</td>';
                    $med_table .= '<td>' . $this->display_medicine($med) . '</td>';
                    $med_table .= '<td style="text-align:center; ">1</td>';
                    $med_table .= '<td style="text-align:right;">' . number_format($data['price'], 2) . '</td>';
                    $med_table .= '</tr>';
                    $total_amount = $total_amount + $data['price'];
                }

                $med_table .= '<tr><td style="text-align:right;" colspan=3>Total:</td><td style="text-align:right;">' . number_format($total_amount, 2) . '</td></tr>';
                $med_table .= '</table>';
                //PDF content
                $header = '<div style="width:100%;">
                    <div style="width:20%;float:left;padding-left:1%;padding-top:1%;">'
                        . '<img src="' . base_url('assets/your_logo.png') . '" width="60" height="60" alt="logo">
                    </div>
                    <div style="width:70%;float:left;padding-left:1%;padding-top:1%;">
                    <h3 align="left">' . $config["college_name"] . '</h3>
                    </div></div><hr>';

                $patient_details = "<div style='width:100%'><h3 align='center'>Pharmacy BILL</h3></div><hr><table width='100%'><tr><td width='33.33%'>No: <b>" . $treat_id . "</b></td>
                    <td width='33.33%' style='text-align: center;'>OPD: <b>" . $opd . "</b></td>
                    <td width='33.33%' style='text-align: right;'>Date: <b>" . format_date($treatment_info['CameOn']) . "</b></td></tr>
                    <tr><td width='33.33%'>Patient Name: <b>" . $patient['FirstName'] . ' ' . $patient['LastName'] . "</b></td>
                    <td width='33.33%' style='text-align: center;'>Age: <b>" . $patient['Age'] . "</b></td><td width='33.33%'>Ref.Doctor: <b>" . $treatment_info['attndedby'] . "</b></td></tr>    
                </table><br/>";
                $html .= '<div style="height:50%;border-style: dashed; border-bottom: 1px solid black;">' . $header . $patient_details . $med_table . '</div>';
                //'<barcode code="' . $treat_id . '" type="C128B" size="1.6" height="0.5" /><br/>
            }
        }
        $filename = 'pharmacy_bills_' . $c_date;
        pdf_create($title, $html, $filename, 'P', 'I', FALSE, TRUE);
        exit;
    }

    public function print_prescription() {
        $result = $this->treatment_model->get_all_opd_patients($this->input->post(), true);
        $patients = $result['data'];
        //pma($result,1);
        //$c_date = '2019-03-08';
        //$c_date = '2019-02-16';
        //$query = "SELECT * FROM treatmentdata t JOIN patientdata p ON t.OpdNo=p.OpdNo WHERE CameOn >='$c_date' AND CameOn <='$c_date' AND t.department !='Swasthavritta' LIMIT 10";
        //$patients = $this->db->query($query)->result_array();
        if (!empty($patients)) {
            $html = "";
            foreach ($patients as $patient) {
                $opd = $patient['OpdNo'];
                $treat_id = $patient['ID'];
                $config = $this->db->get('config');
                $config = $config->row_array();
                $patient_info = $this->treatment_model->get_patient_basic_details($opd);
                $where = array(
                    't.OpdNo' => $opd,
                    't.ID' => $treat_id
                );
                $treatment_info = $this->treatment_model->get_treatment_information($where);
                $medicines = explode(',', rtrim(trim($treatment_info['Trtment']), ','));
                $med_table = '<table width="100%" class="table table-bordered">';
                $med_table .= "<thead><tr style='text-align: center;'><th>Sl.No</th><th>Perticulars</th><th>QTY</th></tr></thead>";
                $i = 0;
                $find = array('BD', 'TID', 'TDS', 'ML', 'MG', 'GM', '.', '10ML');
                $total_amount = 0;

                foreach ($medicines as $med) {
                    $str = str_replace($find, '', trim($med));
                    $clean_str = trim(preg_replace('/[0-9]+/', '', $str));
                    $query = "SELECT * FROM product_list WHERE product LIKE '%" . $clean_str . "%' LIMIT 1";
                    //$data = $this->db->query($query)->row_array();
                    $med_table .= '<tr>';
                    $med_table .= '<td style="text-align:center; ">' . ++$i . '</td>';
                    $med_table .= '<td>' . $this->display_medicine($med) . '</td>';
                    $med_table .= '<td style="text-align:center; ">1</td>';
                    //$med_table .= '<td style="text-align:right;">' . number_format($data['price'], 2) . '</td>';
                    $med_table .= '</tr>';
                    //$total_amount = $total_amount + $data['price'];
                }

                //$med_table .= '<tr><td style="text-align:right;" colspan=3>Total:</td><td style="text-align:right;">' . number_format($total_amount, 2) . '</td></tr>';
                $med_table .= '</table>';
                //PDF content
                $header = '<div style="width:100%;">
                    <div style="width:20%;float:left;padding-left:1%;padding-top:1%;">'
                        . '<img src="' . base_url('assets/your_logo.png') . '" width="60" height="60" alt="logo">
                    </div>
                    <div style="width:70%;float:left;padding-left:1%;padding-top:1%;">
                    <h3 align="left">' . $config["college_name"] . '</h3>
                    </div></div><hr>';

                $patient_details = "<div style='width:100%'><h3 align='center'>Prescription</h3></div><hr><table width='100%'><tr><td width='33.33%'>No: <b>" . $treat_id . "</b></td>
                    <td width='33.33%' style='text-align: center;'>OPD: <b>" . $opd . "</b></td>
                    <td width='33.33%' style='text-align: right;'>Date: <b>" . format_date($treatment_info['CameOn']) . "</b></td></tr>
                    <tr><td width='33.33%'>Patient Name: <b>" . $patient['FirstName'] . ' ' . $patient['LastName'] . "</b></td>
                    <td width='33.33%' style='text-align: center;'>Age: <b>" . $patient['Age'] . "</b></td><td width='33.33%'>Ref.Doctor: <b>" . $treatment_info['attndedby'] . "</b></td></tr>
                    <tr><td width='50%'>Diagosis: <b>" . $treatment_info['diagnosis'] . "</b></td></tr>
                </table><br/>";
                $footer = "<div style='width:100%;margin-top:2%;'><table width='100%' style='border: none'><tr><td width='50%'>Please visit after 15 days.</td>"
                        . "<td width='50%' style='text-align:right;font-size:12px;'><b>Doctor signature</b></td></table></div>";
                $html .= '<div style="height:50%;border-style: dashed; border-bottom: 1px solid black;">' . $header . $patient_details . $med_table . $footer . '</div>';
                //'<barcode code="' . $treat_id . '" type="C128B" size="1.6" height="0.5" /><br/>
            }
        }
        $filename = 'pharmacy_bills_' . date('Y_m_d');
        pdf_create('', $html, $filename, 'P', 'I', FALSE, TRUE);
        exit;
    }

    function display_medicine($med) {
        if (strpos($med, 'BD') == true) {
            return str_replace('BD', '', $med);
        } else if (strpos($med, 'TDS') == true) {
            return str_replace('TDS', '', $med);
        } else if (strpos($med, 'TID') == true) {
            return str_replace('TID', '', $med);
        } else {
            return $med;
        }
    }

    function get_frequency($med) {
        if (strpos($med, 'BD') == true) {
            return '1-0-1';
        } else if (strpos($med, 'TDS') == true) {
            return '1-1-1';
        } else if (strpos($med, 'TID') == true) {
            return '1-1-1';
        } else {
            return '0-0-0';
        }
    }

    function generate_barcode() {
        $temp = rand(10000, 99999);
        $this->set_barcode($temp);
    }

    private function set_barcode($code) {
        //load library
        $this->load->library('zend');
        //load in folder Zend
        $this->zend->load('Zend/Barcode');
        //generate barcode
        Zend_Barcode::render('code128', 'image', array('text' => $code), array());
    }

    function update_medicines() {
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        $this->db->trans_start();
        $this->db->where('Trtment <>', NULL);
        $treat_data = $this->db->get('treatmentdata')->result_array();
        foreach ($treat_data as $row) {
            if ($row['department'] != 'Swasthavritta') {
                echo $row['Trtment'] . '<br/>';
                $medicines = explode(',', rtrim($row['Trtment'], ','));
                foreach ($medicines as $med) {
                    $med = trim($med);
                    if ($med != '' && strlen($med) != 0) {
                        $medicine_arr = array(
                            'OpdNo' => $row['OpdNo'],
                            'treat_id' => $row['ID'],
                            'medicine_name' => $this->display_medicine($med),
                            'frequency' => $this->get_frequency($med),
                            'no_of_days' => 3
                        );
                        $this->db->insert('prescription_details', $medicine_arr);
                    }
                }
            }
        }
        $this->db->query('UPDATE prescription_details SET no_of_days=0 WHERE frequency="0-0-0"');
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            $this->db->trans_rollback();
            echo 'failed';
        } else {
            # Everything is Perfect. 
            # Committing data to the database.
            $this->db->trans_commit();
            echo 'success';
        }
    }

}
