<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Billing Model - Core Billing Operations
 * Handles invoice creation, management, and related operations
 */
class Billing_model extends CI_Model {

    protected $table = 'billing_invoices';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Create new invoice
     * @param array $invoice_data
     * @return int Invoice ID
     */
    public function create_invoice($invoice_data) {
        try {
            $this->db->insert($this->table, $invoice_data);
            return $this->db->insert_id();
        } catch (Exception $e) {
            log_message('error', 'Error creating invoice: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get invoice details with line items
     * @param int $invoice_id
     * @return array Invoice with items
     */
    public function get_invoice_details($invoice_id) {
        $this->db->select('i.*, 
                          COALESCE(p.FirstName, "") as patient_first_name,
                          COALESCE(p.LastName, "") as patient_last_name,
                          COALESCE(ip.FName, "") as ipd_patient_name')
                  ->from($this->table . ' i')
                  ->join('patientdata p', 'i.opd_no = p.OpdNo', 'left')
                  ->join('inpatientdetails ip', 'i.ipd_no = ip.IpNo', 'left')
                  ->where('i.invoice_id', $invoice_id);
        
        $invoice = $this->db->get()->row_array();
        
        if ($invoice) {
            $invoice['items'] = $this->get_invoice_items($invoice_id);
            $invoice['payments'] = $this->get_invoice_payments($invoice_id);
        }
        
        return $invoice;
    }

    /**
     * Get all line items for an invoice
     * @param int $invoice_id
     * @return array Line items
     */
    public function get_invoice_items($invoice_id) {
        return $this->db->where('invoice_id', $invoice_id)
                        ->order_by('item_id', 'ASC')
                        ->get('billing_invoice_items')
                        ->result_array();
    }

    /**
     * Add line item to invoice
     * @param int $invoice_id
     * @param array $item_data
     * @return int Item ID
     */
    public function add_invoice_item($invoice_id, $item_data) {
        $item_data['invoice_id'] = $invoice_id;
        $this->db->insert('billing_invoice_items', $item_data);
        return $this->db->insert_id();
    }

    /**
     * Update invoice totals
     * @param int $invoice_id
     * @param array $totals
     * @return bool
     */
    public function update_invoice_totals($invoice_id, $totals) {
        try {
            $this->db->where('invoice_id', $invoice_id)
                     ->update($this->table, $totals);
            return true;
        } catch (Exception $e) {
            log_message('error', 'Error updating invoice totals: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Calculate invoice totals from items
     * @param int $invoice_id
     * @return array Calculated totals
     */
    public function calculate_invoice_totals($invoice_id) {
        $this->db->select('
                    SUM(COALESCE(item_amount, 0)) as subtotal,
                    SUM(COALESCE(gst_amount, 0)) as gst_total,
                    SUM(COALESCE(discount_amount, 0)) as discount_total,
                    SUM(COALESCE(line_total, 0)) as total_amount
                  ')
                  ->where('invoice_id', $invoice_id)
                  ->from('billing_invoice_items');
        
        $result = $this->db->get()->row_array();
        
        return [
            'subtotal_amount' => $result['subtotal'] ?? 0,
            'gst_amount' => $result['gst_total'] ?? 0,
            'discount_amount' => $result['discount_total'] ?? 0,
            'total_amount' => $result['total_amount'] ?? 0,
            'balance_due' => ($result['total_amount'] ?? 0) - $this->get_paid_amount($invoice_id)
        ];
    }

    /**
     * Get all invoices for patient
     * @param int $opd_no or $ipd_no
     * @param string $type OPD/IPD/ALL
     * @return array Invoices
     */
    public function get_patient_invoices($patient_identifier, $type = 'ALL') {
        $this->db->where('invoice_status !=', 'CANCELLED')
                 ->order_by('invoice_date', 'DESC');
        
        if ($type == 'OPD') {
            $this->db->where('opd_no', $patient_identifier);
        } elseif ($type == 'IPD') {
            $this->db->where('ipd_no', $patient_identifier);
        } else {
            $this->db->group_start()
                     ->where('opd_no', $patient_identifier)
                     ->or_where('ipd_no', $patient_identifier)
                     ->group_end();
        }
        
        return $this->db->get($this->table)->result_array();
    }

    /**
     * Update invoice status
     * @param int $invoice_id
     * @param string $status
     * @return bool
     */
    public function update_invoice_status($invoice_id, $status) {
        try {
            $this->db->where('invoice_id', $invoice_id)
                     ->update($this->table, [
                         'invoice_status' => $status,
                         'updated_at' => date('Y-m-d H:i:s')
                     ]);
            
            $this->log_audit_trail('billing_invoices', $invoice_id, 'UPDATE', 
                                   ['status' => $status]);
            return true;
        } catch (Exception $e) {
            log_message('error', 'Error updating invoice status: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate next invoice number
     * @return string Invoice number
     */
    public function generate_invoice_number() {
        $prefix = $this->get_config('INVOICE_PREFIX', 'INV');
        $last_invoice = $this->db->select('invoice_number')
                                  ->order_by('invoice_id', 'DESC')
                                  ->limit(1)
                                  ->get($this->table)
                                  ->row();
        
        if ($last_invoice) {
            preg_match('/(\d+)$/', $last_invoice->invoice_number, $matches);
            $next_number = intval($matches[1]) + 1;
        } else {
            $next_number = intval($this->get_config('INVOICE_START_NUMBER', '1000'));
        }
        
        return $prefix . str_pad($next_number, 8, '0', STR_PAD_LEFT);
    }

    /**
     * Get configuration value
     * @param string $key
     * @param mixed $default
     * @return mixed Config value
     */
    public function get_config($key, $default = null) {
        $config = $this->db->where('config_key', $key)
                          ->get('billing_configurations')
                          ->row();
        
        return $config ? $config->config_value : $default;
    }

    /**
     * Get paid amount for invoice
     * @param int $invoice_id
     * @return float Paid amount
     */
    public function get_paid_amount($invoice_id) {
        $result = $this->db->select('SUM(payment_amount) as total_paid')
                          ->where('invoice_id', $invoice_id)
                          ->get('billing_payments')
                          ->row();
        
        return $result->total_paid ?? 0;
    }

    /**
     * Get invoice payments
     * @param int $invoice_id
     * @return array Payments
     */
    public function get_invoice_payments($invoice_id) {
        return $this->db->select('bp.*, bpm.method_name')
                        ->from('billing_payments bp')
                        ->join('billing_payment_methods bpm', 'bp.payment_method_id = bpm.method_id', 'left')
                        ->where('bp.invoice_id', $invoice_id)
                        ->order_by('bp.payment_date', 'DESC')
                        ->get()
                        ->result_array();
    }

    /**
     * Log audit trail
     * @param string $entity_type
     * @param int $entity_id
     * @param string $action
     * @param array $changes
     */
    protected function log_audit_trail($entity_type, $entity_id, $action, $changes = []) {
        $log_data = [
            'entity_type' => $entity_type,
            'entity_id' => $entity_id,
            'action_type' => $action,
            'new_values' => json_encode($changes),
            'user_id' => $this->session->userdata('user_id') ?? 1,
            'ip_address' => $this->input->ip_address(),
            'user_agent' => $this->input->user_agent()
        ];
        
        $this->db->insert('billing_audit_logs', $log_data);
    }

    /**
     * Get invoice summary/dashboard data
     * @param array $filters
     * @return array Summary data
     */
    public function get_invoice_summary($filters = []) {
        $query = $this->db->select('
                            COUNT(DISTINCT invoice_id) as total_invoices,
                            SUM(total_amount) as total_revenue,
                            SUM(amount_paid) as total_collected,
                            SUM(balance_due) as total_pending,
                            SUM(CASE WHEN invoice_status = "PAID" THEN 1 ELSE 0 END) as paid_invoices,
                            SUM(CASE WHEN invoice_status = "UNPAID" THEN 1 ELSE 0 END) as unpaid_invoices,
                            SUM(CASE WHEN invoice_status = "PARTIALLY_PAID" THEN 1 ELSE 0 END) as partial_invoices
                          ')
                          ->from($this->table);
        
        // Apply filters
        if (!empty($filters['from_date'])) {
            $query->where('DATE(invoice_date) >=', $filters['from_date']);
        }
        if (!empty($filters['to_date'])) {
            $query->where('DATE(invoice_date) <=', $filters['to_date']);
        }
        if (!empty($filters['invoice_type'])) {
            $query->where('invoice_type', $filters['invoice_type']);
        }
        if (!empty($filters['invoice_status'])) {
            $query->where('invoice_status', $filters['invoice_status']);
        }
        if (!empty($filters['department'])) {
            $query->where('department', $filters['department']);
        }
        
        return $query->get()->row_array();
    }

    /**
     * Get overdue invoices
     * @return array Overdue invoices
     */
    public function get_overdue_invoices() {
        $due_days = $this->get_config('INVOICE_DUE_DAYS', 30);
        $due_date = date('Y-m-d', strtotime("-{$due_days} days"));
        
        return $this->db->where('payment_status !=', 'PAID')
                       ->where('invoice_date <', $due_date)
                       ->get($this->table)
                       ->result_array();
    }

    /**
     * Cancel invoice
     * @param int $invoice_id
     * @param string $reason
     * @return bool
     */
    public function cancel_invoice($invoice_id, $reason = '') {
        try {
            $this->db->trans_start();
            
            $this->db->where('invoice_id', $invoice_id)
                     ->update($this->table, [
                         'invoice_status' => 'CANCELLED',
                         'remarks' => $reason,
                         'updated_at' => date('Y-m-d H:i:s')
                     ]);
            
            $this->log_audit_trail('billing_invoices', $invoice_id, 'CANCEL', 
                                   ['reason' => $reason]);
            
            $this->db->trans_complete();
            return $this->db->trans_status();
        } catch (Exception $e) {
            log_message('error', 'Error cancelling invoice: ' . $e->getMessage());
            return false;
        }
    }
}
?>
