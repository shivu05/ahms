<?php
/**
 * Aadhaar and ABHA Validation Helper
 * Implements Verhoeff algorithm for Aadhaar validation
 * 
 * @author System
 * @package helpers
 */

/**
 * Validate Aadhaar Number using basic format checks only.
 *
 * @param string $aadhaar
 * @return bool
 */
function validate_aadhaar_format($aadhaar) {
    $aadhaar = preg_replace('/\D+/', '', (string) $aadhaar);

    return preg_match('/^[1-9][0-9]{11}$/', $aadhaar) === 1;
}

/**
 * Validate Aadhaar Number using Verhoeff Algorithm
 * Aadhaar is a 12-digit unique identification number
 * The last digit is a checksum digit calculated using Verhoeff algorithm
 * 
 * @param string $aadhaar The Aadhaar number to validate
 * @return bool True if valid, false otherwise
 */
function validate_aadhaar_verhoeff($aadhaar) {
    $aadhaar = preg_replace('/\D+/', '', (string) $aadhaar);

    if (!validate_aadhaar_format($aadhaar)) {
        return false;
    }
    
    // Calculate Verhoeff checksum
    return verhoeff_validate($aadhaar);
}

/**
 * Validate ABHA ID Format
 * ABHA ID can be in two formats:
 * 1. 14-digit numeric code
 * 2. ABHA address: username@abdm format
 * 
 * @param string $abha_id The ABHA ID to validate
 * @return bool True if valid format, false otherwise
 */
function validate_abha_id_format($abha_id) {
    $abha_id = trim($abha_id);
    
    // Check 14-digit format
    if (preg_match('/^\d{14}$/', $abha_id)) {
        return true;
    }
    
    // Check ABHA address format (username@abdm)
    // Username: alphanumeric, underscore, hyphen (3-30 chars)
    // Domain: must be abdm or abdm subdomain
    if (preg_match('/^[a-zA-Z0-9_-]{3,30}@abdm$/i', $abha_id)) {
        return true;
    }
    
    return false;
}

/**
 * Mask Aadhaar Number - Show only last 4 digits
 * 
 * @param string $aadhaar The Aadhaar number to mask
 * @return string Masked Aadhaar (e.g., XXXX-XXXX-1234)
 */
function mask_aadhaar($aadhaar) {
    $aadhaar = preg_replace('/\s+/', '', $aadhaar);
    
    if (strlen($aadhaar) !== 12) {
        return '';
    }
    
    $last_four = substr($aadhaar, -4);
    return 'XXXX-XXXX-' . $last_four;
}

/**
 * Verhoeff Algorithm Implementation
 * Validates checksum digit using Verhoeff algorithm
 * 
 * @param string $number The number to validate (including checksum digit)
 * @return bool True if checksum is valid
 */
function verhoeff_validate($number) {
    // Multiplication table
    $mult = [
        [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
        [1, 2, 3, 4, 0, 6, 7, 8, 9, 5],
        [2, 3, 4, 0, 1, 7, 8, 9, 5, 6],
        [3, 4, 0, 1, 2, 8, 9, 5, 6, 7],
        [4, 0, 1, 2, 3, 9, 5, 6, 7, 8],
        [5, 9, 8, 7, 6, 0, 4, 3, 2, 1],
        [6, 5, 9, 8, 7, 1, 0, 4, 3, 2],
        [7, 6, 5, 9, 8, 2, 1, 0, 4, 3],
        [8, 7, 6, 5, 9, 3, 2, 1, 0, 4],
        [9, 8, 7, 6, 5, 4, 3, 2, 1, 0]
    ];

    // Permutation table
    $perm = [
        [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
        [1, 2, 3, 4, 0, 6, 7, 8, 9, 5],
        [2, 3, 4, 0, 1, 7, 8, 9, 5, 6],
        [3, 4, 0, 1, 2, 8, 9, 5, 6, 7],
        [4, 0, 1, 2, 3, 9, 5, 6, 7, 8],
        [5, 9, 8, 7, 6, 0, 4, 3, 2, 1],
        [6, 5, 9, 8, 7, 1, 0, 4, 3, 2],
        [7, 6, 5, 9, 8, 2, 1, 0, 4, 3],
        [8, 7, 6, 5, 9, 3, 2, 1, 0, 4],
        [9, 8, 7, 6, 5, 4, 3, 2, 1, 0]
    ];

    // Inverse table
    $inv = [0, 4, 3, 2, 1, 5, 6, 7, 8, 9];

    $c = 0;
    $multiplier = 1;
    
    // Traverse number from right to left
    for ($i = strlen($number) - 1; $i >= 0; $i--) {
        $digit = (int)$number[$i];
        $c = $mult[$c][$perm[$multiplier][$digit]];
        $multiplier = ($multiplier + 1) % 10;
    }

    return $c === 0;
}

/**
 * Calculate Aadhaar Checksum Digit
 * If you need to generate a new checksum digit (for 11 digits)
 * 
 * @param string $eleven_digits The first 11 digits of Aadhaar
 * @return string The complete 12-digit Aadhaar (or false if invalid)
 */
function calculate_aadhaar_checksum($eleven_digits) {
    // Remove spaces
    $eleven_digits = preg_replace('/\s+/', '', $eleven_digits);
    
    // Validate
    if (!is_numeric($eleven_digits) || strlen($eleven_digits) !== 11) {
        return false;
    }
    
    if ($eleven_digits[0] === '0') {
        return false;
    }

    // Multiplication table
    $mult = [
        [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
        [1, 2, 3, 4, 0, 6, 7, 8, 9, 5],
        [2, 3, 4, 0, 1, 7, 8, 9, 5, 6],
        [3, 4, 0, 1, 2, 8, 9, 5, 6, 7],
        [4, 0, 1, 2, 3, 9, 5, 6, 7, 8],
        [5, 9, 8, 7, 6, 0, 4, 3, 2, 1],
        [6, 5, 9, 8, 7, 1, 0, 4, 3, 2],
        [7, 6, 5, 9, 8, 2, 1, 0, 4, 3],
        [8, 7, 6, 5, 9, 3, 2, 1, 0, 4],
        [9, 8, 7, 6, 5, 4, 3, 2, 1, 0]
    ];

    // Permutation table
    $perm = [
        [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
        [1, 2, 3, 4, 0, 6, 7, 8, 9, 5],
        [2, 3, 4, 0, 1, 7, 8, 9, 5, 6],
        [3, 4, 0, 1, 2, 8, 9, 5, 6, 7],
        [4, 0, 1, 2, 3, 9, 5, 6, 7, 8],
        [5, 9, 8, 7, 6, 0, 4, 3, 2, 1],
        [6, 5, 9, 8, 7, 1, 0, 4, 3, 2],
        [7, 6, 5, 9, 8, 2, 1, 0, 4, 3],
        [8, 7, 6, 5, 9, 3, 2, 1, 0, 4],
        [9, 8, 7, 6, 5, 4, 3, 2, 1, 0]
    ];

    // Inverse table
    $inv = [0, 4, 3, 2, 1, 5, 6, 7, 8, 9];

    $c = 0;
    $multiplier = 1;

    // Traverse from right to left
    for ($i = strlen($eleven_digits) - 1; $i >= 0; $i--) {
        $digit = (int)$eleven_digits[$i];
        $c = $mult[$c][$perm[$multiplier][$digit]];
        $multiplier = ($multiplier + 1) % 10;
    }

    // Calculate checksum digit
    $check_digit = $inv[$c];
    return $eleven_digits . $check_digit;
}

/* End of file aadhaar_abha_helper.php */
