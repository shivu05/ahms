<?php

/**
 * Description of Login
 *
 * @author Shivaraj
 */
class Login extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        $this->layout->layout = 'login_layout';
        $this->layout->title = 'Login';
    }

    function index() {
        $data = array();
        $data['app_settings'] = $this->application_settings(array('college_name'));
        $this->layout->data = $data;
        $this->layout->render();
    }

    function validate() {
        $data["title"] = "Login Page";
        $this->form_validation->set_rules('loginname', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $data['app_settings'] = $this->application_settings(array('college_name'));
            $this->layout->data = $data;
            $this->layout->render(array('view' => 'login/index'));
        } else {
            //if ($this->simpleloginsecure->check_license() == 1) {
            if ($this->simpleloginsecure->login($this->input->post('loginname'), $this->input->post('password'))) {
                redirect('dashboard');
            } else {
                $this->session->set_flashdata('noty_msg', 'Wrong username / password ');
                redirect('login');
            }
            /* } else {
              $data["invalid"] = "License Expired please contact admin";
              $this->load->view('login/login_vw', $data);
              } */
        }
    }

    function logout() {
        $this->simpleloginsecure->logout();
        //remove all session data
        $this->output->set_header('refresh:3; url=' . base_url());
        $this->session->unset_userdata('logged_in');
        $this->session->unset_userdata('user');
        //$this->session->sess_destroy();
        $this->db->cache_delete_all();
        redirect('Login', true);
    }

}
