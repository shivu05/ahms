<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Panchakarma extends SHV_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('reports/Panchaprocedure');
    }

    public function index()
    {
        $data['unique_procedures'] = $this->Panchaprocedure->get_unique_procedures();
        $this->layout->title = "Patient reports";
        $this->layout->data = $data;
        $this->layout->render();
    }

    // Add other methods as needed
}