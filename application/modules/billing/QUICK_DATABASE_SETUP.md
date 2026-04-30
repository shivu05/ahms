# 🔧 Billing Module - Database Setup Guide

---

## 🔴 **IMPORTANT WARNING**

**If you're getting ERROR 1005 or ERROR 1061:**

This means you used the wrong SQL file. **STOP and read this:**

1. ❌ **NEVER use `billing_module.sql` for first import** - it will fail
2. ✅ **ALWAYS use `FINAL_CLEAN_SETUP.sql` for initial setup**
3. ✅ **DELETE all billing tables first** before importing

See **SQL_FILES_GUIDE.md** for detailed explanation of which file to use.

---

## 📋 Quick Summary

You have **THREE SQL scripts** to choose from:

### ⭐ **1. FINAL_CLEAN_SETUP.sql** ✅ **USE THIS NOW** (Best choice!)

**What it does:**
- ✅ Completely disables foreign key checks
- ✅ Drops ALL existing billing tables safely
- ✅ Creates 22 fresh billing tables
- ✅ Inserts all default data
- ✅ Adds all foreign keys and indexes
- ✅ Safe to run multiple times
- ✅ **Fixes duplicate index errors**

**When to use:**
- ✅ **You're getting ERROR 1005 or ERROR 1061** (duplicate key errors)
- First time setup
- You had duplicate entry errors
- You had foreign key errors
- You want a completely fresh start

**File location:** `application/modules/billing/sql/FINAL_CLEAN_SETUP.sql`

---

### 2. **CLEAN_SETUP.sql** (Use if FINAL_CLEAN_SETUP.sql has issues)

**What it does:**
- ✅ Drops all existing billing tables (cleans up)
- ✅ Creates 22 fresh billing tables
- ✅ Inserts all default data
- ✅ Adds all foreign keys and indexes
- ✅ Safe to run multiple times

**When to use:**
- If FINAL_CLEAN_SETUP.sql fails
- You had duplicate entry errors
- You want a completely fresh start

**File location:** `application/modules/billing/sql/CLEAN_SETUP.sql`

---

### 3. **billing_module.sql** (Use this for incremental updates ONLY)

**What it does:**
- Creates tables only if they don't exist (IF NOT EXISTS)
- Won't overwrite existing data
- Adds missing indexes

**When to use:**
- **ONLY after FINAL_CLEAN_SETUP.sql is done**
- Adding new tables
- Testing incremental changes
- You want to preserve existing data

**File location:** `application/modules/billing/sql/billing_module.sql`

---

## 🚀 How to Import

### ⭐ RECOMMENDED: Using FINAL_CLEAN_SETUP.sql

#### Step 1: Delete Old Tables (If Stuck)

If you're getting errors, first clean up in phpMyAdmin:

1. Open **phpMyAdmin** at `http://localhost/phpmyadmin`
2. Select database: `vhms_rashmi_amc_2025`
3. Click **Structure** tab
4. At the bottom, click checkbox to **Select all**
5. From dropdown, select **Drop** (this removes all tables)
6. Click **Yes** to confirm

#### Step 2: Import FINAL_CLEAN_SETUP.sql

1. Open **phpMyAdmin**
2. Select your database: `vhms_rashmi_amc_2025`
3. Click **Import** tab
4. Click **Choose File**
5. Select: `application/modules/billing/sql/FINAL_CLEAN_SETUP.sql` ⭐
6. Click **Go** button
7. **Wait for completion message** (may take 30-60 seconds)

✅ **Expected result:** "22 tables created successfully"

---

### Option B: Using MySQL Command Line

#### Windows PowerShell:
```powershell
# Navigate to your XAMPP directory
cd D:\xampp\htdocs\ahms

# Run the FINAL_CLEAN_SETUP.sql
Get-Content "application\modules\billing\sql\FINAL_CLEAN_SETUP.sql" | mysql -u root -p your_password vhms_rashmi_amc_2025
```

#### Windows Command Prompt:
```cmd
cd D:\xampp\htdocs\ahms
mysql -u root -p your_password vhms_rashmi_amc_2025 < application\modules\billing\sql\FINAL_CLEAN_SETUP.sql
```

#### Linux/Mac:
```bash
cd /path/to/ahms
mysql -u root -p vhms_rashmi_amc_2025 < application/modules/billing/sql/FINAL_CLEAN_SETUP.sql
```

---

## ✅ Verification Steps

### Step 1: Check All Tables Created

In phpMyAdmin, go to **SQL** tab and run:

```sql
SHOW TABLES LIKE 'billing%';
```

**Expected:** 22 tables starting with `billing_`

### Step 2: Check Default Data

```sql
-- Payment methods (should be 7)
SELECT COUNT(*) as count FROM billing_payment_methods;

-- Service categories (should be 8)
SELECT COUNT(*) as count FROM billing_service_categories;

-- Tax configurations (should be 3)
SELECT COUNT(*) as count FROM billing_tax_configurations;

-- Billing configurations (should be 9)
SELECT COUNT(*) as count FROM billing_configurations;
```

### Step 3: Verify Foreign Keys Work

```sql
-- This should show the insurance policy foreign key
SHOW CREATE TABLE billing_invoices\G
```

---

## 🎯 What Gets Created

### Tables (22 Total)

| Category | Tables | Count |
|----------|--------|-------|
| Service Management | Categories, Services, Packages, Package Services | 4 |
| Invoice | Invoices, Invoice Items | 2 |
| Payment | Payment Methods, Payments, Deposits, Deposit Adjustments | 4 |
| Insurance | Companies, Policies, Pre-auth, Claims, Documents, Followups | 6 |
| Config & Audit | Configurations, Tax, Discounts, Credit Notes, Audit Logs | 6 |
| **TOTAL** | | **22** |

### Default Data

✅ **7 Payment Methods:**
- Cash
- Cheque
- Credit/Debit Card
- Bank Transfer/NEFT/RTGS
- UPI
- Digital Wallet
- Insurance Settlement

✅ **8 Service Categories:**
- Consultation
- Diagnostic Services
- Surgical Procedures
- Ward/Room Charges
- Pathology Tests
- Radiology/Imaging
- Pharmacy Services
- Physiotherapy

✅ **3 Tax Rates (GST):**
- 5%
- 12%
- 18%

✅ **9 Configuration Settings:**
- Invoice prefix/numbering
- Receipt prefix
- Claim prefix
- Credit note prefix
- Deposit/advance settings
- Invoice due days

---

## 🔍 Troubleshooting

### 🔴 ERROR 1005: "Can't create table (errno: 121 Duplicate key)"

**Cause:** Partial tables from failed import + duplicate indexes

**Fix:**
1. ✅ Use `FINAL_CLEAN_SETUP.sql` (does full cleanup)
2. Or manually delete all `billing_*` tables in phpMyAdmin
3. Then import `FINAL_CLEAN_SETUP.sql`

### 🔴 ERROR 1061: "Duplicate key name"

**Cause:** Indexes already exist from partial import

**Fix:**
1. Delete all `billing_*` tables in phpMyAdmin
2. Import `FINAL_CLEAN_SETUP.sql`

### 🔴 Error: "Duplicate entry for key"

**Cause:** Script was imported twice

**Fix:** Use `FINAL_CLEAN_SETUP.sql` which uses `INSERT IGNORE`

### 🔴 Error: "Foreign key constraint is incorrectly formed"

**Cause:** Table doesn't exist or column types don't match

**Fix:** 
1. Use `FINAL_CLEAN_SETUP.sql`
2. Delete all billing tables first, then reimport

### 🔴 Error: "Access denied"

**Cause:** Wrong password or user

**Fix:**
- Check your MySQL password
- Make sure you're using correct username (usually `root`)
- In command: `mysql -u root -p your_password vhms_rashmi_amc_2025`

### 🔴 Error: "Table doesn't exist"

**Cause:** Import failed silently

**Fix:**
1. Check the import status in phpMyAdmin
2. Scroll down to see any error messages
3. Try using `FINAL_CLEAN_SETUP.sql` instead

---

## 📝 File Reference

```
application/modules/billing/sql/
├── FINAL_CLEAN_SETUP.sql        ← ⭐ USE THIS (best choice!)
├── CLEAN_SETUP.sql              ← Use if FINAL fails
├── billing_module.sql           ← Use after setup
└── README.md
```

---

## 🎓 After Setup

Once tables are created:

1. ✅ Database ready
2. ⏳ Create first service
3. ⏳ Create first invoice
4. ⏳ Test payment
5. ⏳ Add insurance company
6. ⏳ Test insurance claim

See `README.md` in billing module for step-by-step examples.

---

## 💡 Tips

- **Getting ERROR 1005 or 1061?** Use `FINAL_CLEAN_SETUP.sql` ⭐
- **First time?** Use `FINAL_CLEAN_SETUP.sql` ⭐
- **Had errors?** Use `FINAL_CLEAN_SETUP.sql` ⭐
- **Testing?** Use `FINAL_CLEAN_SETUP.sql` (safe to rerun)
- **Production update?** Use `billing_module.sql` (preserves data)

---

**Status:** Ready to Setup ✅

Questions? See `DATABASE_SETUP_INSTRUCTIONS.md` for detailed troubleshooting.
