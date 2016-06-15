-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 29, 2016 at 11:39 AM
-- Server version: 5.6.26
-- PHP Version: 5.6.12

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

CREATE TABLE IF NOT EXISTS `departments` (
  `id` int(10) unsigned NOT NULL,
  `id_number` int(10) unsigned NOT NULL,
  `name` varchar(45) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `id_number`, `name`) VALUES
(2, 190, '2Sample Department1'),
(3, 105, 'Hello Department '),
(4, 901, 'Sample Department');

-- --------------------------------------------------------

--
-- Table structure for table `department_supervisors`
--

CREATE TABLE IF NOT EXISTS `department_supervisors` (
  `id` int(10) unsigned NOT NULL,
  `employee_id` int(10) unsigned NOT NULL,
  `department_id` int(10) unsigned NOT NULL,
  `from` date NOT NULL,
  `to` date DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `department_supervisors`
--

INSERT INTO `department_supervisors` (`id`, `employee_id`, `department_id`, `from`, `to`) VALUES
(1, 3, 2, '2016-01-20', '2016-01-20'),
(2, 5, 2, '2016-01-20', '2016-01-20'),
(3, 3, 2, '2016-01-20', NULL),
(4, 6, 4, '2016-01-21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `divisions`
--

CREATE TABLE IF NOT EXISTS `divisions` (
  `id` int(10) unsigned NOT NULL,
  `id_number` int(10) unsigned NOT NULL,
  `name` varchar(45) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `divisions`
--

INSERT INTO `divisions` (`id`, `id_number`, `name`) VALUES
(1, 100, 'Division 1'),
(2, 1002, 'Division 3 Hello');

-- --------------------------------------------------------

--
-- Table structure for table `division_departments`
--

CREATE TABLE IF NOT EXISTS `division_departments` (
  `id` int(10) unsigned NOT NULL,
  `division_id` int(10) unsigned NOT NULL,
  `department_id` int(10) unsigned NOT NULL,
  `from` date NOT NULL,
  `to` date DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `division_departments`
--

INSERT INTO `division_departments` (`id`, `division_id`, `department_id`, `from`, `to`) VALUES
(1, 1, 2, '2016-01-18', '2016-01-18'),
(2, 2, 2, '2016-01-18', '2016-01-18'),
(3, 1, 2, '2016-01-18', '2016-01-18'),
(4, 2, 2, '2016-01-18', '2016-01-18'),
(5, 1, 2, '2016-01-18', '2016-01-18'),
(6, 2, 2, '2016-01-18', NULL),
(7, 1, 3, '2016-01-18', NULL),
(8, 1, 4, '2016-01-21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE IF NOT EXISTS `employees` (
  `id` int(10) unsigned NOT NULL,
  `id_number` int(10) unsigned NOT NULL,
  `firstname` varchar(50) COLLATE utf8_bin NOT NULL,
  `middlename` varchar(50) COLLATE utf8_bin NOT NULL,
  `lastname` varchar(50) COLLATE utf8_bin NOT NULL,
  `birthdate` date NOT NULL,
  `birthplace` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `gender` enum('M','F') COLLATE utf8_bin NOT NULL,
  `civil_status` varchar(15) COLLATE utf8_bin NOT NULL,
  `nationality` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `religion` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `full_address` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `email_address` varchar(45) COLLATE utf8_bin NOT NULL,
  `mobile_number` varchar(15) COLLATE utf8_bin NOT NULL,
  `date_hired` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `id_number`, `firstname`, `middlename`, `lastname`, `birthdate`, `birthplace`, `gender`, `civil_status`, `nationality`, `religion`, `full_address`, `email_address`, `mobile_number`, `date_hired`, `created_at`) VALUES
(3, 1, 'Adrian', 'Garcia', 'Natabio', '1995-06-20', 'Cebu City', 'M', 'Single', 'Filipino', 'Roman Catholic', 'Mandaue City, Cebu', 'natabioadr@gmail.com', '09434524412', '2016-01-16', '2016-01-17 02:07:01'),
(5, 1002, 'Dave', 'G', 'delos Angeles', '1999-09-01', 'Cebu City', 'M', 'Married', 'Filipino', 'Muslim', 'Mandaue City, Cebu', 'mddl@gmail.com', '09233887588', '2016-01-05', '2016-01-18 03:41:31'),
(6, 1003, 'Marvin', 'M', 'Agruda', '2016-01-01', 'Cebu', 'M', 'Single', 'Filipino', 'Roman Catholic', 'Cebu City', 'sample@gmail.com', '1234567890', '2016-01-01', '2016-01-21 05:55:20');

-- --------------------------------------------------------

--
-- Table structure for table `employee_departments`
--

CREATE TABLE IF NOT EXISTS `employee_departments` (
  `id` int(10) unsigned NOT NULL,
  `department_id` int(10) unsigned NOT NULL,
  `employee_id` int(10) unsigned NOT NULL,
  `from` date NOT NULL,
  `to` date DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `employee_departments`
--

INSERT INTO `employee_departments` (`id`, `department_id`, `employee_id`, `from`, `to`) VALUES
(1, 2, 5, '2016-01-18', '2016-01-18'),
(2, 2, 3, '2016-01-18', '2016-01-18'),
(3, 3, 5, '2016-01-18', '2016-01-18'),
(4, 3, 3, '2016-01-18', '2016-01-18'),
(5, 2, 3, '2016-01-18', '2016-01-21'),
(6, 2, 5, '2016-01-18', '2016-01-18'),
(7, 3, 5, '2016-01-18', '2016-01-20'),
(8, 2, 5, '2016-01-20', NULL),
(9, 2, 6, '2016-01-21', '2016-01-21'),
(10, 4, 6, '2016-01-21', NULL),
(11, 4, 3, '2016-01-21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employee_positions`
--

CREATE TABLE IF NOT EXISTS `employee_positions` (
  `id` int(10) unsigned NOT NULL,
  `employee_id` int(10) unsigned NOT NULL,
  `position_id` int(10) unsigned NOT NULL,
  `from` date NOT NULL,
  `to` date DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `employee_positions`
--

INSERT INTO `employee_positions` (`id`, `employee_id`, `position_id`, `from`, `to`) VALUES
(1, 6, 1, '2016-01-28', '2016-01-28'),
(2, 6, 2, '2016-01-28', NULL),
(3, 5, 2, '2016-01-28', '2016-01-28'),
(4, 5, 4, '2016-01-28', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employee_requests`
--

CREATE TABLE IF NOT EXISTS `employee_requests` (
  `id` int(10) unsigned NOT NULL,
  `sender_id` int(10) unsigned NOT NULL,
  `department_id` int(10) unsigned NOT NULL,
  `datetime_filed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `datetime_start` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `datetime_end` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `title` varchar(100) COLLATE utf8_bin NOT NULL,
  `content` text COLLATE utf8_bin,
  `status` enum('p','a','da') COLLATE utf8_bin NOT NULL COMMENT 'p - pending\na - approved\nda - disapproved',
  `approved_by` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `employee_requests`
--

INSERT INTO `employee_requests` (`id`, `sender_id`, `department_id`, `datetime_filed`, `datetime_start`, `datetime_end`, `title`, `content`, `status`, `approved_by`) VALUES
(1, 5, 2, '2016-01-20 09:37:00', '2016-01-20 04:29:45', '2016-01-22 04:29:45', 'asd', 'asd', 'p', NULL),
(2, 3, 4, '2016-01-21 08:47:58', '2016-01-21 05:58:42', '2016-01-23 05:58:42', 'Sample title', 'Sample request', 'a', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pay_modifiers`
--

CREATE TABLE IF NOT EXISTS `pay_modifiers` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(50) COLLATE utf8_bin NOT NULL,
  `type` enum('a','d') COLLATE utf8_bin NOT NULL COMMENT 'a - additional\nd - deductions',
  `is_active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `pay_modifiers`
--

INSERT INTO `pay_modifiers` (`id`, `name`, `type`, `is_active`) VALUES
(1, 'SSS Premium', 'd', 1),
(2, 'Allowance', 'a', 1);

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE IF NOT EXISTS `positions` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(45) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`id`, `name`) VALUES
(1, 'General Manager'),
(4, 'Payroll Manager'),
(2, 'Regular Employee'),
(3, 'Supervisor');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `employee_id` int(10) unsigned NOT NULL,
  `password` varchar(45) COLLATE utf8_bin NOT NULL,
  `type` enum('hr','sv','po','re') COLLATE utf8_bin NOT NULL COMMENT 'hr - human resource officer\nsv - supervisor\npo - payroll officer\nre - regular employee',
  `is_locked` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`employee_id`, `password`, `type`, `is_locked`, `created_at`) VALUES
(3, '8c4205ec33d8f6caeaaaa0c10a14138c', 're', NULL, '2016-01-19 16:20:59'),
(5, '8c4205ec33d8f6caeaaaa0c10a14138c', 're', NULL, '2016-01-20 03:04:38'),
(6, '81dc9bdb52d04dc20036dbd8313ed055', 'sv', NULL, '2016-01-21 05:56:32');

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
-- Indexes for table `divisions`
--
ALTER TABLE `divisions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_number_UNIQUE` (`id_number`),
  ADD UNIQUE KEY `name_UNIQUE` (`name`);

--
-- Indexes for table `division_departments`
--
ALTER TABLE `division_departments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `division_departments_division_id_foreign_idx` (`division_id`),
  ADD KEY `division_departments_department_id_foreign_idx` (`department_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_number_UNIQUE` (`id_number`);

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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`employee_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `department_supervisors`
--
ALTER TABLE `department_supervisors`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `divisions`
--
ALTER TABLE `divisions`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `division_departments`
--
ALTER TABLE `division_departments`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `employee_departments`
--
ALTER TABLE `employee_departments`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `employee_positions`
--
ALTER TABLE `employee_positions`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `employee_requests`
--
ALTER TABLE `employee_requests`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `pay_modifiers`
--
ALTER TABLE `pay_modifiers`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
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
-- Constraints for table `division_departments`
--
ALTER TABLE `division_departments`
  ADD CONSTRAINT `division_departments_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `division_departments_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

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
  ADD CONSTRAINT `employee_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `employees` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `employee_requests_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `employee_requests_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
