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
    }

    public function index() {
        $this->scripts_include->includePlugins(array('typeahead'));
        $data = array();
        $this->layout->data = $data;
        $this->layout->render();
    }

    public function get_opd_details() {
        if ($this->input->is_ajax_request()) {
            $type = $this->input->get('type');
            if ($type == 'opd') {
                echo json_encode(array('data' => array_column($this->db->select('OpdNo')->get('treatmentdata')->result_array(), 'OpdNo')));
            } else {
                echo json_encode(array('data' => array_column($this->db->select('ipdno')->get('ipdtreatment')->result_array(), 'ipdno')));
            }
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
