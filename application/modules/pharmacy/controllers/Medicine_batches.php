<?php

class Medicine_batches extends SHV_Controller {
    private $is_admin;

    public function __construct() {
        parent::__construct();
        $this->load->model('pharmacy/medicine_batches_model');
        $this->is_admin = $this->rbac->is_admin();
    }

    // Load medicine batches management page
    public function index() {
        $this->layout->title = "Medicine Batches";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Medicine Batches";
        $this->scripts_include->includePlugins(array('datatables'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        
        $data['is_admin'] = $this->is_admin;
        $data['medicines'] = $this->medicine_batches_model->get_medicine_names();
        $data['suppliers'] = $this->medicine_batches_model->get_supplier_names();
        
        $this->layout->data = $data;
        $this->layout->render();
    }

    // Fetch medicine batches data for DataTable
    public function fetch_batches() {
        $data = $this->medicine_batches_model->get_datatables();
        echo json_encode($data);
    }

    // Add medicine batch
    public function add() {
        $this->_validate();

        $data = [
            'medicine_id' => $this->input->post('medicine_id'),
            'batch_number' => $this->input->post('batch_number'),
            'expiry_date' => $this->input->post('expiry_date'),
            'quantity_received' => $this->input->post('quantity_received'),
            'quantity_instock' => $this->input->post('quantity_instock'),
            'supplier_id' => $this->input->post('supplier_id'),
            'storage_location' => $this->input->post('storage_location'),
            'date_received' => $this->input->post('date_received')
        ];

        $this->medicine_batches_model->save($data);
        echo json_encode(["status" => TRUE]);
    }

    // Edit medicine batch
    public function edit($id) {
        $data = $this->medicine_batches_model->get_by_id($id);
        echo json_encode($data);
    }

    // Update medicine batch
    public function update() {
        $this->_validate();

        $data = [
            'medicine_id' => $this->input->post('medicine_id'),
            'batch_number' => $this->input->post('batch_number'),
            'expiry_date' => $this->input->post('expiry_date'),
            'quantity_received' => $this->input->post('quantity_received'),
            'quantity_instock' => $this->input->post('quantity_instock'),
            'supplier_id' => $this->input->post('supplier_id'),
            'storage_location' => $this->input->post('storage_location'),
            'date_received' => $this->input->post('date_received')
        ];

        $this->medicine_batches_model->update(['id' => $this->input->post('id')], $data);
        echo json_encode(["status" => TRUE]);
    }

    // Delete medicine batch
    public function delete($id) {
        $this->medicine_batches_model->delete_by_id($id);
        echo json_encode(["status" => TRUE]);
    }

    // Validation rules
    private function _validate() {
        $this->form_validation->set_rules('medicine_id', 'Medicine ID', 'required');
        $this->form_validation->set_rules('batch_number', 'Batch Number', 'required');
        $this->form_validation->set_rules('expiry_date', 'Expiry Date', 'required');
        $this->form_validation->set_rules('quantity_received', 'Quantity Received', 'required|numeric');
        $this->form_validation->set_rules('quantity_instock', 'Quantity In Stock', 'required|numeric');
        $this->form_validation->set_rules('supplier_id', 'Supplier ID', 'required');
        $this->form_validation->set_rules('storage_location', 'Storage Location', 'required');
        $this->form_validation->set_rules('date_received', 'Date Received', 'required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                "status" => FALSE,
                "errors" => $this->form_validation->error_array()
            ]);
            exit;
        }
    }
}
?>