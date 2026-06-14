ALTER TABLE `patient_vitals`
  ADD COLUMN IF NOT EXISTS `height` decimal(6,2) NULL AFTER `weight`,
  ADD COLUMN IF NOT EXISTS `bmi` decimal(6,2) NULL AFTER `height`,
  ADD COLUMN IF NOT EXISTS `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`;
