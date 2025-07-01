<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dispense extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Dispense_model');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('session'); // For flash messages
    }

    /**
     * Loads the main dispensing view.
     */
    public function index()
    {
        $data['medicines'] = $this->Dispense_model->get_all_medicines();
        $this->load->view('dispense_view', $data);
    }

    /**
     * AJAX endpoint to get batches for a selected medicine.
     * Returns JSON.
     */
    public function get_batches_ajax()
    {
        $medicine_id = $this->input->post('medicine_id');
        if ($medicine_id) {
            $batches = $this->Dispense_model->get_batches_for_medicine($medicine_id);
            echo json_encode(['status' => 'success', 'batches' => $batches]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Medicine ID is required.']);
        }
        $this->output->set_content_type('application/json');
    }

    /**
     * AJAX endpoint to search for patients.
     * Returns JSON.
     */
    public function search_patient_ajax()
    {
        $search_term = $this->input->post('search_term');
        if ($search_term) {
            $patients = $this->Dispense_model->search_patients($search_term);
            echo json_encode(['status' => 'success', 'patients' => $patients]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Search term is required.']);
        }
        $this->output->set_content_type('application/json');
    }

    /**
     * AJAX endpoint to get patient details.
     * Returns JSON.
     */
    public function get_patient_details_ajax()
    {
        $patient_id = $this->input->post('patient_id');
        if ($patient_id) {
            $patient = $this->Dispense_model->get_patient_details($patient_id);
            if ($patient) {
                echo json_encode(['status' => 'success', 'patient' => $patient]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Patient not found.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Patient ID is required.']);
        }
        $this->output->set_content_type('application/json');
    }

    /**
     * AJAX endpoint to add a new patient.
     * Returns JSON.
     */
    public function add_patient_ajax()
    {
        $this->form_validation->set_rules('first_name', 'First Name', 'required|max_length[255]');
        $this->form_validation->set_rules('last_name', 'Last Name', 'max_length[255]');
        $this->form_validation->set_rules('phone_number', 'Phone Number', 'max_length[20]');
        $this->form_validation->set_rules('address', 'Address', 'max_length[255]');
        $this->form_validation->set_rules('date_of_birth', 'Date of Birth', 'callback_valid_date_format'); // Using custom callback

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => 'error', 'message' => validation_errors()]);
        } else {
            $data = array(
                'FirstName'     => $this->input->post('first_name'),
                'LastName'      => $this->input->post('last_name'),
                'PhoneNumber'   => $this->input->post('phone_number'),
                'Address'       => $this->input->post('address'),
                'DateOfBirth'   => $this->input->post('date_of_birth') ? $this->input->post('date_of_birth') : NULL,
                'Email'         => $this->input->post('email') ? $this->input->post('email') : NULL,
                'MedicalHistory' => $this->input->post('medical_history') ? $this->input->post('medical_history') : NULL
            );
            $patient_id = $this->Dispense_model->add_patient($data);
            if ($patient_id) {
                echo json_encode(['status' => 'success', 'message' => 'Patient added successfully!', 'patient_id' => $patient_id]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to add patient.']);
            }
        }
        $this->output->set_content_type('application/json');
    }

    /**
     * AJAX endpoint to process the final dispensing and billing.
     * This method uses a database transaction for atomicity.
     * Returns JSON.
     */
    public function process_dispense_ajax()
    {
        $patient_id = $this->input->post('patient_id');
        $cart_items = $this->input->post('cart_items'); // Array of objects: {medicine_id, batch_id, quantity, price}
        $total_amount = $this->input->post('total_amount');

        // Basic validation
        if (empty($patient_id) || !is_array($cart_items) || empty($cart_items) || empty($total_amount)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid data provided.']);
            $this->output->set_content_type('application/json');
            return;
        }

        $this->Dispense_model->start_transaction();
        $success = TRUE;

        try {
            // 1. Create Sale Record
            $sale_id = $this->Dispense_model->create_sale($patient_id, $total_amount);
            if (!$sale_id) {
                throw new Exception('Failed to create sale record.');
            }

            // 2. Add Sale Items and Update Batch Stock
            foreach ($cart_items as $item) {
                $medicine_id = $item['medicine_id'];
                $batch_id = $item['batch_id'];
                $quantity = $item['quantity'];
                $price = $item['price'];

                // Add item to SaleItems
                if (!$this->Dispense_model->add_sale_item($sale_id, $medicine_id, $batch_id, $quantity, $price)) {
                    throw new Exception('Failed to add sale item for medicine ID: ' . $medicine_id);
                }

                // Update batch stock
                if (!$this->Dispense_model->update_batch_stock($batch_id, $quantity)) {
                    throw new Exception('Failed to update stock for batch ID: ' . $batch_id);
                }
            }
        } catch (Exception $e) {
            $success = FALSE;
            log_message('error', 'Dispensing transaction failed: ' . $e->getMessage());
        }

        if ($this->Dispense_model->complete_transaction() && $success) {
            echo json_encode(['status' => 'success', 'message' => 'Medicines dispensed and bill generated successfully!', 'sale_id' => $sale_id]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Dispensing failed. Please check logs for details.']);
        }

        $this->output->set_content_type('application/json');
    }

    // Custom validation callback for date format (add this to your controller)
    public function valid_date_format($date)
    {
        if (empty($date)) { // Allow empty date for optional fields
            return TRUE;
        }
        if (preg_match("/^(\d{4})-(\d{2})-(\d{2})$/", $date, $matches)) {
            if (checkdate($matches[2], $matches[3], $matches[1])) {
                return TRUE;
            }
        }
        $this->form_validation->set_message('valid_date_format', 'The {field} field must be in YYYY-MM-DD format and a valid date.');
        return FALSE;
    }
}
