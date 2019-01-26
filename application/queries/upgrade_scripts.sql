/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  hp
 * Created: 24 Jan, 2019
 */

DROP TABLE IF EXISTS users;
CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
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
  `active` varchar(45) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `user_name`, `user_email`, `user_password`, `user_country`, `user_state`, `user_mobile`, `user_type`, `user_department`, `user_date`, `user_modified`, `active`) VALUES
(1, 'Admin', 'admin@ahms.com', '$2a$08$SCRISSRvXN3.PQ4PL7nY5O1I6GcD2EfIAh8.Wwza7DDSKUa7LWtbG', 'india', 'kar', '9845098450', 'admin', 'kayachikista', NULL, NULL, '1'),
(2, 'Doctor', 'doctor@ahms.com', '$2a$08$SCRISSRvXN3.PQ4PL7nY5O1I6GcD2EfIAh8.Wwza7DDSKUa7LWtbG', 'India', 'Karnataka', '9845098450', 'doctor', 'kayachikista', NULL, NULL, '0'),
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
(19, 'Dr. Sankanagouda M P', 'drsmp@samcy.com', '$2a$08$KQYHwiEM2adAPL9UfwgCp.bTgW2GcTIArIhMiOSdr2evWd5I8YMgu', '', '', '1234567890', '4', 'SWASTHAVRITTA', '2019-01-24T13:43:56+05:30', '2019-01-24T13:43:56+05:30', '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;


DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `id` int(10) UNSIGNED NOT NULL,
  `college_name` text,
  `place` varchar(70) NOT NULL,
  `printing_style` varchar(1) NOT NULL,
  `logo` varchar(50) DEFAULT NULL,
  `edit_flag` int(1) NOT NULL DEFAULT '0',
  `admin_email` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`id`, `college_name`, `place`, `printing_style`, `logo`, `edit_flag`, `admin_email`) VALUES
(1, 'SHARADA AYURVEDIC MEDICAL COLLEGE AND HOSPITAL YADGIR - 585202', 'Yadgir', 'L', 'your_logo.png', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `i_user_roles`
--

DROP TABLE IF EXISTS `i_user_roles`;
CREATE TABLE `i_user_roles` (
  `user_role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
(16, 19, 4);

-- --------------------------------------------------------

--
-- Table structure for table `medicine_frequency`
--

DROP TABLE IF EXISTS `medicine_frequency`;
CREATE TABLE `medicine_frequency` (
  `med_frq_id` int(10) UNSIGNED NOT NULL,
  `med_freq` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `medicine_frequency`
--

INSERT INTO `medicine_frequency` (`med_frq_id`, `med_freq`) VALUES
(1, 'BID'),
(2, 'TID'),
(3, 'QID');

-- --------------------------------------------------------

--
-- Table structure for table `perm_master`
--

DROP TABLE IF EXISTS `perm_master`;
CREATE TABLE `perm_master` (
  `perm_id` int(11) NOT NULL,
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
  `last_updated_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
(34, 'DUTY_CHART', 'Doctors duty chart', 4, 4, '2', '', 'duty-doctors', 'Active', '', 'fa fa-medkit', 1, '2019-01-24 14:03:35');

-- --------------------------------------------------------

--
-- Table structure for table `role_master`
--

DROP TABLE IF EXISTS `role_master`;
CREATE TABLE `role_master` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `role_name` varchar(45) NOT NULL,
  `role_code` varchar(45) NOT NULL,
  `last_updated_by` varchar(45) NOT NULL,
  `last_updated_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `role_master`
--

INSERT INTO `role_master` (`role_id`, `role_name`, `role_code`, `last_updated_by`, `last_updated_on`) VALUES
(1, 'Admin', 'ADMIN', '1', '2018-08-23 02:51:51'),
(2, 'Lab', 'LAB', '1', '2018-08-23 02:53:45'),
(3, 'Xray', 'XRAY', '1', '2018-08-23 02:53:45'),
(4, 'Doctor', 'DOCTOR', '1', '2018-08-23 03:24:22');

-- --------------------------------------------------------

--
-- Table structure for table `role_perm`
--

DROP TABLE IF EXISTS `role_perm`;
CREATE TABLE `role_perm` (
  `role_perm_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `perm_id` int(11) NOT NULL,
  `status` varchar(10) NOT NULL,
  `last_updated_id` int(11) NOT NULL,
  `last_updated_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `access_perm` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
(41, 1, 34, 'Active', 1, '2019-01-24 14:03:59', 1);


DROP TABLE IF EXISTS `doctorsduty`;
CREATE TABLE `doctorsduty` (
  `id` int(11) NOT NULL,
  `doc_id` int(10) UNSIGNED DEFAULT NULL,
  `day` varchar(45) DEFAULT NULL,
  `added_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `doctorsduty`
--
ALTER TABLE `doctorsduty`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `doctorsduty`
--
ALTER TABLE `doctorsduty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;
--
-- Indexes for dumped tables
--

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `i_user_roles`
--
ALTER TABLE `i_user_roles`
  ADD PRIMARY KEY (`user_role_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `medicine_frequency`
--
ALTER TABLE `medicine_frequency`
  ADD PRIMARY KEY (`med_frq_id`);

--
-- Indexes for table `perm_master`
--
ALTER TABLE `perm_master`
  ADD PRIMARY KEY (`perm_id`);

--
-- Indexes for table `role_perm`
--
ALTER TABLE `role_perm`
  ADD PRIMARY KEY (`role_perm_id`),
  ADD UNIQUE KEY `unique_index` (`role_id`,`perm_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `config`
--
ALTER TABLE `config`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `i_user_roles`
--
ALTER TABLE `i_user_roles`
  MODIFY `user_role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `medicine_frequency`
--
ALTER TABLE `medicine_frequency`
  MODIFY `med_frq_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `perm_master`
--
ALTER TABLE `perm_master`
  MODIFY `perm_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `role_perm`
--
ALTER TABLE `role_perm`
  MODIFY `role_perm_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;



CREATE TABLE `i_user_roles` (
  `user_role_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`user_role_id`),
  KEY `user_id` (`user_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `medicine_frequency`;
CREATE TABLE `medicine_frequency` (
  `med_frq_id` int(10) UNSIGNED NOT NULL,
  `med_freq` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `medicine_frequency`
--

INSERT INTO `medicine_frequency` (`med_frq_id`, `med_freq`) VALUES
(1, 'BID'),
(2, 'TID'),
(3, 'QID');

-- --------------------------------------------------------

--
-- Table structure for table `product_master`
--

DROP TABLE IF EXISTS `product_master`;
CREATE TABLE `product_master` (
  `product_id` int(11) NOT NULL,
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
  `rack` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='medicine information';

--
-- Dumping data for table `product_master`
--

INSERT INTO `product_master` (`product_id`, `product_unique_id`, `product_master_id`, `product_batch`, `supplier_id`, `packing_name`, `product_mfg`, `product_type`, `manifacture_date`, `exp_date`, `quantity`, `purchase_rate`, `mrp`, `sale_rate`, `vat`, `no_of_items_in_pack`, `pack_type`, `item_unit_cost`, `no_of_sub_items`, `sub_item_pack_type`, `sub_item_unit_cost`, `no_of_sub_items_in_pack`, `discount`, `reorder_point`, `weight`, `rack`) VALUES
(1, 'ba63b486-1674-4539-8e41-0d45f2f95b67', 2, '102211', 1, '100 ML', 'SDM PAHARMACY', 'TAILA', '2018-07-24', '2019-10-31', 10, 380.00, 455.00, 455.00, 0.00, 12, 'STRIP', 37.92, 10, 'NUMBER', 0.00, 120, 0.00, 5, '100.00', 10),
(2, '064bc9c0-262a-43ad-853f-6b2b04197fd3', 36, '14532018', 221, '100 mg', 'BHIDHYANATH', 'GUGGULU', '2018-12-01', '2020-12-31', 10, 480.00, 500.00, 500.00, 0.00, 12, 'STRIP', 41.67, 10, 'NUMBER', 0.00, 120, 0.00, 0, '100.00', 12);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_entry`
--

DROP TABLE IF EXISTS `purchase_entry`;
CREATE TABLE `purchase_entry` (
  `id` int(11) NOT NULL,
  `supplier_id` varchar(45) DEFAULT NULL,
  `billno` varchar(45) DEFAULT NULL,
  `date` varchar(45) DEFAULT NULL,
  `refno` varchar(45) DEFAULT NULL,
  `price` varchar(45) DEFAULT NULL,
  `opbal` varchar(45) DEFAULT NULL,
  `product` varchar(45) DEFAULT NULL,
  `batch` varchar(45) DEFAULT NULL,
  `pty` varchar(45) DEFAULT NULL,
  `fty` varchar(45) DEFAULT NULL,
  `prate` varchar(45) DEFAULT NULL,
  `mrp` varchar(45) DEFAULT NULL,
  `discount` varchar(45) DEFAULT NULL,
  `vat` varchar(45) DEFAULT NULL,
  `btype` varchar(45) DEFAULT NULL,
  `total` varchar(45) DEFAULT NULL,
  `product_name` varchar(45) DEFAULT NULL,
  `user_id` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_return_temp`
--

DROP TABLE IF EXISTS `purchase_return_temp`;
CREATE TABLE `purchase_return_temp` (
  `id` int(11) NOT NULL,
  `supplier_id` varchar(45) DEFAULT NULL,
  `billno` varchar(45) DEFAULT NULL,
  `date` varchar(45) DEFAULT NULL,
  `refno` varchar(45) DEFAULT NULL,
  `price` varchar(45) DEFAULT NULL,
  `opbal` varchar(45) DEFAULT NULL,
  `product` varchar(45) DEFAULT NULL,
  `batch` varchar(45) DEFAULT NULL,
  `pty` varchar(45) DEFAULT NULL,
  `fty` varchar(45) DEFAULT NULL,
  `prate` varchar(45) DEFAULT NULL,
  `mrp` varchar(45) DEFAULT NULL,
  `discount` varchar(45) DEFAULT NULL,
  `vat` varchar(45) DEFAULT NULL,
  `btype` varchar(45) DEFAULT NULL,
  `total` varchar(45) DEFAULT NULL,
  `product_name` varchar(45) DEFAULT NULL,
  `user_id` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_types_master`
--

DROP TABLE IF EXISTS `purchase_types_master`;
CREATE TABLE `purchase_types_master` (
  `pt_id` int(10) UNSIGNED NOT NULL,
  `pt_type` varchar(45) NOT NULL DEFAULT '',
  `pt_desc` varchar(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `purchase_types_master`
--

INSERT INTO `purchase_types_master` (`pt_id`, `pt_type`, `pt_desc`) VALUES
(1, 'SUPPLIER', 'Supplier'),
(2, 'PRODUCT', 'Product'),
(3, 'MFG', 'MFG'),
(4, 'GROUP', 'Group'),
(5, 'CATEGORY', 'Category');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_variables`
--

DROP TABLE IF EXISTS `purchase_variables`;
CREATE TABLE `purchase_variables` (
  `id` int(11) NOT NULL,
  `type` varchar(45) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `extrainfo` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `purchase_variables`
--

INSERT INTO `purchase_variables` (`id`, `type`, `name`, `extrainfo`) VALUES
(1, 'supplier', 'SHRI VINAYAKA AYURVEDIC AGENCIES UDUPI', NULL),
(2, 'product', 'MAHANARAYANA TAILA', NULL),
(3, 'mfg', 'SDM PAHARMACY', '947'),
(4, 'group', 'TAILA', NULL),
(5, 'category', 'TAILA', NULL),
(6, 'supplier', 'M/S ROYAL PHARMA BIDAR', NULL),
(7, 'product', 'KSHEERA BALA TAILA', NULL),
(10, 'product', 'DHANWANTARAM TAILA', NULL),
(13, 'product', 'MURIVENNA TAILA', NULL),
(15, 'product', 'SAHACHARADI TAILA', NULL),
(17, 'product', 'KOTTAMCHUKKADI TAILA', NULL),
(18, 'product', 'BRUHAT SAINDHAVADHYA TAILA', NULL),
(19, 'product', 'GANDHARVAHASTHYADHYA TAILA', NULL),
(20, 'product', 'MOORCHITA TILA TAILA', NULL),
(29, 'product', 'TRIPHALA GUGGULU', NULL),
(30, 'product', 'KAISHORA GUGGULU', NULL),
(31, 'product', 'TRAYODASHANGA GUGGULU', NULL),
(32, 'product', 'YOGARAJA GUGGULU', NULL),
(33, 'product', 'SIMHANADA GUGGULU', NULL),
(34, 'product', 'MAHAYOGARAJA GUGGULU', NULL),
(35, 'product', 'RASNADI GUGGULU', NULL),
(36, 'product', 'LAXADI GUGGULU', NULL),
(37, 'product', 'PANCHATIKTA GRUTA GUGGULU', NULL),
(38, 'product', 'NAVAKA GUGGULU', NULL),
(39, 'group', 'GUGGULU', NULL),
(40, 'category', 'GUGGULU', NULL),
(42, 'product', 'KANCHANARA GUGGULU', NULL),
(50, 'product', 'CHITRAKADI VATI', NULL),
(51, 'product', 'AGNITUNDI VATI', NULL),
(52, 'product', 'AMAPACHANA VATI', NULL),
(53, 'product', 'VATAGAJANKUSHA RASA', NULL),
(54, 'product', 'MAHAVATAVIDHWAMSA RASA', NULL),
(55, 'product', 'VATAVIDHWAMSA RASA', NULL),
(56, 'product', 'BRUHATVATA CHINTAMANI RASA ', NULL),
(57, 'product', 'EKANGAVEERA RASA', NULL),
(58, 'product', 'SANDHI ABHAYA', NULL),
(59, 'product', 'ABHARAKA BHASMA', NULL),
(60, 'product', 'KHADIRADI VAT', NULL),
(61, 'product', 'AROGYAVARDHINI VATI', NULL),
(62, 'product', 'GANDHAKA RASYANA', NULL),
(63, 'product', 'CHANDRAPRABHA VATI', NULL),
(64, 'product', 'SHILAJATU VATI', NULL),
(65, 'product', 'SHIVA GUTIKA', NULL),
(66, 'product', 'VISHAMUSTI VATI', NULL),
(67, 'product', 'VASANTAKUSUMAKARA RASA', NULL),
(68, 'product', 'TARAKESHWARA RASA', NULL),
(69, 'product', 'LUMBATONE ', NULL),
(70, 'product', 'CERVILONE', NULL),
(71, 'product', 'SOOTASHEKARA RASA', NULL),
(72, 'product', 'LAGHUSOOTASHEKARA RASA', NULL),
(73, 'product', 'PALSINURON', NULL),
(74, 'product', 'KSHEERABALA 101 DS', NULL),
(75, 'product', 'DHANWANTARAM DS', NULL),
(76, 'product', 'KSHEERA BALA 101 TAILA', NULL),
(77, 'product', 'TRIGUNAKYA RASA', NULL),
(78, 'product', 'MEDHOHARA VIDANGADI LOHA', NULL),
(79, 'product', 'KAMDUGDHA RASA', NULL),
(80, 'product', 'SWASAKUTARA RASA', NULL),
(81, 'product', 'SWASANANDANA GUTIKA', NULL),
(82, 'product', 'SWASAKASACHINTAMANI RASA', NULL),
(83, 'product', 'KAPHAKUTARA RASA ', NULL),
(84, 'product', 'KRIMIKUTARA RASA', NULL),
(85, 'product', 'SUDARSHANA VATI', NULL),
(86, 'product', 'GODANTI BHASMA', NULL),
(87, 'product', 'SHANKA VATI', NULL),
(88, 'product', 'PUNANRNAVA MANDOORA VATAKA', NULL),
(89, 'product', 'MANDOORA VATAKA', NULL),
(90, 'product', 'NAVAYASA LOHA', NULL),
(91, 'product', 'DHATRI LOHA', NULL),
(92, 'product', 'SAPTAMRUTA LOHA VATI', NULL),
(93, 'product', 'SHIRASHULADRI VAJRA RASA ', NULL),
(94, 'product', 'CEPHAGRAINE ', NULL),
(95, 'product', 'NAGAGUTI', NULL),
(96, 'product', 'CHANDRAKALA RASA', NULL),
(97, 'product', 'BOLABADDHA RASA', NULL),
(98, 'product', 'PRAVALA PISTI', NULL),
(99, 'product', 'PANCHAMRUTA PARPATI', NULL),
(100, 'product', 'RASA PARPATI', NULL),
(101, 'product', 'SAMSHAMANA VATI', NULL),
(102, 'product', 'BRAHMI VATI ', NULL),
(103, 'product', 'MANASAMITRA VATI', NULL),
(104, 'product', 'LAXMI VILASA RASA', NULL),
(105, 'product', 'MAHALAXMI VILASA RASA', NULL),
(106, 'product', 'SUNIDRA ', NULL),
(107, 'product', 'RECTONE', NULL),
(108, 'product', 'ANARSHA', NULL),
(109, 'product', 'ARSHACARE', NULL),
(110, 'product', 'TANTUPASHANA VATI', NULL),
(111, 'product', 'UNMADA GAJAKESHARI RASA', NULL),
(112, 'product', 'GRAHANI KAPATA RASA', NULL),
(113, 'product', 'MYROLAX', NULL),
(114, 'product', 'ANULOMANA DS', NULL),
(115, 'product', 'STOAMATAB', NULL),
(116, 'product', 'PROLIFE', NULL),
(117, 'product', 'NEERI', NULL),
(118, 'product', 'STONEX', NULL),
(119, 'product', 'MULTITONE', NULL),
(120, 'product', 'DHATUVRUDDHI', NULL),
(121, 'product', 'PRAMEHIN', NULL),
(122, 'product', 'LIV 52 DS', NULL),
(123, 'product', 'KANKAYANA VATI', NULL),
(124, 'product', 'PILEX', NULL),
(125, 'product', 'ABHANA', NULL),
(126, 'product', 'SARPHAGANDHAGHANA VATI', NULL),
(127, 'product', 'KUSTAGNA VATI', NULL),
(128, 'product', 'TIKTAMRUTA', NULL),
(129, 'product', 'ARSHOGHNA VATI', NULL),
(130, 'mfg', 'BHIDHYANATH', '125'),
(131, 'mfg', 'SWADESHI', '156'),
(132, 'mfg', 'DHOOTAPAPESHWAR', '5689'),
(133, 'mfg', 'ARYAVAIDHYA PHARMACY', '56792'),
(134, 'mfg', 'ALWAS PHARMACY', '2598'),
(135, 'mfg', 'PENTACARE', '56421'),
(136, 'mfg', 'CHARAKA', '8913'),
(137, 'group', 'VATI', NULL),
(138, 'mfg', 'ZANDU', '64123'),
(139, 'group', 'ASAVA', NULL),
(140, 'mfg', 'CHAITANYA PHARMACY', '32468'),
(141, 'mfg', 'NAGARJUNA', '3649'),
(142, 'mfg', 'ARYAVAIDHYA SHALA', '7619'),
(143, 'group', 'ARISTA', NULL),
(144, 'group', 'DROPS', NULL),
(145, 'group', 'LEHYA', NULL),
(146, 'group', 'CHOORNA', NULL),
(147, 'group', 'BHASMA', NULL),
(148, 'group', 'GRUTA', NULL),
(149, 'group', 'OINTMENT', NULL),
(150, 'group', 'KASHAYA', NULL),
(151, 'category', 'VATI', NULL),
(152, 'category', 'ASAVA', NULL),
(153, 'category', 'ARISTA', NULL),
(154, 'category', 'ASAVA', NULL),
(155, 'category', 'DROPS', NULL),
(156, 'category', 'LEHYA', NULL),
(157, 'category', 'CHOORNA', NULL),
(158, 'category', 'BHASMA', NULL),
(159, 'category', 'GRUTA', NULL),
(160, 'category', 'OINTMENT', NULL),
(161, 'category', 'KASHAYA', NULL),
(162, 'group', 'TABLET', NULL),
(163, 'group', 'SYRUP', NULL),
(164, 'group', 'INJECTION', NULL),
(165, 'group', 'CAPSULES', NULL),
(166, 'group', 'SUPOOSITORS', NULL),
(167, 'group', 'SPRAY', NULL),
(168, 'group', 'LINAMENT', NULL),
(169, 'category', 'TABLET', NULL),
(170, 'category', 'SYRUP', NULL),
(171, 'category', 'INJECTION', NULL),
(172, 'category', 'CAPSULES', NULL),
(173, 'category', 'SUPPOSITORS', NULL),
(174, 'category', 'SPRAY', NULL),
(175, 'category', 'LINAMENT', NULL),
(179, 'supplier', 'xyz', NULL),
(198, 'mfg', 'SG PHYATOPHARMA', '3679'),
(215, 'mfg', 'HIMALAYA PHARMA', '4593'),
(221, 'supplier', 'DHANWANTARI PHARMA', NULL),
(233, 'mfg', 'BAL PHARMA', '5986'),
(235, 'mfg', 'BAYIR CHEMICALS', '3489'),
(237, 'mfg', 'SAGAR PHARMACEUTICALS', '2896'),
(238, 'product', 'ANULOMANA DS', 'extra'),
(239, 'product', 'STOMATAB', ''),
(240, 'product', 'PROLIFE', ''),
(241, 'product', 'NEERI', ''),
(242, 'product', 'STONEX', ''),
(243, 'product', 'MULTITONE', ''),
(244, 'product', 'DHATUVRUDDI', ''),
(245, 'product', 'PRAMEHIN', ''),
(246, 'product', 'LIV 52 DS', ''),
(247, 'product', 'KANKAYANA VATI', ''),
(248, 'product', 'PILEX', ''),
(249, 'product', 'SARPHAGANDHA GHANA VATI', ''),
(250, 'product', 'ABHANA', ''),
(251, 'product', 'KUSTAGHNA VATI', ''),
(252, 'product', 'TIKTAMRUTA', ''),
(253, 'product', 'ARSHOGHNA ', ''),
(254, 'product', 'SAHACHARADI KASHAYA', ''),
(255, 'product', 'TRIPHALA KASHAYA', ''),
(256, 'product', 'DASHAMOOLA KASHAYA', ''),
(257, 'product', 'RASNAERANADADI KASHAYA', ''),
(258, 'product', 'MAHARASNADI KASHAYA', ''),
(259, 'product', 'MANJISTADI KASHAYA', ''),
(260, 'product', 'MAHAMANJISTADI KASHAYA', ''),
(261, 'product', 'ASTAVARGA KASHAYA', ''),
(262, 'product', 'RASNADI KASHAYA', ''),
(263, 'product', 'GUGGULU TIKTAKA KASHAYA', ''),
(264, 'product', 'TIKTA KASHAYA', ''),
(265, 'product', 'PANCHATIKTA KASHAYA', ''),
(266, 'product', 'MAHATIKTAKA KASHAYA', ''),
(267, 'product', 'GANDHARVA HASTHYADI KASHAYA', ''),
(268, 'product', 'BRUNGASMALAKA KASHAYA', ''),
(269, 'product', 'AMRUTOTTARA KASHAYA', ''),
(270, 'product', 'BRUHATYADI KASHAYA', ''),
(271, 'product', 'CHIRABILWADI KASHAYA', ''),
(272, 'product', 'DHANADHANYADI KASHAYA', ''),
(273, 'product', 'DHANWANTARAM KASHAYA', ''),
(274, 'product', 'GOKSHURADI KWATHA', ''),
(275, 'product', 'JIVANTHYADI KASHAYA', ''),
(276, 'product', 'MULAKADI KASHAY', ''),
(277, 'product', 'NIMBHADI KASHAYA', ''),
(278, 'product', 'PATHYADI KHADA', ''),
(279, 'product', 'PATOLADI KWATHA', ''),
(280, 'product', 'PUNARNAVADI KWATHA', ''),
(281, 'product', 'VARUNADI KASHAYA', ''),
(282, 'product', 'BRAHMI GRUTA', ''),
(283, 'product', 'PANCHATIKTA GRUTA', ''),
(284, 'product', 'MAHATIKTAKA GRUTA', ''),
(285, 'product', 'GUGGULUTIKTAKA GRUTA', ''),
(286, 'product', 'INDUKANTA GRUTA', ''),
(287, 'product', 'SUKUMARA GRUTA', ''),
(288, 'product', 'VIDARYADI GRUTA', ''),
(289, 'product', 'PANCHAGAVYAGRUTA', ''),
(290, 'product', 'TRIPHALA GRUTA', ''),
(291, 'product', 'PHALA GRUTA', ''),
(292, 'product', 'PIPPALYADI GRUTA', ''),
(293, 'product', 'KANTAKARI GRUTA', ''),
(294, 'product', 'KALYANAKA GRUTA', ''),
(295, 'product', 'MAHAKALYANAKA GRITA', ''),
(296, 'product', 'JIVANTYADI GRUTA', ''),
(297, 'product', 'DASHAMOOLA GRUTA', ''),
(298, 'product', 'AMRUTAPRASHA GRUTA', ''),
(299, 'product', 'ABHAYRISTA', ''),
(300, 'product', 'DASHAMOOLARISTA', ''),
(301, 'product', 'ASHOKARISTA', ''),
(302, 'product', 'SARASWATARISTA', ''),
(303, 'product', 'KANAKASAVA', ''),
(304, 'product', 'PUSKARAMOOLASAVA', ''),
(305, 'product', 'ASHWAGANDHARISTA', ''),
(306, 'product', 'BALARISTA', ''),
(307, 'product', 'BRUNGARJASAVA', ''),
(308, 'product', 'DRAKSHARISTA', ''),
(309, 'product', 'JEERAKADHYARISTA', ''),
(310, 'product', 'KAROORASAVA', ''),
(311, 'product', 'KHADIRARISTA', ''),
(312, 'product', 'KUMARYASAVA', ''),
(313, 'product', 'LOHASAVA', ''),
(314, 'product', 'KUTAJARISTA', ''),
(315, 'product', 'KUTAJAMUSTAKARISTA', ''),
(316, 'product', 'PATRANGASAVA', ''),
(317, 'product', 'PIPPALYASAVA', ''),
(318, 'product', 'ROHITAKARISTA', ''),
(319, 'product', 'SARIVADHYASAVA', ''),
(320, 'product', 'CHANDANASAVA', ''),
(321, 'product', 'USHEERASAVA', ''),
(322, 'product', 'VASAKASAVA', ''),
(323, 'product', 'VIDANGARISTA', ''),
(324, 'product', 'SITOPALADI CHOORNA', ''),
(325, 'product', 'TALISADI CHOORNA', ''),
(326, 'product', 'AMALAKICHOORNA', ''),
(327, 'product', 'AVIPATTIKARA CHOORNA', ''),
(328, 'product', 'HINGWASTAKA CHOORNA', ''),
(329, 'product', 'GANGHADHARA CHOORNA', ''),
(330, 'product', 'DADIMASTAKA CHOORNA', ''),
(331, 'product', 'ELADI CHOORNA', ''),
(332, 'product', 'HARIDRAKHANDA', ''),
(333, 'product', 'JATIPHALA CHOORNA', ''),
(334, 'product', 'KALYANAKA AVALEHA CHOORNA', ''),
(335, 'product', 'NARAYANA CHOORNA', ''),
(336, 'product', 'NIMBADI CHOORNA', ''),
(337, 'product', 'PUSHYANAGA CHOORNA', ''),
(338, 'product', 'SHATAVARYADI GRANULES', ''),
(339, 'product', 'SUDARSHANA CHOORNA', ''),
(340, 'product', 'TRIPHALA CHOORNA', ''),
(341, 'product', 'YAVANISHADAVA CHOORNA', ''),
(342, 'product', 'DERMACID ', ''),
(343, 'product', 'DERMEX', ''),
(344, 'product', 'KANAKA LEPA', ''),
(345, 'product', 'MYOSTAL OINT', ''),
(346, 'product', 'SHALLAKI PLUS', ''),
(347, 'product', 'SHALLAKI LINAMENT', ''),
(348, 'product', 'DASHANGA LEPA', ''),
(349, 'product', 'SINDURADI LEPA', ''),
(350, 'product', 'AVALGUJADI LEPA', ''),
(351, 'product', 'ROPANI OINT', ''),
(352, 'product', 'KUNKUMADI LEPA', ''),
(353, 'product', 'SHOTHAGNA LEPA', ''),
(354, 'product', 'MUKHADOOSHIHARA LEPA', ''),
(355, 'product', 'FESTIVE E', ''),
(356, 'product', 'FESTIVE D', ''),
(357, 'product', 'CEFLOX E', ''),
(358, 'product', ' CEFLOX D', ''),
(359, 'product', 'WOFLOX D', ''),
(360, 'product', 'UNIDINE POWDER', ''),
(361, 'product', 'WOMETRO', ''),
(362, 'product', 'Q MAX OZ', ''),
(363, 'product', 'L DOPER', ''),
(364, 'product', 'ZENLOC ', ''),
(365, 'product', 'B CO PAC ', ''),
(366, 'product', 'NODARD PLUS', ''),
(367, 'product', 'WOFLOX OZ', ''),
(368, 'product', 'LOVOLKEM', ''),
(369, 'product', 'MULTIPREX', ''),
(370, 'product', 'NASELIN SPRAY', ''),
(371, 'product', 'NIRCIP', ''),
(372, 'product', 'COTTON WOOL IP', ''),
(373, 'product', 'PILES CURE', ''),
(374, 'product', 'MAFENAR SPAS', ''),
(375, 'product', 'DERIPHYLLIN', ''),
(376, 'product', 'ALPHA B12', ''),
(377, 'product', 'ALPHA CPM', ''),
(378, 'product', 'RANIVIN', ''),
(379, 'product', 'ALPHAMET', ''),
(380, 'product', 'MEDRAGAN', ''),
(381, 'product', 'DICLOVERON', ''),
(382, 'product', 'ALPHADEXA', ''),
(383, 'product', 'ALPHAPLEX', ''),
(384, 'product', 'ALPHAGEN', ''),
(385, 'product', 'ONDITRON', ''),
(386, 'product', 'VITCOFOL', ''),
(387, 'product', 'RANIPEP', ''),
(388, 'product', 'AVIL', ''),
(389, 'product', 'REACTIN PLUS', ''),
(390, 'product', 'HEXAMOX', ''),
(391, 'product', 'CITZER COLD', ''),
(392, 'product', 'ACEFLAM P', ''),
(393, 'product', 'ACEFLAM SR', ''),
(394, 'product', 'SCALP VEIN 23 G', ''),
(395, 'product', 'SCALP VEIN 22 G', ''),
(396, 'product', 'AVIL AMP', ''),
(397, 'product', 'SAFTI URINE BAGS', ''),
(398, 'product', 'FOLLIES CATHETOR 16 G', ''),
(399, 'product', 'ROLLED BANDAGE 150CMX3', ''),
(400, 'product', 'GLOVES 7.5', ''),
(401, 'product', 'DISPOVAN SYRINGE 5ML', ''),
(402, 'product', 'DISPOVAN SYRINGE 2ML', ''),
(403, 'product', 'HYDROZEN PEROXIDE', ''),
(404, 'product', 'SURGICAL SPIRIT', ''),
(405, 'product', 'CIPLADINE SOLUTION', ''),
(406, 'product', 'IV SET', ''),
(407, 'product', 'NASOCEF 1GM', ''),
(408, 'product', 'NASOCEF S 1.5GM', ''),
(409, 'product', 'MEDGRIP 10 CM', ''),
(410, 'product', 'AMIJECT ', ''),
(411, 'product', 'ATROPIN', ''),
(412, 'product', 'MAEDITAPE', ''),
(413, 'product', 'POVIKEM OINT', ''),
(414, 'product', 'RAPID GEL', ''),
(415, 'product', 'WOCANE 2%', ''),
(416, 'product', 'OMPRAZ NOVA ', ''),
(417, 'product', 'HANSAPLAST', ''),
(418, 'product', 'DNS ', ''),
(419, 'product', 'RL', ''),
(420, 'product', 'NS', ''),
(421, 'product', 'DEXTROSE', ''),
(422, 'product', 'KETOKEM SHAMPOO', ''),
(423, 'product', 'SPINAL NEEDLE NO 22', ''),
(424, 'product', 'OMEZ', ''),
(425, 'product', 'CALMPOSE', ''),
(426, 'product', 'PERINORM', ''),
(427, 'product', 'XYLOCAINE', ''),
(428, 'product', 'MARICHYADI TAILA', NULL),
(429, 'product', 'PANCHAMRUTA PARPATI', NULL),
(430, 'product', 'BILVADI GULIKA ', NULL),
(431, 'product', 'MEDHOHARA KASHAYA', NULL),
(432, 'product', 'VATSAKADI KASHAYA', NULL),
(433, 'product', 'GOMUTRA HAREETAKI', NULL),
(434, 'product', 'CHITRAKA HAREETAKI', NULL),
(435, 'product', 'PANCHAMRUTA PARPATI', NULL),
(436, 'product', 'PANCHAMRUTA RASA', NULL),
(437, 'product', 'TRIKATU CHOORNA', NULL),
(438, 'product', 'BRAHMA RASAYANA', NULL),
(439, 'product', 'KELSILOHA', NULL),
(440, 'product', 'LODRASAVA', NULL),
(441, 'product', 'MAHACEF ', NULL),
(442, 'category', 'MANKIND PHARMA', NULL),
(443, 'mfg', 'MANKIND', '12345'),
(444, 'product', 'ACILOC', NULL),
(445, 'product', 'JYATHYADI TAILA', NULL),
(446, 'product', 'ARAGWADA KASHAYA', NULL),
(447, 'product', 'NITYANANDA RASA', NULL),
(448, 'product', 'NITYANANDA RASA', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `medicine_frequency`
--
ALTER TABLE `medicine_frequency`
  ADD PRIMARY KEY (`med_frq_id`);

--
-- Indexes for table `product_master`
--
ALTER TABLE `product_master`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `purchase_entry`
--
ALTER TABLE `purchase_entry`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_return_temp`
--
ALTER TABLE `purchase_return_temp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_types_master`
--
ALTER TABLE `purchase_types_master`
  ADD PRIMARY KEY (`pt_id`);

--
-- Indexes for table `purchase_variables`
--
ALTER TABLE `purchase_variables`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `medicine_frequency`
--
ALTER TABLE `medicine_frequency`
  MODIFY `med_frq_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `product_master`
--
ALTER TABLE `product_master`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `purchase_entry`
--
ALTER TABLE `purchase_entry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_return_temp`
--
ALTER TABLE `purchase_return_temp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_types_master`
--
ALTER TABLE `purchase_types_master`
  MODIFY `pt_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `purchase_variables`
--
ALTER TABLE `purchase_variables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=449;
COMMIT;