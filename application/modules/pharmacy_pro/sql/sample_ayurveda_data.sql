-- Complete Sample Ayurveda Test Data for Pharmacy Module
-- Run this AFTER importing pharmacy_schema.sql

-- 0) Safety: disable foreign key checks for truncate
SET FOREIGN_KEY_CHECKS = 0;

-- Clear dependent tables first
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

-- 1) Insert master data in correct order

-- Medicine Categories (IDs will start at 1)
INSERT INTO `medicine_categories` (`category_name`, `category_code`, `description`, `status`) VALUES
('Churna', 'CHURN', 'Ayurvedic powdered medicines', 'active'),
('Vati/Gutika', 'VATI', 'Ayurvedic tablets and pills', 'active'),
('Asava/Arishta', 'ASAVA', 'Ayurvedic fermented liquid medicines', 'active'),
('Taila', 'TAILA', 'Ayurvedic medicated oils', 'active'),
('Ghrita', 'GHRIT', 'Ayurvedic medicated ghee preparations', 'active'),
('Kwatha', 'KWATH', 'Ayurvedic decoctions', 'active'),
('Rasayana', 'RASAN', 'Ayurvedic rejuvenative medicines', 'active'),
('Bhasma', 'BHASM', 'Ayurvedic calcined preparations', 'active');

-- Medicine Units (IDs will start at 1)
INSERT INTO `medicine_units` (`unit_name`, `unit_symbol`, `description`, `status`) VALUES
('Grams', 'gm', 'Weight measurement in grams', 'active'),
('Milliliters', 'ml', 'Volume measurement in milliliters', 'active'),
('Tablets', 'tab', 'Individual tablet count', 'active'),
('Capsules', 'cap', 'Individual capsule count', 'active'),
('Bottles', 'btl', 'Bottle packaging', 'active'),
('Packets', 'pkt', 'Packet packaging', 'active'),
('Vials', 'vial', 'Small glass containers', 'active'),
('Pieces', 'pcs', 'Individual pieces', 'active');

-- Medicine Manufacturers (IDs will start at 1)
INSERT INTO `medicine_manufacturers` (`company_name`, `company_code`, `license_number`, `address`, `contact_person`, `phone`, `email`, `gst_number`, `status`) VALUES
('Dabur India Ltd', 'DABUR', 'DL-1234-2020', 'Ghaziabad, Uttar Pradesh', 'Ramesh Kumar', '+91-120-3982000', 'info@dabur.com', '09AABCD1234E1Z1', 'active'),
('Himalaya Drug Company', 'HIMAL', 'KA-5678-2019', 'Bangalore, Karnataka', 'Priya Sharma', '+91-80-7142000', 'contact@himalayawellness.com', '29AABCD1234E1Z2', 'active'),
('Baidyanath Group', 'BAIDY', 'WB-9012-2018', 'Kolkata, West Bengal', 'Suresh Patel', '+91-33-2237-8102', 'info@baidyanath.co.in', '19AABCD1234E1Z3', 'active'),
('Patanjali Ayurved', 'PATAN', 'UT-3456-2017', 'Haridwar, Uttarakhand', 'Kavita Nair', '+91-1334-610111', 'contact@patanjaliayurved.net', '05AABCD1234E1Z4', 'active'),
('Zandu Pharmaceuticals', 'ZANDU', 'MH-7890-2016', 'Mumbai, Maharashtra', 'Amit Singh', '+91-22-6198-2000', 'info@zandu.com', '27AABCD1234E1Z5', 'active'),
('Arya Vaidya Sala', 'AVS', 'KL-2345-2015', 'Malappuram, Kerala', 'Dr. Radhika', '+91-483-274-4212', 'info@aryavaidyasala.com', '32AABCD1234E1Z6', 'active'),
('Dhootapapeshwar Limited', 'DHOOT', 'MH-6789-2014', 'Mumbai, Maharashtra', 'Rajesh Gupta', '+91-22-2431-1316', 'info@sdpl.net', '27AABCD1234E1Z7', 'active'),
('Charak Pharma', 'CHARA', 'MH-0123-2013', 'Mumbai, Maharashtra', 'Neeta Shah', '+91-22-2672-0042', 'info@charak.com', '27AABCD1234E1Z8', 'active'),
('Kerala Ayurveda Ltd', 'KERAL', 'KL-4567-2012', 'Aluva, Kerala', 'Dr. Krishnan', '+91-484-262-3300', 'info@keralaayurveda.biz', '32AABCD1234E1Z9', 'active'),
('Nagarjuna Herbal Concentrates', 'NAGAR', 'TS-8901-2011', 'Hyderabad, Telangana', 'Venkat Reddy', '+91-40-2373-1456', 'info@nagarjunaherbal.com', '36AABCD1234E1Z0', 'active');

-- Medicine Frequencies
INSERT INTO `medicine_frequencies` (`frequency_name`, `frequency_code`, `times_per_day`, `description`, `status`) VALUES
('Once Daily', 'OD', 1, 'To be taken once daily', 'active'),
('Twice Daily', 'BD', 2, 'To be taken twice daily (Morning & Evening)', 'active'),
('Thrice Daily', 'TDS', 3, 'To be taken thrice daily (Morning, Afternoon & Evening)', 'active'),
('Four Times Daily', 'QDS', 4, 'To be taken four times daily', 'active'),
('Before Meals', 'AC', 0, 'To be taken before meals', 'active'),
('After Meals', 'PC', 0, 'To be taken after meals', 'active'),
('At Bedtime', 'HS', 0, 'To be taken at bedtime', 'active'),
('As Required', 'PRN', 0, 'To be taken as and when required', 'active');

-- Suppliers
INSERT INTO `medicine_suppliers` (`supplier_name`, `supplier_code`, `contact_person`, `address`, `phone`, `email`, `gst_number`, `drug_license`, `credit_limit`, `payment_terms`, `status`) VALUES
('Ayurveda Distributors Pvt Ltd', 'AYUDIS', 'Ramesh Kumar', 'Medical Plaza, MG Road, Bangalore, Karnataka - 560001', '+91-9876543210', 'ramesh@ayurvedadist.com', '29ABCDE1234F1Z5', 'KA-DL-2023-001', 50000.00, '30 Days Credit', 'active'),
('Herbal Medicine Suppliers', 'HERBMED', 'Priya Sharma', 'Ayurveda Complex, Civil Lines, Delhi - 110001', '+91-9876543211', 'priya@herbalmeds.co.in', '07ABCDE1234F1Z6', 'DL-DL-2023-002', 75000.00, '45 Days Credit', 'active'),
('Natural Healthcare Distributors', 'NATHC', 'Suresh Patel', 'Wellness Center, Station Road, Ahmedabad, Gujarat - 380001', '+91-9876543212', 'suresh@naturalhc.com', '24ABCDE1234F1Z7', 'GJ-DL-2023-003', 100000.00, '60 Days Credit', 'active'),
('Ayush Medical Store', 'AYUSHMED', 'Kavitha Nair', 'Ayurveda Lane, Fort Kochi, Kerala - 682001', '+91-9876543213', 'kavitha@ayushmed.in', '32ABCDE1234F1Z8', 'KL-DL-2023-004', 40000.00, '30 Days Credit', 'active');

-- 2) Insert medicines (use allowed enum values and 'yes'/'no' for prescription_required)
INSERT INTO `medicines` (`medicine_code`, `medicine_name`, `generic_name`, `category_id`, `manufacturer_id`, `unit_id`, `medicine_type`, `composition`, `strength`, `therapeutic_class`, `prescription_required`, `reorder_level`, `status`) VALUES
('MED250001', 'Triphala Churna', 'Triphala Powder', 1, 1, 1, 'powder', 'Amalaki, Bibhitaki, Haritaki', '100gm', 'Digestive', 'no', 10, 'active'),
('MED250002', 'Chyawanprash', 'Chyawanprash Avaleha', 7, 1, 1, 'others', 'Amalaki, Ashwagandha, Guduchi', '500gm', 'Rasayana', 'no', 5, 'active'),
('MED250003', 'Ashwagandha Churna', 'Withania Somnifera', 1, 2, 1, 'powder', 'Ashwagandha Root Powder', '100gm', 'Nervine Tonic', 'no', 15, 'active'),
('MED250004', 'Brahmi Vati', 'Bacopa Monnieri Tablets', 2, 3, 3, 'tablet', 'Brahmi, Shankhpushpi, Mandukaparni', '250mg', 'Nervine Tonic', 'no', 20, 'active'),
('MED250005', 'Mahanarayan Taila', 'Mahanarayan Oil', 4, 4, 2, 'others', 'Bilwa, Agnimantha, Shyonak', '100ml', 'External Application', 'no', 8, 'active'),
('MED250006', 'Arjunarishta', 'Terminalia Arjuna Fermented Liquid', 3, 5, 2, 'others', 'Arjuna, Dhataki Pushpa, Jaggery', '450ml', 'Cardiac Tonic', 'no', 12, 'active'),
('MED250007', 'Saraswatarishta', 'Brain Tonic Liquid', 3, 6, 2, 'others', 'Brahmi, Shankhpushpi, Vacha', '450ml', 'Nervine Tonic', 'no', 10, 'active'),
('MED250008', 'Chandraprabha Vati', 'Urinary Wellness Tablets', 2, 7, 3, 'tablet', 'Shilajit, Guggulu, Chandana', '500mg', 'Genitourinary', 'no', 25, 'active'),
('MED250009', 'Dashmularishta', 'Ten Roots Fermented Liquid', 3, 8, 2, 'others', 'Dashmula, Dhataki, Jaggery', '450ml', 'General Tonic', 'no', 8, 'active'),
('MED250010', 'Shatavari Churna', 'Asparagus Racemosus', 1, 2, 1, 'powder', 'Shatavari Root Powder', '100gm', 'Female Health', 'no', 15, 'active'),
('MED250011', 'Abhayarishta', 'Digestive Fermented Liquid', 3, 9, 2, 'others', 'Haritaki, Vidanga, Dhataki', '450ml', 'Digestive', 'no', 10, 'active'),
('MED250012', 'Yograj Guggulu', 'Joint Health Tablets', 2, 1, 3, 'tablet', 'Guggulu, Chitrak, Pippali', '250mg', 'Musculoskeletal', 'no', 30, 'active'),
('MED250013', 'Brahmi Ghrita', 'Medicated Ghee for Brain', 5, 6, 1, 'others', 'Brahmi, Mandukaparni, Go-Ghrita', '200gm', 'Nervine Tonic', 'no', 6, 'active'),
('MED250014', 'Kumaryasava', 'Aloe Vera Fermented Liquid', 3, 3, 2, 'others', 'Kumari, Dhataki, Honey', '450ml', 'Digestive', 'no', 12, 'active'),
('MED250015', 'Panchasakar Churna', 'Mild Laxative Powder', 1, 5, 1, 'powder', 'Senna, Haritaki, Saunf', '100gm', 'Digestive', 'no', 20, 'active'),
('MED250016', 'Khadirarishta', 'Blood Purifier Liquid', 3, 7, 2, 'others', 'Khadira, Manjistha, Dhataki', '450ml', 'Blood Purifier', 'no', 10, 'active'),
('MED250017', 'Giloy Churna', 'Tinospora Cordifolia', 1, 4, 1, 'powder', 'Guduchi Stem Powder', '100gm', 'Immunomodulator', 'no', 18, 'active'),
('MED250018', 'Mahamanjisthadi Kwatha', 'Blood Purifier Decoction', 6, 8, 2, 'others', 'Manjistha, Haridra, Daruharidra', '450ml', 'Blood Purifier', 'no', 8, 'active'),
('MED250019', 'Swarna Bhasma', 'Gold Calcined Powder', 8, 10, 1, 'others', 'Purified Gold', '125mg', 'Cardiac & Nervous', 'yes', 2, 'active'),
('MED250020', 'Mukta Pishti', 'Pearl Calcium Compound', 8, 7, 1, 'others', 'Purified Pearl', '1gm', 'Cardiac & Cooling', 'yes', 3, 'active');

-- 3) Insert medicine batches (use full column list to match schema)
INSERT INTO `medicine_batches` (
  `medicine_id`, `batch_number`, `manufacturing_date`, `expiry_date`, `mrp`, `purchase_price`, `selling_price`,
  `gst_percentage`, `discount_percentage`, `opening_stock`, `current_stock`, `reserved_stock`, `minimum_stock`,
  `rack_number`, `location`, `supplier_id`, `purchase_invoice_number`, `purchase_date`, `status`
) VALUES
-- Triphala Churna
(1, 'TC2024001', '2024-01-15', '2026-01-14', 75.00, 45.00, 65.00, 12.00, 0.00, 50, 50, 0, 10, 'A1', 'Shelf-A', 1, 'PI-TC-001', '2024-01-15', 'active'),
(1, 'TC2024002', '2024-03-20', '2026-03-19', 76.00, 46.00, 66.00, 12.00, 0.00, 75, 75, 0, 10, 'A1', 'Shelf-A', 1, 'PI-TC-002', '2024-03-20', 'active'),
-- Chyawanprash
(2, 'CP2024001', '2024-02-10', '2026-02-09', 280.00, 180.00, 250.00, 12.00, 0.00, 30, 30, 0, 5, 'B2', 'Shelf-B', 1, 'PI-CP-001', '2024-02-10', 'active'),
(2, 'CP2024002', '2024-04-15', '2026-04-14', 282.00, 182.00, 252.00, 12.00, 0.00, 25, 25, 0, 5, 'B2', 'Shelf-B', 1, 'PI-CP-002', '2024-04-15', 'active'),
-- Ashwagandha Churna
(3, 'AC2024001', '2024-01-25', '2026-01-24', 140.00, 85.00, 120.00, 12.00, 0.00, 40, 40, 0, 15, 'A2', 'Shelf-A', 2, 'PI-AC-001', '2024-01-25', 'active'),
(3, 'AC2024002', '2024-03-30', '2026-03-29', 142.00, 87.00, 122.00, 12.00, 0.00, 60, 60, 0, 15, 'A2', 'Shelf-A', 2, 'PI-AC-002', '2024-03-30', 'active'),
-- Brahmi Vati
(4, 'BV2024001', '2024-02-05', '2026-02-04', 155.00, 95.00, 135.00, 12.00, 0.00, 80, 80, 0, 20, 'C1', 'Shelf-C', 3, 'PI-BV-001', '2024-02-05', 'active'),
(4, 'BV2024002', '2024-04-20', '2026-04-19', 157.00, 97.00, 137.00, 12.00, 0.00, 65, 65, 0, 20, 'C1', 'Shelf-C', 3, 'PI-BV-002', '2024-04-20', 'active'),
-- Mahanarayan Taila
(5, 'MT2024001', '2024-01-30', '2026-01-29', 195.00, 120.00, 170.00, 12.00, 0.00, 25, 25, 0, 8, 'D1', 'Shelf-D', 4, 'PI-MT-001', '2024-01-30', 'active'),
-- Arjunarishta
(6, 'AR2024001', '2024-02-20', '2026-02-19', 180.00, 110.00, 155.00, 12.00, 0.00, 35, 35, 0, 12, 'E1', 'Shelf-E', 1, 'PI-AR-001', '2024-02-20', 'active'),
-- Saraswatarishta
(7, 'SR2024001', '2024-03-10', '2026-03-09', 185.00, 115.00, 160.00, 12.00, 0.00, 28, 28, 0, 10, 'E2', 'Shelf-E', 2, 'PI-SR-001', '2024-03-10', 'active'),
-- Chandraprabha Vati
(8, 'CV2024001', '2024-01-20', '2026-01-19', 210.00, 130.00, 185.00, 12.00, 0.00, 90, 90, 0, 25, 'F1', 'Shelf-F', 3, 'PI-CV-001', '2024-01-20', 'active'),
-- Dashmularishta
(9, 'DR2024001', '2024-02-28', '2026-02-27', 175.00, 105.00, 150.00, 12.00, 0.00, 22, 22, 0, 8, 'G1', 'Shelf-G', 4, 'PI-DR-001', '2024-02-28', 'active'),
-- Shatavari Churna
(10, 'SC2024001', '2024-03-05', '2026-03-04', 145.00, 90.00, 125.00, 12.00, 0.00, 55, 55, 0, 15, 'H1', 'Shelf-H', 2, 'PI-SC-001', '2024-03-05', 'active'),
-- Additional batches
(11, 'AB2024001', '2024-01-18', '2026-01-17', 165.00, 100.00, 140.00, 12.00, 0.00, 18, 18, 0, 10, 'I1', 'Shelf-I', 1, 'PI-AB-001', '2024-01-18', 'active'),
(12, 'YG2024001', '2024-02-12', '2026-02-11', 230.00, 140.00, 200.00, 12.00, 0.00, 75, 75, 0, 30, 'J1', 'Shelf-J', 3, 'PI-YG-001', '2024-02-12', 'active'),
(13, 'BG2024001', '2024-03-25', '2026-03-24', 450.00, 280.00, 400.00, 12.00, 0.00, 12, 12, 0, 6, 'K1', 'Shelf-K', 4, 'PI-BG-001', '2024-03-25', 'active'),
(14, 'KA2024001', '2024-02-08', '2026-02-07', 160.00, 95.00, 135.00, 12.00, 0.00, 20, 20, 0, 12, 'L1', 'Shelf-L', 2, 'PI-KA-001', '2024-02-08', 'active'),
(15, 'PC2024001', '2024-01-12', '2026-01-11', 95.00, 55.00, 80.00, 12.00, 0.00, 45, 45, 0, 20, 'M1', 'Shelf-M', 1, 'PI-PC-001', '2024-01-12', 'active'),
(16, 'KR2024001', '2024-03-15', '2026-03-14', 175.00, 108.00, 152.00, 12.00, 0.00, 15, 15, 0, 10, 'N1', 'Shelf-N', 3, 'PI-KR-001', '2024-03-15', 'active'),
(17, 'GC2024001', '2024-02-25', '2026-02-24', 130.00, 75.00, 110.00, 12.00, 0.00, 38, 38, 0, 18, 'O1', 'Shelf-O', 4, 'PI-GC-001', '2024-02-25', 'active'),
(18, 'MK2024001', '2024-01-28', '2026-01-27', 200.00, 125.00, 175.00, 12.00, 0.00, 12, 12, 0, 8, 'P1', 'Shelf-P', 2, 'PI-MK-001', '2024-01-28', 'active'),
(19, 'SB2024001', '2024-04-01', '2026-03-31', 4000.00, 2500.00, 3500.00, 12.00, 0.00, 5, 5, 0, 2, 'Q1', 'Shelf-Q', 1, 'PI-SB-001', '2024-04-01', 'active'),
(20, 'MP2024001', '2024-03-20', '2026-03-19', 750.00, 450.00, 650.00, 12.00, 0.00, 8, 8, 0, 3, 'R1', 'Shelf-R', 3, 'PI-MP-001', '2024-03-20', 'active');

-- 4) Optional: sample purchase records (links medicine_purchases and medicine_purchase_items)
INSERT INTO `medicine_purchases` (`invoice_number`, `supplier_id`, `po_id`, `purchase_date`, `invoice_date`, `subtotal`, `tax_amount`, `discount_amount`, `freight_charges`, `other_charges`, `total_amount`, `payment_mode`, `payment_status`, `remarks`, `created_by`) VALUES
('INV-AYU-001', 1, NULL, '2024-01-15', '2024-01-15', 2250.00, 270.00, 0.00, 0.00, 0.00, 2520.00, 'credit', 'paid', 'Initial stock for Triphala', 1),
('INV-AYU-002', 1, NULL, '2024-02-10', '2024-02-10', 4500.00, 540.00, 0.00, 0.00, 0.00, 5040.00, 'credit', 'paid', 'Initial stock for Chyawanprash', 1);

INSERT INTO `medicine_purchase_items` (`purchase_id`, `medicine_id`, `batch_number`, `manufacturing_date`, `expiry_date`, `quantity`, `free_quantity`, `unit_purchase_price`, `unit_selling_price`, `mrp`, `discount_percentage`, `gst_percentage`, `total_amount`, `rack_number`) VALUES
(LAST_INSERT_ID()-1, 1, 'TC2024001', '2024-01-15', '2026-01-14', 50, 0, 45.00, 65.00, 75.00, 0.00, 12.00, 2250.00, 'A1'),
(LAST_INSERT_ID(), 2, 'CP2024001', '2024-02-10', '2026-02-09', 30, 0, 180.00, 250.00, 280.00, 0.00, 12.00, 5400.00, 'B2');

-- 5) Summary query
SELECT 'Sample Ayurveda data inserted successfully!' as Status,
       (SELECT COUNT(*) FROM medicine_categories WHERE status = 'active') as Categories,
       (SELECT COUNT(*) FROM medicine_manufacturers WHERE status = 'active') as Manufacturers,
       (SELECT COUNT(*) FROM medicines WHERE status = 'active') as Medicines,
       (SELECT COUNT(*) FROM medicine_batches WHERE status = 'active') as Batches,
       (SELECT SUM(current_stock) FROM medicine_batches WHERE status = 'active') as 'Total Stock Units';
