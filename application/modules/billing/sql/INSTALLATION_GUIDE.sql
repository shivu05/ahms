-- =====================================================
-- BILLING MODULE INSTALLATION GUIDE
-- =====================================================

/*
INSTALLATION STEPS:

1. Copy the billing module folder to:
   application/modules/billing/

2. Execute this SQL script to create all necessary tables:
   - Open your database management tool (PHPMyAdmin, etc.)
   - Select your VHMS database
   - Click "Import" and select this SQL file
   - Click "Go" to execute

3. Verify tables are created:
   - Check for all billing_* tables in your database

4. Optionally add menu permissions if using RBAC:
   - See PERMISSIONS.sql file

5. Test the module:
   - Navigate to: /billing in your VHMS application
   - You should see the Billing Dashboard

TROUBLESHOOTING:

- If tables don't create, check your database user has CREATE privilege
- If invoice creation fails, verify billing_invoices table exists
- If payments fail, check billing_payments table exists
- For insurance features, ensure billing_insurance_* tables exist

IMPORTANT NOTES:

- All currency amounts are stored as DECIMAL(12,2)
- All dates follow Y-m-d format (ISO 8601)
- Timestamps include timezone information
- All status fields use ENUM for data integrity
- Extensive indexing for query performance
- Foreign key constraints for referential integrity

For support or issues, check the README.md file in this directory.
*/

-- Execute the main billing module SQL file
-- This file is in: application/modules/billing/sql/billing_module.sql

-- After successful execution:

-- 1. Verify tables created
SELECT COUNT(*) as table_count 
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME LIKE 'billing%';

-- Expected result: 27 tables

-- 2. Check default data
SELECT COUNT(*) FROM billing_payment_methods;
-- Expected: 7 payment methods

SELECT COUNT(*) FROM billing_service_categories;
-- Expected: 8 service categories

SELECT COUNT(*) FROM billing_tax_configurations;
-- Expected: 3 tax configurations

SELECT COUNT(*) FROM billing_configurations;
-- Expected: 9 configuration entries

-- 3. Create indexes for performance
-- These are already included in the main SQL file

-- 4. Next Steps:
-- a) Add insurance companies via UI or manually:
INSERT INTO billing_insurance_companies 
(company_code, company_name, email, phone, is_active) 
VALUES 
('ICICI', 'ICICI Lombard', 'support@icici.com', '1860-266-7766', 1),
('HDFC', 'HDFC Ergo', 'support@hdfc-ergo.com', '1860-180-8680', 1),
('APOLLO', 'Apollo Munich', 'support@apollomunich.com', '1800-2008-6969', 1),
('ADITYA', 'Aditya Birla', 'support@adityabirla.com', '1860-210-4444', 1);

-- b) Add services:
INSERT INTO billing_services 
(service_code, service_name, category_id, unit_price, is_active) 
VALUES 
('CONS001', 'General Consultation', 1, 500.00, 1),
('PROC001', 'Minor Surgery', 3, 5000.00, 1),
('WARD001', 'General Ward - Single Bed', 4, 1500.00, 1),
('PATH001', 'Blood Test', 5, 300.00, 1);

-- c) Configure GST if needed:
INSERT INTO billing_tax_configurations 
(tax_name, tax_rate, effective_from, is_active) 
VALUES 
('GST @ 5%', 5.00, CURDATE(), 1),
('GST @ 12%', 12.00, CURDATE(), 0),
('GST @ 18%', 18.00, CURDATE(), 0);

-- =====================================================
-- END OF INSTALLATION GUIDE
-- =====================================================
