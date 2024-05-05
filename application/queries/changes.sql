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

ALTER TABLE `inpatientdetails` 
ADD COLUMN `admit_time` VARCHAR(45) NULL AFTER `sid`,
ADD COLUMN `discharge_time` VARCHAR(45) NULL AFTER `admit_time`;

-- 02-05-2024
CREATE TABLE autoclave_register (
    id INT PRIMARY KEY auto_increment,
    DrumNo VARCHAR(50),
    DrumStartTime DATETIME,
    DrumEndTime DATETIME,
    SupervisorName VARCHAR(150),
    Remarks TEXT
);


ALTER TABLE `lab_investigations` 
ADD COLUMN `status` VARCHAR(45) NULL DEFAULT 'ACTIVE' AFTER `lab_test_reference`;
ALTER TABLE `lab_investigations` 
CHANGE COLUMN `status` `test_status` VARCHAR(45) NULL DEFAULT 'ACTIVE' ;

