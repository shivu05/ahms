INSERT INTO `perm_master`
(`perm_code`, `perm_desc`, `perm_order`, `perm_label`, `perm_parent`, `perm_class`, `perm_url`, `perm_status`, `perm_attr`, `perm_icon`, `last_updated_id`, `last_updated_date`)
SELECT 'DEATH_REGISTER', 'Death Register', 15, 15, pm.perm_id, '', 'death-register', 'Active', '', 'fa fa-book', 1, CURRENT_TIMESTAMP
FROM `perm_master` pm
WHERE pm.perm_code = 'REPORTS'
  AND NOT EXISTS (
    SELECT 1 FROM `perm_master` existing
    WHERE existing.perm_code = 'DEATH_REGISTER'
       OR existing.perm_url = 'death-register'
  )
LIMIT 1;

INSERT INTO `role_perm` (`role_id`, `perm_id`, `status`, `last_updated_id`, `access_perm`)
SELECT '1', pm.perm_id, 'Active', '1', '2'
FROM `perm_master` pm
WHERE pm.perm_code = 'DEATH_REGISTER'
  AND NOT EXISTS (
    SELECT 1
    FROM `role_perm` rp
    WHERE rp.role_id = '1'
      AND rp.perm_id = pm.perm_id
  );
