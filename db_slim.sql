-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 19, 2026 at 05:30 AM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_slim`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_category`
--

CREATE TABLE `tbl_category` (
  `category_id` int(10) NOT NULL,
  `category_name` varchar(25) NOT NULL,
  `standard_category` decimal(25,2) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_category`
--

INSERT INTO `tbl_category` (`category_id`, `category_name`, `standard_category`, `created_by`, `created_at`, `modified_by`, `modified_at`) VALUES
(1, 'Important', '98.50', NULL, NULL, NULL, NULL),
(2, 'Very Important', '99.00', NULL, NULL, NULL, NULL),
(3, 'Critical', '99.50', NULL, NULL, NULL, NULL),
(4, 'Necessary', NULL, NULL, NULL, NULL, NULL),
(5, 'Others', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_database`
--

CREATE TABLE `tbl_database` (
  `database_id` int(5) NOT NULL,
  `database_name` varchar(50) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_database`
--

INSERT INTO `tbl_database` (`database_id`, `database_name`, `created_by`, `created_at`, `modified_by`, `modified_at`) VALUES
(1, 'DB2', NULL, NULL, NULL, NULL),
(2, 'Oracle', NULL, NULL, NULL, NULL),
(3, 'Ms SQL Server', NULL, NULL, NULL, NULL),
(4, 'CloudSQL for MySQL 8.0', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_deployment`
--

CREATE TABLE `tbl_deployment` (
  `deployment_id` int(11) NOT NULL,
  `deployment_model` varchar(25) DEFAULT NULL,
  `deployment_provider` varchar(25) DEFAULT NULL,
  `main_deployment_site` varchar(25) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_deployment`
--

INSERT INTO `tbl_deployment` (`deployment_id`, `deployment_model`, `deployment_provider`, `main_deployment_site`, `created_by`, `created_at`, `modified_by`, `modified_at`) VALUES
(1, 'On-Premises', 'CIMB', 'Bintaro', NULL, NULL, NULL, NULL),
(2, 'IaaS', 'GCP', 'Asia-southeast2 (Jakarta)', NULL, NULL, NULL, NULL),
(3, 'On-Premises', 'CIMB', 'NTT', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_infra_server`
--

CREATE TABLE `tbl_infra_server` (
  `infra_server_id` int(11) NOT NULL,
  `infra_server_name` varchar(25) NOT NULL,
  `server_sla` decimal(10,2) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_infra_server`
--

INSERT INTO `tbl_infra_server` (`infra_server_id`, `infra_server_name`, `server_sla`, `created_by`, `created_at`, `modified_by`, `modified_at`) VALUES
(1, 'Physical', '99.00', NULL, NULL, NULL, NULL),
(2, 'VM', '94.20', NULL, NULL, NULL, NULL),
(3, 'GCM', '99.50', NULL, NULL, NULL, NULL),
(4, 'AWS', '99.50', NULL, NULL, NULL, NULL),
(5, 'Azure', '99.50', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_network`
--

CREATE TABLE `tbl_network` (
  `network_id` int(11) NOT NULL,
  `network_name` varchar(50) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_network`
--

INSERT INTO `tbl_network` (`network_id`, `network_name`, `created_by`, `created_at`, `modified_by`, `modified_at`) VALUES
(1, 'Customer Facing', NULL, NULL, NULL, NULL),
(2, 'Internal User (Include Branches)', NULL, NULL, NULL, NULL),
(3, 'Internal User (Exclude Branches)', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_operating_software`
--

CREATE TABLE `tbl_operating_software` (
  `operating_software_id` int(5) NOT NULL,
  `operating_software_name` varchar(50) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_operating_software`
--

INSERT INTO `tbl_operating_software` (`operating_software_id`, `operating_software_name`, `created_by`, `created_at`, `modified_by`, `modified_at`) VALUES
(1, 'OS400', NULL, NULL, NULL, NULL),
(2, 'Ms Windows Server', NULL, NULL, NULL, NULL),
(3, 'Solaris', NULL, NULL, NULL, NULL),
(4, 'RHEL', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_operational_day`
--

CREATE TABLE `tbl_operational_day` (
  `operational_day_id` int(10) NOT NULL,
  `start_day` varchar(10) DEFAULT NULL,
  `end_day` varchar(10) DEFAULT NULL,
  `total_day` int(11) GENERATED ALWAYS AS ((field(`end_day`,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') - field(`start_day`,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') + 7) MOD 7 + 1) STORED,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_operational_day`
--

INSERT INTO `tbl_operational_day` (`operational_day_id`, `start_day`, `end_day`, `created_by`, `created_at`, `modified_by`, `modified_at`) VALUES
(1, 'Monday', 'Sunday', NULL, NULL, NULL, NULL),
(2, 'Monday', 'Saturday', NULL, NULL, NULL, NULL),
(3, 'Monday', 'Friday', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_operational_hour`
--

CREATE TABLE `tbl_operational_hour` (
  `operational_hour_id` int(10) NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `total_hour` int(11) GENERATED ALWAYS AS (timestampdiff(HOUR,`start_time`,`end_time`)) STORED,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_operational_hour`
--

INSERT INTO `tbl_operational_hour` (`operational_hour_id`, `start_time`, `end_time`, `created_by`, `created_at`, `modified_by`, `modified_at`) VALUES
(1, '00:00:00', '24:00:00', NULL, NULL, NULL, NULL),
(2, '08:00:00', '18:00:00', NULL, NULL, NULL, NULL),
(3, '08:00:00', '22:00:00', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Vicken', 'vicken@gmail.com', '12345', '2025-12-30 03:52:45', '2025-12-30 09:13:52'),
(2, 'Tes', 'tes@gmail.com', '11111', '2025-12-30 09:11:58', '2025-12-30 09:13:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_category`
--
ALTER TABLE `tbl_category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `tbl_database`
--
ALTER TABLE `tbl_database`
  ADD PRIMARY KEY (`database_id`);

--
-- Indexes for table `tbl_deployment`
--
ALTER TABLE `tbl_deployment`
  ADD PRIMARY KEY (`deployment_id`);

--
-- Indexes for table `tbl_infra_server`
--
ALTER TABLE `tbl_infra_server`
  ADD PRIMARY KEY (`infra_server_id`) USING BTREE;

--
-- Indexes for table `tbl_network`
--
ALTER TABLE `tbl_network`
  ADD PRIMARY KEY (`network_id`);

--
-- Indexes for table `tbl_operating_software`
--
ALTER TABLE `tbl_operating_software`
  ADD PRIMARY KEY (`operating_software_id`);

--
-- Indexes for table `tbl_operational_day`
--
ALTER TABLE `tbl_operational_day`
  ADD PRIMARY KEY (`operational_day_id`) USING BTREE;

--
-- Indexes for table `tbl_operational_hour`
--
ALTER TABLE `tbl_operational_hour`
  ADD PRIMARY KEY (`operational_hour_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_category`
--
ALTER TABLE `tbl_category`
  MODIFY `category_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tbl_database`
--
ALTER TABLE `tbl_database`
  MODIFY `database_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `tbl_deployment`
--
ALTER TABLE `tbl_deployment`
  MODIFY `deployment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_infra_server`
--
ALTER TABLE `tbl_infra_server`
  MODIFY `infra_server_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_network`
--
ALTER TABLE `tbl_network`
  MODIFY `network_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_operating_software`
--
ALTER TABLE `tbl_operating_software`
  MODIFY `operating_software_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tbl_operational_day`
--
ALTER TABLE `tbl_operational_day`
  MODIFY `operational_day_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tbl_operational_hour`
--
ALTER TABLE `tbl_operational_hour`
  MODIFY `operational_hour_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
