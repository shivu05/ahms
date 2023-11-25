<?php

/**
 * Description of Patient_reports
 *
 * @author shiv
 */
class Patient_reports extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('reports/Patient_report_model', 'prep_model');
    }

    public function view() {
        $this->layout->title = "Patient reports";
        $this->layout->render();
    }

    public function fetch_patient_repo() {
        if ($this->input->is_ajax_request()) {
            $treat_id = $this->input->post('treatment_date');
            $data = $this->prep_model->get_report_stats($treat_id);
            echo json_encode(array('data' => $data));
        }
        return false;
    }
}
