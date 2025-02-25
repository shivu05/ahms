<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Siravyadana extends SHV_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('register/siravyadana_model');
    }

    public function index()
    {
        $data['records'] = $this->siravyadana_model->get_all_records();
        $this->load->view('siravyadana/index', $data);
    }

    public function create()
    {
        $this->load->view('siravyadana/create');
    }

    public function store()
    {
        $data = array(
            'opd_no' => $this->input->post('opd'),
            'ipd_no' => $this->input->post('ipd'),
            'treat_id' => $this->input->post('tid'),
            'ref_date' => $this->input->post('ref_date'),
            'doctor_name' => $this->input->post('doctor_name'),
            'procedure_details' => $this->input->post('procedure_details'),
            'doctor_remarks' => $this->input->post('doctor_remarks'),
        );
        $is_inserted = $this->siravyadana_model->create($data);
        if ($is_inserted) {
            echo json_encode(array('status' => 'OK', 'icon' => 'fa-check', 'message' => 'Inserted successfully', 'type' => 'success'));
        } else {
            echo json_encode(array('status' => 'NOK', 'icon' => 'fa-cross', 'message' => 'Failed to insert data', 'type' => 'danger'));
        }
    }

    public function edit($id)
    {
        $data['record'] = $this->siravyadana_model->get_record_by_id($id);
        $this->load->view('siravyadana/edit', $data);
    }

    public function update($id)
    {
        $data = array(
            'opd_no' => $this->input->post('opd_no'),
            'ipd_no' => $this->input->post('ipd_no'),
            'ref_date' => $this->input->post('ref_date'),
            'doctor_name' => $this->input->post('doctor_name'),
            'procedure_details' => $this->input->post('procedure_details'),
            'doctor_remarks' => $this->input->post('doctor_remarks'),
            'last_updated' => date('Y-m-d H:i:s')
        );
        $this->siravyadana_model->update_record($id, $data);
        redirect('siravyadana');
    }

    public function delete($id)
    {
        $this->siravyadana_model->delete_record($id);
        redirect('siravyadana');
    }
}