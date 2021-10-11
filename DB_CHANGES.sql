CREATE TABLE `master_physiotheraphy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


CREATE TABLE `physiotherapy_treatments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `OpdNo` int(11) DEFAULT NULL,
  `IpNo` int(11) DEFAULT NULL,
  `therapy_name` varchar(250) DEFAULT NULL,
  `physician` varchar(100) DEFAULT NULL,
  `referred_date` varchar(25) DEFAULT NULL,
  `treat_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

ALTER TABLE `physiotherapy_treatments` 
ADD COLUMN `end_date` DATE NULL AFTER `start_date`,
CHANGE COLUMN `treat_id` `treat_id` INT(11) NULL DEFAULT NULL AFTER `physician`,
CHANGE COLUMN `referred_date` `start_date` DATE NULL DEFAULT NULL ;