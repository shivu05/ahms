DELIMITER $$
DROP PROCEDURE IF EXISTS `get_ipd_patient_count_dept_wise` $$
CREATE  PROCEDURE `get_ipd_patient_count_dept_wise` (`deptname` VARCHAR(50))  BEGIN

SELECT SUM(id) id,month,SUM(total) total,SUM(IPD) IPD,SUM(Male) Male,SUM(Female) Female FROM (
SELECT 0 id,DATE_FORMAT(`DoAdmission`, '%M') as 'month',
COUNT(IpNo) as 'total',
SUM(case when i.IpNo=(SELECT ip.ipdno FROM ipdtreatment ip where ip.ipdno=i.IpNo LIMIT 1) then 1 else 0 end) IPD,
SUM(case when i.gender='Male' then 1 else 0 end) Male,
SUM(case when i.gender='Female' then 1 else 0 end) Female
FROM inpatientdetails i  WHERE i.department=deptname
GROUP BY DATE_FORMAT( `DoAdmission`, '%Y%m')
UNION ALL 
SELECT id,name month,0 total,0 IPD, 0 Male, 0 Female
FROM months_list
) A GROUP BY month order by id;

END$$

DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `get_monthly_opd_ipd_dept_wise`(`deptname` VARCHAR(50))
BEGIN

SELECT SUM(id) id,month,SUM(NEW) NEW,SUM(OLD) OLD,SUM(total) total,SUM(Male) Male,SUM(Female) Female
FROM (
	SELECT 0 id,DATE_FORMAT(it.`cameOn`, '%M') as 'month',
	SUM(case when it.PatType='New Patient' then 1 else 0 end) NEW,
	SUM(case when it.PatType='Old Patient' then 1 else 0 end) OLD,
	COUNT(i.OpdNo) as 'total',
	SUM(case when i.gender='Male' then 1 else 0 end) Male,
	SUM(case when i.gender='Female' then 1 else 0 end) Female
	FROM patientdata i JOIN treatmentdata it ON i.OpdNo=it.OpdNo
	WHERE i.dept=deptname
	GROUP BY DATE_FORMAT(it.`cameOn`, '%Y%m')
	UNION ALL 
	SELECT id,name month,0 NEW,0 OLD, 0 total,0 Male, 0 Female
	FROM months_list order by id
) A GROUP BY month order by id asc;

END$$
DELIMITER ;
