/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  Shiv
 * Created: 30 Dec, 2019
 */

DELIMITER $$
CREATE DEFINER=`root`@`localhost` FUNCTION `display_date`(str_date VARCHAR(50)) RETURNS varchar(50) CHARSET latin1
    DETERMINISTIC
BEGIN
DECLARE new_date varchar(50);

SET new_date = (SELECT DATE_FORMAT(str_date,'%d-%m-%Y') as ndate);

RETURN new_date;

END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_opd_patients_count`(`flag` VARCHAR(70), `from_date` VARCHAR(50), `to_date` VARCHAR(50))
BEGIN

SELECT department,OLD,NEW,Total,Male,Female,NRV,KNMDV from(
SELECT p.dept department,
SUM(case when t.PatType='Old Patient' then 1 else 0 end) OLD,
SUM(case when t.PatType='New Patient' then 1 else 0 end) NEW,
Count(*) Total,
SUM(case when p.gender='Male' then 1 else 0 end) Male,
SUM(case when p.gender='Female' then 1 else 0 end) Female,
SUM(case when p.sub_dept='Netra-Roga Vibhaga' then 1 else 0 end) NRV,
SUM(case when p.sub_dept='karna-Nasa-Mukha & Danta Vibhaga' then 1 else 0 end) KNMDV
from patientdata p
JOIN treatmentdata t ON t.OpdNo = p.OpdNo
JOIN deptper d  ON p.dept=d.department WHERE CASE WHEN flag <> 1 THEN p.dept=flag ELSE p.dept like '%%'END
AND t.CameOn >=  from_date
AND t.CameOn <= to_date
group by p.dept
UNION ALL
select department,0 OLD,0 NEW,0 Total,0 Male,0 Female,0 NRV,0 KNMDV from deptper
) A group by department;

END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_monthly_opd_ipd_dept_wise`(`deptname` VARCHAR(50))
BEGIN

SELECT DATE_FORMAT(it.`cameOn`, '%M') as 'month',
SUM(case when it.PatType='New Patient' then 1 else 0 end) NEW,
SUM(case when it.PatType='Old Patient' then 1 else 0 end) OLD,
COUNT(i.OpdNo) as 'total',
SUM(case when i.gender='Male' then 1 else 0 end) Male,
SUM(case when i.gender='Female' then 1 else 0 end) Female
FROM patientdata i JOIN treatmentdata it ON i.OpdNo=it.OpdNo
WHERE i.dept=deptname
GROUP BY DATE_FORMAT(it.`cameOn`, '%Y%m');

END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_bed_occupancy`(`deptname` VARCHAR(50), `from_time` VARCHAR(50), `to_time` VARCHAR(50))
BEGIN
IF deptname=1 THEN
SELECT department,total,Male,Female,discharged,onbed
FROM (
SELECT department,
COUNT(IpNo) as 'total',
SUM(case when i.gender='Male' then 1 else 0 end) Male,
SUM(case when i.gender='Female' then 1 else 0 end) Female,
SUM(CASE when i.DoDischarge = to_time then 1 else 0 end) discharged,
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
SUM(CASE when i.DoDischarge = to_time then 1 else 0 end) discharged,
SUM(CASE when i.status='stillin' OR i.DoDischarge >= to_time then 1 else 0 end) onbed
FROM inpatientdetails i
WHERE
t.department = deptname AND
 ((i.DoAdmission <= from_time
 AND i.DoDischarge >= to_time)
OR (i.DoAdmission <= from_time AND  i.status='stillin'))

UNION ALL
select department,0 total,0 Male,0 Female,0 discharged,0 onbed from deptper
) A GROUP by department;

END IF;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_ipd_patient_count_dept_wise`(`deptname` VARCHAR(50))
BEGIN

SELECT DATE_FORMAT(`DoAdmission`, '%M') as 'month',
COUNT(IpNo) as 'total',
SUM(case when i.IpNo=(SELECT ip.ipdno FROM ipdtreatment ip where ip.ipdno=i.IpNo LIMIT 1) then 1 else 0 end) IPD,
SUM(case when i.gender='Male' then 1 else 0 end) Male,
SUM(case when i.gender='Female' then 1 else 0 end) Female
FROM inpatientdetails i  WHERE i.department=deptname
GROUP BY DATE_FORMAT( `DoAdmission`, '%Y%m');

END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_patient_count`()
BEGIN


SELECT department,OLD,NEW,Total,Male,Female from(
SELECT p.dept department,
SUM(case when t.PatType='Old Patient' then 1 else 0 end) OLD,
SUM(case when t.PatType='New Patient' then 1 else 0 end) NEW,
Count(*) Total,
SUM(case when p.gender='Male' then 1 else 0 end) Male,
SUM(case when p.gender='Female' then 1 else 0 end) Female
from patientdata p,treatmentdata t,deptper d WHERE t.OpdNo = p.OpdNo and p.dept=d.department
group by p.dept
UNION ALL
select department,0 OLD,0 NEW,0 Total,0 Male,0 Female from deptper
) A group by department;

END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `register`(IN `d_count` INT, IN `flag` INT)
BEGIN
IF flag=1 THEN
select md5(concat(
        substring(uid, 25,2)
, ':',  substring(uid, 27,2)
        , ':',  substring(uid, 29,2)
        , ':',  substring(uid, 31,2)
        , ':',  substring(uid, 33,2)
        , ':',  substring(uid, 35,2)
        )) INTO @mac
from (select uuid() uid) AS alias;

INSERT INTO samltp (`satd`,`sama`) VALUES(DATE_ADD(NOW(), INTERVAL d_count MONTH),@mac) ON DUPLICATE KEY UPDATE
satd=DATE_ADD(NOW(), INTERVAL d_count MONTH),sama=@mac;

ELSE

select md5(concat(
        substring(uid, 25,2)
        , ':',  substring(uid, 27,2)
        , ':',  substring(uid, 29,2)
        , ':',  substring(uid, 31,2)
        , ':',  substring(uid, 33,2)
        , ':',  substring(uid, 35,2)
        )) INTO @mac
from (select uuid() uid) AS alias;

SELECT count(*) as total FROM samltp WHERE sama=@mac AND DATE_FORMAT(satd,'%Y-%m-%d') >= DATE_FORMAT(NOW(),'%Y-%m-%d') LIMIT 1;

END IF;
END$$
DELIMITER ;
