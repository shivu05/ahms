<?php
/**
 * VHMS Professional Billing Module
 * 
 * A comprehensive, production-ready billing management system with:
 * - Multi-type invoicing (OPD, IPD, Emergency, Pharmacy)
 * - Advanced payment processing with multiple methods
 * - Complete insurance claims management
 * - Pre-authorization workflow
 * - GST/Tax compliance
 * - Audit logging and reporting
 * 
 * @package    VHMS
 * @subpackage Modules/Billing
 * @version    1.0
 * @author     Hospital Management Team
 * @license    MIT
 * @link       https://your-hospital-domain.com
 * 
 * MODULE STRUCTURE:
 * ├── controllers/
 * │   ├── Billing.php           - Main invoice & payment controller
 * │   └── Insurance.php         - Insurance claims & pre-auth controller
 * ├── models/
 * │   ├── Billing_model.php     - Invoice management
 * │   ├── Service_model.php     - Service & package management
 * │   ├── Payment_model.php     - Payment processing
 * │   └── Insurance_model.php   - Insurance operations
 * ├── views/
 * │   ├── dashboard/            - Dashboard views
 * │   ├── invoices/             - Invoice views
 * │   ├── payments/             - Payment views
 * │   └── insurance/            - Insurance views
 * ├── helpers/
 * │   └── Billing_helper.php    - Helper functions
 * ├── sql/
 * │   ├── billing_module.sql           - Main database schema
 * │   └── INSTALLATION_GUIDE.sql       - Installation instructions
 * ├── config.php                - Module configuration
 * ├── README.md                 - Complete documentation
 * ├── SETUP_GUIDE.md           - Step-by-step setup
 * ├── QUICK_REFERENCE.md       - Quick reference guide
 * └── IMPLEMENTATION_SUMMARY.txt - Project summary
 * 
 * QUICK START:
 * 
 * 1. Import Database:
 *    Import: application/modules/billing/sql/billing_module.sql
 * 
 * 2. Access Module:
 *    http://your-domain/billing
 * 
 * 3. Create Invoice:
 *    Billing → Create Invoice → Select Type → Add Services → Finalize
 * 
 * 4. Process Payment:
 *    Open Invoice → Record Payment → Select Method → Process
 * 
 * 5. Insurance Claim:
 *    Invoice → Create Insurance Claim → Submit → Follow-up → Approve
 * 
 * DOCUMENTATION:
 * 
 * - README.md              : Complete feature documentation
 * - SETUP_GUIDE.md         : Installation and quick start
 * - QUICK_REFERENCE.md     : API and methods reference
 * - IMPLEMENTATION_SUMMARY : Project completion details
 * 
 * DATABASE TABLES: 27 tables for complete billing operations
 * 
 * KEY FEATURES:
 * ✓ Multi-type invoicing
 * ✓ GST/Tax compliance
 * ✓ Multiple payment methods
 * ✓ Insurance pre-authorization
 * ✓ Insurance claims management
 * ✓ Deposit/Advance management
 * ✓ Complete audit logging
 * ✓ Financial reports
 * ✓ Role-based access control
 * ✓ Production-ready
 * 
 * SUPPORT:
 * For issues or questions, refer to:
 * - README.md for feature documentation
 * - SETUP_GUIDE.md for installation help
 * - QUICK_REFERENCE.md for API methods
 * 
 * VERSION HISTORY:
 * v1.0 (Dec 2025) - Initial release with complete feature set
 */

?>
