ALTER TABLE `oldtable`
  ADD COLUMN `aadhaar_number` CHAR(12) DEFAULT NULL AFTER `medicines`,
  ADD COLUMN `abha_id` VARCHAR(50) DEFAULT NULL AFTER `aadhaar_number`,
  ADD COLUMN `aadhaar_masked` CHAR(14) DEFAULT NULL AFTER `abha_id`,
  ADD KEY `idx_oldtable_aadhaar_number` (`aadhaar_number`),
  ADD KEY `idx_oldtable_abha_id` (`abha_id`);
