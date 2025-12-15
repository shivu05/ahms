<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Billing Module Configuration
 * Contains module-specific configuration settings
 */

// =====================================================
// INVOICE CONFIGURATION
// =====================================================

$config['billing']['invoice'] = [
    'prefix' => 'INV',
    'start_number' => 1000,
    'padding' => 8,
    'auto_generate' => true,
    'due_days' => 30
];

// =====================================================
// PAYMENT CONFIGURATION
// =====================================================

$config['billing']['payment'] = [
    'receipt_prefix' => 'RCP',
    'receipt_start' => 1000,
    'allow_negative_balance' => false,
    'enable_advance_deposits' => true,
    'auto_update_status' => true
];

// =====================================================
// TAX/GST CONFIGURATION
// =====================================================

$config['billing']['tax'] = [
    'default_rate' => 5.00,
    'rates' => [
        'CONSULTATION' => 5.00,
        'DIAGNOSTIC' => 5.00,
        'PROCEDURE' => 5.00,
        'WARD' => 5.00,
        'PHARMACY' => 5.00
    ]
];

// =====================================================
// INSURANCE CONFIGURATION
// =====================================================

$config['billing']['insurance'] = [
    'claim_prefix' => 'CLM',
    'claim_start' => 3000,
    'preauth_prefix' => 'PREAUTH',
    'preauth_start' => 2000,
    'preauth_validity_days' => 30,
    'auto_calculate_settlement' => true,
    'require_documents' => true,
    'document_types' => [
        'INVOICE' => 'Invoice',
        'PRESCRIPTION' => 'Prescription',
        'MEDICAL_REPORT' => 'Medical Report',
        'LAB_REPORT' => 'Lab Report',
        'IMAGING' => 'Imaging Report',
        'DISCHARGE_SUMMARY' => 'Discharge Summary',
        'OTHER' => 'Other'
    ]
];

// =====================================================
// PAYMENT METHODS
// =====================================================

$config['billing']['payment_methods'] = [
    'CASH' => ['name' => 'Cash', 'requires_reference' => false],
    'CHEQUE' => ['name' => 'Cheque', 'requires_reference' => true],
    'CARD' => ['name' => 'Credit/Debit Card', 'requires_reference' => true],
    'TRANSFER' => ['name' => 'Bank Transfer', 'requires_reference' => true],
    'UPI' => ['name' => 'UPI Payment', 'requires_reference' => true],
    'WALLET' => ['name' => 'Digital Wallet', 'requires_reference' => true],
    'INSURANCE' => ['name' => 'Insurance Settlement', 'requires_reference' => true]
];

// =====================================================
// SERVICE CATEGORIES
// =====================================================

$config['billing']['service_categories'] = [
    'CONSULTATION' => 'Consultation',
    'DIAGNOSTIC' => 'Diagnostic Services',
    'PROCEDURE' => 'Surgical Procedures',
    'WARD' => 'Ward/Room Charges',
    'PATHOLOGY' => 'Pathology Tests',
    'RADIOLOGY' => 'Radiology/Imaging',
    'PHARMACY' => 'Pharmacy Services',
    'PHYSIOTHERAPY' => 'Physiotherapy'
];

// =====================================================
// INVOICE TYPES
// =====================================================

$config['billing']['invoice_types'] = [
    'OPD' => 'OPD Invoice',
    'IPD' => 'IPD Invoice',
    'EMERGENCY' => 'Emergency',
    'PHARMACY' => 'Pharmacy'
];

// =====================================================
// DISCOUNT CONFIGURATION
// =====================================================

$config['billing']['discount'] = [
    'enable_percentage' => true,
    'enable_fixed_amount' => true,
    'max_percentage' => 50,
    'max_amount' => 10000,
    'require_approval' => false
];

// =====================================================
// VALIDATION MESSAGES
// =====================================================

$config['billing']['validation_messages'] = [
    'invoice_amount_invalid' => 'Invoice total does not match line items',
    'payment_exceeds_balance' => 'Payment amount cannot exceed balance due',
    'invalid_service' => 'Selected service is not available',
    'policy_expired' => 'Insurance policy has expired',
    'preauth_required' => 'Pre-authorization is required for this treatment',
    'claim_submission_failed' => 'Failed to submit claim to insurance'
];

// =====================================================
// EXPORT CONFIGURATION
// =====================================================

$config['billing']['export'] = [
    'enable_pdf' => true,
    'enable_excel' => true,
    'enable_email' => true,
    'paper_size' => 'A4',
    'orientation' => 'portrait'
];

// =====================================================
// EMAIL CONFIGURATION
// =====================================================

$config['billing']['email'] = [
    'send_invoice' => true,
    'send_receipt' => true,
    'send_claim_notification' => true,
    'from_email' => 'billing@hospital.com',
    'from_name' => 'Hospital Billing'
];

// =====================================================
// AUDIT & LOGGING
// =====================================================

$config['billing']['audit'] = [
    'enable_logging' => true,
    'log_invoice_changes' => true,
    'log_payments' => true,
    'log_claims' => true,
    'retention_days' => 365 * 2 // 2 years
];

// =====================================================
// REPORTING
// =====================================================

$config['billing']['reports'] = [
    'daily_collections' => true,
    'monthly_summary' => true,
    'invoice_aging' => true,
    'insurance_claims' => true,
    'payment_reconciliation' => true,
    'outstanding_receivables' => true
];

?>
