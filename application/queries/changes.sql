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


INSERT INTO `role_master` (`role_name`, `role_code`, `last_updated_by`) VALUES ('Super Admin', 'SUP_ADMIN', '1');
INSERT INTO `users` (`user_name`, `user_email`, `user_mobile`, `user_type`, `active`) VALUES ('Abhilasha', 'abhis@vedicsofts.com', '9845098450', '7', '1');
UPDATE `users` SET `user_password` = '$2a$08$SCRISSRvXN3.PQ4PL7nY5O1I6GcD2EfIAh8.Wwza7DDSKUa7LWtbG' WHERE (`ID` = '19');
INSERT INTO `i_user_roles` (`user_id`, `role_id`) VALUES ('19', '7');



INSERT INTO `perm_master`(`perm_code`,`perm_desc`,`perm_order`,`perm_label`,`perm_parent`,`perm_class`,`perm_url`,`perm_status`,`perm_attr`,`perm_icon`,`last_updated_id`)
VALUES('STOCK_LIST','Stock List',6,0,30,'','stock-view','Active','','',1);
INSERT INTO `role_perm`(`role_id`,`perm_id`,`status`,`last_updated_id`,`access_perm`) VALUES(1,56,'Active',1,1);

INSERT INTO `perm_master`(`perm_code`,`perm_desc`,`perm_order`,`perm_label`,`perm_parent`,`perm_class`,`perm_url`,`perm_status`,`perm_attr`,`perm_icon`,`last_updated_id`)
VALUES('PURCHASE_ENTRY','Purchase entry',7,0,30,'','add-purchase','Active','','',1);
INSERT INTO `role_perm`(`role_id`,`perm_id`,`status`,`last_updated_id`,`access_perm`) VALUES(1,57,'Active',1,1);

/* LAB REFERENCE */
drop table lab_reference;
create table lab_reference select * from labregistery;

update lab_reference a,treatmentdata b set a.labdisease=b.diagnosis where a.treatID=b.ID;

select * from lab_reference where trim(labdisease)='';
delete from lab_reference where trim(labdisease)='';
delete from lab_reference where testvalue is null;

select *from lab_reference where lab_test_type is null;

update lab_reference a,lab_investigations b
set a.lab_test_type=b.lab_test_id
where a.testName=b.lab_inv_id;

select * from lab_reference; where lab_test_cat is null;

update lab_reference a,lab_tests b
set a.lab_test_cat=b.lab_cat_id
where a.lab_test_type=b.lab_test_id;

/* TESTING*/
select lab_test_cat,lab_test_type,testName,treatID,testrange,testvalue,labdisease from lab_reference where upper(trim(labdisease))='ARSHA' group by treatID ORDER BY RAND() LIMIT 1;-- 23268
select * from lab_reference where treatID=8840;

select lab_test_cat,lab_test_type,testName,treatID,testrange,testvalue,labdisease from lab_reference;

INSERT INTO labregistery
(OpdNo,refDocName,lab_test_cat,lab_test_type,testName,testDate,treatID,testrange,testvalue,labdisease,tested_date)
select * from lab_reference where treatID=8840;


CREATE TABLE `reference_treatment` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `diagnosis` VARCHAR(200) NULL,
  `is_lab` VARCHAR(1) NULL DEFAULT 'N',
  `is_usg` VARCHAR(1) NULL DEFAULT 'N',
  `is_ecg` VARCHAR(1) NULL DEFAULT 'N',
  `is_xray` VARCHAR(1) NULL DEFAULT 'N',
  `is_pancha` VARCHAR(1) NULL DEFAULT 'N',
  PRIMARY KEY (`id`));

insert into reference_treatment (diagnosis) select distinct diagnosis from treatmentdata where diagnosis is not null and diagnosis <>'';

SELECT * FROM reference_treatment;

select * from reference_treatment a,
lab_reference b where a.diagnosis=b.labdisease
group by diagnosis;

update reference_treatment a,lab_reference b
set is_lab='Y'
where a.diagnosis=b.labdisease;
update  reference_treatment set  is_lab='N';

select distinct diagnosis from usgregistery a,treatmentdata b where a.treatId=b.ID;

update reference_treatment c,usgregistery a,treatmentdata b
set is_usg='Y'
where a.treatId=b.ID and b.diagnosis=c.diagnosis;

select * from reference_treatment where is_usg='Y';


/* Panchakarma procedures */
select * from panchaprocedure;
create table reference_panchakarma select * from panchaprocedure;

select a.*,DATEDIFF(proc_end_date,date) from reference_panchakarma a; where proc_end_date ='';
update reference_panchakarma a,treatmentdata b
set a.disease=b.diagnosis where a.treatid=b.ID;
ALTER TABLE `reference_panchakarma`
ADD COLUMN `no_of_days` INT(3) NULL DEFAULT 0 AFTER `proc_end_date`;
select * from reference_panchakarma a;
delete from  reference_panchakarma where proc_end_date ='';
update reference_panchakarma set no_of_days=DATEDIFF(proc_end_date,`date`) where  proc_end_date<>`date`;
update reference_panchakarma set no_of_days=1 where  proc_end_date=`date`;
ALTER TABLE reference_panchakarma
CHANGE COLUMN `id` `id` INT(11) NOT NULL AUTO_INCREMENT ,
ADD PRIMARY KEY (`id`);

-- 21-03-2022
ALTER TABLE `kriyakalpa`
ADD COLUMN `kriya_procedures` VARCHAR(250) NULL DEFAULT NULL AFTER `treat_id`,
ADD COLUMN `kriya_start_date` VARCHAR(25) NULL DEFAULT NULL AFTER `kriya_procedures`;

ALTER TABLE `kriyakalpa`
CHANGE COLUMN `kriya_start_date` `kriya_start_date` DATE NULL DEFAULT NULL ;
ALTER TABLE `kriyakalpa`
CHANGE COLUMN `kriya_start_date` `kriya_date` DATE NULL DEFAULT NULL ;

update kriyakalpa a, treatmentdata t
set a.kriya_date=t.CameOn,a.kriya_procedures=t.procedures
where a.treat_id=t.id;
