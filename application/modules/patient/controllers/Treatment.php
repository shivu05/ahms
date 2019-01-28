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
        $data = array();
        $data['opd'] = $opd;
        $data['treat_id'] = $treat_id;
        $data['patient_details'] = $this->treatment_model->get_patient_basic_details($opd);
        $data['treatment_details'] = $this->treatment_model->get_treatment_history($opd);
        $data['wards'] = $this->treatment_model->getBedno();
        $treatment_details = $this->treatment_model->get_current_treatment_info($opd, $treat_id);
        $data['med_freqs'] = $this->get_medicine_frequency();
        $data['medicines'] = $this->get_product_list();
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

}
