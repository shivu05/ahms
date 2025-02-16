<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Agnikarma extends SHV_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('register/agnikarma_model');
    }

    public function index()
    {
        $data['records'] = $this->Agnikarma_model->get_all_records();
        $this->load->view('agnikarma/index', $data);
    }

    public function create()
    {
        $this->load->view('agnikarma/create');
    }

    public function store()
    {
        $data = array(
            'opd_no' => $this->input->post('opd'),
            'ipd_no' => $this->input->post('ipd'),
            'treat_id' => $this->input->post('tid'),
            'ref_date' => $this->input->post('ref_date'),
            'doctor_name' => $this->input->post('doctor_name'),
            'treatment_notes' => $this->input->post('treatment_notes'),

        );
        $is_inserted = $this->agnikarma_model->create($data);
        if ($is_inserted) {
            echo json_encode(array('status' => 'OK', 'icon' => 'fa-check', 'message' => 'Inserted successfully', 'type' => 'success'));
        } else {
            echo json_encode(array('status' => 'NOK', 'icon' => 'fa-cross', 'message' => 'Failed to insert data', 'type' => 'danger'));
        }
    }

    public function edit($id)
    {
        $data['record'] = $this->Agnikarma_model->get_record_by_id($id);
        $this->load->view('agnikarma/edit', $data);
    }

    public function update($id)
    {
        $data = array(
            'opd_no' => $this->input->post('opd_no'),
            'ipd_no' => $this->input->post('ipd_no'),
            'ref_date' => $this->input->post('ref_date'),
            'doctor_name' => $this->input->post('doctor_name')
        );
        $this->Agnikarma_model->update_record($id, $data);
        redirect('agnikarma');
    }

    public function delete($id)
    {
        $this->Agnikarma_model->delete_record($id);
        redirect('agnikarma');
    }
}
