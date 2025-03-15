<?php

require APPPATH . 'modules/api/libraries/REST_Server.php';

class Patients extends REST_Server
{

    public function __construct()
    {
        parent::__construct();
        $this->request->format = 'json';
        $this->load->database();
    }

    // Sample GET API to fetch patient details
    public function info_get()
    {
        $id = $this->get('opd');
        if (empty($id) || $id == '') {
            $this->response([
                'status' => FALSE,
                'message' => 'No patient ID provided'
            ], 400);
        } else {
            $query = $this->db->get_where('patientdata', ['OpdNo' => $id]);
            if ($query->num_rows() > 0) {
                $this->response($query->row_array(), 200);
            } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'Patient not found'
                ], 404);
            }
        }
    }
}
