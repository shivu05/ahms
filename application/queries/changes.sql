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


ALTER TABLE `bed_details` 
ADD COLUMN `bed_category` VARCHAR(45) NULL AFTER `IpNo`;


CREATE TABLE `billing` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `patient_name` VARCHAR(255),
    `opdno` INT,
    `ipdno` INT,
    `service_name` VARCHAR(255),
    `amount` DECIMAL(10, 2),
    `date` DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `service_groups` (
    `group_id` INT AUTO_INCREMENT PRIMARY KEY,
    `group_name` VARCHAR(255) NOT NULL,
    `description` VARCHAR(500)
);

CREATE TABLE `bill_services` (
    `service_id` INT AUTO_INCREMENT PRIMARY KEY,
    `service_name` VARCHAR(255) NOT NULL,
    `group_id` INT NOT NULL,
    `price` DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (`group_id`) REFERENCES `service_groups`(`group_id`)
);

CREATE TABLE `opd_billing` (
    `billing_id` INT AUTO_INCREMENT PRIMARY KEY,
    `opd_no` INT NOT NULL,
    `service_id` INT NOT NULL,
    `billing_date` DATE NOT NULL,
    `amount` DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (`opd_no`) REFERENCES `patientdata`(`OpdNo`),
    FOREIGN KEY (`service_id`) REFERENCES `bill_services`(`service_id`)
);

CREATE TABLE `ipd_billing` (
    `billing_id` INT AUTO_INCREMENT PRIMARY KEY,
    `ipd_no` INT NOT NULL,
    `service_id` INT NOT NULL,
    `billing_date` DATE NOT NULL,
    `amount` DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (`ipd_no`) REFERENCES `inpatientdetails`(`IpNo`),
    FOREIGN KEY (`service_id`) REFERENCES `bill_services`(`service_id`)
);


CREATE TABLE `agnikarma_opd_ipd_register` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `opd_no` INT NULL,
  `ipd_no` INT NULL,
  `ref_date` DATE NULL,
  `doctor_name` VARCHAR(100) NULL,
  `last_updates` DATETIME NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`));

  CREATE TABLE `cupping_opd_ipd_register` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `opd_no` int(11) DEFAULT NULL,
  `ipd_no` int(11) DEFAULT NULL,
  `treat_id` int(11) DEFAULT NULL,
  `ref_date` date DEFAULT NULL,
  `doctor_name` varchar(100) DEFAULT NULL,
  `type_of_cupping` varchar(100) DEFAULT NULL,
  `site_of_application` varchar(100) DEFAULT NULL,
  `no_of_cups_used` int(11) DEFAULT NULL,
  `treatment_notes` text DEFAULT NULL,
  `last_updates` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `jaloukavacharana_opd_ipd_register` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `opd_no` INT NOT NULL,
    `ipd_no` INT NULL,
    `ref_date` DATE NOT NULL,
    `doctor_name` VARCHAR(100) NOT NULL,
    `procedure_details` TEXT NULL,
    `doctor_remarks` TEXT NULL,
    `last_updated` DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_opd` FOREIGN KEY (`opd_no`) REFERENCES `patientdata`(`OpdNo`) ON DELETE CASCADE,
    CONSTRAINT `fk_ipd` FOREIGN KEY (`ipd_no`) REFERENCES `inpatientdetails`(`IpNo`) ON DELETE SET NULL
);

CREATE TABLE `siravyadana_opd_ipd_register` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `opd_no` int(11) NOT NULL,
  `ipd_no` int(11) DEFAULT NULL,
  `treat_id` int(11) DEFAULT NULL,
  `ref_date` date NOT NULL,
  `doctor_name` varchar(100) NOT NULL,
  `procedure_details` text DEFAULT NULL,
  `doctor_remarks` text DEFAULT NULL,
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE patient_vitals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    opd_no VARCHAR(20) NOT NULL,
    date DATE NOT NULL,
    blood_pressure VARCHAR(20), -- e.g., '120/80'
    pulse_rate INT,             -- in beats per minute
    respiratory_rate INT,       -- in breaths per minute
    body_temperature DECIMAL(5,2), -- in Celsius or Fahrenheit, based on system
    spo2 INT,                   -- Oxygen Saturation in %
    weight DECIMAL(5,2),        -- in kg (or lbs, based on system)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);