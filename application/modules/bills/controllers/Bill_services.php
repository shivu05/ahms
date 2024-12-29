<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Bill_services extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('bill_services_model');
        $this->load->model('service_group_model'); // For fetching service groups
        $this->layout->title = "Bill services";
    }

    public function index() {
        // Load services and service groups for the dropdown
        $data['services'] = $this->bill_services_model->get_all_services();
        $data['service_groups'] = $this->service_group_model->get_all_groups();
        $this->layout->data = $data;
        $this->layout->render();
    }

    // Save or update a service
    public function save_service() {
        $service_id = $this->input->post('service_id');
        $data = [
            'service_name' => $this->input->post('service_name'),
            'group_id' => $this->input->post('group_id'),
            'price' => $this->input->post('price')
        ];

        if ($service_id) {
            $this->bill_services_model->update_service($service_id, $data);
        } else {
            $this->bill_services_model->insert_service($data);
        }
        echo json_encode(['status' => 'success']);
    }

    // Fetch service by ID for editing
    public function get_service() {
        $service_id = $this->input->post('service_id');
        $data = $this->bill_services_model->get_service_by_id($service_id);
        echo json_encode($data);
    }

    // Delete a service
    public function delete_service() {
        $service_id = $this->input->post('service_id');
        $this->bill_services_model->delete_service($service_id);
        echo json_encode(['status' => 'success']);
    }

    public function get_service_details() {
        $service_id = $this->input->get('service_id');
        $service = $this->bill_services_model->get_service_by_id($service_id);
        echo json_encode($service);
    }
}
