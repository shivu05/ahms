--
-- Database: `ahms_samcy_2019`
--

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
CREATE TABLE  `config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `college_name` text,
  `place` varchar(70) NOT NULL,
  `printing_style` varchar(1) NOT NULL,
  `logo` varchar(50) DEFAULT NULL,
  `edit_flag` int(1) NOT NULL DEFAULT '0',
  `admin_email` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`id`, `college_name`, `place`, `printing_style`, `logo`, `edit_flag`, `admin_email`) VALUES
(1, 'SHARADA AYURVEDIC MEDICAL COLLEGE AND HOSPITAL YADGIR - 585202', 'Yadgir', 'L', 'your_logo.png', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `doctorsduty`
--

DROP TABLE IF EXISTS `doctorsduty`;
CREATE TABLE `doctorsduty` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `doc_id` int(10) unsigned DEFAULT NULL,
  `day` varchar(45) DEFAULT NULL,
  `added_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `doctorsduty`
--

INSERT INTO `doctorsduty` (`id`, `doc_id`, `day`, `added_date`) VALUES
(1, 6, '1', '2019-01-24 05:48:47'),
(2, 5, '2', '2019-01-24 05:49:02'),
(3, 6, '3', '2019-01-24 05:49:14'),
(4, 5, '4', '2019-01-24 05:49:28'),
(5, 6, '5', '2019-01-24 05:49:41'),
(6, 5, '6', '2019-01-24 05:49:55'),
(7, 6, '7', '2019-01-24 05:50:07'),
(8, 12, '1', '2019-01-24 06:08:17'),
(9, 11, '2', '2019-01-24 07:56:30'),
(10, 12, '3', '2019-01-24 07:56:45'),
(11, 11, '4', '2019-01-24 07:57:27'),
(12, 12, '5', '2019-01-24 07:57:40'),
(13, 11, '6', '2019-01-24 07:57:48'),
(14, 12, '7', '2019-01-24 07:58:02'),
(15, 8, '1', '2019-01-24 07:58:30'),
(16, 7, '2', '2019-01-24 08:03:12'),
(17, 8, '3', '2019-01-24 08:03:27'),
(18, 7, '4', '2019-01-24 08:03:39'),
(19, 8, '5', '2019-01-24 08:03:54'),
(20, 7, '6', '2019-01-24 08:04:07'),
(21, 8, '7', '2019-01-24 08:04:31'),
(22, 10, '1', '2019-01-24 08:05:34'),
(23, 9, '2', '2019-01-24 08:05:46'),
(24, 10, '3', '2019-01-24 08:06:00'),
(25, 9, '4', '2019-01-24 08:06:15'),
(26, 10, '5', '2019-01-24 08:06:58'),
(27, 9, '6', '2019-01-24 08:07:05'),
(28, 10, '7', '2019-01-24 08:07:12'),
(29, 16, '1', '2019-01-24 08:07:40'),
(30, 15, '2', '2019-01-24 08:07:53'),
(31, 16, '3', '2019-01-24 08:08:05'),
(32, 15, '4', '2019-01-24 08:08:40'),
(33, 16, '5', '2019-01-24 08:08:53'),
(34, 15, '6', '2019-01-24 08:09:05'),
(35, 16, '7', '2019-01-24 08:09:16'),
(36, 14, '1', '2019-01-24 08:09:53'),
(37, 13, '2', '2019-01-24 08:10:00'),
(38, 14, '3', '2019-01-24 08:10:10'),
(39, 13, '4', '2019-01-24 08:10:25'),
(40, 14, '5', '2019-01-24 08:10:45'),
(41, 13, '6', '2019-01-24 08:10:51'),
(42, 14, '7', '2019-01-24 08:11:02'),
(43, 18, '1', '2019-01-24 08:11:21'),
(44, 18, '2', '2019-01-24 08:11:30'),
(45, 18, '3', '2019-01-24 08:11:38'),
(46, 18, '4', '2019-01-24 08:11:46'),
(47, 18, '5', '2019-01-24 08:11:53'),
(48, 18, '6', '2019-01-24 08:12:20'),
(49, 18, '7', '2019-01-24 08:12:29'),
(50, 17, '1', '2019-01-24 08:15:00'),
(51, 17, '2', '2019-01-24 08:15:20'),
(52, 19, '3', '2019-01-24 08:15:36'),
(53, 17, '4', '2019-01-24 08:15:48'),
(54, 19, '5', '2019-01-24 08:15:57'),
(55, 17, '6', '2019-01-24 08:16:06'),
(56, 19, '7', '2019-01-24 08:16:15');

-- --------------------------------------------------------

--
-- Table structure for table `i_user_roles`
--

DROP TABLE IF EXISTS `i_user_roles`;
CREATE TABLE `i_user_roles` (
  `user_role_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`user_role_id`),
  KEY `user_id` (`user_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `i_user_roles`
--

INSERT INTO `i_user_roles` (`user_role_id`, `user_id`, `role_id`) VALUES
(1, 1, 1),
(2, 5, 4),
(3, 6, 4),
(4, 7, 4),
(5, 8, 4),
(6, 9, 4),
(7, 10, 4),
(8, 11, 4),
(9, 12, 4),
(10, 13, 4),
(11, 14, 4),
(12, 15, 4),
(13, 16, 4),
(14, 17, 4),
(15, 18, 4),
(16, 19, 4),
(17, 20, 3),
(18, 21, 5),
(19, 22, 6);

-- --------------------------------------------------------

--
-- Table structure for table `perm_master`
--

DROP TABLE IF EXISTS `perm_master`;
CREATE TABLE  `perm_master` (
  `perm_id` int(11) NOT NULL AUTO_INCREMENT,
  `perm_code` varchar(30) NOT NULL,
  `perm_desc` varchar(100) NOT NULL,
  `perm_order` int(5) NOT NULL,
  `perm_label` int(5) NOT NULL,
  `perm_parent` varchar(10) NOT NULL,
  `perm_class` varchar(50) NOT NULL,
  `perm_url` varchar(200) NOT NULL,
  `perm_status` varchar(10) NOT NULL,
  `perm_attr` varchar(200) NOT NULL,
  `perm_icon` varchar(50) NOT NULL,
  `last_updated_id` int(11) NOT NULL,
  `last_updated_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`perm_id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `perm_master`
--

INSERT INTO `perm_master` (`perm_id`, `perm_code`, `perm_desc`, `perm_order`, `perm_label`, `perm_parent`, `perm_class`, `perm_url`, `perm_status`, `perm_attr`, `perm_icon`, `last_updated_id`, `last_updated_date`) VALUES
(1, 'DASHBOARD', 'Dashboard', 1, 1, '0', '', 'dashboard', 'Active', '', 'fa fa-dashboard', 1, '2018-08-24 20:05:12'),
(2, 'SETTINGS', 'Settings', 2, 1, '0', '', 'home/Settings', 'Active', '', 'fa fa-cog', 1, '2018-08-24 20:07:04'),
(3, 'PROFILE_SETTING', 'Profile settings', 1, 1, '2', '', 'home/Settings#profile-settings', 'Active', '', 'fa fa-dashboard', 1, '2018-08-26 20:41:02'),
(4, 'PATIENTS', 'Patients', 3, 1, '0', '', '#', 'Active', '', 'fa fa-user', 1, '2018-09-06 11:44:58'),
(5, 'PATIENTS_OPD_LIST', 'OPD list', 1, 1, '4', '', 'patient/patient/opd_list', 'Active', '', 'fa fa-user', 1, '2018-09-06 11:47:14'),
(6, 'TREATMENT', 'Treatment', 4, 2, '0', '', '#', 'Active', '', 'fa fa-edit', 1, '2018-09-06 12:24:57'),
(7, 'TREATMENT_OPD', 'OPD', 1, 3, '6', '', 'patient/treatment/show_patients', 'Active', '', 'fa fa-edit', 1, '2018-09-06 12:27:29'),
(8, 'REPORTS', 'Reports', 5, 5, '0', '', '#', 'Active', '', 'fa fa-book', 1, '2018-09-06 13:25:14'),
(9, 'PATIENTS_IPD_LIST', 'IPD', 2, 2, '6', '', 'patient/patient/ipd_list', 'Active', '', 'fa fa-user', 1, '2018-09-07 13:22:09'),
(10, 'OPD_REPORT', 'OPD Report', 1, 1, '8', '', 'reports/Opd', 'Active', '', 'fa fa-book', 1, '2018-09-19 17:52:17'),
(11, 'IPD_REPORT', 'IPD Report', 2, 2, '8', '', 'reports/Ipd', 'Active', '', 'fa fa-book', 1, '2018-09-25 20:06:45'),
(12, 'BED_OCC_REPORT', 'Bed occupied Report', 3, 3, '8', '', 'reports/Ipd/bed_occupied_report', 'Active', '', 'fa fa-book', 1, '2018-09-26 13:18:03'),
(13, 'BED_OCC_CHART', 'Bed occupancy chart', 4, 4, '8', '', 'reports/Ipd/bed_occupancy_chart', 'Active', '', 'fa fa-book', 1, '2018-09-26 19:07:28'),
(14, 'TEST_REPORTS', 'Test reports', 6, 1, '0', '', '#', 'Active', '', 'fa fa-book', 1, '2018-11-08 20:06:36'),
(15, 'XRAY', 'X-Ray', 1, 1, '14', '', 'x-ray', 'Active', '', 'fa fa-book', 1, '2018-11-08 20:10:15'),
(16, 'MONTHLY_IPD_REPORT', 'Monthly IPD report', 5, 5, '8', '', 'monthwise-ipd-report', 'Active', '', 'fa fa-book', 1, '2018-11-08 20:56:47'),
(17, 'MONTHLY_OPD_IPD_REPORT', 'Month wise OPD IPD report', 6, 6, '8', '', 'monthwise-opd-ipd-report', 'Active', '', 'fa fa-book', 1, '2018-11-08 21:00:38'),
(18, 'ECG', 'ECG', 2, 2, '14', '', 'ecg', 'Active', '', 'fa fa-book', 1, '2018-11-09 00:36:59'),
(19, 'USG', 'USG', 3, 3, '14', '', 'usg', 'Active', '', 'fa fa-book', 1, '2018-11-09 00:38:49'),
(20, 'KSHARASUTRA', 'Ksharasutra', 4, 4, '14', '', 'ksharasutra', 'Active', '', 'fa fa-book', 1, '2018-11-14 20:18:06'),
(21, 'SURGERY', 'Surgery', 5, 5, '14', '', 'surgery', 'Active', '', 'fa fa-book', 1, '2018-11-14 20:30:38'),
(22, 'SURGERY_COUNT', 'Surgery count', 6, 6, '14', '', 'surgery-count', 'Active', '', 'fa fa-book', 1, '2018-11-14 20:33:23'),
(23, 'PANCHAKARMA', 'Panchakarma', 7, 7, '14', '', 'panchakarma', 'Active', '', 'fa fa-book', 1, '2018-11-14 20:35:52'),
(24, 'PANCHAKARMA_PROC_COUNT', 'Panchakarma count', 8, 8, '14', '', 'panchakarma-procedure-stats', 'Active', '', 'fa fa-book', 1, '2018-11-14 20:38:42'),
(25, 'LAB', 'Laboratory', 9, 9, '14', '', 'lab', 'Active', '', 'fa fa-book', 1, '2018-11-14 20:43:19'),
(26, 'LAB_COUNT', 'Laboratory count', 10, 10, '14', '', 'lab-count', 'Active', '', 'fa fa-book', 1, '2018-11-14 20:46:04'),
(27, 'DEPARTMENT_LIST', 'Department list', 2, 2, '2', '', 'department-list', 'Active', '', 'fa fa-book', 1, '2018-11-17 22:06:32'),
(28, 'USERS_LIST', 'Users list', 3, 3, '2', '', 'users-list', 'Active', '', 'fa fa-book', 1, '2018-12-10 19:52:40'),
(29, 'NEW_PATIENT', 'New patient', 2, 2, '4', '', 'patient', 'Active', '', 'fa fa-book', 1, '2018-12-10 20:15:03'),
(30, 'PHARMACY', 'Pharmacy', 7, 7, '0', '', '#', 'Active', '', 'fa fa-medkit', 1, '2018-12-23 08:22:38'),
(31, 'PURCHASE_MASTER', 'Purchase master data', 1, 1, '30', '', 'show-purchase-type', 'Active', '', 'fa fa-medkit', 1, '2018-12-23 08:24:54'),
(32, 'PRODUCT_LIST', 'Product list', 2, 2, '30', '', 'product-list', 'Active', '', 'fa fa-medkit', 1, '2018-12-23 08:26:26'),
(33, 'PURCHASE_RETURN', 'Purchase return', 3, 3, '30', '', 'purchase-return', 'Active', '', 'fa fa-medkit', 1, '2018-12-23 09:07:07'),
(34, 'DUTY_CHART', 'Doctors duty chart', 4, 4, '2', '', 'duty-doctors', 'Active', '', 'fa fa-medkit', 1, '2019-01-24 14:03:35'),
(35, 'XRAY_INFO', 'X-Ray List', 11, 11, '0', '', 'xray-info', 'Active', '', 'fa fa-times', 1, '2019-01-26 13:10:23'),
(36, 'ECG_INFO', 'ECG List', 12, 12, '0', '', 'ecg-info', 'Active', '', 'fa fa-medkit', 1, '2019-01-26 14:40:56'),
(37, 'USG_INFO', 'USG List', 13, 13, '0', '', 'usg-info', 'Active', '', 'fa fa-medkit', 1, '2019-01-26 17:00:24');

-- --------------------------------------------------------

--
-- Table structure for table `product_master`
--

DROP TABLE IF EXISTS `product_master`;
CREATE TABLE  `product_master` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_unique_id` varchar(50) NOT NULL DEFAULT '',
  `product_master_id` int(11) NOT NULL,
  `product_batch` varchar(45) NOT NULL DEFAULT '',
  `supplier_id` int(11) NOT NULL,
  `packing_name` varchar(10) NOT NULL DEFAULT '',
  `product_mfg` varchar(100) NOT NULL DEFAULT '',
  `product_type` varchar(45) NOT NULL DEFAULT '',
  `manifacture_date` date NOT NULL DEFAULT '0000-00-00',
  `exp_date` date NOT NULL DEFAULT '0000-00-00',
  `quantity` int(11) NOT NULL DEFAULT '0',
  `purchase_rate` float(8,2) NOT NULL DEFAULT '0.00',
  `mrp` float(8,2) NOT NULL DEFAULT '0.00',
  `sale_rate` float(8,2) NOT NULL,
  `vat` float(8,2) NOT NULL,
  `no_of_items_in_pack` int(11) NOT NULL DEFAULT '0',
  `pack_type` varchar(45) NOT NULL DEFAULT '',
  `item_unit_cost` float(8,2) NOT NULL DEFAULT '0.00',
  `no_of_sub_items` int(11) NOT NULL DEFAULT '0',
  `sub_item_pack_type` varchar(45) NOT NULL DEFAULT '',
  `sub_item_unit_cost` float(8,2) NOT NULL,
  `no_of_sub_items_in_pack` int(11) NOT NULL DEFAULT '0',
  `discount` float(8,2) NOT NULL DEFAULT '0.00',
  `reorder_point` int(11) NOT NULL DEFAULT '0',
  `weight` varchar(45) NOT NULL DEFAULT '0.00',
  `rack` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COMMENT='medicine information';

--
-- Dumping data for table `product_master`
--

INSERT INTO `product_master` (`product_id`, `product_unique_id`, `product_master_id`, `product_batch`, `supplier_id`, `packing_name`, `product_mfg`, `product_type`, `manifacture_date`, `exp_date`, `quantity`, `purchase_rate`, `mrp`, `sale_rate`, `vat`, `no_of_items_in_pack`, `pack_type`, `item_unit_cost`, `no_of_sub_items`, `sub_item_pack_type`, `sub_item_unit_cost`, `no_of_sub_items_in_pack`, `discount`, `reorder_point`, `weight`, `rack`) VALUES
(1, 'ba63b486-1674-4539-8e41-0d45f2f95b67', 2, '102211', 1, '100 ML', 'SDM PAHARMACY', 'TAILA', '2018-07-24', '2019-10-31', 10, 380.00, 455.00, 455.00, 0.00, 12, 'STRIP', 37.92, 10, 'NUMBER', 0.00, 120, 0.00, 5, '100.00', 10),
(2, '064bc9c0-262a-43ad-853f-6b2b04197fd3', 36, '14532018', 221, '100 mg', 'BHIDHYANATH', 'GUGGULU', '2018-12-01', '2020-12-31', 10, 480.00, 500.00, 500.00, 0.00, 12, 'STRIP', 41.67, 10, 'NUMBER', 0.00, 120, 0.00, 0, '100.00', 12);

-- --------------------------------------------------------

--
-- Table structure for table `role_master`
--

DROP TABLE IF EXISTS `role_master`;
CREATE TABLE `role_master` (
  `role_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(45) NOT NULL,
  `role_code` varchar(45) NOT NULL,
  `last_updated_by` varchar(45) NOT NULL,
  `last_updated_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `role_master`
--

INSERT INTO `role_master` (`role_id`, `role_name`, `role_code`, `last_updated_by`, `last_updated_on`) VALUES
(1, 'Admin', 'ADMIN', '1', '2018-08-22 21:21:51'),
(2, 'Lab', 'LAB', '1', '2018-08-22 21:23:45'),
(3, 'Xray', 'XRAY', '1', '2018-08-22 21:23:45'),
(4, 'Doctor', 'DOCTOR', '1', '2018-08-22 21:54:22'),
(5, 'ECG', 'ECG', '1', '2019-01-26 08:13:56'),
(6, 'USG', 'USG', '1', '2019-01-26 08:14:07');

-- --------------------------------------------------------

--
-- Table structure for table `role_perm`
--

DROP TABLE IF EXISTS `role_perm`;
CREATE TABLE `role_perm` (
  `role_perm_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `perm_id` int(11) NOT NULL,
  `status` varchar(10) NOT NULL,
  `last_updated_id` int(11) NOT NULL,
  `last_updated_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `access_perm` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`role_perm_id`),
  UNIQUE KEY `unique_index` (`role_id`,`perm_id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `role_perm`
--

INSERT INTO `role_perm` (`role_perm_id`, `role_id`, `perm_id`, `status`, `last_updated_id`, `last_updated_date`, `access_perm`) VALUES
(1, 1, 1, 'Active', 1, '0000-00-00 00:00:00', 1),
(2, 1, 2, 'Active', 1, '2018-08-24 20:09:54', 1),
(3, 1, 3, 'Active', 1, '2018-08-26 20:44:02', 1),
(4, 1, 4, 'Active', 1, '2018-09-06 11:45:26', 1),
(5, 1, 5, 'Active', 1, '2018-09-06 11:45:26', 1),
(6, 1, 6, 'Active', 1, '2018-09-06 12:25:11', 1),
(7, 1, 7, 'Active', 1, '2018-09-06 12:27:58', 1),
(8, 1, 8, 'Active', 1, '2018-09-06 13:25:29', 1),
(9, 1, 9, 'Active', 1, '2018-09-07 13:22:33', 1),
(10, 1, 10, 'Active', 1, '2018-09-19 17:52:46', 1),
(11, 1, 11, 'Active', 1, '2018-09-25 20:07:16', 1),
(12, 1, 12, 'Active', 1, '2018-09-26 13:18:28', 1),
(13, 1, 13, 'Active', 1, '2018-09-26 13:18:00', 1),
(14, 1, 14, 'Active', 1, '2018-11-08 20:07:52', 1),
(15, 1, 15, 'Active', 1, '2018-11-08 20:11:20', 1),
(16, 1, 16, 'Active', 1, '2018-11-08 20:57:47', 1),
(17, 1, 17, 'Active', 1, '2018-11-08 21:00:40', 1),
(18, 1, 18, 'Active', 1, '2018-11-09 00:37:01', 1),
(20, 1, 19, 'Active', 1, '2018-11-09 00:38:52', 1),
(21, 1, 20, 'Active', 1, '2018-11-14 20:17:54', 1),
(23, 1, 21, 'Active', 1, '2018-11-14 20:30:43', 1),
(24, 1, 22, 'Active', 1, '2018-11-14 20:33:27', 1),
(25, 1, 23, 'Active', 1, '2018-11-14 20:35:52', 1),
(27, 1, 24, 'Active', 1, '2018-11-14 20:39:56', 1),
(28, 1, 25, 'Active', 1, '2018-11-14 20:43:19', 1),
(29, 1, 26, 'Active', 1, '2018-11-14 20:46:04', 1),
(30, 1, 27, 'Active', 1, '2018-11-17 22:06:34', 1),
(31, 4, 1, 'Active', 1, '2018-12-10 00:52:32', 1),
(32, 4, 2, 'Active', 1, '2018-12-10 00:53:35', 1),
(33, 1, 28, 'Active', 1, '2018-12-10 19:53:15', 1),
(34, 1, 29, 'Active', 1, '2018-12-10 20:15:26', 1),
(35, 1, 30, 'Active', 1, '2018-12-23 08:23:14', 1),
(36, 1, 31, 'Active', 1, '2018-12-23 08:25:15', 1),
(37, 1, 32, 'Active', 1, '2018-12-23 08:26:58', 1),
(38, 1, 33, 'Active', 1, '2018-12-23 09:07:34', 1),
(39, 4, 6, 'Active', 1, '2019-01-24 13:10:17', 1),
(40, 4, 7, 'Active', 1, '2019-01-24 13:11:12', 1),
(41, 1, 34, 'Active', 1, '2019-01-24 14:03:59', 1),
(42, 4, 9, 'Active', 1, '2019-01-25 20:48:16', 1),
(43, 3, 1, 'Active', 1, '2019-01-26 01:07:22', 1),
(44, 3, 35, 'Active', 1, '2019-01-26 13:10:53', 1),
(45, 3, 14, 'Active', 1, '2019-01-26 13:14:46', 1),
(46, 3, 15, 'Active', 1, '2019-01-26 13:15:04', 1),
(47, 5, 1, 'Active', 1, '2019-01-26 14:12:28', 1),
(48, 5, 14, 'Active', 1, '2019-01-26 14:33:08', 1),
(49, 5, 18, 'Active', 1, '2019-01-26 14:33:29', 1),
(50, 5, 36, 'Active', 1, '2019-01-26 14:41:17', 1),
(51, 6, 1, 'Active', 1, '2019-01-26 16:24:12', 1),
(52, 6, 14, 'Active', 1, '2019-01-26 16:58:08', 1),
(53, 6, 19, 'Active', 1, '2019-01-26 16:58:54', 1),
(54, 6, 37, 'Active', 1, '2019-01-26 17:00:44', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(256) DEFAULT NULL,
  `user_email` varchar(256) DEFAULT NULL,
  `user_password` varchar(256) DEFAULT NULL,
  `user_country` varchar(256) DEFAULT NULL,
  `user_state` varchar(256) DEFAULT NULL,
  `user_mobile` varchar(256) DEFAULT NULL,
  `user_type` varchar(256) DEFAULT NULL,
  `user_department` varchar(256) DEFAULT NULL,
  `user_date` varchar(256) DEFAULT NULL,
  `user_modified` varchar(45) DEFAULT NULL,
  `active` varchar(45) DEFAULT '1',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `user_name`, `user_email`, `user_password`, `user_country`, `user_state`, `user_mobile`, `user_type`, `user_department`, `user_date`, `user_modified`, `active`) VALUES
(1, 'Admin', 'admin@ahms.com', '$2a$08$SCRISSRvXN3.PQ4PL7nY5O1I6GcD2EfIAh8.Wwza7DDSKUa7LWtbG', 'india', 'kar', '9845098450', 'admin', 'kayachikista', NULL, NULL, '1'),
(2, 'Doctor', 'doctor@ahms.com', '$2a$08$SCRISSRvXN3.PQ4PL7nY5O1I6GcD2EfIAh8.Wwza7DDSKUa7LWtbG', 'India', 'Karnataka', '9845098450', 'doctor', 'kayachikista', NULL, NULL, '1'),
(3, 'Lab', 'lab@ahms.com', '$2a$08$SCRISSRvXN3.PQ4PL7nY5O1I6GcD2EfIAh8.Wwza7DDSKUa7LWtbG', 'India', 'Karnataka', '1234567890', 'lab', '', NULL, NULL, '0'),
(4, 'admin2', 'mb@ahms.com', '$2a$08$SCRISSRvXN3.PQ4PL7nY5O1I6GcD2EfIAh8.Wwza7DDSKUa7LWtbG', 'India', 'Karnataka', NULL, 'admin', NULL, NULL, NULL, '0'),
(5, 'Dr. R I Sankannavar', 'drrisankannavar@samcy.com', '$2a$08$O.Uj3ocfy5nXQgqRYCSpK.evQFR7Y1uns.EV12TymM/RDqJzSsM6a', '', '', '1234567890', '4', 'AATYAYIKACHIKITSA', '2019-01-24T11:04:47+05:30', '2019-01-24T11:04:47+05:30', '1'),
(6, 'Dr. Reshma. K', 'drreshmak@samcy.com', '$2a$08$gfG2wDcawxs7BvpWV2L5OeG6UcUWwDhnvhpAYVYFz4te6dI.KeV1q', '', '', '1234567890', '4', 'AATYAYIKACHIKITSA', '2019-01-24T11:09:07+05:30', '2019-01-24T11:09:07+05:30', '1'),
(7, 'Dr. Mahesh', 'drmahesh@samcy.com', '$2a$08$curR0j3f3cmYvc2dmj315Od/RJ6/fSI/uXmVv04A8Ls7WrblABEym', '', '', '1234567890', '4', 'KAYACHIKITSA', '2019-01-24T11:09:52+05:30', '2019-01-24T11:09:52+05:30', '1'),
(8, 'Dr Rahul Kulkarni', 'drrahulk@samcy.com', '$2a$08$L9hx.oxHdhLRwXn5YssvqO/wq2kPpCNHshgRttxAINmY.Q3Kk2h.a', '', '', '1234567890', '4', 'KAYACHIKITSA', '2019-01-24T11:10:28+05:30', '2019-01-24T11:10:28+05:30', '1'),
(9, 'Dr. Mahantagouda Biradar', 'drmbiradar@samcy.com', '$2a$08$7YGGlI2uVJlgVjyWgW8btOYtrcoBWCig5Dacjgy3veVYc3X7fcrNm', '', '', '1234567890', '4', 'PANCHAKARMA', '2019-01-24T11:11:57+05:30', '2019-01-24T11:11:57+05:30', '1'),
(10, 'Dr Vijaylaxmi', 'drvijaylaxmi@samcy.com', '$2a$08$mn8DlHx34oUWtHMoSgSn9eyCMO4lszg1zBwpKLmBalX6OTfK27MCu', '', '', '1234567890', '4', 'PANCHAKARMA', '2019-01-24T11:12:34+05:30', '2019-01-24T11:12:34+05:30', '1'),
(11, 'Dr. Manjula Ranagatti', 'drmranagatti@samcy.com', '$2a$08$km1yILIB7EMQoySY91LogeyYjaJfH7PsaEm5cXq34pPQMTIHLWS5K', '', '', '1234567890', '4', 'BALAROGA', '2019-01-24T11:13:19+05:30', '2019-01-24T11:13:19+05:30', '1'),
(12, 'Dr. Sital Desai', 'drsdesai@samcy.com', '$2a$08$3FBMq5CB.BMijMAfs.YWc.nHEnUKejCdsVFHcLAj7X3GhfL5GMIzS', '', '', '1234567890', '4', 'BALAROGA', '2019-01-24T11:13:57+05:30', '2019-01-24T11:13:57+05:30', '1'),
(13, 'Dr. Manasa Naik', 'drmnaik@samcy.com', '$2a$08$T.9Wwn28Wr56H145/DnxEOqZsG3PrCMNQ7uGxDDmFg737Xcj229Gy', '', '', '1234567890', '4', 'SHALAKYA_TANTRA', '2019-01-24T11:14:33+05:30', '2019-01-24T11:14:33+05:30', '1'),
(14, 'Dr. Supriya', 'drsupriya@samcy.com', '$2a$08$0SkrsclnZvPYNRgfeP2G/O.J1W/U.XeGhG2prbmzQpCftzkZCZU0G', '', '', '1234567890', '4', 'SHALAKYA_TANTRA', '2019-01-24T11:15:04+05:30', '2019-01-24T11:15:04+05:30', '1'),
(15, 'Dr. Preeti Takur', 'drpreetit@samcy.com', '$2a$08$9yIDAJxCQOSa0H0NLYpVMu3KeHoCC3cgNDaHoXNwdThLIHRN6JUba', '', '', '1234567890', '4', 'PRASOOTI_&_STRIROGA', '2019-01-24T11:15:39+05:30', '2019-01-24T11:15:39+05:30', '1'),
(16, 'Dr. Madhuri', 'drmadhuri@samcy.com', '$2a$08$gai1P4Vz1LKUz10K75TEA.wrTZSfzdTiaofOEYWGrunPHiZLGlSSe', '', '', '1234567890', '4', 'PRASOOTI_&_STRIROGA', '2019-01-24T11:16:15+05:30', '2019-01-24T11:16:15+05:30', '1'),
(17, 'Dr. Mahesh Raju', 'drmaheshr@samcy.com', '$2a$08$KJKvOHriMZ0.e9OIG8ebm.nf3pba2F684VNhFcj8gnOC5zuMEUGKm', '', '', '1234567890', '4', 'SWASTHAVRITTA', '2019-01-24T11:16:49+05:30', '2019-01-24T11:16:49+05:30', '1'),
(18, 'Dr Ramesh Sarpatil', 'drrameshs@samcy.com', '$2a$08$aDhnRAOuHuvZSfIsAgqwhuzmAHULzmuviE.45Om8vzmlUa9uEdtyO', '', '', '1234567890', '4', 'SHALYA_TANTRA', '2019-01-24T11:17:35+05:30', '2019-01-24T11:17:35+05:30', '1'),
(19, 'Dr. Sankanagouda M P', 'drsmp@samcy.com', '$2a$08$KQYHwiEM2adAPL9UfwgCp.bTgW2GcTIArIhMiOSdr2evWd5I8YMgu', '', '', '1234567890', '4', 'SWASTHAVRITTA', '2019-01-24T13:43:56+05:30', '2019-01-24T13:43:56+05:30', '1'),
(20, 'xray user', 'xraydept@samcy.com', '$2a$08$iI.L.DJq2GyZjPX0ZlfZY.RBIiG9Z6V1awQu1GJ5ZCbuJCP2rZRhS', '', '', '1234567890', '3', '', '2019-01-26T01:03:47+05:30', '2019-01-26T01:03:47+05:30', '1'),
(21, 'ECG user', 'ecgdept@samcy.com', '$2a$08$D8odr40VhZ3Zj37ZrQErMuWXjamZGVRyenR5B.0YiThch6TGNryZ6', '', '', '1234567890', '5', '', '2019-01-26T13:45:01+05:30', '2019-01-26T13:45:01+05:30', '1'),
(22, 'USG user', 'usgdept@samcy.com', '$2a$08$Sp6jb3IvuP1dy2yUmeG7KO.S32J39GjIa9Bob.0Aw/LsQo7Xc1T76', '', '', '1234567890', '6', '', '2019-01-26T13:45:25+05:30', '2019-01-26T13:45:25+05:30', '1');



DROP TABLE IF EXISTS `purchase_types_master`;
CREATE TABLE `purchase_types_master` (
  `pt_id` int(11) NOT NULL AUTO_INCREMENT,
  `pt_desc` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`pt_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `purchase_types_master` (`pt_id`, `pt_desc`) VALUES
(1, 'supplier'),
(2, 'product'),
(3, 'mfg'),
(4, 'group'),
(5, 'category');

DROP TABLE IF EXISTS `packagin_type`;
CREATE TABLE `packagin_type` (
  `pakg_id` int(11) NOT NULL AUTO_INCREMENT,
  `pakg_name` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`pakg_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
--

INSERT INTO `packagin_type` (`pakg_id`, `pakg_name`) VALUES
(1, 'Box'),
(2, 'Strip');

CREATE TABLE `medicine_frequency` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `frequency` VARCHAR(45) NOT NULL DEFAULT '',
  PRIMARY KEY(`id`)
)
ENGINE = InnoDB;

update labregistery set tested_date=testDate;
ALTER TABLE `labregistery` MODIFY COLUMN `tested_date` DATE;


INSERT INTO `perm_master` (`perm_id`, `perm_code`, `perm_desc`, `perm_order`, `perm_label`, `perm_parent`, `perm_class`, `perm_url`, `perm_status`, `perm_attr`, `perm_icon`, `last_updated_id`, `last_updated_date`) VALUES (41, 'SEARCH_PATIENT', 'Search patient', '4', '4', '4', '', 'patient/search_patient', 'Active', '', '', '1', CURRENT_TIMESTAMP);
INSERT INTO `role_perm` (`role_perm_id`, `role_id`, `perm_id`, `status`, `last_updated_id`, `last_updated_date`, `access_perm`) VALUES (NULL, '1', '41', 'Active', '1', CURRENT_TIMESTAMP, '1');