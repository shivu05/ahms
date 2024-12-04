<?php

class Medicine extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        $this->layout->title = "Pharmacy";
        $this->load->model('pharmacy/Medicine_model');
    }

    public function index() {
        $data['medicines'] = $this->Medicine_model->get_medicines();
        $this->layout->data = $data;
        $this->layout->render();
    }

    public function add() {
        if ($this->input->post()) {
            $data = array(
                'name' => $this->input->post('name'),
                'category' => $this->input->post('category'),
                'price' => $this->input->post('price'),
                'quantity' => $this->input->post('quantity'),
                'expiry_date' => $this->input->post('expiry_date')
            );
            $this->Medicine_model->add_medicine($data);
            redirect('medicine');
        } else {
            $this->layout->render();
        }
    }

    public function edit($id) {
        if ($this->input->post()) {
            $data = array(
                'name' => $this->input->post('name'),
                'category' => $this->input->post('category'),
                'price' => $this->input->post('price'),
                'quantity' => $this->input->post('quantity'),
                'expiry_date' => $this->input->post('expiry_date')
            );
            $this->Medicine_model->update_medicine($id, $data);
            redirect('medicine');
        } else {
            $data['medicine'] = $this->Medicine_model->get_medicine($id);
            $this->load->view('edit_medicine', $data);
        }
    }

    public function delete($id) {
        $this->Medicine_model->delete_medicine($id);
        redirect('medicine');
    }
}
