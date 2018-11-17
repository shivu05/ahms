<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Users extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('master/users_model');
        $this->layout->title = "Users";
    }

    public function index() {
        $this->layout->navTitleFlag = true;
        $this->layout->navIcon = "fa fa-users";
        $this->layout->navTitle = "Users";
        $this->scripts_include->includePlugins(array('datatables', 'js'));
        $this->scripts_include->includePlugins(array('datatables', 'css'));
        $data = array();
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_users_list() {
        $input_array = array();
        foreach ($this->input->post('search_form') as $search_data) {
            $input_array[$search_data['name']] = $search_data['value'];
        }
        $input_array['start'] = $this->input->post('start');
        $input_array['length'] = $this->input->post('length');
        $input_array['order'] = $this->input->post('order');
        $data = $this->users_model->get_users_data($input_array);

        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

    function add() {
        $this->layout->navTitleFlag = true;
        $this->layout->navIcon = "fa fa-users";
        $this->layout->navTitle = "Users";
        $data = array();
        $this->layout->data = $data;
        $this->layout->render();
    }

}
