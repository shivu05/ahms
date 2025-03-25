<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of Medicines
 *
 * @author shiva
 */
class Medicines extends SHV_Controller {

    private $_is_admin;
    public function __construct() {
        parent::__construct();
        $this->load->model('Medicines_model');
        $this->load->library('form_validation');
        $this->_is_admin = $this->rbac->is_admin();
    }

    // Load medicines management page
    public function index() {
        $this->layout->title = "Pharmacy";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Medicines";
        $this->layout->navDescr = "";
        $this->scripts_include->includePlugins(array('datatables'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $data['is_admin'] = $this->_is_admin;
        $this->layout->data = $data;
        $this->layout->render();
    }

    // Fetch medicines data for DataTable
    public function fetch_medicines() {
        $data = $this->Medicines_model->get_datatables();
        echo json_encode($data);
    }

    // Add medicine
    public function add() {
        $this->_validate();

        $data = [
            'name' => $this->input->post('name'),
            'generic_name' => $this->input->post('generic_name'),
            'dosage' => $this->input->post('dosage'),
            'form' => $this->input->post('form'),
            'manufacturer' => $this->input->post('manufacturer'),
            'ndc' => $this->input->post('ndc'),
            'description' => $this->input->post('description'),
            'controlled_substance' => $this->input->post('controlled_substance') ? 1 : 0,
            'requires_prescription' => $this->input->post('requires_prescription') ? 1 : 0,
            'storage_conditions' => $this->input->post('storage_conditions'),
            'side_effects' => $this->input->post('side_effects'),
            'interactions' => $this->input->post('interactions'),
            'unit_price' => $this->input->post('unit_price'),
            'reorder_level' => $this->input->post('reorder_level'),
            'category' => $this->input->post('category'),
            'image' => $this->input->post('image'),
            'date_added' => date('Y-m-d')
        ];

        $this->Medicines_model->save($data);
        echo json_encode(["status" => TRUE]);
    }

    // Edit medicine
    public function edit($id) {
        $data = $this->Medicines_model->get_by_id($id);
        echo json_encode($data);
    }

    // Update medicine
    public function update() {
        $this->_validate();

        $data = [
            'name' => $this->input->post('name'),
            'generic_name' => $this->input->post('generic_name'),
            'dosage' => $this->input->post('dosage'),
            'form' => $this->input->post('form'),
            'manufacturer' => $this->input->post('manufacturer'),
            'ndc' => $this->input->post('ndc'),
            'description' => $this->input->post('description'),
            'controlled_substance' => $this->input->post('controlled_substance') ? 1 : 0,
            'requires_prescription' => $this->input->post('requires_prescription') ? 1 : 0,
            'storage_conditions' => $this->input->post('storage_conditions'),
            'side_effects' => $this->input->post('side_effects'),
            'interactions' => $this->input->post('interactions'),
            'unit_price' => $this->input->post('unit_price'),
            'reorder_level' => $this->input->post('reorder_level'),
            'category' => $this->input->post('category'),
            'image' => $this->input->post('image'),
            'last_updated' => date('Y-m-d')
        ];

        $this->Medicines_model->update(['id' => $this->input->post('id')], $data);
        echo json_encode(["status" => TRUE]);
    }

    // Delete medicine
    public function delete($id) {
        $this->Medicines_model->delete_by_id($id);
        echo json_encode(["status" => TRUE]);
    }

    // Validation rules
    private function _validate() {
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('generic_name', 'Generic Name', 'required');
        $this->form_validation->set_rules('dosage', 'Dosage', 'required');
        $this->form_validation->set_rules('form', 'Form', 'required');
        $this->form_validation->set_rules('manufacturer', 'Manufacturer', 'required');
        $this->form_validation->set_rules('unit_price', 'Unit Price', 'required|numeric');
        $this->form_validation->set_rules('reorder_level', 'Reorder Level', 'required|numeric');
        $this->form_validation->set_rules('category', 'Category', 'required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                "status" => FALSE,
                "errors" => $this->form_validation->error_array()
            ]);
            exit;
        }
    }
}
