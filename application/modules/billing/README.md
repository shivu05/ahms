# 🎉 Professional VHMS Billing Module

## 🚨 DATABASE SETUP HELP

**Getting ERROR 1005 or ERROR 1061?** 
→ See **STEP_BY_STEP_FIX.md** for quick fix (5 min)

**Need documentation?**
→ See **INDEX.md** for all guides

---

## Overview
A comprehensive, production-ready billing module for the VHMS (Veterinary/Hospital Management System) with full support for OPD/IPD billing, insurance claims management, pre-authorizations, and advanced payment processing.

## Features

### 1. **Invoice Management**
- Create invoices for OPD, IPD, Emergency, and Pharmacy services
- Automatic invoice numbering with configurable prefix
- Line-item based billing with GST calculations
- Multiple discount options (percentage/fixed amount)
- Invoice status tracking (DRAFT, ISSUED, PARTIALLY_PAID, PAID, CANCELLED)
- Print-ready invoice generation
- Invoice history and archival

### 2. **Service Management**
- Hierarchical service categories (Consultation, Diagnostic, Procedure, etc.)
- Individual service pricing with GST configuration
- Service package/bundled offerings
- Service availability filters (OPD/IPD specific)
- Bulk service management capabilities

### 3. **Payment Processing**
- Multiple payment methods (Cash, Cheque, Card, Bank Transfer, UPI, Wallet)
- Automatic payment receipt generation
- Deposit/advance management
- Deposit adjustment against invoices
- Payment reconciliation
- Daily collection reports
- Payment reversal/refund capability

### 4. **Insurance Management**

#### Insurance Companies
- Master database of insurance providers
- Contact information and claim submission details
- Configurable communication channels

#### Insurance Policies
- Policy master maintenance
- Coverage limit management
- Deductible and co-payment configuration
- Policy expiry tracking
- Multiple policy types (Individual, Family, Group, Corporate)

#### Pre-Authorization (Pre-Auth)
- Request pre-authorization for treatments
- Insurance approval workflow
- Authorization number tracking
- Expiry date management
- Pre-auth document management

#### Insurance Claims
- Automated claim creation from invoices
- Claim settlement calculation based on policy terms
- Multi-status claim workflow (DRAFT → SUBMITTED → APPROVED → PAID)
- Partial approval handling
- Claim rejection with reason tracking
- Document attachment (invoices, prescriptions, reports, lab results, discharge summaries)
- Follow-up management and audit trail
- Insurance reference number tracking

### 5. **Advanced Features**
- **Audit Logging**: Complete audit trail for all transactions
- **Tax Management**: Flexible GST/tax configuration
- **Discount Master**: Create and apply promotional discounts
- **Credit Notes**: Issue credit notes for returns/adjustments
- **Payment Reconciliation**: Daily and monthly reconciliation reports
- **Patient Billing History**: Comprehensive patient-wise billing summary
- **Overdue Management**: Automatic identification of overdue invoices
- **Configuration Management**: System-wide billing configuration

## Installation

### 1. Database Setup
```bash
# Navigate to the module SQL directory
cd application/modules/billing/sql

# Import the billing module schema
mysql -u your_user -p your_database < billing_module.sql
```

### 2. Module Configuration
The module is automatically enabled in HMVC architecture. Ensure the module folder is in:
```
application/modules/billing/
```

### 3. Permissions (if using RBAC)
Add the following permissions to your role management:

```php
// Menu Items
- billing: Billing Management
  - billing-dashboard: Dashboard
  - billing-invoices: Invoice Management
  - billing-services: Service Configuration
  - billing-payments: Payment Management
  - billing-insurance: Insurance Management
```

## Database Tables

### Core Tables
- `billing_invoices` - Invoice master
- `billing_invoice_items` - Line items
- `billing_services` - Service master
- `billing_service_categories` - Service categories
- `billing_service_packages` - Service packages

### Payment Tables
- `billing_payments` - Payment records
- `billing_deposits` - Advance deposits
- `billing_deposit_adjustments` - Deposit adjustments
- `billing_payment_methods` - Payment methods master

### Insurance Tables
- `billing_insurance_companies` - Insurance companies
- `billing_insurance_policies` - Insurance policies
- `billing_insurance_preauth` - Pre-authorization requests
- `billing_insurance_claims` - Insurance claims
- `billing_claim_documents` - Claim documents
- `billing_claim_followups` - Claim follow-ups

### Configuration Tables
- `billing_configurations` - System settings
- `billing_tax_configurations` - Tax rates
- `billing_discounts` - Discount master
- `billing_audit_logs` - Audit trail
- `billing_credit_notes` - Credit notes

## Usage

### Creating an Invoice

```php
// OPD Invoice
$invoice_data = [
    'invoice_number' => 'INV00001001',
    'invoice_date' => date('Y-m-d'),
    'opd_no' => 1234,
    'invoice_type' => 'OPD',
    'subtotal_amount' => 500,
    'gst_amount' => 25,
    'total_amount' => 525,
    'invoice_status' => 'DRAFT'
];

$invoice_id = $this->Billing_model->create_invoice($invoice_data);
```

### Adding Line Items

```php
$item_data = [
    'service_id' => 5,
    'service_name' => 'Consultation',
    'quantity' => 1,
    'unit_price' => 500,
    'item_amount' => 500,
    'gst_applicable' => 1,
    'gst_rate' => 5,
    'gst_amount' => 25,
    'line_total' => 525
];

$item_id = $this->Billing_model->add_invoice_item($invoice_id, $item_data);
```

### Processing Payment

```php
$payment_data = [
    'invoice_id' => $invoice_id,
    'payment_date' => date('Y-m-d'),
    'payment_amount' => 525,
    'payment_method_id' => 1, // CASH
    'reference_number' => 'REF123',
    'created_by' => $user_id
];

$payment_id = $this->Payment_model->record_payment($payment_data);
```

### Insurance Claim Process

#### Step 1: Create/Link Policy
```php
$policy_data = [
    'policy_number' => 'POL123456',
    'company_id' => 1,
    'policy_holder_name' => 'John Doe',
    'coverage_limit' => 100000,
    'deductible_amount' => 5000,
    'co_payment_percentage' => 10
];

$policy_id = $this->Insurance_model->create_policy($policy_data);
```

#### Step 2: Request Pre-Authorization (Optional)
```php
$preauth_data = [
    'policy_id' => $policy_id,
    'estimated_cost' => 50000,
    'treatment_description' => 'Emergency Surgery',
    'patient_name' => 'John Doe'
];

$preauth_id = $this->Insurance_model->request_preauth($preauth_data);
```

#### Step 3: Create Insurance Claim
```php
$settlement = $this->Insurance_model->calculate_claim_settlement($invoice_id, $policy_id);

$claim_data = [
    'invoice_id' => $invoice_id,
    'policy_id' => $policy_id,
    'invoice_amount' => $invoice['total_amount'],
    'claimed_amount' => $settlement['approved_amount'],
    'deductible_amount' => $settlement['deductible'],
    'co_payment_amount' => $settlement['copay_amount']
];

$claim_id = $this->Insurance_model->create_claim($claim_data);
```

#### Step 4: Submit Claim
```php
$this->Insurance_model->submit_claim($claim_id, 'Claim submitted for approval');
```

#### Step 5: Approve Claim (Insurance)
```php
$this->Insurance_model->approve_claim(
    $claim_id, 
    $approved_amount, 
    'INS-REF-2025-001'
);
```

## Validation Rules

### Invoice Validations
- Invoice type must be OPD, IPD, EMERGENCY, or PHARMACY
- Patient ID is mandatory
- Total amount must equal sum of line items + GST - discount
- Cannot cancel paid or partially paid invoices
- Invoice status transitions are validated

### Payment Validations
- Payment amount cannot exceed invoice balance due
- Payment method is mandatory
- Reference number required for cheque/card/transfer payments
- Payment amount must be positive
- Duplicate payment prevention

### Insurance Validations
- Policy must be active and not expired
- Pre-authorization must be approved before claim
- Claim amount cannot exceed invoice amount
- Deductible and co-payment calculated per policy
- Coverage limit enforcement

## API Endpoints

### Invoice Management
- `POST /billing/create_invoice` - Create new invoice
- `GET /billing/view_invoice/{id}` - View invoice details
- `GET /billing/invoices` - List invoices
- `POST /billing/finalize_invoice/{id}` - Issue invoice
- `POST /billing/cancel_invoice/{id}` - Cancel invoice
- `GET /billing/print_invoice/{id}` - Print invoice
- `POST /billing/add_invoice_item` - Add line item (AJAX)

### Payment Management
- `GET /billing/payment/{invoice_id}` - Payment form
- `POST /billing/payment/{invoice_id}` - Process payment

### Insurance Management
- `GET /billing/insurance` - Dashboard
- `GET /billing/insurance/companies` - List companies
- `POST /billing/insurance/add_company` - Add company
- `GET /billing/insurance/policies` - List policies
- `POST /billing/insurance/edit_policy` - Create/Edit policy
- `GET /billing/insurance/request_preauth` - Request pre-auth
- `GET /billing/insurance/claims` - List claims
- `POST /billing/insurance/create_claim/{invoice_id}` - Create claim
- `POST /billing/insurance/submit_claim/{claim_id}` - Submit claim
- `POST /billing/insurance/approve_claim/{claim_id}` - Approve claim

## Configuration Parameters

Key configuration variables stored in `billing_configurations` table:

```
INVOICE_PREFIX: 'INV' (default invoice number prefix)
INVOICE_START_NUMBER: '1000' (starting number for invoices)
PAYMENT_RECEIPT_PREFIX: 'RCP' (receipt prefix)
CREDIT_NOTE_PREFIX: 'CN' (credit note prefix)
CLAIM_PREFIX: 'CLM' (claim number prefix)
ENABLE_ADVANCE_DEPOSIT: '1' (enable/disable advance deposits)
ALLOW_NEGATIVE_BALANCE: '0' (allow negative patient balance)
AUTO_GENERATE_INVOICE: '1' (auto-generate on service addition)
INVOICE_DUE_DAYS: '30' (invoice due date in days)
```

## Security Features

1. **Audit Logging**: All transactions logged with user ID, timestamp, and IP address
2. **Role-Based Access Control**: Integrates with existing RBAC system
3. **Input Validation**: Server-side validation on all operations
4. **Transaction Support**: Critical operations use database transactions
5. **Error Handling**: Comprehensive logging and error handling
6. **Data Integrity**: Foreign key constraints and data validation

## Reports Available

1. **Daily Collection Report** - Collections by payment method
2. **Invoice Summary** - Daily/Monthly revenue summary
3. **Overdue Invoice Report** - Invoices pending payment
4. **Insurance Claims Report** - Claims by status, company, period
5. **Patient Billing History** - Complete patient billing summary
6. **Payment Reconciliation** - Payment vs. receipt matching

## Extending the Module

### Adding New Payment Method
```php
// Add to billing_payment_methods table
$data = [
    'method_code' => 'CRYPTO',
    'method_name' => 'Cryptocurrency',
    'requires_reference' => 1,
    'is_active' => 1
];

$this->db->insert('billing_payment_methods', $data);
```

### Adding New Service Category
```php
$category_data = [
    'category_code' => 'VACCINATION',
    'category_name' => 'Vaccination Services',
    'gst_applicable' => 1,
    'gst_rate' => 5
];

$category_id = $this->Service_model->create_category($category_data);
```

### Custom Report
Create a new method in `Billing_model`:

```php
public function get_custom_report($filters) {
    return $this->db->where_in('invoice_status', ['ISSUED', 'PAID'])
                    ->where('invoice_date >=', $filters['from_date'])
                    ->get('billing_invoices')
                    ->result_array();
}
```

## Troubleshooting

### Invoice not showing
- Check if `billing_invoices` table exists
- Verify user has proper permissions
- Check error logs in `application/logs/`

### Payment not updating invoice
- Ensure payment amount matches calculation
- Check if invoice is in correct status
- Verify payment method exists in `billing_payment_methods`

### Insurance claim approval not working
- Verify policy is active and not expired
- Check coverage limit vs. claimed amount
- Ensure claim is in correct status (UNDER_PROCESS)

## Support & Maintenance

- Regular backups of billing database recommended
- Monthly reconciliation of invoices vs. payments
- Quarterly review of insurance claims process
- Annual audit of billing module

## Version History

- **v1.0** (2025) - Initial release with complete feature set

## License & Attribution

This module is part of the VHMS Project. All code follows the hospital management system standards and security protocols.

---

**Last Updated**: December 2025
