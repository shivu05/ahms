-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 29, 2025 at 04:53 PM
-- Server version: 10.11.11-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vhms_mrn_2025`
--

-- --------------------------------------------------------

--
-- Table structure for table `agnikarma_opd_ipd_register`
--

CREATE TABLE `agnikarma_opd_ipd_register` (
  `id` int(11) NOT NULL,
  `opd_no` int(11) DEFAULT NULL,
  `ipd_no` int(11) DEFAULT NULL,
  `treat_id` int(11) DEFAULT NULL,
  `ref_date` date DEFAULT NULL,
  `doctor_name` varchar(100) DEFAULT NULL,
  `treatment_notes` text DEFAULT NULL,
  `last_updates` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_prescription`
--

CREATE TABLE `app_prescription` (
  `id` int(11) NOT NULL,
  `pid` varchar(45) DEFAULT NULL,
  `product` varchar(45) DEFAULT NULL,
  `qty` varchar(45) DEFAULT NULL,
  `rate` varchar(45) DEFAULT NULL,
  `discount` varchar(45) DEFAULT NULL,
  `vat` varchar(45) DEFAULT NULL,
  `totalamt` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `archived_data`
--

CREATE TABLE `archived_data` (
  `id` int(11) NOT NULL,
  `db_name` varchar(45) DEFAULT NULL,
  `status` varchar(45) DEFAULT 'active',
  `name` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `autoclave_register`
--

CREATE TABLE `autoclave_register` (
  `id` int(11) NOT NULL,
  `DrumNo` varchar(50) DEFAULT NULL,
  `DrumStartTime` datetime DEFAULT NULL,
  `DrumEndTime` datetime DEFAULT NULL,
  `SupervisorName` varchar(150) DEFAULT NULL,
  `Remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bed_details`
--

CREATE TABLE `bed_details` (
  `id` int(11) NOT NULL,
  `OpdNo` varchar(45) DEFAULT NULL,
  `department` varchar(45) DEFAULT NULL,
  `bedno` varchar(45) DEFAULT NULL,
  `bedstatus` varchar(45) DEFAULT 'available',
  `wardno` varchar(45) DEFAULT NULL,
  `treatId` varchar(45) DEFAULT NULL,
  `IpNo` varchar(45) DEFAULT NULL,
  `bed_category` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bill_perticulars`
--

CREATE TABLE `bill_perticulars` (
  `bp_id` int(10) UNSIGNED NOT NULL,
  `bsg_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `perticulars` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bill_service_group`
--

CREATE TABLE `bill_service_group` (
  `bsg_id` int(10) UNSIGNED NOT NULL,
  `bsg_name` varchar(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `birthregistery`
--

CREATE TABLE `birthregistery` (
  `ID` int(11) NOT NULL,
  `OpdNo` varchar(256) DEFAULT NULL,
  `deliveryDetail` varchar(256) DEFAULT NULL,
  `babyBirthDate` varchar(256) DEFAULT NULL,
  `babyWeight` varchar(256) DEFAULT NULL,
  `treatby` varchar(256) DEFAULT NULL,
  `babygender` varchar(45) DEFAULT NULL,
  `fatherName` varchar(45) DEFAULT NULL,
  `motherblood` varchar(45) DEFAULT NULL,
  `anaesthetic` varchar(45) DEFAULT NULL,
  `deliverytype` varchar(45) DEFAULT NULL,
  `treatId` varchar(45) DEFAULT NULL,
  `babyblood` varchar(45) DEFAULT NULL,
  `birthtime` varchar(45) DEFAULT NULL,
  `anesthesia_type` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` int(11) NOT NULL,
  `name` varchar(500) NOT NULL,
  `desc` text DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE `config` (
  `id` int(10) UNSIGNED NOT NULL,
  `college_id` varchar(20) DEFAULT NULL,
  `college_name` text DEFAULT NULL,
  `place` varchar(70) NOT NULL,
  `printing_style` varchar(1) NOT NULL,
  `logo` varchar(50) DEFAULT NULL,
  `edit_flag` int(1) NOT NULL DEFAULT 0,
  `admin_email` varchar(45) DEFAULT NULL,
  `logo_img` longblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `config_variables`
--

CREATE TABLE `config_variables` (
  `config_var_id` int(11) NOT NULL,
  `config_var_name` varchar(250) NOT NULL,
  `config_var_value` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cupping_opd_ipd_register`
--

CREATE TABLE `cupping_opd_ipd_register` (
  `id` int(11) NOT NULL,
  `opd_no` int(11) DEFAULT NULL,
  `ipd_no` int(11) DEFAULT NULL,
  `treat_id` int(11) DEFAULT NULL,
  `ref_date` date DEFAULT NULL,
  `doctor_name` varchar(100) DEFAULT NULL,
  `type_of_cupping` varchar(100) DEFAULT NULL,
  `site_of_application` varchar(100) DEFAULT NULL,
  `no_of_cups_used` int(11) DEFAULT NULL,
  `treatment_notes` text DEFAULT NULL,
  `last_updates` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deptper`
--

CREATE TABLE `deptper` (
  `ID` int(11) NOT NULL,
  `department` varchar(50) DEFAULT NULL,
  `percentage` varchar(50) DEFAULT NULL,
  `dept_unique_code` varchar(50) NOT NULL,
  `bed_count` int(11) DEFAULT NULL,
  `ref_room` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `diagnosis`
--

CREATE TABLE `diagnosis` (
  `id` int(11) NOT NULL,
  `diagnosis_name` varchar(500) NOT NULL,
  `diagnosis_desc` text NOT NULL,
  `InActive` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `diet_register`
--

CREATE TABLE `diet_register` (
  `id` int(11) NOT NULL,
  `ipd_no` int(11) DEFAULT NULL,
  `opd_no` int(11) DEFAULT NULL,
  `treat_id` int(11) DEFAULT NULL,
  `morning` varchar(100) DEFAULT NULL,
  `after_noon` varchar(100) DEFAULT NULL,
  `evening` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `doctortype` varchar(45) DEFAULT NULL,
  `doctorname` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctorsduty`
--

CREATE TABLE `doctorsduty` (
  `id` int(11) NOT NULL,
  `doc_id` int(10) UNSIGNED DEFAULT NULL,
  `day` varchar(45) DEFAULT NULL,
  `added_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctor_patient_info`
--

CREATE TABLE `doctor_patient_info` (
  `sys_no` int(11) NOT NULL,
  `doctor_guid` text DEFAULT NULL,
  `doctor_name` varchar(100) DEFAULT NULL,
  `doctor_mobile` varchar(12) DEFAULT NULL,
  `clinic_name` varchar(150) DEFAULT NULL,
  `patient_uid` varchar(100) DEFAULT NULL,
  `patient_name` varchar(100) DEFAULT NULL,
  `patient_age` int(3) DEFAULT NULL,
  `patient_mobile` varchar(12) DEFAULT NULL,
  `added_date` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ecgregistery`
--

CREATE TABLE `ecgregistery` (
  `ID` int(11) NOT NULL,
  `OpdNo` int(11) DEFAULT NULL,
  `refDocName` varchar(256) DEFAULT NULL,
  `ecgDate` varchar(25) DEFAULT NULL,
  `treatId` int(11) DEFAULT NULL,
  `refDate` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fumigation_register`
--

CREATE TABLE `fumigation_register` (
  `id` int(11) NOT NULL,
  `fumigation_mothod` varchar(200) DEFAULT NULL,
  `chemical_used` varchar(250) DEFAULT NULL,
  `start_time` varchar(45) DEFAULT NULL,
  `end_time` varchar(45) DEFAULT NULL,
  `ot_number` varchar(45) DEFAULT NULL,
  `neutralization` varchar(200) DEFAULT NULL,
  `superviser_name` varchar(100) DEFAULT NULL,
  `remarks` varchar(200) DEFAULT NULL,
  `f_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `indent`
--

CREATE TABLE `indent` (
  `id` int(11) NOT NULL,
  `ipdno` int(11) DEFAULT NULL,
  `opdno` int(11) DEFAULT NULL,
  `indentdate` varchar(45) DEFAULT NULL,
  `product` varchar(45) DEFAULT NULL,
  `morning` varchar(45) DEFAULT NULL,
  `afternoon` varchar(45) DEFAULT NULL,
  `night` varchar(45) DEFAULT NULL,
  `totalqty` varchar(45) DEFAULT NULL,
  `treatid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inpatientdetails`
--

CREATE TABLE `inpatientdetails` (
  `IpNo` int(11) NOT NULL,
  `OpdNo` int(11) DEFAULT NULL,
  `deptOpdNo` varchar(256) DEFAULT NULL,
  `FName` varchar(256) DEFAULT NULL,
  `Age` varchar(256) DEFAULT NULL,
  `Gender` varchar(256) DEFAULT NULL,
  `department` varchar(256) DEFAULT NULL,
  `WardNo` varchar(256) DEFAULT NULL,
  `BedNo` varchar(256) DEFAULT NULL,
  `diagnosis` varchar(256) DEFAULT NULL,
  `DoAdmission` varchar(256) DEFAULT NULL,
  `DoDischarge` varchar(256) DEFAULT '--',
  `DischargeNotes` varchar(256) DEFAULT NULL,
  `NofDays` varchar(256) DEFAULT NULL,
  `Doctor` varchar(256) DEFAULT NULL,
  `DischBy` varchar(256) DEFAULT NULL,
  `treatId` varchar(256) DEFAULT NULL,
  `status` varchar(45) DEFAULT 'stillin',
  `sid` int(10) UNSIGNED DEFAULT NULL,
  `admit_time` varchar(45) DEFAULT NULL,
  `discharge_time` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `ipdtreatment`
--

CREATE TABLE `ipdtreatment` (
  `ID` int(11) NOT NULL,
  `ipdno` int(11) NOT NULL,
  `AddedBy` varchar(256) DEFAULT NULL,
  `Trtment` varchar(256) DEFAULT NULL,
  `diagnosis` varchar(256) DEFAULT NULL,
  `complaints` varchar(256) DEFAULT NULL,
  `department` varchar(256) DEFAULT NULL,
  `procedures` varchar(256) DEFAULT NULL,
  `notes` varchar(256) DEFAULT NULL,
  `attndedon` varchar(256) DEFAULT NULL,
  `status` varchar(45) DEFAULT 'nottreated'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `i_user_roles`
--

CREATE TABLE `i_user_roles` (
  `user_role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jaloukavacharana_opd_ipd_register`
--

CREATE TABLE `jaloukavacharana_opd_ipd_register` (
  `id` int(11) NOT NULL,
  `opd_no` int(11) NOT NULL,
  `ipd_no` int(11) DEFAULT NULL,
  `treat_id` int(11) DEFAULT NULL,
  `ref_date` date NOT NULL,
  `doctor_name` varchar(100) NOT NULL,
  `procedure_details` text DEFAULT NULL,
  `doctor_remarks` text DEFAULT NULL,
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kriyakalpa`
--

CREATE TABLE `kriyakalpa` (
  `id` int(11) NOT NULL,
  `OpdNo` int(11) DEFAULT NULL,
  `IpNo` int(11) DEFAULT NULL,
  `treat_id` int(11) DEFAULT NULL,
  `kriya_procedures` varchar(250) DEFAULT NULL,
  `kriya_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ksharsutraregistery`
--

CREATE TABLE `ksharsutraregistery` (
  `ID` int(11) NOT NULL,
  `OpdNo` int(11) DEFAULT NULL,
  `ksharsType` varchar(256) DEFAULT NULL,
  `ksharsDate` varchar(25) DEFAULT NULL,
  `treatId` int(11) DEFAULT NULL,
  `ksharaname` varchar(45) DEFAULT NULL,
  `surgeon` varchar(45) DEFAULT NULL,
  `asssurgeon` varchar(45) DEFAULT NULL,
  `anaesthetic` varchar(45) DEFAULT NULL,
  `anesthesia_type` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `labregistery`
--

CREATE TABLE `labregistery` (
  `ID` int(11) NOT NULL,
  `OpdNo` int(11) NOT NULL,
  `refDocName` varchar(100) DEFAULT NULL,
  `lab_test_cat` int(11) DEFAULT NULL,
  `lab_test_type` int(11) DEFAULT NULL,
  `testName` int(11) DEFAULT NULL,
  `testDate` varchar(10) DEFAULT NULL,
  `treatID` int(11) NOT NULL,
  `testrange` varchar(256) DEFAULT NULL,
  `testvalue` varchar(256) DEFAULT NULL,
  `labdisease` varchar(256) DEFAULT NULL,
  `tested_date` date DEFAULT NULL,
  `ipdno` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lab_categories`
--

CREATE TABLE `lab_categories` (
  `lab_cat_id` int(11) NOT NULL,
  `lab_cat_name` varchar(250) NOT NULL,
  `status` varchar(45) NOT NULL DEFAULT 'ACTIVE'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lab_investigations`
--

CREATE TABLE `lab_investigations` (
  `lab_inv_id` int(11) NOT NULL,
  `lab_inv_name` varchar(250) NOT NULL,
  `lab_test_id` int(11) NOT NULL,
  `lab_test_reference` varchar(250) DEFAULT NULL,
  `test_status` varchar(45) DEFAULT 'ACTIVE'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lab_reference`
--

CREATE TABLE `lab_reference` (
  `ID` int(11) NOT NULL DEFAULT 0,
  `OpdNo` int(11) NOT NULL,
  `refDocName` varchar(100) DEFAULT NULL,
  `lab_test_cat` int(11) DEFAULT NULL,
  `lab_test_type` int(11) DEFAULT NULL,
  `testName` int(11) DEFAULT NULL,
  `testDate` varchar(10) DEFAULT NULL,
  `treatID` int(11) NOT NULL,
  `testrange` varchar(256) DEFAULT NULL,
  `testvalue` varchar(256) DEFAULT NULL,
  `labdisease` varchar(256) DEFAULT NULL,
  `tested_date` date DEFAULT NULL,
  `ipdno` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lab_tests`
--

CREATE TABLE `lab_tests` (
  `lab_test_id` int(11) NOT NULL,
  `lab_test_name` varchar(250) NOT NULL,
  `lab_cat_id` int(11) DEFAULT NULL,
  `status` varchar(45) NOT NULL DEFAULT 'ACTIVE'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `master_occupation`
--

CREATE TABLE `master_occupation` (
  `occupation` varchar(256) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `master_other_procedures`
--

CREATE TABLE `master_other_procedures` (
  `id` int(11) NOT NULL,
  `name` varchar(250) DEFAULT NULL,
  `status` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `master_panchakarma_procedures`
--

CREATE TABLE `master_panchakarma_procedures` (
  `id` int(11) NOT NULL,
  `proc_name` varchar(200) NOT NULL,
  `last_modified_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `master_panchakarma_sub_procedures`
--

CREATE TABLE `master_panchakarma_sub_procedures` (
  `id` int(11) NOT NULL,
  `procecure_id` int(11) NOT NULL,
  `sub_proc_name` varchar(200) NOT NULL,
  `no_of_treatment_days` int(11) DEFAULT NULL,
  `last_modified_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `master_physiotheraphy`
--

CREATE TABLE `master_physiotheraphy` (
  `id` int(11) NOT NULL,
  `name` varchar(250) DEFAULT NULL,
  `status` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medicine_frequency`
--

CREATE TABLE `medicine_frequency` (
  `id` int(10) UNSIGNED NOT NULL,
  `freq` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `months_list`
--

CREATE TABLE `months_list` (
  `id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `month_list`
--

CREATE TABLE `month_list` (
  `id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oldtable`
--

CREATE TABLE `oldtable` (
  `ID` int(11) NOT NULL,
  `FirstName` varchar(256) DEFAULT NULL,
  `MidName` varchar(256) DEFAULT NULL,
  `LastName` varchar(256) DEFAULT NULL,
  `Age` varchar(256) DEFAULT NULL,
  `gender` varchar(256) DEFAULT NULL,
  `occupation` varchar(256) DEFAULT NULL,
  `address` varchar(256) DEFAULT NULL,
  `city` varchar(256) DEFAULT NULL,
  `Mobileno` varchar(256) DEFAULT NULL,
  `diagnosis` varchar(256) DEFAULT NULL,
  `complaints` varchar(256) DEFAULT NULL,
  `department` varchar(256) DEFAULT NULL,
  `procedures` varchar(256) DEFAULT NULL,
  `Trtment` varchar(256) DEFAULT NULL,
  `notes` varchar(256) DEFAULT NULL,
  `AddedBy` varchar(256) DEFAULT NULL,
  `entrydate` varchar(256) DEFAULT NULL,
  `medicines` varchar(256) DEFAULT NULL,
  `sub_dept` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `other_procedures_treatments`
--

CREATE TABLE `other_procedures_treatments` (
  `id` int(11) NOT NULL,
  `OpdNo` int(11) DEFAULT NULL,
  `IpNo` int(11) DEFAULT NULL,
  `therapy_name` varchar(250) DEFAULT NULL,
  `physician` varchar(100) DEFAULT NULL,
  `treat_id` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `packagin_type`
--

CREATE TABLE `packagin_type` (
  `pakg_id` int(11) NOT NULL,
  `pakg_name` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `panchakarma_ref`
--

CREATE TABLE `panchakarma_ref` (
  `id` int(11) NOT NULL,
  `disease` varchar(45) DEFAULT NULL,
  `treatment` varchar(45) DEFAULT NULL,
  `procedure` tinytext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `panchaprocedure`
--

CREATE TABLE `panchaprocedure` (
  `id` int(11) NOT NULL,
  `opdno` int(11) DEFAULT NULL,
  `disease` varchar(100) DEFAULT NULL,
  `treatment` varchar(100) DEFAULT NULL,
  `procedure` tinytext DEFAULT NULL,
  `date` varchar(45) DEFAULT NULL,
  `docname` varchar(45) DEFAULT NULL,
  `treatid` int(11) DEFAULT NULL,
  `proc_end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patientdata`
--

CREATE TABLE `patientdata` (
  `OpdNo` int(11) NOT NULL,
  `UHID` varchar(50) DEFAULT NULL,
  `FirstName` varchar(256) DEFAULT NULL,
  `MidName` varchar(256) DEFAULT NULL,
  `LastName` varchar(256) DEFAULT NULL,
  `Age` varchar(256) DEFAULT NULL,
  `gender` varchar(256) DEFAULT NULL,
  `occupation` varchar(256) DEFAULT NULL,
  `address` varchar(256) DEFAULT NULL,
  `city` varchar(256) DEFAULT NULL,
  `AddedBy` varchar(256) DEFAULT NULL,
  `entrydate` varchar(45) DEFAULT NULL,
  `dept` varchar(45) DEFAULT NULL,
  `mob` varchar(45) DEFAULT NULL,
  `deptOpdNo` int(11) DEFAULT 0,
  `sub_dept` varchar(100) DEFAULT NULL,
  `sid` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=REDUNDANT;

-- --------------------------------------------------------

--
-- Table structure for table `patient_bill_details`
--

CREATE TABLE `patient_bill_details` (
  `bill_id` int(250) NOT NULL,
  `bill_no` varchar(256) DEFAULT NULL,
  `bill_opd_no` varchar(256) DEFAULT NULL,
  `bill_ipd_no` varchar(256) DEFAULT NULL,
  `bill_adv_amt` int(11) DEFAULT NULL,
  `bill_serv_group` varchar(256) DEFAULT NULL,
  `bill_particulars` varchar(256) DEFAULT NULL,
  `bill_days` int(11) DEFAULT NULL,
  `bill_charges` int(11) DEFAULT NULL,
  `bill_amt` int(11) DEFAULT NULL,
  `bill_discount` varchar(256) DEFAULT NULL,
  `bill_treat_id` varchar(256) DEFAULT NULL,
  `bill_date` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `perm_master`
--

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
  `last_updated_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `physiotherapy_treatments`
--

CREATE TABLE `physiotherapy_treatments` (
  `id` int(11) NOT NULL,
  `OpdNo` int(11) DEFAULT NULL,
  `IpNo` int(11) DEFAULT NULL,
  `therapy_name` varchar(250) DEFAULT NULL,
  `physician` varchar(100) DEFAULT NULL,
  `treat_id` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prescribed_drug_info`
--

CREATE TABLE `prescribed_drug_info` (
  `drug_id` int(11) NOT NULL,
  `sys_no` int(255) DEFAULT NULL,
  `prescription_guid` tinytext DEFAULT NULL,
  `drug_name` tinytext DEFAULT NULL,
  `drug_qty` int(11) DEFAULT NULL,
  `drug_dose` varchar(20) DEFAULT NULL,
  `prescribed_date` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prescription_details`
--

CREATE TABLE `prescription_details` (
  `presc_id` int(11) NOT NULL,
  `OpdNo` int(11) DEFAULT NULL,
  `treat_id` int(11) DEFAULT NULL,
  `IpNo` int(11) DEFAULT NULL,
  `medicine_name` varchar(250) DEFAULT NULL,
  `frequency` varchar(45) DEFAULT NULL,
  `no_of_days` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_details`
--

CREATE TABLE `product_details` (
  `id` int(11) NOT NULL,
  `supplier` varchar(45) DEFAULT NULL,
  `productname` varchar(45) DEFAULT NULL,
  `batch` varchar(45) DEFAULT NULL,
  `packing` varchar(45) DEFAULT NULL,
  `mfg` varchar(45) DEFAULT NULL,
  `group` varchar(45) DEFAULT NULL,
  `category` varchar(45) DEFAULT NULL,
  `expirydate` varchar(45) DEFAULT NULL,
  `vat` varchar(45) DEFAULT NULL,
  `minstock` varchar(45) DEFAULT NULL,
  `ordlevel` varchar(45) DEFAULT NULL,
  `rack` varchar(45) DEFAULT NULL,
  `p_rate` varchar(45) DEFAULT NULL,
  `mrp` varchar(45) DEFAULT NULL,
  `mfgid` varchar(45) DEFAULT NULL,
  `supplier_id` varchar(45) DEFAULT NULL,
  `product_id` varchar(45) DEFAULT NULL,
  `pqty` varchar(50) NOT NULL,
  `fqty` varchar(50) NOT NULL,
  `discount` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_list`
--

CREATE TABLE `product_list` (
  `slno` int(11) NOT NULL,
  `product` varchar(250) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_master`
--

CREATE TABLE `product_master` (
  `product_id` int(11) NOT NULL,
  `product_unique_id` varchar(50) NOT NULL DEFAULT '',
  `product_master_id` int(11) NOT NULL,
  `product_batch` varchar(45) NOT NULL DEFAULT '',
  `supplier_id` int(11) NOT NULL,
  `packing_name` varchar(10) NOT NULL DEFAULT '',
  `product_mfg` varchar(100) NOT NULL DEFAULT '',
  `product_type` varchar(45) NOT NULL DEFAULT '',
  `product_group` varchar(200) DEFAULT NULL,
  `manifacture_date` date NOT NULL DEFAULT '0000-00-00',
  `exp_date` date NOT NULL DEFAULT '0000-00-00',
  `purchase_rate` float(8,2) NOT NULL DEFAULT 0.00,
  `mrp` float(8,2) NOT NULL DEFAULT 0.00,
  `sale_rate` float(8,2) NOT NULL,
  `vat` float(8,2) NOT NULL,
  `pack_type` varchar(45) DEFAULT NULL,
  `discount` float(8,2) NOT NULL DEFAULT 0.00,
  `reorder_point` int(11) NOT NULL DEFAULT 0,
  `order_level` int(11) DEFAULT NULL,
  `weight` varchar(45) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='medicine information';

-- --------------------------------------------------------

--
-- Table structure for table `purchase_entry`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_return_temp`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_types_master`
--

CREATE TABLE `purchase_types_master` (
  `pt_id` int(11) NOT NULL,
  `pt_desc` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_variables`
--

CREATE TABLE `purchase_variables` (
  `id` int(11) NOT NULL,
  `type` varchar(45) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `extrainfo` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `qrcodes`
--

CREATE TABLE `qrcodes` (
  `id` int(11) NOT NULL,
  `code` varchar(45) DEFAULT NULL,
  `status` varchar(45) DEFAULT 'NOT'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reference_panchakarma`
--

CREATE TABLE `reference_panchakarma` (
  `id` int(11) NOT NULL,
  `opdno` int(11) DEFAULT NULL,
  `disease` varchar(100) DEFAULT NULL,
  `treatment` varchar(100) DEFAULT NULL,
  `procedure` tinytext DEFAULT NULL,
  `date` varchar(45) DEFAULT NULL,
  `docname` varchar(45) DEFAULT NULL,
  `treatid` int(11) DEFAULT NULL,
  `proc_end_date` date DEFAULT NULL,
  `no_of_days` int(3) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reference_table`
--

CREATE TABLE `reference_table` (
  `ID` int(11) NOT NULL,
  `FirstName` varchar(256) DEFAULT NULL,
  `MidName` varchar(256) DEFAULT NULL,
  `LastName` varchar(256) DEFAULT NULL,
  `Age` varchar(256) DEFAULT NULL,
  `gender` varchar(256) DEFAULT NULL,
  `occupation` varchar(256) DEFAULT NULL,
  `address` varchar(256) DEFAULT NULL,
  `city` varchar(256) DEFAULT NULL,
  `Mobileno` varchar(256) DEFAULT NULL,
  `diagnosis` varchar(256) DEFAULT NULL,
  `complaints` varchar(256) DEFAULT NULL,
  `department` varchar(256) DEFAULT NULL,
  `procedures` varchar(256) DEFAULT NULL,
  `Trtment` varchar(256) DEFAULT NULL,
  `notes` varchar(256) DEFAULT NULL,
  `AddedBy` varchar(256) DEFAULT NULL,
  `entrydate` varchar(256) DEFAULT NULL,
  `medicines` varchar(45) DEFAULT NULL,
  `sub_dept` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reference_table_orgl`
--

CREATE TABLE `reference_table_orgl` (
  `ID` int(11) NOT NULL DEFAULT 0,
  `FirstName` varchar(256) DEFAULT NULL,
  `MidName` varchar(256) DEFAULT NULL,
  `LastName` varchar(256) DEFAULT NULL,
  `Age` varchar(256) DEFAULT NULL,
  `gender` varchar(256) DEFAULT NULL,
  `occupation` varchar(256) DEFAULT NULL,
  `address` varchar(256) DEFAULT NULL,
  `city` varchar(256) DEFAULT NULL,
  `Mobileno` varchar(256) DEFAULT NULL,
  `diagnosis` varchar(256) DEFAULT NULL,
  `complaints` varchar(256) DEFAULT NULL,
  `department` varchar(256) DEFAULT NULL,
  `procedures` varchar(256) DEFAULT NULL,
  `Trtment` varchar(256) DEFAULT NULL,
  `notes` varchar(256) DEFAULT NULL,
  `AddedBy` varchar(256) DEFAULT NULL,
  `entrydate` varchar(256) DEFAULT NULL,
  `medicines` varchar(45) DEFAULT NULL,
  `sub_dept` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ref_lab_reg_tab`
--

CREATE TABLE `ref_lab_reg_tab` (
  `lab_id` int(242) NOT NULL,
  `lab_disease` varchar(242) DEFAULT NULL,
  `lab_test` varchar(256) DEFAULT NULL,
  `lab_test_val` varchar(256) DEFAULT NULL,
  `lab_ref_range` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_master`
--

CREATE TABLE `role_master` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `role_name` varchar(45) NOT NULL,
  `role_code` varchar(45) NOT NULL,
  `last_updated_by` varchar(45) NOT NULL,
  `last_updated_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_perm`
--

CREATE TABLE `role_perm` (
  `role_perm_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `perm_id` int(11) NOT NULL,
  `status` varchar(10) NOT NULL,
  `last_updated_id` int(11) NOT NULL,
  `last_updated_date` datetime NOT NULL DEFAULT current_timestamp(),
  `access_perm` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_entry`
--

CREATE TABLE `sales_entry` (
  `id` int(11) NOT NULL,
  `opdno` int(11) DEFAULT NULL,
  `billno` varchar(45) DEFAULT NULL,
  `date` varchar(45) DEFAULT NULL,
  `ipdno` int(11) DEFAULT NULL,
  `product` tinytext DEFAULT NULL,
  `batch` varchar(45) DEFAULT NULL,
  `qty` varchar(45) DEFAULT NULL,
  `treat_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `price` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_return_temp`
--

CREATE TABLE `sales_return_temp` (
  `id` int(11) NOT NULL,
  `opdno` varchar(45) DEFAULT NULL,
  `billno` varchar(45) DEFAULT NULL,
  `date` varchar(45) DEFAULT NULL,
  `doctor` varchar(45) DEFAULT NULL,
  `ipdno` varchar(45) DEFAULT NULL,
  `product` varchar(45) DEFAULT NULL,
  `batch` varchar(45) DEFAULT NULL,
  `qty` varchar(45) DEFAULT NULL,
  `rate` varchar(45) DEFAULT NULL,
  `discount` varchar(45) DEFAULT NULL,
  `vat` varchar(45) DEFAULT NULL,
  `amount` varchar(45) DEFAULT NULL,
  `product_id` varchar(45) DEFAULT NULL,
  `user_id` varchar(45) DEFAULT NULL,
  `stock_id` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `samltp`
--

CREATE TABLE `samltp` (
  `said` int(8) NOT NULL,
  `satd` varchar(100) DEFAULT NULL,
  `sama` varchar(100) DEFAULT NULL,
  `sanp` varchar(100) DEFAULT NULL,
  `sclip` varchar(60) DEFAULT NULL,
  `last_access` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `servpart`
--

CREATE TABLE `servpart` (
  `id` int(11) NOT NULL,
  `type` varchar(45) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `siravyadana_opd_ipd_register`
--

CREATE TABLE `siravyadana_opd_ipd_register` (
  `id` int(11) NOT NULL,
  `opd_no` int(11) NOT NULL,
  `ipd_no` int(11) DEFAULT NULL,
  `treat_id` int(11) DEFAULT NULL,
  `ref_date` date NOT NULL,
  `doctor_name` varchar(100) NOT NULL,
  `procedure_details` text DEFAULT NULL,
  `doctor_remarks` text DEFAULT NULL,
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `id` int(11) NOT NULL,
  `supplier_id` varchar(45) DEFAULT NULL,
  `product` varchar(45) DEFAULT NULL,
  `batchno` varchar(45) DEFAULT NULL,
  `mhf` varchar(45) DEFAULT NULL,
  `pack` varchar(45) DEFAULT NULL,
  `cstock` int(11) DEFAULT NULL,
  `expdate` varchar(45) DEFAULT NULL,
  `price` varchar(45) DEFAULT NULL,
  `amount` varchar(45) DEFAULT NULL,
  `dmonths` varchar(45) DEFAULT NULL,
  `billno` varchar(45) DEFAULT NULL,
  `purchasetype` varchar(45) DEFAULT NULL,
  `vat` varchar(45) DEFAULT NULL,
  `refno` varchar(45) DEFAULT NULL,
  `date` varchar(45) DEFAULT NULL,
  `pgroup` varchar(45) DEFAULT NULL,
  `category` varchar(45) DEFAULT NULL,
  `product_id` varchar(45) DEFAULT NULL,
  `status` varchar(45) DEFAULT 'AVAIL'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sub_department`
--

CREATE TABLE `sub_department` (
  `sub_dept_id` int(10) UNSIGNED NOT NULL,
  `sub_dept_name` varchar(45) NOT NULL,
  `parent_dept_id` int(10) UNSIGNED NOT NULL,
  `last_updated_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `surgeryregistery`
--

CREATE TABLE `surgeryregistery` (
  `ID` int(11) NOT NULL,
  `OpdNo` int(11) DEFAULT NULL,
  `surgType` varchar(256) DEFAULT NULL,
  `surgName` varchar(256) DEFAULT NULL,
  `surgDate` varchar(256) DEFAULT NULL,
  `treatId` int(11) DEFAULT NULL,
  `anaesthetic` varchar(45) DEFAULT NULL,
  `asssurgeon` varchar(45) DEFAULT NULL,
  `surgeryname` varchar(45) DEFAULT NULL,
  `anesthesia_type` varchar(45) DEFAULT NULL,
  `ipdno` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `swarnaprashana`
--

CREATE TABLE `swarnaprashana` (
  `id` int(11) NOT NULL,
  `opd_no` int(11) DEFAULT NULL,
  `dept_opd` int(11) DEFAULT NULL,
  `treat_id` int(11) DEFAULT NULL,
  `date_month` varchar(45) DEFAULT NULL,
  `dose_time` varchar(45) DEFAULT NULL,
  `consultant` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `temp_bill`
--

CREATE TABLE `temp_bill` (
  `id` int(11) NOT NULL,
  `bill_no` varchar(45) DEFAULT NULL,
  `bill_opd_no` varchar(45) DEFAULT NULL,
  `bill_ipd_no` varchar(45) DEFAULT NULL,
  `bill_adv_amt` varchar(45) DEFAULT NULL,
  `bill_serv_group` varchar(45) DEFAULT NULL,
  `bill_particulars` varchar(45) DEFAULT NULL,
  `bill_days` varchar(45) DEFAULT NULL,
  `bill_charges` varchar(45) DEFAULT NULL,
  `bill_amt` varchar(45) DEFAULT NULL,
  `bill_discount` varchar(45) DEFAULT NULL,
  `bill_treat_id` varchar(45) DEFAULT NULL,
  `bill_date` varchar(45) DEFAULT NULL,
  `user_id` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `temp_purchase_entry`
--

CREATE TABLE `temp_purchase_entry` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `temp_sales_entry`
--

CREATE TABLE `temp_sales_entry` (
  `id` int(11) NOT NULL,
  `opdno` varchar(45) DEFAULT NULL,
  `billno` varchar(45) DEFAULT NULL,
  `date` varchar(45) DEFAULT NULL,
  `doctor` varchar(45) DEFAULT NULL,
  `ipdno` varchar(45) DEFAULT NULL,
  `product` varchar(45) DEFAULT NULL,
  `batch` varchar(45) DEFAULT NULL,
  `qty` varchar(45) DEFAULT NULL,
  `rate` varchar(45) DEFAULT NULL,
  `discount` varchar(45) DEFAULT NULL,
  `vat` varchar(45) DEFAULT NULL,
  `amount` varchar(45) DEFAULT NULL,
  `product_name` varchar(45) DEFAULT NULL,
  `user_id` varchar(45) DEFAULT NULL,
  `stock_id` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `treatmentdata`
--

CREATE TABLE `treatmentdata` (
  `ID` int(11) NOT NULL,
  `OpdNo` int(11) DEFAULT NULL,
  `deptOpdNo` int(11) DEFAULT NULL,
  `PatType` varchar(15) DEFAULT NULL,
  `AddedBy` varchar(30) DEFAULT NULL,
  `CameOn` varchar(25) DEFAULT NULL,
  `Trtment` varchar(256) DEFAULT NULL,
  `diagnosis` varchar(256) DEFAULT NULL,
  `complaints` varchar(256) DEFAULT NULL,
  `department` varchar(256) DEFAULT NULL,
  `procedures` varchar(256) DEFAULT NULL,
  `notes` varchar(256) DEFAULT NULL,
  `InOrOutPat` varchar(256) DEFAULT NULL,
  `attndedby` varchar(256) DEFAULT NULL,
  `attndedon` varchar(256) DEFAULT NULL,
  `monthly_sid` int(11) DEFAULT NULL,
  `sub_department` varchar(100) DEFAULT NULL,
  `medicines` varchar(256) DEFAULT NULL,
  `sequence` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Triggers `treatmentdata`
--
DELIMITER $$
CREATE TRIGGER `add_opd_sequence` BEFORE INSERT ON `treatmentdata` FOR EACH ROW BEGIN
SET @newsq = (SELECT COALESCE(MAX(sequence), 0) FROM treatmentdata);
SET NEW.sequence=@newsq+1;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `uhid_sequence`
--

CREATE TABLE `uhid_sequence` (
  `id` int(11) NOT NULL,
  `seq_date` date NOT NULL,
  `hospital_code` varchar(10) NOT NULL,
  `daily_seq` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

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
  `active` varchar(45) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `user_role_id` int(10) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `last_updated_by` int(10) UNSIGNED NOT NULL,
  `last_updated_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_validation`
--

CREATE TABLE `user_validation` (
  `val_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `link` text DEFAULT NULL,
  `val_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usgregistery`
--

CREATE TABLE `usgregistery` (
  `ID` int(11) NOT NULL,
  `OpdNo` int(11) DEFAULT NULL,
  `refDocName` varchar(100) DEFAULT NULL,
  `usgDate` varchar(25) DEFAULT NULL,
  `treatId` int(11) DEFAULT NULL,
  `refDate` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `week_days`
--

CREATE TABLE `week_days` (
  `week_id` int(10) UNSIGNED NOT NULL,
  `week_day` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wound_dressing_opd_ipd_register`
--

CREATE TABLE `wound_dressing_opd_ipd_register` (
  `id` int(11) NOT NULL,
  `opd_no` int(11) NOT NULL,
  `ipd_no` int(11) DEFAULT NULL,
  `treat_id` int(11) DEFAULT NULL,
  `ref_date` date NOT NULL,
  `wound_location` varchar(255) NOT NULL,
  `wound_type` varchar(255) NOT NULL,
  `dressing_material` text NOT NULL,
  `doctor_name` varchar(100) NOT NULL,
  `next_dressing_date` date DEFAULT NULL,
  `doctor_remarks` text DEFAULT NULL,
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `xrayregistery`
--

CREATE TABLE `xrayregistery` (
  `ID` int(11) NOT NULL,
  `OpdNo` int(11) DEFAULT NULL,
  `ipdno` int(11) DEFAULT NULL,
  `xrayNo` varchar(256) DEFAULT NULL,
  `partOfXray` varchar(256) DEFAULT NULL,
  `filmSize` varchar(256) DEFAULT NULL,
  `refDocName` varchar(256) DEFAULT NULL,
  `xrayDate` varchar(25) DEFAULT NULL,
  `treatID` int(11) DEFAULT NULL,
  `refDate` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `xray_ref`
--

CREATE TABLE `xray_ref` (
  `id` int(11) NOT NULL,
  `diesease` varchar(45) DEFAULT NULL,
  `xraypart` varchar(200) DEFAULT NULL,
  `filmsize` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agnikarma_opd_ipd_register`
--
ALTER TABLE `agnikarma_opd_ipd_register`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_prescription`
--
ALTER TABLE `app_prescription`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `archived_data`
--
ALTER TABLE `archived_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `autoclave_register`
--
ALTER TABLE `autoclave_register`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bed_details`
--
ALTER TABLE `bed_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bill_perticulars`
--
ALTER TABLE `bill_perticulars`
  ADD PRIMARY KEY (`bp_id`),
  ADD KEY `FK_bill_perticulars_1` (`bsg_id`);

--
-- Indexes for table `bill_service_group`
--
ALTER TABLE `bill_service_group`
  ADD PRIMARY KEY (`bsg_id`);

--
-- Indexes for table `birthregistery`
--
ALTER TABLE `birthregistery`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `config_variables`
--
ALTER TABLE `config_variables`
  ADD PRIMARY KEY (`config_var_id`);

--
-- Indexes for table `cupping_opd_ipd_register`
--
ALTER TABLE `cupping_opd_ipd_register`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deptper`
--
ALTER TABLE `deptper`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `diagnosis`
--
ALTER TABLE `diagnosis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `diet_register`
--
ALTER TABLE `diet_register`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctorsduty`
--
ALTER TABLE `doctorsduty`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctor_patient_info`
--
ALTER TABLE `doctor_patient_info`
  ADD PRIMARY KEY (`sys_no`);

--
-- Indexes for table `ecgregistery`
--
ALTER TABLE `ecgregistery`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `OpdNo` (`OpdNo`);

--
-- Indexes for table `fumigation_register`
--
ALTER TABLE `fumigation_register`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `indent`
--
ALTER TABLE `indent`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ipdno` (`ipdno`),
  ADD KEY `opdno` (`opdno`);

--
-- Indexes for table `inpatientdetails`
--
ALTER TABLE `inpatientdetails`
  ADD PRIMARY KEY (`IpNo`),
  ADD KEY `OpdNo` (`OpdNo`);

--
-- Indexes for table `ipdtreatment`
--
ALTER TABLE `ipdtreatment`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ipdno` (`ipdno`);

--
-- Indexes for table `i_user_roles`
--
ALTER TABLE `i_user_roles`
  ADD PRIMARY KEY (`user_role_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `jaloukavacharana_opd_ipd_register`
--
ALTER TABLE `jaloukavacharana_opd_ipd_register`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_opd` (`opd_no`),
  ADD KEY `fk_ipd` (`ipd_no`);

--
-- Indexes for table `kriyakalpa`
--
ALTER TABLE `kriyakalpa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ksharsutraregistery`
--
ALTER TABLE `ksharsutraregistery`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `labregistery`
--
ALTER TABLE `labregistery`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `OpdNo` (`OpdNo`),
  ADD KEY `treatID` (`treatID`),
  ADD KEY `labregistery_index` (`OpdNo`,`treatID`,`lab_test_cat`,`lab_test_type`,`testName`);

--
-- Indexes for table `lab_categories`
--
ALTER TABLE `lab_categories`
  ADD PRIMARY KEY (`lab_cat_id`);

--
-- Indexes for table `lab_investigations`
--
ALTER TABLE `lab_investigations`
  ADD PRIMARY KEY (`lab_inv_id`),
  ADD KEY `FK_lab_investigations_1` (`lab_test_id`);

--
-- Indexes for table `lab_tests`
--
ALTER TABLE `lab_tests`
  ADD PRIMARY KEY (`lab_test_id`),
  ADD KEY `FK_lab_tests_1` (`lab_cat_id`);

--
-- Indexes for table `master_other_procedures`
--
ALTER TABLE `master_other_procedures`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_panchakarma_procedures`
--
ALTER TABLE `master_panchakarma_procedures`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_panchakarma_sub_procedures`
--
ALTER TABLE `master_panchakarma_sub_procedures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_master_panchakarma_sub_procedures_1` (`procecure_id`);

--
-- Indexes for table `master_physiotheraphy`
--
ALTER TABLE `master_physiotheraphy`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medicine_frequency`
--
ALTER TABLE `medicine_frequency`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `months_list`
--
ALTER TABLE `months_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `month_list`
--
ALTER TABLE `month_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oldtable`
--
ALTER TABLE `oldtable`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `other_procedures_treatments`
--
ALTER TABLE `other_procedures_treatments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `packagin_type`
--
ALTER TABLE `packagin_type`
  ADD PRIMARY KEY (`pakg_id`);

--
-- Indexes for table `panchakarma_ref`
--
ALTER TABLE `panchakarma_ref`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `panchaprocedure`
--
ALTER TABLE `panchaprocedure`
  ADD PRIMARY KEY (`id`),
  ADD KEY `opdno` (`opdno`),
  ADD KEY `treatid` (`treatid`);

--
-- Indexes for table `patientdata`
--
ALTER TABLE `patientdata`
  ADD PRIMARY KEY (`OpdNo`),
  ADD UNIQUE KEY `UHID` (`UHID`);

--
-- Indexes for table `patient_bill_details`
--
ALTER TABLE `patient_bill_details`
  ADD PRIMARY KEY (`bill_id`);

--
-- Indexes for table `perm_master`
--
ALTER TABLE `perm_master`
  ADD PRIMARY KEY (`perm_id`);

--
-- Indexes for table `physiotherapy_treatments`
--
ALTER TABLE `physiotherapy_treatments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prescribed_drug_info`
--
ALTER TABLE `prescribed_drug_info`
  ADD PRIMARY KEY (`drug_id`);

--
-- Indexes for table `prescription_details`
--
ALTER TABLE `prescription_details`
  ADD PRIMARY KEY (`presc_id`);

--
-- Indexes for table `product_details`
--
ALTER TABLE `product_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_list`
--
ALTER TABLE `product_list`
  ADD PRIMARY KEY (`slno`);

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
-- Indexes for table `qrcodes`
--
ALTER TABLE `qrcodes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reference_panchakarma`
--
ALTER TABLE `reference_panchakarma`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reference_table`
--
ALTER TABLE `reference_table`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ref_lab_reg_tab`
--
ALTER TABLE `ref_lab_reg_tab`
  ADD PRIMARY KEY (`lab_id`);

--
-- Indexes for table `role_master`
--
ALTER TABLE `role_master`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `role_perm`
--
ALTER TABLE `role_perm`
  ADD PRIMARY KEY (`role_perm_id`),
  ADD UNIQUE KEY `unique_index` (`role_id`,`perm_id`);

--
-- Indexes for table `sales_entry`
--
ALTER TABLE `sales_entry`
  ADD PRIMARY KEY (`id`),
  ADD KEY `opdno` (`opdno`),
  ADD KEY `ipdno` (`ipdno`);

--
-- Indexes for table `sales_return_temp`
--
ALTER TABLE `sales_return_temp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `samltp`
--
ALTER TABLE `samltp`
  ADD PRIMARY KEY (`said`);

--
-- Indexes for table `servpart`
--
ALTER TABLE `servpart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `siravyadana_opd_ipd_register`
--
ALTER TABLE `siravyadana_opd_ipd_register`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_department`
--
ALTER TABLE `sub_department`
  ADD PRIMARY KEY (`sub_dept_id`);

--
-- Indexes for table `surgeryregistery`
--
ALTER TABLE `surgeryregistery`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `OpdNo` (`OpdNo`),
  ADD KEY `treatId` (`treatId`);

--
-- Indexes for table `swarnaprashana`
--
ALTER TABLE `swarnaprashana`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `temp_bill`
--
ALTER TABLE `temp_bill`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `temp_purchase_entry`
--
ALTER TABLE `temp_purchase_entry`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `temp_sales_entry`
--
ALTER TABLE `temp_sales_entry`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `treatmentdata`
--
ALTER TABLE `treatmentdata`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `OpdNo` (`OpdNo`),
  ADD KEY `treatment_index` (`department`,`CameOn`,`diagnosis`);

--
-- Indexes for table `uhid_sequence`
--
ALTER TABLE `uhid_sequence`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_day_hospital` (`seq_date`,`hospital_code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`user_role_id`);

--
-- Indexes for table `user_validation`
--
ALTER TABLE `user_validation`
  ADD PRIMARY KEY (`val_id`);

--
-- Indexes for table `usgregistery`
--
ALTER TABLE `usgregistery`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `OpdNo` (`OpdNo`);

--
-- Indexes for table `week_days`
--
ALTER TABLE `week_days`
  ADD PRIMARY KEY (`week_id`);

--
-- Indexes for table `wound_dressing_opd_ipd_register`
--
ALTER TABLE `wound_dressing_opd_ipd_register`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `xrayregistery`
--
ALTER TABLE `xrayregistery`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `xray_ref`
--
ALTER TABLE `xray_ref`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agnikarma_opd_ipd_register`
--
ALTER TABLE `agnikarma_opd_ipd_register`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `app_prescription`
--
ALTER TABLE `app_prescription`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `archived_data`
--
ALTER TABLE `archived_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `autoclave_register`
--
ALTER TABLE `autoclave_register`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bed_details`
--
ALTER TABLE `bed_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bill_perticulars`
--
ALTER TABLE `bill_perticulars`
  MODIFY `bp_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bill_service_group`
--
ALTER TABLE `bill_service_group`
  MODIFY `bsg_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `birthregistery`
--
ALTER TABLE `birthregistery`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `config`
--
ALTER TABLE `config`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `config_variables`
--
ALTER TABLE `config_variables`
  MODIFY `config_var_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cupping_opd_ipd_register`
--
ALTER TABLE `cupping_opd_ipd_register`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deptper`
--
ALTER TABLE `deptper`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `diagnosis`
--
ALTER TABLE `diagnosis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `diet_register`
--
ALTER TABLE `diet_register`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doctorsduty`
--
ALTER TABLE `doctorsduty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doctor_patient_info`
--
ALTER TABLE `doctor_patient_info`
  MODIFY `sys_no` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ecgregistery`
--
ALTER TABLE `ecgregistery`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fumigation_register`
--
ALTER TABLE `fumigation_register`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `indent`
--
ALTER TABLE `indent`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inpatientdetails`
--
ALTER TABLE `inpatientdetails`
  MODIFY `IpNo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ipdtreatment`
--
ALTER TABLE `ipdtreatment`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `i_user_roles`
--
ALTER TABLE `i_user_roles`
  MODIFY `user_role_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jaloukavacharana_opd_ipd_register`
--
ALTER TABLE `jaloukavacharana_opd_ipd_register`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kriyakalpa`
--
ALTER TABLE `kriyakalpa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ksharsutraregistery`
--
ALTER TABLE `ksharsutraregistery`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `labregistery`
--
ALTER TABLE `labregistery`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lab_categories`
--
ALTER TABLE `lab_categories`
  MODIFY `lab_cat_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lab_investigations`
--
ALTER TABLE `lab_investigations`
  MODIFY `lab_inv_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lab_tests`
--
ALTER TABLE `lab_tests`
  MODIFY `lab_test_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_other_procedures`
--
ALTER TABLE `master_other_procedures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_panchakarma_procedures`
--
ALTER TABLE `master_panchakarma_procedures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_panchakarma_sub_procedures`
--
ALTER TABLE `master_panchakarma_sub_procedures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_physiotheraphy`
--
ALTER TABLE `master_physiotheraphy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medicine_frequency`
--
ALTER TABLE `medicine_frequency`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `months_list`
--
ALTER TABLE `months_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `month_list`
--
ALTER TABLE `month_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oldtable`
--
ALTER TABLE `oldtable`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `other_procedures_treatments`
--
ALTER TABLE `other_procedures_treatments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `packagin_type`
--
ALTER TABLE `packagin_type`
  MODIFY `pakg_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `panchakarma_ref`
--
ALTER TABLE `panchakarma_ref`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `panchaprocedure`
--
ALTER TABLE `panchaprocedure`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patientdata`
--
ALTER TABLE `patientdata`
  MODIFY `OpdNo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patient_bill_details`
--
ALTER TABLE `patient_bill_details`
  MODIFY `bill_id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `perm_master`
--
ALTER TABLE `perm_master`
  MODIFY `perm_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `physiotherapy_treatments`
--
ALTER TABLE `physiotherapy_treatments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prescribed_drug_info`
--
ALTER TABLE `prescribed_drug_info`
  MODIFY `drug_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prescription_details`
--
ALTER TABLE `prescription_details`
  MODIFY `presc_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_details`
--
ALTER TABLE `product_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_list`
--
ALTER TABLE `product_list`
  MODIFY `slno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_master`
--
ALTER TABLE `product_master`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `pt_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_variables`
--
ALTER TABLE `purchase_variables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `qrcodes`
--
ALTER TABLE `qrcodes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reference_panchakarma`
--
ALTER TABLE `reference_panchakarma`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reference_table`
--
ALTER TABLE `reference_table`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ref_lab_reg_tab`
--
ALTER TABLE `ref_lab_reg_tab`
  MODIFY `lab_id` int(242) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role_master`
--
ALTER TABLE `role_master`
  MODIFY `role_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role_perm`
--
ALTER TABLE `role_perm`
  MODIFY `role_perm_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_entry`
--
ALTER TABLE `sales_entry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_return_temp`
--
ALTER TABLE `sales_return_temp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `samltp`
--
ALTER TABLE `samltp`
  MODIFY `said` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `servpart`
--
ALTER TABLE `servpart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `siravyadana_opd_ipd_register`
--
ALTER TABLE `siravyadana_opd_ipd_register`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sub_department`
--
ALTER TABLE `sub_department`
  MODIFY `sub_dept_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `surgeryregistery`
--
ALTER TABLE `surgeryregistery`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `swarnaprashana`
--
ALTER TABLE `swarnaprashana`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `temp_bill`
--
ALTER TABLE `temp_bill`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `temp_purchase_entry`
--
ALTER TABLE `temp_purchase_entry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `temp_sales_entry`
--
ALTER TABLE `temp_sales_entry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `treatmentdata`
--
ALTER TABLE `treatmentdata`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `uhid_sequence`
--
ALTER TABLE `uhid_sequence`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `user_role_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_validation`
--
ALTER TABLE `user_validation`
  MODIFY `val_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usgregistery`
--
ALTER TABLE `usgregistery`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `week_days`
--
ALTER TABLE `week_days`
  MODIFY `week_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wound_dressing_opd_ipd_register`
--
ALTER TABLE `wound_dressing_opd_ipd_register`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `xrayregistery`
--
ALTER TABLE `xrayregistery`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `xray_ref`
--
ALTER TABLE `xray_ref`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bill_perticulars`
--
ALTER TABLE `bill_perticulars`
  ADD CONSTRAINT `FK_bill_perticulars_1` FOREIGN KEY (`bsg_id`) REFERENCES `bill_service_group` (`bsg_id`);

--
-- Constraints for table `ecgregistery`
--
ALTER TABLE `ecgregistery`
  ADD CONSTRAINT `ecgregistery_ibfk_1` FOREIGN KEY (`OpdNo`) REFERENCES `patientdata` (`OpdNo`);

--
-- Constraints for table `indent`
--
ALTER TABLE `indent`
  ADD CONSTRAINT `indent_ibfk_1` FOREIGN KEY (`ipdno`) REFERENCES `inpatientdetails` (`IpNo`),
  ADD CONSTRAINT `indent_ibfk_2` FOREIGN KEY (`opdno`) REFERENCES `patientdata` (`OpdNo`);

--
-- Constraints for table `inpatientdetails`
--
ALTER TABLE `inpatientdetails`
  ADD CONSTRAINT `inpatientdetails_ibfk_1` FOREIGN KEY (`OpdNo`) REFERENCES `patientdata` (`OpdNo`);

--
-- Constraints for table `ipdtreatment`
--
ALTER TABLE `ipdtreatment`
  ADD CONSTRAINT `ipdtreatment_ibfk_1` FOREIGN KEY (`ipdno`) REFERENCES `inpatientdetails` (`IpNo`);

--
-- Constraints for table `jaloukavacharana_opd_ipd_register`
--
ALTER TABLE `jaloukavacharana_opd_ipd_register`
  ADD CONSTRAINT `fk_ipd` FOREIGN KEY (`ipd_no`) REFERENCES `inpatientdetails` (`IpNo`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_opd` FOREIGN KEY (`opd_no`) REFERENCES `patientdata` (`OpdNo`) ON DELETE CASCADE;

--
-- Constraints for table `labregistery`
--
ALTER TABLE `labregistery`
  ADD CONSTRAINT `labregistery_ibfk_1` FOREIGN KEY (`OpdNo`) REFERENCES `patientdata` (`OpdNo`),
  ADD CONSTRAINT `labregistery_ibfk_2` FOREIGN KEY (`treatID`) REFERENCES `treatmentdata` (`ID`);

--
-- Constraints for table `lab_investigations`
--
ALTER TABLE `lab_investigations`
  ADD CONSTRAINT `FK_lab_investigations_1` FOREIGN KEY (`lab_test_id`) REFERENCES `lab_tests` (`lab_test_id`);

--
-- Constraints for table `lab_tests`
--
ALTER TABLE `lab_tests`
  ADD CONSTRAINT `FK_lab_tests_1` FOREIGN KEY (`lab_cat_id`) REFERENCES `lab_categories` (`lab_cat_id`);

--
-- Constraints for table `master_panchakarma_sub_procedures`
--
ALTER TABLE `master_panchakarma_sub_procedures`
  ADD CONSTRAINT `FK_master_panchakarma_sub_procedures_1` FOREIGN KEY (`procecure_id`) REFERENCES `master_panchakarma_procedures` (`id`);

--
-- Constraints for table `panchaprocedure`
--
ALTER TABLE `panchaprocedure`
  ADD CONSTRAINT `panchaprocedure_ibfk_1` FOREIGN KEY (`opdno`) REFERENCES `patientdata` (`OpdNo`),
  ADD CONSTRAINT `panchaprocedure_ibfk_2` FOREIGN KEY (`treatid`) REFERENCES `treatmentdata` (`ID`);

--
-- Constraints for table `sales_entry`
--
ALTER TABLE `sales_entry`
  ADD CONSTRAINT `FK_sales_entry_1` FOREIGN KEY (`opdno`) REFERENCES `patientdata` (`OpdNo`),
  ADD CONSTRAINT `sales_entry_ibfk_1` FOREIGN KEY (`ipdno`) REFERENCES `inpatientdetails` (`IpNo`);

--
-- Constraints for table `surgeryregistery`
--
ALTER TABLE `surgeryregistery`
  ADD CONSTRAINT `surgeryregistery_ibfk_1` FOREIGN KEY (`OpdNo`) REFERENCES `patientdata` (`OpdNo`),
  ADD CONSTRAINT `surgeryregistery_ibfk_2` FOREIGN KEY (`treatId`) REFERENCES `treatmentdata` (`ID`);

--
-- Constraints for table `treatmentdata`
--
ALTER TABLE `treatmentdata`
  ADD CONSTRAINT `FK_treatmentdata_1` FOREIGN KEY (`OpdNo`) REFERENCES `patientdata` (`OpdNo`);

--
-- Constraints for table `usgregistery`
--
ALTER TABLE `usgregistery`
  ADD CONSTRAINT `usgregistery_ibfk_1` FOREIGN KEY (`OpdNo`) REFERENCES `patientdata` (`OpdNo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
