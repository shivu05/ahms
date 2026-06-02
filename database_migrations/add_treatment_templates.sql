CREATE TABLE IF NOT EXISTS `treatment_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `department_id` varchar(100) DEFAULT NULL,
  `diagnosis_id` int(11) DEFAULT NULL,
  `treatment_text` text NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'ACTIVE',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_treatment_templates_status` (`status`),
  KEY `idx_treatment_templates_department` (`department_id`),
  KEY `idx_treatment_templates_diagnosis` (`diagnosis_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
