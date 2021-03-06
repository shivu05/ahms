-- Database Changes file
--
-- Dumping data for table perm_master
--

INSERT INTO perm_master (perm_id, perm_code, perm_desc, perm_order, perm_label, perm_parent, perm_class, perm_url, perm_status, perm_attr, perm_icon, last_updated_id, last_updated_date) VALUES
(11, 'IPD_REPORT', 'IPD Report', 2, 2, '8', '', 'reports/Ipd', 'Active', '', 'fa fa-book', 1, '2018-09-25 20:06:45'),
(12, 'BED_OCC_REPORT', 'Bed occupied Report', 3, 3, '8', '', 'reports/Ipd/bed_occupied_report', 'Active', '', 'fa fa-book', 1, '2018-09-26 13:18:03'),
(13, 'BED_OCC_CHART', 'Bed occupancy chart', 4, 4, '8', '', 'reports/Ipd/bed_occupancy_chart', 'Active', '', 'fa fa-book', 1, '2018-09-26 19:07:28');

INSERT INTO role_perm (role_perm_id, role_id, perm_id, status, last_updated_id, last_updated_date, access_perm) VALUES
(11, 1, 11, 'Active', 1, '2018-09-25 20:07:16', 1),
(12, 1, 12, 'Active', 1, '2018-09-26 13:18:28', 1),
(13, 1, 12, 'Active', 1, '2018-09-26 13:18:28', 1);

INSERT INTO perm_master (perm_code, perm_desc, perm_order, perm_label, perm_parent, perm_url, perm_status, perm_icon, last_updated_id) VALUES 
('TEST_REPORTS', 'Test reports', '6', '1', '0', '#', 'Active', 'fa fa-book', '1');

INSERT INTO role_perm (role_id, perm_id, status, last_updated_id) VALUES ('1', '14', 'Active', '1');

INSERT INTO perm_master (perm_code, perm_desc, perm_order, perm_label, perm_parent, perm_url, perm_status, perm_icon, last_updated_id)
 VALUES ('XRAY', 'X-Ray', '1', '1', '14', 'x-ray', 'Active', 'fa fa-book', '1');
INSERT INTO role_perm (role_id, perm_id, status, last_updated_id) VALUES ('1', '15', 'Active', '1');

INSERT INTO perm_master (perm_code, perm_desc, perm_order, perm_label, perm_parent, perm_url, perm_status, perm_icon, last_updated_id) 
VALUES ('MONTHLY_IPD_REPORT', 'Monthly IPD report', '5', '5', '8', 'monthwise-ipd-report', 'Active', 'fa fa-book', '1');

INSERT INTO role_perm (role_id, perm_id, status, last_updated_id) VALUES ('1', '16', 'Active', '1');

INSERT INTO perm_master (perm_code, perm_desc, perm_order, perm_label, perm_parent, perm_url, perm_status, perm_icon, last_updated_id) 
VALUES ('MONTHLY_OPD_IPD_REPORT', 'Month wise OPD IPD report', '6', '6', '8', 'monthwise-opd-ipd-report', 'Active', 'fa fa-book', '1');
INSERT INTO role_perm (role_id, perm_id, status, last_updated_id) VALUES ('1', '17', 'Active', '1');

INSERT INTO perm_master (perm_code, perm_desc, perm_order, perm_label, perm_parent, perm_url, perm_status, perm_icon, last_updated_id)
 VALUES ('ECG', 'ECG', '2', '2', '14', 'ecg', 'Active', 'fa fa-book', '1');
INSERT INTO role_perm (role_id, perm_id, status, last_updated_id) VALUES ('1', '18', 'Active', '1');

INSERT INTO perm_master (perm_code, perm_desc, perm_order, perm_label, perm_parent, perm_url, perm_status, perm_icon, last_updated_id)
 VALUES ('USG', 'USG', '3', '3', '14', 'usg', 'Active', 'fa fa-book', '1');
INSERT INTO role_perm (role_id, perm_id, status, last_updated_id) VALUES ('1', '19', 'Active', '1');

INSERT INTO perm_master (perm_code, perm_desc, perm_order, perm_label, perm_parent, perm_url, perm_status, perm_icon, last_updated_id)
 VALUES ('KSHARASUTRA', 'Ksharasutra', '4', '4', '14', 'ksharasutra', 'Active', 'fa fa-book', '1');
INSERT INTO role_perm (role_id, perm_id, STATUS, last_updated_id) VALUES ('1', '20', 'Active', '1');

INSERT INTO perm_master (perm_code, perm_desc, perm_order, perm_label, perm_parent, perm_url, perm_status, perm_icon, last_updated_id)
 VALUES ('SURGERY', 'Surgery', '5', '5', '14', 'surgery', 'Active', 'fa fa-book', '1');
INSERT INTO role_perm (role_id, perm_id, STATUS, last_updated_id) VALUES ('1', '21', 'Active', '1');

INSERT INTO perm_master (perm_code, perm_desc, perm_order, perm_label, perm_parent, perm_url, perm_status, perm_icon, last_updated_id)
 VALUES ('SURGERY_COUNT', 'Surgery count', '6', '6', '14', 'surgery-count', 'Active', 'fa fa-book', '1');
INSERT INTO role_perm (role_id, perm_id, STATUS, last_updated_id) VALUES ('1', '22', 'Active', '1');

INSERT INTO perm_master (perm_code, perm_desc, perm_order, perm_label, perm_parent, perm_url, perm_status, perm_icon, last_updated_id)
 VALUES ('PANCHAKARMA', 'Panchakarma', '7', '7', '14', 'panchakarma', 'Active', 'fa fa-book', '1');
INSERT INTO role_perm (role_id, perm_id, STATUS, last_updated_id) VALUES ('1', '23', 'Active', '1');

INSERT INTO perm_master (perm_code, perm_desc, perm_order, perm_label, perm_parent, perm_url, perm_status, perm_icon, last_updated_id)
 VALUES ('PANCHAKARMA_PROC_COUNT', 'Panchakarma count', '8', '8', '14', 'panchakarma-procedure-stats', 'Active', 'fa fa-book', '1');
INSERT INTO role_perm (role_id, perm_id, STATUS, last_updated_id) VALUES ('1', '24', 'Active', '1');

INSERT INTO perm_master (perm_code, perm_desc, perm_order, perm_label, perm_parent, perm_url, perm_status, perm_icon, last_updated_id)
 VALUES ('LAB', 'Laboratory', '9', '9', '14', 'lab', 'Active', 'fa fa-book', '1');
INSERT INTO role_perm (role_id, perm_id, STATUS, last_updated_id) VALUES ('1', '25', 'Active', '1');

INSERT INTO perm_master (perm_code, perm_desc, perm_order, perm_label, perm_parent, perm_url, perm_status, perm_icon, last_updated_id)
 VALUES ('LAB_COUNT', 'Laboratory count', '10', '10', '14', 'lab-count', 'Active', 'fa fa-book', '1');
INSERT INTO role_perm (role_id, perm_id, STATUS, last_updated_id) VALUES ('1', '26', 'Active', '1');

INSERT INTO perm_master (perm_code, perm_desc, perm_order, perm_label, perm_parent, perm_url, perm_status, perm_icon, last_updated_id)
 VALUES ('DEPARTMENT_LIST', 'Department list', '2', '2', '2', 'department-list', 'Active', 'fa fa-book', '1');
INSERT INTO role_perm (role_id, perm_id, STATUS, last_updated_id) VALUES ('1', '27', 'Active', '1');







DELIMITER $$

DROP PROCEDURE IF EXISTS get_bed_occupancy $$
CREATE  PROCEDURE get_bed_occupancy(deptname VARCHAR(50), from_time VARCHAR(50), to_time VARCHAR(50))
BEGIN
IF deptname=1 THEN
SELECT department,total,Male,Female,discharged,onbed
FROM (
SELECT department,
COUNT(IpNo) as 'total',
SUM(case when i.gender='Male' then 1 else 0 end) Male,
SUM(case when i.gender='Female' then 1 else 0 end) Female,
SUM(CASE when i.DoDischarge=to_time then 1 else 0 end) discharged,
SUM(CASE when i.status='stillin' OR i.DoDischarge > to_time then 1 else 0 end) onbed
FROM inpatientdetails i
WHERE
(i.DoAdmission <= from_time
 AND i.DoDischarge >= to_time)
OR (i.DoAdmission <= from_time AND i.status='stillin')
GROUP by i.department
UNION ALL
select department,0 total,0 Male,0 Female,0 discharged,0 onbed from deptper
) A GROUP by department;

ELSE

SELECT department,total,Male,Female,discharged,onbed
FROM (
SELECT i.department,
COUNT(i.IpNo) as 'total',
SUM(case when i.gender='Male' then 1 else 0 end) Male,
SUM(case when i.gender='Female' then 1 else 0 end) Female,
SUM(CASE when i.DoDischarge=to_time then 1 else 0 end) discharged,
SUM(CASE when i.status='stillin' OR i.DoDischarge > to_time then 1 else 0 end) onbed
FROM inpatientdetails i
WHERE
i.department = deptname AND
 ((i.DoAdmission <= from_time
 AND i.DoDischarge >= to_time)
OR (i.DoAdmission <= from_time AND  i.status='stillin'))

UNION ALL
select department,0 total,0 Male,0 Female,0 discharged,0 onbed from deptper
) A GROUP by department;

END IF;
END $$

DELIMITER ;

DELIMITER $$

DROP PROCEDURE IF EXISTS `get_patient_count` $$
CREATE PROCEDURE `get_patient_count`()
BEGIN

SELECT department,OLD,NEW,Total,Male,Female from(
SELECT p.dept department,
SUM(case when t.PatType='O' then 1 else 0 end) OLD,
SUM(case when t.PatType='N' then 1 else 0 end) NEW,
Count(*) Total,
SUM(case when p.gender='Male' then 1 else 0 end) Male,
SUM(case when p.gender='Female' then 1 else 0 end) Female
from patientdata p,treatmentdata t,deptper d WHERE t.OpdNo = p.OpdNo and p.dept=d.department
group by p.dept
UNION ALL
select department,0 OLD,0 NEW,0 Total,0 Male,0 Female from deptper
) A group by department;

END $$

DELIMITER ;

-- new menu
INSERT INTO perm_master (perm_id, perm_code, perm_desc, perm_order, perm_label, perm_parent, perm_class, perm_url, perm_status, perm_attr, perm_icon, last_updated_id, last_updated_date) VALUES 
(NULL, 'BED_OCC_CHART', 'Bed occupancy chart', '4', '4', '8', '', 'reports/Ipd/bed_occupancy_chart', 'Active', '', 'fa fa-book', '1', CURRENT_TIMESTAMP);
INSERT INTO role_perm (role_perm_id, role_id, perm_id, status, last_updated_id, last_updated_date, access_perm) VALUES (NULL, '1', '13', 'Active', '1', CURRENT_TIMESTAMP, '1');