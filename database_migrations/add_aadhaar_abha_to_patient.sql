ALTER TABLE `patientdata`
  ADD COLUMN `aadhaar_number` CHAR(12) DEFAULT NULL AFTER `sid`,
  ADD COLUMN `abha_id` VARCHAR(50) DEFAULT NULL AFTER `aadhaar_number`,
  ADD COLUMN `aadhaar_masked` CHAR(14) DEFAULT NULL AFTER `abha_id`,
  ADD UNIQUE KEY `uk_aadhaar` (`aadhaar_number`),
  ADD KEY `idx_abha_id` (`abha_id`);

CREATE TABLE IF NOT EXISTS `aadhaar_access_log` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `opd_no` INT NOT NULL,
  `accessed_by` VARCHAR(256) DEFAULT NULL,
  `access_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `action` VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_access_log_opd` (`opd_no`),
  KEY `idx_access_log_time` (`access_time`),
  CONSTRAINT `fk_aadhaar_access_log_opd`
    FOREIGN KEY (`opd_no`) REFERENCES `patientdata`(`OpdNo`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
