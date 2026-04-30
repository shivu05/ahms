# Billing Module Setup & Quick Start Guide

## Complete Installation Instructions

### Step 1: File Structure Verification

Ensure your module structure matches:

```
application/modules/billing/
├── controllers/
│   ├── Billing.php
│   └── Insurance.php
├── models/
│   ├── Billing_model.php
│   ├── Service_model.php
│   ├── Payment_model.php
│   └── Insurance_model.php
├── views/
│   ├── dashboard/
│   │   └── index.php
│   ├── invoices/
│   │   ├── list.php
│   │   ├── create.php
│   │   ├── view.php
│   │   └── print_invoice.php
│   ├── payments/
│   │   └── payment_form.php
│   └── insurance/
│       ├── dashboard.php
│       ├── companies.php
│       ├── policies.php
│       ├── claims.php
│       └── preauth.php
├── helpers/
│   └── Billing_helper.php
├── sql/
│   ├── billing_module.sql
│   └── INSTALLATION_GUIDE.sql
├── config.php
├── README.md
└── SETUP_GUIDE.md (this file)
```

### Step 2: Database Setup

1. Open phpMyAdmin or your MySQL client
2. Select your VHMS database
3. Run the SQL import:
   - Go to Import tab
   - Choose file: `application/modules/billing/sql/billing_module.sql`
   - Click Go

4. Verify installation:
```sql
-- Check if tables exist
SHOW TABLES LIKE 'billing%';
-- Should return 27 tables

-- Check data
SELECT COUNT(*) FROM billing_payment_methods;
-- Should return 7

SELECT COUNT(*) FROM billing_service_categories;
-- Should return 8
```

### Step 3: Configuration (Optional)

Edit `application/modules/billing/config.php` to customize:
- Invoice numbering prefix and start number
- Payment receipt prefix
- GST/Tax rates
- Insurance settings
- Payment methods

### Step 4: Access the Module

1. Log in to your VHMS application
2. Navigate to: `http://your-domain/billing`
3. You should see the Billing Dashboard

## Quick Start Workflow

### Creating Your First Invoice

1. **Go to Dashboard**
   - Click "Create Invoice" button
   - Select Invoice Type (OPD/IPD/Emergency/Pharmacy)
   - Select Patient
   - Click "Create"

2. **Add Services to Invoice**
   - Click "Add Item"
   - Select Service Category
   - Select Service
   - Enter Quantity (if applicable)
   - Quantity and Discount automatically calculated
   - Click "Add"

3. **Review Invoice**
   - Check all items and calculations
   - Verify GST is correctly applied
   - Review total amount

4. **Finalize Invoice**
   - Click "Issue Invoice" button
   - Invoice status changes to ISSUED
   - Patient can now be asked for payment

### Recording Payment

1. **Go to Invoice**
   - Click "View" on any issued invoice
   - Click "Record Payment" button

2. **Enter Payment Details**
   - Select Payment Method (Cash/Cheque/Card/etc)
   - Enter Payment Amount (cannot exceed balance)
   - Add Reference Number (if required by method)
   - Click "Process Payment"

3. **Verify Payment**
   - Payment is recorded immediately
   - Invoice status updates automatically
   - Receipt can be generated

### Insurance Claims (New!)

#### Setup Insurance Policy

1. **Add Insurance Company** (First time only)
   - Go to: Billing → Insurance → Companies
   - Click "Add Company"
   - Enter company details
   - Save

2. **Add Insurance Policy**
   - Go to: Billing → Insurance → Policies
   - Click "Add Policy"
   - Select Company
   - Enter Policy Number and Details
   - Set Coverage Limit
   - Set Deductible Amount (if any)
   - Set Co-payment % (if any)
   - Save

#### Submit Insurance Claim

1. **Create Claim from Invoice**
   - Open the invoice
   - Click "Create Insurance Claim"
   - Select Patient's Insurance Policy
   - Review calculated settlement:
     - Deductible to be paid by patient
     - Co-payment percentage
     - Insurance to pay amount
   - Click "Create Claim"

2. **Attach Documents**
   - Click "Add Document"
   - Select Document Type (Invoice/Prescription/Lab Report/etc)
   - Upload file
   - Repeat for all required documents

3. **Submit to Insurance**
   - Click "Submit Claim"
   - Add remarks if needed
   - Click "Submit"
   - Status changes to SUBMITTED

4. **Follow Up**
   - Click "Add Follow-up"
   - Select Follow-up Type (Call/Email/Letter)
   - Enter Notes
   - Set next follow-up date

5. **Approve Claim** (When insurance approves)
   - Click "Approve Claim"
   - Enter Approved Amount
   - Enter Insurance Reference Number
   - Click "Approve"
   - Amount automatically credited to patient account

## Testing Scenarios

### Scenario 1: Simple OPD Billing

```
1. Create OPD Invoice
   - OPD No: 1001
   - Patient: John Doe
   
2. Add Services
   - Consultation: ₹500 (GST: ₹25)
   - X-Ray: ₹800 (GST: ₹40)
   
3. Expected Invoice Total: ₹1,365

4. Record Payment
   - Method: Cash
   - Amount: ₹1,365
   - Status should change to PAID
```

### Scenario 2: IPD Billing with Insurance

```
1. Create IPD Invoice
   - IPD No: 5001
   - Ward Charges: ₹50,000
   - Surgery: ₹30,000
   - Medicines: ₹5,000
   - Total: ₹85,000

2. Create Insurance Claim
   - Policy: ICICI Health
   - Coverage Limit: ₹100,000
   - Deductible: ₹5,000
   - Co-payment: 10%
   
   Settlement Calculation:
   - Invoice: ₹85,000
   - Less Deductible: ₹5,000 = ₹80,000
   - Less Co-payment (10%): ₹8,000 = ₹72,000
   - Insurance pays: ₹72,000

3. Submit Claim
   - Add discharge summary, medical reports
   - Submit for processing

4. When Approved
   - Insurance approves ₹72,000
   - Patient pays: ₹5,000 (deductible) + ₹8,000 (co-pay) = ₹13,000
```

### Scenario 3: Advance Deposit

```
1. Patient Deposits Advance
   - Go to: Payment → Record Deposit
   - Amount: ₹10,000
   - Payment Method: Cash
   - Deposit created

2. Create Invoice
   - Services total: ₹8,500
   - Create invoice

3. Adjust Deposit
   - Click "Use Deposit"
   - Select amount: ₹8,500
   - Balance remaining: ₹1,500
   - Invoice marked as PAID

4. Refund Remaining
   - Refund ₹1,500 to patient
```

## Common Operations

### Viewing Payment History

```
Invoice View → "Payments" Tab
Shows all payments made against invoice with:
- Payment Date
- Amount
- Method
- Reference Number
- Receipt Number
```

### Generating Reports

**Daily Collection Report:**
- Go to: Reports → Daily Collections
- Select date
- View collection by payment method

**Insurance Claims Report:**
- Go to: Reports → Insurance Claims
- Select date range
- View status summary and amounts

**Patient Billing Summary:**
- Go to: Patient History
- Select patient
- View all invoices and balances

### Cancelling Invoice

1. Open Invoice
2. Click "More Options" → "Cancel"
3. Enter cancellation reason
4. Confirm
5. Invoice status: CANCELLED

### Issuing Credit Note

1. Open paid/issued invoice
2. Click "More Options" → "Issue Credit Note"
3. Enter reason (Return/Adjustment/Discount/Error)
4. Enter credit amount
5. Approval flow (if configured)
6. Credit note generated

## Validation Rules Enforced

### Invoice Creation
- ✓ Invoice type required
- ✓ Patient must exist
- ✓ At least one item required
- ✓ Total must match line items + GST - discount

### Payment Processing
- ✓ Payment amount > 0
- ✓ Payment amount ≤ balance due
- ✓ Payment method required
- ✓ Reference required for cheque/card/transfer

### Insurance Claims
- ✓ Policy must be active (not expired)
- ✓ Claim amount ≤ invoice amount
- ✓ Deductible and co-payment calculated
- ✓ Coverage limit enforced
- ✓ Documents required before submission

## Troubleshooting

### Issue: "Billing module tables not found"
**Solution:**
- Verify database import completed successfully
- Check tables exist: `SHOW TABLES LIKE 'billing%'`
- Ensure user has CREATE TABLE permission

### Issue: Invoice total calculation incorrect
**Solution:**
- Verify GST rate in service master
- Check discount is applied correctly
- Recalculate using: Item Amount + GST - Discount

### Issue: Payment not updating invoice status
**Solution:**
- Verify payment amount matches calculation
- Check invoice is in correct status
- Ensure payment method exists in database

### Issue: Cannot create insurance claim
**Solution:**
- Verify policy is active (not expired)
- Check policy is linked to correct company
- Ensure invoice is issued (not draft)

### Issue: Insurance claim approval not working
**Solution:**
- Check claim is in UNDER_PROCESS status
- Verify approved amount ≤ coverage limit
- Ensure all required fields are filled

## Performance Optimization

### Database Indexes
Already created for:
- Invoice date, status, patient
- Payment invoice and date
- Claim status and date
- Policy and company

### Recommended Backups
- Daily: Invoice and payment tables
- Weekly: Full billing database
- Monthly: Archive old records

### Data Retention
- Keep invoices for 7 years (per audit requirements)
- Archive paid invoices after 2 years
- Keep claims indefinitely

## Next Steps

1. **Configure Insurance Companies**
   - Add your partner insurance companies
   - Set up contact information

2. **Setup Service Master**
   - Add all your medical services
   - Set pricing and GST rates

3. **Train Staff**
   - Invoice creation workflow
   - Payment processing
   - Insurance claims submission

4. **Customize Branding**
   - Set hospital logo in invoices
   - Configure invoice header/footer
   - Set payment terms and conditions

5. **Integrate with Other Modules**
   - Link with OPD/IPD patient records
   - Integrate with pharmacy billing
   - Connect with lab billing

## Support

- **Module Location**: `application/modules/billing/`
- **Documentation**: `README.md` in module folder
- **Sample Data**: See `INSTALLATION_GUIDE.sql`
- **Configuration**: Edit `config.php`

For advanced customization or issues, refer to the main README.md file.

---

**Module Version**: 1.0  
**Last Updated**: December 2025  
**Compatibility**: CodeIgniter 3.x + HMVC Pattern
