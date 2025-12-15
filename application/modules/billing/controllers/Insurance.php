<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Insurance Controller - Insurance Claims & Pre-authorizations Management
 */
class Insurance extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('billing/Insurance_model');
        $this->load->model('billing/Billing_model');
        $this->load->library('form_validation');
        $this->layout->title = "Insurance Management";
    }

    /**
     * Insurance Dashboard
     */
    public function index() {
        try {
            // Claims summary
            $data['pending_claims'] = $this->db->where('claim_status', 'UNDER_PROCESS')
                                               ->count_all_results('billing_insurance_claims');
            
            $data['approved_claims'] = $this->db->where('claim_status', 'APPROVED')
                                                ->count_all_results('billing_insurance_claims');
            
            $data['rejected_claims'] = $this->db->where('claim_status', 'REJECTED')
                                                ->count_all_results('billing_insurance_claims');
            
            // Pre-auth summary
            $data['pending_preatuhs'] = $this->db->where('preauth_status', 'SUBMITTED')
                                                 ->count_all_results('billing_insurance_preauth');
            
            $data['approved_preauths'] = $this->db->where('preauth_status', 'APPROVED')
                                                  ->count_all_results('billing_insurance_preauth');
            
            // Claims report
            $data['claims_report'] = $this->Insurance_model->get_claims_report([
                'from_date' => date('Y-m-d', strtotime('first day of this month')),
                'to_date' => date('Y-m-d', strtotime('last day of this month'))
            ]);
            
            // Recent claims
            $data['recent_claims'] = $this->db->select('claim_number, claim_date, policy_number, claimed_amount, claim_status')
                                              ->from('billing_insurance_claims')
                                              ->limit(5)
                                              ->order_by('claim_date', 'DESC')
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
            if ($this->input->post()) {
                $this->form_validation->set_rules('company_code', 'Company Code', 'required|alpha_numeric');
                $this->form_validation->set_rules('company_name', 'Company Name', 'required');
                $this->form_validation->set_rules('email', 'Email', 'valid_email');
                
                if ($this->form_validation->run() == FALSE) {
                    $data['errors'] = $this->form_validation->error_array();
                } else {
                    $company_data = [
                        'company_code' => $this->input->post('company_code'),
                        'company_name' => $this->input->post('company_name'),
                        'contact_person' => $this->input->post('contact_person'),
                        'email' => $this->input->post('email'),
                        'phone' => $this->input->post('phone'),
                        'address' => $this->input->post('address'),
                        'city' => $this->input->post('city'),
                        'state' => $this->input->post('state'),
                        'pincode' => $this->input->post('pincode'),
                        'website' => $this->input->post('website'),
                        'claim_contact_email' => $this->input->post('claim_contact_email'),
                        'claim_contact_phone' => $this->input->post('claim_contact_phone'),
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
            $filters = [];
            
            if ($this->input->get('company_id')) {
                $filters['company_id'] = $this->input->get('company_id');
            }
            
            if ($this->input->get('search')) {
                $filters['search'] = $this->input->get('search');
            }
            
            $data['policies'] = $this->Insurance_model->get_policies($filters);
            $data['companies'] = $this->Insurance_model->get_companies();
            
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
                $this->form_validation->set_rules('coverage_limit', 'Coverage Limit', 'required|numeric');
                
                if ($this->form_validation->run() == FALSE) {
                    $data['errors'] = $this->form_validation->error_array();
                } else {
                    $policy_data = [
                        'policy_number' => $this->input->post('policy_number'),
                        'company_id' => $this->input->post('company_id'),
                        'policy_name' => $this->input->post('policy_name'),
                        'policy_type' => $this->input->post('policy_type'),
                        'policy_holder_name' => $this->input->post('policy_holder_name'),
                        'policy_holder_contact' => $this->input->post('policy_holder_contact'),
                        'policy_start_date' => $this->input->post('policy_start_date'),
                        'policy_end_date' => $this->input->post('policy_end_date'),
                        'coverage_limit' => $this->input->post('coverage_limit'),
                        'deductible_amount' => $this->input->post('deductible_amount', 0),
                        'co_payment_percentage' => $this->input->post('co_payment_percentage', 0),
                        'requires_pre_approval' => $this->input->post('requires_pre_approval', 0),
                        'is_active' => 1
                    ];
                    
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
            $status_filter = $this->input->get('status');
            
            $query = $this->db->select('bic.*, bip.policy_number, bic2.company_name, bi.invoice_number')
                             ->from('billing_insurance_claims bic')
                             ->join('billing_insurance_policies bip', 'bic.policy_id = bip.policy_id', 'left')
                             ->join('billing_insurance_companies bic2', 'bip.company_id = bic2.company_id', 'left')
                             ->join('billing_invoices bi', 'bic.invoice_id = bi.invoice_id', 'left');
            
            if ($status_filter) {
                $query->where('bic.claim_status', $status_filter);
            }
            
            $data['claims'] = $query->order_by('bic.claim_date', 'DESC')
                                   ->limit(100)
                                   ->get()
                                   ->result_array();
            
            $data['status_filter'] = $status_filter;
            $data['statuses'] = ['DRAFT', 'SUBMITTED', 'UNDER_PROCESS', 'APPROVED', 'PARTIALLY_APPROVED', 'REJECTED', 'PAID'];
            
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
