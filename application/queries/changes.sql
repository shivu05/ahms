-- 20-DEC-2023
CREATE TABLE `swarnaprashana` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `opd_no` INT NULL,
  `dept_opd` INT NULL,
  `treat_id` INT NULL,
  `date_month` VARCHAR(45) NULL,
  `dose_time` VARCHAR(45) NULL,
  `consultant` VARCHAR(100) NULL,
  PRIMARY KEY (`id`));