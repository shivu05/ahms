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
ADD COLUMN `test_status` VARCHAR(45) NULL DEFAULT 'ACTIVE' AFTER `lab_test_reference`;
ALTER TABLE `lab_investigations` 
CHANGE COLUMN `status` `test_status` VARCHAR(45) NULL DEFAULT 'ACTIVE' ;

INSERT INTO `perm_master` (`perm_code`, `perm_desc`, `perm_order`, `perm_label`, `perm_parent`, `perm_url`, `perm_status`, `perm_icon`, `last_updated_id`) VALUES ('REGISTER', 'Registers', '8', '8', '0', '#', 'Active', 'fa fa-book', '1');
INSERT INTO `perm_master` (`perm_code`, `perm_desc`, `perm_order`, `perm_label`, `perm_parent`, `perm_url`, `perm_status`, `perm_icon`, `last_updated_id`) VALUES ('AUTOCLAVE', 'Autoclave', '1', '1', '60', 'autoclave-register', 'Active', 'fa fa-book', '1');

INSERT INTO `role_perm` (`role_id`, `perm_id`, `status`, `last_updated_id`, `access_perm`) VALUES ('1', '60', 'Active', '1', '2');
INSERT INTO `role_perm` (`role_id`, `perm_id`, `status`, `last_updated_id`, `access_perm`) VALUES ('1', '61', 'Active', '1', '2');


CREATE TABLE `fumigation_register` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fugimation_mothod` VARCHAR(200) NULL,
  `chemical_used` VARCHAR(250) NULL,
  `start_time` VARCHAR(45) NULL,
  `end_time` VARCHAR(45) NULL,
  `ot_number` VARCHAR(45) NULL,
  `neutralization` VARCHAR(200) NULL,
  `superviser_name` VARCHAR(100) NULL,
  `remarks` VARCHAR(200) NULL,
  `f_date` DATE NULL,
  PRIMARY KEY (`id`));
ALTER TABLE `fumigation_register` 
CHANGE COLUMN `fugimation_mothod` `fumigation_mothod` VARCHAR(200) NULL DEFAULT NULL ;

INSERT INTO `perm_master` (`perm_code`, `perm_desc`, `perm_order`, `perm_label`, `perm_parent`, `perm_url`, `perm_status`, `perm_icon`, `last_updated_id`)
 VALUES ('AUTOCLAVE', 'Autoclave', '1', '1', '60', 'autoclave-register', 'Active', 'fa fa-book', '1');
INSERT INTO `role_perm` (`role_id`, `perm_id`, `status`, `last_updated_id`, `access_perm`) VALUES ('1', '61', 'Active', '1', '2');
