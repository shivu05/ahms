/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  hp
 * Created: 24 Jan, 2019
 */

update treatmentdata set department=UPPER(replace(department,' ','_'));
ALTER TABLE `config` ADD COLUMN `admin_email` VARCHAR(45) AFTER `edit_flag`;
ALTER TABLE `xrayregistery` ADD COLUMN `refDate` VARCHAR(45) AFTER `treatID`;
update xrayregistery set refDate=xrayDate where xrayDate is not null;
ALTER TABLE `role_master` ADD PRIMARY KEY(`role_id`);
ALTER TABLE `role_master` CHANGE `role_id` `role_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `ecgregistery` ADD COLUMN `refDate` VARCHAR(45) AFTER `treatId`;
ALTER TABLE `usgregistery` ADD COLUMN `refDate` VARCHAR(45) AFTER `treatId`;
ALTER TABLE `treatmentdata` MODIFY COLUMN `deptOpdNo` INTEGER;


CREATE TABLE `config_variables` (
  `config_var_id` INTEGER NOT NULL AUTO_INCREMENT,
  `config_var_name` VARCHAR(250) NOT NULL,
  `config_var_value` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`config_var_id`)
)
ENGINE = InnoDB;

INSERT INTO `config_variables` (`config_var_id`, `config_var_name`, `config_var_value`) VALUES
(1, 'OPD_CHARGES', '10.00'),
(2, 'OPD_CHARGES_IN_WORDS', 'Ten rupees only');

ALTER TABLE `treatmentdata` ADD COLUMN `monthly_sid` INTEGER NOT NULL AFTER `attndedon`;
ALTER TABLE `treatmentdata` ADD COLUMN `year_no` INTEGER DEFAULT 0 AFTER `monthly_sid`;

ALTER TABLE `panchaprocedure` ADD COLUMN `proc_end_date` DATE AFTER `treatid`;

CREATE TABLE `master_panchakarma_procedures` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `proc_name` VARCHAR(200) NOT NULL,
  `last_modified_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)
ENGINE = InnoDB;

DROP TABLE IF EXISTS `master_panchakarma_sub_procedures`;
CREATE TABLE  `master_panchakarma_sub_procedures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `procecure_id` int(11) NOT NULL,
  `sub_proc_name` varchar(200) NOT NULL,
  `no_of_treatment_days` int(11) DEFAULT NULL,
  `last_modified_date` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_master_panchakarma_sub_procedures_1` (`procecure_id`),
  CONSTRAINT `FK_master_panchakarma_sub_procedures_1` FOREIGN KEY (`procecure_id`) REFERENCES `master_panchakarma_procedures` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

update perm_master set perm_desc='Patient Master',perm_code='PATIENT_MASTER',perm_url='patient-list' where perm_id=5;
ALTER TABLE `lab_investigations` 
ADD COLUMN `lab_test_reference` VARCHAR(100) NULL AFTER `lab_test_id`;
ALTER TABLE `labregistery` 
CHANGE COLUMN `tested_date` `tested_date` DATE NULL DEFAULT NULL ;

ALTER TABLE `lab_investigations` 
CHANGE COLUMN `lab_test_reference` `lab_test_reference` VARCHAR(250) NULL DEFAULT NULL ;

ALTER TABLE `xray_ref` 
CHANGE COLUMN `xraypart` `xraypart` VARCHAR(200) NULL DEFAULT NULL ,
CHANGE COLUMN `filmsize` `filmsize` VARCHAR(200) NULL DEFAULT NULL ;

ALTER TABLE `birthregistery` 
ADD COLUMN `anesthesia_type` VARCHAR(45) NULL DEFAULT NULL AFTER `birthtime`;

ALTER TABLE `ksharsutraregistery` 
ADD COLUMN `anesthesia_type` VARCHAR(45) NULL DEFAULT NULL AFTER `anaesthetic`;

ALTER TABLE `surgeryregistery` 
ADD COLUMN `anesthesia_type` VARCHAR(45) NULL DEFAULT NULL AFTER `surgeryname`;

ALTER TABLE `deptper` 
ADD COLUMN `ref_room` INT NULL AFTER `bed_count`;

UPDATE `deptper` SET `ref_room` = '1' WHERE (`ID` = '1');
UPDATE `deptper` SET `ref_room` = '2' WHERE (`ID` = '5');
UPDATE `deptper` SET `ref_room` = '3' WHERE (`ID` = '3');
UPDATE `deptper` SET `ref_room` = '4' WHERE (`ID` = '8');
UPDATE `deptper` SET `ref_room` = '5' WHERE (`ID` = '2');
UPDATE `deptper` SET `ref_room` = '6' WHERE (`ID` = '4');
UPDATE `deptper` SET `ref_room` = '8' WHERE (`ID` = '9');
UPDATE `deptper` SET `ref_room` = '7' WHERE (`ID` = '10');

ALTER TABLE `treatmentdata` 
ADD COLUMN `sl_id` INT NULL AFTER `sub_department`;

CREATE TABLE `kriyakalpa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `OpdNo` int(11) DEFAULT NULL,
  `IpNo` int(11) DEFAULT NULL,
  `treat_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1;


INSERT INTO `kriyakalpa`
(`OpdNo`,`IpNo`,`treat_id`)
SELECT t.OpdNo,ip.IpNo,t.ID
FROM treatmentdata t, (SELECT @a:= 0) AS a 
JOIN patientdata p 
LEFT JOIN inpatientdetails ip ON ip.OpdNo=p.OpdNo WHERE t.OpdNo=p.OpdNo 
AND LOWER(t.department)=LOWER('SHALAKYA_TANTRA');

INSERT INTO `months_list`
(`months_name`,`month_number`)
VALUES
('January',1),('February',2),('March',3),('April',4),('May',5),('June',6),('July',7),('August',8),('September',9),
('October',10),('November',11),('December',12);
-- Nursing indent report
INSERT INTO `perm_master` (`perm_id`, `perm_code`, `perm_desc`, `perm_order`, `perm_label`, `perm_parent`, `perm_class`, `perm_url`, `perm_status`, `perm_attr`, `perm_icon`, `last_updated_id`, `last_updated_date`) 
VALUES
(48, 'NURSING_INDENT_REPORT', 'Nursing indent report', 16, 0, '14', '', 'reports/nursing', 'Active', '', '', 1, '2021-04-02 19:39:41');

INSERT INTO `role_perm` (`role_perm_id`, `role_id`, `perm_id`, `status`, `last_updated_id`, `last_updated_date`, `access_perm`) VALUES
(75, 1, 48, 'Active', 0, '2021-04-02 19:39:49', 2);


CREATE TABLE `months_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;


INSERT INTO `months_list`
(`name`)
VALUES
('January'),('February'),('March'),('April'),('May'),('June'),('July'),('August'),('September'),('October'),('November'),('December');

CREATE TABLE `diet_register` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ipd_no` int(11) DEFAULT NULL,
  `opd_no` int(11) DEFAULT NULL,
  `treat_id` int(11) DEFAULT NULL,
  `morning` varchar(100) DEFAULT NULL,
  `after_noon` varchar(100) DEFAULT NULL,
  `evening` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


INSERT INTO `diet_register` (`ipd_no`,`opd_no`,`treat_id`,`morning`,`after_noon`,`evening`)
SELECT IpNo,OpdNo,treatId,'Bread/Biscuit/Tea','Chapati rice','Chapati rice' FROM inpatientdetails ;


CREATE TABLE `archived_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `db_name` varchar(45) DEFAULT NULL,
  `status` varchar(45) DEFAULT 'active',
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1;
INSERT INTO archived_data (db_name,status,name) VALUES('ahms_kramch_2020','Active','2020');