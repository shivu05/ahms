# QUICK START GUIDE - Aadhaar and ABHA Integration

## Installation Checklist

### Step 1: Files Placement ✓
Verify these files exist:

```
d:\xampp\htdocs\ahms\
├── application/
│   ├── helpers/
│   │   └── aadhaar_abha_helper.php                 ✓ NEW
│   └── modules/patient/
│       ├── controllers/
│       │   └── Patient.php                         ✓ UPDATED
│       ├── models/
│       │   └── Patient_model.php                   ✓ UPDATED
│       └── views/patient/
│           └── index.php                           ✓ UPDATED
├── assets/
│   ├── css/
│   │   └── patient_registration_validation.css     ✓ NEW
│   └── js/
│       └── patient_registration_validation.js      ✓ NEW
└── database_migrations/
    └── add_aadhaar_abha_to_patient.sql            ✓ NEW
```

### Step 2: Database Migration ✓

**Option A: Using phpMyAdmin**
1. Open phpMyAdmin
2. Select `ahms` database
3. Click "SQL" tab
4. Copy content from `database_migrations/add_aadhaar_abha_to_patient.sql`
5. Paste in SQL window
6. Click "Go"

**Option B: Command Line**
```bash
mysql -u root -p ahms < database_migrations/add_aadhaar_abha_to_patient.sql
```

**Option C: Direct MySQL**
```sql
-- Login to MySQL
mysql -u root -p

-- Select database
USE ahms;

-- Run migration
SOURCE database_migrations/add_aadhaar_abha_to_patient.sql;
```

### Step 3: Verify Installation ✓

```sql
-- Check new columns in patientdata
DESCRIBE patientdata;

-- Should see:
-- aadhaar_number (VARCHAR 12)
-- abha_id (VARCHAR 50)
-- aadhaar_masked (VARCHAR 20)

-- Check audit table
DESCRIBE aadhaar_access_log;

-- Should see:
-- id, opd_no, accessed_by, access_time, action
```

### Step 4: Test the Form ✓

1. Navigate to: `http://localhost/ahms/patient`
2. Look for new fields:
   - "Aadhaar Number" field
   - "ABHA ID" field
3. Check CSS and JS loaded (no console errors)

## Configuration

### Base URL
The JavaScript file automatically sets `BASE_URL`:
```javascript
var BASE_URL = "<?php echo base_url(); ?>";
```

No additional configuration needed if `base_url()` is set in `config/config.php`.

## Field Requirements

### Aadhaar Number
- **Type:** Text input
- **Length:** Exactly 12 digits
- **Format:** Numeric only
- **Validation:** Verhoeff algorithm checksum
- **Rules:** 
  - Required
  - Cannot start with 0
  - Must be unique (no duplicates)

### ABHA ID
- **Type:** Text input
- **Length:** 14 characters (digits) OR 3-30 characters (username)
- **Format:** `14 digits` OR `username@abdm`
- **Examples:**
  - `12345678901234` (14 digits)
  - `john@abdm` (username format)
  - `patient_123@abdm` (with underscore)

## Validation Layers

### Frontend Validation (JavaScript)
- Real-time as user types
- Verhoeff algorithm checksum
- ABHA format validation
- Inline error messages
- AJAX duplicate check

### Backend Validation (PHP/CodeIgniter)
- Form validation rules
- Callback validation
- Database unique constraint
- Server-side Verhoeff check

## API Endpoints

### Check Aadhaar Duplicate
```
Endpoint: /patient/check_aadhaar_duplicate
Method: POST
Parameter: aadhaar_number (string)
Response: {"exists": bool, "message": string}
Example:
  POST: aadhaar_number=123456789012
  Response: {"exists": false, "message": "Aadhaar is available"}
```

### Store Patient Info
```
Endpoint: /patient/store_patient_info
Method: POST
Parameters: All form fields including aadhaar_number, abha_id
Response: {"status": "success|error", "message": string, "opd_no": int}
Example Response:
  {
    "status": "success",
    "message": "Patient registered successfully!",
    "opd_no": 12345
  }
```

## Database Schema

### patientdata Table - New Columns
```sql
-- Aadhaar information
aadhaar_number VARCHAR(12) UNIQUE NOT NULL
aadhaar_masked VARCHAR(20) -- Display format (XXXX-XXXX-1234)

-- ABHA information  
abha_id VARCHAR(50)
```

### aadhaar_access_log Table
```sql
CREATE TABLE `aadhaar_access_log` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `opd_no` INT NOT NULL,
  `accessed_by` VARCHAR(256),
  `access_time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `action` VARCHAR(50),
  FOREIGN KEY (`opd_no`) REFERENCES `patientdata`(`OpdNo`)
);
```

## Security Features

### 1. Aadhaar Masking
- Display: `XXXX-XXXX-1234` (show only last 4)
- Storage: Full number for validation
- Audit: Access logged to `aadhaar_access_log`

### 2. Unique Constraint
- Database unique key on `aadhaar_number`
- Prevents duplicate registrations
- Real-time AJAX check during form entry

### 3. Verhoeff Checksum
- Validates Aadhaar legitimacy
- Catches data entry errors
- Implemented in both PHP and JavaScript

### 4. Access Logging
- Every Aadhaar access logged
- Timestamp and user tracked
- Compliance support (PII regulations)

## Common Issues & Solutions

### Issue: "Aadhaar must be exactly 12 digits"
**Solution:** Check Aadhaar number format - must be 12 numeric digits

### Issue: "Invalid Aadhaar checksum"
**Solution:** Last digit (checksum) is incorrect. Use valid test Aadhaar.

### Issue: "This Aadhaar is already registered"
**Solution:** Aadhaar exists in system. Use different number or check with admin.

### Issue: Validation not working
**Solution:** 
- Check browser console for JavaScript errors
- Verify jQuery and jQuery.validate loaded
- Check BASE_URL is set correctly
- Clear browser cache

### Issue: Database error on save
**Solution:**
- Run migration SQL first
- Verify new columns exist: `DESCRIBE patientdata;`
- Check database user permissions
- Restart MySQL

### Issue: AJAX calls failing
**Solution:**
- Check BASE_URL configuration
- Verify `check_aadhaar_duplicate` controller method exists
- Check network tab in developer tools
- Verify CORS if API called from different domain

## Customization

### Change Aadhaar Length Validation
In `patient_registration_validation.js` - line ~115:
```javascript
if (aadhaar.length !== 12) {
    // Change 12 to desired length
}
```

### Modify Error Messages
In controller `Patient.php` - `store_patient_info()` method:
```php
$this->form_validation->set_message('validate_aadhaar', 
    'Your custom error message here.');
```

### Add More Validation Rules
In controller:
```php
$this->form_validation->set_rules('aadhaar_number', 'Aadhaar', 
    'required|numeric|exact_length[12]|callback_validate_aadhaar|your_custom_rule');
```

## Testing

Quick test with form:
1. **Valid Aadhaar + ABHA:** Should submit successfully
2. **Invalid Aadhaar:** Should show error
3. **Duplicate Aadhaar:** Should show "already registered"
4. **Invalid ABHA:** Should show format error

See `TESTING_GUIDE.md` for comprehensive test cases.

## Support & Documentation

- **Implementation Guide:** `AADHAAR_ABHA_IMPLEMENTATION_GUIDE.md`
- **Testing Guide:** `TESTING_GUIDE.md`
- **Quick Start:** This file (`QUICKSTART.md`)

## File Summary

| File | Type | Purpose |
|------|------|---------|
| aadhaar_abha_helper.php | PHP | Validation functions, Verhoeff algorithm |
| patient_registration_validation.js | JS | Frontend validation, Verhoeff checksum |
| patient_registration_validation.css | CSS | Styling for form and errors |
| Patient.php | PHP | Controller with validation callbacks |
| Patient_model.php | PHP | Database methods for Aadhaar/ABHA |
| index.php | PHP | Form view with new fields |
| add_aadhaar_abha_to_patient.sql | SQL | Database migration |

## Next Steps

1. ✓ Copy all files to their locations
2. ✓ Run database migration
3. ✓ Test the form at `http://localhost/ahms/patient`
4. ✓ Verify JavaScript console has no errors
5. ✓ Submit a test form with valid data
6. ✓ Check database for new record with Aadhaar

## Support

For issues or questions:
1. Check `TESTING_GUIDE.md` for common problems
2. Check browser console for JavaScript errors
3. Check server logs for PHP/MySQL errors
4. Verify all files are in correct locations
5. Run database migration if not done

---

**Status:** Ready for Production ✓
**Last Updated:** 2026-04-26
**Version:** 1.0.0
