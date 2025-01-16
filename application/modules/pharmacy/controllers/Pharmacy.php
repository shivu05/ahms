<?php

// Pharmacy Controller (Add CRUD for Medicines)
class Pharmacy extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Pharmacy_model');
        $this->load->helper('url');
        $this->load->library('form_validation');
    }

    // Medicines Management Page
    public function medicines() {
        $this->layout->render();
    }

    // Get Medicines (Server-side DataTable Processing)
    public function get_medicines() {
        $list = $this->Pharmacy_model->get_datatables();
        $data = [];
        $no = $_POST['start'];
        foreach ($list as $medicine) {
            $no++;
            $row = [];
            $row[] = $medicine->name;
            $row[] = $medicine->brand;
            $row[] = $medicine->price;
            $row[] = $medicine->stock;
            $row[] = $medicine->expiry_date;
            $row[] = '<button class="btn btn-primary btn-sm edit" data-id="' . $medicine->id . '">Edit</button> <button class="btn btn-danger btn-sm delete" data-id="' . $medicine->id . '">Delete</button>';

            $data[] = $row;
        }

        $output = [
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Pharmacy_model->count_all(),
            "recordsFiltered" => $this->Pharmacy_model->count_filtered(),
            "data" => $data,
        ];

        echo json_encode($output);
    }

    // Save Medicine (Add/Edit)
    public function save_medicine() {
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('price', 'Price', 'required|decimal');
        $this->form_validation->set_rules('stock', 'Stock', 'required|integer');
        $this->form_validation->set_rules('expiry_date', 'Expiry Date', 'required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => FALSE, 'errors' => validation_errors()]);
        } else {
            $data = [
                'name' => $this->input->post('name'),
                'brand' => $this->input->post('brand'),
                'price' => $this->input->post('price'),
                'stock' => $this->input->post('stock'),
                'expiry_date' => $this->input->post('expiry_date')
            ];

            if ($this->input->post('id')) {
                $this->Pharmacy_model->update_medicine($this->input->post('id'), $data);
            } else {
                $this->Pharmacy_model->add_medicine($data);
            }

            echo json_encode(['status' => TRUE]);
        }
    }

    // Delete Medicine
    public function delete_medicine($id) {
        $this->Pharmacy_model->delete_medicine($id);
        echo json_encode(['status' => TRUE]);
    }
}
