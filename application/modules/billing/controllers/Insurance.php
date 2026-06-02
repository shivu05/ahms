<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Insurance Controller - Insurance Claims & Pre-authorizations Management
 */
class Insurance extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        $this->ensure_authenticated();
        $this->load->model('billing/Insurance_model');
        $this->load->model('billing/Billing_model');
        $this->load->library('form_validation');
        $this->load->helper(['form', 'url', 'security']);
        $this->layout->title = "Insurance Management";
    }

    /**
     * Insurance Dashboard
     */
    public function index() {
        try {
            if (!$this->insurance_tables_ready(['billing_insurance_claims', 'billing_insurance_policies', 'billing_insurance_companies', 'billing_insurance_preauth'])) {
                $this->layout->data = [
                    'setup_required' => true,
                    'message' => 'Insurance tables are not installed. Run the billing module database setup first.'
                ];
                $this->layout->render();
                return;
            }

            // Claims summary
            $data['pending_claims'] = $this->db->where('claim_status', 'UNDER_PROCESS')
                                               ->count_all_results('billing_insurance_claims');
            
            $data['approved_claims'] = $this->db->where('claim_status', 'APPROVED')
                                                ->count_all_results('billing_insurance_claims');
            
            $data['rejected_claims'] = $this->db->where('claim_status', 'REJECTED')
                                                ->count_all_results('billing_insurance_claims');
            
            // Pre-auth summary
            $data['pending_preauths'] = $this->db->where('preauth_status', 'SUBMITTED')
                                                 ->count_all_results('billing_insurance_preauth');
            
            $data['approved_preauths'] = $this->db->where('preauth_status', 'APPROVED')
                                                  ->count_all_results('billing_insurance_preauth');
            
            // Claims report
            $data['claims_report'] = $this->Insurance_model->get_claims_report([
                'from_date' => date('Y-m-d', strtotime('first day of this month')),
                'to_date' => date('Y-m-d', strtotime('last day of this month'))
            ]);
            
            // Recent claims
            $data['recent_claims'] = $this->db->select('bic.claim_number, bic.claim_date, bip.policy_number, bic.claimed_amount, bic.claim_status')
                                              ->from('billing_insurance_claims bic')
                                              ->join('billing_insurance_policies bip', 'bic.policy_id = bip.policy_id', 'left')
                                              ->limit(5)
                                              ->order_by('bic.claim_date', 'DESC')
                                              ->get()
                                              ->result_array();
            
            $this->scripts_include->includePlugins(array('chart_js'), 'js');
            $this->layout->data = $data;
            $this->layout->render();
        } catch (Exception $e) {
            log_message('error', 'Error loading insurance dashboard: ' . $e->getMessage());
            redirect('billing');
        }
    }

    /**
     * List Insurance Companies
     */
    public function companies() {
        try {
            if (!$this->insurance_tables_ready(['billing_insurance_companies'])) {
                $this->layout->data = [
                    'companies' => [],
                    'setup_required' => true,
                    'message' => 'Insurance company table is not installed. Run the billing module database setup first.'
                ];
                $this->layout->render();
                return;
            }

            $data['companies'] = $this->Insurance_model->get_companies();
            
            $this->scripts_include->includePlugins(array('datatables'), 'js');
            $this->scripts_include->includePlugins(array('datatables'), 'css');
            
            $this->layout->data = $data;
            $this->layout->render();
        } catch (Exception $e) {
            log_message('error', 'Error loading companies: ' . $e->getMessage());
            redirect('billing/insurance');
        }
    }

    /**
     * Create Insurance Company
     */
    public function add_company() {
        try {
            if (!$this->insurance_tables_ready(['billing_insurance_companies'])) {
                $this->session->set_flashdata('error', 'Insurance company table is not installed. Run the billing module database setup first.');
                redirect('billing/insurance/policies');
            }

            if ($this->input->post()) {
                $this->form_validation->set_rules('company_code', 'Company Code', 'required|alpha_numeric');
                $this->form_validation->set_rules('company_name', 'Company Name', 'required');
                $this->form_validation->set_rules('email', 'Email', 'valid_email');
                
                if ($this->form_validation->run() == FALSE) {
                    $data['errors'] = $this->form_validation->error_array();
                } else {
                    $company_data = [
                        'company_code' => strtoupper(trim((string) $this->input->post('company_code', true))),
                        'company_name' => trim((string) $this->input->post('company_name', true)),
                        'contact_person' => trim((string) $this->input->post('contact_person', true)),
                        'email' => trim((string) $this->input->post('email', true)),
                        'phone' => trim((string) $this->input->post('phone', true)),
                        'address' => trim((string) $this->input->post('address', true)),
                        'city' => trim((string) $this->input->post('city', true)),
                        'state' => trim((string) $this->input->post('state', true)),
                        'pincode' => trim((string) $this->input->post('pincode', true)),
                        'website' => trim((string) $this->input->post('website', true)),
                        'claim_contact_email' => trim((string) $this->input->post('claim_contact_email', true)),
                        'claim_contact_phone' => trim((string) $this->input->post('claim_contact_phone', true)),
                        'is_active' => 1,
                        'created_by' => $this->session->userdata('user_id') ?? 1
                    ];
                    
                    $company_id = $this->Insurance_model->create_company($company_data);
                    
                    if ($company_id) {
                        $this->session->set_flashdata('success', 'Insurance company added successfully!');
                        redirect('billing/insurance/companies');
                    } else {
                        $data['error'] = 'Failed to add insurance company';
                    }
                }
            }
            
            $this->layout->data = $data ?? [];
            $this->layout->render();
        } catch (Exception $e) {
            log_message('error', 'Error adding company: ' . $e->getMessage());
            redirect('billing/insurance/companies');
        }
    }

    /**
     * List Insurance Policies
     */
    public function policies() {
        try {
            if (!$this->insurance_tables_ready(['billing_insurance_policies', 'billing_insurance_companies'])) {
                $this->layout->data = [
                    'setup_required' => true,
                    'message' => 'Insurance policy tables are not installed. Run the billing module database setup first.',
                    'policies' => [],
                    'companies' => [],
                    'filters' => ['company_id' => '', 'search' => '']
                ];
                $this->layout->render();
                return;
            }

            $filters = [];
            $filters['company_id'] = '';
            $filters['search'] = '';
            
            $company_id = (int) $this->input->get('company_id', true);
            if ($company_id > 0) {
                $filters['company_id'] = $company_id;
            }
            
            $search = trim((string) $this->input->get('search', true));
            if ($search !== '') {
                $filters['search'] = $search;
            }
            
            $data['policies'] = $this->Insurance_model->get_policies($filters);
            $data['companies'] = $this->Insurance_model->get_companies();
            $data['filters'] = $filters;
            
            $this->scripts_include->includePlugins(array('datatables'), 'js');
            $this->scripts_include->includePlugins(array('datatables'), 'css');
            
            $this->layout->data = $data;
            $this->layout->render();
        } catch (Exception $e) {
            log_message('error', 'Error loading policies: ' . $e->getMessage());
            redirect('billing/insurance');
        }
    }

    /**
     * Add/Edit Insurance Policy
     */
    public function edit_policy($policy_id = null) {
        try {
            if (!$this->insurance_tables_ready(['billing_insurance_policies', 'billing_insurance_companies'])) {
                $this->session->set_flashdata('error', 'Insurance policy tables are not installed. Run the billing module database setup first.');
                redirect('billing/insurance/policies');
            }

            if ($policy_id) {
                $policy = $this->Insurance_model->get_policy($policy_id);
                if (!$policy) {
                    redirect('billing/insurance/policies');
                }
            } else {
                $policy = [];
            }
            
            if ($this->input->post()) {
                $this->form_validation->set_rules('policy_number', 'Policy Number', 'required');
                $this->form_validation->set_rules('company_id', 'Insurance Company', 'required|numeric');
                $this->form_validation->set_rules('coverage_limit', 'Coverage Limit', 'required|numeric|greater_than_equal_to[0]');
                $this->form_validation->set_rules('deductible_amount', 'Deductible Amount', 'numeric|greater_than_equal_to[0]');
                $this->form_validation->set_rules('co_payment_percentage', 'Co-pay Percentage', 'numeric|greater_than_equal_to[0]|less_than_equal_to[100]');
                
                if ($this->form_validation->run() == FALSE) {
                    $data['errors'] = $this->form_validation->error_array();
                } else {
                    $policy_data = [
                        'policy_number' => trim((string) $this->input->post('policy_number', true)),
                        'company_id' => (int) $this->input->post('company_id', true),
                        'policy_name' => trim((string) $this->input->post('policy_name', true)),
                        'policy_type' => strtoupper(trim((string) $this->input->post('policy_type', true))),
                        'policy_holder_name' => trim((string) $this->input->post('policy_holder_name', true)),
                        'policy_holder_contact' => trim((string) $this->input->post('policy_holder_contact', true)),
                        'policy_start_date' => $this->normalize_date($this->input->post('policy_start_date', true)),
                        'policy_end_date' => $this->normalize_date($this->input->post('policy_end_date', true)),
                        'coverage_limit' => round((float) $this->input->post('coverage_limit', true), 2),
                        'deductible_amount' => round((float) $this->input->post('deductible_amount', true), 2),
                        'co_payment_percentage' => round((float) $this->input->post('co_payment_percentage', true), 2),
                        'requires_pre_approval' => (int) $this->input->post('requires_pre_approval', true) ? 1 : 0,
                        'is_active' => (int) $this->input->post('is_active', true) ? 1 : 0
                    ];

                    if (!in_array($policy_data['policy_type'], ['INDIVIDUAL', 'FAMILY', 'GROUP', 'CORPORATE'], true)) {
                        $policy_data['policy_type'] = 'INDIVIDUAL';
                    }
                    
                    if ($policy_id) {
                        $result = $this->Insurance_model->update_policy($policy_id, $policy_data);
                        $message = 'Policy updated successfully!';
                    } else {
                        $policy_id = $this->Insurance_model->create_policy($policy_data);
                        $result = $policy_id ? true : false;
                        $message = 'Policy created successfully!';
                    }
                    
                    if ($result) {
                        $this->session->set_flashdata('success', $message);
                        redirect('billing/insurance/policies');
                    } else {
                        $data['error'] = 'Failed to save policy';
                    }
                }
            }
            
            $data['policy'] = $policy;
            $data['companies'] = $this->Insurance_model->get_companies();
            $data['policy_types'] = ['INDIVIDUAL', 'FAMILY', 'GROUP', 'CORPORATE'];
            
            $this->layout->data = $data;
            $this->layout->render();
        } catch (Exception $e) {
            log_message('error', 'Error editing policy: ' . $e->getMessage());
            redirect('billing/insurance/policies');
        }
    }

    /**
     * Request Pre-authorization
     */
    public function request_preauth($invoice_id = null) {
        try {
            if ($this->input->post()) {
                $this->form_validation->set_rules('policy_id', 'Insurance Policy', 'required|numeric');
                $this->form_validation->set_rules('estimated_cost', 'Estimated Cost', 'required|numeric');
                
                if ($this->form_validation->run() == FALSE) {
                    $data['errors'] = $this->form_validation->error_array();
                } else {
                    $preauth_data = [
                        'policy_id' => $this->input->post('policy_id'),
                        'estimated_cost' => $this->input->post('estimated_cost'),
                        'treatment_description' => $this->input->post('treatment_description'),
                        'patient_name' => $this->input->post('patient_name'),
                        'patient_contact' => $this->input->post('patient_contact'),
                        'preauth_status' => 'SUBMITTED'
                    ];
                    
                    if ($invoice_id) {
                        $invoice = $this->Billing_model->get_invoice_details($invoice_id);
                        if ($invoice) {
                            if ($invoice['opd_no']) $preauth_data['opd_no'] = $invoice['opd_no'];
                            if ($invoice['ipd_no']) $preauth_data['ipd_no'] = $invoice['ipd_no'];
                        }
                    }
                    
                    $preauth_id = $this->Insurance_model->request_preauth($preauth_data);
                    
                    if ($preauth_id) {
                        $this->session->set_flashdata('success', 'Pre-authorization request submitted successfully!');
                        redirect('billing/insurance/view_preauth/' . $preauth_id);
                    } else {
                        $data['error'] = 'Failed to submit pre-authorization request';
                    }
                }
            }
            
            $data['invoice_id'] = $invoice_id;
            $data['policies'] = $this->Insurance_model->get_policies();
            
            $this->layout->data = $data;
            $this->layout->render();
        } catch (Exception $e) {
            log_message('error', 'Error requesting preauth: ' . $e->getMessage());
            redirect('billing/insurance');
        }
    }

    /**
     * View Pre-authorization
     */
    public function view_preauth($preauth_id) {
        try {
            $preauth = $this->Insurance_model->get_preauth($preauth_id);
            
            if (!$preauth) {
                redirect('billing/insurance');
            }
            
            $data['preauth'] = $preauth;
            $data['can_approve'] = $preauth['preauth_status'] == 'SUBMITTED';
            $data['can_reject'] = $preauth['preauth_status'] == 'SUBMITTED';
            
            $this->layout->data = $data;
            $this->layout->render();
        } catch (Exception $e) {
            log_message('error', 'Error viewing preauth: ' . $e->getMessage());
            redirect('billing/insurance');
        }
    }

    /**
     * List Insurance Claims
     */
    public function claims() {
        try {
            if (!$this->insurance_tables_ready(['billing_insurance_claims', 'billing_insurance_policies', 'billing_insurance_companies', 'billing_invoices'])) {
                $this->layout->data = [
                    'setup_required' => true,
                    'message' => 'Insurance claim tables are not installed. Run the billing module database setup first.',
                    'claims' => [],
                    'status_filter' => '',
                    'statuses' => []
                ];
                $this->layout->render();
                return;
            }

            $allowed_statuses = ['DRAFT', 'SUBMITTED', 'ACKNOWLEDGED', 'UNDER_PROCESS', 'APPROVED', 'PARTIALLY_APPROVED', 'REJECTED', 'PAID', 'CLOSED'];
            $status_filter = strtoupper(trim((string) $this->input->get('status', true)));
            
            $query = $this->db->select('bic.*, bip.policy_number, bic2.company_name, bi.invoice_number')
                             ->from('billing_insurance_claims bic')
                             ->join('billing_insurance_policies bip', 'bic.policy_id = bip.policy_id', 'left')
                             ->join('billing_insurance_companies bic2', 'bip.company_id = bic2.company_id', 'left')
                             ->join('billing_invoices bi', 'bic.invoice_id = bi.invoice_id', 'left');
            
            if ($status_filter !== '' && in_array($status_filter, $allowed_statuses, true)) {
                $query->where('bic.claim_status', $status_filter);
            } else {
                $status_filter = '';
            }
            
            $data['claims'] = $query->order_by('bic.claim_date', 'DESC')
                                   ->limit(100)
                                   ->get()
                                   ->result_array();
            
            $data['status_filter'] = $status_filter;
            $data['statuses'] = $allowed_statuses;
            
            $this->scripts_include->includePlugins(array('datatables'), 'js');
            $this->scripts_include->includePlugins(array('datatables'), 'css');
            
            $this->layout->data = $data;
            $this->layout->render();
        } catch (Exception $e) {
            log_message('error', 'Error loading claims: ' . $e->getMessage());
            redirect('billing/insurance');
        }
    }

    /**
     * Create Insurance Claim
     */
    public function create_claim($invoice_id) {
        try {
            if (!$this->insurance_tables_ready(['billing_insurance_claims', 'billing_insurance_policies', 'billing_insurance_companies', 'billing_invoices'])) {
                $this->session->set_flashdata('error', 'Insurance claim tables are not installed. Run the billing module database setup first.');
                redirect('billing');
            }

            $invoice = $this->Billing_model->get_invoice_details($invoice_id);
            
            if (!$invoice) {
                $this->session->set_flashdata('error', 'Invoice not found');
                redirect('billing');
            }
            
            if ($this->input->post()) {
                $this->form_validation->set_rules('policy_id', 'Insurance Policy', 'required|numeric');
                
                if ($this->form_validation->run() == FALSE) {
                    $data['errors'] = $this->form_validation->error_array();
                } else {
                    $policy = $this->Insurance_model->get_policy($this->input->post('policy_id'));
                    
                    // Calculate settlement
                    $settlement = $this->Insurance_model->calculate_claim_settlement($invoice_id, $this->input->post('policy_id'));
                    
                    $claim_data = [
                        'invoice_id' => $invoice_id,
                        'policy_id' => $this->input->post('policy_id'),
                        'invoice_amount' => $invoice['total_amount'],
                        'claimed_amount' => $settlement['approved_amount'],
                        'deductible_amount' => $settlement['deductible'],
                        'co_payment_amount' => $settlement['copay_amount'],
                        'claim_status' => 'DRAFT'
                    ];
                    
                    $claim_id = $this->Insurance_model->create_claim($claim_data);
                    
                    if ($claim_id) {
                        $this->session->set_flashdata('success', 'Insurance claim created successfully!');
                        redirect('billing/insurance/view_claim/' . $claim_id);
                    } else {
                        $data['error'] = 'Failed to create claim';
                    }
                }
            }
            
            $data['invoice'] = $invoice;
            $data['policies'] = $this->Insurance_model->get_policies();
            
            $this->layout->data = $data;
            $this->layout->render();
        } catch (Exception $e) {
            log_message('error', 'Error creating claim: ' . $e->getMessage());
            redirect('billing');
        }
    }

    /**
     * View Claim Details
     */
    public function view_claim($claim_id) {
        try {
            $claim = $this->Insurance_model->get_claim($claim_id);
            
            if (!$claim) {
                redirect('billing/insurance/claims');
            }
            
            $data['claim'] = $claim;
            $data['can_submit'] = $claim['claim_status'] == 'DRAFT';
            $data['can_approve'] = $claim['claim_status'] == 'UNDER_PROCESS';
            $data['can_reject'] = in_array($claim['claim_status'], ['SUBMITTED', 'UNDER_PROCESS']);
            
            $this->scripts_include->includePlugins(array('datatables'), 'js');
            $this->scripts_include->includePlugins(array('datatables'), 'css');
            
            $this->layout->data = $data;
            $this->layout->render();
        } catch (Exception $e) {
            log_message('error', 'Error viewing claim: ' . $e->getMessage());
            redirect('billing/insurance/claims');
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

    private function insurance_tables_ready(array $tables)
    {
        foreach ($tables as $table) {
            if (!$this->db->table_exists($table)) {
                return false;
            }
        }

        return true;
    }

    private function normalize_date($value)
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        $date = DateTime::createFromFormat('Y-m-d', $value);
        return $date ? $date->format('Y-m-d') : null;
    }

    /**
     * Submit Claim to Insurance
     */
    public function submit_claim($claim_id) {
        header('Content-Type: application/json');
        
        try {
            $remarks = $this->input->post('remarks');
            
            if ($this->Insurance_model->submit_claim($claim_id, $remarks)) {
                echo json_encode(['success' => true, 'message' => 'Claim submitted successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to submit claim']);
            }
        } catch (Exception $e) {
            log_message('error', 'Error submitting claim: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error submitting claim']);
        }
    }

    /**
     * Approve Claim (Insurance approval)
     */
    public function approve_claim($claim_id) {
        header('Content-Type: application/json');
        
        try {
            $approved_amount = $this->input->post('approved_amount');
            $insurance_ref = $this->input->post('insurance_reference');
            
            if ($this->Insurance_model->approve_claim($claim_id, $approved_amount, $insurance_ref)) {
                echo json_encode(['success' => true, 'message' => 'Claim approved successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to approve claim']);
            }
        } catch (Exception $e) {
            log_message('error', 'Error approving claim: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error approving claim']);
        }
    }
}
?>
