-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 15, 2016 at 02:50 PM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 7.0.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `seaolympus`
--

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_number` int(10) UNSIGNED NOT NULL,
  `name` varchar(45) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `id_number`, `name`) VALUES
(6, 1, 'VP');

-- --------------------------------------------------------

--
-- Table structure for table `department_supervisors`
--

CREATE TABLE `department_supervisors` (
  `id` int(10) UNSIGNED NOT NULL,
  `employee_id` int(10) UNSIGNED NOT NULL,
  `department_id` int(10) UNSIGNED NOT NULL,
  `from` date NOT NULL,
  `to` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `department_supervisors`
--

INSERT INTO `department_supervisors` (`id`, `employee_id`, `department_id`, `from`, `to`) VALUES
(6, 3, 6, '2016-05-15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_number` int(10) UNSIGNED DEFAULT NULL,
  `firstname` varchar(50) COLLATE utf8_bin NOT NULL,
  `middleinitial` varchar(50) COLLATE utf8_bin NOT NULL,
  `lastname` varchar(50) COLLATE utf8_bin NOT NULL,
  `birthdate` date NOT NULL,
  `birthplace` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `gender` enum('M','F') COLLATE utf8_bin NOT NULL,
  `civil_status` enum('sg','m','sp','d','w') COLLATE utf8_bin NOT NULL,
  `nationality` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `religion` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `full_address` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `email_address` varchar(45) COLLATE utf8_bin NOT NULL,
  `mobile_number` varchar(15) COLLATE utf8_bin NOT NULL,
  `date_hired` date NOT NULL,
  `login_password` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `sss_number` varchar(45) COLLATE utf8_bin NOT NULL,
  `pagibig_number` varchar(45) COLLATE utf8_bin NOT NULL,
  `tin_number` varchar(45) COLLATE utf8_bin NOT NULL,
  `rfid_uid` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `password` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `daily_rate` decimal(15,2) DEFAULT NULL,
  `overtime_rate` decimal(15,2) DEFAULT NULL,
  `allowed_late_period` decimal(15,2) DEFAULT NULL,
  `late_penalty` decimal(15,2) DEFAULT NULL,
  `is_locked` tinyint(1) NOT NULL DEFAULT '0',
  `account_type` enum('em','ad','pm') COLLATE utf8_bin NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `id_number`, `firstname`, `middleinitial`, `lastname`, `birthdate`, `birthplace`, `gender`, `civil_status`, `nationality`, `religion`, `full_address`, `email_address`, `mobile_number`, `date_hired`, `login_password`, `sss_number`, `pagibig_number`, `tin_number`, `rfid_uid`, `password`, `daily_rate`, `overtime_rate`, `allowed_late_period`, `late_penalty`, `is_locked`, `account_type`, `created_at`) VALUES
(3, 2, 'JULITO', 'G', 'CASTANEDA', '1995-06-20', 'Cebu City', 'M', 'w', 'Filipino', 'Roman Catholic', 'Mandaue City, Cebu', 'natabioadr@gmail.com', '09434524412', '2016-12-15', NULL, '1232', '13', 'ss', '00000003', '21232f297a57a5a743894a0e4a801fc3', '560.00', '150.00', '15.00', '15.00', 0, 'ad', '2016-01-17 02:07:01');

-- --------------------------------------------------------

--
-- Table structure for table `employee_attendance`
--

CREATE TABLE `employee_attendance` (
  `id` int(10) UNSIGNED NOT NULL,
  `employee_id` int(10) UNSIGNED NOT NULL,
  `datetime_in` timestamp NULL DEFAULT NULL,
  `datetime_out` timestamp NULL DEFAULT NULL,
  `request_id` int(10) UNSIGNED DEFAULT NULL,
  `upload_batch` int(11) NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `last_updated_by` int(10) UNSIGNED NOT NULL,
  `last_approved_by` int(10) UNSIGNED NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `employee_attendance`
--

INSERT INTO `employee_attendance` (`id`, `employee_id`, `datetime_in`, `datetime_out`, `request_id`, `upload_batch`, `created_by`, `last_updated_by`, `last_approved_by`, `date_created`) VALUES
(2273, 3, '2016-06-23 05:00:00', '2016-06-23 09:00:00', NULL, 1, 3, 3, 3, '2016-06-15 19:41:45'),
(2274, 3, '2016-06-23 00:00:00', '2016-06-23 04:00:00', NULL, 1, 3, 3, 3, '2016-06-15 19:41:45');

-- --------------------------------------------------------

--
-- Table structure for table `employee_departments`
--

CREATE TABLE `employee_departments` (
  `id` int(10) UNSIGNED NOT NULL,
  `department_id` int(10) UNSIGNED NOT NULL,
  `employee_id` int(10) UNSIGNED NOT NULL,
  `from` date NOT NULL,
  `to` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `employee_departments`
--

INSERT INTO `employee_departments` (`id`, `department_id`, `employee_id`, `from`, `to`) VALUES
(41, 6, 3, '2016-05-15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employee_positions`
--

CREATE TABLE `employee_positions` (
  `id` int(10) UNSIGNED NOT NULL,
  `employee_id` int(10) UNSIGNED NOT NULL,
  `position_id` int(10) UNSIGNED NOT NULL,
  `from` date NOT NULL,
  `to` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `employee_positions`
--

INSERT INTO `employee_positions` (`id`, `employee_id`, `position_id`, `from`, `to`) VALUES
(50, 3, 8, '2016-05-15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employee_requests`
--

CREATE TABLE `employee_requests` (
  `id` int(10) UNSIGNED NOT NULL,
  `sender_id` int(10) UNSIGNED NOT NULL,
  `department_id` int(10) UNSIGNED NOT NULL,
  `type` enum('matpat','sl','wml','vl','o') COLLATE utf8_bin NOT NULL COMMENT 'matpat - maternity/paternity leave\nsl - sick leave\nmpl - mens paid leave\nwml - womens menstruation leave\nvl - vacation leave\no - others',
  `custom_type_name` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `datetime_filed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `title` varchar(100) COLLATE utf8_bin NOT NULL,
  `content` text COLLATE utf8_bin,
  `approved_by` int(10) UNSIGNED DEFAULT NULL,
  `status` enum('p','a','da') COLLATE utf8_bin NOT NULL COMMENT 'p - pending\na - approved\nda - disapproved',
  `is_acknowledged` tinyint(1) NOT NULL DEFAULT '0',
  `halfday` enum('am','pm') COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `id` int(11) UNSIGNED NOT NULL,
  `loan_date` timestamp NULL DEFAULT NULL,
  `employee_id` int(10) UNSIGNED NOT NULL,
  `loan_amount` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `loans`
--

INSERT INTO `loans` (`id`, `loan_date`, `employee_id`, `loan_amount`) VALUES
(8, '2016-04-30 16:00:00', 3, '1000.00');

-- --------------------------------------------------------

--
-- Table structure for table `payment_terms`
--

CREATE TABLE `payment_terms` (
  `id` int(10) NOT NULL,
  `loan_id` int(10) UNSIGNED NOT NULL,
  `payment_date` date DEFAULT NULL,
  `payment_amount` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payment_terms`
--

INSERT INTO `payment_terms` (`id`, `loan_id`, `payment_date`, `payment_amount`) VALUES
(63, 8, '2016-05-10', '500.00'),
(64, 8, '2016-05-05', '500.00');

-- --------------------------------------------------------

--
-- Table structure for table `payroll`
--

CREATE TABLE `payroll` (
  `id` int(10) UNSIGNED NOT NULL,
  `employee_id` int(10) UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `days_rendered` float DEFAULT '0',
  `overtime_hours_rendered` float DEFAULT '0',
  `late_minutes` float DEFAULT '0',
  `current_daily_wage` decimal(13,2) DEFAULT '0.00',
  `daily_wage_units` int(11) NOT NULL,
  `wage_adjustment` decimal(13,2) DEFAULT '0.00',
  `current_late_penalty` decimal(13,2) DEFAULT '0.00',
  `overtime_pay` decimal(13,2) DEFAULT '0.00',
  `created_by` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `payroll`
--

INSERT INTO `payroll` (`id`, `employee_id`, `start_date`, `end_date`, `days_rendered`, `overtime_hours_rendered`, `late_minutes`, `current_daily_wage`, `daily_wage_units`, `wage_adjustment`, `current_late_penalty`, `overtime_pay`, `created_by`) VALUES
(32, 3, '2016-05-01', '2016-05-15', 1, 1, 0, '560.00', 0, '0.00', '15.00', '840.00', 3),
(34, 3, '2016-06-01', '2016-06-15', 1, 0, 0, '560.00', 0, '0.00', '15.00', '0.00', 3),
(42, 3, '2016-06-16', '2016-06-30', 1, 0, 0, '560.00', 0, '0.00', '15.00', '0.00', 3);

-- --------------------------------------------------------

--
-- Table structure for table `payroll_particulars`
--

CREATE TABLE `payroll_particulars` (
  `id` int(10) UNSIGNED NOT NULL,
  `payroll_id` int(10) UNSIGNED NOT NULL,
  `particulars_id` int(10) UNSIGNED NOT NULL,
  `units` int(11) NOT NULL,
  `amount` decimal(13,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `payroll_particulars`
--

INSERT INTO `payroll_particulars` (`id`, `payroll_id`, `particulars_id`, `units`, `amount`) VALUES
(46, 32, 4, 0, '11.11'),
(47, 32, 5, 0, '100.00'),
(50, 34, 4, 0, '11.11'),
(51, 34, 5, 0, '100.00'),
(52, 34, 6, 0, '10.00'),
(67, 42, 4, 0, '11.11'),
(68, 42, 5, 0, '100.00');

-- --------------------------------------------------------

--
-- Table structure for table `pay_modifiers`
--

CREATE TABLE `pay_modifiers` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8_bin NOT NULL,
  `particular_type` enum('d','m') COLLATE utf8_bin NOT NULL COMMENT 'd - daily     m - monthly',
  `type` enum('a','d') COLLATE utf8_bin NOT NULL COMMENT 'a - additional\nd - deductions'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `pay_modifiers`
--

INSERT INTO `pay_modifiers` (`id`, `name`, `particular_type`, `type`) VALUES
(4, 'SSS', 'd', 'd'),
(5, 'Bonus', 'd', 'a'),
(6, 'trial', 'm', 'a');

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(45) COLLATE utf8_bin NOT NULL,
  `attendance_type` enum('re','fl') COLLATE utf8_bin DEFAULT NULL,
  `workday` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`id`, `name`, `attendance_type`, `workday`) VALUES
(8, 'HR', 're', '[{"day":"3","time":{"from_time_1":"8:00 AM","to_time_1":"12:00 PM","from_time_2":"1:00 PM","to_time_2":"5:00 PM"},"first_hours":4,"second_hours":4,"total_working_hours":8},{"day":"4","time":{"from_time_1":"8:00 AM","to_time_1":"12:00 PM","from_time_2":"1:00 PM","to_time_2":"5:00 PM"},"first_hours":4,"second_hours":4,"total_working_hours":8},{"day":"5","time":{"from_time_1":"8:00 AM","to_time_1":"12:00 PM","from_time_2":"1:00 PM","to_time_2":"5:00 PM"},"first_hours":4,"second_hours":4,"total_working_hours":8}]'),
(9, 'asddasdasdasdas', 're', '[{"day":"1","time":{"from_time_1":"1:15 AM","to_time_1":"2:15 AM","from_time_2":"1:15 PM","to_time_2":"2:15 AM"},"first_hours":1,"second_hours":11,"total_working_hours":12}]');

-- --------------------------------------------------------

--
-- Table structure for table `salary_particulars`
--

CREATE TABLE `salary_particulars` (
  `employee_id` int(10) UNSIGNED NOT NULL,
  `particulars_id` int(10) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `salary_particulars`
--

INSERT INTO `salary_particulars` (`employee_id`, `particulars_id`, `amount`) VALUES
(3, 4, '11.11'),
(3, 5, '100.00'),
(3, 6, '10.00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name_UNIQUE` (`name`),
  ADD UNIQUE KEY `id_number_UNIQUE` (`id_number`);

--
-- Indexes for table `department_supervisors`
--
ALTER TABLE `department_supervisors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department_supervisors_employee_id_foreign_idx` (`employee_id`),
  ADD KEY `department_supervisors_department_id_foreign_idx` (`department_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_address_UNIQUE` (`email_address`),
  ADD UNIQUE KEY `employee_number_UNIQUE` (`id_number`),
  ADD UNIQUE KEY `rfid_uid_UNIQUE` (`rfid_uid`);

--
-- Indexes for table `employee_attendance`
--
ALTER TABLE `employee_attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_attendance_employee_id_foreign_idx` (`employee_id`),
  ADD KEY `employee_attendance_request_id_foreign_idx` (`request_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `last_updated_by` (`last_updated_by`),
  ADD KEY `last_approved_by` (`last_approved_by`);

--
-- Indexes for table `employee_departments`
--
ALTER TABLE `employee_departments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_departments_department_id_foreign_idx` (`department_id`),
  ADD KEY `employee_departments_employee_id_foreign_idx` (`employee_id`);

--
-- Indexes for table `employee_positions`
--
ALTER TABLE `employee_positions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_positions_employee_id_foreign_idx` (`employee_id`),
  ADD KEY `employee_positions_position_id_foreign_idx` (`position_id`);

--
-- Indexes for table `employee_requests`
--
ALTER TABLE `employee_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_requests_sender_id_foreign_idx` (`sender_id`),
  ADD KEY `employee_requests_department_id_foreign_idx` (`department_id`),
  ADD KEY `employee_requests_approved_by_foreign_idx` (`approved_by`);

--
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_terms`
--
ALTER TABLE `payment_terms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_terms_loan_id_foreign_idx` (`loan_id`);

--
-- Indexes for table `payroll`
--
ALTER TABLE `payroll`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payroll_created_by_foreign_idx` (`created_by`),
  ADD KEY `payroll_employee_id_foreign_idx` (`employee_id`);

--
-- Indexes for table `payroll_particulars`
--
ALTER TABLE `payroll_particulars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payroll_particulars_payroll_id_foreign_idx` (`payroll_id`),
  ADD KEY `payroll_particulars_particulars_id_foreign_idx` (`particulars_id`);

--
-- Indexes for table `pay_modifiers`
--
ALTER TABLE `pay_modifiers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name_UNIQUE` (`name`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name_UNIQUE` (`name`);

--
-- Indexes for table `salary_particulars`
--
ALTER TABLE `salary_particulars`
  ADD KEY `salary_particulars_particulars_id_foreign_idx` (`particulars_id`),
  ADD KEY `salary_particulars_employee_id_foreign_idx` (`employee_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `department_supervisors`
--
ALTER TABLE `department_supervisors`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `employee_attendance`
--
ALTER TABLE `employee_attendance`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2275;
--
-- AUTO_INCREMENT for table `employee_departments`
--
ALTER TABLE `employee_departments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;
--
-- AUTO_INCREMENT for table `employee_positions`
--
ALTER TABLE `employee_positions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;
--
-- AUTO_INCREMENT for table `employee_requests`
--
ALTER TABLE `employee_requests`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `payment_terms`
--
ALTER TABLE `payment_terms`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;
--
-- AUTO_INCREMENT for table `payroll`
--
ALTER TABLE `payroll`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;
--
-- AUTO_INCREMENT for table `payroll_particulars`
--
ALTER TABLE `payroll_particulars`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;
--
-- AUTO_INCREMENT for table `pay_modifiers`
--
ALTER TABLE `pay_modifiers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `department_supervisors`
--
ALTER TABLE `department_supervisors`
  ADD CONSTRAINT `department_supervisors_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `department_supervisors_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `employee_attendance`
--
ALTER TABLE `employee_attendance`
  ADD CONSTRAINT `employee_attendance_created_by` FOREIGN KEY (`created_by`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `employee_attendance_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `employee_attendance_last_approved_by` FOREIGN KEY (`last_approved_by`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `employee_attendance_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `employees` (`id`),
  ADD CONSTRAINT `employee_attendance_request_id_foreign` FOREIGN KEY (`request_id`) REFERENCES `employee_requests` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `employee_departments`
--
ALTER TABLE `employee_departments`
  ADD CONSTRAINT `employee_departments_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `employee_departments_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `employee_positions`
--
ALTER TABLE `employee_positions`
  ADD CONSTRAINT `employee_positions_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `employee_positions_position_id_foreign` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `employee_requests`
--
ALTER TABLE `employee_requests`
  ADD CONSTRAINT `employee_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `employee_requests_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `employee_requests_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `payment_terms`
--
ALTER TABLE `payment_terms`
  ADD CONSTRAINT `payment_terms_loan_id_foreign` FOREIGN KEY (`loan_id`) REFERENCES `loans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payroll`
--
ALTER TABLE `payroll`
  ADD CONSTRAINT `payroll_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `employees` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `payroll_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `payroll_particulars`
--
ALTER TABLE `payroll_particulars`
  ADD CONSTRAINT `payroll_particulars_particulars_id_foreign` FOREIGN KEY (`particulars_id`) REFERENCES `pay_modifiers` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `payroll_particulars_payroll_id_foreign` FOREIGN KEY (`payroll_id`) REFERENCES `payroll` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `salary_particulars`
--
ALTER TABLE `salary_particulars`
  ADD CONSTRAINT `salary_particulars_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `salary_particulars_particulars_id_foreign` FOREIGN KEY (`particulars_id`) REFERENCES `pay_modifiers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
