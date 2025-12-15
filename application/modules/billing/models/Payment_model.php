<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Payment Model - Handle All Payment Operations
 */
class Payment_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Record payment for invoice
     * @param array $payment_data
     * @return int Payment ID
     */
    public function record_payment($payment_data) {
        try {
            $this->db->trans_start();
            
            // Generate payment number
            $payment_data['payment_number'] = $this->generate_payment_number();
            
            $this->db->insert('billing_payments', $payment_data);
            $payment_id = $this->db->insert_id();
            
            // Update invoice payment status
            $this->update_invoice_payment_status($payment_data['invoice_id']);
            
            $this->db->trans_complete();
            
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Transaction failed');
            }
            
            return $payment_id;
        } catch (Exception $e) {
            log_message('error', 'Error recording payment: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get payment methods
     * @return array Payment methods
     */
    public function get_payment_methods() {
        return $this->db->where('is_active', 1)
                        ->order_by('method_name', 'ASC')
                        ->get('billing_payment_methods')
                        ->result_array();
    }

    /**
     * Update invoice payment status based on amount paid
     * @param int $invoice_id
     * @return bool
     */
    public function update_invoice_payment_status($invoice_id) {
        // Get invoice total
        $invoice = $this->db->select('total_amount')
                           ->where('invoice_id', $invoice_id)
                           ->get('billing_invoices')
                           ->row();
        
        // Get total paid
        $paid = $this->db->select('SUM(payment_amount) as total_paid')
                        ->where('invoice_id', $invoice_id)
                        ->get('billing_payments')
                        ->row();
        
        $paid_amount = $paid->total_paid ?? 0;
        $total_amount = $invoice->total_amount ?? 0;
        $balance_due = $total_amount - $paid_amount;
        
        $payment_status = 'UNPAID';
        $invoice_status = 'ISSUED';
        
        if ($paid_amount >= $total_amount) {
            $payment_status = 'PAID';
            $invoice_status = 'PAID';
        } elseif ($paid_amount > 0) {
            $payment_status = 'PARTIAL';
            $invoice_status = 'PARTIALLY_PAID';
        }
        
        return $this->db->where('invoice_id', $invoice_id)
                       ->update('billing_invoices', [
                           'payment_status' => $payment_status,
                           'invoice_status' => $invoice_status,
                           'amount_paid' => $paid_amount,
                           'balance_due' => $balance_due
                       ]);
    }

    /**
     * Generate payment number
     * @return string Payment number
     */
    public function generate_payment_number() {
        $prefix = 'PAY';
        $last_payment = $this->db->select('payment_number')
                                ->order_by('payment_id', 'DESC')
                                ->limit(1)
                                ->get('billing_payments')
                                ->row();
        
        if ($last_payment) {
            preg_match('/(\d+)$/', $last_payment->payment_number, $matches);
            $next_number = intval($matches[1]) + 1;
        } else {
            $next_number = 10000;
        }
        
        return $prefix . str_pad($next_number, 8, '0', STR_PAD_LEFT);
    }

    /**
     * Get payment details
     * @param int $payment_id
     * @return array Payment details
     */
    public function get_payment($payment_id) {
        return $this->db->select('bp.*, bpm.method_name, bi.invoice_number, bi.total_amount')
                        ->from('billing_payments bp')
                        ->join('billing_payment_methods bpm', 'bp.payment_method_id = bpm.method_id', 'left')
                        ->join('billing_invoices bi', 'bp.invoice_id = bi.invoice_id', 'left')
                        ->where('bp.payment_id', $payment_id)
                        ->get()
                        ->row_array();
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
     * Record deposit/advance
     * @param array $deposit_data
     * @return int Deposit ID
     */
    public function record_deposit($deposit_data) {
        try {
            $deposit_data['deposit_number'] = $this->generate_deposit_number();
            $deposit_data['remaining_amount'] = $deposit_data['deposit_amount'];
            
            $this->db->insert('billing_deposits', $deposit_data);
            return $this->db->insert_id();
        } catch (Exception $e) {
            log_message('error', 'Error recording deposit: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate deposit number
     * @return string Deposit number
     */
    public function generate_deposit_number() {
        $prefix = 'DEP';
        $last_deposit = $this->db->select('deposit_number')
                                ->order_by('deposit_id', 'DESC')
                                ->limit(1)
                                ->get('billing_deposits')
                                ->row();
        
        if ($last_deposit) {
            preg_match('/(\d+)$/', $last_deposit->deposit_number, $matches);
            $next_number = intval($matches[1]) + 1;
        } else {
            $next_number = 5000;
        }
        
        return $prefix . str_pad($next_number, 8, '0', STR_PAD_LEFT);
    }

    /**
     * Adjust deposit against invoice
     * @param int $deposit_id
     * @param int $invoice_id
     * @param float $amount
     * @return bool
     */
    public function adjust_deposit($deposit_id, $invoice_id, $amount) {
        try {
            $this->db->trans_start();
            
            // Record adjustment
            $this->db->insert('billing_deposit_adjustments', [
                'deposit_id' => $deposit_id,
                'invoice_id' => $invoice_id,
                'adjustment_amount' => $amount
            ]);
            
            // Update remaining deposit
            $this->db->set('remaining_amount', 'remaining_amount - ' . $amount, FALSE)
                    ->where('deposit_id', $deposit_id)
                    ->update('billing_deposits');
            
            // Record as payment
            $this->db->insert('billing_payments', [
                'invoice_id' => $invoice_id,
                'payment_number' => $this->generate_payment_number(),
                'payment_date' => date('Y-m-d'),
                'payment_amount' => $amount,
                'payment_method_id' => 6, // WALLET/Deposit
                'reference_number' => 'DEP-' . $deposit_id,
                'created_by' => $this->session->userdata('user_id') ?? 1
            ]);
            
            $this->update_invoice_payment_status($invoice_id);
            
            $this->db->trans_complete();
            return $this->db->trans_status();
        } catch (Exception $e) {
            log_message('error', 'Error adjusting deposit: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get patient deposits
     * @param int $opd_no or $ipd_no
     * @return array Deposits
     */
    public function get_patient_deposits($patient_identifier, $type = 'OPD') {
        $this->db->order_by('deposit_date', 'DESC');
        
        if ($type == 'OPD') {
            $this->db->where('opd_no', $patient_identifier);
        } else {
            $this->db->where('ipd_no', $patient_identifier);
        }
        
        return $this->db->get('billing_deposits')
                       ->result_array();
    }

    /**
     * Generate receipt
     * @param int $payment_id
     * @return string Receipt number
     */
    public function generate_receipt($payment_id) {
        $prefix = $this->get_config('PAYMENT_RECEIPT_PREFIX', 'RCP');
        $last_receipt = $this->db->select('receipt_number')
                                ->where('receipt_generated', 1)
                                ->order_by('payment_id', 'DESC')
                                ->limit(1)
                                ->get('billing_payments')
                                ->row();
        
        if ($last_receipt) {
            preg_match('/(\d+)$/', $last_receipt->receipt_number, $matches);
            $next_number = intval($matches[1]) + 1;
        } else {
            $next_number = 1000;
        }
        
        $receipt_number = $prefix . str_pad($next_number, 8, '0', STR_PAD_LEFT);
        
        $this->db->where('payment_id', $payment_id)
                ->update('billing_payments', [
                    'receipt_generated' => 1,
                    'receipt_number' => $receipt_number
                ]);
        
        return $receipt_number;
    }

    /**
     * Get configuration value
     * @param string $key
     * @param mixed $default
     * @return mixed Config value
     */
    protected function get_config($key, $default = null) {
        $config = $this->db->where('config_key', $key)
                          ->get('billing_configurations')
                          ->row();
        
        return $config ? $config->config_value : $default;
    }

    /**
     * Get daily collection report
     * @param string $date
     * @return array Collections by method
     */
    public function get_daily_collections($date) {
        return $this->db->select('bpm.method_name, COUNT(bp.payment_id) as count, SUM(bp.payment_amount) as total')
                        ->from('billing_payments bp')
                        ->join('billing_payment_methods bpm', 'bp.payment_method_id = bpm.method_id')
                        ->where('DATE(bp.payment_date)', $date)
                        ->group_by('bp.payment_method_id')
                        ->get()
                        ->result_array();
    }

    /**
     * Refund payment
     * @param int $payment_id
     * @param string $reason
     * @return bool
     */
    public function refund_payment($payment_id, $reason = '') {
        try {
            $payment = $this->get_payment($payment_id);
            
            if (!$payment) {
                throw new Exception('Payment not found');
            }
            
            $this->db->trans_start();
            
            // Mark payment as refunded (soft delete approach)
            $this->db->where('payment_id', $payment_id)
                    ->update('billing_payments', [
                        'payment_amount' => 0,
                        'remarks' => 'REFUNDED - ' . $reason
                    ]);
            
            // Update invoice status
            $this->update_invoice_payment_status($payment['invoice_id']);
            
            $this->db->trans_complete();
            return $this->db->trans_status();
        } catch (Exception $e) {
            log_message('error', 'Error refunding payment: ' . $e->getMessage());
            return false;
        }
    }
}
?>
