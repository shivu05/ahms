<?php

/**
 * Description of Laboratory reports module
 *
 * @author Shivaraj B
 */
class Lab extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('patient/lab_model');
    }

    function index() {
        $this->layout->title = "Lab";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Laboratory";
        $this->layout->navDescr = "";
        $this->scripts_include->includePlugins(array('datatables', 'js'));
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'export-lab-report');
        $data['dept_list'] = $this->get_department_list('array');
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_pending_lab_list() {
        $input_array = array();

        $input_array['start'] = $this->input->post('start');
        $input_array['length'] = $this->input->post('length');
        $input_array['order'] = $this->input->post('order');
        $data = $this->lab_model->get_pending_labs($input_array);
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

    public function save_lab_data() {
        $testvalue = $this->input->post('test_value');
        $testrange = $this->input->post('test_range');
        $test_date = $this->input->post('test_date');
        $test_refdate = $this->input->post('test_refdate');
        $lab_id = $this->input->post('lab_id');
        $i = 0;
        foreach ($lab_id as $id) {
            $form_data = array(
                'testvalue' => $testvalue[$i],
                'testrange' => $testrange[$i],
                'tested_date' => $test_date,
                'testDate' => $test_refdate
            );
            $this->lab_model->update($form_data, $id);
            $i++;
        }
        if ($i == sizeof($lab_id)) {
            echo TRUE;
        } else {
            echo FALSE;
        }
    }

    public function get_lab_records() {
        $opdno = $this->input->post('opdno');
        $treat_id = $this->input->post('treat_id');
        $where = array(
            'OpdNo' => $opdno,
            'treatId' => $treat_id
        );
        $data['opd'] = $opdno;
        $data['records'] = $this->lab_model->get_lab_data($where);
        echo $this->load->view('patient/lab/get_lab_records', $data);
    }

    public function export() {
        
    }

}
