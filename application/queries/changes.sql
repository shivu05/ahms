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