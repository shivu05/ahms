ALTER TABLE `perm_master` CHANGE `perm_attr` `perm_attr` VARCHAR(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
ALTER TABLE `perm_master` CHANGE `perm_class` `perm_class` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
INSERT INTO `perm_master` (`perm_code`, `perm_desc`, `perm_order`, `perm_label`, `perm_parent`, `perm_url`, `perm_status`, `perm_icon`, `last_updated_id`)
 VALUES ('BILLING', 'Billing', '4', '4', '4', 'billing', 'Active', 'fa fa-book', '1');
 
INSERT INTO `role_perm` (`role_id`, `perm_id`, `status`, `last_updated_id`, `access_perm`)
SELECT '1', pm.perm_id, 'Active', '1', '2'
FROM `perm_master` pm
WHERE pm.perm_code = 'BILLING'
AND NOT EXISTS (
  SELECT 1
  FROM `role_perm` rp
  WHERE rp.role_id = '1'
    AND rp.perm_id = pm.perm_id
);


UPDATE `perm_master` SET `perm_code` = 'PHARMA_MGMT' WHERE `perm_master`.`perm_id` = 58;
UPDATE `perm_master` SET `perm_url` = 'pharmacy_pro/pharmacy' WHERE `perm_master`.`perm_id` = 58;
UPDATE `perm_master` SET `perm_status` = 'Inactive' WHERE `perm_master`.`perm_id` = 57;
UPDATE `perm_master` SET `perm_status` = 'Inactive' WHERE `perm_master`.`perm_id` = 56;
UPDATE `perm_master` SET `perm_status` = 'Inactive' WHERE `perm_master`.`perm_id` = 31;
UPDATE `perm_master` SET `perm_status` = 'Inactive' WHERE `perm_master`.`perm_id` = 32;
UPDATE `perm_master` SET `perm_status` = 'Inactive' WHERE `perm_master`.`perm_id` = 33;