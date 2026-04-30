# Testing Guide - Aadhaar and ABHA Integration

## Pre-Testing Checklist

- [ ] Database migration has been applied
- [ ] All files are in place (see AADHAAR_ABHA_IMPLEMENTATION_GUIDE.md)
- [ ] Apache/Nginx server is running
- [ ] MySQL database is accessible
- [ ] jQuery and jQuery Validation plugin are loaded
- [ ] Browser developer console is clear of errors

## Testing Scenarios

### 1. Aadhaar Validation Tests

#### Test Case 1.1: Valid Aadhaar Format
- **Input:** 123456789012 (valid 12-digit number with correct checksum)
- **Expected:** ✓ Field validation passes
- **Note:** Must have valid Verhoeff checksum

#### Test Case 1.2: Invalid Format - Too Few Digits
- **Input:** 12345678901 (11 digits)
- **Expected:** Error: "Aadhaar must be exactly 12 digits"
- **Timing:** Triggers on blur or after keyup

#### Test Case 1.3: Invalid Format - Too Many Digits
- **Input:** 1234567890123 (13 digits)
- **Expected:** Error: "Aadhaar must be exactly 12 digits"

#### Test Case 1.4: Invalid Format - Non-Numeric
- **Input:** 12345678901A
- **Expected:** Error: "Aadhaar must contain only digits"

#### Test Case 1.5: Invalid Format - Starts with 0
- **Input:** 012345678901
- **Expected:** Error: "Aadhaar cannot start with 0"

#### Test Case 1.6: Invalid Checksum
- **Input:** 123456789010 (last digit wrong)
- **Expected:** Error: "Invalid Aadhaar checksum"

#### Test Case 1.7: Duplicate Aadhaar
- **Input:** Previously registered Aadhaar
- **Expected:** Error: "This Aadhaar number is already registered"
- **Action:** AJAX check runs automatically

#### Test Case 1.8: Spaces and Formatting
- **Input:** 1234 5678 9012 (with spaces)
- **Expected:** ✓ Spaces removed, validation passes

### 2. ABHA ID Validation Tests

#### Test Case 2.1: Valid ABHA - 14 Digits
- **Input:** 12345678901234
- **Expected:** ✓ Field validation passes

#### Test Case 2.2: Valid ABHA - Username@abdm
- **Input:** john@abdm
- **Expected:** ✓ Field validation passes

#### Test Case 2.3: Valid ABHA - Username with Underscore
- **Input:** john_doe@abdm
- **Expected:** ✓ Field validation passes

#### Test Case 2.4: Valid ABHA - Username with Hyphen
- **Input:** john-doe@abdm
- **Expected:** ✓ Field validation passes

#### Test Case 2.5: Invalid ABHA - Wrong Domain
- **Input:** john@gmail.com
- **Expected:** Error: "ABHA ID must be 14 digits or username@abdm format"

#### Test Case 2.6: Invalid ABHA - Username Too Short
- **Input:** ab@abdm
- **Expected:** Error: "ABHA ID must be 14 digits or username@abdm format"

#### Test Case 2.7: Invalid ABHA - Username Too Long
- **Input:** 123456789012345678901234567890123@abdm (31+ chars)
- **Expected:** Error: "ABHA ID must be 14 digits or username@abdm format"

#### Test Case 2.8: Invalid ABHA - Special Characters
- **Input:** john!@abdm
- **Expected:** Error: "ABHA ID must be 14 digits or username@abdm format"

#### Test Case 2.9: Invalid ABHA - Incomplete Digits
- **Input:** 1234567890123 (13 digits)
- **Expected:** Error: "ABHA ID must be 14 digits or username@abdm format"

### 3. Form Submission Tests

#### Test Case 3.1: Complete Valid Form
- **Steps:**
  1. Fill all required fields correctly
  2. Enter valid Aadhaar
  3. Enter valid ABHA ID
  4. Click Register
- **Expected:** 
  - Success message: "Patient registered successfully!"
  - Redirect to patient list
  - OPD number displayed

#### Test Case 3.2: Missing Required Fields
- **Steps:**
  1. Leave Aadhaar empty
  2. Click Register
- **Expected:** Error: "Aadhaar Number field is required"

#### Test Case 3.3: Invalid Data + Form Submission
- **Steps:**
  1. Enter invalid Aadhaar
  2. Click Register
- **Expected:** Form doesn't submit, error displays

#### Test Case 3.4: Server-Side Validation Error
- **Steps:**
  1. Fill form with valid frontend validation
  2. Submit (backend processes)
- **Expected:** Errors returned from server displayed in form

### 4. Real-Time Validation Tests

#### Test Case 4.1: Aadhaar Blur Event
- **Steps:**
  1. Type in Aadhaar field: "12345678"
  2. Click elsewhere (blur)
- **Expected:** 
  - "Aadhaar must be exactly 12 digits" error shows immediately
  - Error disappears on focus

#### Test Case 4.2: ABHA ID Blur Event
- **Steps:**
  1. Type in ABHA field: "invalid"
  2. Click elsewhere (blur)
- **Expected:** Error message shows below field

#### Test Case 4.3: Inline Error Clearing
- **Steps:**
  1. Trigger error
  2. Correct the value
  3. Blur field again
- **Expected:** Error message clears, field valid

#### Test Case 4.4: Duplicate Check AJAX
- **Steps:**
  1. Enter a previously registered Aadhaar
  2. Blur the field
- **Expected:** AJAX request fires, "already registered" message appears

### 5. Browser Compatibility Tests

Test on:
- [ ] Chrome (Latest)
- [ ] Firefox (Latest)
- [ ] Safari (Latest)
- [ ] Edge (Latest)
- [ ] Mobile browsers

### 6. Security Tests

#### Test Case 6.1: Aadhaar Masking
- **Steps:**
  1. Register a patient
  2. View patient details
- **Expected:** Aadhaar shown as XXXX-XXXX-1234

#### Test Case 6.2: SQL Injection Attempt
- **Input:** `' OR '1'='1`
- **Expected:** 
  - No database error
  - Validation fails
  - Error message displayed

#### Test Case 6.3: XSS Attempt
- **Input:** `<script>alert('xss')</script>`
- **Expected:** 
  - Input sanitized
  - No alert triggers
  - Validation fails or input properly escaped

### 7. Database Tests

#### Test Case 7.1: Unique Constraint
```sql
-- Try inserting duplicate Aadhaar
INSERT INTO patientdata (aadhaar_number) VALUES ('123456789012');
INSERT INTO patientdata (aadhaar_number) VALUES ('123456789012');
```
**Expected:** Second insert fails with UNIQUE constraint error

#### Test Case 7.2: Audit Log Creation
```sql
SELECT * FROM aadhaar_access_log WHERE opd_no = [registered_opd_no];
```
**Expected:** Entry with access timestamp and action='insert'

#### Test Case 7.3: Data Types
```sql
SELECT 
    COLUMN_NAME, 
    DATA_TYPE, 
    IS_NULLABLE 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_NAME = 'patientdata' 
AND COLUMN_NAME IN ('aadhaar_number', 'abha_id', 'aadhaar_masked');
```
**Expected:**
- aadhaar_number: VARCHAR(12), NOT NULL
- abha_id: VARCHAR(50), NULL or NOT NULL
- aadhaar_masked: VARCHAR(20), NULL

## Test Data

### Valid Test Aadhaar Numbers
(Note: These are examples - real Aadhaar validation uses Verhoeff algorithm)
```
123456789012 (modify last digit for valid checksum)
234567890123
345678901234
456789012345
```

### Valid ABHA IDs
```
12345678901234
john_doe@abdm
patient123@abdm
pm_scheme@abdm
yogesh_yadav@abdm
```

## Performance Tests

### Test Case P1: Form Load Time
- **Action:** Measure time to load registration form
- **Expected:** < 2 seconds

### Test Case P2: AJAX Validation Response
- **Action:** Time the duplicate check AJAX call
- **Expected:** < 500ms

### Test Case P3: Form Submission
- **Action:** Time from submit to success response
- **Expected:** < 2 seconds

## Load Testing

### Test Case L1: Multiple Simultaneous Submissions
- **Action:** Submit 10 forms simultaneously
- **Expected:** All process correctly, no database conflicts

### Test Case L2: Concurrent Duplicate Checks
- **Action:** Check same Aadhaar 5 times quickly
- **Expected:** All return correct result

## Error Handling Tests

### Test Case E1: Network Error
- **Steps:**
  1. Disable network during form submission
  2. Observe response
- **Expected:** Error message: "An error occurred while submitting the form"

### Test Case E2: Invalid Response Format
- **Action:** Server returns invalid JSON
- **Expected:** Graceful error handling, user-friendly message

### Test Case E3: Database Connection Error
- **Steps:**
  1. Stop MySQL
  2. Try to submit form
- **Expected:** User-friendly error message (no technical SQL errors)

## Regression Tests

After any code changes, verify:
- [ ] Aadhaar validation still works
- [ ] ABHA validation still works
- [ ] Form submission works
- [ ] Duplicate prevention works
- [ ] Masking works
- [ ] All other patient registration fields still function
- [ ] Department/Doctor selection still works
- [ ] No console JavaScript errors

## Test Results Template

```
Test Date: _______________
Tester: ___________________
Environment: ______________
Browser: __________________

Test Case: _________________
Status: [PASS / FAIL]
Notes: ______________________

Issues Found:
1. ________________________
2. ________________________

Sign-off: ___________________ Date: ____________
```

## Quick Test Checklist

- [ ] Form loads without errors
- [ ] Aadhaar field validates properly
- [ ] ABHA field validates properly
- [ ] Valid data submits successfully
- [ ] Invalid data shows errors
- [ ] Database records are created
- [ ] Aadhaar is masked in display
- [ ] No SQL errors in logs
- [ ] No JavaScript errors in console
- [ ] AJAX calls work correctly

## Debugging Tips

### Check JavaScript Console
```javascript
// Set breakpoint for debugging
debugger;

// Check if BASE_URL is set
console.log(BASE_URL);

// Test Verhoeff validation
console.log(verhoeffValidate('123456789012'));

// Check form data
console.log($('#patient_form').serializeArray());
```

### Check Server Logs
```bash
# Check Apache/PHP error log
tail -f /var/log/apache2/error.log

# Check MySQL error log
tail -f /var/log/mysql/error.log
```

### Check Database
```sql
-- Verify new columns exist
DESCRIBE patientdata;

-- Check data insertion
SELECT * FROM patientdata WHERE aadhaar_number IS NOT NULL;

-- Check audit log
SELECT * FROM aadhaar_access_log;
```

---

**Last Updated:** 2026-04-26
**Test Plan Version:** 1.0
