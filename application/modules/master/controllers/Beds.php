<?php

/**
 * Description of Beds
 *
 * @author shiva
 */
class Beds extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        $this->layout->title = "Bed Management";
    }

    public function index() {
        $this->scripts_include->includePlugins(array('datatables', 'js'));
        $this->scripts_include->includePlugins(array('datatables', 'css'));
        $this->load->model('master/bed_details');
        $data['beds_list'] = $this->bed_details->all();
        $data['dept_list'] = $this->get_department_list('array');
        $this->layout->data = $data;
        $this->layout->render();
    }

    public function bed_allocation() {
        $this->load->model('master/bed_details');
        $data['beds_list'] = $this->bed_details->all();
        $this->layout->data = $data;
        $this->layout->render();
    }

    public function update() {
        if ($this->input->is_ajax_request()) {
            $post_values = $this->input->post();
            $this->load->model('master/bed_details');
            $data = array(
                'department' => $post_values['department']
            );
            $is_updated = $this->bed_details->update($data, array('id' => $post_values['id']));
            if ($is_updated) {
                echo json_encode(array('msg' => 'Updated Successfully', 'status' => 'ok'));
            } else {
                echo json_encode(array('msg' => 'Failed to update', 'status' => 'nok'));
            }
        } else {
            echo json_encode(array('msg' => 'Failed to update', 'status' => 'nok'));
        }
    }
}
