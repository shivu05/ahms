<?php

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
        $this->layout->navDescr = "Register new user";
        $this->scripts_include->includePlugins(array('jq_validation', 'js'));
        $data = array();
        $data['department_list'] = $this->get_department_list('array');
        $data['roles'] = $this->get_roles();
        $this->layout->data = $data;
        $this->layout->render();
    }

    function save() {
        $is_inserted = $this->users_model->save_user_details($this->input->post());
        if ($is_inserted) {
            $this->session->set_flashdata('noty_msg', '<p class="text-success">User added successfully</p>');
        } else {
            $this->session->set_flashdata('noty_msg', '<p class="text-danger">Failed to add user</p>');
        }
        redirect('add-user');
    }

    function check_for_duplicate_email() {
        $email = $this->input->get('email');
        $count = $this->users_model->check_for_dup_email($email);
        if ($count > 0) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

}
