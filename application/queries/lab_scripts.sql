DROP TABLE IF EXISTS `lab_categories`;
CREATE TABLE `lab_categories` (
  `lab_cat_id` INTEGER NOT NULL AUTO_INCREMENT,
  `lab_cat_name` VARCHAR(250) NOT NULL,
  `status` VARCHAR(45) NOT NULL DEFAULT 'ACTIVE',
  PRIMARY KEY (`lab_cat_id`)
)
ENGINE = InnoDB;

DROP TABLE IF EXISTS `lab_tests`;
CREATE TABLE `lab_tests` (
  `lab_test_id` INTEGER NOT NULL AUTO_INCREMENT,
  `lab_test_name` VARCHAR(250) NOT NULL,
  `lab_cat_id` INTEGER,
  `status` VARCHAR(45) NOT NULL DEFAULT 'ACTIVE',
  PRIMARY KEY (`lab_test_id`),
  CONSTRAINT `FK_lab_tests_1` FOREIGN KEY `FK_lab_tests_1` (`lab_cat_id`)
    REFERENCES `lab_categories` (`lab_cat_id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT
)
ENGINE = InnoDB;

DROP TABLE IF EXISTS `lab_investigations`;
CREATE TABLE `lab_investigations` (
  `lab_inv_id` INTEGER NOT NULL AUTO_INCREMENT,
  `lab_inv_name` VARCHAR(250) NOT NULL,
  `lab_test_id` INTEGER NOT NULL,
  PRIMARY KEY (`lab_inv_id`),
  CONSTRAINT `FK_lab_investigations_1` FOREIGN KEY `FK_lab_investigations_1` (`lab_test_id`)
    REFERENCES `lab_tests` (`lab_test_id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT
)
ENGINE = InnoDB;

--
-- Dumping data for table `lab_categories`
--

INSERT INTO `lab_categories` (`lab_cat_id`, `lab_cat_name`, `status`) VALUES
(1, 'Haematology', 'ACTIVE'),
(2, 'Biochemistry', 'ACTIVE'),
(3, 'Serology', 'ACTIVE'),
(4, 'Microbiology', 'ACTIVE'),
(5, 'Others', 'ACTIVE');

--
-- Dumping data for table `lab_tests`
--

INSERT INTO `lab_tests` (`lab_test_id`, `lab_test_name`, `lab_cat_id`, `status`) VALUES
(1, 'CBC', 1, 'ACTIVE'),
(2, 'HB%', 1, 'ACTIVE'),
(3, 'TC', 1, 'ACTIVE'),
(4, 'DC', 1, 'ACTIVE'),
(5, 'ESR', 1, 'ACTIVE'),
(6, 'PLATELETS', 1, 'ACTIVE'),
(7, 'RBC COUNT', 1, 'ACTIVE'),
(8, 'HAEMATOCRIT(HCT)', 1, 'ACTIVE'),
(9, 'MCV', 1, 'ACTIVE'),
(10, 'MCH', 1, 'ACTIVE'),
(11, 'MCHC', 1, 'ACTIVE'),
(12, 'RDV-CV', 1, 'ACTIVE'),
(13, 'BLOOD GROUP & Rh Type', 1, 'ACTIVE'),
(14, 'PCV', 1, 'ACTIVE'),
(15, 'AEC', 1, 'ACTIVE'),
(16, 'CT/BT/PT', 1, 'ACTIVE'),
(17, 'Smear for MP', 1, 'ACTIVE'),
(18, 'Blood Urea', 2, 'ACTIVE'),
(19, 'Serum Creatinine', 2, 'ACTIVE'),
(20, 'Serum Cholesterol', 2, 'ACTIVE'),
(21, 'Serum Bilirubin', 2, 'ACTIVE'),
(22, 'Serum Alkaline Phosphatase', 2, 'ACTIVE'),
(23, 'Serum Uric acid', 2, 'ACTIVE'),
(24, 'S.G.O.T', 2, 'ACTIVE'),
(25, 'S.G.P.T', 2, 'ACTIVE'),
(26, 'Uric Acid Test', 2, 'ACTIVE'),
(27, 'Serum Calcium', 2, 'ACTIVE'),
(28, 'Serum Phosphorus', 2, 'ACTIVE'),
(29, 'Serum Bilirubin', 2, 'ACTIVE'),
(30, 'Blood Glucose', 2, 'ACTIVE'),
(31, 'Lipid Profile', 2, 'ACTIVE'),
(32, 'Serum LFT', 2, 'ACTIVE'),
(33, 'Oral Glucose Tolerance test (OGTT)', 2, 'ACTIVE'),
(34, 'Aspartate amino transferase', 2, 'ACTIVE'),
(35, 'Electrolytes', 2, 'ACTIVE'),
(36, 'Widal test', 3, 'ACTIVE'),
(37, 'RA test', 3, 'ACTIVE'),
(38, 'ASLO', 3, 'ACTIVE'),
(39, 'CRP', 3, 'ACTIVE'),
(40, 'HBS/AG test', 3, 'ACTIVE'),
(41, 'HIV test', 3, 'ACTIVE'),
(42, 'V.D.R.L', 3, 'ACTIVE'),
(43, 'HIV', 3, 'ACTIVE'),
(44, 'CMV', 3, 'ACTIVE'),
(45, 'Tirch test', 3, 'ACTIVE'),
(46, 'MALARIA RAPID CARD', 3, 'ACTIVE'),
(47, 'Dengue Ig G & Ig M', 3, 'ACTIVE'),
(48, 'Brucella Test', 3, 'ACTIVE'),
(49, 'Chikun Gunya Ig G & Ig M', 3, 'ACTIVE'),
(50, 'TB Rapid Card Test', 3, 'ACTIVE'),
(51, 'BLOOD CULTURE SENSITYVITY', 4, 'ACTIVE'),
(52, 'BACTERIAL CULTURE', 4, 'ACTIVE'),
(53, 'STOOL CULTUR', 4, 'ACTIVE'),
(54, 'URINE CULTURE', 4, 'ACTIVE'),
(55, 'SEMEN ANALYSIS', 5, 'ACTIVE'),
(56, 'URINE ANALYSIS', 5, 'ACTIVE'),
(57, 'STOOL CULTUR', 5, 'ACTIVE'),
(58, 'THYROID FUNCTION TEST', 5, 'ACTIVE'),
(59, 'CSF ANALYSIS', 5, 'ACTIVE'),
(60, 'SPUTUM FOR AFB', 5, 'ACTIVE'),
(61, 'PREGNANCY TEST', 5, 'ACTIVE'),
(62, 'ENDOCRINE TESTS', 5, 'ACTIVE');


--
-- Dumping data for table `lab_investigations`
--

INSERT INTO `lab_investigations` (`lab_inv_id`, `lab_inv_name`, `lab_test_id`) VALUES
(1, 'HB%', 1),
(2, 'WBC', 1),
(3, 'RBC', 1),
(4, 'DIFFERENTIAL COUNT', 1),
(5, 'PLATELETS', 1),
(6, 'PERIPH. SMEAR STUDY', 1),
(7, 'HB%', 2),
(8, 'TC', 3),
(9, 'NEUTROPHILS', 4),
(10, 'LYMPHOCYTES', 4),
(11, 'EOSINOPHILS', 4),
(12, 'MONOCYTES', 4),
(13, 'BASOPHOLS', 4),
(14, 'ESR', 5),
(15, 'PLATELETS', 6),
(16, 'MPV', 6),
(17, 'RBC COUNT', 7),
(18, 'HAEMATOCRIT(HCT)', 8),
(19, 'MCV', 9),
(20, 'MCH', 10),
(21, 'MCHC', 11),
(22, 'RDV-CV', 12),
(23, 'BLOOD GROUP & Rh Type', 13),
(24, 'PCV', 14),
(25, 'AEC', 15),
(26, 'CT/BT/PT', 16),
(27, 'Smear for MP', 17),
(28, 'Blood Urea', 18),
(29, 'Serum Creatinine', 19),
(30, 'Serum Cholesterol', 20),
(31, 'Serum Bilirubin', 21),
(32, 'Serum Alkaline Phosphatase', 22),
(33, 'Serum Uric acid', 23),
(34, 'S.G.O.T', 24),
(35, 'S.G.P.T', 25),
(36, 'Uric Acid Test', 26),
(37, 'Serum Calcium', 27),
(38, 'Serum Phosphorus', 28),
(39, 'Total', 29),
(40, 'Direct', 29),
(41, 'RBS', 30),
(42, 'FBS', 30),
(43, 'PPBS', 30),
(44, 'Total Cholesterol', 31),
(45, 'Triacylglycerol', 31),
(46, 'HDL cholesterol', 31),
(47, 'LDL cholesterol', 31),
(48, 'VLDL cholesterol', 31),
(49, 'Serum LFT', 32),
(50, 'Oral Glucose Tolerance test (OGTT)', 33),
(51, 'Aspartate amino transferase', 34),
(52, 'Sodium', 35),
(53, 'Potassium', 35),
(54, 'Widal test', 36),
(55, 'RA test', 37),
(56, 'ASLO', 38),
(57, 'CRP', 39),
(58, 'HBS/AG Test', 40),
(59, 'HIV test', 41),
(60, 'V.D.R.L', 42),
(61, 'HIV', 43),
(62, 'CMV', 44),
(63, 'Tirch test', 45),
(64, 'MALARIA RAPID CARD', 46),
(65, 'Dengue Ig G & Ig M', 47),
(66, 'Brucella Test', 48),
(67, 'Chikun Gunya Ig G & Ig M', 49),
(68, 'TB Rapid Card Test', 50),
(69, 'BLOOD CULTURE SENSITYVITY', 51),
(70, 'BLOOD CULTURE SENSITYVITY', 52),
(71, 'STOOL CULTUR', 53),
(72, 'URINE CULTURE', 54),
(73, 'PHYSICAL EXAMINATION', 55),
(74, 'MICROSCOPIC EXAMINATION', 55),
(75, 'PHYSICAL EXAMINATION', 56),
(76, 'CHEMICAL EXAMINATION', 56),
(77, 'MICROSCOPIC EXAMINATION', 56),
(78, 'PHYSICAL EXAMINATION', 57),
(79, 'MICROSCOPIC EXAMINATION', 57),
(80, 'T3', 58),
(81, 'T4', 58),
(82, 'TSH', 58),
(83, 'CSF ANALYSIS', 59),
(84, 'SPUTUM FOR AFB', 60),
(85, 'PREGNANCY TEST', 61),
(86, 'SERU LH', 62),
(87, 'SERUM FSH', 62),
(88, 'SERUM PROLACTIN', 62),
(89, 'SERUM TESTOSTERON', 62),
(90, 'SERUM ADH', 62);

ALTER TABLE `labregistery` ADD `lab_test_cat` VARCHAR(250) NULL AFTER `refDocName`, ADD `lab_test_type` VARCHAR(250) NULL AFTER `lab_test_cat`;
-- --------------------------------------------------------