<?php

/**
 * Description of Dashboard
 *
 * @author Shivaraj
 */
class Dashboard extends SHV_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->rbac->is_login()) {
            redirect('Login');
        }
        $this->layout->title = 'Dashboard';
        $this->load->model('home/dashboard_model');
        $this->layout->breadcrumbsFlag = TRUE;
    }

    public function index()
    {
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
        } else if ($this->rbac->has_role('LAB')) {
            redirect('home/lab');
        } else if ($this->rbac->has_role('OPDSCR')) {
            redirect('home/opdscr_dashboard');
        } else {
            redirect('home/user');
        }
    }

    public function admin()
    {
        if ($this->rbac->is_admin()) {
            $this->scripts_include->includePlugins(array('chartjs'), 'js');
            $this->layout->navTitleFlag = true;
            $app_configs = $this->application_settings();
            $this->layout->navTitle = '<span class="" style="width:100% !important;text-align:center;padding:5px;background-color:#00C0EF;color:white;font-size:18px; font-weight:bold;text-shadow: 2px 2px grey;">' . @$app_configs['college_name'] . '</span>';
            $this->breadcrumbs->push('Dashboard', '#');
            $this->layout->breadcrumbsFlag = false;

            $data = array();
            $data['gender_count'] = $this->dashboard_model->get_gender_wise_patients();
            $data['dept_wise_data'] = $this->dashboard_model->get_departmentwise_patient_count();
            $data['ipd_data'] = $this->dashboard_model->get_ipd_patients_count();

            $this->layout->data = $data;
            $this->layout->render();
        } else {
            $this->layout->render(array('error' => '401'));
        }
    }

    public function doctors_dashboard()
    {
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "Dashboard";
        $data = array();
        $data['gender_count'] = $this->dashboard_model->get_gender_wise_patients();
        $this->layout->data = $data;
        $this->layout->render();
    }

    public function blank_dashboard()
    {
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "Dashboard";
        $data = array();
        $this->layout->data = $data;
        $this->layout->render();
    }

    public function xray_dashabord()
    {
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "Dashboard";
        $this->load->model('patient/xray_model');
        $data = array();
        $data['stats'] = $this->xray_model->dashboard_stats();

        $this->layout->data = $data;
        $this->layout->render();
    }

    public function ecg_dashboard()
    {
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Dashboard";
        $this->load->model('patient/ecg_model');
        $data = array();
        $data['stats'] = $this->ecg_model->dashboard_stats();

        $this->layout->data = $data;
        $this->layout->render();
    }

    public function usg_dashboard()
    {
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "Dashboard";
        $this->load->model('patient/usg_model');
        $data = array();
        $data['stats'] = $this->usg_model->dashboard_stats();

        $this->layout->data = $data;
        $this->layout->render();
    }

    public function lab_dashboard()
    {

        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "Dashboard";
        $this->load->model('patient/lab_model');
        $data = array();
        $data['stats'] = $this->lab_model->dashboard_stats();

        $this->layout->data = $data;
        $this->layout->render();
    }

    public function opdscr_dashboard()
    {

        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "Dashboard";
        //$this->load->model('patient/lab_model');
        $data = array();
        //$data['stats'] = $this->lab_model->dashboard_stats();

        $this->layout->data = $data;
        $this->layout->render();
    }
}
