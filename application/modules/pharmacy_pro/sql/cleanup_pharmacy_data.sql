-- Pharmacy Pro cleanup script
-- Use this before running sample_ayurveda_data.sql

SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE TABLE `purchase_order_items`;
TRUNCATE TABLE `medicine_purchase_items`;
TRUNCATE TABLE `medicine_sale_items`;
TRUNCATE TABLE `medicine_stock_adjustment_items`;
TRUNCATE TABLE `medicine_return_items`;
TRUNCATE TABLE `prescription_items`;

TRUNCATE TABLE `medicine_batches`;
TRUNCATE TABLE `medicine_purchases`;
TRUNCATE TABLE `purchase_orders`;
TRUNCATE TABLE `medicine_sales`;
TRUNCATE TABLE `prescriptions`;
TRUNCATE TABLE `medicine_stock_adjustments`;
TRUNCATE TABLE `medicine_returns`;

TRUNCATE TABLE `medicines`;
TRUNCATE TABLE `medicine_suppliers`;
TRUNCATE TABLE `medicine_manufacturers`;
TRUNCATE TABLE `medicine_units`;
TRUNCATE TABLE `medicine_categories`;
TRUNCATE TABLE `medicine_frequencies`;
TRUNCATE TABLE `daily_stock_summary`;

SET FOREIGN_KEY_CHECKS = 1;

SELECT 'Pharmacy cleanup completed.' AS status;
