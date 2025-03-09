<?php
// application/controllers/Pharmacy.php
class Pharmacy extends SHV_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pharmacy_model');
    }

    public function index()
    {
        $this->layout->title = "Pharmacy Management";
        $this->scripts_include->includePlugins(array('jq_validation', 'chosen', 'datatables'), 'js');
        $this->scripts_include->includePlugins(array('chosen', 'datatables'), 'css');
        $this->layout->render();
    }

    public function fetch_medicines()
    {
        $list = $this->Pharmacy_model->get_medicines_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $med) {
            $no++;
            $row = array();
            $row[] = $med->name;
            $row[] = $med->batch_no;
            $row[] = $med->expiry_date;
            $row[] = $med->price;
            $row[] = $med->gst;
            $row[] = $med->discount;
            $row[] = $med->stock;
            $row[] = '<button class="btn btn-warning btn-edit" data-id="' . $med->id . '">Edit</button> '
                . '<button class="btn btn-danger btn-delete" data-id="' . $med->id . '">Delete</button>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Pharmacy_model->count_all(),
            "recordsFiltered" => $this->Pharmacy_model->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function add_medicine()
    {
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('batch_no', 'Batch No', 'required');
        $this->form_validation->set_rules('price', 'Price', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('status' => 'NOK', 'icon' => 'fa-cross', 'message' => validation_errors(), 'type' => 'danger'));
            return;
        }

        $data = array(
            'name' => $this->input->post('name'),
            'batch_no' => $this->input->post('batch_no'),
            'expiry_date' => $this->input->post('expiry_date'),
            'price' => $this->input->post('price'),
            'gst' => $this->input->post('gst'),
            'discount' => $this->input->post('discount'),
            'stock' => $this->input->post('stock')
        );

        $id = $this->input->post('id');
        if ($id) {
            // Update existing record
            $update_status = $this->Pharmacy_model->update_medicine($id, $data);
            if ($update_status) {
                echo json_encode(array('status' => 'OK', 'icon' => 'fa-check', 'message' => 'Updated successfully', 'type' => 'success'));
            } else {
                echo json_encode(array('status' => 'NOK', 'icon' => 'fa-cross', 'message' => 'Failed to update data', 'type' => 'danger'));
            }
        } else {
            // Insert new record
            $insert_status = $this->Pharmacy_model->insert_medicine($data);
            if ($insert_status) {
                echo json_encode(array('status' => 'OK', 'icon' => 'fa-check', 'message' => 'Inserted successfully', 'type' => 'success'));
            } else {
                echo json_encode(array('status' => 'NOK', 'icon' => 'fa-cross', 'message' => 'Failed to insert data', 'type' => 'danger'));
            }
        }
    }

    public function edit_medicine($id)
    {
        $data = $this->Pharmacy_model->get_medicine_by_id($id);
        echo json_encode($data);
    }

    public function update_medicine()
    {
        $id = $this->input->post('id');
        $data = array(
            'name' => $this->input->post('name'),
            'batch_no' => $this->input->post('batch_no'),
            'expiry_date' => $this->input->post('expiry_date'),
            'price' => $this->input->post('price'),
            'gst' => $this->input->post('gst'),
            'discount' => $this->input->post('discount'),
            'stock' => $this->input->post('stock')
        );
        $this->Pharmacy_model->update_medicine($id, $data);
        echo json_encode(array("status" => "updated"));
    }

    public function delete_medicine()
    {
        $id = $this->input->post('id');
        $is_deleted = $this->Pharmacy_model->delete_medicine($id);
        if ($is_deleted) {
            echo json_encode(array('status' => 'OK', 'icon' => 'fa-check', 'message' => 'Deleted successfully', 'type' => 'success'));
        } else {
            echo json_encode(array('status' => 'NOK', 'icon' => 'fa-cross', 'message' => 'Failed to delete data', 'type' => 'danger'));
        }
    }
}
