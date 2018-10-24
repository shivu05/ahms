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

-- new menu
INSERT INTO perm_master (perm_id, perm_code, perm_desc, perm_order, perm_label, perm_parent, perm_class, perm_url, perm_status, perm_attr, perm_icon, last_updated_id, last_updated_date) VALUES 
(NULL, 'BED_OCC_CHART', 'Bed occupancy chart', '4', '4', '8', '', 'reports/Ipd/bed_occupancy_chart', 'Active', '', 'fa fa-book', '1', CURRENT_TIMESTAMP);
INSERT INTO role_perm (role_perm_id, role_id, perm_id, status, last_updated_id, last_updated_date, access_perm) VALUES (NULL, '1', '13', 'Active', '1', CURRENT_TIMESTAMP, '1');