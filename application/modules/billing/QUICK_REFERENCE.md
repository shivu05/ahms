# Billing Module - Quick Reference Guide

## Module Access URLs

```
Dashboard:              /billing
Create Invoice:         /billing/create_invoice
View Invoices:          /billing/invoices
Process Payment:        /billing/payment/{invoice_id}
Patient History:        /billing/patient_history/{id}/{type}

Insurance Dashboard:    /billing/insurance
Insurance Companies:    /billing/insurance/companies
Insurance Policies:     /billing/insurance/policies
Insurance Claims:       /billing/insurance/claims
View Claim:             /billing/insurance/view_claim/{id}
Request Pre-Auth:       /billing/insurance/request_preauth
```

## Database Tables Quick Reference

### Core Invoice Tables
```sql
-- Create invoice
INSERT INTO billing_invoices 
(invoice_number, invoice_date, opd_no, invoice_type, total_amount, invoice_status)
VALUES ('INV00001001', CURDATE(), 1001, 'OPD', 500, 'DRAFT');

-- Get invoice with items
SELECT i.*, COUNT(ii.item_id) as item_count
FROM billing_invoices i
LEFT JOIN billing_invoice_items ii ON i.invoice_id = ii.invoice_id
WHERE i.invoice_id = 1;
```

### Payment Tables
```sql
-- Record payment
INSERT INTO billing_payments 
(invoice_id, payment_date, payment_amount, payment_method_id, payment_number)
VALUES (1, CURDATE(), 500, 1, 'PAY00010001');

-- Get payment summary
SELECT SUM(payment_amount) as total_paid FROM billing_payments 
WHERE invoice_id = 1;
```

### Insurance Tables
```sql
-- Get active policies
SELECT * FROM billing_insurance_policies 
WHERE is_active = 1 
AND policy_start_date <= CURDATE() 
AND policy_end_date >= CURDATE();

-- Get pending claims
SELECT * FROM billing_insurance_claims 
WHERE claim_status = 'UNDER_PROCESS' 
ORDER BY claim_date ASC;
```

## Model Methods Reference

### Billing_model Methods

```php
// Invoice operations
create_invoice($data)                   // Create new invoice
get_invoice_details($id)                // Get invoice with items & payments
get_invoice_items($id)                  // Get line items
add_invoice_item($id, $data)            // Add line item
update_invoice_totals($id, $totals)     // Update invoice calculations
calculate_invoice_totals($id)           // Recalculate totals
update_invoice_status($id, $status)     // Update status
cancel_invoice($id, $reason)            // Cancel invoice
generate_invoice_number()               // Generate next invoice number

// Patient operations
get_patient_invoices($patient_id, $type) // Get patient's invoices
get_overdue_invoices()                  // Get overdue invoices

// Reporting
get_invoice_summary($filters)           // Get summary statistics
get_paid_amount($invoice_id)            // Get paid amount
get_invoice_payments($invoice_id)       // Get payment history

// Configuration
get_config($key, $default)              // Get config value
```

### Service_model Methods

```php
// Category operations
get_categories()                        // Get all categories
create_category($data)                  // Create category
get_services_by_category($id)          // Get services in category

// Service operations
get_service($id)                        // Get service details
create_service($data)                   // Create service
update_service($id, $data)              // Update service
search_services($term)                  // Search services
is_service_available($id, $type)        // Check availability

// Package operations
get_packages()                          // Get all packages
get_package_details($id)                // Get package with services
create_package($data)                   // Create package
add_package_services($id, $services)    // Add services to package

// Pricing
get_service_pricing($id)                // Get pricing details
calculate_discount($amount, $id)        // Apply discount
```

### Payment_model Methods

```php
// Payment operations
record_payment($data)                   // Record payment
get_payment($id)                        // Get payment details
get_invoice_payments($id)               // Get all payments on invoice
update_invoice_payment_status($id)      // Update invoice status
generate_payment_number()               // Generate next payment number
refund_payment($id, $reason)            // Refund payment

// Deposit operations
record_deposit($data)                   // Record advance deposit
get_patient_deposits($id, $type)        // Get patient deposits
adjust_deposit($deposit_id, $inv_id, $amt) // Use deposit towards invoice
generate_deposit_number()               // Generate deposit number

// Receipt & Reporting
generate_receipt($payment_id)           // Generate receipt number
get_daily_collections($date)            // Get daily collection report
get_payment_methods()                   // Get payment methods
```

### Insurance_model Methods

```php
// Company operations
get_companies()                         // Get all companies
create_company($data)                   // Create company

// Policy operations
get_policies($filters)                  // Get policies with filters
get_policy($id)                         // Get policy details
create_policy($data)                    // Create policy
update_policy($id, $data)               // Update policy

// Pre-authorization
request_preauth($data)                  // Request pre-auth
get_preauth($id)                        // Get pre-auth details
approve_preauth($id, $amt, $num, $days) // Approve pre-auth
reject_preauth($id, $reason)            // Reject pre-auth
generate_preauth_number()               // Generate preauth number

// Claims
create_claim($data)                     // Create claim
get_claim($id)                          // Get claim details
submit_claim($id, $remarks)             // Submit claim
approve_claim($id, $amt, $ref)          // Approve claim
reject_claim($id, $reason)              // Reject claim
generate_claim_number()                 // Generate claim number

// Claim documents
add_claim_document($claim_id, $data)    // Add document
get_claim_documents($claim_id)          // Get documents

// Claim follow-ups
add_claim_followup($claim_id, $data)    // Add follow-up
get_claim_followups($claim_id)          // Get follow-ups

// Calculations & Reports
calculate_claim_settlement($inv_id, $policy_id) // Calculate settlement
calculate_settlement()                  // Settlement calculation
get_claims_report($filters)             // Get claims report
get_claims_for_review($status)          // Get claims needing review
get_pending_preauths()                  // Get pending pre-auths
```

## Controller Actions Quick Map

### Billing Controller

```php
billing/index()                     // Dashboard
billing/create_invoice()            // Create invoice form/process
billing/view_invoice/{id}           // View invoice details
billing/invoices()                  // List invoices
billing/payment/{invoice_id}        // Payment form/process
billing/patient_history/{id}/{type} // Patient billing history
billing/add_invoice_item()          // AJAX: Add line item
billing/finalize_invoice/{id}       // Issue invoice
billing/cancel_invoice/{id}         // Cancel invoice
billing/print_invoice/{id}          // Print invoice
```

### Insurance Controller

```php
billing/insurance/index()           // Insurance dashboard
billing/insurance/companies()       // List companies
billing/insurance/add_company()     // Add company
billing/insurance/policies()        // List policies
billing/insurance/edit_policy/{id}  // Create/Edit policy
billing/insurance/request_preauth() // Request pre-auth
billing/insurance/view_preauth/{id} // View pre-auth
billing/insurance/claims()          // List claims
billing/insurance/create_claim/{id} // Create claim from invoice
billing/insurance/view_claim/{id}   // View claim details
billing/insurance/submit_claim/{id} // Submit claim (AJAX)
billing/insurance/approve_claim({id}) // Approve claim (AJAX)
```

## Common SQL Queries

### Get Recent Invoices
```sql
SELECT invoice_number, invoice_date, patient_name, total_amount, payment_status
FROM billing_invoices
ORDER BY invoice_date DESC
LIMIT 10;
```

### Get Overdue Invoices
```sql
SELECT invoice_number, invoice_date, patient_name, balance_due
FROM billing_invoices
WHERE payment_status IN ('UNPAID', 'PARTIAL')
AND DATE_ADD(invoice_date, INTERVAL 30 DAY) < CURDATE()
ORDER BY invoice_date ASC;
```

### Get Monthly Revenue
```sql
SELECT DATE(invoice_date) as date, COUNT(*) as count, SUM(total_amount) as amount
FROM billing_invoices
WHERE MONTH(invoice_date) = MONTH(CURDATE())
AND YEAR(invoice_date) = YEAR(CURDATE())
GROUP BY DATE(invoice_date);
```

### Get Insurance Claims Summary
```sql
SELECT claim_status, COUNT(*) as count, SUM(claimed_amount) as claimed, SUM(approved_amount) as approved
FROM billing_insurance_claims
WHERE claim_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
GROUP BY claim_status;
```

### Get Collection by Payment Method
```sql
SELECT bpm.method_name, COUNT(*) as count, SUM(bp.payment_amount) as total
FROM billing_payments bp
JOIN billing_payment_methods bpm ON bp.payment_method_id = bpm.method_id
WHERE DATE(bp.payment_date) = CURDATE()
GROUP BY bp.payment_method_id;
```

## Helper Functions Quick Reference

```php
// Status badges
get_invoice_status_badge($status)      // Invoice status HTML
get_payment_status_badge($status)      // Payment status HTML
get_claim_status_badge($status)        // Claim status HTML
get_preauth_status_badge($status)      // Pre-auth status HTML

// Formatting
format_currency($amount, $symbol)      // Format as currency
get_invoice_status_color($status)      // Get color for status
truncate_string($string, $length)      // Truncate text

// Calculations
calculate_gst($amount, $rate)          // Calculate GST
calculate_line_total(...)              // Calculate line item total
calculate_settlement(...)              // Settlement calculation

// Validation
validate_invoice_number($number)       // Validate format
validate_policy_number($number)        // Validate format
validate_email($email)                 // Validate email
validate_payment_amount($amount, $due) // Validate payment amount

// Dates & Status
is_invoice_overdue($date, $days)       // Check if overdue
get_days_overdue($date, $days)         // Get days overdue count
is_policy_active($start, $end)         // Check policy validity
get_claim_aging($claim_date)           // Get claim age in days

// Utilities
generate_reference_number($prefix)     // Generate reference
```

## Common Errors & Solutions

| Error | Cause | Solution |
|-------|-------|----------|
| "Table not found" | SQL import failed | Re-run billing_module.sql |
| Invoice amount incorrect | GST not applied | Check service GST settings |
| Payment not recorded | Amount > balance | Verify balance calculation |
| Claim creation fails | Policy expired | Check policy dates |
| Pre-auth not approved | Invalid policy | Verify policy exists & active |
| Deposit adjustment fails | Insufficient balance | Check remaining amount |

## Configuration Options

```php
// In config.php - key configuration entries:

$config['billing']['invoice']['prefix']           // Invoice number prefix
$config['billing']['invoice']['start_number']     // Starting number
$config['billing']['payment']['receipt_prefix']   // Receipt prefix
$config['billing']['insurance']['claim_prefix']   // Claim number prefix
$config['billing']['tax']['default_rate']         // Default GST rate
$config['billing']['insurance']['preauth_validity_days'] // Pre-auth validity
```

## Important Default Values

| Setting | Default | Where |
|---------|---------|-------|
| Invoice Prefix | INV | config.php |
| Invoice Start # | 1000 | config.php |
| Receipt Prefix | RCP | config.php |
| Claim Prefix | CLM | config.php |
| Default GST | 5% | billing_tax_configurations |
| Pre-auth Validity | 30 days | config.php |
| Invoice Due Days | 30 days | billing_configurations |

## Key Database Relationships

```
patient (patientdata/inpatientdetails)
    ↓
billing_invoices (1-to-many)
    ↓
billing_invoice_items (1-to-many)
    └→ billing_services (1-to-1)

billing_invoices (1-to-many)
    ↓
billing_payments
billing_insurance_claims
billing_deposits

billing_insurance_claims (1-to-many)
    ↓
billing_claim_documents
billing_claim_followups

billing_insurance_policies
    ↓
billing_insurance_preauth
billing_insurance_claims
```

---

**Last Updated**: December 2025  
**Module Version**: 1.0  
**For Complete Docs**: See README.md in module folder
