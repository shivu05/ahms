DELIMITER $$
DROP PROCEDURE IF EXISTS `get_patient_count` $$
CREATE PROCEDURE `get_patient_count`()
BEGIN

SELECT department,OLD,NEW,Total,Male,Female from(
SELECT t.department,
SUM(case when t.PatType='Old Patient' then 1 else 0 end) OLD,
SUM(case when t.PatType='New Patient' then 1 else 0 end) NEW,
Count(*) Total,
SUM(case when p.gender='Male' then 1 else 0 end) Male,
SUM(case when p.gender='Female' then 1 else 0 end) Female
from patientdata p,treatmentdata t,deptper d WHERE t.OpdNo = p.OpdNo and t.department=d.dept_unique_code
group by t.department
UNION ALL
select dept_unique_code department,0 OLD,0 NEW,0 Total,0 Male,0 Female from deptper
) A group by department;

END$$
DELIMITER ;
