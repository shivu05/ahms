<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Read-only executive dashboard for hospital analytics.
 */
class Executive_dashboard extends SHV_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->rbac->is_login()) {
            redirect('Login');
        }

        $this->load->model('analytics/Executive_dashboard_model', 'executive_dashboard_model');
        $this->layout->title = 'Executive Dashboard';
        $this->layout->breadcrumbsFlag = false;
    }

    public function index()
    {
        $this->scripts_include->includePlugins(array('chartjs'), 'js');
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = 'Executive Dashboard';

        $data = $this->executive_dashboard_model->get_dashboard_data();
        $data['page_title'] = 'Executive Dashboard';

        $this->layout->render(array(
            'view' => 'executive_dashboard',
            'data' => $data
        ));
    }
}
