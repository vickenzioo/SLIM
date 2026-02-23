-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 23, 2026 at 05:18 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

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
-- Table structure for table `tbl_apps_approval`
--

CREATE TABLE `tbl_apps_approval` (
  `approval_id` int(11) NOT NULL,
  `apps_id` int(11) DEFAULT NULL,
  `user_role_id` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  `current` int(1) DEFAULT 0,
  `remarks` varchar(255) DEFAULT NULL,
  `submit_date` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_apps_approval`
--

INSERT INTO `tbl_apps_approval` (`approval_id`, `apps_id`, `user_role_id`, `status`, `current`, `remarks`, `submit_date`, `created_by`, `created_at`, `modified_by`, `modified_at`) VALUES
(187, 35, 2, 1, 0, 'a', '2026-02-18 15:12:41', 2, '2026-02-18 15:12:41', 2, '2026-02-18 15:12:41'),
(188, 35, 3, 1, 0, 'aaa', '2026-02-18 15:13:45', 2, '2026-02-18 15:12:41', 3, '2026-02-18 15:13:45'),
(189, 35, 4, 1, 0, 'a', '2026-02-19 14:16:58', 4, '2026-02-19 14:16:58', 4, '2026-02-19 14:16:58'),
(190, 35, 5, 1, 0, 'loke', '2026-02-19 14:52:53', 4, '2026-02-19 14:16:58', 5, '2026-02-19 14:52:53'),
(191, 35, 6, 1, 0, 'oke', '2026-02-20 10:04:01', 6, '2026-02-20 10:04:01', 6, '2026-02-20 10:04:01'),
(192, 35, 7, 1, 0, 'oke', '2026-02-20 10:21:10', 6, '2026-02-20 10:04:01', 7, '2026-02-20 10:21:10'),
(193, 35, 8, 1, 0, 'o', '2026-02-20 10:22:41', 8, '2026-02-20 10:22:41', 8, '2026-02-20 10:22:41'),
(194, 36, 2, 1, 0, 'test', '2026-02-22 21:18:42', 2, '2026-02-22 21:18:42', 2, '2026-02-22 21:18:42'),
(195, 36, 3, 1, 0, 'test', '2026-02-22 21:29:36', 2, '2026-02-22 21:18:42', 3, '2026-02-22 21:29:36'),
(196, 36, 4, 1, 0, 'test', '2026-02-23 11:01:02', 4, '2026-02-23 11:01:02', 4, '2026-02-23 11:01:02'),
(197, 36, 5, 0, 1, NULL, NULL, 4, '2026-02-23 11:01:02', NULL, NULL),
(198, 36, 6, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(199, 36, 7, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(200, 36, 8, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_apps_category`
--

CREATE TABLE `tbl_apps_category` (
  `category_id` int(10) NOT NULL,
  `category_name` varchar(25) NOT NULL,
  `standard_category` decimal(25,2) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_apps_category`
--

INSERT INTO `tbl_apps_category` (`category_id`, `category_name`, `standard_category`, `status`, `created_by`, `created_at`, `modified_by`, `modified_at`) VALUES
(1, 'Important', '98.50', 1, NULL, NULL, 2, '2026-01-20 14:18:45'),
(2, 'Very Important', '99.00', 1, NULL, NULL, NULL, NULL),
(3, 'Critical', '99.50', 1, NULL, NULL, NULL, NULL),
(4, 'Necessary', '100.00', 1, NULL, NULL, 2, '2026-02-12 09:05:07'),
(5, 'Others', '0.00', 1, NULL, NULL, 2, '2026-02-12 09:05:21'),
(16, 'Test', '99.50', 0, 2, '2026-02-01 10:19:43', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_apps_database`
--

CREATE TABLE `tbl_apps_database` (
  `id_apps_database` int(11) NOT NULL,
  `apps_id` varchar(255) DEFAULT NULL,
  `database_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_apps_database`
--

INSERT INTO `tbl_apps_database` (`id_apps_database`, `apps_id`, `database_id`) VALUES
(52, '35', '1'),
(53, '35', '2'),
(55, '36', '1'),
(56, '36', '2');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_apps_deployment`
--

CREATE TABLE `tbl_apps_deployment` (
  `deployment_id` int(11) NOT NULL,
  `deployment_model` varchar(25) DEFAULT NULL,
  `deployment_provider` varchar(25) DEFAULT NULL,
  `main_deployment_site` varchar(25) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_apps_deployment`
--

INSERT INTO `tbl_apps_deployment` (`deployment_id`, `deployment_model`, `deployment_provider`, `main_deployment_site`, `status`, `created_by`, `created_at`, `modified_by`, `modified_at`) VALUES
(1, 'On-Premises', 'CIMB', 'Bintaro', 1, NULL, NULL, NULL, NULL),
(2, 'IaaS', 'GCP', 'Asia-southeast2 (Jakarta)', 1, NULL, NULL, NULL, NULL),
(3, 'On-Premises', 'CIMB', 'NTT', 1, NULL, NULL, NULL, NULL),
(13, 'Iaas', 'CIMB', 'Bintaro', 1, 2, '2026-02-01 13:54:50', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_apps_infra`
--

CREATE TABLE `tbl_apps_infra` (
  `apps_infra_id` int(255) NOT NULL,
  `apps_id` int(255) DEFAULT NULL,
  `infra_id` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_apps_infra`
--

INSERT INTO `tbl_apps_infra` (`apps_infra_id`, `apps_id`, `infra_id`) VALUES
(36, 34, 1),
(41, 35, 18),
(42, 35, 19),
(44, 36, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_apps_network`
--

CREATE TABLE `tbl_apps_network` (
  `network_id` int(11) NOT NULL,
  `network_name` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_apps_network`
--

INSERT INTO `tbl_apps_network` (`network_id`, `network_name`, `status`, `created_by`, `created_at`, `modified_by`, `modified_at`) VALUES
(1, 'Customer Facing', 1, NULL, NULL, 2, '2026-01-20 14:12:36'),
(2, 'Internal User (Include Branches)', 1, NULL, NULL, NULL, NULL),
(3, 'Internal User (Exclude Branches)', 1, NULL, NULL, NULL, NULL),
(15, 'tes', 0, 2, '2026-02-09 08:54:32', NULL, NULL),
(16, '-', 0, 3, '2026-02-10 11:13:47', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_apps_operating_software`
--

CREATE TABLE `tbl_apps_operating_software` (
  `id_apps_operating_software` int(11) NOT NULL,
  `apps_id` varchar(255) DEFAULT NULL,
  `operating_software_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_apps_operating_software`
--

INSERT INTO `tbl_apps_operating_software` (`id_apps_operating_software`, `apps_id`, `operating_software_id`) VALUES
(44, '31', '1'),
(45, '32', '1'),
(46, '33', '3'),
(51, '34', '1'),
(52, '34', '2'),
(53, '35', '1'),
(54, '35', '2'),
(55, '36', '1');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_apps_operational_day`
--

CREATE TABLE `tbl_apps_operational_day` (
  `operational_day_id` int(10) NOT NULL,
  `start_day` varchar(10) DEFAULT NULL,
  `end_day` varchar(10) DEFAULT NULL,
  `total_day` int(11) GENERATED ALWAYS AS ((field(`end_day`,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') - field(`start_day`,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') + 7) MOD 7 + 1) STORED,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_apps_operational_day`
--

INSERT INTO `tbl_apps_operational_day` (`operational_day_id`, `start_day`, `end_day`, `status`, `created_by`, `created_at`, `modified_by`, `modified_at`) VALUES
(1, 'Monday', 'Sunday', 1, NULL, NULL, 2, '2026-01-20 14:03:18'),
(2, 'Monday', 'Saturday', 1, NULL, NULL, NULL, NULL),
(3, 'Tuesday', 'Saturday', 1, NULL, NULL, 2, '2026-01-29 13:38:09'),
(19, 'Monday', 'Wednesday', 1, 2, '2026-02-01 15:03:18', NULL, NULL),
(20, 'Monday', 'Thursday', 1, 2, '2026-02-02 09:18:36', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_apps_operational_hour`
--

CREATE TABLE `tbl_apps_operational_hour` (
  `operational_hour_id` int(10) NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `total_hour` decimal(10,1) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_apps_operational_hour`
--

INSERT INTO `tbl_apps_operational_hour` (`operational_hour_id`, `start_time`, `end_time`, `total_hour`, `status`, `created_by`, `created_at`, `modified_by`, `modified_at`) VALUES
(1, '00:00:00', '23:59:00', '24.0', 1, NULL, NULL, 2, '2026-01-20 07:59:46'),
(2, '08:00:00', '18:00:00', '10.0', 1, NULL, NULL, NULL, NULL),
(3, '08:00:00', '22:00:00', '14.0', 1, NULL, NULL, NULL, NULL),
(18, '09:00:00', '10:30:00', '1.5', 1, 2, '2026-01-20 00:00:00', 2, '2026-02-01 14:44:10');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_audit_trail`
--

CREATE TABLE `tbl_audit_trail` (
  `audit_id` int(11) NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp(),
  `username` varchar(100) NOT NULL,
  `action` enum('ADD','EDIT','DEACTIVATE','ACTIVATE','EXPORT') DEFAULT NULL,
  `table_name` varchar(50) NOT NULL,
  `foreign_id` int(11) NOT NULL,
  `field_name` varchar(50) DEFAULT NULL,
  `old_value` text DEFAULT NULL,
  `new_value` text DEFAULT NULL,
  `reason` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_audit_trail`
--

INSERT INTO `tbl_audit_trail` (`audit_id`, `timestamp`, `username`, `action`, `table_name`, `foreign_id`, `field_name`, `old_value`, `new_value`, `reason`) VALUES
(233, '2026-02-08 12:20:27', 'Tes', 'ACTIVATE', 'tbl_database_master', 47, 'status', '0', '1', 'test'),
(234, '2026-02-08 12:20:35', 'Tes', 'DEACTIVATE', 'tbl_database_master', 47, 'status', '1', '0', 'test'),
(235, '2026-02-08 12:20:47', 'Tes', 'ACTIVATE', 'tbl_network_product_junc', 1, 'status', '0', '1', 'test'),
(236, '2026-02-08 12:25:16', 'Tes', 'DEACTIVATE', 'tbl_network_product_junc', 7, 'status', '1', '0', 'test'),
(237, '2026-02-08 12:28:11', 'Tes', 'ACTIVATE', 'tbl_network_product_junc', 7, 'status', '0', '1', 'TEST'),
(238, '2026-02-08 12:28:51', 'Tes', 'DEACTIVATE', 'tbl_network_product_junc', 7, 'status', '1', '0', 'TEST'),
(239, '2026-02-08 12:28:59', 'Tes', 'ACTIVATE', 'tbl_database_master', 47, 'status', '0', '1', 'TEST'),
(240, '2026-02-08 12:30:05', 'Tes', 'ADD', 'tbl_network_provider', 10, 'Provider Name', '-', 'test', 'Initial Creation'),
(241, '2026-02-08 12:30:05', 'Tes', 'ADD', 'tbl_network_provider', 10, 'Network Name', '-', 'Internal User (Exclude Branches)', 'Initial Creation'),
(242, '2026-02-08 12:30:44', 'Tes', 'DEACTIVATE', 'tbl_network_provider_junc', 34, 'status', '1', '0', 'test'),
(243, '2026-02-08 12:31:05', 'Tes', 'ACTIVATE', 'tbl_network_provider_junc', 33, 'status', '0', '1', 'test'),
(244, '2026-02-08 13:45:39', 'Tes', 'EDIT', 'tbl_database_master', 47, 'database_name', 'DB234', 'DB2345', 'test'),
(245, '2026-02-08 13:50:15', 'Tes', 'ADD', 'tbl_network_product', 13, 'Product Name', '-', 'test', 'Initial Creation'),
(246, '2026-02-08 13:50:15', 'Tes', 'ADD', 'tbl_network_product', 13, 'Product SLA', '-', '98.50', 'Initial Creation'),
(247, '2026-02-08 13:50:15', 'Tes', 'ADD', 'tbl_network_product', 13, 'Network Name', '-', 'Customer Facing', 'Initial Creation'),
(248, '2026-02-08 13:51:35', 'Tes', 'DEACTIVATE', 'tbl_network_product_junc', 36, 'status', '1', '0', 'test'),
(249, '2026-02-08 13:58:45', 'Tes', 'ACTIVATE', 'tbl_network_product', 13, 'Status', 'Non Active', 'Active', 'test'),
(250, '2026-02-08 13:58:55', 'Tes', 'DEACTIVATE', 'tbl_network_product', 13, 'Status', 'Active', 'Non Active', 'test'),
(251, '2026-02-08 14:02:23', 'Tes', 'ADD', 'tbl_network_provider', 11, 'Provider Name', '-', 'test', 'Initial Creation'),
(252, '2026-02-08 14:02:23', 'Tes', 'ADD', 'tbl_network_provider', 11, 'Network Name', '-', 'Customer Facing', 'Initial Creation'),
(253, '2026-02-08 14:02:39', 'Tes', 'DEACTIVATE', 'tbl_network_provider', 11, 'Status', 'Active', 'Non Active', 'TEST'),
(254, '2026-02-08 14:02:50', 'Tes', 'ACTIVATE', 'tbl_network_provider', 11, 'Status', 'Non Active', 'Active', 'TEST'),
(255, '2026-02-09 08:54:32', 'Tes', 'ADD', 'tbl_apps_network', 15, 'network_name', '-', 'tes', 'Initial Creation'),
(256, '2026-02-09 08:54:42', 'Tes', 'DEACTIVATE', 'tbl_apps_network', 15, 'status', '1', '0', 'test'),
(257, '2026-02-09 08:54:56', 'Tes', 'ACTIVATE', 'tbl_apps_network', 1, 'status', '0', '1', 'tes'),
(258, '2026-02-09 08:55:12', 'Tes', 'ACTIVATE', 'tbl_network_product', 6, 'Status', 'Non Active', 'Active', 'test'),
(259, '2026-02-09 13:54:00', 'Tes', 'DEACTIVATE', 'users', 4, 'status', '1', '0', 'TEST'),
(260, '2026-02-09 13:54:13', 'Tes', 'ACTIVATE', 'users', 4, 'status', '0', '1', 'TEST'),
(261, '2026-02-10 11:13:47', 'Dinda Aulia', 'ADD', 'tbl_apps_network', 16, 'network_name', '-', '-', 'Initial Creation'),
(262, '2026-02-10 13:30:51', 'Dinda Aulia', 'DEACTIVATE', 'tbl_apps_category', 16, 'status', '1', '0', 'test'),
(263, '2026-02-11 15:26:09', 'Tes', 'ADD', 'tbl_database_master', 49, 'database_name', '-', 'test', 'Initial Creation'),
(264, '2026-02-11 15:26:18', 'Tes', 'DEACTIVATE', 'tbl_database_master', 49, 'status', '1', '0', 'test'),
(265, '2026-02-12 09:05:07', 'Tes', 'EDIT', 'tbl_apps_category', 4, 'Standard Category', NULL, '0', 'ubah standard category'),
(266, '2026-02-12 09:05:21', 'Tes', 'EDIT', 'tbl_apps_category', 5, 'Standard Category', NULL, '0', 'ubah standard category'),
(267, '2026-02-12 09:47:32', 'Tes', 'DEACTIVATE', 'tbl_database_master', 47, 'status', '1', '0', 'test'),
(268, '2026-02-12 09:47:37', 'Tes', 'ACTIVATE', 'tbl_database_master', 4, 'status', '0', '1', 'tets'),
(269, '2026-02-12 10:35:17', 'Tes', 'EXPORT', 'tbl_database_master', 0, '-', '-', '-', 'Export Data'),
(270, '2026-02-13 09:07:26', 'Tes', 'ADD', 'tbl_holiday', 22, 'Holiday Name', '-', 'Imlek', 'Initial creation'),
(271, '2026-02-13 09:07:26', 'Tes', 'ADD', 'tbl_holiday', 22, 'Holiday Date', '-', '2026-02-17', 'Initial creation'),
(272, '2026-02-13 09:07:26', 'Tes', 'ADD', 'tbl_holiday', 22, 'Holiday Description', '-', 'test', 'Initial creation'),
(273, '2026-02-13 09:45:00', 'Tes', 'EXPORT', 'tbl_user_role', 0, '-', '-', '-', 'Export Data'),
(274, '2026-02-13 09:53:13', 'Tes', 'EXPORT', 'tbl_user_role', 0, '-', '-', '-', 'Export Data'),
(275, '2026-02-13 09:55:22', 'Tes', 'EXPORT', 'tbl_user_role', 0, '-', '-', '-', 'Export Data'),
(276, '2026-02-13 10:00:40', 'Tes', 'EXPORT', 'tbl_user_role', 0, '-', '-', '-', 'Export Data'),
(277, '2026-02-13 13:55:32', 'Tes', 'EXPORT', 'tbl_portofolio_apps_master', 0, NULL, '-', '-', 'Export Data'),
(278, '2026-02-13 13:57:51', 'Tes', 'EXPORT', 'tbl_portofolio_apps_master', 0, NULL, '-', '-', 'Export Data'),
(279, '2026-02-19 15:37:40', 'Tes', 'ADD', 'tbl_infra_module', 11, 'module_name', '-', 'tes', 'Initial Creation'),
(280, '2026-02-19 15:37:56', 'Tes', 'DEACTIVATE', 'tbl_infra_module', 11, 'status', '1', '0', 'tes'),
(281, '2026-02-19 20:48:33', 'Tes', 'ACTIVATE', 'tbl_infra_module', 11, 'status', '0', '1', 'test'),
(282, '2026-02-19 20:48:46', 'Tes', 'DEACTIVATE', 'tbl_infra_module', 11, 'status', '1', '0', 'test'),
(283, '2026-02-19 20:59:22', 'Tes', 'DEACTIVATE', 'tbl_apps_network', 16, 'status', '1', '0', 'TEST'),
(284, '2026-02-19 20:59:57', 'Tes', 'EDIT', 'tbl_service', 1, 'service_name', 'Actimize', 'Actimize', 'test'),
(285, '2026-02-19 21:09:47', 'Tes', 'EDIT', 'tbl_service', 1, 'service_name', 'Actimize', 'ALM', 'TEST'),
(286, '2026-02-19 21:10:11', 'Tes', 'EDIT', 'tbl_service', 1, 'service_name', 'ALM', 'Actimize', 'test'),
(287, '2026-02-20 08:20:05', 'Tes', 'ADD', 'tbl_infra_module', 12, 'module_name', '-', 'TEST', 'Initial Creation'),
(288, '2026-02-20 08:20:31', 'Tes', 'ADD', 'tbl_service', 16, 'service_name', '-', 'TEST', 'Initial Creation'),
(289, '2026-02-20 08:21:11', 'Tes', 'DEACTIVATE', 'tbl_service', 16, 'status', '1', '0', 'TEST'),
(290, '2026-02-20 15:12:09', 'role1', 'EXPORT', 'tbl_infra_module', 0, '-', '-', '-', 'Export Data'),
(291, '2026-02-20 15:12:46', 'Tes', 'EXPORT', 'tbl_apps_network', 0, '-', '-', '-', 'Export Data');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_database_master`
--

CREATE TABLE `tbl_database_master` (
  `database_id` int(5) NOT NULL,
  `database_name` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_database_master`
--

INSERT INTO `tbl_database_master` (`database_id`, `database_name`, `status`, `created_by`, `created_at`, `modified_by`, `modified_at`) VALUES
(1, 'DB2', 1, NULL, NULL, 2, '2026-01-20 00:00:00'),
(2, 'Oracle', 1, NULL, NULL, NULL, NULL),
(3, 'Ms SQL Server', 1, NULL, NULL, NULL, NULL),
(4, 'CloudSQL for MySQL 8.0', 1, NULL, NULL, NULL, NULL),
(47, 'DB2345', 0, 2, '2026-01-29 19:42:27', 2, '2026-02-08 13:45:39'),
(48, 'DB24', 0, 2, '2026-01-29 20:48:39', NULL, NULL),
(49, 'test', 0, 2, '2026-02-11 15:26:09', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_holiday`
--

CREATE TABLE `tbl_holiday` (
  `Holiday_ID` int(11) NOT NULL,
  `Holiday_Date` date NOT NULL,
  `Holiday_Description` varchar(50) DEFAULT NULL,
  `Holiday_Name` varchar(25) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_at` int(11) DEFAULT NULL,
  `modified_by` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_holiday`
--

INSERT INTO `tbl_holiday` (`Holiday_ID`, `Holiday_Date`, `Holiday_Description`, `Holiday_Name`, `created_by`, `created_at`, `modified_at`, `modified_by`) VALUES
(1, '2026-02-16', 'Tahun Baru Imlek', 'Cuti Bersama', NULL, NULL, NULL, NULL),
(5, '1970-01-01', 'test', 'Libur Test', NULL, NULL, NULL, NULL),
(6, '1970-01-01', 'Test', 'Libur Test', NULL, NULL, NULL, NULL),
(7, '2026-01-24', 'TEST', 'Libur Test', NULL, NULL, NULL, NULL),
(8, '2026-01-22', 'test', 'Libur Test', NULL, NULL, NULL, NULL),
(9, '2026-01-31', 'test', 'Libur Test', NULL, NULL, NULL, NULL),
(10, '2026-02-11', 'test', 'Libur Test', NULL, NULL, NULL, NULL),
(11, '2026-01-28', 'test', 'test', NULL, NULL, NULL, NULL),
(13, '2026-01-29', '-', 'Libur Test', NULL, NULL, NULL, NULL),
(14, '2026-02-13', 'tEST', 'Libur Test', NULL, NULL, NULL, NULL),
(15, '2026-03-13', 'test', 'Libur Test', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_infra_server`
--

CREATE TABLE `tbl_infra_server` (
  `infra_server_id` int(11) NOT NULL,
  `infra_id` int(11) DEFAULT NULL,
  `server_id` int(11) DEFAULT NULL,
  `server_web_prod_count` varchar(255) DEFAULT NULL,
  `server_app_prod_count` varchar(255) DEFAULT NULL,
  `server_db_prod_count` varchar(255) DEFAULT NULL,
  `server_web_dr_count` varchar(255) DEFAULT NULL,
  `server_app_dr_count` varchar(255) DEFAULT NULL,
  `server_db_dr_count` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_infra_server`
--

INSERT INTO `tbl_infra_server` (`infra_server_id`, `infra_id`, `server_id`, `server_web_prod_count`, `server_app_prod_count`, `server_db_prod_count`, `server_web_dr_count`, `server_app_dr_count`, `server_db_dr_count`) VALUES
(3, 7, 0, '0', '0', '0', '0', '0', '0'),
(4, 18, 1, '0', '1', '0', '0', '1', '0'),
(5, 19, 2, '0', '1', '0', '0', '1', '0'),
(6, 1, 1, '2', '0', '1', '1', '0', '1');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_module`
--

CREATE TABLE `tbl_module` (
  `module_id` int(11) NOT NULL,
  `module_name` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `modified_by` varchar(100) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_module`
--

INSERT INTO `tbl_module` (`module_id`, `module_name`, `status`, `created_by`, `created_at`, `modified_by`, `modified_at`) VALUES
(1, 'Actimize', 1, NULL, '2026-02-18 10:28:53', NULL, NULL),
(2, 'ALM', 1, NULL, '2026-02-18 10:28:53', NULL, NULL),
(3, 'AML', 1, NULL, '2026-02-18 10:28:53', NULL, NULL),
(4, 'GatotKaca', 1, NULL, '2026-02-18 10:28:53', NULL, NULL),
(5, 'CREDIT', 1, NULL, '2026-02-18 10:28:53', NULL, NULL),
(6, 'ETP', 1, NULL, '2026-02-18 10:28:53', NULL, NULL),
(7, 'Trade', 1, NULL, '2026-02-18 10:28:53', NULL, NULL),
(8, 'BERPESTA', 1, NULL, '2026-02-18 10:28:53', NULL, NULL),
(9, 'FAST', 1, NULL, '2026-02-18 10:28:53', NULL, NULL),
(10, 'BIZ', 1, NULL, '2026-02-18 10:28:53', NULL, NULL),
(11, 'tes', 0, '2', '2026-02-19 15:37:40', NULL, '2026-02-19 20:48:46'),
(12, 'TEST', 1, '2', '2026-02-20 08:20:05', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_network_product`
--

CREATE TABLE `tbl_network_product` (
  `product_id` int(11) NOT NULL COMMENT 'Id Network Produk',
  `product_name` varchar(255) DEFAULT NULL COMMENT 'Nama Network Produk',
  `product_sla` decimal(10,2) DEFAULT NULL COMMENT 'SLA Network',
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_network_product`
--

INSERT INTO `tbl_network_product` (`product_id`, `product_name`, `product_sla`, `status`, `created_by`, `created_at`, `modified_by`, `modified_at`) VALUES
(1, 'Internet_SDWAN', '98.50', 1, NULL, NULL, NULL, NULL),
(2, 'DWDM', '98.50', 1, NULL, NULL, NULL, NULL),
(3, 'MPLS', '98.50', 1, NULL, NULL, 2, '2026-01-26 03:05:18'),
(4, 'Metro-E', '98.50', 1, NULL, NULL, NULL, NULL),
(5, 'Mobil-KAS', '98.50', 1, NULL, NULL, NULL, NULL),
(6, 'PaymentPoint', '98.50', 1, NULL, NULL, NULL, NULL),
(7, 'CallCenter', '98.50', 1, NULL, NULL, NULL, NULL),
(8, 'NAC', '98.50', 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_network_product_junc`
--

CREATE TABLE `tbl_network_product_junc` (
  `network_product_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `network_id` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_network_product_junc`
--

INSERT INTO `tbl_network_product_junc` (`network_product_id`, `product_id`, `network_id`, `status`) VALUES
(1, 1, 1, 1),
(2, 1, 2, 1),
(3, 2, 3, 1),
(5, 4, NULL, 1),
(6, 5, NULL, 1),
(7, 6, NULL, 1),
(8, 7, NULL, 1),
(9, 8, NULL, 1),
(30, 3, 2, 1),
(31, 9, 1, 1),
(32, 10, 2, 1),
(34, 11, 3, 1),
(35, 12, 1, 1),
(36, 13, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_network_provider`
--

CREATE TABLE `tbl_network_provider` (
  `provider_id` int(11) NOT NULL COMMENT 'Id Network Provider',
  `provider_name` varchar(255) DEFAULT NULL COMMENT 'Nama Network Provider',
  `status` tinyint(1) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_network_provider`
--

INSERT INTO `tbl_network_provider` (`provider_id`, `provider_name`, `status`, `created_by`, `created_at`, `modified_by`, `modified_at`) VALUES
(1, 'FiberStar', 1, NULL, NULL, NULL, NULL),
(2, 'iForte', 1, NULL, NULL, NULL, NULL),
(3, 'Indosat', 1, NULL, NULL, NULL, NULL),
(4, 'LinkNet', 1, NULL, NULL, NULL, NULL),
(5, 'Lintasarta', 1, NULL, NULL, NULL, NULL),
(6, 'Telkom', 1, NULL, NULL, NULL, NULL),
(7, 'XL', 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_network_provider_junc`
--

CREATE TABLE `tbl_network_provider_junc` (
  `network_provider_id` int(11) NOT NULL,
  `provider_id` int(11) DEFAULT NULL,
  `network_id` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_network_provider_junc`
--

INSERT INTO `tbl_network_provider_junc` (`network_provider_id`, `provider_id`, `network_id`, `status`) VALUES
(1, 1, 1, 1),
(5, 3, 2, 1),
(6, 3, 3, 1),
(7, 3, 1, 1),
(10, 4, 3, 1),
(11, 4, 2, 1),
(12, 4, NULL, 1),
(13, 5, 2, 1),
(14, 5, 3, 1),
(17, 5, NULL, 1),
(18, 6, 2, 1),
(19, 6, NULL, 1),
(20, 6, 3, 1),
(23, 6, 1, 1),
(26, 7, 2, 1),
(27, 7, 1, 1),
(29, 7, NULL, 1),
(31, 8, 3, 1),
(32, 2, 3, 1),
(35, 11, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_operating_software`
--

CREATE TABLE `tbl_operating_software` (
  `operating_software_id` int(5) NOT NULL,
  `operating_software_name` varchar(50) NOT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_operating_software`
--

INSERT INTO `tbl_operating_software` (`operating_software_id`, `operating_software_name`, `status`, `created_by`, `created_at`, `modified_by`, `modified_at`) VALUES
(1, 'OS400', 1, NULL, NULL, 2, '2026-01-20 14:25:48'),
(2, 'Ms Windows Server', 1, NULL, NULL, NULL, NULL),
(3, 'Solaris', 1, NULL, NULL, NULL, NULL),
(4, 'RHEL', 1, NULL, NULL, NULL, NULL),
(15, 'OS401', 1, 2, '2026-02-01 11:22:26', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_portofolio_apps_master`
--

CREATE TABLE `tbl_portofolio_apps_master` (
  `apps_id` int(255) NOT NULL,
  `network_id` int(255) DEFAULT NULL,
  `deployment_id` int(255) DEFAULT NULL,
  `category_id` int(255) DEFAULT NULL,
  `operational_hour_id` int(255) DEFAULT NULL,
  `operational_day_id` int(255) DEFAULT NULL,
  `resilience_id` int(255) DEFAULT NULL,
  `short_name` varchar(255) DEFAULT NULL,
  `application_name` varchar(255) DEFAULT NULL,
  `module` varchar(255) DEFAULT NULL,
  `apps_description` varchar(255) DEFAULT NULL,
  `application_type` varchar(255) DEFAULT NULL,
  `live_year` varchar(255) DEFAULT NULL,
  `decommission_year` varchar(255) DEFAULT NULL,
  `principle_name` varchar(255) DEFAULT NULL,
  `principle_solution_name` varchar(255) DEFAULT NULL,
  `nik_owner_head` varchar(255) DEFAULT NULL,
  `nik_owner` varchar(255) DEFAULT NULL,
  `nik_it_department` varchar(255) DEFAULT NULL,
  `owner_directorate` varchar(255) DEFAULT NULL,
  `owner_subdirectorate` varchar(255) DEFAULT NULL,
  `it_group_name` varchar(255) DEFAULT NULL,
  `it_division_name` varchar(255) DEFAULT NULL,
  `owner_title` varchar(255) DEFAULT NULL,
  `flash_copy` varchar(255) DEFAULT NULL,
  `end_of_day` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_portofolio_apps_master`
--

INSERT INTO `tbl_portofolio_apps_master` (`apps_id`, `network_id`, `deployment_id`, `category_id`, `operational_hour_id`, `operational_day_id`, `resilience_id`, `short_name`, `application_name`, `module`, `apps_description`, `application_type`, `live_year`, `decommission_year`, `principle_name`, `principle_solution_name`, `nik_owner_head`, `nik_owner`, `nik_it_department`, `owner_directorate`, `owner_subdirectorate`, `it_group_name`, `it_division_name`, `owner_title`, `flash_copy`, `end_of_day`, `created_by`, `created_at`, `modified_by`, `modified_at`, `approved_by`, `approved_at`) VALUES
(35, 1, 1, 4, 3, 1, 4, 'a', 'a', NULL, 'a', 'Off the shelf', '2111', '2222', 'tes', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'Y', 'Y', 2, '2026-02-18 15:12:41', 6, '2026-02-20 10:04:01', NULL, NULL),
(36, 2, 2, 1, NULL, NULL, 2, 'T', 'Test', NULL, 'test', 'Off the shelf', '2025', '2030', 'TEST', 'TEST', NULL, NULL, NULL, 'TEST', 'TEST', 'TEST', 'TEST', 'TEST', 'Y', 'Y', 2, '2026-02-22 21:07:41', 2, '2026-02-22 21:18:42', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_portofolio_infra_master`
--

CREATE TABLE `tbl_portofolio_infra_master` (
  `infra_id` varchar(255) NOT NULL,
  `module_id` int(11) NOT NULL,
  `service_id` int(11) DEFAULT NULL,
  `resilience_id` int(11) DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `modified_by` varchar(100) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_portofolio_infra_master`
--

INSERT INTO `tbl_portofolio_infra_master` (`infra_id`, `module_id`, `service_id`, `resilience_id`, `created_by`, `created_at`, `modified_by`, `modified_at`) VALUES
('1', 1, 1, 2, NULL, '2026-02-18 10:27:40', NULL, '2026-02-20 08:43:17'),
('10', 2, 2, 2, NULL, '2026-02-18 10:27:40', NULL, '2026-02-20 08:43:17'),
('11', 3, 3, 2, NULL, '2026-02-18 10:27:40', NULL, '2026-02-20 08:43:17'),
('12', 3, 4, 3, NULL, '2026-02-18 10:27:40', NULL, '2026-02-20 08:43:17'),
('13', 4, 5, 3, NULL, '2026-02-18 10:27:40', NULL, '2026-02-20 08:43:17'),
('14', 5, 6, 2, NULL, '2026-02-18 10:27:40', NULL, '2026-02-20 08:43:17'),
('15', 5, 7, 2, NULL, '2026-02-18 10:27:40', NULL, '2026-02-20 08:43:17'),
('2', 6, 8, 2, NULL, '2026-02-18 10:27:40', NULL, '2026-02-20 08:43:17'),
('3', 6, 9, 2, NULL, '2026-02-18 10:27:40', NULL, '2026-02-20 08:43:17'),
('4', 7, 10, 2, NULL, '2026-02-18 10:27:40', NULL, '2026-02-20 08:43:17'),
('5', 8, 11, 4, NULL, '2026-02-18 10:27:40', NULL, '2026-02-20 08:43:17'),
('6', 9, 12, 2, NULL, '2026-02-18 10:27:40', NULL, '2026-02-20 08:43:17'),
('7', 9, 13, 3, NULL, '2026-02-18 10:27:40', NULL, '2026-02-20 08:43:17'),
('8', 10, 14, 2, NULL, '2026-02-18 10:27:40', NULL, '2026-02-20 08:43:17'),
('9', 10, 15, 2, NULL, '2026-02-18 10:27:40', NULL, '2026-02-20 08:43:17');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_provider_junc`
--

CREATE TABLE `tbl_product_provider_junc` (
  `produk_provider_id` int(11) NOT NULL,
  `produk_id` int(11) DEFAULT NULL,
  `provider_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_product_provider_junc`
--

INSERT INTO `tbl_product_provider_junc` (`produk_provider_id`, `produk_id`, `provider_id`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 2, 1),
(4, 3, 3),
(5, 3, 2),
(6, 3, 1),
(7, 4, 3),
(8, 4, 4),
(9, 4, 2),
(10, 4, 1),
(11, 4, 5),
(12, 5, 3),
(13, 5, 2),
(14, 5, 6),
(15, 5, 1),
(16, 5, 5),
(17, 6, 3),
(18, 6, 4),
(19, 6, 2),
(20, 6, 6),
(21, 6, 7),
(22, 6, 1),
(23, 6, 8),
(24, 7, 3),
(25, 7, 1),
(26, 7, 9);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_resilience`
--

CREATE TABLE `tbl_resilience` (
  `resilience_id` int(11) NOT NULL,
  `resilience_category` varchar(10) NOT NULL,
  `dr` varchar(5) DEFAULT NULL,
  `ha` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_resilience`
--

INSERT INTO `tbl_resilience` (`resilience_id`, `resilience_category`, `dr`, `ha`) VALUES
(1, 'L0', 'N', ''),
(2, 'L1', 'Y', 'N'),
(3, 'L2', 'Y', 'Y'),
(4, 'L3', 'Y', 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_role`
--

CREATE TABLE `tbl_role` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_role`
--

INSERT INTO `tbl_role` (`role_id`, `role_name`) VALUES
(1, 'IT SLM'),
(2, 'EA Apps Inputter'),
(3, 'EA Apps Approver'),
(4, 'EA Infra Inputter'),
(5, 'EA Infra Approver'),
(6, 'BU Inputter'),
(7, 'BU Approver'),
(8, 'IT Dev');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_server`
--

CREATE TABLE `tbl_server` (
  `server_id` int(11) NOT NULL,
  `server_name` varchar(25) NOT NULL,
  `server_sla` decimal(10,2) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_server`
--

INSERT INTO `tbl_server` (`server_id`, `server_name`, `server_sla`, `created_by`, `created_at`, `modified_by`, `modified_at`) VALUES
(1, 'Physical', '99.00', NULL, NULL, NULL, NULL),
(2, 'VM', '99.42', NULL, NULL, NULL, NULL),
(3, 'GCM', '99.50', NULL, NULL, NULL, NULL),
(4, 'AWS', '99.50', NULL, NULL, NULL, NULL),
(5, 'Azure', '99.50', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_service`
--

CREATE TABLE `tbl_service` (
  `service_id` int(10) NOT NULL,
  `service_name` varchar(250) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `modified_by` varchar(100) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_service`
--

INSERT INTO `tbl_service` (`service_id`, `service_name`, `status`, `created_by`, `created_at`, `modified_by`, `modified_at`) VALUES
(1, 'Actimize', 1, NULL, '2026-02-18 10:29:02', '2', '2026-02-19 21:10:11'),
(2, 'Trade', 1, NULL, '2026-02-18 10:29:02', NULL, '2026-02-19 09:23:30'),
(3, 'BERPESTA', 1, NULL, '2026-02-18 10:29:02', NULL, '2026-02-19 09:23:30'),
(4, 'FAST-CONV', 1, NULL, '2026-02-18 10:29:02', NULL, '2026-02-19 09:23:30'),
(5, 'FAS-SYARIA', 1, NULL, '2026-02-18 10:29:02', NULL, '2026-02-19 09:23:30'),
(6, 'BIZ-MOBILE', 1, NULL, '2026-02-18 10:29:02', NULL, '2026-02-19 09:23:30'),
(7, 'BIZ-VM', 1, NULL, '2026-02-18 10:29:02', NULL, '2026-02-19 09:23:30'),
(8, 'ALM', 1, NULL, '2026-02-18 10:29:02', NULL, '2026-02-19 09:23:30'),
(9, 'AML DB', 1, NULL, '2026-02-18 10:29:02', NULL, '2026-02-19 09:23:30'),
(10, 'AML APPS', 1, NULL, '2026-02-18 10:29:02', NULL, '2026-02-19 09:23:30'),
(11, 'GatotKaca', 1, NULL, '2026-02-18 10:29:02', NULL, '2026-02-19 09:23:30'),
(12, 'CREDIT-CORE', 1, NULL, '2026-02-18 10:29:02', NULL, '2026-02-19 09:23:30'),
(13, 'CREDIT-CORE-IM-ASP', 1, NULL, '2026-02-18 10:29:02', NULL, '2026-02-19 09:23:30'),
(14, 'ETP-CONV', 1, NULL, '2026-02-18 10:29:02', NULL, '2026-02-19 09:23:30'),
(15, 'ETP-SYARIA', 1, NULL, '2026-02-18 10:29:02', NULL, '2026-02-19 09:23:30'),
(16, 'TEST', 0, '2', '2026-02-20 08:20:31', NULL, '2026-02-20 08:21:11');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_role`
--

CREATE TABLE `tbl_user_role` (
  `user_role_id` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user_role`
--

INSERT INTO `tbl_user_role` (`user_role_id`, `id`, `role_id`, `created_by`, `created_at`, `modified_by`, `modified_at`) VALUES
(1, 1, 1, NULL, NULL, NULL, NULL),
(2, 2, 2, NULL, NULL, NULL, NULL),
(3, 3, 3, NULL, NULL, 2, '2026-01-23 04:55:10'),
(4, 4, 4, 2, '2026-01-26 08:42:51', NULL, NULL),
(5, 5, 5, 2, '2026-01-26 09:20:01', NULL, NULL),
(9, 6, 6, 2, '2026-01-29 10:34:35', NULL, NULL),
(11, 7, 7, NULL, NULL, NULL, NULL),
(12, 8, 8, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `status`, `created_at`, `updated_at`) VALUES
(1, 'role1', 'role1@gmail.com', '11111', 1, '2025-12-29 20:52:45', '2026-02-19 19:16:05'),
(2, 'role2', 'role2@gmail.com', '11111', 1, '2025-12-30 02:11:58', '2026-02-19 19:16:06'),
(3, 'role3', 'role3@gmail.com', '11111', 1, '2026-01-22 19:27:33', '2026-02-19 19:16:06'),
(4, 'role4', 'role4@gmail.com', '11111', 1, '2026-02-06 00:26:11', '2026-02-19 19:16:10'),
(5, 'role5', 'role5@gmail.com', '11111', 1, '2026-02-06 00:26:21', '2026-02-19 19:16:06'),
(6, 'role6', 'role6@gmail.com', '11111', 1, '2026-02-12 02:53:04', '2026-02-19 19:16:09'),
(7, 'role7', 'role7@gmail.com', '11111', 1, '2026-02-12 02:55:13', '2026-02-19 20:07:13'),
(8, 'role8', 'role8@gmail.com', '11111', 1, '2026-02-12 02:59:23', '2026-02-19 20:07:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_apps_approval`
--
ALTER TABLE `tbl_apps_approval`
  ADD PRIMARY KEY (`approval_id`) USING BTREE;

--
-- Indexes for table `tbl_apps_category`
--
ALTER TABLE `tbl_apps_category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `tbl_apps_database`
--
ALTER TABLE `tbl_apps_database`
  ADD PRIMARY KEY (`id_apps_database`);

--
-- Indexes for table `tbl_apps_deployment`
--
ALTER TABLE `tbl_apps_deployment`
  ADD PRIMARY KEY (`deployment_id`);

--
-- Indexes for table `tbl_apps_infra`
--
ALTER TABLE `tbl_apps_infra`
  ADD PRIMARY KEY (`apps_infra_id`);

--
-- Indexes for table `tbl_apps_network`
--
ALTER TABLE `tbl_apps_network`
  ADD PRIMARY KEY (`network_id`);

--
-- Indexes for table `tbl_apps_operating_software`
--
ALTER TABLE `tbl_apps_operating_software`
  ADD PRIMARY KEY (`id_apps_operating_software`);

--
-- Indexes for table `tbl_apps_operational_day`
--
ALTER TABLE `tbl_apps_operational_day`
  ADD PRIMARY KEY (`operational_day_id`) USING BTREE;

--
-- Indexes for table `tbl_apps_operational_hour`
--
ALTER TABLE `tbl_apps_operational_hour`
  ADD PRIMARY KEY (`operational_hour_id`);

--
-- Indexes for table `tbl_audit_trail`
--
ALTER TABLE `tbl_audit_trail`
  ADD PRIMARY KEY (`audit_id`);

--
-- Indexes for table `tbl_database_master`
--
ALTER TABLE `tbl_database_master`
  ADD PRIMARY KEY (`database_id`);

--
-- Indexes for table `tbl_holiday`
--
ALTER TABLE `tbl_holiday`
  ADD PRIMARY KEY (`Holiday_ID`);

--
-- Indexes for table `tbl_infra_server`
--
ALTER TABLE `tbl_infra_server`
  ADD PRIMARY KEY (`infra_server_id`);

--
-- Indexes for table `tbl_module`
--
ALTER TABLE `tbl_module`
  ADD PRIMARY KEY (`module_id`);

--
-- Indexes for table `tbl_network_product`
--
ALTER TABLE `tbl_network_product`
  ADD PRIMARY KEY (`product_id`) USING BTREE;

--
-- Indexes for table `tbl_network_product_junc`
--
ALTER TABLE `tbl_network_product_junc`
  ADD PRIMARY KEY (`network_product_id`) USING BTREE;

--
-- Indexes for table `tbl_network_provider`
--
ALTER TABLE `tbl_network_provider`
  ADD PRIMARY KEY (`provider_id`);

--
-- Indexes for table `tbl_network_provider_junc`
--
ALTER TABLE `tbl_network_provider_junc`
  ADD PRIMARY KEY (`network_provider_id`);

--
-- Indexes for table `tbl_operating_software`
--
ALTER TABLE `tbl_operating_software`
  ADD PRIMARY KEY (`operating_software_id`);

--
-- Indexes for table `tbl_portofolio_apps_master`
--
ALTER TABLE `tbl_portofolio_apps_master`
  ADD PRIMARY KEY (`apps_id`);

--
-- Indexes for table `tbl_portofolio_infra_master`
--
ALTER TABLE `tbl_portofolio_infra_master`
  ADD PRIMARY KEY (`infra_id`),
  ADD KEY `fk_infra_resilience` (`resilience_id`);

--
-- Indexes for table `tbl_product_provider_junc`
--
ALTER TABLE `tbl_product_provider_junc`
  ADD PRIMARY KEY (`produk_provider_id`);

--
-- Indexes for table `tbl_resilience`
--
ALTER TABLE `tbl_resilience`
  ADD PRIMARY KEY (`resilience_id`);

--
-- Indexes for table `tbl_role`
--
ALTER TABLE `tbl_role`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `tbl_server`
--
ALTER TABLE `tbl_server`
  ADD PRIMARY KEY (`server_id`) USING BTREE;

--
-- Indexes for table `tbl_service`
--
ALTER TABLE `tbl_service`
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `tbl_user_role`
--
ALTER TABLE `tbl_user_role`
  ADD PRIMARY KEY (`user_role_id`),
  ADD KEY `user_id` (`id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `id` (`id`);

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
-- AUTO_INCREMENT for table `tbl_apps_approval`
--
ALTER TABLE `tbl_apps_approval`
  MODIFY `approval_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201;

--
-- AUTO_INCREMENT for table `tbl_apps_category`
--
ALTER TABLE `tbl_apps_category`
  MODIFY `category_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tbl_apps_database`
--
ALTER TABLE `tbl_apps_database`
  MODIFY `id_apps_database` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `tbl_apps_deployment`
--
ALTER TABLE `tbl_apps_deployment`
  MODIFY `deployment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_apps_infra`
--
ALTER TABLE `tbl_apps_infra`
  MODIFY `apps_infra_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `tbl_apps_network`
--
ALTER TABLE `tbl_apps_network`
  MODIFY `network_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tbl_apps_operating_software`
--
ALTER TABLE `tbl_apps_operating_software`
  MODIFY `id_apps_operating_software` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `tbl_apps_operational_day`
--
ALTER TABLE `tbl_apps_operational_day`
  MODIFY `operational_day_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tbl_apps_operational_hour`
--
ALTER TABLE `tbl_apps_operational_hour`
  MODIFY `operational_hour_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tbl_audit_trail`
--
ALTER TABLE `tbl_audit_trail`
  MODIFY `audit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=292;

--
-- AUTO_INCREMENT for table `tbl_database_master`
--
ALTER TABLE `tbl_database_master`
  MODIFY `database_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `tbl_holiday`
--
ALTER TABLE `tbl_holiday`
  MODIFY `Holiday_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tbl_infra_server`
--
ALTER TABLE `tbl_infra_server`
  MODIFY `infra_server_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_module`
--
ALTER TABLE `tbl_module`
  MODIFY `module_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_network_product`
--
ALTER TABLE `tbl_network_product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id Network Produk', AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_network_product_junc`
--
ALTER TABLE `tbl_network_product_junc`
  MODIFY `network_product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `tbl_network_provider`
--
ALTER TABLE `tbl_network_provider`
  MODIFY `provider_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id Network Provider', AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_network_provider_junc`
--
ALTER TABLE `tbl_network_provider_junc`
  MODIFY `network_provider_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `tbl_operating_software`
--
ALTER TABLE `tbl_operating_software`
  MODIFY `operating_software_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tbl_portofolio_apps_master`
--
ALTER TABLE `tbl_portofolio_apps_master`
  MODIFY `apps_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `tbl_product_provider_junc`
--
ALTER TABLE `tbl_product_provider_junc`
  MODIFY `produk_provider_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tbl_resilience`
--
ALTER TABLE `tbl_resilience`
  MODIFY `resilience_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_role`
--
ALTER TABLE `tbl_role`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_server`
--
ALTER TABLE `tbl_server`
  MODIFY `server_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_service`
--
ALTER TABLE `tbl_service`
  MODIFY `service_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tbl_user_role`
--
ALTER TABLE `tbl_user_role`
  MODIFY `user_role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_portofolio_infra_master`
--
ALTER TABLE `tbl_portofolio_infra_master`
  ADD CONSTRAINT `fk_infra_resilience` FOREIGN KEY (`resilience_id`) REFERENCES `tbl_resilience` (`resilience_id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
