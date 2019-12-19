<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Panchakarma
 *
 * @author Shivaraj
 */
class Panchakarma extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('master/panchakarma_model');
    }

    public function index() {
        show_error('<h2 style="color:red;padding-left:5%;">Unathorizes access</h2>', 401);
    }

    public function fetch_procedures() {
        $data = $this->panchakarma_model->get_procedures();
        if ($this->input->is_ajax_request()) {
            echo json_encode(array('data' => $data, 'status_code' => 200, 'status' => 'true'));
        } else {
            return $data;
        }
    }
    
    public function fetch_sub_procedures(){
        $proc_name = $this->input->post('proc_name');
        $data = $this->panchakarma_model->get_sub_procedures($proc_name);
        if ($this->input->is_ajax_request()) {
            echo json_encode(array('data' => $data, 'status_code' => 200, 'status' => 'true'));
        } else {
            return $data;
        }
    }

}
