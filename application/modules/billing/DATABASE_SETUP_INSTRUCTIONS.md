# Database Setup Instructions for Billing Module

## Quick Setup (Recommended)

### Step 1: Use Clean Setup Script (If you have duplicate errors)

If you're seeing duplicate entry errors, use the **CLEAN_SETUP.sql** script which:
- Drops all existing billing tables
- Creates fresh tables
- Inserts default data

**In phpMyAdmin:**
1. Open your database `vhms_rashmi_amc_2025`
2. Click **Import**
3. Choose file: `application/modules/billing/sql/CLEAN_SETUP.sql`
4. Click **Go** (Import)
5. Wait for completion (should see: "22 tables created" message)

**From Command Line (with password):**
```bash
mysql -u root -p your_password vhms_rashmi_amc_2025 < "application/modules/billing/sql/CLEAN_SETUP.sql"
```

### Step 2: Verify Tables Created

Run this query in phpMyAdmin (SQL tab) or command line:

```sql
SHOW TABLES LIKE 'billing%';
```

**Expected Output:** 22 tables
```
billing_service_categories
billing_services
billing_service_packages
billing_package_services
billing_invoices
billing_invoice_items
billing_payment_methods
billing_payments
billing_deposits
billing_deposit_adjustments
billing_insurance_companies
billing_insurance_policies
billing_insurance_preauth
billing_insurance_claims
billing_claim_documents
billing_claim_followups
billing_credit_notes
billing_configurations
billing_tax_configurations
billing_discounts
billing_audit_logs
```

### Step 3: Verify Default Data

Run these queries to confirm default data was inserted:

```sql
-- Should show 7 payment methods
SELECT COUNT(*) as payment_methods FROM billing_payment_methods;

-- Should show 8 service categories
SELECT COUNT(*) as service_categories FROM billing_service_categories;

-- Should show 3 tax configurations
SELECT COUNT(*) as tax_configs FROM billing_tax_configurations;

-- Should show 9 billing configurations
SELECT COUNT(*) as billing_configs FROM billing_configurations;
```

---

## Troubleshooting

### Issue 1: "Duplicate entry for key" Error

**Cause:** Script was run multiple times

**Solution:**
1. Use **CLEAN_SETUP.sql** instead (it drops and recreates)
2. OR manually delete the duplicate rows:

```sql
-- Delete duplicate billing tables
DROP TABLE IF EXISTS `billing_claim_followups`;
DROP TABLE IF EXISTS `billing_claim_documents`;
DROP TABLE IF EXISTS `billing_insurance_claims`;
DROP TABLE IF EXISTS `billing_insurance_preauth`;
DROP TABLE IF EXISTS `billing_insurance_policies`;
DROP TABLE IF EXISTS `billing_insurance_companies`;
DROP TABLE IF EXISTS `billing_credit_notes`;
DROP TABLE IF EXISTS `billing_audit_logs`;
DROP TABLE IF EXISTS `billing_discounts`;
DROP TABLE IF EXISTS `billing_tax_configurations`;
DROP TABLE IF EXISTS `billing_configurations`;
DROP TABLE IF EXISTS `billing_deposit_adjustments`;
DROP TABLE IF EXISTS `billing_deposits`;
DROP TABLE IF EXISTS `billing_payments`;
DROP TABLE IF EXISTS `billing_payment_methods`;
DROP TABLE IF EXISTS `billing_invoice_items`;
DROP TABLE IF EXISTS `billing_invoices`;
DROP TABLE IF EXISTS `billing_package_services`;
DROP TABLE IF EXISTS `billing_service_packages`;
DROP TABLE IF EXISTS `billing_services`;
DROP TABLE IF EXISTS `billing_service_categories`;

-- Then re-import CLEAN_SETUP.sql
```

### Issue 2: "Foreign key constraint is incorrectly formed"

**Cause:** Table being referenced doesn't exist or has wrong column type

**Solution:** Use **CLEAN_SETUP.sql** which ensures proper table creation order

### Issue 3: MySQL Connection Error

**Cause:** Incorrect password or username

**Command Line Example (with password):**
```bash
mysql -u root -p "your_password" vhms_rashmi_amc_2025 < "path/to/CLEAN_SETUP.sql"
```

**Windows Command Line:**
```powershell
Get-Content "application\modules\billing\sql\CLEAN_SETUP.sql" | mysql -u root -p your_password vhms_rashmi_amc_2025
```

---

## File Information

| File | Purpose | Action |
|------|---------|--------|
| **CLEAN_SETUP.sql** | Complete fresh setup (drops & recreates) | ✅ **Use this first** |
| **billing_module.sql** | Incremental setup (creates if not exists) | Use for adding new tables |

---

## What Gets Created

### Database Tables (22 total)

**Service Management (4 tables)**
- `billing_service_categories` - Service groups/categories
- `billing_services` - Individual services and procedures
- `billing_service_packages` - Bundled services
- `billing_package_services` - Package line items

**Invoice Management (2 tables)**
- `billing_invoices` - Master invoices
- `billing_invoice_items` - Invoice line items

**Payment Processing (4 tables)**
- `billing_payment_methods` - 7 payment methods (Cash, Cheque, Card, etc.)
- `billing_payments` - Payment records
- `billing_deposits` - Advance deposits
- `billing_deposit_adjustments` - Deposit adjustments against invoices

**Insurance Management (6 tables)**
- `billing_insurance_companies` - Insurance company master
- `billing_insurance_policies` - Insurance policies
- `billing_insurance_preauth` - Pre-authorization requests
- `billing_insurance_claims` - Insurance claims
- `billing_claim_documents` - Claim attachments
- `billing_claim_followups` - Claim follow-up tracking

**Configuration & Audit (6 tables)**
- `billing_configurations` - Module settings
- `billing_tax_configurations` - GST/Tax rates
- `billing_discounts` - Discount master
- `billing_credit_notes` - Credit notes and refunds
- `billing_audit_logs` - Complete audit trail

### Default Data Inserted

✅ **7 Payment Methods**
- Cash, Cheque, Card, Bank Transfer, UPI, Digital Wallet, Insurance

✅ **8 Service Categories**
- Consultation, Diagnostic, Procedure, Ward, Pathology, Radiology, Pharmacy, Physiotherapy

✅ **3 GST Rates**
- 5%, 12%, 18%

✅ **9 Configuration Settings**
- Invoice prefix, numbering, receipt prefix, claim prefix, etc.

---

## Next Steps After Setup

1. ✅ Import database (CLEAN_SETUP.sql)
2. ✅ Verify 22 tables created
3. ⏳ Add insurance companies (via module UI or SQL)
4. ⏳ Add services/pricing (via module UI or SQL)
5. ⏳ Test invoice creation
6. ⏳ Test payment processing
7. ⏳ Test insurance claims

---

## Verification Checklist

After importing, verify:

- [ ] 22 billing tables exist: `SHOW TABLES LIKE 'billing%';`
- [ ] Payment methods: `SELECT * FROM billing_payment_methods;` (7 rows)
- [ ] Service categories: `SELECT * FROM billing_service_categories;` (8 rows)
- [ ] Tax rates: `SELECT * FROM billing_tax_configurations;` (3 rows)
- [ ] Config settings: `SELECT * FROM billing_configurations;` (9 rows)
- [ ] Foreign keys work: Try creating an invoice
- [ ] Module accessible: Navigate to `/billing` in browser

---

## Support

For issues:
1. Check the **Troubleshooting** section above
2. Review `CLEAN_SETUP.sql` comments for each table
3. Check phpMyAdmin **Structure** tab for table details
4. Verify user permissions: `SHOW GRANTS FOR 'root'@'localhost';`

---

**Status:** ✅ Ready for Setup
