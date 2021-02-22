<?php

/**
 * Description of Panchakarma
 *
 * @author Shivaraj
 */
class Panchakarma extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('master/panchakarma_model');
    }

    public function index() {
        show_error('<h2 style="color:red;padding-left:5%;">Unathorizes access</h2>', 401);
    }

    public function fetch_procedures() {
        $data = $this->panchakarma_model->get_procedures();
        if ($this->input->is_ajax_request()) {
            echo json_encode(array('data' => $data, 'status_code' => 200, 'status' => 'true'));
        } else {
            return $data;
        }
    }

    public function fetch_sub_procedures() {
        $proc_name = $this->input->post('proc_name');
        $data = $this->panchakarma_model->get_sub_procedures($proc_name);
        if ($this->input->is_ajax_request()) {
            echo json_encode(array('data' => $data, 'status_code' => 200, 'status' => 'true'));
        } else {
            return $data;
        }
    }

    public function list_procedures() {
        $this->scripts_include->includePlugins(array('jq_validation', 'js'));
        $this->layout->title = 'Panchakarma';
        $data['main_proc'] = $this->fetch_procedures();
        $this->layout->data = $data;
        $this->layout->render();
    }

    public function add_pancha_procedure() {
        if ($this->input->is_ajax_request()) {
            $proc_name = $this->input->post('proc_name');
            $is_inserted = $this->panchakarma_model->save_pancha_procedure(trim($proc_name));
            if ($is_inserted) {
                echo json_encode(array('status_code' => 200, 'status' => true));
            } else {
                echo json_encode(array('status_code' => 200, 'status' => false));
            }
        }
    }

    public function add_sub_pancha_procedure() {
        if ($this->input->is_ajax_request()) {
            $proc_name = $this->input->post('proc_name');
            $sub_proc_name = $this->input->post('sub_proc_name');
            $no_of_treatment_days = $this->input->post('no_of_treatment_days');
            $input = array(
                'procecure_id' => trim($proc_name),
                'sub_proc_name' => trim($sub_proc_name),
                'no_of_treatment_days' => trim($no_of_treatment_days),
            );
            $is_inserted = $this->panchakarma_model->save_sub_pancha_procedure($input);
            if ($is_inserted) {
                echo json_encode(array('status_code' => 200, 'status' => true));
            } else {
                echo json_encode(array('status_code' => 200, 'status' => false));
            }
        }
    }

    function update_sub_procedure() {
        if ($this->input->is_ajax_request()) {
            $edit_sub_proc_id = $this->input->post('edit_sub_proc_id');
            $edit_sub_proc_name = $this->input->post('edit_sub_proc_name');
            $edit_no_of_treatment_days = $this->input->post('edit_no_of_treatment_days');
            $input_array = array(
                'sub_proc_name' => $edit_sub_proc_name,
                'no_of_treatment_days' => $edit_no_of_treatment_days
            );
            $where = array(
                'id' => $edit_sub_proc_id
            );
            $is_updated = $this->panchakarma_model->update_sub_procedure($input_array, $where);
            if ($is_updated) {
                echo json_encode(array('status_code' => 200, 'status' => true));
            } else {
                echo json_encode(array('status_code' => 200, 'status' => false));
            }
        }
    }

}
