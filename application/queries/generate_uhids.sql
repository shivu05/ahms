DELIMITER $$

DROP PROCEDURE IF EXISTS generate_existing_uhids $$
CREATE PROCEDURE generate_existing_uhids()
BEGIN
    DECLARE v_college_code VARCHAR(50);
    DECLARE v_opd_no INT;
    DECLARE v_entry_date DATE;
    DECLARE v_short_date VARCHAR(6);
    DECLARE v_daily_seq INT;
    DECLARE v_uhid VARCHAR(50);
    DECLARE v_sequence_id INT;
    DECLARE done INT DEFAULT FALSE;
    
    -- Cursor for patients without UHID
    DECLARE patient_cursor CURSOR FOR 
        SELECT p.OpdNo, p.entrydate 
        FROM patientdata p
        WHERE p.UHID IS NULL
        ORDER BY p.entrydate, p.OpdNo;
    
    -- Handler for cursor
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    
    -- Get hospital/college code
    SELECT COALESCE(college_id, 'UNKNOWN') INTO v_college_code
    FROM config LIMIT 1;
    
    -- Open cursor
    OPEN patient_cursor;
    
    read_loop: LOOP
        FETCH patient_cursor INTO v_opd_no, v_entry_date;
        
        IF done THEN
            LEAVE read_loop;
        END IF;
        
        -- Skip if date is invalid
        IF v_entry_date IS NOT NULL THEN
            -- Generate short date (YYMMDD)
            SET v_short_date = DATE_FORMAT(v_entry_date, '%y%m%d');
            
            -- Get or insert daily sequence
            SELECT id, daily_seq 
            INTO v_sequence_id, v_daily_seq
            FROM uhid_sequence 
            WHERE seq_date = v_entry_date 
            AND hospital_code = v_college_code
            LIMIT 1;
            
            IF v_sequence_id IS NULL THEN
                -- Insert new sequence
                INSERT INTO uhid_sequence (seq_date, hospital_code, daily_seq)
                VALUES (v_entry_date, v_college_code, 1);
                
                SET v_daily_seq = 1;
            ELSE
                -- Update existing sequence
                UPDATE uhid_sequence 
                SET daily_seq = daily_seq + 1
                WHERE id = v_sequence_id;
                
                SET v_daily_seq = v_daily_seq + 1;
            END IF;
            
            -- Generate UHID
            SET v_uhid = CONCAT('VH-', 
                              v_college_code, '-',
                              v_short_date, '-',
                              LPAD(v_daily_seq, 4, '0'));
            
            -- Update patient record
            UPDATE patientdata 
            SET UHID = v_uhid
            WHERE OpdNo = v_opd_no;
        END IF;
    END LOOP;
    
    -- Close cursor
    CLOSE patient_cursor;
    
    -- Return success message
    SELECT 'UHID generation completed successfully' as message;
END$$

DELIMITER ;
