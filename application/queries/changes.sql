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
