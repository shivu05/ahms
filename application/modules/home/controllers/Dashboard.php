<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Dashboard
 *
 * @author Shivaraj
 */
class Dashboard extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->rbac->is_login()) {
            redirect('Login');
        }
        $this->layout->title = 'Dashboard';
        $this->load->model('home/dashboard_model');
    }

    public function index() {
        if ($this->rbac->is_admin()) {
            redirect('admin-dashboard');
        } else if ($this->rbac->is_doctor()) {
            redirect('home/doctor');
        } else if ($this->rbac->has_role('XRAY')) {
            redirect('home/xray');
        } else if ($this->rbac->has_role('ECG')) {
            redirect('home/ecg');
        } else if ($this->rbac->has_role('USG')) {
            redirect('home/usg');
        } else {
            redirect('home/user');
        }
    }

    public function admin() {
        if ($this->rbac->is_admin()) {
            $this->scripts_include->includePlugins(array('charts'), 'js');
            $this->layout->navTitleFlag = true;
            $this->layout->navTitle = "Dashboard";

            $data = array();
            $data['gender_count'] = $this->dashboard_model->get_gender_wise_patients();
            $data['dept_wise_data'] = $this->dashboard_model->get_departmentwise_patient_count();

            $this->layout->data = $data;
            $this->layout->render();
        } else {
            $this->layout->render(array('error' => '401'));
        }
    }

    public function doctors_dashboard() {
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "Dashboard";
        $data = array();
        $data['gender_count'] = $this->dashboard_model->get_gender_wise_patients();
        $this->layout->data = $data;
        $this->layout->render();
    }

    public function blank_dashboard() {
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "Dashboard";
        $data = array();
        $this->layout->data = $data;
        $this->layout->render();
    }

    public function xray_dashabord() {
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "Dashboard";
        $this->load->model('patient/xray_model');
        $data = array();
        $data['stats'] = $this->xray_model->dashboard_stats();

        $this->layout->data = $data;
        $this->layout->render();
    }

    public function ecg_dashboard() {
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "Dashboard";
        $this->load->model('patient/ecg_model');
        $data = array();
        $data['stats'] = $this->ecg_model->dashboard_stats();

        $this->layout->data = $data;
        $this->layout->render();
    }

    public function usg_dashboard() {
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "Dashboard";
        $this->load->model('patient/usg_model');
        $data = array();
        $data['stats'] = $this->usg_model->dashboard_stats();

        $this->layout->data = $data;
        $this->layout->render();
    }

}
