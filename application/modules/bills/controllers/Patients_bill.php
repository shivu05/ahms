<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Patients_bill extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('BillingModel');
        $this->layout->title = "Billing";
        $this->load->model('bills/bill_services_model', 'ServiceModel');
    }

    public function add_opd_bill() {
        $data['patients'] = array(); //$this->PatientModel->get_all_patients();
        $data['service_groups'] = $this->ServiceModel->get_service_groups();

        if ($this->input->post()) {
            $billing_data = array(
                'opd_no' => $this->input->post('patient_id'),
                'service_id' => $this->input->post('service_id'),
                'billing_date' => $this->input->post('billing_date'),
                'amount' => $this->input->post('amount')
            );
            $this->BillingModel->add_opd_billing($billing_data);
            $this->session->set_flashdata('success', 'OPD bill added successfully!');
            redirect('add-opd-bill');
        }

        $this->layout->data = $data;
        $this->layout->render();
    }

    public function add_ipd_bill() {
        $data['admissions'] = array(); // $this->PatientModel->get_all_admissions();
        $data['service_groups'] = $this->ServiceModel->get_service_groups();

        if ($this->input->post()) {
            $billing_data = array(
                'ipd_no' => $this->input->post('patient_id'),
                'service_id' => $this->input->post('service_id'),
                'billing_date' => $this->input->post('billing_date'),
                'amount' => $this->input->post('amount')
            );
            $this->BillingModel->add_ipd_billing($billing_data);
            $this->session->set_flashdata('success', 'IPD bill added successfully!');
            redirect('add-ipd-bill');
        }

        $this->layout->data = $data;
        $this->layout->render();
    }

    public function get_services_by_group() {
        $group_id = $this->input->get('group_id');
        $this->load->model('ServiceModel');
        $services = $this->ServiceModel->get_services_by_group($group_id);
        echo json_encode($services);
    }
}
