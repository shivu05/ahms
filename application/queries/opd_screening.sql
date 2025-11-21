-- SELECT * FROM vhms_pramch_2025.inpatientdetails;

INSERT INTO `role_master` (`role_name`, `role_code`, `last_updated_by`) VALUES ('OPDSCR', 'OPDSCR', '1');
INSERT INTO `role_perm` (`role_id`, `perm_id`, `status`, `last_updated_id`) VALUES ('7', '1', 'Active', '1');
INSERT INTO `role_perm` (`role_id`, `perm_id`, `status`) VALUES ('7', '4', 'Active');
INSERT INTO `role_perm` (`role_id`, `perm_id`, `status`, `last_updated_id`) VALUES ('7', '5', 'Active', '1');
INSERT INTO `role_perm` (`role_id`, `perm_id`, `status`, `last_updated_id`) VALUES ('7', '29', 'Active', '1');
UPDATE `perm_master` SET `perm_url` = 'opd-registration' WHERE (`perm_id` = '29');


INSERT INTO `role_perm` (`role_perm_id`, `role_id`, `perm_id`, `status`, `last_updated_id`, `last_updated_date`, `access_perm`) VALUES (NULL, '4', '66', 'Active', '1', CURRENT_TIMESTAMP, '1');
INSERT INTO `role_perm` (`role_perm_id`, `role_id`, `perm_id`, `status`, `last_updated_id`, `last_updated_date`, `access_perm`) VALUES (NULL, '7', '66', 'Active', '1', CURRENT_TIMESTAMP, '1');


INSERT INTO `perm_master` (`perm_code`, `perm_desc`, `perm_order`, `perm_label`, `perm_parent`, `perm_class`, `perm_url`, `perm_status`, `perm_attr`, `perm_icon`, `last_updated_id`, `last_updated_date`) 
VALUES ('OPD_SCREENING_LIST', 'Screening list', '4', '4', '4', '', 'patient/opd_screening_list', 'Active', '', '', '1', CURRENT_TIMESTAMP);
INSERT INTO `role_perm` (`role_perm_id`, `role_id`, `perm_id`, `status`, `last_updated_id`, `last_updated_date`, `access_perm`) VALUES (NULL, '1', '66', 'Active', '1', CURRENT_TIMESTAMP, '1');



CREATE TABLE patient_vitals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    opd_no VARCHAR(20) NOT NULL,
    date DATE NOT NULL,
    blood_pressure VARCHAR(20), -- e.g., '120/80'
    pulse_rate INT,             -- in beats per minute
    respiratory_rate INT,       -- in breaths per minute
    body_temperature DECIMAL(5,2), -- in Celsius or Fahrenheit, based on system
    spo2 INT,                   -- Oxygen Saturation in %
    weight DECIMAL(5,2),        -- in kg (or lbs, based on system)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);