<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Insurance Model - Handle Insurance Claims, Pre-authorizations & Policies
 */
class Insurance_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get all insurance companies
     * @return array Companies
     */
    public function get_companies() {
        return $this->db->where('is_active', 1)
                        ->order_by('company_name', 'ASC')
                        ->get('billing_insurance_companies')
                        ->result_array();
    }

    /**
     * Create insurance company
     * @param array $data
     * @return int Company ID
     */
    public function create_company($data) {
        $this->db->insert('billing_insurance_companies', $data);
        return $this->db->insert_id();
    }

    /**
     * Get insurance policies
     * @param array $filters
     * @return array Policies
     */
    public function get_policies($filters = []) {
        $query = $this->db->select('bp.*, bic.company_name')
                          ->from('billing_insurance_policies bp')
                          ->join('billing_insurance_companies bic', 'bp.company_id = bic.company_id')
                          ->where('bp.is_active', 1);
        
        if (!empty($filters['company_id'])) {
            $query->where('bp.company_id', $filters['company_id']);
        }
        
        if (!empty($filters['search'])) {
            $query->group_start()
                 ->like('bp.policy_number', $filters['search'])
                 ->or_like('bp.policy_holder_name', $filters['search'])
                 ->group_end();
        }
        
        return $query->order_by('bp.policy_number', 'ASC')
                    ->get()
                    ->result_array();
    }

    /**
     * Get policy details
     * @param int $policy_id
     * @return array Policy
     */
    public function get_policy($policy_id) {
        return $this->db->select('bp.*, bic.company_name, bic.company_code')
                        ->from('billing_insurance_policies bp')
                        ->join('billing_insurance_companies bic', 'bp.company_id = bic.company_id')
                        ->where('bp.policy_id', $policy_id)
                        ->get()
                        ->row_array();
    }

    /**
     * Create insurance policy
     * @param array $data
     * @return int Policy ID
     */
    public function create_policy($data) {
        $data['created_by'] = $this->session->userdata('user_id') ?? 1;
        $this->db->insert('billing_insurance_policies', $data);
        return $this->db->insert_id();
    }

    /**
     * Update policy
     * @param int $policy_id
     * @param array $data
     * @return bool
     */
    public function update_policy($policy_id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->where('policy_id', $policy_id)
                        ->update('billing_insurance_policies', $data);
    }

    /**
     * Request pre-authorization
     * @param array $preauth_data
     * @return int Pre-auth ID
     */
    public function request_preauth($preauth_data) {
        try {
            $preauth_data['preauth_number'] = $this->generate_preauth_number();
            $preauth_data['preauth_date'] = date('Y-m-d');
            $preauth_data['created_by'] = $this->session->userdata('user_id') ?? 1;
            
            $this->db->insert('billing_insurance_preauth', $preauth_data);
            return $this->db->insert_id();
        } catch (Exception $e) {
            log_message('error', 'Error creating preauth: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate pre-authorization number
     * @return string Preauth number
     */
    public function generate_preauth_number() {
        $prefix = 'PREAUTH';
        $last_preauth = $this->db->select('preauth_number')
                                 ->order_by('preauth_id', 'DESC')
                                 ->limit(1)
                                 ->get('billing_insurance_preauth')
                                 ->row();
        
        if ($last_preauth) {
            preg_match('/(\d+)$/', $last_preauth->preauth_number, $matches);
            $next_number = intval($matches[1]) + 1;
        } else {
            $next_number = 2000;
        }
        
        return $prefix . str_pad($next_number, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Get pre-authorization details
     * @param int $preauth_id
     * @return array Preauth details
     */
    public function get_preauth($preauth_id) {
        return $this->db->select('bip.*, bic.company_name, bic.company_code')
                        ->from('billing_insurance_preauth bip')
                        ->join('billing_insurance_policies bip2', 'bip.policy_id = bip2.policy_id')
                        ->join('billing_insurance_companies bic', 'bip2.company_id = bic.company_id')
                        ->where('bip.preauth_id', $preauth_id)
                        ->get()
                        ->row_array();
    }

    /**
     * Approve pre-authorization
     * @param int $preauth_id
     * @param float $approved_amount
     * @param string $authorization_number
     * @param int $expiry_days
     * @return bool
     */
    public function approve_preauth($preauth_id, $approved_amount, $authorization_number, $expiry_days = 30) {
        try {
            $update_data = [
                'preauth_status' => 'APPROVED',
                'approved_amount' => $approved_amount,
                'authorization_number' => $authorization_number,
                'approval_date' => date('Y-m-d'),
                'expiry_date' => date('Y-m-d', strtotime("+{$expiry_days} days"))
            ];
            
            $this->db->where('preauth_id', $preauth_id)
                    ->update('billing_insurance_preauth', $update_data);
            
            return true;
        } catch (Exception $e) {
            log_message('error', 'Error approving preauth: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Reject pre-authorization
     * @param int $preauth_id
     * @param string $reason
     * @return bool
     */
    public function reject_preauth($preauth_id, $reason) {
        try {
            $this->db->where('preauth_id', $preauth_id)
                    ->update('billing_insurance_preauth', [
                        'preauth_status' => 'REJECTED',
                        'reason_for_rejection' => $reason
                    ]);
            
            return true;
        } catch (Exception $e) {
            log_message('error', 'Error rejecting preauth: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Create insurance claim
     * @param array $claim_data
     * @return int Claim ID
     */
    public function create_claim($claim_data) {
        try {
            $claim_data['claim_number'] = $this->generate_claim_number();
            $claim_data['claim_date'] = date('Y-m-d');
            $claim_data['created_by'] = $this->session->userdata('user_id') ?? 1;
            
            $this->db->insert('billing_insurance_claims', $claim_data);
            return $this->db->insert_id();
        } catch (Exception $e) {
            log_message('error', 'Error creating claim: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate claim number
     * @return string Claim number
     */
    public function generate_claim_number() {
        $prefix = 'CLM';
        $last_claim = $this->db->select('claim_number')
                               ->order_by('claim_id', 'DESC')
                               ->limit(1)
                               ->get('billing_insurance_claims')
                               ->row();
        
        if ($last_claim) {
            preg_match('/(\d+)$/', $last_claim->claim_number, $matches);
            $next_number = intval($matches[1]) + 1;
        } else {
            $next_number = 3000;
        }
        
        return $prefix . str_pad($next_number, 8, '0', STR_PAD_LEFT);
    }

    /**
     * Get claim details
     * @param int $claim_id
     * @return array Claim details
     */
    public function get_claim($claim_id) {
        $claim = $this->db->select('bic.*, bip.policy_number, bic2.company_name, bi.invoice_number, bi.total_amount')
                         ->from('billing_insurance_claims bic')
                         ->join('billing_insurance_policies bip', 'bic.policy_id = bip.policy_id', 'left')
                         ->join('billing_insurance_companies bic2', 'bip.company_id = bic2.company_id', 'left')
                         ->join('billing_invoices bi', 'bic.invoice_id = bi.invoice_id', 'left')
                         ->where('bic.claim_id', $claim_id)
                         ->get()
                         ->row_array();
        
        if ($claim) {
            $claim['documents'] = $this->get_claim_documents($claim_id);
            $claim['followups'] = $this->get_claim_followups($claim_id);
        }
        
        return $claim;
    }

    /**
     * Submit claim to insurance
     * @param int $claim_id
     * @param string $remarks
     * @return bool
     */
    public function submit_claim($claim_id, $remarks = '') {
        try {
            $this->db->trans_start();
            
            $this->db->where('claim_id', $claim_id)
                    ->update('billing_insurance_claims', [
                        'claim_status' => 'SUBMITTED',
                        'claim_submitted_date' => date('Y-m-d'),
                        'submitted_by' => $this->session->userdata('user_id') ?? 1,
                        'submitted_at' => date('Y-m-d H:i:s'),
                        'remarks' => $remarks
                    ]);
            
            $this->db->trans_complete();
            return $this->db->trans_status();
        } catch (Exception $e) {
            log_message('error', 'Error submitting claim: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Approve claim (insurance approval)
     * @param int $claim_id
     * @param float $approved_amount
     * @param string $insurance_reference
     * @return bool
     */
    public function approve_claim($claim_id, $approved_amount, $insurance_reference = '') {
        try {
            $this->db->trans_start();
            
            $this->db->where('claim_id', $claim_id)
                    ->update('billing_insurance_claims', [
                        'claim_status' => 'APPROVED',
                        'approved_amount' => $approved_amount,
                        'approval_date' => date('Y-m-d'),
                        'insurance_reference_number' => $insurance_reference
                    ]);
            
            // Link to invoice for automatic settlement
            $claim = $this->db->where('claim_id', $claim_id)
                             ->get('billing_insurance_claims')
                             ->row();
            
            if ($claim) {
                // Record as insurance payment
                $this->db->insert('billing_payments', [
                    'invoice_id' => $claim->invoice_id,
                    'payment_number' => $this->generate_claim_payment_number(),
                    'payment_date' => date('Y-m-d'),
                    'payment_amount' => $approved_amount,
                    'payment_method_id' => 7, // INSURANCE
                    'reference_number' => $insurance_reference,
                    'created_by' => $this->session->userdata('user_id') ?? 1
                ]);
            }
            
            $this->db->trans_complete();
            return $this->db->trans_status();
        } catch (Exception $e) {
            log_message('error', 'Error approving claim: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Reject claim
     * @param int $claim_id
     * @param string $reason
     * @return bool
     */
    public function reject_claim($claim_id, $reason) {
        try {
            $this->db->where('claim_id', $claim_id)
                    ->update('billing_insurance_claims', [
                        'claim_status' => 'REJECTED',
                        'rejection_reason' => $reason
                    ]);
            
            return true;
        } catch (Exception $e) {
            log_message('error', 'Error rejecting claim: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Add claim document
     * @param int $claim_id
     * @param array $document_data
     * @return int Document ID
     */
    public function add_claim_document($claim_id, $document_data) {
        $document_data['claim_id'] = $claim_id;
        $document_data['uploaded_by'] = $this->session->userdata('user_id') ?? 1;
        
        $this->db->insert('billing_claim_documents', $document_data);
        return $this->db->insert_id();
    }

    /**
     * Get claim documents
     * @param int $claim_id
     * @return array Documents
     */
    public function get_claim_documents($claim_id) {
        return $this->db->where('claim_id', $claim_id)
                        ->order_by('uploaded_date', 'DESC')
                        ->get('billing_claim_documents')
                        ->result_array();
    }

    /**
     * Add claim follow-up
     * @param int $claim_id
     * @param array $followup_data
     * @return int Followup ID
     */
    public function add_claim_followup($claim_id, $followup_data) {
        $followup_data['claim_id'] = $claim_id;
        $followup_data['created_by'] = $this->session->userdata('user_id') ?? 1;
        
        if (empty($followup_data['followup_date'])) {
            $followup_data['followup_date'] = date('Y-m-d');
        }
        
        $this->db->insert('billing_claim_followups', $followup_data);
        return $this->db->insert_id();
    }

    /**
     * Get claim follow-ups
     * @param int $claim_id
     * @return array Follow-ups
     */
    public function get_claim_followups($claim_id) {
        return $this->db->where('claim_id', $claim_id)
                        ->order_by('followup_date', 'DESC')
                        ->get('billing_claim_followups')
                        ->result_array();
    }

    /**
     * Get claims for review
     * @param string $status
     * @return array Claims
     */
    public function get_claims_for_review($status = 'UNDER_PROCESS') {
        return $this->db->select('bic.*, bip.policy_number, bic2.company_name, bi.invoice_number')
                        ->from('billing_insurance_claims bic')
                        ->join('billing_insurance_policies bip', 'bic.policy_id = bip.policy_id')
                        ->join('billing_insurance_companies bic2', 'bip.company_id = bic2.company_id')
                        ->join('billing_invoices bi', 'bic.invoice_id = bi.invoice_id')
                        ->where('bic.claim_status', $status)
                        ->order_by('bic.claim_date', 'DESC')
                        ->get()
                        ->result_array();
    }

    /**
     * Get pending pre-authorizations
     * @return array Pending pre-auths
     */
    public function get_pending_preauths() {
        return $this->db->select('bip.*, bic.policy_number, bic2.company_name')
                        ->from('billing_insurance_preauth bip')
                        ->join('billing_insurance_policies bic', 'bip.policy_id = bic.policy_id')
                        ->join('billing_insurance_companies bic2', 'bic.company_id = bic2.company_id')
                        ->where('bip.preauth_status', 'SUBMITTED')
                        ->order_by('bip.preauth_date', 'ASC')
                        ->get()
                        ->result_array();
    }

    /**
     * Generate claim payment number
     * @return string Payment number
     */
    protected function generate_claim_payment_number() {
        $prefix = 'CLMPAY';
        $last = $this->db->select('reference_number')
                        ->like('reference_number', 'CLMPAY')
                        ->order_by('payment_id', 'DESC')
                        ->limit(1)
                        ->get('billing_payments')
                        ->row();
        
        if ($last) {
            preg_match('/(\d+)$/', $last->reference_number, $matches);
            $next_number = intval($matches[1]) + 1;
        } else {
            $next_number = 1;
        }
        
        return $prefix . str_pad($next_number, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Calculate claim settlement
     * @param int $invoice_id
     * @param int $policy_id
     * @return array Settlement details
     */
    public function calculate_claim_settlement($invoice_id, $policy_id) {
        $invoice = $this->db->where('invoice_id', $invoice_id)
                           ->get('billing_invoices')
                           ->row_array();
        
        $policy = $this->db->where('policy_id', $policy_id)
                          ->get('billing_insurance_policies')
                          ->row_array();
        
        $settlement = [
            'invoice_amount' => $invoice['total_amount'] ?? 0,
            'deductible' => $policy['deductible_amount'] ?? 0,
            'copay_percentage' => $policy['co_payment_percentage'] ?? 0,
            'copay_amount' => 0,
            'approved_amount' => 0
        ];
        
        $settlement['copay_amount'] = ($settlement['invoice_amount'] * $settlement['copay_percentage']) / 100;
        $settlement['approved_amount'] = $settlement['invoice_amount'] - $settlement['deductible'] - $settlement['copay_amount'];
        
        if ($settlement['approved_amount'] > $policy['coverage_limit']) {
            $settlement['approved_amount'] = $policy['coverage_limit'];
        }
        
        return $settlement;
    }

    /**
     * Generate insurance claim report
     * @param array $filters
     * @return array Report data
     */
    public function get_claims_report($filters = []) {
        $query = $this->db->select('
                            COUNT(DISTINCT claim_id) as total_claims,
                            SUM(invoice_amount) as total_claimed,
                            SUM(approved_amount) as total_approved,
                            COUNT(CASE WHEN claim_status = "APPROVED" THEN 1 END) as approved_count,
                            COUNT(CASE WHEN claim_status = "REJECTED" THEN 1 END) as rejected_count,
                            COUNT(CASE WHEN claim_status = "UNDER_PROCESS" THEN 1 END) as pending_count
                          ')
                          ->from('billing_insurance_claims');
        
        if (!empty($filters['from_date'])) {
            $query->where('DATE(claim_date) >=', $filters['from_date']);
        }
        if (!empty($filters['to_date'])) {
            $query->where('DATE(claim_date) <=', $filters['to_date']);
        }
        if (!empty($filters['policy_id'])) {
            $query->where('policy_id', $filters['policy_id']);
        }
        if (!empty($filters['company_id'])) {
            $query->join('billing_insurance_policies bip', 'billing_insurance_claims.policy_id = bip.policy_id')
                  ->where('bip.company_id', $filters['company_id']);
        }
        
        return $query->get()->row_array();
    }
}
?>
