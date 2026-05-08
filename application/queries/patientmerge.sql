DELIMITER $$

DROP PROCEDURE IF EXISTS sync_dependencies $$
CREATE PROCEDURE sync_dependencies(
    IN p_src_schema VARCHAR(64),
    IN p_dst_schema VARCHAR(64),
    IN p_table VARCHAR(64),
    IN p_start DATETIME,
    IN p_end DATETIME
)
BEGIN
    -- Declare all variables first
    DECLARE v_referenced_table VARCHAR(64);
    DECLARE v_referenced_column VARCHAR(64);
    DECLARE v_column VARCHAR(64);
    DECLARE done INT DEFAULT FALSE;
    
    -- Declare cursor before handler
    DECLARE cur CURSOR FOR 
        SELECT DISTINCT
            k.REFERENCED_TABLE_NAME,
            k.REFERENCED_COLUMN_NAME,
            k.COLUMN_NAME
        FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE k
        WHERE k.TABLE_SCHEMA = p_src_schema
        AND k.TABLE_NAME = p_table
        AND k.REFERENCED_TABLE_NAME IS NOT NULL;
    
    -- Declare handler last
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    OPEN cur;
    read_loop: LOOP
        FETCH cur INTO v_referenced_table, v_referenced_column, v_column;
        IF done THEN
            LEAVE read_loop;
        END IF;

        -- Sync the referenced table first
        SET @dep_sql = CONCAT(
            'INSERT IGNORE INTO `', p_dst_schema, '`.`', v_referenced_table, '` ',
            'SELECT DISTINCT r.* FROM `', p_src_schema, '`.`', v_referenced_table, '` r ',
            'INNER JOIN `', p_src_schema, '`.`', p_table, '` t ',
            'ON r.`', v_referenced_column, '` = t.`', v_column, '` ',
            'LEFT JOIN `', p_dst_schema, '`.`', v_referenced_table, '` d ',
            'ON r.`', v_referenced_column, '` = d.`', v_referenced_column, '` ',
            'WHERE d.`', v_referenced_column, '` IS NULL'
        );
        PREPARE stmt FROM @dep_sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END LOOP;
    CLOSE cur;
END$$

DROP PROCEDURE IF EXISTS sync_table_by_date $$
CREATE PROCEDURE `sync_table_by_date`(
    IN p_src_schema   VARCHAR(64),
    IN p_dst_schema   VARCHAR(64),
    IN p_table        VARCHAR(64),
    IN p_date_column  VARCHAR(64),        -- e.g., 'updated_at' or 'DoAdmission'
    IN p_start        DATETIME,           -- inclusive
    IN p_end          DATETIME            -- inclusive
)
BEGIN
    -- All declarations must be in order: variables, cursors, then handlers
    DECLARE v_column_type VARCHAR(64);
    DECLARE v_batch_size INT DEFAULT 5000;
    DECLARE v_last_id INT DEFAULT 0;
    DECLARE v_cols TEXT;
    DECLARE v_update_list TEXT;
    DECLARE v_first_pk VARCHAR(255);
    DECLARE v_date_exists INT DEFAULT 0;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION
    BEGIN
        GET DIAGNOSTICS CONDITION 1
            @sqlstate = RETURNED_SQLSTATE,
            @errno = MYSQL_ERRNO,
            @text = MESSAGE_TEXT;
        SET @full_error = CONCAT('ERROR ', @errno, ' (', @sqlstate, '): ', @text);
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = @full_error;
    END;

    -- Check if source table exists
    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.TABLES 
        WHERE TABLE_SCHEMA = p_src_schema AND TABLE_NAME = p_table
    ) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Source table does not exist';
    END IF;

    -- Check if destination table exists
    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.TABLES 
        WHERE TABLE_SCHEMA = p_dst_schema AND TABLE_NAME = p_table
    ) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Destination table does not exist';
    END IF;

    -- Get date column type and verify it exists
    SELECT DATA_TYPE INTO v_column_type
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = p_src_schema
        AND TABLE_NAME = p_table
        AND COLUMN_NAME = p_date_column;

    IF v_column_type IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Date column not found in source table';
    END IF;

    -- Sync dependencies first
    CALL sync_dependencies(p_src_schema, p_dst_schema, p_table, p_start, p_end);

    -- Build column list from DESTINATION (ensures columns exist there)
    SELECT GROUP_CONCAT(CONCAT('`', c.COLUMN_NAME, '`') ORDER BY c.ORDINAL_POSITION)
      INTO v_cols
      FROM INFORMATION_SCHEMA.COLUMNS c
     WHERE c.TABLE_SCHEMA = p_dst_schema
       AND c.TABLE_NAME   = p_table;
    IF v_cols IS NULL THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Destination table not found or has no columns';
    END IF;

    -- First PK column (for a no-op fallback)
    SELECT MIN(CONCAT('`', k.COLUMN_NAME, '`'))
      INTO v_first_pk
      FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE k
     WHERE k.TABLE_SCHEMA    = p_dst_schema
       AND k.TABLE_NAME      = p_table
       AND k.CONSTRAINT_NAME = 'PRIMARY';

    -- Build update list for all NON-PK columns using VALUES()
    SELECT GROUP_CONCAT(CONCAT('`', c.COLUMN_NAME, '`=VALUES(`', c.COLUMN_NAME, '`)')
                        ORDER BY c.ORDINAL_POSITION SEPARATOR ', ')
      INTO v_update_list
      FROM INFORMATION_SCHEMA.COLUMNS c
     WHERE c.TABLE_SCHEMA = p_dst_schema
       AND c.TABLE_NAME   = p_table
       AND NOT EXISTS (
           SELECT 1
             FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE k
            WHERE k.TABLE_SCHEMA    = c.TABLE_SCHEMA
              AND k.TABLE_NAME      = c.TABLE_NAME
              AND k.CONSTRAINT_NAME = 'PRIMARY'
              AND k.COLUMN_NAME     = c.COLUMN_NAME
       );

    IF v_update_list IS NULL OR v_update_list = '' THEN
        IF v_first_pk IS NULL THEN
            SIGNAL SQLSTATE '45000'
                SET MESSAGE_TEXT = 'Table has no PK and no updatable columns';
        END IF;
        -- true no-op if there are no non-PK columns
        SET v_update_list = CONCAT(v_first_pk, '=', v_first_pk);
    END IF;

    -- Build the main sync query with proper date handling
    SET @sql = CASE v_column_type
        WHEN 'varchar' THEN
            CONCAT(
                'INSERT INTO `', p_dst_schema, '`.`', p_table, '` (', v_cols, ') ',
                'SELECT ', v_cols, ' FROM `', p_src_schema, '`.`', p_table, '` s ',
                'WHERE STR_TO_DATE(s.`', p_date_column, '`, "%Y-%m-%d %H:%i:%s") BETWEEN ? AND ? ',
                'OR STR_TO_DATE(s.`', p_date_column, '`, "%Y-%m-%d") BETWEEN ? AND ? ',
                'ON DUPLICATE KEY UPDATE ', v_update_list
            )
        ELSE
            CONCAT(
                'INSERT INTO `', p_dst_schema, '`.`', p_table, '` (', v_cols, ') ',
                'SELECT ', v_cols, ' FROM `', p_src_schema, '`.`', p_table, '` s ',
                'WHERE s.`', p_date_column, '` BETWEEN ? AND ? ',
                'ON DUPLICATE KEY UPDATE ', v_update_list
            )
    END;

    -- Execute with proper parameter handling
    PREPARE stmt FROM @sql;
    IF v_column_type = 'varchar' THEN
        EXECUTE stmt USING p_start, p_end, p_start, p_end;
    ELSE
        EXECUTE stmt USING p_start, p_end;
    END IF;

    -- Get the number of affected rows
    SET @affected = ROW_COUNT();
    
    -- Provide detailed feedback
    SELECT 
        @affected as affected_rows,
        p_table as table_name,
        p_date_column as date_column,
        v_column_type as column_type,
        p_start as start_date,
        p_end as end_date;

    DEALLOCATE PREPARE stmt;
END$$

DROP PROCEDURE IF EXISTS sync_discharged_patients_by_date $$
CREATE PROCEDURE `sync_discharged_patients_by_date`(
    IN p_src_schema VARCHAR(64),
    IN p_dst_schema VARCHAR(64),
    IN p_start DATETIME,
    IN p_end DATETIME
)
BEGIN
    DECLARE v_cols TEXT;
    DECLARE v_select_cols TEXT;
    DECLARE v_update_list TEXT;
    DECLARE v_first_pk VARCHAR(255);
    DECLARE v_discharge_count INT DEFAULT 0;
    DECLARE v_inpatient_synced INT DEFAULT 0;
    DECLARE v_bed_updated INT DEFAULT 0;
    DECLARE v_treatment_synced INT DEFAULT 0;
    DECLARE v_indent_synced INT DEFAULT 0;
    DECLARE v_treated_updated INT DEFAULT 0;

    IF NOT EXISTS (
        SELECT 1
        FROM INFORMATION_SCHEMA.TABLES
        WHERE TABLE_SCHEMA = p_src_schema
          AND TABLE_NAME = 'inpatientdetails'
    ) THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Source inpatientdetails table does not exist';
    END IF;

    IF NOT EXISTS (
        SELECT 1
        FROM INFORMATION_SCHEMA.TABLES
        WHERE TABLE_SCHEMA = p_dst_schema
          AND TABLE_NAME = 'inpatientdetails'
    ) THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Destination inpatientdetails table does not exist';
    END IF;

    DROP TEMPORARY TABLE IF EXISTS tmp_discharge_ipds;
    CREATE TEMPORARY TABLE tmp_discharge_ipds (
        IpNo INT PRIMARY KEY
    );

    SET @src_start = p_start;
    SET @src_end = p_end;
    SET @sql = CONCAT(
        'INSERT INTO tmp_discharge_ipds (IpNo) ',
        'SELECT DISTINCT s.`IpNo` ',
        'FROM `', p_src_schema, '`.`inpatientdetails` s ',
        'WHERE s.`status` = ''discharge'' ',
        'AND COALESCE(',
            'STR_TO_DATE(s.`DoDischarge`, ''%Y-%m-%d %H:%i:%s''), ',
            'STR_TO_DATE(s.`DoDischarge`, ''%Y-%m-%d''), ',
            'STR_TO_DATE(s.`DoDischarge`, ''%d-%m-%Y''), ',
            'CAST(s.`DoDischarge` AS DATETIME)',
        ') BETWEEN ? AND ?'
    );
    PREPARE stmt FROM @sql;
    EXECUTE stmt USING @src_start, @src_end;
    DEALLOCATE PREPARE stmt;

    SELECT COUNT(*) INTO v_discharge_count
    FROM tmp_discharge_ipds;

    IF v_discharge_count = 0 THEN
        SELECT
            0 AS discharged_ipds,
            0 AS inpatientdetails_synced,
            0 AS bed_details_updated,
            0 AS ipdtreatment_synced,
            0 AS indent_synced,
            0 AS treated_status_updated;
        DROP TEMPORARY TABLE IF EXISTS tmp_discharge_ipds;
    ELSE
        SELECT GROUP_CONCAT(CONCAT('`', c.COLUMN_NAME, '`') ORDER BY c.ORDINAL_POSITION)
          INTO v_cols
          FROM INFORMATION_SCHEMA.COLUMNS c
         WHERE c.TABLE_SCHEMA = p_dst_schema
           AND c.TABLE_NAME = 'inpatientdetails';

        SELECT GROUP_CONCAT(CONCAT('s.`', c.COLUMN_NAME, '`') ORDER BY c.ORDINAL_POSITION)
          INTO v_select_cols
          FROM INFORMATION_SCHEMA.COLUMNS c
         WHERE c.TABLE_SCHEMA = p_dst_schema
           AND c.TABLE_NAME = 'inpatientdetails';

        SELECT MIN(CONCAT('`', k.COLUMN_NAME, '`'))
          INTO v_first_pk
          FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE k
         WHERE k.TABLE_SCHEMA = p_dst_schema
           AND k.TABLE_NAME = 'inpatientdetails'
           AND k.CONSTRAINT_NAME = 'PRIMARY';

        SELECT GROUP_CONCAT(CONCAT('`', c.COLUMN_NAME, '`=VALUES(`', c.COLUMN_NAME, '`)')
                            ORDER BY c.ORDINAL_POSITION SEPARATOR ', ')
          INTO v_update_list
          FROM INFORMATION_SCHEMA.COLUMNS c
         WHERE c.TABLE_SCHEMA = p_dst_schema
           AND c.TABLE_NAME = 'inpatientdetails'
           AND NOT EXISTS (
               SELECT 1
               FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE k
               WHERE k.TABLE_SCHEMA = c.TABLE_SCHEMA
                 AND k.TABLE_NAME = c.TABLE_NAME
                 AND k.CONSTRAINT_NAME = 'PRIMARY'
                 AND k.COLUMN_NAME = c.COLUMN_NAME
           );

        IF v_update_list IS NULL OR v_update_list = '' THEN
            SET v_update_list = CONCAT(v_first_pk, '=', v_first_pk);
        END IF;

        SET @sql = CONCAT(
            'INSERT INTO `', p_dst_schema, '`.`inpatientdetails` (', v_cols, ') ',
            'SELECT ', v_select_cols, ' ',
            'FROM `', p_src_schema, '`.`inpatientdetails` s ',
            'INNER JOIN tmp_discharge_ipds t ON t.`IpNo` = s.`IpNo` ',
            'ON DUPLICATE KEY UPDATE ', v_update_list
        );
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        SET v_inpatient_synced = ROW_COUNT();
        DEALLOCATE PREPARE stmt;

        SET @sql = CONCAT(
            'UPDATE `', p_dst_schema, '`.`bed_details` b ',
            'INNER JOIN `', p_dst_schema, '`.`inpatientdetails` i ON i.`BedNo` = b.`bedno` ',
            'INNER JOIN tmp_discharge_ipds t ON t.`IpNo` = i.`IpNo` ',
            'SET b.`OpdNo` = NULL, ',
                'b.`bedstatus` = ''Available'', ',
                'b.`treatId` = NULL, ',
                'b.`IpNo` = NULL'
        );
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        SET v_bed_updated = ROW_COUNT();
        DEALLOCATE PREPARE stmt;

        IF EXISTS (
            SELECT 1
            FROM INFORMATION_SCHEMA.TABLES
            WHERE TABLE_SCHEMA = p_src_schema
              AND TABLE_NAME = 'ipdtreatment'
        ) THEN
            IF EXISTS (
                SELECT 1
                FROM INFORMATION_SCHEMA.TABLES
                WHERE TABLE_SCHEMA = p_dst_schema
                  AND TABLE_NAME = 'ipdtreatment'
            ) THEN
                SELECT GROUP_CONCAT(CONCAT('`', c.COLUMN_NAME, '`') ORDER BY c.ORDINAL_POSITION)
                  INTO v_cols
                  FROM INFORMATION_SCHEMA.COLUMNS c
                 WHERE c.TABLE_SCHEMA = p_dst_schema
                   AND c.TABLE_NAME = 'ipdtreatment';

                SELECT GROUP_CONCAT(CONCAT('s.`', c.COLUMN_NAME, '`') ORDER BY c.ORDINAL_POSITION)
                  INTO v_select_cols
                  FROM INFORMATION_SCHEMA.COLUMNS c
                 WHERE c.TABLE_SCHEMA = p_dst_schema
                   AND c.TABLE_NAME = 'ipdtreatment';

            SELECT MIN(CONCAT('`', k.COLUMN_NAME, '`'))
              INTO v_first_pk
              FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE k
             WHERE k.TABLE_SCHEMA = p_dst_schema
               AND k.TABLE_NAME = 'ipdtreatment'
               AND k.CONSTRAINT_NAME = 'PRIMARY';

            SELECT GROUP_CONCAT(CONCAT('`', c.COLUMN_NAME, '`=VALUES(`', c.COLUMN_NAME, '`)')
                                ORDER BY c.ORDINAL_POSITION SEPARATOR ', ')
              INTO v_update_list
              FROM INFORMATION_SCHEMA.COLUMNS c
             WHERE c.TABLE_SCHEMA = p_dst_schema
               AND c.TABLE_NAME = 'ipdtreatment'
               AND NOT EXISTS (
                   SELECT 1
                   FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE k
                   WHERE k.TABLE_SCHEMA = c.TABLE_SCHEMA
                     AND k.TABLE_NAME = c.TABLE_NAME
                     AND k.CONSTRAINT_NAME = 'PRIMARY'
                     AND k.COLUMN_NAME = c.COLUMN_NAME
               );

            IF v_update_list IS NULL OR v_update_list = '' THEN
                SET v_update_list = CONCAT(v_first_pk, '=', v_first_pk);
            END IF;

                SET @sql = CONCAT(
                    'INSERT INTO `', p_dst_schema, '`.`ipdtreatment` (', v_cols, ') ',
                    'SELECT ', v_select_cols, ' ',
                    'FROM `', p_src_schema, '`.`ipdtreatment` s ',
                    'INNER JOIN tmp_discharge_ipds t ON t.`IpNo` = s.`ipdno` ',
                    'ON DUPLICATE KEY UPDATE ', v_update_list
                );
            PREPARE stmt FROM @sql;
            EXECUTE stmt;
            SET v_treatment_synced = ROW_COUNT();
            DEALLOCATE PREPARE stmt;

            SET @sql = CONCAT(
                'UPDATE `', p_dst_schema, '`.`ipdtreatment` it ',
                'INNER JOIN tmp_discharge_ipds t ON t.`IpNo` = it.`ipdno` ',
                'SET it.`status` = ''Treated'''
            );
            PREPARE stmt FROM @sql;
            EXECUTE stmt;
            SET v_treated_updated = ROW_COUNT();
            DEALLOCATE PREPARE stmt;
            END IF;
        END IF;

        IF EXISTS (
            SELECT 1
            FROM INFORMATION_SCHEMA.TABLES
            WHERE TABLE_SCHEMA = p_src_schema
              AND TABLE_NAME = 'indent'
        ) THEN
            IF EXISTS (
                SELECT 1
                FROM INFORMATION_SCHEMA.TABLES
                WHERE TABLE_SCHEMA = p_dst_schema
                  AND TABLE_NAME = 'indent'
            ) THEN
                SELECT GROUP_CONCAT(CONCAT('`', c.COLUMN_NAME, '`') ORDER BY c.ORDINAL_POSITION)
                  INTO v_cols
                  FROM INFORMATION_SCHEMA.COLUMNS c
                 WHERE c.TABLE_SCHEMA = p_dst_schema
                   AND c.TABLE_NAME = 'indent';

                SELECT GROUP_CONCAT(CONCAT('s.`', c.COLUMN_NAME, '`') ORDER BY c.ORDINAL_POSITION)
                  INTO v_select_cols
                  FROM INFORMATION_SCHEMA.COLUMNS c
                 WHERE c.TABLE_SCHEMA = p_dst_schema
                   AND c.TABLE_NAME = 'indent';

            SET @sql = CONCAT(
                'DELETE d ',
                'FROM `', p_dst_schema, '`.`indent` d ',
                'INNER JOIN tmp_discharge_ipds t ON t.`IpNo` = d.`ipdno`'
            );
            PREPARE stmt FROM @sql;
            EXECUTE stmt;
            DEALLOCATE PREPARE stmt;

                SET @sql = CONCAT(
                    'INSERT INTO `', p_dst_schema, '`.`indent` (', v_cols, ') ',
                    'SELECT ', v_select_cols, ' ',
                    'FROM `', p_src_schema, '`.`indent` s ',
                    'INNER JOIN tmp_discharge_ipds t ON t.`IpNo` = s.`ipdno`'
                );
            PREPARE stmt FROM @sql;
            EXECUTE stmt;
            SET v_indent_synced = ROW_COUNT();
            DEALLOCATE PREPARE stmt;
            END IF;
        END IF;

        SELECT
            v_discharge_count AS discharged_ipds,
            v_inpatient_synced AS inpatientdetails_synced,
            v_bed_updated AS bed_details_updated,
            v_treatment_synced AS ipdtreatment_synced,
            v_indent_synced AS indent_synced,
            v_treated_updated AS treated_status_updated;

        DROP TEMPORARY TABLE IF EXISTS tmp_discharge_ipds;
    END IF;
END$$
DELIMITER ;
