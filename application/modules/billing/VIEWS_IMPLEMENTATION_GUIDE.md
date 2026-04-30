# Billing Module Views - Implementation Checklist

## Overview
The core models and controllers are complete and production-ready. View files require customization based on your specific UI framework and design preferences. Below is a guide for completing the views.

## Views Directory Structure
```
views/
├── dashboard/
│   └── index.php              ✅ CREATED
├── invoices/
│   ├── create.php             ⏳ TEMPLATE NEEDED
│   ├── view.php               ⏳ TEMPLATE NEEDED
│   ├── list.php               ✅ CREATED
│   └── print_invoice.php      ⏳ TEMPLATE NEEDED
├── payments/
│   └── payment_form.php       ⏳ TEMPLATE NEEDED
├── insurance/
│   ├── dashboard.php          ⏳ TEMPLATE NEEDED
│   ├── companies.php          ⏳ TEMPLATE NEEDED
│   ├── policies.php           ⏳ TEMPLATE NEEDED
│   ├── claims.php             ⏳ TEMPLATE NEEDED
│   ├── create_claim.php       ⏳ TEMPLATE NEEDED
│   ├── view_claim.php         ⏳ TEMPLATE NEEDED
│   ├── request_preauth.php    ⏳ TEMPLATE NEEDED
│   └── view_preauth.php       ⏳ TEMPLATE NEEDED
└── reports/
    ├── daily_collections.php  ⏳ TEMPLATE NEEDED
    ├── invoice_summary.php    ⏳ TEMPLATE NEEDED
    ├── claims_report.php      ⏳ TEMPLATE NEEDED
    └── aging_report.php       ⏳ TEMPLATE NEEDED
```

## Dashboard View (✅ CREATED)
**File**: `views/dashboard/index.php`
**Status**: Ready to use
**Features**: KPIs, Statistics, Recent invoices
**Requirements**: Bootstrap/Admin template

## Invoice Views (⏳ TO BE CREATED)

### 1. Create Invoice View
**Path**: `views/invoices/create.php`
**Required Elements**:
- Invoice type selection
- Patient search/selection
- Service selection with category filter
- Line item addition (AJAX)
- Quantity and discount inputs
- Real-time total calculation
- Submit/Draft/Cancel buttons

**Sample Form Structure**:
```html
<form id="invoiceForm">
    <select name="invoice_type">...</select>
    <input type="text" name="patient_search" id="patientSearch">
    <div id="serviceItems">
        <!-- Add items dynamically -->
    </div>
    <div class="totals">
        <span>Subtotal: <span id="subtotal"></span></span>
        <span>GST: <span id="gst"></span></span>
        <span>Total: <span id="total"></span></span>
    </div>
    <button type="submit">Create Invoice</button>
</form>
```

### 2. View Invoice Details
**Path**: `views/invoices/view.php`
**Required Elements**:
- Invoice header (number, date, patient)
- Line items table
- Calculations (subtotal, GST, discount, total)
- Payment history
- Action buttons (edit, finalize, cancel, print)
- Insurance claim section (if applicable)

### 3. Invoice List View
**Path**: `views/invoices/list.php`
**Status**: ✅ Template created (can be enhanced)
**Required Elements**:
- DataTables integration
- Filter by status
- Filter by payment status
- Quick actions (view, print, payment)
- Bulk operations (optional)

### 4. Print Invoice View
**Path**: `views/invoices/print_invoice.php`
**Required Elements**:
- Hospital header with logo
- Invoice number and date
- Patient details
- Line items in print-friendly format
- Company details
- Terms and conditions
- Payment details
- Authorized signature area
**Note**: Should be CSS print-friendly

## Payment Views (⏳ TO BE CREATED)

### Payment Form View
**Path**: `views/payments/payment_form.php`
**Required Elements**:
- Invoice details summary
- Outstanding balance
- Payment method selection
- Payment amount input
- Reference number input (conditional)
- Bank details input (for cheque/transfer)
- Remarks textarea
- Receipt preview (optional)
- Submit button

**Payment Methods Conditional Display**:
```
CASH          → No additional fields
CHEQUE        → Cheque number, Bank name
CARD          → Card type, Last 4 digits
TRANSFER      → Bank name, Transaction ID
UPI           → UPI ID, Transaction ID
WALLET        → Wallet type, Balance
INSURANCE     → Reference number
```

## Insurance Views (⏳ TO BE CREATED)

### 1. Insurance Dashboard
**Path**: `views/insurance/dashboard.php`
**Required Elements**:
- Pending claims count
- Approved claims count
- Rejected claims count
- Pending pre-authorizations
- Monthly claims report
- Recent claims list
- Quick action buttons

### 2. Insurance Companies List
**Path**: `views/insurance/companies.php`
**Required Elements**:
- DataTable with company list
- Columns: Code, Name, Contact, Status
- Add company button
- Edit/Delete actions
- Search and filter

### 3. Insurance Policies
**Path**: `views/insurance/policies.php`
**Required Elements**:
- DataTable with policies
- Columns: Policy#, Company, Holder, Limit, Valid Till, Status
- Filter by company
- Search by policy number/holder
- Add/Edit buttons
- View details link

### 4. Policy Add/Edit Form
**Path**: `views/insurance/edit_policy.php`
**Required Elements**:
- Insurance company selection
- Policy number input
- Policy holder name
- Policy type (Individual/Family/Group/Corporate)
- Coverage limit
- Deductible amount
- Co-payment percentage
- Validity dates (from/to)
- Pre-authorization requirement toggle
- Submit button

### 5. Insurance Claims List
**Path**: `views/insurance/claims.php`
**Required Elements**:
- DataTable with claims
- Columns: Claim#, Invoice#, Policy, Amount, Status, Date
- Status filter dropdown
- Status badges
- View, Print, Email actions
- Bulk actions (export, print multiple)

### 6. Create Insurance Claim
**Path**: `views/insurance/create_claim.php`
**Required Elements**:
- Invoice details (read-only)
- Policy selection
- Settlement calculation display:
  - Invoice amount
  - Deductible
  - Co-payment %
  - Approved amount
- Create claim button

### 7. View Claim Details
**Path**: `views/insurance/view_claim.php`
**Required Elements**:
- Claim header (number, status, dates)
- Invoice details
- Policy details
- Settlement breakdown
- Documents section (upload/download)
- Follow-up section
- Status-based action buttons
- Timeline of claim actions
- Amount breakdown

### 8. Request Pre-Authorization
**Path**: `views/insurance/request_preauth.php`
**Required Elements**:
- Policy selection
- Estimated cost input
- Treatment description
- Patient details
- Submit button

### 9. View Pre-Authorization
**Path**: `views/insurance/view_preauth.php`
**Required Elements**:
- Pre-auth details (number, status)
- Policy information
- Request details
- Approval information (if approved)
- Expiry date
- Action buttons (approve/reject if pending)

## Report Views (⏳ TO BE CREATED)

### 1. Daily Collections Report
**Path**: `views/reports/daily_collections.php`
**Required Elements**:
- Date picker
- Collection by payment method table
- Total amount by method
- Chart visualization
- Export to PDF/Excel

### 2. Invoice Summary Report
**Path**: `views/reports/invoice_summary.php`
**Required Elements**:
- Date range picker
- Total invoices
- Total amount
- Collections summary
- Outstanding summary
- Department-wise breakdown
- Chart visualization

### 3. Insurance Claims Report
**Path**: `views/reports/claims_report.php`
**Required Elements**:
- Date range picker
- Company filter
- Status breakdown
- Claimed vs approved comparison
- Pending claims list
- Chart visualization

### 4. Invoice Aging Report
**Path**: `views/reports/aging_report.php`
**Required Elements**:
- Age buckets (0-30, 30-60, 60-90, 90+)
- Patient-wise outstanding
- Department-wise outstanding
- Export capability
- Follow-up reminder section

## Implementation Priority

### Phase 1 (Critical - Must Have)
1. ✅ Dashboard
2. ⏳ Create Invoice
3. ⏳ View Invoice
4. ⏳ Payment Form
5. ⏳ Print Invoice

### Phase 2 (Important)
1. ⏳ Insurance Companies
2. ⏳ Insurance Policies
3. ⏳ Create Claim
4. ⏳ View Claim
5. ⏳ Request Pre-Auth

### Phase 3 (Nice to Have)
1. ⏳ Insurance Dashboard
2. ⏳ Reports
3. ⏳ Advanced Filters
4. ⏳ Bulk Operations

## UI Framework Notes

The module is designed to work with:
- **Bootstrap 3/4/5** (most common)
- **Admin LTE** (if already used)
- **Material Design**
- **Custom CSS**

The controllers return data as PHP variables to views, making them framework-agnostic.

## Important View Implementation Points

1. **Auto-calculation**: Use JavaScript for real-time calculations
2. **AJAX Integration**: Use add_invoice_item() for dynamic item addition
3. **Validation**: Client-side for UX, server-side enforced
4. **Status Badges**: Use get_invoice_status_badge() helper
5. **Currency Formatting**: Use format_currency() helper
6. **Date Formatting**: Use date formatting consistently
7. **Responsive Design**: Mobile-friendly tables with horizontal scroll
8. **Print Stylesheet**: Separate print styles for invoices
9. **Modal Dialogs**: For quick actions (refund, cancel, etc.)
10. **Loading Indicators**: For AJAX operations

## JavaScript Requirements

Most views will need:
- jQuery (likely already included)
- DataTables (for data tables)
- Bootstrap Modal (for dialogs)
- Custom scripts for calculations
- Date picker libraries (if needed)

## Sample AJAX Integration

```javascript
// Add invoice item (existing in controller)
$.post('<?php echo site_url("billing/add_invoice_item"); ?>', {
    invoice_id: invoiceId,
    service_id: serviceId,
    quantity: qty
}, function(response) {
    if (response.success) {
        updateInvoiceTotals();
        addItemToTable(response);
    }
});
```

## View Variables (Passed from Controllers)

Each view receives pre-populated variables:

**Dashboard**:
```php
$today_invoices, $today_collection, $overdue_invoices
$pending_claims, $month_summary, $recent_invoices
```

**Create Invoice**:
```php
$invoice_types, $service_categories, $error, $errors
```

**View Invoice**:
```php
$invoice, $can_edit, $can_finalize
```

**Payment**:
```php
$invoice, $payment_methods, $error, $errors
```

**Insurance Companies**:
```php
$companies
```

**Create Claim**:
```php
$invoice, $policies, $settlement
```

**View Claim**:
```php
$claim, $can_submit, $can_approve, $can_reject
```

## Next Steps for View Implementation

1. Choose your UI framework (Bootstrap is recommended)
2. Copy and modify existing dashboard template as starting point
3. Create forms with validation (client-side and server-side)
4. Implement AJAX for smooth UX
5. Add print stylesheets for invoices
6. Implement DataTables for listing views
7. Add status badges and color coding
8. Create modal dialogs for quick actions
9. Test all workflows end-to-end
10. Optimize for mobile devices

## Notes

- All form data is validated server-side in controllers
- All calculations are double-checked in models
- Helper functions available for formatting
- Bootstrap classes used in sample views
- DataTables for large datasets
- Mobile-responsive design recommended

## Support

For view implementation help:
- Check existing VHMS views for consistent styling
- Use provided helper functions
- Refer to Bootstrap documentation
- Check controller methods for available data
- Review model methods for calculations

---

**Module Structure**: Complete ✅
**Models & Controllers**: Complete ✅  
**Views**: Ready for customization ⏳
**Database Schema**: Complete ✅
**Documentation**: Complete ✅

You can now build views based on your hospital's UI/UX guidelines while using the complete backend provided.
