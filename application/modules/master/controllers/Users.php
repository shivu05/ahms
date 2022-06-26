<?php

class Users extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('master/users_model');
        $this->layout->title = "Users";
    }

    public function index() {
        $this->scripts_include->includePlugins(array('datatables', 'js'));
        $this->scripts_include->includePlugins(array('datatables', 'css'));
        $data = array();
        $data['roles'] = $this->users_model->get_roles();
        $data['department_list'] = $this->get_department_list('array');
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_users_list() {
        $input_array = array();
        foreach ($this->input->post('search_form') as $search_data) {
            $input_array[$search_data['name']] = $search_data['value'];
        }
        $search_key = $this->input->post('search');
        $input_array['keyword'] = $search_key['value'];
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

    function check_for_duplicate_email_update() {
        $email = $this->input->get('email');
        $id = $this->input->get('id');
        $count = $this->users_model->check_for_dup_email($email, $id);
        if ($count > 0) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

    function update() {
        if ($this->input->is_ajax_request()) {
            $user_name = $this->input->post('user_name');
            $user_mail = $this->input->post('email');
            $user_mobile = $this->input->post('user_mobile');
            $user_department = $this->input->post('user_department');
            $id = $this->input->post('id');
            $update = array(
                'user_name' => $user_name,
                'user_email' => $user_mail,
                'user_mobile' => $user_mobile,
                'user_department' => $user_department
            );
            $where = array(
                'ID' => $id
            );

            $is_updated = $this->users_model->update($update, $where);
            if ($is_updated) {
                echo json_encode(array('status' => 'Success', 'msg' => 'Updated successfully', 'p_class' => 'success'));
            } else {
                echo json_encode(array('status' => 'Failed', 'msg' => 'Failed to Update', 'p_class' => 'danger'));
            }
        }
    }

}
