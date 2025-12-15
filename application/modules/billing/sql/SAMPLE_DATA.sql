-- ============================================================================
-- BILLING MODULE - SAMPLE DATA FOR TESTING
-- ============================================================================
-- Purpose: Insert sample service categories and services for testing
-- Run this after FINAL_CLEAN_SETUP.sql
-- ============================================================================

USE vhms_rashmi_amc_2025;

-- Clear existing data (if any)
DELETE FROM billing_services WHERE 1=1;
DELETE FROM billing_service_categories WHERE 1=1;

-- ============================================================================
-- 1. INSERT SERVICE CATEGORIES
-- ============================================================================

INSERT INTO billing_service_categories 
(category_code, category_name, description, is_active, gst_applicable, gst_rate, created_by) 
VALUES 
('CONSULT', 'Consultation Services', 'Doctor consultation and follow-up visits', 1, 0, 0.00, 1),
('LAB', 'Laboratory Services', 'Diagnostic tests and pathology services', 1, 1, 5.00, 1),
('RADIOLOGY', 'Radiology Services', 'X-Ray, CT Scan, MRI, Ultrasound', 1, 1, 5.00, 1),
('PHARMACY', 'Pharmacy / Medicines', 'Medicines and pharmaceutical products', 1, 1, 12.00, 1),
('PROCEDURE', 'Medical Procedures', 'Minor and major medical procedures', 1, 1, 5.00, 1),
('SURGERY', 'Surgical Services', 'Surgical operations and related services', 1, 1, 5.00, 1),
('ROOM', 'Room & Accommodation', 'Ward, private room, ICU charges', 1, 0, 0.00, 1),
('EMERGENCY', 'Emergency Services', 'Emergency treatment and ambulance', 1, 1, 5.00, 1),
('AYURVEDA', 'Ayurvedic Treatments', 'Panchakarma and other Ayurvedic therapies', 1, 1, 5.00, 1),
('PHYSIO', 'Physiotherapy', 'Physical therapy and rehabilitation', 1, 1, 5.00, 1);

-- ============================================================================
-- 2. INSERT SERVICES
-- ============================================================================

-- CONSULTATION SERVICES
INSERT INTO billing_services 
(service_code, service_name, category_id, description, unit_price, gst_applicable, gst_rate, applicable_for_opd, applicable_for_ipd, is_active, created_by)
VALUES
('CONS001', 'General Consultation', 1, 'General physician consultation', 500.00, 0, 0.00, 1, 1, 1, 1),
('CONS002', 'Specialist Consultation', 1, 'Specialist doctor consultation', 800.00, 0, 0.00, 1, 1, 1, 1),
('CONS003', 'Follow-up Consultation', 1, 'Follow-up visit within 15 days', 300.00, 0, 0.00, 1, 1, 1, 1),
('CONS004', 'Emergency Consultation', 1, 'Emergency doctor consultation', 1000.00, 0, 0.00, 1, 1, 1, 1);

-- LABORATORY SERVICES
INSERT INTO billing_services 
(service_code, service_name, category_id, description, unit_price, gst_applicable, gst_rate, applicable_for_opd, applicable_for_ipd, is_active, created_by)
VALUES
('LAB001', 'Complete Blood Count (CBC)', 2, 'Full blood count test', 300.00, 1, 5.00, 1, 1, 1, 1),
('LAB002', 'Lipid Profile', 2, 'Cholesterol and lipid test', 600.00, 1, 5.00, 1, 1, 1, 1),
('LAB003', 'Blood Sugar Fasting', 2, 'Fasting blood glucose test', 100.00, 1, 5.00, 1, 1, 1, 1),
('LAB004', 'Blood Sugar PP', 2, 'Post-prandial blood glucose', 100.00, 1, 5.00, 1, 1, 1, 1),
('LAB005', 'HbA1c', 2, 'Glycated hemoglobin test', 500.00, 1, 5.00, 1, 1, 1, 1),
('LAB006', 'Thyroid Profile', 2, 'TSH, T3, T4 tests', 700.00, 1, 5.00, 1, 1, 1, 1),
('LAB007', 'Liver Function Test (LFT)', 2, 'Complete liver function tests', 800.00, 1, 5.00, 1, 1, 1, 1),
('LAB008', 'Kidney Function Test (KFT)', 2, 'Complete kidney function tests', 750.00, 1, 5.00, 1, 1, 1, 1),
('LAB009', 'Urine Routine', 2, 'Complete urine analysis', 150.00, 1, 5.00, 1, 1, 1, 1),
('LAB010', 'Stool Routine', 2, 'Stool examination', 150.00, 1, 5.00, 1, 1, 1, 1);

-- RADIOLOGY SERVICES
INSERT INTO billing_services 
(service_code, service_name, category_id, description, unit_price, gst_applicable, gst_rate, applicable_for_opd, applicable_for_ipd, is_active, created_by)
VALUES
('RAD001', 'X-Ray - Chest PA', 3, 'Chest X-ray posteroanterior view', 400.00, 1, 5.00, 1, 1, 1, 1),
('RAD002', 'X-Ray - Abdomen', 3, 'Abdominal X-ray', 500.00, 1, 5.00, 1, 1, 1, 1),
('RAD003', 'Ultrasound - Abdomen', 3, 'Abdominal ultrasound scan', 1200.00, 1, 5.00, 1, 1, 1, 1),
('RAD004', 'CT Scan - Head', 3, 'CT scan of brain', 3500.00, 1, 5.00, 1, 1, 1, 1),
('RAD005', 'MRI - Spine', 3, 'MRI of spine', 6000.00, 1, 5.00, 1, 1, 1, 1),
('RAD006', 'ECG', 3, 'Electrocardiogram', 200.00, 1, 5.00, 1, 1, 1, 1),
('RAD007', 'ECHO', 3, 'Echocardiography', 1500.00, 1, 5.00, 1, 1, 1, 1);

-- PHARMACY
INSERT INTO billing_services 
(service_code, service_name, category_id, description, unit_price, gst_applicable, gst_rate, requires_qty, applicable_for_opd, applicable_for_ipd, is_active, created_by)
VALUES
('PHARM001', 'Medicine - General', 4, 'General medicines', 100.00, 1, 12.00, 1, 1, 1, 1, 1),
('PHARM002', 'Medicine - Antibiotics', 4, 'Antibiotic medicines', 200.00, 1, 12.00, 1, 1, 1, 1, 1),
('PHARM003', 'IV Fluids', 4, 'Intravenous fluids', 150.00, 1, 12.00, 1, 1, 1, 1, 1),
('PHARM004', 'Injections', 4, 'Injectable medicines', 100.00, 1, 12.00, 1, 1, 1, 1, 1),
('PHARM005', 'Surgical Supplies', 4, 'Surgical consumables', 500.00, 1, 12.00, 1, 0, 1, 1, 1);

-- MEDICAL PROCEDURES
INSERT INTO billing_services 
(service_code, service_name, category_id, description, unit_price, gst_applicable, gst_rate, applicable_for_opd, applicable_for_ipd, is_active, created_by)
VALUES
('PROC001', 'Wound Dressing - Simple', 5, 'Simple wound dressing', 200.00, 1, 5.00, 1, 1, 1, 1),
('PROC002', 'Wound Dressing - Complex', 5, 'Complex wound dressing', 500.00, 1, 5.00, 1, 1, 1, 1),
('PROC003', 'Suturing - Minor', 5, 'Minor wound suturing', 800.00, 1, 5.00, 1, 1, 1, 1),
('PROC004', 'Catheterization', 5, 'Urinary catheter insertion', 600.00, 1, 5.00, 1, 1, 1, 1),
('PROC005', 'Nebulization', 5, 'Nebulizer treatment', 150.00, 1, 5.00, 1, 1, 1, 1),
('PROC006', 'IV Cannulation', 5, 'IV line insertion', 300.00, 1, 5.00, 1, 1, 1, 1);

-- SURGERY
INSERT INTO billing_services 
(service_code, service_name, category_id, description, unit_price, gst_applicable, gst_rate, applicable_for_opd, applicable_for_ipd, is_active, created_by)
VALUES
('SURG001', 'Appendectomy', 6, 'Appendix removal surgery', 25000.00, 1, 5.00, 0, 1, 1, 1),
('SURG002', 'Hernia Repair', 6, 'Hernia correction surgery', 30000.00, 1, 5.00, 0, 1, 1, 1),
('SURG003', 'Cesarean Section', 6, 'C-section delivery', 35000.00, 1, 5.00, 0, 1, 1, 1),
('SURG004', 'Cataract Surgery', 6, 'Cataract removal surgery', 20000.00, 1, 5.00, 0, 1, 1, 1);

-- ROOM & ACCOMMODATION
INSERT INTO billing_services 
(service_code, service_name, category_id, description, unit_price, gst_applicable, gst_rate, requires_qty, applicable_for_opd, applicable_for_ipd, is_active, created_by)
VALUES
('ROOM001', 'General Ward - Per Day', 7, 'General ward bed charges per day', 1000.00, 0, 0.00, 1, 0, 1, 1, 1),
('ROOM002', 'Semi-Private Room - Per Day', 7, 'Semi-private room per day', 2000.00, 0, 0.00, 1, 0, 1, 1, 1),
('ROOM003', 'Private Room - Per Day', 7, 'Private room per day', 3000.00, 0, 0.00, 1, 0, 1, 1, 1),
('ROOM004', 'ICU - Per Day', 7, 'Intensive care unit per day', 5000.00, 0, 0.00, 1, 0, 1, 1, 1),
('ROOM005', 'NICU - Per Day', 7, 'Neonatal ICU per day', 6000.00, 0, 0.00, 1, 0, 1, 1, 1);

-- EMERGENCY SERVICES
INSERT INTO billing_services 
(service_code, service_name, category_id, description, unit_price, gst_applicable, gst_rate, applicable_for_opd, applicable_for_ipd, is_active, created_by)
VALUES
('EMRG001', 'Emergency Registration', 8, 'Emergency department registration', 500.00, 1, 5.00, 1, 1, 1, 1),
('EMRG002', 'Ambulance Service - Basic', 8, 'Basic ambulance service', 1500.00, 1, 5.00, 1, 1, 1, 1),
('EMRG003', 'Ambulance Service - Advanced', 8, 'Advanced life support ambulance', 3000.00, 1, 5.00, 1, 1, 1, 1);

-- AYURVEDIC TREATMENTS
INSERT INTO billing_services 
(service_code, service_name, category_id, description, unit_price, gst_applicable, gst_rate, applicable_for_opd, applicable_for_ipd, is_active, created_by)
VALUES
('AYUR001', 'Abhyanga (Oil Massage)', 9, 'Full body ayurvedic oil massage', 800.00, 1, 5.00, 1, 1, 1, 1),
('AYUR002', 'Shirodhara', 9, 'Oil pouring therapy on forehead', 1200.00, 1, 5.00, 1, 1, 1, 1),
('AYUR003', 'Panchakarma - Vamana', 9, 'Therapeutic vomiting', 2500.00, 1, 5.00, 1, 1, 1, 1),
('AYUR004', 'Panchakarma - Virechana', 9, 'Therapeutic purgation', 2500.00, 1, 5.00, 1, 1, 1, 1),
('AYUR005', 'Nasya', 9, 'Nasal medication', 600.00, 1, 5.00, 1, 1, 1, 1),
('AYUR006', 'Basti', 9, 'Medicated enema', 1000.00, 1, 5.00, 1, 1, 1, 1);

-- PHYSIOTHERAPY
INSERT INTO billing_services 
(service_code, service_name, category_id, description, unit_price, gst_applicable, gst_rate, applicable_for_opd, applicable_for_ipd, is_active, created_by)
VALUES
('PHYSIO001', 'Physiotherapy Session', 10, 'Standard physio session', 500.00, 1, 5.00, 1, 1, 1, 1),
('PHYSIO002', 'Electrotherapy', 10, 'Electrical stimulation therapy', 400.00, 1, 5.00, 1, 1, 1, 1),
('PHYSIO003', 'Ultrasound Therapy', 10, 'Therapeutic ultrasound', 400.00, 1, 5.00, 1, 1, 1, 1),
('PHYSIO004', 'Traction Therapy', 10, 'Spine traction treatment', 600.00, 1, 5.00, 1, 1, 1, 1);

-- ============================================================================
-- VERIFICATION QUERIES
-- ============================================================================

-- Count categories
SELECT COUNT(*) as total_categories FROM billing_service_categories WHERE is_active = 1;

-- Count services per category
SELECT 
    c.category_name,
    COUNT(s.service_id) as service_count,
    MIN(s.unit_price) as min_price,
    MAX(s.unit_price) as max_price,
    AVG(s.unit_price) as avg_price
FROM billing_service_categories c
LEFT JOIN billing_services s ON c.category_id = s.category_id AND s.is_active = 1
WHERE c.is_active = 1
GROUP BY c.category_id, c.category_name
ORDER BY c.category_name;

-- List all services with categories
SELECT 
    c.category_name,
    s.service_code,
    s.service_name,
    s.unit_price,
    s.gst_rate,
    IF(s.applicable_for_opd, 'Yes', 'No') as OPD,
    IF(s.applicable_for_ipd, 'Yes', 'No') as IPD
FROM billing_services s
JOIN billing_service_categories c ON s.category_id = c.category_id
WHERE s.is_active = 1
ORDER BY c.category_name, s.service_name;

-- ============================================================================
-- SUCCESS MESSAGE
-- ============================================================================
SELECT 
    'Sample data inserted successfully!' as Message,
    (SELECT COUNT(*) FROM billing_service_categories WHERE is_active = 1) as Categories,
    (SELECT COUNT(*) FROM billing_services WHERE is_active = 1) as Services;
