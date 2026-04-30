/*
  VHMS production-style migration script
  Source:      vhms_rashmi_amc_2026
  Destination: vhms_riamsh_2026
  Range:       2026-03-17 to 2026-04-30

  Rules:
  - No ON DUPLICATE KEY UPDATE
  - Existing destination rows are not overwritten
  - Source OpdNo is treated as reference only
  - New destination OpdNo starts after destination MAX(patientdata.OpdNo)
  - treatmentdata.ID is remapped to new destination ID
  - inpatientdetails.IpNo is remapped to new destination IpNo
  - Child tables use mapped OpdNo / IpNo / treatID
*/

SET @src_db = 'vhms_rashmi_amc_2026';
SET @dst_db = 'vhms_riamsh_2026';
SET @start_dt = '2026-03-17 00:00:00';
SET @end_dt   = '2026-04-30 23:59:59';

USE `vhms_riamsh_2026`;

/* Safety: do not run while application is writing to destination DB. Take backup first. */
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE IF NOT EXISTS migration_opd_map (
    old_opdno INT NOT NULL PRIMARY KEY,
    new_opdno INT NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS migration_treatment_map (
    old_treatment_id INT NOT NULL PRIMARY KEY,
    new_treatment_id INT NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS migration_ipd_map (
    old_ipdno INT NOT NULL PRIMARY KEY,
    new_ipdno INT NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS migration_treatment_scope (
    old_treatment_id INT NOT NULL PRIMARY KEY
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS migration_opd_scope (
    old_opdno INT NOT NULL PRIMARY KEY
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS migration_ipd_scope (
    old_ipdno INT NOT NULL PRIMARY KEY
) ENGINE=InnoDB;

TRUNCATE TABLE migration_opd_map;
TRUNCATE TABLE migration_treatment_map;
TRUNCATE TABLE migration_ipd_map;
TRUNCATE TABLE migration_treatment_scope;
TRUNCATE TABLE migration_opd_scope;
TRUNCATE TABLE migration_ipd_scope;

/* ---------- Helper procedure for mapped child tables ---------- */
DROP PROCEDURE IF EXISTS migrate_child_table_mapped;
DELIMITER $$
CREATE PROCEDURE migrate_child_table_mapped(
    IN p_src_schema VARCHAR(64),
    IN p_dst_schema VARCHAR(64),
    IN p_table VARCHAR(64),
    IN p_date_column VARCHAR(64),
    IN p_start DATETIME,
    IN p_end DATETIME
)
BEGIN
    DECLARE v_cols LONGTEXT;
    DECLARE v_select_cols LONGTEXT;
    DECLARE v_date_type VARCHAR(64);
    DECLARE v_opd_col VARCHAR(64);
    DECLARE v_ipd_col VARCHAR(64);
    DECLARE v_treat_col VARCHAR(64);
    DECLARE v_opd_join TEXT DEFAULT 'NULL';
    DECLARE v_ipd_join TEXT DEFAULT 'NULL';
    DECLARE v_treat_join TEXT DEFAULT 'NULL';

    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION
    BEGIN
        GET DIAGNOSTICS CONDITION 1
            @sqlstate = RETURNED_SQLSTATE,
            @errno = MYSQL_ERRNO,
            @text = MESSAGE_TEXT;
        SET @full_error = CONCAT('ERROR ', @errno, ' (', @sqlstate, ') in ', p_table, ': ', @text);
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = @full_error;
    END;

    SELECT DATA_TYPE INTO v_date_type
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = p_src_schema AND TABLE_NAME = p_table AND COLUMN_NAME = p_date_column;

    IF v_date_type IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Date column not found';
    END IF;

    SELECT COLUMN_NAME INTO v_opd_col
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = p_src_schema AND TABLE_NAME = p_table AND LOWER(COLUMN_NAME) = 'opdno'
    ORDER BY ORDINAL_POSITION LIMIT 1;

    SELECT COLUMN_NAME INTO v_ipd_col
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = p_src_schema AND TABLE_NAME = p_table AND LOWER(COLUMN_NAME) IN ('ipdno','ipno')
    ORDER BY FIELD(LOWER(COLUMN_NAME),'ipdno','ipno'), ORDINAL_POSITION LIMIT 1;

    SELECT COLUMN_NAME INTO v_treat_col
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = p_src_schema AND TABLE_NAME = p_table AND LOWER(COLUMN_NAME) IN ('treatid','treat_id')
    ORDER BY FIELD(LOWER(COLUMN_NAME),'treatid','treat_id'), ORDINAL_POSITION LIMIT 1;

    IF v_opd_col IS NOT NULL THEN
        SET v_opd_join = CONCAT('s.`', v_opd_col, '`');
    END IF;
    IF v_ipd_col IS NOT NULL THEN
        SET v_ipd_join = CONCAT('s.`', v_ipd_col, '`');
    END IF;
    IF v_treat_col IS NOT NULL THEN
        SET v_treat_join = CONCAT('s.`', v_treat_col, '`');
    END IF;

    SELECT GROUP_CONCAT(CONCAT('`', c.COLUMN_NAME, '`') ORDER BY c.ORDINAL_POSITION SEPARATOR ', ')
    INTO v_cols
    FROM INFORMATION_SCHEMA.COLUMNS c
    WHERE c.TABLE_SCHEMA = p_dst_schema
      AND c.TABLE_NAME = p_table
      AND NOT EXISTS (
          SELECT 1 FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE k
          WHERE k.TABLE_SCHEMA = c.TABLE_SCHEMA
            AND k.TABLE_NAME = c.TABLE_NAME
            AND k.CONSTRAINT_NAME = 'PRIMARY'
            AND k.COLUMN_NAME = c.COLUMN_NAME
      );

    SELECT GROUP_CONCAT(
        CASE
            WHEN LOWER(c.COLUMN_NAME) = 'opdno' THEN
                CONCAT('CASE WHEN s.`', c.COLUMN_NAME, '` IS NULL OR s.`', c.COLUMN_NAME, '` = 0 THEN s.`', c.COLUMN_NAME, '` ELSE mopd.new_opdno END AS `', c.COLUMN_NAME, '`')
            WHEN LOWER(c.COLUMN_NAME) IN ('ipdno', 'ipno') THEN
                CONCAT('CASE WHEN s.`', c.COLUMN_NAME, '` IS NULL OR s.`', c.COLUMN_NAME, '` = 0 THEN s.`', c.COLUMN_NAME, '` ELSE mipd.new_ipdno END AS `', c.COLUMN_NAME, '`')
            WHEN LOWER(c.COLUMN_NAME) IN ('treatid', 'treat_id') THEN
                CONCAT('CASE WHEN s.`', c.COLUMN_NAME, '` IS NULL OR s.`', c.COLUMN_NAME, '` = 0 THEN s.`', c.COLUMN_NAME, '` ELSE mtrt.new_treatment_id END AS `', c.COLUMN_NAME, '`')
            ELSE CONCAT('s.`', c.COLUMN_NAME, '`')
        END
        ORDER BY c.ORDINAL_POSITION SEPARATOR ', '
    )
    INTO v_select_cols
    FROM INFORMATION_SCHEMA.COLUMNS c
    WHERE c.TABLE_SCHEMA = p_dst_schema
      AND c.TABLE_NAME = p_table
      AND NOT EXISTS (
          SELECT 1 FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE k
          WHERE k.TABLE_SCHEMA = c.TABLE_SCHEMA
            AND k.TABLE_NAME = c.TABLE_NAME
            AND k.CONSTRAINT_NAME = 'PRIMARY'
            AND k.COLUMN_NAME = c.COLUMN_NAME
      );

    IF v_cols IS NULL OR v_select_cols IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'No insertable columns found';
    END IF;

    SET @sql = CONCAT(
        'INSERT INTO `', p_dst_schema, '`.`', p_table, '` (', v_cols, ') ',
        'SELECT ', v_select_cols, ' ',
        'FROM `', p_src_schema, '`.`', p_table, '` s ',
        'LEFT JOIN `', p_dst_schema, '`.`migration_opd_map` mopd ON mopd.old_opdno = ', v_opd_join, ' ',
        'LEFT JOIN `', p_dst_schema, '`.`migration_ipd_map` mipd ON mipd.old_ipdno = ', v_ipd_join, ' ',
        'LEFT JOIN `', p_dst_schema, '`.`migration_treatment_map` mtrt ON mtrt.old_treatment_id = ', v_treat_join, ' ',
        'WHERE '
    );

    IF v_date_type IN ('date', 'datetime', 'timestamp') THEN
        SET @sql = CONCAT(@sql, 's.`', p_date_column, '` BETWEEN ? AND ?');
    ELSE
        SET @sql = CONCAT(@sql,
            '(COALESCE(STR_TO_DATE(s.`', p_date_column, '`, ''%Y-%m-%d %H:%i:%s''), ',
                      'STR_TO_DATE(s.`', p_date_column, '`, ''%Y-%m-%d''), ',
                      'STR_TO_DATE(s.`', p_date_column, '`, ''%d-%m-%Y'')) BETWEEN ? AND ? ',
            ' OR s.`', p_date_column, '` BETWEEN ? AND ?)'
        );
    END IF;

    PREPARE stmt FROM @sql;
    IF v_date_type IN ('date', 'datetime', 'timestamp') THEN
        EXECUTE stmt USING p_start, p_end;
    ELSE
        EXECUTE stmt USING p_start, p_end, p_start, p_end;
    END IF;
    SET @rows_inserted = ROW_COUNT();
    DEALLOCATE PREPARE stmt;

    SELECT p_table AS table_name, @rows_inserted AS inserted_rows;
END$$
DELIMITER ;

/* ---------- Scope collection ---------- */
START TRANSACTION;

INSERT IGNORE INTO migration_treatment_scope(old_treatment_id)
SELECT ID FROM vhms_rashmi_amc_2026.treatmentdata
WHERE COALESCE(STR_TO_DATE(CameOn,'%Y-%m-%d %H:%i:%s'), STR_TO_DATE(CameOn,'%Y-%m-%d'), STR_TO_DATE(CameOn,'%d-%m-%Y')) BETWEEN @start_dt AND @end_dt;

INSERT IGNORE INTO migration_treatment_scope(old_treatment_id)
SELECT treatid FROM vhms_rashmi_amc_2026.indent WHERE treatid IS NOT NULL AND treatid <> 0 AND COALESCE(STR_TO_DATE(indentdate,'%Y-%m-%d %H:%i:%s'),STR_TO_DATE(indentdate,'%Y-%m-%d'),STR_TO_DATE(indentdate,'%d-%m-%Y')) BETWEEN @start_dt AND @end_dt;
INSERT IGNORE INTO migration_treatment_scope(old_treatment_id)
SELECT treatID FROM vhms_rashmi_amc_2026.labregistery WHERE treatID IS NOT NULL AND treatID <> 0 AND COALESCE(STR_TO_DATE(testDate,'%Y-%m-%d %H:%i:%s'),STR_TO_DATE(testDate,'%Y-%m-%d'),STR_TO_DATE(testDate,'%d-%m-%Y')) BETWEEN @start_dt AND @end_dt;
INSERT IGNORE INTO migration_treatment_scope(old_treatment_id)
SELECT treatID FROM vhms_rashmi_amc_2026.xrayregistery WHERE treatID IS NOT NULL AND treatID <> 0 AND COALESCE(STR_TO_DATE(xrayDate,'%Y-%m-%d %H:%i:%s'),STR_TO_DATE(xrayDate,'%Y-%m-%d'),STR_TO_DATE(xrayDate,'%d-%m-%Y')) BETWEEN @start_dt AND @end_dt;
INSERT IGNORE INTO migration_treatment_scope(old_treatment_id)
SELECT treatId FROM vhms_rashmi_amc_2026.usgregistery WHERE treatId IS NOT NULL AND treatId <> 0 AND COALESCE(STR_TO_DATE(usgDate,'%Y-%m-%d %H:%i:%s'),STR_TO_DATE(usgDate,'%Y-%m-%d'),STR_TO_DATE(usgDate,'%d-%m-%Y')) BETWEEN @start_dt AND @end_dt;
INSERT IGNORE INTO migration_treatment_scope(old_treatment_id)
SELECT treatId FROM vhms_rashmi_amc_2026.surgeryregistery WHERE treatId IS NOT NULL AND treatId <> 0 AND COALESCE(STR_TO_DATE(surgDate,'%Y-%m-%d %H:%i:%s'),STR_TO_DATE(surgDate,'%Y-%m-%d'),STR_TO_DATE(surgDate,'%d-%m-%Y')) BETWEEN @start_dt AND @end_dt;
INSERT IGNORE INTO migration_treatment_scope(old_treatment_id)
SELECT treat_id FROM vhms_rashmi_amc_2026.sales_entry WHERE treat_id IS NOT NULL AND treat_id <> 0 AND COALESCE(STR_TO_DATE(`date`,'%Y-%m-%d %H:%i:%s'),STR_TO_DATE(`date`,'%Y-%m-%d'),STR_TO_DATE(`date`,'%d-%m-%Y')) BETWEEN @start_dt AND @end_dt;
INSERT IGNORE INTO migration_treatment_scope(old_treatment_id)
SELECT treatid FROM vhms_rashmi_amc_2026.panchaprocedure WHERE treatid IS NOT NULL AND treatid <> 0 AND COALESCE(STR_TO_DATE(`date`,'%Y-%m-%d %H:%i:%s'),STR_TO_DATE(`date`,'%Y-%m-%d'),STR_TO_DATE(`date`,'%d-%m-%Y')) BETWEEN @start_dt AND @end_dt;
INSERT IGNORE INTO migration_treatment_scope(old_treatment_id)
SELECT treatId FROM vhms_rashmi_amc_2026.ksharsutraregistery WHERE treatId IS NOT NULL AND treatId <> 0 AND COALESCE(STR_TO_DATE(ksharsDate,'%Y-%m-%d %H:%i:%s'),STR_TO_DATE(ksharsDate,'%Y-%m-%d'),STR_TO_DATE(ksharsDate,'%d-%m-%Y')) BETWEEN @start_dt AND @end_dt;
INSERT IGNORE INTO migration_treatment_scope(old_treatment_id)
SELECT treat_id FROM vhms_rashmi_amc_2026.kriyakalpa WHERE treat_id IS NOT NULL AND treat_id <> 0 AND kriya_date BETWEEN @start_dt AND @end_dt;
INSERT IGNORE INTO migration_treatment_scope(old_treatment_id)
SELECT treatId FROM vhms_rashmi_amc_2026.ecgregistery WHERE treatId IS NOT NULL AND treatId <> 0 AND COALESCE(STR_TO_DATE(ecgDate,'%Y-%m-%d %H:%i:%s'),STR_TO_DATE(ecgDate,'%Y-%m-%d'),STR_TO_DATE(ecgDate,'%d-%m-%Y')) BETWEEN @start_dt AND @end_dt;
INSERT IGNORE INTO migration_treatment_scope(old_treatment_id)
SELECT treat_id FROM vhms_rashmi_amc_2026.other_procedures_treatments WHERE treat_id IS NOT NULL AND treat_id <> 0 AND start_date BETWEEN @start_dt AND @end_dt;
INSERT IGNORE INTO migration_treatment_scope(old_treatment_id)
SELECT treat_id FROM vhms_rashmi_amc_2026.physiotherapy_treatments WHERE treat_id IS NOT NULL AND treat_id <> 0 AND start_date BETWEEN @start_dt AND @end_dt;

/* OPD scope from patients, treatments, and all OPD child rows */
INSERT IGNORE INTO migration_opd_scope(old_opdno)
SELECT OpdNo FROM vhms_rashmi_amc_2026.patientdata
WHERE OpdNo IS NOT NULL AND COALESCE(STR_TO_DATE(entrydate,'%Y-%m-%d %H:%i:%s'), STR_TO_DATE(entrydate,'%Y-%m-%d'), STR_TO_DATE(entrydate,'%d-%m-%Y')) BETWEEN @start_dt AND @end_dt;
INSERT IGNORE INTO migration_opd_scope(old_opdno)
SELECT t.OpdNo FROM vhms_rashmi_amc_2026.treatmentdata t JOIN migration_treatment_scope mts ON mts.old_treatment_id=t.ID WHERE t.OpdNo IS NOT NULL;
INSERT IGNORE INTO migration_opd_scope(old_opdno)
SELECT OpdNo FROM vhms_rashmi_amc_2026.inpatientdetails WHERE OpdNo IS NOT NULL AND COALESCE(STR_TO_DATE(DoAdmission,'%Y-%m-%d %H:%i:%s'), STR_TO_DATE(DoAdmission,'%Y-%m-%d'), STR_TO_DATE(DoAdmission,'%d-%m-%Y')) BETWEEN @start_dt AND @end_dt;
INSERT IGNORE INTO migration_opd_scope(old_opdno) SELECT opdno FROM vhms_rashmi_amc_2026.indent WHERE opdno IS NOT NULL AND COALESCE(STR_TO_DATE(indentdate,'%Y-%m-%d %H:%i:%s'),STR_TO_DATE(indentdate,'%Y-%m-%d'),STR_TO_DATE(indentdate,'%d-%m-%Y')) BETWEEN @start_dt AND @end_dt;
INSERT IGNORE INTO migration_opd_scope(old_opdno) SELECT OpdNo FROM vhms_rashmi_amc_2026.labregistery WHERE OpdNo IS NOT NULL AND COALESCE(STR_TO_DATE(testDate,'%Y-%m-%d %H:%i:%s'),STR_TO_DATE(testDate,'%Y-%m-%d'),STR_TO_DATE(testDate,'%d-%m-%Y')) BETWEEN @start_dt AND @end_dt;
INSERT IGNORE INTO migration_opd_scope(old_opdno) SELECT OpdNo FROM vhms_rashmi_amc_2026.xrayregistery WHERE OpdNo IS NOT NULL AND COALESCE(STR_TO_DATE(xrayDate,'%Y-%m-%d %H:%i:%s'),STR_TO_DATE(xrayDate,'%Y-%m-%d'),STR_TO_DATE(xrayDate,'%d-%m-%Y')) BETWEEN @start_dt AND @end_dt;
INSERT IGNORE INTO migration_opd_scope(old_opdno) SELECT OpdNo FROM vhms_rashmi_amc_2026.usgregistery WHERE OpdNo IS NOT NULL AND COALESCE(STR_TO_DATE(usgDate,'%Y-%m-%d %H:%i:%s'),STR_TO_DATE(usgDate,'%Y-%m-%d'),STR_TO_DATE(usgDate,'%d-%m-%Y')) BETWEEN @start_dt AND @end_dt;
INSERT IGNORE INTO migration_opd_scope(old_opdno) SELECT OpdNo FROM vhms_rashmi_amc_2026.surgeryregistery WHERE OpdNo IS NOT NULL AND COALESCE(STR_TO_DATE(surgDate,'%Y-%m-%d %H:%i:%s'),STR_TO_DATE(surgDate,'%Y-%m-%d'),STR_TO_DATE(surgDate,'%d-%m-%Y')) BETWEEN @start_dt AND @end_dt;
INSERT IGNORE INTO migration_opd_scope(old_opdno) SELECT opdno FROM vhms_rashmi_amc_2026.sales_entry WHERE opdno IS NOT NULL AND COALESCE(STR_TO_DATE(`date`,'%Y-%m-%d %H:%i:%s'),STR_TO_DATE(`date`,'%Y-%m-%d'),STR_TO_DATE(`date`,'%d-%m-%Y')) BETWEEN @start_dt AND @end_dt;
INSERT IGNORE INTO migration_opd_scope(old_opdno) SELECT opdno FROM vhms_rashmi_amc_2026.panchaprocedure WHERE opdno IS NOT NULL AND COALESCE(STR_TO_DATE(`date`,'%Y-%m-%d %H:%i:%s'),STR_TO_DATE(`date`,'%Y-%m-%d'),STR_TO_DATE(`date`,'%d-%m-%Y')) BETWEEN @start_dt AND @end_dt;
INSERT IGNORE INTO migration_opd_scope(old_opdno) SELECT OpdNo FROM vhms_rashmi_amc_2026.ksharsutraregistery WHERE OpdNo IS NOT NULL AND COALESCE(STR_TO_DATE(ksharsDate,'%Y-%m-%d %H:%i:%s'),STR_TO_DATE(ksharsDate,'%Y-%m-%d'),STR_TO_DATE(ksharsDate,'%d-%m-%Y')) BETWEEN @start_dt AND @end_dt;
INSERT IGNORE INTO migration_opd_scope(old_opdno) SELECT OpdNo FROM vhms_rashmi_amc_2026.kriyakalpa WHERE OpdNo IS NOT NULL AND kriya_date BETWEEN @start_dt AND @end_dt;
INSERT IGNORE INTO migration_opd_scope(old_opdno) SELECT OpdNo FROM vhms_rashmi_amc_2026.ecgregistery WHERE OpdNo IS NOT NULL AND COALESCE(STR_TO_DATE(ecgDate,'%Y-%m-%d %H:%i:%s'),STR_TO_DATE(ecgDate,'%Y-%m-%d'),STR_TO_DATE(ecgDate,'%d-%m-%Y')) BETWEEN @start_dt AND @end_dt;
INSERT IGNORE INTO migration_opd_scope(old_opdno) SELECT OpdNo FROM vhms_rashmi_amc_2026.other_procedures_treatments WHERE OpdNo IS NOT NULL AND start_date BETWEEN @start_dt AND @end_dt;
INSERT IGNORE INTO migration_opd_scope(old_opdno) SELECT OpdNo FROM vhms_rashmi_amc_2026.physiotherapy_treatments WHERE OpdNo IS NOT NULL AND start_date BETWEEN @start_dt AND @end_dt;

/* IPD scope */
INSERT IGNORE INTO migration_ipd_scope(old_ipdno)
SELECT IpNo FROM vhms_rashmi_amc_2026.inpatientdetails WHERE IpNo IS NOT NULL AND COALESCE(STR_TO_DATE(DoAdmission,'%Y-%m-%d %H:%i:%s'), STR_TO_DATE(DoAdmission,'%Y-%m-%d'), STR_TO_DATE(DoAdmission,'%d-%m-%Y')) BETWEEN @start_dt AND @end_dt;
INSERT IGNORE INTO migration_ipd_scope(old_ipdno) SELECT ipdno FROM vhms_rashmi_amc_2026.ipdtreatment WHERE ipdno IS NOT NULL AND COALESCE(STR_TO_DATE(attndedon,'%Y-%m-%d %H:%i:%s'),STR_TO_DATE(attndedon,'%Y-%m-%d'),STR_TO_DATE(attndedon,'%d-%m-%Y')) BETWEEN @start_dt AND @end_dt;
INSERT IGNORE INTO migration_ipd_scope(old_ipdno) SELECT ipdno FROM vhms_rashmi_amc_2026.indent WHERE ipdno IS NOT NULL AND COALESCE(STR_TO_DATE(indentdate,'%Y-%m-%d %H:%i:%s'),STR_TO_DATE(indentdate,'%Y-%m-%d'),STR_TO_DATE(indentdate,'%d-%m-%Y')) BETWEEN @start_dt AND @end_dt;
INSERT IGNORE INTO migration_ipd_scope(old_ipdno) SELECT ipdno FROM vhms_rashmi_amc_2026.labregistery WHERE ipdno IS NOT NULL AND COALESCE(STR_TO_DATE(testDate,'%Y-%m-%d %H:%i:%s'),STR_TO_DATE(testDate,'%Y-%m-%d'),STR_TO_DATE(testDate,'%d-%m-%Y')) BETWEEN @start_dt AND @end_dt;
INSERT IGNORE INTO migration_ipd_scope(old_ipdno) SELECT ipdno FROM vhms_rashmi_amc_2026.xrayregistery WHERE ipdno IS NOT NULL AND COALESCE(STR_TO_DATE(xrayDate,'%Y-%m-%d %H:%i:%s'),STR_TO_DATE(xrayDate,'%Y-%m-%d'),STR_TO_DATE(xrayDate,'%d-%m-%Y')) BETWEEN @start_dt AND @end_dt;
INSERT IGNORE INTO migration_ipd_scope(old_ipdno) SELECT ipdno FROM vhms_rashmi_amc_2026.surgeryregistery WHERE ipdno IS NOT NULL AND COALESCE(STR_TO_DATE(surgDate,'%Y-%m-%d %H:%i:%s'),STR_TO_DATE(surgDate,'%Y-%m-%d'),STR_TO_DATE(surgDate,'%d-%m-%Y')) BETWEEN @start_dt AND @end_dt;
INSERT IGNORE INTO migration_ipd_scope(old_ipdno) SELECT ipdno FROM vhms_rashmi_amc_2026.sales_entry WHERE ipdno IS NOT NULL AND COALESCE(STR_TO_DATE(`date`,'%Y-%m-%d %H:%i:%s'),STR_TO_DATE(`date`,'%Y-%m-%d'),STR_TO_DATE(`date`,'%d-%m-%Y')) BETWEEN @start_dt AND @end_dt;
INSERT IGNORE INTO migration_ipd_scope(old_ipdno) SELECT IpNo FROM vhms_rashmi_amc_2026.kriyakalpa WHERE IpNo IS NOT NULL AND kriya_date BETWEEN @start_dt AND @end_dt;
INSERT IGNORE INTO migration_ipd_scope(old_ipdno) SELECT IpNo FROM vhms_rashmi_amc_2026.other_procedures_treatments WHERE IpNo IS NOT NULL AND start_date BETWEEN @start_dt AND @end_dt;
INSERT IGNORE INTO migration_ipd_scope(old_ipdno) SELECT IpNo FROM vhms_rashmi_amc_2026.physiotherapy_treatments WHERE IpNo IS NOT NULL AND start_date BETWEEN @start_dt AND @end_dt;

/* ---------- Create ID maps ---------- */
SET @max_dst_opd := (SELECT COALESCE(MAX(OpdNo), 0) FROM vhms_riamsh_2026.patientdata);
SET @rn := 0;
INSERT INTO migration_opd_map(old_opdno, new_opdno)
SELECT old_opdno, @max_dst_opd + seq_no
FROM (
    SELECT old_opdno, (@rn := @rn + 1) AS seq_no
    FROM migration_opd_scope
    ORDER BY old_opdno
) x;

SET @max_dst_ipd := (SELECT COALESCE(MAX(IpNo), 0) FROM vhms_riamsh_2026.inpatientdetails);
SET @rn := 0;
INSERT INTO migration_ipd_map(old_ipdno, new_ipdno)
SELECT old_ipdno, @max_dst_ipd + seq_no
FROM (
    SELECT old_ipdno, (@rn := @rn + 1) AS seq_no
    FROM migration_ipd_scope
    ORDER BY old_ipdno
) x;

/* ---------- Precheck: referenced parents must exist in source ---------- */
SELECT 'missing source patientdata for mapped OPD' AS check_name, COUNT(*) AS missing_count
FROM migration_opd_map m LEFT JOIN vhms_rashmi_amc_2026.patientdata p ON p.OpdNo = m.old_opdno
WHERE p.OpdNo IS NULL;

SELECT 'missing source treatmentdata for mapped treatment IDs' AS check_name, COUNT(*) AS missing_count
FROM migration_treatment_scope s LEFT JOIN vhms_rashmi_amc_2026.treatmentdata t ON t.ID = s.old_treatment_id
WHERE t.ID IS NULL;

SELECT 'missing source inpatientdetails for mapped IPD' AS check_name, COUNT(*) AS missing_count
FROM migration_ipd_map m LEFT JOIN vhms_rashmi_amc_2026.inpatientdetails ip ON ip.IpNo = m.old_ipdno
WHERE ip.IpNo IS NULL;

/* ---------- Insert patientdata ---------- */
INSERT INTO vhms_riamsh_2026.patientdata
(OpdNo, UHID, FirstName, MidName, LastName, Age, gender, occupation, address, city, AddedBy, entrydate, dept, mob, deptOpdNo, sub_dept, sid)
SELECT
    m.new_opdno,
    CASE WHEN p.UHID IS NOT NULL AND EXISTS (SELECT 1 FROM vhms_riamsh_2026.patientdata d WHERE d.UHID = p.UHID) THEN NULL ELSE p.UHID END,
    p.FirstName, p.MidName, p.LastName, p.Age, p.gender, p.occupation, p.address, p.city,
    p.AddedBy, p.entrydate, p.dept, p.mob, p.deptOpdNo, p.sub_dept, p.sid
FROM migration_opd_map m
JOIN vhms_rashmi_amc_2026.patientdata p ON p.OpdNo = m.old_opdno;

SELECT ROW_COUNT() AS patientdata_inserted;

/* ---------- Insert treatmentdata and capture old ID -> new ID ---------- */
DROP PROCEDURE IF EXISTS migrate_treatmentdata_with_map;
DELIMITER $$
CREATE PROCEDURE migrate_treatmentdata_with_map()
BEGIN
    DECLARE done INT DEFAULT 0;
    DECLARE v_old_id INT;
    DECLARE v_opd INT;
    DECLARE v_deptOpdNo INT;
    DECLARE v_PatType VARCHAR(15);
    DECLARE v_AddedBy VARCHAR(30);
    DECLARE v_CameOn VARCHAR(25);
    DECLARE v_Trtment VARCHAR(256);
    DECLARE v_diagnosis VARCHAR(256);
    DECLARE v_complaints VARCHAR(256);
    DECLARE v_department VARCHAR(256);
    DECLARE v_procedures VARCHAR(256);
    DECLARE v_notes VARCHAR(256);
    DECLARE v_InOrOutPat VARCHAR(256);
    DECLARE v_attndedby VARCHAR(256);
    DECLARE v_attndedon VARCHAR(256);
    DECLARE v_monthly_sid INT;
    DECLARE v_sub_department VARCHAR(100);
    DECLARE v_medicines VARCHAR(256);
    DECLARE v_sequence INT;

    DECLARE cur CURSOR FOR
        SELECT t.ID, m.new_opdno, t.deptOpdNo, t.PatType, t.AddedBy, t.CameOn, t.Trtment,
               t.diagnosis, t.complaints, t.department, t.procedures, t.notes, t.InOrOutPat,
               t.attndedby, t.attndedon, t.monthly_sid, t.sub_department, t.medicines, t.sequence
        FROM vhms_rashmi_amc_2026.treatmentdata t
        JOIN migration_treatment_scope s ON s.old_treatment_id = t.ID
        JOIN migration_opd_map m ON m.old_opdno = t.OpdNo
        ORDER BY t.ID;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    OPEN cur;
    read_loop: LOOP
        FETCH cur INTO v_old_id, v_opd, v_deptOpdNo, v_PatType, v_AddedBy, v_CameOn, v_Trtment,
                       v_diagnosis, v_complaints, v_department, v_procedures, v_notes, v_InOrOutPat,
                       v_attndedby, v_attndedon, v_monthly_sid, v_sub_department, v_medicines, v_sequence;
        IF done = 1 THEN
            LEAVE read_loop;
        END IF;

        INSERT INTO vhms_riamsh_2026.treatmentdata
        (OpdNo, deptOpdNo, PatType, AddedBy, CameOn, Trtment, diagnosis, complaints, department,
         procedures, notes, InOrOutPat, attndedby, attndedon, monthly_sid, sub_department, medicines, sequence)
        VALUES
        (v_opd, v_deptOpdNo, v_PatType, v_AddedBy, v_CameOn, v_Trtment, v_diagnosis, v_complaints, v_department,
         v_procedures, v_notes, v_InOrOutPat, v_attndedby, v_attndedon, v_monthly_sid, v_sub_department, v_medicines, v_sequence);

        INSERT INTO migration_treatment_map(old_treatment_id, new_treatment_id)
        VALUES (v_old_id, LAST_INSERT_ID());
    END LOOP;
    CLOSE cur;
END$$
DELIMITER ;

CALL migrate_treatmentdata_with_map();
SELECT COUNT(*) AS treatmentdata_inserted FROM migration_treatment_map;

/* ---------- Insert inpatientdetails ---------- */
INSERT INTO vhms_riamsh_2026.inpatientdetails
(IpNo, OpdNo, deptOpdNo, FName, Age, Gender, department, WardNo, BedNo, diagnosis, DoAdmission, DoDischarge,
 DischargeNotes, NofDays, Doctor, DischBy, treatId, status, sid, admit_time, discharge_time)
SELECT
    im.new_ipdno,
    om.new_opdno,
    ip.deptOpdNo, ip.FName, ip.Age, ip.Gender, ip.department, ip.WardNo, ip.BedNo, ip.diagnosis,
    ip.DoAdmission, ip.DoDischarge, ip.DischargeNotes, ip.NofDays, ip.Doctor, ip.DischBy,
    CASE WHEN ip.treatId IS NULL OR ip.treatId = '' OR ip.treatId = '0' THEN ip.treatId ELSE CAST(tm.new_treatment_id AS CHAR) END,
    ip.status, ip.sid, ip.admit_time, ip.discharge_time
FROM migration_ipd_map im
JOIN vhms_rashmi_amc_2026.inpatientdetails ip ON ip.IpNo = im.old_ipdno
LEFT JOIN migration_opd_map om ON om.old_opdno = ip.OpdNo
LEFT JOIN migration_treatment_map tm ON tm.old_treatment_id = CAST(ip.treatId AS UNSIGNED);

SELECT ROW_COUNT() AS inpatientdetails_inserted;

/* ---------- Child tables ---------- */
CALL migrate_child_table_mapped('vhms_rashmi_amc_2026', 'vhms_riamsh_2026', 'ipdtreatment', 'attndedon', @start_dt, @end_dt);
CALL migrate_child_table_mapped('vhms_rashmi_amc_2026', 'vhms_riamsh_2026', 'indent', 'indentdate', @start_dt, @end_dt);
CALL migrate_child_table_mapped('vhms_rashmi_amc_2026', 'vhms_riamsh_2026', 'labregistery', 'testDate', @start_dt, @end_dt);
CALL migrate_child_table_mapped('vhms_rashmi_amc_2026', 'vhms_riamsh_2026', 'xrayregistery', 'xrayDate', @start_dt, @end_dt);
CALL migrate_child_table_mapped('vhms_rashmi_amc_2026', 'vhms_riamsh_2026', 'usgregistery', 'usgDate', @start_dt, @end_dt);
CALL migrate_child_table_mapped('vhms_rashmi_amc_2026', 'vhms_riamsh_2026', 'surgeryregistery', 'surgDate', @start_dt, @end_dt);
CALL migrate_child_table_mapped('vhms_rashmi_amc_2026', 'vhms_riamsh_2026', 'sales_entry', 'date', @start_dt, @end_dt);
CALL migrate_child_table_mapped('vhms_rashmi_amc_2026', 'vhms_riamsh_2026', 'panchaprocedure', 'date', @start_dt, @end_dt);
CALL migrate_child_table_mapped('vhms_rashmi_amc_2026', 'vhms_riamsh_2026', 'ksharsutraregistery', 'ksharsDate', @start_dt, @end_dt);
CALL migrate_child_table_mapped('vhms_rashmi_amc_2026', 'vhms_riamsh_2026', 'kriyakalpa', 'kriya_date', @start_dt, @end_dt);
CALL migrate_child_table_mapped('vhms_rashmi_amc_2026', 'vhms_riamsh_2026', 'ecgregistery', 'ecgDate', @start_dt, @end_dt);
CALL migrate_child_table_mapped('vhms_rashmi_amc_2026', 'vhms_riamsh_2026', 'other_procedures_treatments', 'start_date', @start_dt, @end_dt);
CALL migrate_child_table_mapped('vhms_rashmi_amc_2026', 'vhms_riamsh_2026', 'physiotherapy_treatments', 'start_date', @start_dt, @end_dt);

/* ---------- Audit checks ---------- */
SELECT 'OPD map' AS audit_name, COUNT(*) AS rows_mapped, MIN(new_opdno) AS first_new, MAX(new_opdno) AS last_new FROM migration_opd_map;
SELECT 'Treatment map' AS audit_name, COUNT(*) AS rows_mapped, MIN(new_treatment_id) AS first_new, MAX(new_treatment_id) AS last_new FROM migration_treatment_map;
SELECT 'IPD map' AS audit_name, COUNT(*) AS rows_mapped, MIN(new_ipdno) AS first_new, MAX(new_ipdno) AS last_new FROM migration_ipd_map;

SELECT 'Missing destination patient rows' AS audit_name, COUNT(*) AS missing_count
FROM migration_opd_map m LEFT JOIN vhms_riamsh_2026.patientdata p ON p.OpdNo = m.new_opdno
WHERE p.OpdNo IS NULL;

SELECT 'Missing destination treatment rows' AS audit_name, COUNT(*) AS missing_count
FROM migration_treatment_map m LEFT JOIN vhms_riamsh_2026.treatmentdata t ON t.ID = m.new_treatment_id
WHERE t.ID IS NULL;

SELECT 'Missing destination IPD rows' AS audit_name, COUNT(*) AS missing_count
FROM migration_ipd_map m LEFT JOIN vhms_riamsh_2026.inpatientdetails ip ON ip.IpNo = m.new_ipdno
WHERE ip.IpNo IS NULL;

COMMIT;

/* Optional cleanup only after audit/report is complete:
DROP PROCEDURE IF EXISTS migrate_child_table_mapped;
DROP PROCEDURE IF EXISTS migrate_treatmentdata_with_map;
*/
