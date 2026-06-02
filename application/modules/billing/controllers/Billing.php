<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Billing Controller - Main Billing Module Controller
 * Handles invoice creation, payment processing, and administrative functions
 */
class Billing extends SHV_Controller {
    protected $billing_settings = [];

    public function __construct() {
        parent::__construct();
        $this->ensure_authenticated();
        $this->load->model('billing/Billing_model');
        $this->load->model('billing/Service_model');
        $this->load->model('billing/Payment_model');
        $this->load->model('billing/Insurance_model');
        $this->load->library('form_validation');
        $this->load->helper(['form', 'url', 'security']);
        $this->billing_settings = $this->load_billing_settings();
        
        // Load billing helper from module
        $helper_path = APPPATH . 'modules/billing/helpers/Billing_helper.php';
        if (file_exists($helper_path)) {
            require_once($helper_path);
        }
        
        $this->layout->title = "Billing Management";
    }

    /**
     * Dashboard
     */
    public function index() {
        try {
            // Check if billing tables exist
            if (!$this->db->table_exists('billing_invoices')) {
                $data['setup_required'] = true;
                $data['message'] = 'Billing module tables not found. Please run the SQL installation script.';
            } else {
                $data['setup_required'] = false;
                
                // Get today's statistics
                $today = date('Y-m-d');
                $from_date = date('Y-m-d', strtotime('first day of this month'));
                $to_date = date('Y-m-d', strtotime('last day of this month'));
                
                // Today's invoices
                $data['today_invoices'] = $this->db->where('DATE(invoice_date)', $today)
                                                   ->count_all_results('billing_invoices');
                
                $today_summary = $this->Billing_model->get_invoice_summary([
                    'from_date' => $today,
                    'to_date' => $today
                ]);
                
                // Month's summary
                $month_summary = $this->Billing_model->get_invoice_summary([
                    'from_date' => $from_date,
                    'to_date' => $to_date
                ]);
                
                // Payment summary
                $payment_summary = $this->db->select('
                                        COUNT(payment_id) as total_payments,
                                        SUM(payment_amount) as total_amount
                                      ')
                                          ->where('DATE(payment_date)', $today)
                                          ->get('billing_payments')
                                          ->row_array();
                
                $data['today_summary'] = $today_summary;
                $data['month_summary'] = $month_summary;
                $data['today_payments'] = $payment_summary['total_payments'] ?? 0;
                $data['today_collection'] = $payment_summary['total_amount'] ?? 0;
                
                // Pending claims
                $data['pending_claims'] = $this->db->where('claim_status', 'UNDER_PROCESS')
                                                   ->count_all_results('billing_insurance_claims');
                
                // Overdue invoices
                $data['overdue_invoices'] = count($this->Billing_model->get_overdue_invoices());
                
                // Recent invoices
                $data['recent_invoices'] = $this->db->select('invoice_id, invoice_number, invoice_date, patient_name, total_amount, payment_status')
                                                    ->order_by('invoice_date', 'DESC')
                                                    ->limit(10)
                                                    ->get('billing_invoices')
                                                    ->result_array();
            }
            $this->scripts_include->includePlugins(array('chart_js'), 'js');
            $this->layout->data = $data;
            $this->layout->render();
        } catch (Exception $e) {
            log_message('error', 'Billing dashboard error: ' . $e->getMessage());
            $this->layout->data = ['error' => 'Error loading dashboard'];
            $this->layout->render();
        }
    }

    /**
     * Create new invoice (OPD/IPD)
     */
    public function create_invoice() {
        try {
            if ($this->input->post()) {
                // Validation rules
                $invoice_type_keys = array_keys($this->billing_settings['invoice_types'] ?? [
                    'OPD' => 'OPD Invoice',
                    'IPD' => 'IPD Invoice',
                    'EMERGENCY' => 'Emergency',
                    'PHARMACY' => 'Pharmacy'
                ]);
                $this->form_validation->set_rules('invoice_type', 'Invoice Type', 'required|in_list[' . implode(',', $invoice_type_keys) . ']');
                $this->form_validation->set_rules('patient_id', 'Patient ID', 'required');
                $this->form_validation->set_rules('invoice_date', 'Invoice Date', 'required');
                $this->form_validation->set_rules('service_id[]', 'Service', 'required');
                $this->form_validation->set_rules('quantity[]', 'Quantity', 'required|numeric|greater_than[0]');
                $this->form_validation->set_rules('discount[]', 'Discount', 'numeric|greater_than_equal_to[0]|less_than_equal_to[100]');
                
                if ($this->form_validation->run() == FALSE) {
                    $this->session->set_flashdata('error', validation_errors());
                } else {
                    // Get form data
                    $invoice_type = strtoupper(trim((string) $this->input->post('invoice_type', true)));
                    $patient_id = trim((string) $this->input->post('patient_id', true));
                    $invoice_date = trim((string) $this->input->post('invoice_date', true));
                    $notes = trim((string) $this->input->post('notes', true));
                    
                    $date_obj = DateTime::createFromFormat('Y-m-d', $invoice_date);
                    if (!$date_obj || $date_obj->format('Y-m-d') !== $invoice_date) {
                        $this->session->set_flashdata('error', 'Invalid invoice date. Use YYYY-MM-DD.');
                        redirect('billing/create_invoice');
                    }
                    
                    $service_ids = (array) $this->input->post('service_id', true);
                    $quantities = (array) $this->input->post('quantity', true);
                    $discounts = (array) $this->input->post('discount', true);
                    
                    // Calculate totals
                    $subtotal = 0;
                    $total_discount = 0;
                    $total_gst = 0;
                    $items = [];
                    
                    for ($i = 0; $i < count($service_ids); $i++) {
                        if (!empty($service_ids[$i])) {
                            $service_id = (int) $service_ids[$i];
                            $qty = isset($quantities[$i]) ? round((float) $quantities[$i], 2) : 0;
                            $discount_pct = isset($discounts[$i]) ? round((float) $discounts[$i], 2) : 0;
                            
                            if ($qty <= 0) {
                                $this->session->set_flashdata('error', 'Quantity must be greater than zero.');
                                redirect('billing/create_invoice');
                            }
                            if ($discount_pct < 0 || $discount_pct > 100) {
                                $this->session->set_flashdata('error', 'Discount must be between 0 and 100%.');
                                redirect('billing/create_invoice');
                            }
                            
                            // Get service details
                            if (!$this->Service_model->is_service_available($service_id, $invoice_type === 'EMERGENCY' ? 'OPD' : $invoice_type)) {
                                $this->session->set_flashdata('error', 'Selected service is not available for this invoice type.');
                                redirect('billing/create_invoice');
                            }
                            $service = $this->Service_model->get_service_pricing($service_id);
                            if (!$service) {
                                $this->session->set_flashdata('error', 'Selected service could not be found.');
                                redirect('billing/create_invoice');
                            }
                            
                            $price = round((float) $service['unit_price'], 2);
                            $gst_pct = !empty($service['gst_applicable']) ? round((float) $service['gst_rate'], 2) : 0;
                            
                            $item_total = $qty * $price;
                            $item_discount = ($item_total * $discount_pct) / 100;
                            $taxable_amount = $item_total - $item_discount;
                            $item_gst = ($taxable_amount * $gst_pct) / 100;
                            $line_total = $taxable_amount + $item_gst;
                            
                            $subtotal += $item_total;
                            $total_discount += $item_discount;
                            $total_gst += $item_gst;
                            
                            $items[] = [
                                'service_id' => $service_id,
                                'service_code' => $service['service_code'] ?? '',
                                'service_name' => $service['service_name'] ?? 'Service',
                                'quantity' => $qty,
                                'unit_price' => $price,
                                'discount_percentage' => $discount_pct,
                                'discount_amount' => round($item_discount, 2),
                                'gst_applicable' => $gst_pct > 0 ? 1 : 0,
                                'gst_rate' => $gst_pct,
                                'gst_amount' => round($item_gst, 2),
                                'item_amount' => round($item_total, 2),
                                'line_total' => round($line_total, 2)
                            ];
                        }
                    }
                    
                    if (empty($items)) {
                        $this->session->set_flashdata('error', 'Please select at least one active service.');
                        redirect('billing/create_invoice');
                    }
                    
                    $grand_total = round($subtotal - $total_discount + $total_gst, 2);
                    
                    // Prepare invoice data
                    $invoice_data = [
                        'invoice_date' => $invoice_date,
                        'invoice_type' => $invoice_type,
                        'subtotal_amount' => round($subtotal, 2),
                        'discount_amount' => round($total_discount, 2),
                        'gst_amount' => round($total_gst, 2),
                        'total_amount' => $grand_total,
                        'amount_paid' => 0,
                        'balance_due' => $grand_total,
                        'invoice_status' => 'ISSUED',
                        'payment_status' => 'UNPAID',
                        'remarks' => $notes,
                        'created_by' => $this->session->userdata('user_id') ?? 1,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    
                    // Set patient identifier based on type
                    if ($invoice_type == 'OPD' || $invoice_type == 'EMERGENCY') {
                        $invoice_data['opd_no'] = $patient_id;
                    } elseif ($invoice_type == 'IPD') {
                        $invoice_data['ipd_no'] = $patient_id;
                    }
                    
                    $created = $this->Billing_model->create_invoice_with_items($invoice_data, $items);
                    
                    if ($created) {
                        $this->session->set_flashdata('success', 'Invoice created successfully! Invoice #' . $created['invoice_number']);
                        redirect('billing/view_invoice/' . $created['invoice_id']);
                    } else {
                        $this->session->set_flashdata('error', 'Failed to create invoice');
                        redirect('billing/create_invoice');
                    }
                }
            }
            
            // Load form data
            $data['invoice_types'] = $this->billing_settings['invoice_types'] ?? [
                'OPD' => 'OPD Invoice',
                'IPD' => 'IPD Invoice',
                'EMERGENCY' => 'Emergency',
                'PHARMACY' => 'Pharmacy'
            ];
            $data['service_categories'] = $this->Service_model->get_categories();
            
            $this->scripts_include->includePlugins(array('chosen', 'jq_validation'), 'js');
            $this->scripts_include->includePlugins(array('chosen'), 'css');
            
            $this->layout->data = $data;
            $this->layout->render();
        } catch (Exception $e) {
            log_message('error', 'Error creating invoice: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Error creating invoice: ' . $e->getMessage());
            redirect('billing/create_invoice');
        }
    }

    /**
     * View invoice details
     */
    public function view_invoice($invoice_id) {
        try {
            $invoice = $this->Billing_model->get_invoice_details($invoice_id);

            if (!$invoice) {
                $this->session->set_flashdata('error', 'Invoice not found');
                redirect('billing');
            }

            // Normalize keys expected by the view
            // Ensure an 'id' key exists for URL generation
            $invoice['id'] = $invoice['invoice_id'] ?? $invoice['id'] ?? null;

            // Due date: if not stored, compute from invoice_date + INVOICE_DUE_DAYS
            if (empty($invoice['due_date'])) {
                $due_days = $this->Billing_model->get_config('INVOICE_DUE_DAYS', 30);
                if (!empty($invoice['invoice_date'])) {
                    $invoice['due_date'] = date('Y-m-d', strtotime($invoice['invoice_date'] . " +{$due_days} days"));
                } else {
                    $invoice['due_date'] = null;
                }
            }

            // Totals: provide friendly keys used in view
            $invoice['subtotal'] = $invoice['subtotal_amount'] ?? $invoice['subtotal'] ?? 0;
            $invoice['tax_amount'] = $invoice['gst_amount'] ?? $invoice['tax_amount'] ?? 0;
            $invoice['tax_percentage'] = $invoice['gst_rate'] ?? $invoice['tax_percentage'] ?? 0;
            $invoice['total_amount'] = $invoice['total_amount'] ?? $invoice['total_amount'] ?? 0;
            $invoice['paid_amount'] = $invoice['amount_paid'] ?? $invoice['paid_amount'] ?? 0;
            $invoice['balance_amount'] = $invoice['balance_due'] ?? $invoice['balance_amount'] ?? ($invoice['total_amount'] - $invoice['paid_amount']);

            // Patient fields - fallback to joined names
            if (empty($invoice['patient_name'])) {
                $first = $invoice['patient_first_name'] ?? '';
                $last = $invoice['patient_last_name'] ?? '';
                $invoice['patient_name'] = trim($first . ' ' . $last) ?: ($invoice['patient_uhid'] ?? 'N/A');
            }
            $invoice['patient_id'] = $invoice['opd_no'] ?? $invoice['ipd_no'] ?? $invoice['patient_id'] ?? null;

            // Ensure items have consistent fields for the view
            if (!empty($invoice['items']) && is_array($invoice['items'])) {
                foreach ($invoice['items'] as &$item) {
                    $item['total_amount'] = $item['line_total'] ?? $item['item_amount'] ?? $item['total_amount'] ?? 0;
                    $item['unit_price'] = $item['unit_price'] ?? $item['unit_price'] ?? 0;
                    $item['quantity'] = $item['quantity'] ?? 0;
                    $item['discount_amount'] = $item['discount_amount'] ?? 0;
                    $item['discount_percentage'] = $item['discount_percentage'] ?? 0;
                    $item['description'] = $item['description'] ?? '';
                }
                unset($item);
            }

            // Pass payments separately for the view
            $payments = $invoice['payments'] ?? [];

            $data['invoice'] = $invoice;
            $data['payments'] = $payments;
            $data['can_edit'] = in_array($invoice['invoice_status'], ['DRAFT']);
            $data['can_finalize'] = $invoice['invoice_status'] == 'DRAFT' && !empty($invoice['items']);
            $college = $this->get_college_details();
            $data['hospital_name'] = $college['college_name'] ?? 'Hospital Name';
            $data['hospital_address'] = $college['place'] ?? 'Hospital Address';
            $data['hospital_logo'] = $college['logo'] ?? '';

            $this->scripts_include->includePlugins(array('datatables'), 'js');
            $this->scripts_include->includePlugins(array('datatables'), 'css');

            $this->layout->data = $data;
            $this->layout->render();
        } catch (Exception $e) {
            log_message('error', 'Error viewing invoice: ' . $e->getMessage());
            redirect('billing');
        }
    }

    /**
     * List all invoices
     */
    public function invoices() {
        try {
            $filters = [];
            $data['filter_applied'] = false;
            $allowed_invoice_statuses = ['DRAFT', 'ISSUED', 'PARTIALLY_PAID', 'PAID', 'CANCELLED', 'CREDITED'];
            $allowed_payment_statuses = ['UNPAID', 'PARTIAL', 'PAID'];
            
            $invoice_status = strtoupper(trim((string) $this->input->get('invoice_status', true)));
            $payment_status = strtoupper(trim((string) $this->input->get('payment_status', true)));

            if ($invoice_status !== '' && in_array($invoice_status, $allowed_invoice_statuses, true)) {
                $filters['invoice_status'] = $invoice_status;
                $data['filter_applied'] = true;
            }
            
            if ($payment_status !== '' && in_array($payment_status, $allowed_payment_statuses, true)) {
                $filters['payment_status'] = $payment_status;
                $data['filter_applied'] = true;
            }
            
            $query = $this->db->select('*')
                             ->from('billing_invoices');
            
            if (!empty($filters)) {
                foreach ($filters as $field => $value) {
                    $query->where($field, $value);
                }
            }
            
            $data['invoices'] = $query->order_by('invoice_date', 'DESC')
                                     ->limit(100)
                                     ->get()
                                     ->result_array();
            
            $data['statuses'] = $allowed_invoice_statuses;
            
            $this->scripts_include->includePlugins(array('datatables'), 'js');
            $this->scripts_include->includePlugins(array('datatables'), 'css');
            
            $this->layout->data = $data;
            $this->layout->render();
        } catch (Exception $e) {
            log_message('error', 'Error listing invoices: ' . $e->getMessage());
            redirect('billing');
        }
    }

    /**
     * Process payment
     */
    public function payment($invoice_id) {
        try {
            $invoice = $this->Billing_model->get_invoice_details($invoice_id);
            
            if (!$invoice) {
                $this->session->set_flashdata('error', 'Invoice not found');
                redirect('billing');
            }
            
            if ($this->input->post()) {
                $this->form_validation->set_rules('payment_amount', 'Payment Amount', 'required|numeric|greater_than[0]');
                $this->form_validation->set_rules('payment_method_id', 'Payment Method', 'required|numeric');
                
                if ($this->form_validation->run() == FALSE) {
                    $data['errors'] = $this->form_validation->error_array();
                } else {
                    $payment_amount = $this->input->post('payment_amount');
                    $current_user_id = (int) ($this->rbac->is_login() ?: ($this->session->userdata('user_id') ?? 1));
                    if ($current_user_id <= 0) {
                        $current_user_id = 1;
                    }
                    
                    // Validate payment amount
                    if ($payment_amount > $invoice['balance_due']) {
                        $data['error'] = 'Payment amount cannot exceed balance due of ' . $invoice['balance_due'];
                    } else {
                        $payment_data = [
                            'invoice_id' => $invoice_id,
                            'payment_date' => date('Y-m-d'),
                            'payment_amount' => $payment_amount,
                            'payment_method_id' => $this->input->post('payment_method_id'),
                            'reference_number' => $this->input->post('reference_number'),
                            'bank_name' => $this->input->post('bank_name'),
                            'cheque_number' => $this->input->post('cheque_number'),
                            'remarks' => $this->input->post('remarks'),
                            'created_by' => $current_user_id
                        ];
                        
                        $payment_id = $this->Payment_model->record_payment($payment_data);
                        
                        if ($payment_id) {
                            $this->session->set_flashdata('success', 'Payment recorded successfully!');
                            redirect('billing/view_invoice/' . $invoice_id);
                        } else {
                            $data['error'] = 'Failed to record payment';
                        }
                    }
                }
            }
            
            // Normalize invoice keys for payment view
            $invoice['id'] = $invoice['invoice_id'] ?? $invoice['id'] ?? null;
            $invoice['subtotal'] = $invoice['subtotal_amount'] ?? $invoice['subtotal'] ?? 0;
            $invoice['tax_amount'] = $invoice['gst_amount'] ?? $invoice['tax_amount'] ?? 0;
            $invoice['tax_percentage'] = $invoice['gst_rate'] ?? $invoice['tax_percentage'] ?? 0;
            $invoice['total_amount'] = $invoice['total_amount'] ?? $invoice['total_amount'] ?? 0;
            $invoice['paid_amount'] = $invoice['amount_paid'] ?? $invoice['paid_amount'] ?? 0;
            $invoice['balance_amount'] = $invoice['balance_due'] ?? $invoice['balance_amount'] ?? ($invoice['total_amount'] - $invoice['paid_amount']);

            if (empty($invoice['patient_name'])) {
                $first = $invoice['patient_first_name'] ?? '';
                $last = $invoice['patient_last_name'] ?? '';
                $invoice['patient_name'] = trim($first . ' ' . $last) ?: ($invoice['patient_uhid'] ?? 'N/A');
            }

            $data['invoice'] = $invoice;
            $data['payment_methods'] = $this->Payment_model->get_payment_methods();

            $this->layout->data = $data;
            $this->layout->render();
        } catch (Exception $e) {
            log_message('error', 'Error processing payment: ' . $e->getMessage());
            redirect('billing');
        }
    }

    /**
     * View patient billing history
     */
    public function patient_history($patient_id, $type = 'OPD') {
        try {
            $invoices = $this->Billing_model->get_patient_invoices($patient_id, $type);
            
            $data['patient_id'] = $patient_id;
            $data['type'] = $type;
            $data['invoices'] = $invoices;
            $data['total_invoices'] = count($invoices);
            
            if (!empty($invoices)) {
                $total = array_sum(array_column($invoices, 'total_amount'));
                $paid = array_sum(array_column($invoices, 'amount_paid'));
                
                $data['total_amount'] = $total;
                $data['total_paid'] = $paid;
                $data['total_pending'] = $total - $paid;
            }
            
            $this->scripts_include->includePlugins(array('datatables'), 'js');
            $this->scripts_include->includePlugins(array('datatables'), 'css');
            
            $this->layout->data = $data;
            $this->layout->render();
        } catch (Exception $e) {
            log_message('error', 'Error getting patient history: ' . $e->getMessage());
            redirect('billing');
        }
    }

    /**
     * API endpoint to add line item to invoice
     */
    public function add_invoice_item() {
        header('Content-Type: application/json');
        
        try {
            if (!$this->input->post()) {
                echo json_encode(['success' => false, 'message' => 'Invalid request']);
                return;
            }
            
            $invoice_id = $this->input->post('invoice_id');
            $service_id = $this->input->post('service_id');
            $quantity = $this->input->post('quantity', 1);
            
            // Validate inputs
            if (!$invoice_id || !$service_id) {
                echo json_encode(['success' => false, 'message' => 'Missing required fields']);
                return;
            }
            
            // Get service details
            $service = $this->Service_model->get_service_pricing($service_id);
            
            if (!$service) {
                echo json_encode(['success' => false, 'message' => 'Service not found']);
                return;
            }
            
            // Calculate item total
            $item_amount = $service['unit_price'] * $quantity;
            $gst_amount = $service['calculated_gst'] * $quantity;
            $line_total = $item_amount + $gst_amount;
            
            $item_data = [
                'service_id' => $service_id,
                'service_code' => $service['service_code'],
                'service_name' => $service['service_name'],
                'quantity' => $quantity,
                'unit_price' => $service['unit_price'],
                'item_amount' => $item_amount,
                'gst_applicable' => $service['gst_applicable'],
                'gst_rate' => $service['gst_rate'],
                'gst_amount' => $gst_amount,
                'line_total' => $line_total
            ];
            
            $item_id = $this->Billing_model->add_invoice_item($invoice_id, $item_data);
            
            if ($item_id) {
                // Recalculate invoice totals
                $totals = $this->Billing_model->calculate_invoice_totals($invoice_id);
                $this->Billing_model->update_invoice_totals($invoice_id, $totals);
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Item added successfully',
                    'item_id' => $item_id,
                    'line_total' => $line_total
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to add item']);
            }
        } catch (Exception $e) {
            log_message('error', 'Error adding invoice item: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error adding item']);
        }
    }

    /**
     * AJAX endpoint to get services by category
     */
    public function get_services_by_category() {
        header('Content-Type: application/json');
        
        try {
            $category_id = $this->input->post('category_id');
            
            if (!$category_id) {
                echo json_encode(['success' => false, 'message' => 'Category ID is required']);
                return;
            }
            
            $services = $this->Service_model->get_services_by_category($category_id);
            
            echo json_encode([
                'success' => true,
                'services' => $services
            ]);
        } catch (Exception $e) {
            log_message('error', 'Error fetching services: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error fetching services']);
        }
    }

    private function ensure_authenticated()
    {
        if (isset($this->rbac) && !$this->rbac->is_login()) {
            if ($this->input->is_ajax_request()) {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['success' => false, 'message' => 'Authentication required']));
                exit;
            }

            redirect('login');
            exit;
        }
    }

    private function load_billing_settings()
    {
        $config = [];
        $path = APPPATH . 'modules/billing/config.php';
        if (file_exists($path)) {
            include $path;
        }

        return isset($config['billing']) && is_array($config['billing']) ? $config['billing'] : [];
    }

    /**
     * Finalize invoice (Issue)
     */
    public function finalize_invoice($invoice_id) {
        try {
            if ($this->Billing_model->update_invoice_status($invoice_id, 'ISSUED')) {
                $this->session->set_flashdata('success', 'Invoice issued successfully!');
            } else {
                $this->session->set_flashdata('error', 'Failed to issue invoice');
            }
        } catch (Exception $e) {
            log_message('error', 'Error finalizing invoice: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Error issuing invoice');
        }
        
        redirect('billing/view_invoice/' . $invoice_id);
    }

    /**
     * Cancel invoice
     */
    public function cancel_invoice($invoice_id) {
        try {
            $reason = $this->input->post('reason', 'User requested cancellation');
            
            if ($this->Billing_model->cancel_invoice($invoice_id, $reason)) {
                $this->session->set_flashdata('success', 'Invoice cancelled successfully!');
            } else {
                $this->session->set_flashdata('error', 'Failed to cancel invoice');
            }
        } catch (Exception $e) {
            log_message('error', 'Error cancelling invoice: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Error cancelling invoice');
        }
        
        redirect('billing/view_invoice/' . $invoice_id);
    }

    /**
     * Print invoice
     */
    public function print_invoice($invoice_id) {
        try {
            $invoice = $this->Billing_model->get_invoice_details($invoice_id);
            
            if (!$invoice) {
                $this->session->set_flashdata('error', 'Invoice not found');
                redirect('billing');
            }
            
            // Normalize invoice keys for print view
            $invoice['id'] = $invoice['invoice_id'] ?? $invoice['id'] ?? null;
            
            if (empty($invoice['patient_name'])) {
                $first = $invoice['patient_first_name'] ?? '';
                $last = $invoice['patient_last_name'] ?? '';
                $invoice['patient_name'] = trim($first . ' ' . $last) ?: ($invoice['patient_uhid'] ?? 'N/A');
            }
            
            // Ensure items have consistent fields
            if (!empty($invoice['items']) && is_array($invoice['items'])) {
                foreach ($invoice['items'] as &$item) {
                    $item['line_total'] = $item['line_total'] ?? $item['item_amount'] ?? $item['total_amount'] ?? 0;
                    $item['unit_price'] = $item['unit_price'] ?? 0;
                    $item['quantity'] = $item['quantity'] ?? 0;
                    $item['discount_amount'] = $item['discount_amount'] ?? 0;
                    $item['discount_percentage'] = $item['discount_percentage'] ?? 0;
                    $item['gst_rate'] = $item['gst_rate'] ?? 0;
                    $item['description'] = $item['description'] ?? '';
                }
                unset($item);
            }
            
            $data['invoice'] = $invoice;
            $college = $this->get_college_details();
            $data['hospital_name'] = $college['college_name'] ?? 'Hospital Name';
            $data['hospital_address'] = $college['place'] ?? 'Hospital Address';
            $data['hospital_logo'] = $college['logo'] ?? '';
            
            // Load print view directly without layout
            $this->load->view('billing/invoices/print_invoice', $data);
        } catch (Exception $e) {
            log_message('error', 'Error printing invoice: ' . $e->getMessage());
            redirect('billing');
        }
    }

    /**
     * Get college details from config table for headers/print
     *
     * @return array
     */
    private function get_college_details() {
        $row = $this->db->select('college_name, place, logo')
                        ->where("TRIM(IFNULL(college_name, '')) <> ''", null, false)
                        ->limit(1)
                        ->get('config')
                        ->row_array();

        if (empty($row)) {
            $row = $this->db->select('college_name, place, logo')
                            ->limit(1)
                            ->get('config')
                            ->row_array();
        }

        if (is_array($row)) {
            $row['college_name'] = trim((string) ($row['college_name'] ?? ''));
            $row['place'] = trim((string) ($row['place'] ?? ''));
            $row['logo'] = trim((string) ($row['logo'] ?? ''));
        }

        return is_array($row) ? $row : [];
    }
}
?>
