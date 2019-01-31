/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  Shivaraj B
 * Created: 31 Jan, 2019
 */

DELETE FROM xrayregistery WHERE xrayDate='2019-02-04';
ALTER TABLE xrayregistery AUTO_INCREMENT = 1;
DELETE FROM usgregistery where usgDate='2019-02-04';
ALTER TABLE usgregistery AUTO_INCREMENT = 1;
DELETE FROM ecgregistery where ecgDate='2019-02-04';
ALTER TABLE ecgregistery AUTO_INCREMENT = 1;
DELETE FROM labregistery where testDate='2019-02-04';
ALTER TABLE labregistery AUTO_INCREMENT = 1;
DELETE FROM panchaprocedure where date='2019-02-04';
ALTER TABLE panchaprocedure AUTO_INCREMENT = 1;
DELETE FROM sales_entry where date='2019-02-04';
ALTER TABLE sales_entry AUTO_INCREMENT = 1;
DELETE FROM treatmentdata where CameOn='2019-02-04';
ALTER TABLE treatmentdata AUTO_INCREMENT = 1;
DELETE FROM patientdata where entrydate='2019-02-04';
ALTER TABLE patientdata AUTO_INCREMENT = 1;
