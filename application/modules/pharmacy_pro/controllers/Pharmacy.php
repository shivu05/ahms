<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Professional Pharmacy Management Controller
 * Follows Apollo/Medplus standards for pharmacy operations
 */
class Pharmacy extends SHV_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('pharmacy_pro/Pharmacy_model');
        $this->load->model('pharmacy_pro/Medicine_model');
        $this->load->model('pharmacy_pro/Sales_model');
        $this->load->model('pharmacy_pro/Purchase_model');
        $this->load->model('pharmacy_pro/Inventory_model');
        $this->load->library('form_validation');
        $this->layout->title = "Pharmacy Management";
    }

    /**
     * Dashboard with key metrics
     */
    public function index()
    {
        // Check if pharmacy tables exist, if not show setup message
        if (!$this->db->table_exists('medicines')) {
            $data['setup_required'] = true;
            $data['total_medicines'] = 0;
            $data['low_stock_count'] = 0;
            $data['expired_count'] = 0;
            $data['todays_sales'] = 0;
            $data['monthly_sales'] = 0;
            $data['recent_sales'] = array();
            $data['expiring_soon'] = array();
        } else {
            try {
                $data['setup_required'] = false;
                $data['total_medicines'] = $this->Medicine_model->count_all_medicines();
                $data['low_stock_count'] = $this->Inventory_model->get_low_stock_count();
                $data['expired_count'] = $this->Inventory_model->get_expired_medicines_count();
                $data['todays_sales'] = $this->Sales_model->get_todays_sales_amount();
                $data['monthly_sales'] = $this->Sales_model->get_monthly_sales_amount();
                $data['recent_sales'] = $this->Sales_model->get_recent_sales(10);
                $data['expiring_soon'] = $this->Inventory_model->get_medicines_expiring_soon(30);
            } catch (Exception $e) {
                // Handle any database errors gracefully
                $data['setup_required'] = true;
                $data['error_message'] = $e->getMessage();
                $data['total_medicines'] = 0;
                $data['low_stock_count'] = 0;
                $data['expired_count'] = 0;
                $data['todays_sales'] = 0;
                $data['monthly_sales'] = 0;
                $data['recent_sales'] = array();
                $data['expiring_soon'] = array();
            }
        }

        $this->scripts_include->includePlugins(array('chart_js', 'datatables'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');

        $this->layout->data = $data;
        $this->layout->render();
    }

    /**
     * Point of Sale (POS) Interface
     */
    public function pos()
    {
        // Check if tables exist before loading data
        try {
            if ($this->db->table_exists('medicine_frequencies')) {
                $data['medicine_frequencies'] = $this->Pharmacy_model->get_medicine_frequencies();
            } else {
                $data['medicine_frequencies'] = array();
            }

            // Load doctors from users table
            $data['doctors'] = $this->get_doctors_list();
        } catch (Exception $e) {
            $data['medicine_frequencies'] = array();
            $data['doctors'] = array();
        }

        $this->scripts_include->includePlugins(array('chosen', 'jq_validation', 'jq_ui'), 'js');
        $this->scripts_include->includePlugins(array('chosen', 'jq_ui'), 'css');

        $this->layout->data = $data;
        $this->layout->render();
    }

    /**
     * Get doctors list for POS
     */
    private function get_doctors_list()
    {
        $this->db->select('ID, user_name');
        $this->db->from('users');
        $this->db->where('user_type', 4);
        $this->db->order_by('user_name', 'ASC');

        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Medicine Search for POS
     */
    public function search_medicines()
    {
        $search_term = $this->input->get('term');
        $medicines = $this->Medicine_model->search_medicines_for_pos($search_term);
        echo json_encode($medicines);
    }

    /**
     * Get medicine details by batch
     */
    public function get_medicine_batch_details()
    {
        $medicine_id = $this->input->post('medicine_id');
        $batches = $this->Inventory_model->get_available_batches($medicine_id);
        echo json_encode(array('status' => 'success', 'data' => $batches));
    }

    /**
     * Process Sale
     */
    public function process_sale()
    {
        $this->form_validation->set_rules('patient_name', 'Patient Name', 'required');
        $this->form_validation->set_rules('sale_items', 'Sale Items', 'required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('status' => 'error', 'message' => validation_errors()));
            return;
        }

        $sale_data = array(
            'patient_name' => $this->input->post('patient_name'),
            'patient_phone' => $this->input->post('patient_phone'),
            'patient_type' => $this->input->post('patient_type'),
            'patient_id' => $this->input->post('patient_id'),
            'doctor_id' => $this->input->post('doctor_id'),
            'prescription_id' => $this->input->post('prescription_id'),
            'subtotal' => $this->input->post('subtotal'),
            'discount_amount' => $this->input->post('discount_amount'),
            'tax_amount' => $this->input->post('tax_amount'),
            'total_amount' => $this->input->post('total_amount'),
            'paid_amount' => $this->input->post('paid_amount'),
            'payment_mode' => $this->input->post('payment_mode'),
            'sale_type' => $this->input->post('sale_type'),
            'remarks' => $this->input->post('remarks'),
            'cashier_id' => $this->rbac->get_uid()
        );

        $sale_items = json_decode($this->input->post('sale_items'), true);

        $result = $this->Sales_model->process_sale($sale_data, $sale_items);

        if ($result['status']) {
            echo json_encode(array(
                'status' => 'success',
                'message' => 'Sale completed successfully',
                'bill_number' => $result['bill_number'],
                'sale_id' => $result['sale_id']
            ));
        } else {
            echo json_encode(array('status' => 'error', 'message' => $result['message']));
        }
    }

    /**
     * Print Invoice
     */
    public function print_invoice($sale_id)
    {
        $data['sale'] = $this->Sales_model->get_sale_by_id($sale_id);
        $data['sale_items'] = $this->Sales_model->get_sale_items($sale_id);
        $data['app_settings'] = $this->application_settings();

        // Since we're in a module, we need to use the correct view path
        $this->layout->render(array(
            'view' => 'pharmacy/pos/invoice_print',  // Changed from pharmacy_pro/pos/invoice_print
            'data' => $data,
            'layout' => 'print_layout'  // Using a print-specific layout
        ));
    }

    /**
     * Sales Report
     */
    public function sales_report()
    {
        $this->scripts_include->includePlugins(array('datatables', 'daterangepicker', 'buttons', 'jq_validation'), 'js');
        $this->scripts_include->includePlugins(array('datatables', 'daterangepicker'), 'css');
        
        // Add payment modes for filter
        $data['payment_modes'] = array(
            '' => 'All Payment Modes',
            'cash' => 'Cash',
            'card' => 'Card',
            'upi' => 'UPI',
            'netbanking' => 'Net Banking'
        );
        
        $this->layout->data = $data;
        $this->layout->render();
    }

    /**
     * Get Sales Report Data
     */
    public function get_sales_report_data()
    {
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        $payment_mode = $this->input->post('payment_mode');

        // Get sales data
        $list = $this->Sales_model->get_sales_report($from_date, $to_date, $payment_mode);
        
        $formatted_data = array();
        $total_amount = 0;
        
        if (!empty($list) && is_array($list)) {
            foreach ($list as $sale) {
                // Convert to array if needed
                $sale = (array)$sale;
                
                // Calculate total
                $total = !empty($sale['total_amount']) ? floatval($sale['total_amount']) : 0;
                $total_amount += $total;

                // Format data according to DataTable columns
                $formatted_data[] = array(
                    'bill_number' => $sale['bill_number'] ?? '',
                    'sale_date' => $sale['sale_date'] ?? '',
                    'patient_display_name' => $sale['patient_display_name'] ?? '',
                    'payment_mode' => ucfirst($sale['payment_mode'] ?? ''),
                    'subtotal' => floatval($sale['subtotal'] ?? 0),
                    'discount_amount' => floatval($sale['discount_amount'] ?? 0),
                    'tax_amount' => floatval($sale['tax_amount'] ?? 0),
                    'total_amount' => floatval($sale['total_amount'] ?? 0),
                    'cashier_name' => $sale['cashier_name'] ?? ''
                );
            }
        }

        $response = array(
            'data' => $formatted_data
        );

        echo json_encode($response);
    }

    /**
     * Export Sales Report to Excel
     */
    public function export_sales_report() {
        $from_date = $this->input->get('from_date');
        $to_date = $this->input->get('to_date');
        $payment_mode = $this->input->get('payment_mode');

        // Get sales data
        $sales = $this->Sales_model->get_sales_report($from_date, $to_date, $payment_mode);
        
        // Load the Excel helper
        $this->load->helper('to_excel');

        // Prepare data for Excel
        $excel_data = array();
        
        // Header row
        $excel_data[] = array(
            'Bill Number',
            'Date',
            'Patient Name',
            'Patient Type',
            'Doctor',
            'Subtotal',
            'Discount',
            'Tax',
            'Total Amount',
            'Payment Mode'
        );
        
        // Data rows
        if (!empty($sales)) {
            foreach ($sales as $sale) {
                $excel_data[] = array(
                    isset($sale['bill_number']) ? $sale['bill_number'] : '',
                    isset($sale['sale_date']) ? date('d-m-Y', strtotime($sale['sale_date'])) : '',
                    isset($sale['patient_name']) ? $sale['patient_name'] : '',
                    isset($sale['patient_type']) ? $sale['patient_type'] . 
                        (isset($sale['patient_id']) && $sale['patient_id'] ? ' ('.$sale['patient_id'].')' : '') : '',
                    isset($sale['doctor_name']) ? $sale['doctor_name'] : '-',
                    isset($sale['subtotal']) ? number_format($sale['subtotal'], 2) : '0.00',
                    isset($sale['discount_amount']) ? number_format($sale['discount_amount'], 2) : '0.00',
                    isset($sale['tax_amount']) ? number_format($sale['tax_amount'], 2) : '0.00',
                    isset($sale['total_amount']) ? number_format($sale['total_amount'], 2) : '0.00',
                    isset($sale['payment_mode']) ? ucfirst($sale['payment_mode']) : ''
                );
            }
        }
        
        // Generate Excel file
        $filename = 'sales_report_' . date('d-m-Y_His');
        to_excel($excel_data, $filename);
    }

    /**
     * Inventory Management
     */
    public function inventory()
    {
        $this->layout->render();
    }

    /**
     * Get Inventory Data for DataTable
     */
    public function get_inventory_data()
    {
        $list = $this->Inventory_model->get_inventory_datatables();
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = $item->medicine_name;
            $row[] = $item->batch_number;
            $row[] = date('d-m-Y', strtotime($item->expiry_date));
            $row[] = $item->current_stock;
            $row[] = '₹' . number_format($item->mrp, 2);

            $status = '';
            if ($item->current_stock <= $item->minimum_stock) {
                $status = '<span class="label label-danger">Low Stock</span>';
            } elseif (strtotime($item->expiry_date) <= strtotime('+30 days')) {
                $status = '<span class="label label-warning">Expiring Soon</span>';
            } else {
                $status = '<span class="label label-success">Active</span>';
            }
            $row[] = $status;

            $row[] = '<button class="btn btn-sm btn-info btn-edit" data-id="' . $item->id . '">Edit</button> ' .
                '<button class="btn btn-sm btn-warning btn-adjust" data-id="' . $item->id . '">Adjust Stock</button>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Inventory_model->count_all_inventory(),
            "recordsFiltered" => $this->Inventory_model->count_filtered_inventory(),
            "data" => $data,
        );

        echo json_encode($output);
    }

    /**
     * Medicine Master Management
     */
    public function medicines()
    {
        $data['categories'] = $this->Medicine_model->get_categories();
        $data['manufacturers'] = $this->Medicine_model->get_manufacturers();
        $data['units'] = $this->Medicine_model->get_units();

        $this->scripts_include->includePlugins(array('chosen', 'datatables', 'jq_validation'), 'js');
        $this->scripts_include->includePlugins(array('chosen', 'datatables'), 'css');

        $this->layout->data = $data;
        $this->layout->render();
    }

    /**
     * Save Medicine
     */
    public function save_medicine()
    {
        $this->form_validation->set_rules('medicine_name', 'Medicine Name', 'required');
        $this->form_validation->set_rules('category_id', 'Category', 'required');
        $this->form_validation->set_rules('manufacturer_id', 'Manufacturer', 'required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('status' => 'error', 'message' => validation_errors()));
            return;
        }

        $medicine_data = array(
            'medicine_name' => $this->input->post('medicine_name'),
            'generic_name' => $this->input->post('generic_name'),
            'category_id' => $this->input->post('category_id'),
            'manufacturer_id' => $this->input->post('manufacturer_id'),
            'unit_id' => $this->input->post('unit_id'),
            'medicine_type' => $this->input->post('medicine_type', true) ?: $this->input->post('medicine_type'), // Ensure we get the value even if it's unchanged
            'composition' => $this->input->post('composition'),
            'strength' => $this->input->post('strength'),
            'therapeutic_class' => $this->input->post('therapeutic_class'),
            // normalize to 'yes'/'no' as schema expects
            'prescription_required' => $this->input->post('prescription_required') ? 'yes' : 'no',
            'reorder_level' => $this->input->post('reorder_level')
        );

        $medicine_id = $this->input->post('medicine_id');

        if ($medicine_id) {
            $result = $this->Medicine_model->update_medicine($medicine_id, $medicine_data);
            $message = 'Medicine updated successfully';
        } else {
            $medicine_data['medicine_code'] = $this->Medicine_model->generate_medicine_code();
            $result = $this->Medicine_model->add_medicine($medicine_data);
            $message = 'Medicine added successfully';
        }

        if ($result) {
            echo json_encode(array('status' => 'success', 'message' => $message));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Operation failed'));
        }
    }

    /**
     * Get single medicine details (AJAX)
     */
    public function get_medicine()
    {
        $id = $this->input->get_post('id');
        if (!$id) {
            echo json_encode(array('status' => 'error', 'message' => 'Invalid medicine id'));
            return;
        }

        $medicine = $this->Medicine_model->get_medicine_by_id($id);
        if ($medicine) {
            echo json_encode(array('status' => 'success', 'data' => $medicine));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Medicine not found'));
        }
    }

    /**
     * Delete medicine (AJAX)
     */
    public function delete_medicine()
    {
        $id = $this->input->post('id');
        if (!$id) {
            echo json_encode(array('status' => 'error', 'message' => 'Invalid medicine id'));
            return;
        }

        $res = $this->Medicine_model->delete_medicine($id);
        if (is_array($res) && isset($res['status']) && $res['status'] === false) {
            echo json_encode(array('status' => 'error', 'message' => $res['message']));
            return;
        }

        if ($res) {
            echo json_encode(array(
                'status' => 'success',
                'message' => 'Medicine deleted successfully',
                'refresh' => true // Add this flag to trigger DataTable refresh
            ));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Delete failed'));
        }
    }

    /**
     * Purchase Management
     */
    public function purchases()
    {
        $data['suppliers'] = $this->Purchase_model->get_suppliers();

        $this->scripts_include->includePlugins(array('chosen', 'datatables', 'jq_validation'), 'js');
        $this->scripts_include->includePlugins(array('chosen', 'datatables'), 'css');

        $this->layout->data = $data;
        $this->layout->render();
    }

    /**
     * Create Purchase Order
     */
    public function create_purchase_order()
    {
        $data['suppliers'] = $this->Purchase_model->get_suppliers();
        $data['medicines'] = $this->Medicine_model->get_all_medicines();

        $this->layout->data = $data;
        $this->layout->render();
    }

    /**
     * Save Purchase Order
     */
    public function save_purchase_order()
    {
        // Implementation for saving purchase order
        $po_data = array(
            'supplier_id' => $this->input->post('supplier_id'),
            'po_date' => $this->input->post('po_date'),
            'expected_delivery_date' => $this->input->post('expected_delivery_date'),
            'subtotal' => $this->input->post('subtotal'),
            'tax_amount' => $this->input->post('tax_amount'),
            'total_amount' => $this->input->post('total_amount'),
            'payment_terms' => $this->input->post('payment_terms'),
            'remarks' => $this->input->post('remarks'),
            'created_by' => $this->rbac->get_uid()
        );

        $po_items = json_decode($this->input->post('po_items'), true);

        $result = $this->Purchase_model->create_purchase_order($po_data, $po_items);

        if ($result['status']) {
            echo json_encode(array('status' => 'success', 'message' => 'Purchase order created successfully', 'po_number' => $result['po_number']));
        } else {
            echo json_encode(array('status' => 'error', 'message' => $result['message']));
        }
    }

    /**
     * Reports Menu
     */
    public function reports()
    {
        $this->layout->render();
    }

    /**
     * Stock Report
     */
    public function stock_report()
    {
        $this->layout->render();
    }

    /**
     * Expiry Report
     */
    public function expiry_report()
    {
        $data['expiring_medicines'] = $this->Inventory_model->get_medicines_expiring_soon(90);
        $this->layout->data = $data;
        $this->layout->render();
    }

    /**
     * Low Stock Report
     */
    public function low_stock_report()
    {
        $data['low_stock_medicines'] = $this->Inventory_model->get_low_stock_medicines();
        $this->layout->data = $data;
        $this->layout->render();
    }

    /**
     * ABC Analysis Report
     */
    public function abc_analysis()
    {
        $data['abc_analysis'] = $this->Sales_model->get_abc_analysis();
        $this->layout->data = $data;
        $this->layout->render();
    }

    /**
     * Drug Information
     */
    public function drug_info()
    {
        $this->layout->render();
    }

    /**
     * Get Drug Information
     */
    public function get_drug_info()
    {
        $medicine_name = $this->input->post('medicine_name');
        $drug_info = $this->Medicine_model->get_drug_information($medicine_name);
        echo json_encode($drug_info);
    }

    /**
     * Settings
     */
    public function settings()
    {
        $data['categories'] = $this->Medicine_model->get_categories();
        $data['manufacturers'] = $this->Medicine_model->get_manufacturers();
        $data['suppliers'] = $this->Purchase_model->get_suppliers();
        $data['units'] = $this->Medicine_model->get_units();

        $this->layout->data = $data;
        $this->layout->render();
    }

    /**
     * Prescription Management
     */
    public function prescriptions()
    {
        $this->layout->render();
    }

    /**
     * Get Prescription Data
     */
    public function get_prescription_data()
    {
        $prescriptions = $this->Pharmacy_model->get_prescriptions_datatables();
        // Implementation for prescription data table
        echo json_encode($prescriptions);
    }

    /**
     * Patient Medicine History
     */
    public function patient_history($patient_id)
    {
        $data['patient'] = $this->Pharmacy_model->get_patient_details($patient_id);
        $data['medicine_history'] = $this->Sales_model->get_patient_medicine_history($patient_id);

        $this->layout->data = $data;
        $this->layout->render();
    }

    /**
     * Backup Data
     */
    public function backup_data()
    {
        $backup_result = $this->Pharmacy_model->create_data_backup();
        echo json_encode($backup_result);
    }

    /**
     * Database Setup Check
     */
    public function setup()
    {
        $data['tables_status'] = $this->check_database_setup();
        $this->layout->data = $data;
        $this->layout->render();
    }

    /**
     * Check database setup status
     */
    private function check_database_setup()
    {
        $required_tables = array(
            'medicine_categories',
            'medicine_manufacturers',
            'medicine_units',
            'medicines',
            'medicine_batches',
            'medicine_suppliers',
            'medicine_sales',
            'medicine_sale_items',
            'medicine_purchases',
            'medicine_purchase_items',
            'purchase_orders',
            'purchase_order_items',
            'prescriptions',
            'prescription_items',
            'medicine_stock_adjustments',
            'medicine_stock_adjustment_items',
            'medicine_returns',
            'medicine_return_items',
            'medicine_frequencies',
            'daily_stock_summary'
        );

        $tables_status = array();
        foreach ($required_tables as $table) {
            $tables_status[$table] = $this->db->table_exists($table);
        }

        return $tables_status;
    }

    /**
     * Search patients for POS
     */
    public function search_patients()
    {
        $search_term = $this->input->get('term');
        $patient_type = $this->input->get('type');

        $patients = array();

        if ($patient_type == 'opd') {
            $this->db->select('OpdNo as id, CONCAT_WS(" ", FirstName, LastName) as name, mob as phone, Age as age, gender');
            $this->db->from('patientdata');
            $this->db->group_start();
            $this->db->like('CONCAT_WS(" ", FirstName, LastName)', $search_term, FALSE);
            $this->db->or_like('OpdNo', $search_term);
            $this->db->or_like('FirstName', $search_term);
            $this->db->or_like('LastName', $search_term);
            $this->db->group_end();
            $this->db->where('OpdNo IS NOT NULL');
            $this->db->limit(20);
            $query = $this->db->get();
            $patients = $query->result_array();
        } elseif ($patient_type == 'ipd') {
            $this->db->select('IpNo as id, FName as name, "" as phone, Age as age, Gender as gender');
            $this->db->from('inpatientdetails');
            $this->db->group_start();
            $this->db->like('FName', $search_term);
            $this->db->or_like('IpNo', $search_term);
            $this->db->group_end();
            $this->db->where('IpNo IS NOT NULL');
            $this->db->limit(20);
            $query = $this->db->get();
            $patients = $query->result_array();
        }

        // Format for autocomplete
        $formatted_results = array();
        foreach ($patients as $patient) {
            $formatted_results[] = array(
                'id' => $patient['id'],
                'label' => $patient['name'] . ' (' . $patient['id'] . ')',
                'value' => $patient['name'],
                'name' => $patient['name'],
                'phone' => $patient['phone'],
                'age' => $patient['age'],
                'gender' => $patient['gender']
            );
        }

        echo json_encode($formatted_results);
    }

    /**
     * Get medicines data for DataTable (AJAX)
     */
    public function get_medicines_data()
    {
        // DataTables parameters
        $draw = isset($_POST['draw']) ? intval($_POST['draw']) : 1;
        $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
        $length = isset($_POST['length']) ? intval($_POST['length']) : 10;

        // Fetch data using model
        $list = $this->Medicine_model->get_medicines_datatables();

        $data = array();
        $no = $start;
        foreach ($list as $medicine) {
            $no++;
            $row = array();
            $row[] = $medicine->medicine_code;
            $row[] = $medicine->medicine_name;
            $row[] = $medicine->generic_name;
            $row[] = isset($medicine->category_name) ? $medicine->category_name : '';
            $row[] = isset($medicine->manufacturer_name) ? $medicine->manufacturer_name : '';
            $row[] = $medicine->medicine_type;
            $actions = '<button class="btn btn-sm btn-info btn-edit" data-id="' . $medicine->id . '">Edit</button> ';
            $actions .= '<button class="btn btn-sm btn-danger btn-delete" data-id="' . $medicine->id . '">Delete</button>';
            $row[] = $actions;

            $data[] = $row;
        }

        $output = array(
            'draw' => $draw,
            'recordsTotal' => $this->Medicine_model->count_all_medicines(),
            'recordsFiltered' => $this->Medicine_model->count_filtered_medicines(),
            'data' => $data
        );

        echo json_encode($output);
    }
}
