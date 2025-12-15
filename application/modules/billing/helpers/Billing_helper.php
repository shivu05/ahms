<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Billing Utilities - Helper functions for billing operations
 */

if (!function_exists('get_invoice_status_badge')) {
    /**
     * Get HTML badge for invoice status
     * @param string $status
     * @return string HTML badge
     */
    function get_invoice_status_badge($status) {
        $badges = [
            'DRAFT' => '<span class="label label-secondary">Draft</span>',
            'ISSUED' => '<span class="label label-info">Issued</span>',
            'PARTIALLY_PAID' => '<span class="label label-warning">Partially Paid</span>',
            'PAID' => '<span class="label label-success">Paid</span>',
            'CANCELLED' => '<span class="label label-danger">Cancelled</span>',
            'CREDITED' => '<span class="label label-primary">Credited</span>'
        ];
        
        return $badges[$status] ?? '<span class="label label-default">' . $status . '</span>';
    }
}

if (!function_exists('get_payment_status_badge')) {
    /**
     * Get HTML badge for payment status
     * @param string $status
     * @return string HTML badge
     */
    function get_payment_status_badge($status) {
        $badges = [
            'UNPAID' => '<span class="label label-danger">Unpaid</span>',
            'PARTIAL' => '<span class="label label-warning">Partial</span>',
            'PAID' => '<span class="label label-success">Paid</span>'
        ];
        
        return $badges[$status] ?? '<span class="label label-default">' . $status . '</span>';
    }
}

if (!function_exists('get_claim_status_badge')) {
    /**
     * Get HTML badge for claim status
     * @param string $status
     * @return string HTML badge
     */
    function get_claim_status_badge($status) {
        $badges = [
            'DRAFT' => '<span class="label label-secondary">Draft</span>',
            'SUBMITTED' => '<span class="label label-info">Submitted</span>',
            'ACKNOWLEDGED' => '<span class="label label-info">Acknowledged</span>',
            'UNDER_PROCESS' => '<span class="label label-warning">Under Process</span>',
            'APPROVED' => '<span class="label label-success">Approved</span>',
            'PARTIALLY_APPROVED' => '<span class="label label-success">Partially Approved</span>',
            'REJECTED' => '<span class="label label-danger">Rejected</span>',
            'PAID' => '<span class="label label-primary">Paid</span>',
            'CLOSED' => '<span class="label label-default">Closed</span>'
        ];
        
        return $badges[$status] ?? '<span class="label label-default">' . $status . '</span>';
    }
}

if (!function_exists('get_preauth_status_badge')) {
    /**
     * Get HTML badge for pre-auth status
     * @param string $status
     * @return string HTML badge
     */
    function get_preauth_status_badge($status) {
        $badges = [
            'SUBMITTED' => '<span class="label label-info">Submitted</span>',
            'APPROVED' => '<span class="label label-success">Approved</span>',
            'PARTIALLY_APPROVED' => '<span class="label label-success">Partially Approved</span>',
            'REJECTED' => '<span class="label label-danger">Rejected</span>',
            'EXPIRED' => '<span class="label label-warning">Expired</span>',
            'COMPLETED' => '<span class="label label-primary">Completed</span>'
        ];
        
        return $badges[$status] ?? '<span class="label label-default">' . $status . '</span>';
    }
}

if (!function_exists('format_currency')) {
    /**
     * Format amount as currency
     * @param float $amount
     * @param string $symbol
     * @return string Formatted currency
     */
    function format_currency($amount, $symbol = '₹') {
        return $symbol . number_format($amount, 2);
    }
}

if (!function_exists('calculate_gst')) {
    /**
     * Calculate GST on amount
     * @param float $amount
     * @param float $gst_rate
     * @return float GST amount
     */
    function calculate_gst($amount, $gst_rate) {
        return ($amount * $gst_rate) / 100;
    }
}

if (!function_exists('calculate_line_total')) {
    /**
     * Calculate line total with GST
     * @param float $quantity
     * @param float $unit_price
     * @param float $gst_rate
     * @param float $discount_percentage
     * @return array Line total breakdown
     */
    function calculate_line_total($quantity, $unit_price, $gst_rate = 0, $discount_percentage = 0) {
        $item_amount = $quantity * $unit_price;
        $discount_amount = ($item_amount * $discount_percentage) / 100;
        $taxable_amount = $item_amount - $discount_amount;
        $gst_amount = ($taxable_amount * $gst_rate) / 100;
        $line_total = $taxable_amount + $gst_amount;
        
        return [
            'item_amount' => $item_amount,
            'discount_amount' => $discount_amount,
            'taxable_amount' => $taxable_amount,
            'gst_amount' => $gst_amount,
            'line_total' => $line_total
        ];
    }
}

if (!function_exists('validate_invoice_number')) {
    /**
     * Validate invoice number format
     * @param string $invoice_number
     * @return bool Valid/Invalid
     */
    function validate_invoice_number($invoice_number) {
        return preg_match('/^[A-Z]+\d+$/', $invoice_number) ? true : false;
    }
}

if (!function_exists('validate_policy_number')) {
    /**
     * Validate policy number format
     * @param string $policy_number
     * @return bool Valid/Invalid
     */
    function validate_policy_number($policy_number) {
        return strlen($policy_number) >= 6 ? true : false;
    }
}

if (!function_exists('get_days_overdue')) {
    /**
     * Calculate days overdue for invoice
     * @param string $invoice_date
     * @param int $due_days
     * @return int Days overdue (negative if not overdue)
     */
    function get_days_overdue($invoice_date, $due_days = 30) {
        $due_date = strtotime($invoice_date . " +{$due_days} days");
        $days_diff = (time() - $due_date) / (60 * 60 * 24);
        return intval($days_diff);
    }
}

if (!function_exists('is_invoice_overdue')) {
    /**
     * Check if invoice is overdue
     * @param string $invoice_date
     * @param int $due_days
     * @return bool Overdue/Not overdue
     */
    function is_invoice_overdue($invoice_date, $due_days = 30) {
        return get_days_overdue($invoice_date, $due_days) > 0 ? true : false;
    }
}

if (!function_exists('validate_payment_amount')) {
    /**
     * Validate payment amount against invoice balance
     * @param float $payment_amount
     * @param float $balance_due
     * @return bool Valid/Invalid
     */
    function validate_payment_amount($payment_amount, $balance_due) {
        return ($payment_amount > 0 && $payment_amount <= $balance_due) ? true : false;
    }
}

if (!function_exists('calculate_settlement')) {
    /**
     * Calculate insurance settlement
     * @param float $invoice_amount
     * @param float $deductible
     * @param float $copay_percentage
     * @param float $coverage_limit
     * @return array Settlement breakdown
     */
    function calculate_settlement($invoice_amount, $deductible = 0, $copay_percentage = 0, $coverage_limit = null) {
        $after_deductible = $invoice_amount - $deductible;
        $copay_amount = ($after_deductible * $copay_percentage) / 100;
        $approved_amount = $after_deductible - $copay_amount;
        
        if ($coverage_limit && $approved_amount > $coverage_limit) {
            $approved_amount = $coverage_limit;
        }
        
        return [
            'invoice_amount' => $invoice_amount,
            'deductible' => $deductible,
            'after_deductible' => $after_deductible,
            'copay_percentage' => $copay_percentage,
            'copay_amount' => $copay_amount,
            'approved_amount' => $approved_amount
        ];
    }
}

if (!function_exists('get_claim_aging')) {
    /**
     * Get claim aging in days
     * @param string $claim_date
     * @return int Days since claim created
     */
    function get_claim_aging($claim_date) {
        $days_diff = (time() - strtotime($claim_date)) / (60 * 60 * 24);
        return intval($days_diff);
    }
}

if (!function_exists('is_policy_active')) {
    /**
     * Check if policy is currently active
     * @param string $start_date
     * @param string $end_date
     * @return bool Active/Inactive
     */
    function is_policy_active($start_date, $end_date) {
        $today = date('Y-m-d');
        return ($today >= $start_date && $today <= $end_date) ? true : false;
    }
}

if (!function_exists('validate_email')) {
    /**
     * Validate email format
     * @param string $email
     * @return bool Valid/Invalid
     */
    function validate_email($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) ? true : false;
    }
}

if (!function_exists('generate_reference_number')) {
    /**
     * Generate unique reference number
     * @param string $prefix
     * @return string Reference number
     */
    function generate_reference_number($prefix = 'REF') {
        return $prefix . time() . random_int(100, 999);
    }
}

if (!function_exists('get_invoice_status_color')) {
    /**
     * Get color code for invoice status
     * @param string $status
     * @return string Color code
     */
    function get_invoice_status_color($status) {
        $colors = [
            'DRAFT' => '#6c757d',
            'ISSUED' => '#17a2b8',
            'PARTIALLY_PAID' => '#ffc107',
            'PAID' => '#28a745',
            'CANCELLED' => '#dc3545',
            'CREDITED' => '#007bff'
        ];
        
        return $colors[$status] ?? '#6c757d';
    }
}

if (!function_exists('truncate_string')) {
    /**
     * Truncate string to specified length
     * @param string $string
     * @param int $length
     * @param string $suffix
     * @return string Truncated string
     */
    function truncate_string($string, $length = 50, $suffix = '...') {
        if (strlen($string) <= $length) {
            return $string;
        }
        return substr($string, 0, $length) . $suffix;
    }
}

?>
