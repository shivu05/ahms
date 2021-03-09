CREATE PROCEDURE `get_opd_patients_count`(`flag` VARCHAR(70), `from_date` VARCHAR(50), `to_date` VARCHAR(50))
BEGIN

SELECT department,OLD,NEW,Total,Male,Female,NRV,KNMDV from(
SELECT t.department,
SUM(case when t.PatType='Old Patient' then 1 else 0 end) OLD,
SUM(case when t.PatType='New Patient' then 1 else 0 end) NEW,
Count(*) Total,
SUM(case when p.gender='Male' then 1 else 0 end) Male,
SUM(case when p.gender='Female' then 1 else 0 end) Female,
SUM(case when p.sub_dept='Netra-Roga Vibhaga' then 1 else 0 end) NRV,
SUM(case when p.sub_dept='karna-Nasa-Mukha & Danta Vibhaga' then 1 else 0 end) KNMDV
from patientdata p
JOIN treatmentdata t ON t.OpdNo = p.OpdNo
JOIN deptper d  ON t.department=d.department WHERE CASE WHEN flag <> 1 THEN t.department=flag ELSE t.department like '%%'END
AND t.CameOn >=  from_date
AND t.CameOn <= to_date
group by p.dept
UNION ALL
select department,0 OLD,0 NEW,0 Total,0 Male,0 Female,0 NRV,0 KNMDV from deptper
) A group by department;

END