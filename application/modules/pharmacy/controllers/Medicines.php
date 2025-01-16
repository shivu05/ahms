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

    public function __construct() {
        parent::__construct();
        $this->load->model('Medicines_model');
        $this->load->library('form_validation');
    }

    // Load medicines management page
    public function index() {
        $this->layout->title = "Pharmacy";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Medicines";
        $this->layout->navDescr = "";
        $this->scripts_include->includePlugins(array('datatables'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
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
            'brand' => $this->input->post('brand'),
            'description' => $this->input->post('description'),
            'price' => $this->input->post('price'),
            'stock' => $this->input->post('stock'),
            'minimum_stock' => $this->input->post('minimum_stock'),
            'batch_number' => $this->input->post('batch_number'),
            'expiry_date' => $this->input->post('expiry_date'),
            'manufacture_date' => $this->input->post('manufacture_date'),
            'category' => $this->input->post('category'),
            'prescription_required' => $this->input->post('prescription_required') ? 1 : 0,
            'supplier_name' => $this->input->post('supplier_name'),
            'supplier_contact' => $this->input->post('supplier_contact'),
            'last_restock_date' => $this->input->post('last_restock_date')
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
            'brand' => $this->input->post('brand'),
            'description' => $this->input->post('description'),
            'price' => $this->input->post('price'),
            'stock' => $this->input->post('stock'),
            'minimum_stock' => $this->input->post('minimum_stock'),
            'batch_number' => $this->input->post('batch_number'),
            'expiry_date' => $this->input->post('expiry_date'),
            'manufacture_date' => $this->input->post('manufacture_date'),
            'category' => $this->input->post('category'),
            'prescription_required' => $this->input->post('prescription_required') ? 1 : 0,
            'supplier_name' => $this->input->post('supplier_name'),
            'supplier_contact' => $this->input->post('supplier_contact'),
            'last_restock_date' => $this->input->post('last_restock_date')
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
        $this->form_validation->set_rules('price', 'Price', 'required|numeric');
        $this->form_validation->set_rules('stock', 'Stock', 'required|integer');
        $this->form_validation->set_rules('expiry_date', 'Expiry Date', 'required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                "status" => FALSE,
                "errors" => $this->form_validation->error_array()
            ]);
            exit;
        }
    }
}
