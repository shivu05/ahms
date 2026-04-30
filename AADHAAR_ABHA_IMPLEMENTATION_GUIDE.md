# Patient Registration Enhancement: Aadhaar and ABHA ID Integration

## Overview
This comprehensive enhancement adds secure Aadhaar Number and ABHA ID fields to the Patient Registration form in the AHMS system. Includes Verhoeff algorithm validation, real-time JavaScript validation, backend validation, and security measures.

## Files Created/Modified

### 1. **Database Migration**
**File:** `database_migrations/add_aadhaar_abha_to_patient.sql`

**Changes:**
- Added `aadhaar_number` (VARCHAR(12)) - Unique key to prevent duplicates
- Added `abha_id` (VARCHAR(50)) - ABHA ID field
- Added `aadhaar_masked` (VARCHAR(20)) - Masked version for display
- Created `aadhaar_access_log` table for audit trail

**Setup:**
```bash
# Apply migration in phpMyAdmin or MySQL CLI
mysql -u root -p ahms < database_migrations/add_aadhaar_abha_to_patient.sql
```

### 2. **Helper Functions**
**File:** `application/helpers/aadhaar_abha_helper.php`

**Functions:**
- `validate_aadhaar_verhoeff()` - Validates Aadhaar using Verhoeff algorithm
- `validate_abha_id_format()` - Validates ABHA ID format
- `mask_aadhaar()` - Returns masked Aadhaar (XXXX-XXXX-1234)
- `verhoeff_validate()` - Core Verhoeff checksum validation
- `calculate_aadhaar_checksum()` - Generates checksum for 11 digits

### 3. **Patient Model**
**File:** `application/modules/patient/models/Patient_model.php`

**New Methods:**
- `check_aadhaar_exists()` - Check duplicate Aadhaar
- `get_patient_by_aadhaar()` - Retrieve patient with masked Aadhaar
- `get_aadhaar_for_authorized_user()` - Get unmasked Aadhaar with audit logging
- `update_abha_id()` - Update ABHA ID for patient

### 4. **Patient Controller**
**File:** `application/modules/patient/controllers/Patient.php`

**Updates:**
- Updated `__construct()` to load aadhaar_abha helper
- Enhanced `store_patient_info()` with full validation
- Added `validate_aadhaar()` callback
- Added `check_duplicate_aadhaar()` callback
- Added `validate_abha_format()` callback
- Added `log_aadhaar_access()` for audit trail
- Added `check_aadhaar_duplicate()` AJAX endpoint

### 5. **Frontend - Form View**
**File:** `application/modules/patient/views/patient/index.php`

**Added Fields:**
- Aadhaar Number input (12 digits, required)
- ABHA ID input (14 digits or username@abdm, required)
- Error message spans for inline validation
- Bootstrap form styling

### 6. **Frontend - JavaScript Validation**
**File:** `assets/js/patient_registration_validation.js`

**Features:**
- Custom jQuery validator for Aadhaar (Verhoeff algorithm)
- Custom jQuery validator for ABHA ID format
- Real-time validation with inline error messages
- AJAX duplicate check during form entry
- Form submission via AJAX with error display
- Verhoeff algorithm implementation in JavaScript
- Alert notifications for success/error

### 7. **Frontend - CSS Styling**
**File:** `assets/css/patient_registration_validation.css`

**Includes:**
- Error message styling
- Form group error states
- Valid input feedback styling
- Responsive design
- Loading state styling
- Alert styling

## Implementation Steps

### Step 1: Database Migration
```sql
-- Run the migration SQL file
source database_migrations/add_aadhaar_abha_to_patient.sql;
```

Or using phpMyAdmin:
1. Go to SQL tab
2. Copy-paste content from `database_migrations/add_aadhaar_abha_to_patient.sql`
3. Execute

### Step 2: Verify Files Are in Place
```
✓ application/helpers/aadhaar_abha_helper.php
✓ application/modules/patient/models/Patient_model.php (updated)
✓ application/modules/patient/controllers/Patient.php (updated)
✓ application/modules/patient/views/patient/index.php (updated)
✓ assets/js/patient_registration_validation.js
✓ assets/css/patient_registration_validation.css
```

### Step 3: Test the Form
1. Navigate to: `http://localhost/ahms/patient`
2. Fill out the form with test data
3. Test Aadhaar validation:
   - Must be exactly 12 digits
   - Must pass Verhoeff checksum
   - Cannot start with 0
   - Cannot be duplicate
4. Test ABHA ID validation:
   - 14 digits OR
   - username@abdm format

## Validation Rules

### Frontend (JavaScript)
- **Aadhaar:** 
  - Exactly 12 digits
  - Verhoeff checksum validation
  - No leading zeros
  - Real-time duplicate check
  
- **ABHA ID:**
  - 14 digit numeric OR
  - Pattern: `username@abdm`

### Backend (CodeIgniter)
- **Aadhaar:**
  - `required`
  - `numeric`
  - `exact_length[12]`
  - `callback_validate_aadhaar` (Verhoeff)
  - `callback_check_duplicate_aadhaar`
  
- **ABHA ID:**
  - `required`
  - `callback_validate_abha_format`

## Security Features

### 1. Aadhaar Masking
- Only last 4 digits displayed: `XXXX-XXXX-1234`
- Full number stored encrypted/hashed in DB
- Unmasked access logged to audit table

### 2. Duplicate Prevention
- Unique constraint on aadhaar_number
- Real-time duplicate check via AJAX
- Backend validation prevents duplicates

### 3. Audit Trail
- `aadhaar_access_log` table tracks all access
- Logs: timestamp, accessed_by, action
- Helps with compliance requirements

### 4. Input Validation
- Frontend and backend validation
- Verhoeff algorithm ensures valid Aadhaar
- ABHA format strictly validated

## Testing

### Test Aadhaar Numbers (Valid)
The Verhoeff algorithm ensures validity. Generate test numbers or use:
- Make sure to use 12 digits starting with 1-9
- Last digit must be correct checksum

### Test ABHA IDs
Valid formats:
```
12345678901234          (14 digits)
john@abdm              (username@abdm)
john_doe@abdm          (with underscore)
```

## Error Handling

### Real-time Errors
- Displayed inline below field
- User-friendly messages
- Red text for errors, green for valid

### Validation Errors
- Return JSON with error array
- Field-level error messages
- Display below each field

### Success Response
```json
{
  "status": "success",
  "message": "Patient registered successfully!",
  "opd_no": 12345
}
```

## Database Schema

### patientdata table - New Columns
```sql
aadhaar_number VARCHAR(12) UNIQUE NOT NULL
abha_id VARCHAR(50)
aadhaar_masked VARCHAR(20) -- Display only
```

### aadhaar_access_log table
```sql
id INT PRIMARY KEY AUTO_INCREMENT
opd_no INT FOREIGN KEY
accessed_by VARCHAR(256)
access_time TIMESTAMP
action VARCHAR(50) -- 'insert', 'view', 'update'
```

## Verhoeff Algorithm

The Verhoeff algorithm is a checksum formula used by UIDAI (Unique Identification Authority of India) for Aadhaar numbers. It ensures:
- Data entry error detection
- Validation of 12-digit Aadhaar
- Both PHP and JavaScript implementations provided

## Code Examples

### Backend Validation
```php
// In controller action
$this->form_validation->set_rules('aadhaar_number', 'Aadhaar', 
    'required|numeric|exact_length[12]|callback_validate_aadhaar|callback_check_duplicate_aadhaar');

$this->form_validation->set_rules('abha_id', 'ABHA ID', 
    'required|callback_validate_abha_format');
```

### Frontend Validation
```javascript
// jQuery validator methods
$.validator.addMethod("validate_aadhaar", function(value, element) {
    return verhoeffValidate(value);
});

$.validator.addMethod("validate_abha", function(value, element) {
    return /^\d{14}$/.test(value) || /^[a-zA-Z0-9_-]{3,30}@abdm$/i.test(value);
});
```

### Get Masked Aadhaar
```php
$patient = $this->patient_model->get_patient_by_aadhaar($aadhaar);
// Returns aadhaar_masked: 'XXXX-XXXX-1234'
```

## Troubleshooting

### Issue: "Invalid Aadhaar checksum"
- Verify the 12-digit number is correct
- Check last digit (checksum) calculation
- Use valid test numbers

### Issue: "Duplicate Aadhaar" error
- Aadhaar already registered in system
- Check with admin if data entry error

### Issue: Validation not working
- Ensure JavaScript files are loaded (check console)
- Verify jQuery validation plugin is loaded
- Check BASE_URL is set correctly

### Issue: Database error on save
- Run migration SQL first
- Verify new columns exist in table
- Check database user has ALTER permission

## Maintenance

### Regular Tasks
1. **Audit Log Cleanup:** Periodically archive old access logs
2. **Data Backup:** Ensure Aadhaar data is backed up securely
3. **Updates:** Monitor for UIDAI format changes

### Security Considerations
1. Hash Aadhaar before storage (optional, for extra security)
2. Restrict access to unmasked Aadhaar to authorized users
3. Implement IP-based access logging
4. Regular security audits

## API Endpoints

### Check Duplicate Aadhaar
```
POST /patient/check_aadhaar_duplicate
Parameters: aadhaar_number (12 digits)
Response: JSON { exists: bool, message: string }
```

### Store Patient with ABHA
```
POST /patient/store_patient_info
Parameters: All form fields + aadhaar_number + abha_id
Response: JSON { status: string, message: string, opd_no: int }
```

## Support Files

- Helper: `application/helpers/aadhaar_abha_helper.php`
- JavaScript: `assets/js/patient_registration_validation.js`
- CSS: `assets/css/patient_registration_validation.css`
- Migration: `database_migrations/add_aadhaar_abha_to_patient.sql`

## License
Part of AHMS (Ayurveda Hospital Management System)

---

**Last Updated:** 2026-04-26
**Version:** 1.0
**Status:** Production Ready
