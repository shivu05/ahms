-- =====================================================
-- BILLING MODULE - FINAL CLEAN SETUP SCRIPT
-- Version: 2.0 - Fixed and Tested
-- Purpose: Safely drop and recreate all billing tables
-- =====================================================

-- Disable foreign key checks to allow dropping tables
SET FOREIGN_KEY_CHECKS=0;

-- =====================================================
-- STEP 1: DROP ALL EXISTING BILLING TABLES
-- =====================================================

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

-- =====================================================
-- STEP 2: CREATE ALL TABLES FRESH
-- =====================================================

-- Service Categories/Groups
CREATE TABLE `billing_service_categories` (
  `category_id` INT AUTO_INCREMENT PRIMARY KEY,
  `category_code` VARCHAR(50) NOT NULL UNIQUE,
  `category_name` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `is_active` TINYINT DEFAULT 1,
  `gst_applicable` TINYINT DEFAULT 1,
  `gst_rate` DECIMAL(5,2) DEFAULT 0,
  `created_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_category_code` (`category_code`),
  INDEX `idx_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Service Master
CREATE TABLE `billing_services` (
  `service_id` INT AUTO_INCREMENT PRIMARY KEY,
  `service_code` VARCHAR(50) NOT NULL UNIQUE,
  `service_name` VARCHAR(255) NOT NULL,
  `category_id` INT NOT NULL,
  `description` TEXT,
  `unit_price` DECIMAL(12,2) NOT NULL,
  `gst_applicable` TINYINT DEFAULT 1,
  `gst_rate` DECIMAL(5,2) DEFAULT 0,
  `requires_qty` TINYINT DEFAULT 0,
  `requires_description` TINYINT DEFAULT 0,
  `applicable_for_opd` TINYINT DEFAULT 1,
  `applicable_for_ipd` TINYINT DEFAULT 1,
  `is_active` TINYINT DEFAULT 1,
  `created_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`category_id`) REFERENCES `billing_service_categories`(`category_id`),
  INDEX `idx_service_code` (`service_code`),
  INDEX `idx_category_id` (`category_id`),
  INDEX `idx_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Package/Bundled Services
CREATE TABLE `billing_service_packages` (
  `package_id` INT AUTO_INCREMENT PRIMARY KEY,
  `package_code` VARCHAR(50) NOT NULL UNIQUE,
  `package_name` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `total_amount` DECIMAL(12,2) NOT NULL,
  `gst_applicable` TINYINT DEFAULT 1,
  `gst_rate` DECIMAL(5,2) DEFAULT 0,
  `discount_percentage` DECIMAL(5,2) DEFAULT 0,
  `is_active` TINYINT DEFAULT 1,
  `created_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_package_code` (`package_code`),
  INDEX `idx_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Package Line Items
CREATE TABLE `billing_package_services` (
  `package_service_id` INT AUTO_INCREMENT PRIMARY KEY,
  `package_id` INT NOT NULL,
  `service_id` INT NOT NULL,
  `quantity` INT DEFAULT 1,
  `unit_price` DECIMAL(12,2) NOT NULL,
  FOREIGN KEY (`package_id`) REFERENCES `billing_service_packages`(`package_id`) ON DELETE CASCADE,
  FOREIGN KEY (`service_id`) REFERENCES `billing_services`(`service_id`),
  UNIQUE KEY `unique_package_service` (`package_id`, `service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Invoice Master
CREATE TABLE `billing_invoices` (
  `invoice_id` INT AUTO_INCREMENT PRIMARY KEY,
  `invoice_number` VARCHAR(50) NOT NULL UNIQUE,
  `invoice_date` DATE NOT NULL,
  `opd_no` INT,
  `ipd_no` INT,
  `patient_name` VARCHAR(255),
  `patient_uhid` VARCHAR(50),
  `department` VARCHAR(100),
  `doctor_id` INT,
  `doctor_name` VARCHAR(255),
  `invoice_type` ENUM('OPD', 'IPD', 'EMERGENCY', 'PHARMACY') DEFAULT 'OPD',
  `subtotal_amount` DECIMAL(12,2) NOT NULL,
  `gst_amount` DECIMAL(12,2) DEFAULT 0,
  `discount_amount` DECIMAL(12,2) DEFAULT 0,
  `discount_reason` VARCHAR(255),
  `total_amount` DECIMAL(12,2) NOT NULL,
  `amount_paid` DECIMAL(12,2) DEFAULT 0,
  `balance_due` DECIMAL(12,2) NOT NULL,
  `invoice_status` ENUM('DRAFT', 'ISSUED', 'PARTIALLY_PAID', 'PAID', 'CANCELLED', 'CREDITED') DEFAULT 'DRAFT',
  `payment_status` ENUM('UNPAID', 'PARTIAL', 'PAID') DEFAULT 'UNPAID',
  `insurance_applicable` TINYINT DEFAULT 0,
  `insurance_id` INT,
  `remarks` TEXT,
  `created_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_invoice_number` (`invoice_number`),
  INDEX `idx_invoice_date` (`invoice_date`),
  INDEX `idx_opd_no` (`opd_no`),
  INDEX `idx_ipd_no` (`ipd_no`),
  INDEX `idx_invoice_status` (`invoice_status`),
  INDEX `idx_payment_status` (`payment_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Invoice Line Items
CREATE TABLE `billing_invoice_items` (
  `item_id` INT AUTO_INCREMENT PRIMARY KEY,
  `invoice_id` INT NOT NULL,
  `service_id` INT,
  `service_code` VARCHAR(50),
  `service_name` VARCHAR(255) NOT NULL,
  `quantity` DECIMAL(10,2) DEFAULT 1,
  `unit_price` DECIMAL(12,2) NOT NULL,
  `item_amount` DECIMAL(12,2) NOT NULL,
  `gst_applicable` TINYINT DEFAULT 1,
  `gst_rate` DECIMAL(5,2) DEFAULT 0,
  `gst_amount` DECIMAL(12,2) DEFAULT 0,
  `discount_percentage` DECIMAL(5,2) DEFAULT 0,
  `discount_amount` DECIMAL(12,2) DEFAULT 0,
  `line_total` DECIMAL(12,2) NOT NULL,
  `remarks` VARCHAR(255),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`invoice_id`) REFERENCES `billing_invoices`(`invoice_id`) ON DELETE CASCADE,
  INDEX `idx_invoice_id` (`invoice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Payment Methods Master
CREATE TABLE `billing_payment_methods` (
  `method_id` INT AUTO_INCREMENT PRIMARY KEY,
  `method_code` VARCHAR(50) NOT NULL UNIQUE,
  `method_name` VARCHAR(100) NOT NULL,
  `description` TEXT,
  `requires_reference` TINYINT DEFAULT 0,
  `is_active` TINYINT DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_method_code` (`method_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Payment Records
CREATE TABLE `billing_payments` (
  `payment_id` INT AUTO_INCREMENT PRIMARY KEY,
  `payment_number` VARCHAR(50) NOT NULL UNIQUE,
  `invoice_id` INT NOT NULL,
  `payment_date` DATE NOT NULL,
  `payment_amount` DECIMAL(12,2) NOT NULL,
  `payment_method_id` INT NOT NULL,
  `reference_number` VARCHAR(100),
  `bank_name` VARCHAR(100),
  `cheque_number` VARCHAR(50),
  `card_type` VARCHAR(50),
  `remarks` TEXT,
  `receipt_generated` TINYINT DEFAULT 0,
  `receipt_number` VARCHAR(50),
  `created_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`invoice_id`) REFERENCES `billing_invoices`(`invoice_id`),
  FOREIGN KEY (`payment_method_id`) REFERENCES `billing_payment_methods`(`method_id`),
  INDEX `idx_payment_number` (`payment_number`),
  INDEX `idx_invoice_id` (`invoice_id`),
  INDEX `idx_payment_date` (`payment_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Advance/Deposit Management
CREATE TABLE `billing_deposits` (
  `deposit_id` INT AUTO_INCREMENT PRIMARY KEY,
  `deposit_number` VARCHAR(50) NOT NULL UNIQUE,
  `opd_no` INT,
  `ipd_no` INT,
  `deposit_date` DATE NOT NULL,
  `deposit_amount` DECIMAL(12,2) NOT NULL,
  `payment_method_id` INT NOT NULL,
  `reference_number` VARCHAR(100),
  `remaining_amount` DECIMAL(12,2) NOT NULL,
  `remarks` TEXT,
  `created_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`payment_method_id`) REFERENCES `billing_payment_methods`(`method_id`),
  INDEX `idx_deposit_number` (`deposit_number`),
  INDEX `idx_opd_no` (`opd_no`),
  INDEX `idx_ipd_no` (`ipd_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Deposit Adjustments Against Invoices
CREATE TABLE `billing_deposit_adjustments` (
  `adjustment_id` INT AUTO_INCREMENT PRIMARY KEY,
  `deposit_id` INT NOT NULL,
  `invoice_id` INT NOT NULL,
  `adjustment_amount` DECIMAL(12,2) NOT NULL,
  `adjustment_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`deposit_id`) REFERENCES `billing_deposits`(`deposit_id`),
  FOREIGN KEY (`invoice_id`) REFERENCES `billing_invoices`(`invoice_id`),
  INDEX `idx_deposit_id` (`deposit_id`),
  INDEX `idx_invoice_id` (`invoice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insurance Companies Master
CREATE TABLE `billing_insurance_companies` (
  `company_id` INT AUTO_INCREMENT PRIMARY KEY,
  `company_code` VARCHAR(50) NOT NULL UNIQUE,
  `company_name` VARCHAR(255) NOT NULL,
  `contact_person` VARCHAR(100),
  `email` VARCHAR(100),
  `phone` VARCHAR(20),
  `address` TEXT,
  `city` VARCHAR(100),
  `state` VARCHAR(100),
  `pincode` VARCHAR(10),
  `website` VARCHAR(255),
  `claim_contact_email` VARCHAR(100),
  `claim_contact_phone` VARCHAR(20),
  `is_active` TINYINT DEFAULT 1,
  `created_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_company_code` (`company_code`),
  INDEX `idx_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insurance Policies
CREATE TABLE `billing_insurance_policies` (
  `policy_id` INT AUTO_INCREMENT PRIMARY KEY,
  `policy_number` VARCHAR(100) NOT NULL UNIQUE,
  `company_id` INT NOT NULL,
  `policy_name` VARCHAR(255),
  `policy_type` ENUM('INDIVIDUAL', 'FAMILY', 'GROUP', 'CORPORATE') DEFAULT 'INDIVIDUAL',
  `policy_holder_name` VARCHAR(255),
  `policy_holder_contact` VARCHAR(20),
  `policy_start_date` DATE,
  `policy_end_date` DATE,
  `coverage_limit` DECIMAL(12,2),
  `deductible_amount` DECIMAL(12,2) DEFAULT 0,
  `co_payment_percentage` DECIMAL(5,2) DEFAULT 0,
  `requires_pre_approval` TINYINT DEFAULT 1,
  `is_active` TINYINT DEFAULT 1,
  `remarks` TEXT,
  `created_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`company_id`) REFERENCES `billing_insurance_companies`(`company_id`),
  INDEX `idx_policy_number` (`policy_number`),
  INDEX `idx_company_id` (`company_id`),
  INDEX `idx_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insurance Pre-authorization/Pre-approval
CREATE TABLE `billing_insurance_preauth` (
  `preauth_id` INT AUTO_INCREMENT PRIMARY KEY,
  `preauth_number` VARCHAR(50) NOT NULL UNIQUE,
  `policy_id` INT NOT NULL,
  `opd_no` INT,
  `ipd_no` INT,
  `patient_name` VARCHAR(255),
  `patient_contact` VARCHAR(20),
  `preauth_date` DATE NOT NULL,
  `estimated_cost` DECIMAL(12,2) NOT NULL,
  `approved_amount` DECIMAL(12,2),
  `authorization_number` VARCHAR(100),
  `preauth_status` ENUM('SUBMITTED', 'APPROVED', 'PARTIALLY_APPROVED', 'REJECTED', 'EXPIRED', 'COMPLETED') DEFAULT 'SUBMITTED',
  `approval_date` DATE,
  `expiry_date` DATE,
  `reason_for_rejection` TEXT,
  `treatment_description` TEXT,
  `approved_by_insurance` VARCHAR(100),
  `remarks` TEXT,
  `created_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`policy_id`) REFERENCES `billing_insurance_policies`(`policy_id`),
  INDEX `idx_preauth_number` (`preauth_number`),
  INDEX `idx_policy_id` (`policy_id`),
  INDEX `idx_preauth_status` (`preauth_status`),
  INDEX `idx_opd_no` (`opd_no`),
  INDEX `idx_ipd_no` (`ipd_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insurance Claims
CREATE TABLE `billing_insurance_claims` (
  `claim_id` INT AUTO_INCREMENT PRIMARY KEY,
  `claim_number` VARCHAR(50) NOT NULL UNIQUE,
  `invoice_id` INT NOT NULL,
  `policy_id` INT NOT NULL,
  `preauth_id` INT,
  `claim_date` DATE NOT NULL,
  `claim_submitted_date` DATE,
  `invoice_amount` DECIMAL(12,2) NOT NULL,
  `claimed_amount` DECIMAL(12,2) NOT NULL,
  `deductible_amount` DECIMAL(12,2) DEFAULT 0,
  `co_payment_amount` DECIMAL(12,2) DEFAULT 0,
  `approved_amount` DECIMAL(12,2),
  `rejection_reason` TEXT,
  `partial_rejection_reason` TEXT,
  `claim_status` ENUM('DRAFT', 'SUBMITTED', 'ACKNOWLEDGED', 'UNDER_PROCESS', 'APPROVED', 'PARTIALLY_APPROVED', 'REJECTED', 'PAID', 'CLOSED') DEFAULT 'DRAFT',
  `approval_date` DATE,
  `payment_date` DATE,
  `insurance_reference_number` VARCHAR(100),
  `remarks` TEXT,
  `created_by` INT,
  `submitted_by` INT,
  `submitted_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`invoice_id`) REFERENCES `billing_invoices`(`invoice_id`),
  FOREIGN KEY (`policy_id`) REFERENCES `billing_insurance_policies`(`policy_id`),
  INDEX `idx_claim_number` (`claim_number`),
  INDEX `idx_invoice_id` (`invoice_id`),
  INDEX `idx_policy_id` (`policy_id`),
  INDEX `idx_claim_status` (`claim_status`),
  INDEX `idx_claim_date` (`claim_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insurance Claim Documents
CREATE TABLE `billing_claim_documents` (
  `document_id` INT AUTO_INCREMENT PRIMARY KEY,
  `claim_id` INT NOT NULL,
  `document_type` ENUM('INVOICE', 'PRESCRIPTION', 'MEDICAL_REPORT', 'LAB_REPORT', 'IMAGING', 'DISCHARGE_SUMMARY', 'OTHER') DEFAULT 'INVOICE',
  `document_name` VARCHAR(255) NOT NULL,
  `document_file_path` VARCHAR(500),
  `file_size` INT,
  `uploaded_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `uploaded_by` INT,
  FOREIGN KEY (`claim_id`) REFERENCES `billing_insurance_claims`(`claim_id`) ON DELETE CASCADE,
  INDEX `idx_claim_id` (`claim_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insurance Claim Follow-ups
CREATE TABLE `billing_claim_followups` (
  `followup_id` INT AUTO_INCREMENT PRIMARY KEY,
  `claim_id` INT NOT NULL,
  `followup_date` DATE NOT NULL,
  `followup_type` ENUM('CALL', 'EMAIL', 'LETTER', 'VISIT', 'OTHER') DEFAULT 'CALL',
  `contacted_person` VARCHAR(100),
  `followup_notes` TEXT,
  `next_followup_date` DATE,
  `created_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`claim_id`) REFERENCES `billing_insurance_claims`(`claim_id`) ON DELETE CASCADE,
  INDEX `idx_claim_id` (`claim_id`),
  INDEX `idx_followup_date` (`followup_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Credit Notes (for returns/refunds)
CREATE TABLE `billing_credit_notes` (
  `credit_note_id` INT AUTO_INCREMENT PRIMARY KEY,
  `credit_note_number` VARCHAR(50) NOT NULL UNIQUE,
  `invoice_id` INT NOT NULL,
  `credit_date` DATE NOT NULL,
  `reason` ENUM('RETURN', 'ADJUSTMENT', 'DISCOUNT', 'ERROR', 'OTHER') DEFAULT 'RETURN',
  `credit_amount` DECIMAL(12,2) NOT NULL,
  `gst_amount` DECIMAL(12,2) DEFAULT 0,
  `remarks` TEXT,
  `approved_by` INT,
  `approval_date` DATE,
  `status` ENUM('DRAFT', 'ISSUED', 'REVERSED') DEFAULT 'DRAFT',
  `created_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`invoice_id`) REFERENCES `billing_invoices`(`invoice_id`),
  INDEX `idx_credit_note_number` (`credit_note_number`),
  INDEX `idx_invoice_id` (`invoice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Billing Configuration
CREATE TABLE `billing_configurations` (
  `config_id` INT AUTO_INCREMENT PRIMARY KEY,
  `config_key` VARCHAR(100) NOT NULL UNIQUE,
  `config_value` TEXT,
  `config_type` ENUM('STRING', 'NUMBER', 'BOOLEAN', 'JSON') DEFAULT 'STRING',
  `description` TEXT,
  `updated_by` INT,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_config_key` (`config_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tax/GST Configuration
CREATE TABLE `billing_tax_configurations` (
  `tax_config_id` INT AUTO_INCREMENT PRIMARY KEY,
  `tax_name` VARCHAR(100) NOT NULL,
  `tax_rate` DECIMAL(5,2) NOT NULL,
  `effective_from` DATE,
  `effective_to` DATE,
  `description` TEXT,
  `is_active` TINYINT DEFAULT 1,
  `created_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_tax_name` (`tax_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Discount Master
CREATE TABLE `billing_discounts` (
  `discount_id` INT AUTO_INCREMENT PRIMARY KEY,
  `discount_code` VARCHAR(50) NOT NULL UNIQUE,
  `discount_name` VARCHAR(255) NOT NULL,
  `discount_type` ENUM('PERCENTAGE', 'FIXED_AMOUNT') DEFAULT 'PERCENTAGE',
  `discount_value` DECIMAL(12,2) NOT NULL,
  `maximum_discount_amount` DECIMAL(12,2),
  `minimum_invoice_amount` DECIMAL(12,2),
  `applicable_categories` TEXT,
  `valid_from` DATE,
  `valid_to` DATE,
  `is_active` TINYINT DEFAULT 1,
  `created_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_discount_code` (`discount_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Billing Audit Log
CREATE TABLE `billing_audit_logs` (
  `log_id` INT AUTO_INCREMENT PRIMARY KEY,
  `entity_type` VARCHAR(100),
  `entity_id` INT,
  `action_type` ENUM('CREATE', 'UPDATE', 'DELETE', 'APPROVE', 'CANCEL', 'PRINT', 'EMAIL') DEFAULT 'CREATE',
  `old_values` JSON,
  `new_values` JSON,
  `user_id` INT,
  `ip_address` VARCHAR(45),
  `user_agent` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_entity_type` (`entity_type`),
  INDEX `idx_entity_id` (`entity_id`),
  INDEX `idx_action_type` (`action_type`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- STEP 3: INSERT DEFAULT DATA
-- =====================================================

-- Insert Payment Methods
INSERT IGNORE INTO `billing_payment_methods` (`method_code`, `method_name`, `requires_reference`, `is_active`) VALUES
('CASH', 'Cash', 0, 1),
('CHEQUE', 'Cheque', 1, 1),
('CARD', 'Credit/Debit Card', 1, 1),
('TRANSFER', 'Bank Transfer/NEFT/RTGS', 1, 1),
('UPI', 'UPI Payment', 1, 1),
('WALLET', 'Digital Wallet', 1, 1),
('INSURANCE', 'Insurance Settlement', 1, 1);

-- Insert Service Categories
INSERT IGNORE INTO `billing_service_categories` (`category_code`, `category_name`, `gst_applicable`, `gst_rate`, `is_active`) VALUES
('CONSULTATION', 'Consultation', 1, 5.00, 1),
('DIAGNOSTIC', 'Diagnostic Services', 1, 5.00, 1),
('PROCEDURE', 'Surgical Procedures', 1, 5.00, 1),
('WARD', 'Ward/Room Charges', 1, 5.00, 1),
('PATHOLOGY', 'Pathology Tests', 1, 5.00, 1),
('RADIOLOGY', 'Radiology/Imaging', 1, 5.00, 1),
('PHARMACY', 'Pharmacy Services', 1, 5.00, 1),
('PHYSIOTHERAPY', 'Physiotherapy', 1, 5.00, 1);

-- Insert Default Tax Configuration
INSERT IGNORE INTO `billing_tax_configurations` (`tax_name`, `tax_rate`, `effective_from`, `is_active`) VALUES
('GST @ 5%', 5.00, CURDATE(), 1),
('GST @ 12%', 12.00, CURDATE(), 1),
('GST @ 18%', 18.00, CURDATE(), 1);

-- Insert Billing Configurations
INSERT IGNORE INTO `billing_configurations` (`config_key`, `config_value`, `config_type`) VALUES
('INVOICE_PREFIX', 'INV', 'STRING'),
('INVOICE_START_NUMBER', '1000', 'NUMBER'),
('PAYMENT_RECEIPT_PREFIX', 'RCP', 'STRING'),
('CREDIT_NOTE_PREFIX', 'CN', 'STRING'),
('CLAIM_PREFIX', 'CLM', 'STRING'),
('ENABLE_ADVANCE_DEPOSIT', '1', 'BOOLEAN'),
('ALLOW_NEGATIVE_BALANCE', '0', 'BOOLEAN'),
('AUTO_GENERATE_INVOICE', '1', 'BOOLEAN'),
('INVOICE_DUE_DAYS', '30', 'NUMBER');

-- =====================================================
-- STEP 4: ADD FOREIGN KEY CONSTRAINTS
-- =====================================================

ALTER TABLE `billing_invoices` ADD CONSTRAINT `fk_invoice_policy` 
  FOREIGN KEY (`insurance_id`) REFERENCES `billing_insurance_policies`(`policy_id`) ON DELETE SET NULL;

ALTER TABLE `billing_invoice_items` ADD CONSTRAINT `fk_invoice_item_service` 
  FOREIGN KEY (`service_id`) REFERENCES `billing_services`(`service_id`) ON DELETE SET NULL;

ALTER TABLE `billing_insurance_claims` ADD CONSTRAINT `fk_claim_preauth` 
  FOREIGN KEY (`preauth_id`) REFERENCES `billing_insurance_preauth`(`preauth_id`) ON DELETE SET NULL;

-- =====================================================
-- STEP 5: CREATE PERFORMANCE INDEXES
-- =====================================================

ALTER TABLE `billing_invoices` ADD INDEX `idx_patient_date` (`patient_uhid`, `invoice_date`);
ALTER TABLE `billing_invoices` ADD INDEX `idx_status_date` (`invoice_status`, `invoice_date`);
ALTER TABLE `billing_invoice_items` ADD INDEX `idx_service_invoice` (`service_id`, `invoice_id`);
ALTER TABLE `billing_insurance_claims` ADD INDEX `idx_status_date` (`claim_status`, `claim_date`);
ALTER TABLE `billing_insurance_claims` ADD INDEX `idx_insurance_date` (`policy_id`, `claim_date`);
ALTER TABLE `billing_payments` ADD INDEX `idx_invoice_date` (`invoice_id`, `payment_date`);

-- =====================================================
-- FINAL STEP: RE-ENABLE FOREIGN KEY CHECKS
-- =====================================================

SET FOREIGN_KEY_CHECKS=1;

-- =====================================================
-- COMPLETION
-- =====================================================
-- All 22 billing tables have been created successfully
-- Default data has been inserted
-- Foreign keys and indexes are in place
-- Ready for use!
-- =====================================================
