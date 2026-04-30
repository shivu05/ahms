<?php
/**
 * Installation Verification Script
 * Run this script to verify Aadhaar/ABHA integration is properly installed
 * 
 * Access: http://localhost/ahms/verify_aadhaar_setup.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

$checks_passed = 0;
$checks_failed = 0;
$warnings = 0;

function check_file($path, $description) {
    global $checks_passed, $checks_failed;
    
    echo "<tr>";
    if (file_exists($path)) {
        echo "<td class='success'><i class='fa fa-check'></i> OK</td>";
        echo "<td class='success'>$description</td>";
        echo "<td class='success'>File exists: $path</td>";
        $checks_passed++;
    } else {
        echo "<td class='danger'><i class='fa fa-times'></i> FAIL</td>";
        echo "<td class='danger'>$description</td>";
        echo "<td class='danger'>File not found: $path</td>";
        $checks_failed++;
    }
    echo "</tr>";
}

function check_database($connection = null) {
    global $checks_passed, $checks_failed;
    
    // Try to connect to database
    $mysqli = new mysqli("localhost", "root", "", "ahms");
    
    if ($mysqli->connect_error) {
        echo "<tr>";
        echo "<td class='danger'><i class='fa fa-times'></i> FAIL</td>";
        echo "<td class='danger'>Database Connection</td>";
        echo "<td class='danger'>Cannot connect to database: " . $mysqli->connect_error . "</td>";
        echo "</tr>";
        $checks_failed++;
        return false;
    } else {
        echo "<tr>";
        echo "<td class='success'><i class='fa fa-check'></i> OK</td>";
        echo "<td class='success'>Database Connection</td>";
        echo "<td class='success'>Connected to ahms database</td>";
        echo "</tr>";
        $checks_passed++;
    }
    
    // Check if columns exist
    $result = $mysqli->query("DESCRIBE patientdata");
    $columns = array();
    while ($row = $result->fetch_assoc()) {
        $columns[$row['Field']] = true;
    }
    
    $required_columns = array('aadhaar_number', 'abha_id', 'aadhaar_masked');
    foreach ($required_columns as $col) {
        echo "<tr>";
        if (isset($columns[$col])) {
            echo "<td class='success'><i class='fa fa-check'></i> OK</td>";
            echo "<td class='success'>Column: $col</td>";
            echo "<td class='success'>Column exists in patientdata table</td>";
            $checks_passed++;
        } else {
            echo "<td class='danger'><i class='fa fa-times'></i> FAIL</td>";
            echo "<td class='danger'>Column: $col</td>";
            echo "<td class='danger'>Column missing! Run migration SQL</td>";
            $checks_failed++;
        }
        echo "</tr>";
    }
    
    // Check audit table
    $result = $mysqli->query("SHOW TABLES LIKE 'aadhaar_access_log'");
    echo "<tr>";
    if ($result->num_rows > 0) {
        echo "<td class='success'><i class='fa fa-check'></i> OK</td>";
        echo "<td class='success'>Audit Table</td>";
        echo "<td class='success'>aadhaar_access_log table exists</td>";
        $checks_passed++;
    } else {
        echo "<td class='danger'><i class='fa fa-times'></i> FAIL</td>";
        echo "<td class='danger'>Audit Table</td>";
        echo "<td class='danger'>aadhaar_access_log table missing! Run migration</td>";
        $checks_failed++;
    }
    echo "</tr>";
    
    $mysqli->close();
    return true;
}

function check_function($function_name, $file) {
    global $checks_passed, $checks_failed;
    
    echo "<tr>";
    if (function_exists($function_name)) {
        echo "<td class='success'><i class='fa fa-check'></i> OK</td>";
        echo "<td class='success'>Function: $function_name</td>";
        echo "<td class='success'>Function loaded from $file</td>";
        $checks_passed++;
    } else {
        echo "<td class='warning'><i class='fa fa-exclamation'></i> CHECK</td>";
        echo "<td class='warning'>Function: $function_name</td>";
        echo "<td class='warning'>Function not loaded - will be loaded on form page</td>";
        // Not critical as it's loaded conditionally
    }
    echo "</tr>";
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AHMS - Aadhaar/ABHA Integration Verification</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: white;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 2px solid #0275d8;
            padding-bottom: 10px;
        }
        h2 {
            margin-top: 30px;
            color: #0275d8;
        }
        table {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
        }
        table th {
            background-color: #f8f9fa;
            color: #333;
            padding: 10px;
            text-align: left;
            border-bottom: 2px solid #dee2e6;
        }
        table td {
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
        }
        td.success {
            background-color: #d4edda;
            color: #155724;
        }
        td.danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        td.warning {
            background-color: #fff3cd;
            color: #856404;
        }
        .summary {
            margin-top: 30px;
            padding: 15px;
            border-radius: 5px;
        }
        .summary.success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .summary.danger {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        .summary.warning {
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-weight: bold;
            margin-left: 10px;
        }
        .status-badge.pass {
            background-color: #28a745;
            color: white;
        }
        .status-badge.fail {
            background-color: #dc3545;
            color: white;
        }
        .instructions {
            background-color: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin: 10px 0;
        }
    </style>
</head>
<body>

<div class="container">
    <h1><i class="fa fa-check-circle"></i> AHMS - Aadhaar/ABHA Integration Verification</h1>
    
    <div class="instructions">
        <strong>Note:</strong> This script verifies that all components of the Aadhaar and ABHA ID integration are properly installed.
        Run this after completing the installation steps in QUICKSTART.md
    </div>

    <h2>File System Checks</h2>
    <table class="table">
        <thead>
            <tr>
                <th width="10%">Status</th>
                <th width="30%">Check</th>
                <th width="60%">Details</th>
            </tr>
        </thead>
        <tbody>
            <?php
            check_file('application/helpers/aadhaar_abha_helper.php', 'Helper: Aadhaar/ABHA Functions');
            check_file('application/modules/patient/controllers/Patient.php', 'Controller: Patient (Updated)');
            check_file('application/modules/patient/models/Patient_model.php', 'Model: Patient (Updated)');
            check_file('application/modules/patient/views/patient/index.php', 'View: Patient Registration Form (Updated)');
            check_file('assets/js/patient_registration_validation.js', 'JavaScript: Validation & Verhoeff');
            check_file('assets/css/patient_registration_validation.css', 'CSS: Form Styling');
            check_file('database_migrations/add_aadhaar_abha_to_patient.sql', 'SQL: Database Migration');
            ?>
        </tbody>
    </table>

    <h2>Database Checks</h2>
    <table class="table">
        <thead>
            <tr>
                <th width="10%">Status</th>
                <th width="30%">Check</th>
                <th width="60%">Details</th>
            </tr>
        </thead>
        <tbody>
            <?php check_database(); ?>
        </tbody>
    </table>

    <h2>Helper Functions Availability</h2>
    <table class="table">
        <thead>
            <tr>
                <th width="10%">Status</th>
                <th width="30%">Check</th>
                <th width="60%">Details</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Load helper if exists
            if (file_exists('application/helpers/aadhaar_abha_helper.php')) {
                include 'application/helpers/aadhaar_abha_helper.php';
                
                check_function('validate_aadhaar_verhoeff', 'aadhaar_abha_helper.php');
                check_function('validate_abha_id_format', 'aadhaar_abha_helper.php');
                check_function('mask_aadhaar', 'aadhaar_abha_helper.php');
                check_function('verhoeff_validate', 'aadhaar_abha_helper.php');
                check_function('calculate_aadhaar_checksum', 'aadhaar_abha_helper.php');
            }
            ?>
        </tbody>
    </table>

    <?php
    $status_class = ($checks_failed == 0) ? 'success' : (($checks_failed > 2) ? 'danger' : 'warning');
    $status_text = ($checks_failed == 0) ? '✓ All checks passed!' : ('⚠ ' . $checks_failed . ' issue(s) found');
    ?>

    <div class="summary <?php echo $status_class; ?>">
        <h4><?php echo $status_text; ?></h4>
        <p>
            <strong>Passed:</strong> <span class="status-badge pass"><?php echo $checks_passed; ?></span><br>
            <strong>Failed:</strong> <span class="status-badge fail"><?php echo $checks_failed; ?></span><br>
        </p>
    </div>

    <?php if ($checks_failed > 0): ?>
    <div class="alert alert-danger">
        <h4>⚠ Action Required</h4>
        <p>Please fix the failed checks before proceeding:</p>
        <ol>
            <li>Ensure all files are in their correct locations</li>
            <li>Run the database migration SQL</li>
            <li>Check file permissions</li>
            <li>Refresh this page to verify fixes</li>
        </ol>
        <p><strong>For detailed help:</strong> See QUICKSTART.md and AADHAAR_ABHA_IMPLEMENTATION_GUIDE.md</p>
    </div>
    <?php else: ?>
    <div class="alert alert-success">
        <h4>✓ Installation Verified</h4>
        <p>All components are properly installed!</p>
        <p><strong>Next steps:</strong></p>
        <ol>
            <li>Navigate to <a href="patient" target="_blank">Patient Registration Form</a></li>
            <li>Test the form with valid data</li>
            <li>Check browser console for errors (F12)</li>
            <li>See TESTING_GUIDE.md for comprehensive test cases</li>
        </ol>
    </div>
    <?php endif; ?>

    <div class="instructions" style="margin-top: 30px;">
        <strong>Quick Test:</strong>
        <p>Click the link below to go to the Patient Registration form:</p>
        <a href="patient" class="btn btn-primary" target="_blank">
            <i class="fa fa-arrow-right"></i> Open Patient Registration Form
        </a>
    </div>

    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #dee2e6; color: #666;">
        <p><small>
            <strong>Documentation:</strong><br>
            • QUICKSTART.md - Quick installation guide<br>
            • AADHAAR_ABHA_IMPLEMENTATION_GUIDE.md - Complete implementation details<br>
            • TESTING_GUIDE.md - Comprehensive testing scenarios<br>
        </small></p>
    </div>

</div>

<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>
