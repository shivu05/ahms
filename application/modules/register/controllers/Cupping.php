<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cupping extends SHV_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('register/cupping_model');
    }

    public function index()
    {
        $data['records'] = $this->Cupping_model->get_all_records();
        $this->load->view('cupping/index', $data);
    }

    public function create()
    {
        $this->load->view('cupping/create');
    }

    public function store()
    {
        $data = array(
            'opd_no' => $this->input->post('opd'),
            'ipd_no' => $this->input->post('ipd'),
            'treat_id' => $this->input->post('tid'),
            'ref_date' => $this->input->post('ref_date'),
            'doctor_name' => $this->input->post('doctor_name'),
            'type_of_cupping' => $this->input->post('type_of_cupping'),
            'site_of_application' => $this->input->post('site_of_application'),
            'no_of_cups_used' => $this->input->post('no_of_cups_used'),
            'treatment_notes' => $this->input->post('treatment_notes'),
        );
        $is_inserted = $this->cupping_model->create($data);
        if ($is_inserted) {
            echo json_encode(array('status' => 'OK', 'icon' => 'fa-check', 'message' => 'Inserted successfully', 'type' => 'success'));
        } else {
            echo json_encode(array('status' => 'NOK', 'icon' => 'fa-cross', 'message' => 'Failed to insert data', 'type' => 'danger'));
        }
    }

    public function edit($id)
    {
        $data['record'] = $this->Cupping_model->get_record_by_id($id);
        $this->load->view('cupping/edit', $data);
    }

    public function update($id)
    {
        $data = array(
            'opd_no' => $this->input->post('opd_no'),
            'ipd_no' => $this->input->post('ipd_no'),
            'ref_date' => $this->input->post('ref_date'),
            'doctor_name' => $this->input->post('doctor_name'),
            'type_of_cupping' => $this->input->post('type_of_cupping'),
            'site_of_application' => $this->input->post('site_of_application'),
            'no_of_cups_used' => $this->input->post('no_of_cups_used'),
            'treatment_notes' => $this->input->post('treatment_notes'),
            'last_updates' => date('Y-m-d H:i:s')
        );
        $this->Cupping_model->update_record($id, $data);
        redirect('cupping');
    }

    public function delete($id)
    {
        $this->Cupping_model->delete_record($id);
        redirect('cupping');
    }
}