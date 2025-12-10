-- Professional Pharmacy Management System Database Schema
-- Compatible with existing AHMS structure

-- Medicine Categories Master
CREATE TABLE `medicine_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(100) NOT NULL,
  `category_code` varchar(20) NOT NULL UNIQUE,
  `description` text,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Medicine Manufacturers/Companies
CREATE TABLE `medicine_manufacturers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_name` varchar(150) NOT NULL,
  `company_code` varchar(20) NOT NULL UNIQUE,
  `license_number` varchar(50),
  `address` text,
  `contact_person` varchar(100),
  `phone` varchar(20),
  `email` varchar(100),
  `gst_number` varchar(30),
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Medicine Units Master (Tablet, Capsule, ML, etc.)
CREATE TABLE `medicine_units` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unit_name` varchar(50) NOT NULL,
  `unit_symbol` varchar(10) NOT NULL,
  `description` text,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Medicine Master (Main medicine details)
CREATE TABLE `medicines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `medicine_name` varchar(200) NOT NULL,
  `generic_name` varchar(200),
  `medicine_code` varchar(50) UNIQUE,
  `category_id` int(11),
  `manufacturer_id` int(11),
  `unit_id` int(11),
  `medicine_type` enum('tablet','capsule','syrup','injection','ointment','drops','powder','others') DEFAULT 'tablet',
  `composition` text,
  `strength` varchar(100),
  `therapeutic_class` varchar(100),
  `storage_conditions` text,
  `side_effects` text,
  `contraindications` text,
  `drug_interaction` text,
  `prescription_required` enum('yes','no') DEFAULT 'yes',
  `narcotic` enum('yes','no') DEFAULT 'no',
  `schedule` varchar(20),
  `reorder_level` int(11) DEFAULT 10,
  `status` enum('active','inactive','discontinued') DEFAULT 'active',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_medicine_name` (`medicine_name`),
  KEY `idx_generic_name` (`generic_name`),
  FOREIGN KEY (`category_id`) REFERENCES `medicine_categories`(`id`),
  FOREIGN KEY (`manufacturer_id`) REFERENCES `medicine_manufacturers`(`id`),
  FOREIGN KEY (`unit_id`) REFERENCES `medicine_units`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Medicine Batches/Inventory
CREATE TABLE `medicine_batches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `medicine_id` int(11) NOT NULL,
  `batch_number` varchar(50) NOT NULL,
  `manufacturing_date` date,
  `expiry_date` date NOT NULL,
  `mrp` decimal(10,2) NOT NULL,
  `purchase_price` decimal(10,2) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `gst_percentage` decimal(5,2) DEFAULT 0.00,
  `discount_percentage` decimal(5,2) DEFAULT 0.00,
  `opening_stock` int(11) NOT NULL DEFAULT 0,
  `current_stock` int(11) NOT NULL DEFAULT 0,
  `reserved_stock` int(11) NOT NULL DEFAULT 0,
  `minimum_stock` int(11) DEFAULT 5,
  `rack_number` varchar(20),
  `location` varchar(100),
  `supplier_id` int(11),
  `purchase_invoice_number` varchar(50),
  `purchase_date` date,
  `status` enum('active','expired','exhausted') DEFAULT 'active',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_batch_medicine` (`medicine_id`, `batch_number`),
  KEY `idx_expiry_date` (`expiry_date`),
  KEY `idx_batch_number` (`batch_number`),
  FOREIGN KEY (`medicine_id`) REFERENCES `medicines`(`id`),
  FOREIGN KEY (`supplier_id`) REFERENCES `medicine_manufacturers`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Suppliers (separate from manufacturers)
CREATE TABLE `medicine_suppliers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_name` varchar(150) NOT NULL,
  `supplier_code` varchar(20) NOT NULL UNIQUE,
  `contact_person` varchar(100),
  `address` text,
  `phone` varchar(20),
  `email` varchar(100),
  `gst_number` varchar(30),
  `drug_license` varchar(50),
  `credit_limit` decimal(12,2) DEFAULT 0.00,
  `payment_terms` varchar(100),
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Purchase Orders
CREATE TABLE `purchase_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `po_number` varchar(50) NOT NULL UNIQUE,
  `supplier_id` int(11) NOT NULL,
  `po_date` date NOT NULL,
  `expected_delivery_date` date,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `payment_terms` varchar(100),
  `remarks` text,
  `status` enum('draft','sent','partial','received','cancelled') DEFAULT 'draft',
  `created_by` int(11),
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_po_number` (`po_number`),
  FOREIGN KEY (`supplier_id`) REFERENCES `medicine_suppliers`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Purchase Order Items
CREATE TABLE `purchase_order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `po_id` int(11) NOT NULL,
  `medicine_id` int(11) NOT NULL,
  `quantity_ordered` int(11) NOT NULL,
  `quantity_received` int(11) DEFAULT 0,
  `unit_price` decimal(10,2) NOT NULL,
  `discount_percentage` decimal(5,2) DEFAULT 0.00,
  `gst_percentage` decimal(5,2) DEFAULT 0.00,
  `total_amount` decimal(12,2) NOT NULL,
  `remarks` text,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`po_id`) REFERENCES `purchase_orders`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`medicine_id`) REFERENCES `medicines`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Medicine Purchases (Goods Receipt)
CREATE TABLE `medicine_purchases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(50) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `po_id` int(11),
  `purchase_date` date NOT NULL,
  `invoice_date` date,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `freight_charges` decimal(10,2) DEFAULT 0.00,
  `other_charges` decimal(10,2) DEFAULT 0.00,
  `total_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `payment_mode` enum('cash','credit','card','online','cheque') DEFAULT 'credit',
  `payment_status` enum('pending','partial','paid') DEFAULT 'pending',
  `remarks` text,
  `created_by` int(11),
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_invoice_supplier` (`invoice_number`, `supplier_id`),
  FOREIGN KEY (`supplier_id`) REFERENCES `medicine_suppliers`(`id`),
  FOREIGN KEY (`po_id`) REFERENCES `purchase_orders`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Purchase Items Details
CREATE TABLE `medicine_purchase_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_id` int(11) NOT NULL,
  `medicine_id` int(11) NOT NULL,
  `batch_number` varchar(50) NOT NULL,
  `manufacturing_date` date,
  `expiry_date` date NOT NULL,
  `quantity` int(11) NOT NULL,
  `free_quantity` int(11) DEFAULT 0,
  `unit_purchase_price` decimal(10,2) NOT NULL,
  `unit_selling_price` decimal(10,2) NOT NULL,
  `mrp` decimal(10,2) NOT NULL,
  `discount_percentage` decimal(5,2) DEFAULT 0.00,
  `gst_percentage` decimal(5,2) DEFAULT 0.00,
  `total_amount` decimal(12,2) NOT NULL,
  `rack_number` varchar(20),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`purchase_id`) REFERENCES `medicine_purchases`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`medicine_id`) REFERENCES `medicines`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Medicine Sales (Main sales table)
CREATE TABLE `medicine_sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bill_number` varchar(50) NOT NULL UNIQUE,
  `patient_id` int(11),
  `patient_type` enum('opd','ipd','walk_in') DEFAULT 'walk_in',
  `patient_name` varchar(100),
  `patient_phone` varchar(20),
  `doctor_id` int(11),
  `prescription_id` int(11),
  `sale_date` datetime NOT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `balance_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `payment_mode` enum('cash','credit','card','online','insurance') DEFAULT 'cash',
  `payment_status` enum('pending','partial','paid') DEFAULT 'paid',
  `sale_type` enum('prescription','otc','emergency') DEFAULT 'prescription',
  `remarks` text,
  `cashier_id` int(11),
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_bill_number` (`bill_number`),
  KEY `idx_sale_date` (`sale_date`),
  KEY `idx_patient_id` (`patient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Medicine Sale Items
CREATE TABLE `medicine_sale_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sale_id` int(11) NOT NULL,
  `medicine_id` int(11) NOT NULL,
  `batch_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `discount_percentage` decimal(5,2) DEFAULT 0.00,
  `discount_amount` decimal(10,2) DEFAULT 0.00,
  `gst_percentage` decimal(5,2) DEFAULT 0.00,
  `gst_amount` decimal(10,2) DEFAULT 0.00,
  `total_amount` decimal(12,2) NOT NULL,
  `expiry_date` date,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`sale_id`) REFERENCES `medicine_sales`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`medicine_id`) REFERENCES `medicines`(`id`),
  FOREIGN KEY (`batch_id`) REFERENCES `medicine_batches`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Stock Adjustments
CREATE TABLE `medicine_stock_adjustments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adjustment_number` varchar(50) NOT NULL UNIQUE,
  `adjustment_date` date NOT NULL,
  `adjustment_type` enum('stock_in','stock_out','expired','damaged','lost','found') NOT NULL,
  `total_value` decimal(12,2) DEFAULT 0.00,
  `reason` text,
  `approved_by` int(11),
  `created_by` int(11),
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Stock Adjustment Items
CREATE TABLE `medicine_stock_adjustment_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adjustment_id` int(11) NOT NULL,
  `medicine_id` int(11) NOT NULL,
  `batch_id` int(11) NOT NULL,
  `adjustment_qty` int(11) NOT NULL,
  `reason` text,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`adjustment_id`) REFERENCES `medicine_stock_adjustments`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`medicine_id`) REFERENCES `medicines`(`id`),
  FOREIGN KEY (`batch_id`) REFERENCES `medicine_batches`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Prescription Master
CREATE TABLE `prescriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prescription_number` varchar(50) NOT NULL UNIQUE,
  `patient_id` int(11) NOT NULL,
  `patient_type` enum('opd','ipd') NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `prescription_date` datetime NOT NULL,
  `diagnosis` text,
  `remarks` text,
  `status` enum('pending','partial','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_prescription_number` (`prescription_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Prescription Items
CREATE TABLE `prescription_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prescription_id` int(11) NOT NULL,
  `medicine_id` int(11) NOT NULL,
  `dosage` varchar(100),
  `frequency` varchar(100),
  `duration` varchar(50),
  `quantity` int(11) NOT NULL,
  `instructions` text,
  `dispensed_qty` int(11) DEFAULT 0,
  `status` enum('pending','partial','completed') DEFAULT 'pending',
  PRIMARY KEY (`id`),
  FOREIGN KEY (`prescription_id`) REFERENCES `prescriptions`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`medicine_id`) REFERENCES `medicines`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Medicine Returns
CREATE TABLE `medicine_returns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `return_number` varchar(50) NOT NULL UNIQUE,
  `sale_id` int(11),
  `supplier_id` int(11),
  `return_date` date NOT NULL,
  `return_type` enum('customer_return','supplier_return','expired_return') NOT NULL,
  `total_amount` decimal(12,2) DEFAULT 0.00,
  `refund_amount` decimal(12,2) DEFAULT 0.00,
  `reason` text,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `processed_by` int(11),
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`sale_id`) REFERENCES `medicine_sales`(`id`),
  FOREIGN KEY (`supplier_id`) REFERENCES `medicine_suppliers`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Medicine Return Items
CREATE TABLE `medicine_return_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `return_id` int(11) NOT NULL,
  `medicine_id` int(11) NOT NULL,
  `batch_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `reason` varchar(200),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`return_id`) REFERENCES `medicine_returns`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`medicine_id`) REFERENCES `medicines`(`id`),
  FOREIGN KEY (`batch_id`) REFERENCES `medicine_batches`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Medicine Frequency Master
CREATE TABLE `medicine_frequencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `frequency_name` varchar(50) NOT NULL,
  `frequency_code` varchar(20) NOT NULL,
  `times_per_day` int(11) NOT NULL,
  `description` text,
  `status` enum('active','inactive') DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Daily Stock Report (for performance)
CREATE TABLE `daily_stock_summary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `report_date` date NOT NULL,
  `medicine_id` int(11) NOT NULL,
  `opening_stock` int(11) DEFAULT 0,
  `purchases` int(11) DEFAULT 0,
  `sales` int(11) DEFAULT 0,
  `adjustments` int(11) DEFAULT 0,
  `closing_stock` int(11) DEFAULT 0,
  `stock_value` decimal(12,2) DEFAULT 0.00,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_date_medicine` (`report_date`, `medicine_id`),
  FOREIGN KEY (`medicine_id`) REFERENCES `medicines`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert Default Data
INSERT INTO `medicine_categories` (`category_name`, `category_code`, `description`) VALUES
('Analgesics', 'ANALG', 'Pain relievers'),
('Antibiotics', 'ANTIB', 'Anti-bacterial medications'),
('Antacids', 'ANTAC', 'Stomach acid neutralizers'),
('Antihistamines', 'ANTIH', 'Allergy medications'),
('Cardiovascular', 'CARDIO', 'Heart and blood vessel medications'),
('Dermatological', 'DERMA', 'Skin medications'),
('Gastrointestinal', 'GASTRO', 'Digestive system medications'),
('Respiratory', 'RESP', 'Breathing and lung medications'),
('Vitamins & Supplements', 'VITAM', 'Nutritional supplements'),
('Ayurvedic', 'AYURV', 'Traditional Ayurvedic medicines');

INSERT INTO `medicine_units` (`unit_name`, `unit_symbol`) VALUES
('Tablet', 'TAB'),
('Capsule', 'CAP'),
('Milliliter', 'ML'),
('Gram', 'GM'),
('Injection', 'INJ'),
('Drops', 'DROPS'),
('Ointment', 'OINT'),
('Powder', 'PWD'),
('Syrup', 'SYP'),
('Vial', 'VIAL');

INSERT INTO `medicine_frequencies` (`frequency_name`, `frequency_code`, `times_per_day`) VALUES
('Once Daily', 'OD', 1),
('Twice Daily', 'BD', 2),
('Three Times Daily', 'TDS', 3),
('Four Times Daily', 'QDS', 4),
('Every 4 Hours', 'Q4H', 6),
('Every 6 Hours', 'Q6H', 4),
('Every 8 Hours', 'Q8H', 3),
('Every 12 Hours', 'Q12H', 2),
('When Required', 'PRN', 0),
('At Bedtime', 'HS', 1);
