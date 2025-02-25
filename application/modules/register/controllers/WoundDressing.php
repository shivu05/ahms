<?php
defined('BASEPATH') or exit('No direct script access allowed');

class WoundDressing extends SHV_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('register/wound_dressing_model');
    }

    public function index()
    {
        $data['records'] = $this->wound_dressing_model->get_all_records();
        $this->load->view('wound_dressing/index', $data);
    }

    public function create()
    {
        $this->load->view('wound_dressing/create');
    }

    public function store()
    {
        $data = array(
            'opd_no' => $this->input->post('opd'),
            'ipd_no' => $this->input->post('ipd'),
            'treat_id' => $this->input->post('tid'),
            'ref_date' => $this->input->post('ref_date'),
            'wound_location' => $this->input->post('wound_location'),
            'wound_type' => $this->input->post('wound_type'),
            'dressing_material' => $this->input->post('dressing_material'),
            'doctor_name' => $this->input->post('doctor_name'),
            'next_dressing_date' => $this->input->post('next_dressing_date'),
            'doctor_remarks' => $this->input->post('doctor_remarks'),
        );
        $is_inserted = $this->wound_dressing_model->create($data);
        if ($is_inserted) {
            echo json_encode(array('status' => 'OK', 'icon' => 'fa-check', 'message' => 'Inserted successfully', 'type' => 'success'));
        } else {
            echo json_encode(array('status' => 'NOK', 'icon' => 'fa-cross', 'message' => 'Failed to insert data', 'type' => 'danger'));
        }
    }

    public function edit($id)
    {
        $data['record'] = $this->wound_dressing_model->get_record_by_id($id);
        $this->load->view('wound_dressing/edit', $data);
    }

    public function update($id)
    {
        $data = array(
            'opd_no' => $this->input->post('opd_no'),
            'ipd_no' => $this->input->post('ipd_no'),
            'ref_date' => $this->input->post('ref_date'),
            'wound_location' => $this->input->post('wound_location'),
            'wound_type' => $this->input->post('wound_type'),
            'dressing_material' => $this->input->post('dressing_material'),
            'doctor_name' => $this->input->post('doctor_name'),
            'next_dressing_date' => $this->input->post('next_dressing_date'),
            'doctor_remarks' => $this->input->post('doctor_remarks'),
            'last_updated' => date('Y-m-d H:i:s')
        );
        $this->wound_dressing_model->update_record($id, $data);
        redirect('wound_dressing');
    }

    public function delete($id)
    {
        $this->wound_dressing_model->delete_record($id);
        redirect('wound_dressing');
    }
}