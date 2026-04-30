START TRANSACTION;

CALL create_opd_migration_map(
    'vhms_rashmi_amc_2026',
    'vhms_riamsh_2026',
    '2026-03-17 00:00:00',
    '2026-04-30 23:59:59'
);

CALL migrate_table_by_opd_map('vhms_rashmi_amc_2026', 'vhms_riamsh_2026', 'patientdata', 'entrydate', '2026-03-17 00:00:00', '2026-04-30 23:59:59');
CALL migrate_table_by_opd_map('vhms_rashmi_amc_2026', 'vhms_riamsh_2026', 'treatmentdata', 'CameOn', '2026-03-17 00:00:00', '2026-04-30 23:59:59');
CALL migrate_table_by_opd_map('vhms_rashmi_amc_2026', 'vhms_riamsh_2026', 'inpatientdetails', 'DoAdmission', '2026-03-17 00:00:00', '2026-04-30 23:59:59');
CALL migrate_table_by_opd_map('vhms_rashmi_amc_2026', 'vhms_riamsh_2026', 'ipdtreatment', 'attndedon', '2026-03-17 00:00:00', '2026-04-30 23:59:59');
CALL migrate_table_by_opd_map('vhms_rashmi_amc_2026', 'vhms_riamsh_2026', 'indent', 'indentdate', '2026-03-17 00:00:00', '2026-04-30 23:59:59');
CALL migrate_table_by_opd_map('vhms_rashmi_amc_2026', 'vhms_riamsh_2026', 'labregistery', 'testDate', '2026-03-17 00:00:00', '2026-04-30 23:59:59');
CALL migrate_table_by_opd_map('vhms_rashmi_amc_2026', 'vhms_riamsh_2026', 'xrayregistery', 'xrayDate', '2026-03-17 00:00:00', '2026-04-30 23:59:59');
CALL migrate_table_by_opd_map('vhms_rashmi_amc_2026', 'vhms_riamsh_2026', 'usgregistery', 'usgDate', '2026-03-17 00:00:00', '2026-04-30 23:59:59');
CALL migrate_table_by_opd_map('vhms_rashmi_amc_2026', 'vhms_riamsh_2026', 'surgeryregistery', 'surgDate', '2026-03-17 00:00:00', '2026-04-30 23:59:59');
CALL migrate_table_by_opd_map('vhms_rashmi_amc_2026', 'vhms_riamsh_2026', 'sales_entry', 'date', '2026-03-17 00:00:00', '2026-04-30 23:59:59');
CALL migrate_table_by_opd_map('vhms_rashmi_amc_2026', 'vhms_riamsh_2026', 'panchaprocedure', 'date', '2026-03-17 00:00:00', '2026-04-30 23:59:59');
CALL migrate_table_by_opd_map('vhms_rashmi_amc_2026', 'vhms_riamsh_2026', 'ksharsutraregistery', 'ksharsDate', '2026-03-17 00:00:00', '2026-04-30 23:59:59');
CALL migrate_table_by_opd_map('vhms_rashmi_amc_2026', 'vhms_riamsh_2026', 'kriyakalpa', 'kriya_date', '2026-03-17 00:00:00', '2026-04-30 23:59:59');
CALL migrate_table_by_opd_map('vhms_rashmi_amc_2026', 'vhms_riamsh_2026', 'ecgregistery', 'ecgDate', '2026-03-17 00:00:00', '2026-04-30 23:59:59');
CALL migrate_table_by_opd_map('vhms_rashmi_amc_2026', 'vhms_riamsh_2026', 'other_procedures_treatments', 'start_date', '2026-03-17 00:00:00', '2026-04-30 23:59:59');
CALL migrate_table_by_opd_map('vhms_rashmi_amc_2026', 'vhms_riamsh_2026', 'physiotherapy_treatments', 'start_date', '2026-03-17 00:00:00', '2026-04-30 23:59:59');

COMMIT;