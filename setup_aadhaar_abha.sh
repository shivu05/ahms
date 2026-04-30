#!/bin/bash
# Quick Setup Script for Aadhaar and ABHA Integration
# Run this script to verify all files are in place

echo "==========================================================="
echo "AHMS - Aadhaar and ABHA Integration Setup Verification"
echo "==========================================================="
echo ""

# Check if files exist
echo "Checking files..."

files=(
    "application/helpers/aadhaar_abha_helper.php"
    "application/modules/patient/models/Patient_model.php"
    "application/modules/patient/controllers/Patient.php"
    "application/modules/patient/views/patient/index.php"
    "assets/js/patient_registration_validation.js"
    "assets/css/patient_registration_validation.css"
    "database_migrations/add_aadhaar_abha_to_patient.sql"
)

missing=0
for file in "${files[@]}"; do
    if [ -f "$file" ]; then
        echo "✓ $file"
    else
        echo "✗ MISSING: $file"
        ((missing++))
    fi
done

echo ""
echo "==========================================================="

if [ $missing -eq 0 ]; then
    echo "All files present! ✓"
    echo ""
    echo "Next steps:"
    echo "1. Run database migration:"
    echo "   mysql -u root -p ahms < database_migrations/add_aadhaar_abha_to_patient.sql"
    echo ""
    echo "2. Test the form at:"
    echo "   http://localhost/ahms/patient"
    echo ""
else
    echo "ERROR: $missing file(s) missing!"
    echo "Please ensure all files are created before proceeding."
    exit 1
fi

echo "==========================================================="
