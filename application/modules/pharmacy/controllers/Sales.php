<?php

/**
 * Description of Sales
 *
 * @author Shiv
 */
class Sales extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        $this->layout->title = "Sales";
        $this->load->model('pharmacy/sales_model');
    }

    public function index() {
        $this->scripts_include->includePlugins(array('typeahead'));
        $data = array();
        $this->layout->data = $data;
        $this->layout->render();
    }

    public function fetch_patient_data() {
        if ($this->input->is_ajax_request()) {
            $opd = $this->input->post('opd');
            $data = array();
            $data['data'] = $this->sales_model->get_patient_data($opd);
            $this->layout->data = $data;
            echo $this->layout->render(array('view' => 'pharmacy/sales/fetch_patient_data'), true);
        }
    }

    public function fetch_patient_details() {
        if ($this->input->is_ajax_request()) {
            pma($this->input->post());
            echo $opd = $this->input->post('opd', true);
            echo $type = $this->input->post('type', true);
        }
    }

}
