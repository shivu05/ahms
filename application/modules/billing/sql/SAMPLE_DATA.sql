-- ============================================================================
-- BILLING MODULE - SAMPLE DATA FOR TESTING
-- ============================================================================
-- Purpose: Insert sample service categories and services for testing
-- Run this after FINAL_CLEAN_SETUP.sql
-- ============================================================================

--USE vhms_rashmi_amc_2025;

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

-- Resolve category IDs dynamically to avoid FK failures when IDs are not 1..10
SET @cat_consult   = (SELECT category_id FROM billing_service_categories WHERE category_code = 'CONSULT' LIMIT 1);
SET @cat_lab       = (SELECT category_id FROM billing_service_categories WHERE category_code = 'LAB' LIMIT 1);
SET @cat_radiology = (SELECT category_id FROM billing_service_categories WHERE category_code = 'RADIOLOGY' LIMIT 1);
SET @cat_pharmacy  = (SELECT category_id FROM billing_service_categories WHERE category_code = 'PHARMACY' LIMIT 1);
SET @cat_procedure = (SELECT category_id FROM billing_service_categories WHERE category_code = 'PROCEDURE' LIMIT 1);
SET @cat_surgery   = (SELECT category_id FROM billing_service_categories WHERE category_code = 'SURGERY' LIMIT 1);
SET @cat_room      = (SELECT category_id FROM billing_service_categories WHERE category_code = 'ROOM' LIMIT 1);
SET @cat_emergency = (SELECT category_id FROM billing_service_categories WHERE category_code = 'EMERGENCY' LIMIT 1);
SET @cat_ayurveda  = (SELECT category_id FROM billing_service_categories WHERE category_code = 'AYURVEDA' LIMIT 1);
SET @cat_physio    = (SELECT category_id FROM billing_service_categories WHERE category_code = 'PHYSIO' LIMIT 1);

-- ============================================================================
-- 2. INSERT SERVICES
-- ============================================================================

-- CONSULTATION SERVICES
INSERT INTO billing_services 
(service_code, service_name, category_id, description, unit_price, gst_applicable, gst_rate, applicable_for_opd, applicable_for_ipd, is_active, created_by)
VALUES
('CONS001', 'General Consultation', @cat_consult, 'General physician consultation', 500.00, 0, 0.00, 1, 1, 1, 1),
('CONS002', 'Specialist Consultation', @cat_consult, 'Specialist doctor consultation', 800.00, 0, 0.00, 1, 1, 1, 1),
('CONS003', 'Follow-up Consultation', @cat_consult, 'Follow-up visit within 15 days', 300.00, 0, 0.00, 1, 1, 1, 1),
('CONS004', 'Emergency Consultation', @cat_consult, 'Emergency doctor consultation', 1000.00, 0, 0.00, 1, 1, 1, 1);

-- LABORATORY SERVICES
INSERT INTO billing_services 
(service_code, service_name, category_id, description, unit_price, gst_applicable, gst_rate, applicable_for_opd, applicable_for_ipd, is_active, created_by)
VALUES
('LAB001', 'Complete Blood Count (CBC)', @cat_lab, 'Full blood count test', 300.00, 1, 5.00, 1, 1, 1, 1),
('LAB002', 'Lipid Profile', @cat_lab, 'Cholesterol and lipid test', 600.00, 1, 5.00, 1, 1, 1, 1),
('LAB003', 'Blood Sugar Fasting', @cat_lab, 'Fasting blood glucose test', 100.00, 1, 5.00, 1, 1, 1, 1),
('LAB004', 'Blood Sugar PP', @cat_lab, 'Post-prandial blood glucose', 100.00, 1, 5.00, 1, 1, 1, 1),
('LAB005', 'HbA1c', @cat_lab, 'Glycated hemoglobin test', 500.00, 1, 5.00, 1, 1, 1, 1),
('LAB006', 'Thyroid Profile', @cat_lab, 'TSH, T3, T4 tests', 700.00, 1, 5.00, 1, 1, 1, 1),
('LAB007', 'Liver Function Test (LFT)', @cat_lab, 'Complete liver function tests', 800.00, 1, 5.00, 1, 1, 1, 1),
('LAB008', 'Kidney Function Test (KFT)', @cat_lab, 'Complete kidney function tests', 750.00, 1, 5.00, 1, 1, 1, 1),
('LAB009', 'Urine Routine', @cat_lab, 'Complete urine analysis', 150.00, 1, 5.00, 1, 1, 1, 1),
('LAB010', 'Stool Routine', @cat_lab, 'Stool examination', 150.00, 1, 5.00, 1, 1, 1, 1);

-- RADIOLOGY SERVICES
INSERT INTO billing_services 
(service_code, service_name, category_id, description, unit_price, gst_applicable, gst_rate, applicable_for_opd, applicable_for_ipd, is_active, created_by)
VALUES
('RAD001', 'X-Ray - Chest PA', @cat_radiology, 'Chest X-ray posteroanterior view', 400.00, 1, 5.00, 1, 1, 1, 1),
('RAD002', 'X-Ray - Abdomen', @cat_radiology, 'Abdominal X-ray', 500.00, 1, 5.00, 1, 1, 1, 1),
('RAD003', 'Ultrasound - Abdomen', @cat_radiology, 'Abdominal ultrasound scan', 1200.00, 1, 5.00, 1, 1, 1, 1),
('RAD004', 'CT Scan - Head', @cat_radiology, 'CT scan of brain', 3500.00, 1, 5.00, 1, 1, 1, 1),
('RAD005', 'MRI - Spine', @cat_radiology, 'MRI of spine', 6000.00, 1, 5.00, 1, 1, 1, 1),
('RAD006', 'ECG', @cat_radiology, 'Electrocardiogram', 200.00, 1, 5.00, 1, 1, 1, 1),
('RAD007', 'ECHO', @cat_radiology, 'Echocardiography', 1500.00, 1, 5.00, 1, 1, 1, 1);

-- PHARMACY
INSERT INTO billing_services 
(service_code, service_name, category_id, description, unit_price, gst_applicable, gst_rate, requires_qty, applicable_for_opd, applicable_for_ipd, is_active, created_by)
VALUES
('PHARM001', 'Medicine - General', @cat_pharmacy, 'General medicines', 100.00, 1, 12.00, 1, 1, 1, 1, 1),
('PHARM002', 'Medicine - Antibiotics', @cat_pharmacy, 'Antibiotic medicines', 200.00, 1, 12.00, 1, 1, 1, 1, 1),
('PHARM003', 'IV Fluids', @cat_pharmacy, 'Intravenous fluids', 150.00, 1, 12.00, 1, 1, 1, 1, 1),
('PHARM004', 'Injections', @cat_pharmacy, 'Injectable medicines', 100.00, 1, 12.00, 1, 1, 1, 1, 1),
('PHARM005', 'Surgical Supplies', @cat_pharmacy, 'Surgical consumables', 500.00, 1, 12.00, 1, 0, 1, 1, 1);

-- MEDICAL PROCEDURES
INSERT INTO billing_services 
(service_code, service_name, category_id, description, unit_price, gst_applicable, gst_rate, applicable_for_opd, applicable_for_ipd, is_active, created_by)
VALUES
('PROC001', 'Wound Dressing - Simple', @cat_procedure, 'Simple wound dressing', 200.00, 1, 5.00, 1, 1, 1, 1),
('PROC002', 'Wound Dressing - Complex', @cat_procedure, 'Complex wound dressing', 500.00, 1, 5.00, 1, 1, 1, 1),
('PROC003', 'Suturing - Minor', @cat_procedure, 'Minor wound suturing', 800.00, 1, 5.00, 1, 1, 1, 1),
('PROC004', 'Catheterization', @cat_procedure, 'Urinary catheter insertion', 600.00, 1, 5.00, 1, 1, 1, 1),
('PROC005', 'Nebulization', @cat_procedure, 'Nebulizer treatment', 150.00, 1, 5.00, 1, 1, 1, 1),
('PROC006', 'IV Cannulation', @cat_procedure, 'IV line insertion', 300.00, 1, 5.00, 1, 1, 1, 1);

-- SURGERY
INSERT INTO billing_services 
(service_code, service_name, category_id, description, unit_price, gst_applicable, gst_rate, applicable_for_opd, applicable_for_ipd, is_active, created_by)
VALUES
('SURG001', 'Appendectomy', @cat_surgery, 'Appendix removal surgery', 25000.00, 1, 5.00, 0, 1, 1, 1),
('SURG002', 'Hernia Repair', @cat_surgery, 'Hernia correction surgery', 30000.00, 1, 5.00, 0, 1, 1, 1),
('SURG003', 'Cesarean Section', @cat_surgery, 'C-section delivery', 35000.00, 1, 5.00, 0, 1, 1, 1),
('SURG004', 'Cataract Surgery', @cat_surgery, 'Cataract removal surgery', 20000.00, 1, 5.00, 0, 1, 1, 1);

-- ROOM & ACCOMMODATION
INSERT INTO billing_services 
(service_code, service_name, category_id, description, unit_price, gst_applicable, gst_rate, requires_qty, applicable_for_opd, applicable_for_ipd, is_active, created_by)
VALUES
('ROOM001', 'General Ward - Per Day', @cat_room, 'General ward bed charges per day', 1000.00, 0, 0.00, 1, 0, 1, 1, 1),
('ROOM002', 'Semi-Private Room - Per Day', @cat_room, 'Semi-private room per day', 2000.00, 0, 0.00, 1, 0, 1, 1, 1),
('ROOM003', 'Private Room - Per Day', @cat_room, 'Private room per day', 3000.00, 0, 0.00, 1, 0, 1, 1, 1),
('ROOM004', 'ICU - Per Day', @cat_room, 'Intensive care unit per day', 5000.00, 0, 0.00, 1, 0, 1, 1, 1),
('ROOM005', 'NICU - Per Day', @cat_room, 'Neonatal ICU per day', 6000.00, 0, 0.00, 1, 0, 1, 1, 1);

-- EMERGENCY SERVICES
INSERT INTO billing_services 
(service_code, service_name, category_id, description, unit_price, gst_applicable, gst_rate, applicable_for_opd, applicable_for_ipd, is_active, created_by)
VALUES
('EMRG001', 'Emergency Registration', @cat_emergency, 'Emergency department registration', 500.00, 1, 5.00, 1, 1, 1, 1),
('EMRG002', 'Ambulance Service - Basic', @cat_emergency, 'Basic ambulance service', 1500.00, 1, 5.00, 1, 1, 1, 1),
('EMRG003', 'Ambulance Service - Advanced', @cat_emergency, 'Advanced life support ambulance', 3000.00, 1, 5.00, 1, 1, 1, 1);

-- AYURVEDIC TREATMENTS
INSERT INTO billing_services 
(service_code, service_name, category_id, description, unit_price, gst_applicable, gst_rate, applicable_for_opd, applicable_for_ipd, is_active, created_by)
VALUES
('AYUR001', 'Abhyanga (Oil Massage)', @cat_ayurveda, 'Full body ayurvedic oil massage', 800.00, 1, 5.00, 1, 1, 1, 1),
('AYUR002', 'Shirodhara', @cat_ayurveda, 'Oil pouring therapy on forehead', 1200.00, 1, 5.00, 1, 1, 1, 1),
('AYUR003', 'Panchakarma - Vamana', @cat_ayurveda, 'Therapeutic vomiting', 2500.00, 1, 5.00, 1, 1, 1, 1),
('AYUR004', 'Panchakarma - Virechana', @cat_ayurveda, 'Therapeutic purgation', 2500.00, 1, 5.00, 1, 1, 1, 1),
('AYUR005', 'Nasya', @cat_ayurveda, 'Nasal medication', 600.00, 1, 5.00, 1, 1, 1, 1),
('AYUR006', 'Basti', @cat_ayurveda, 'Medicated enema', 1000.00, 1, 5.00, 1, 1, 1, 1);

-- PHYSIOTHERAPY
INSERT INTO billing_services 
(service_code, service_name, category_id, description, unit_price, gst_applicable, gst_rate, applicable_for_opd, applicable_for_ipd, is_active, created_by)
VALUES
('PHYSIO001', 'Physiotherapy Session', @cat_physio, 'Standard physio session', 500.00, 1, 5.00, 1, 1, 1, 1),
('PHYSIO002', 'Electrotherapy', @cat_physio, 'Electrical stimulation therapy', 400.00, 1, 5.00, 1, 1, 1, 1),
('PHYSIO003', 'Ultrasound Therapy', @cat_physio, 'Therapeutic ultrasound', 400.00, 1, 5.00, 1, 1, 1, 1),
('PHYSIO004', 'Traction Therapy', @cat_physio, 'Spine traction treatment', 600.00, 1, 5.00, 1, 1, 1, 1);

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
