<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Jaloukavacharana extends SHV_Controller
{
    private $_is_admin = false;

    public function __construct()
    {
        parent::__construct();
        $this->layout->title = "Autoclave";
        $this->_is_admin = $this->rbac->is_admin();
        $this->load->model('register/Jaloukavacharana_model');
    }

    // CREATE: Add new record
    public function create()
    {
        $data = [
            'opd_no'            => $this->input->post('opd'),
            'ipd_no'            => $this->input->post('ipd'),
            'ref_date'          => $this->input->post('ref_date'),
            'treat_id'          => $this->input->post('tid'),
            'doctor_name'       => $this->input->post('doctor_name'),
            'procedure_details' => $this->input->post('procedure_details'),
            'doctor_remarks'    => $this->input->post('doctor_remarks')
        ];

        if ($this->Jaloukavacharana_model->insert($data)) {
            echo json_encode(["status" => "success", "message" => "Record added successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to add record"]);
        }
    }

    // READ: Get all records or a specific one
    public function get($id = null)
    {
        $data = $this->Jaloukavacharana_model->get($id);
        echo json_encode($data);
    }

    // UPDATE: Edit record by ID
    public function update($id)
    {
        $data = [
            'opd_no'            => $this->input->post('opd_no'),
            'ipd_no'            => $this->input->post('ipd_no'),
            'ref_date'          => $this->input->post('ref_date'),
            'doctor_name'       => $this->input->post('doctor_name'),
            'procedure_details' => $this->input->post('procedure_details'),
            'doctor_remarks'    => $this->input->post('doctor_remarks')
        ];

        if ($this->Jaloukavacharana_model->update($id, $data)) {
            echo json_encode(["status" => "success", "message" => "Record updated successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update record"]);
        }
    }

    // DELETE: Remove record by ID
    public function delete($id)
    {
        if ($this->Jaloukavacharana_model->delete($id)) {
            echo json_encode(["status" => "success", "message" => "Record deleted successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to delete record"]);
        }
    }
}
