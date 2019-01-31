/**
 * Author:  hp
 * Created: 17 Jan, 2019
 */

DELIMITER $$

DROP FUNCTION IF EXISTS `get_department_name` $$
CREATE FUNCTION `get_department_name` (dept VARCHAR(50)) RETURNS VARCHAR(50)
DETERMINISTIC
BEGIN
DECLARE new_dept varchar(50);

SET new_dept = (SELECT department FROM deptper WHERE LOWER(dept_unique_code)=LOWER(dept) LIMIT 1);

RETURN new_dept;

END $$

DELIMITER ;

DELIMITER $$

DROP FUNCTION IF EXISTS `display_date` $$
CREATE FUNCTION `display_date` (str_date VARCHAR(50)) RETURNS VARCHAR(50)
DETERMINISTIC
BEGIN
DECLARE new_date varchar(50);

SET new_date = (SELECT DATE_FORMAT(str_date,'%d-%m-%Y') as ndate);

RETURN new_date;

END $$

DELIMITER ;