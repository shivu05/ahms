<?php

/**
 * Description of Otherprocedures
 *
 * @author shivaraj.badiger
 */
class Otherprocedures extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('master/master_other_procedures');
        $this->layout->title = "Other Procedures";
    }

    public function index() {
        $this->scripts_include->includePlugins(array('datatables'));
        $data['proc_list'] = $this->master_other_procedures->all();
        $this->layout->data = $data;
        $this->layout->render();
    }

    public function save() {
        if ($this->input->is_ajax_request()) {
            $physi_name = $this->input->post('physi_name');
            $physi_id = $this->input->post('physi_id');
            $input_arr = array(
                'name' => trim($physi_name)
            );
            $is_inserted = false;
            $msg = '';
            if ($physi_id) {
                $where = array(
                    'id' => $physi_id
                );
                $is_inserted = $this->master_other_procedures->update($input_arr, $where);
                $msg = 'Updated successfully';
            } else {
                $is_inserted = $this->master_other_procedures->store($input_arr);
                $msg = 'Added successfully';
            }

            if ($is_inserted) {
                echo json_encode(array('status' => 'success', 'msg' => $msg, 'title' => 'Physiotherapy'));
            } else {
                echo json_encode(array('status' => 'danger', 'msg' => 'Failed to insert data', 'title' => 'Physiotherapy'));
            }
        } else {
            echo json_encode(array('status' => 'danger', 'msg' => 'Invalid request', 'title' => 'Physiotherapy'));
        }
    }

    public function delete() {
        if ($this->input->is_ajax_request()) {
            $physi_id = $this->input->post('physi_id');
            $where = array(
                'id' => $physi_id
            );
            $is_deleted = $this->master_other_procedures->delete($where);
            if ($is_deleted) {
                echo json_encode(array('status' => 'success', 'msg' => 'Deleted successfully', 'title' => 'Physiotherapy'));
            } else {
                echo json_encode(array('status' => 'danger', 'msg' => 'Failed to delete data', 'title' => 'Physiotherapy'));
            }
        } else {
            echo json_encode(array('status' => 'danger', 'msg' => 'Invalid request', 'title' => 'Physiotherapy'));
        }
    }

}
