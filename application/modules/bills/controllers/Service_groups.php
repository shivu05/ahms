<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Service_groups extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Service_group_model');
        $this->layout->title = "Service groups";
    }

    // List all service groups
    public function index() {
        $data['service_groups'] = $this->Service_group_model->get_all_groups();
        $this->layout->data = $data;
        $this->layout->render();
    }

    // Insert or Update group
    public function save_group() {
        $group_id = $this->input->post('group_id');
        $data = [
            'group_name' => $this->input->post('group_name'),
            'description' => $this->input->post('description')
        ];
        if ($group_id) {
            $this->Service_group_model->update_group($group_id, $data);
        } else {
            $this->Service_group_model->insert_group($data);
        }
        echo json_encode(['status' => 'success']);
    }

    // Fetch group by ID for editing
    public function get_group() {
        $group_id = $this->input->post('group_id');
        $data = $this->Service_group_model->get_group_by_id($group_id);
        echo json_encode($data);
    }

    // Delete group
    public function delete_group() {
        $group_id = $this->input->post('group_id');
        $this->Service_group_model->delete_group($group_id);
        echo json_encode(['status' => 'success']);
    }
}
