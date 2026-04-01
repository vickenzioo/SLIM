/*
 Navicat Premium Data Transfer

 Source Server         : localhost_3306
 Source Server Type    : MySQL
 Source Server Version : 100419
 Source Host           : localhost:3306
 Source Schema         : db_slim

 Target Server Type    : MySQL
 Target Server Version : 100419
 File Encoding         : 65001

 Date: 01/04/2026 09:17:44
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for tbl_app_type
-- ----------------------------
DROP TABLE IF EXISTS `tbl_app_type`;
CREATE TABLE `tbl_app_type`  (
  `app_type_id` int(5) NOT NULL AUTO_INCREMENT,
  `app_type_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `status` tinyint(1) NULL DEFAULT 1,
  `created_by` int(11) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `modified_by` int(11) NULL DEFAULT NULL,
  `modified_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`app_type_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_app_type
-- ----------------------------
INSERT INTO `tbl_app_type` VALUES (1, 'Custom-built', 1, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_app_type` VALUES (2, 'Off the shelf', 1, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_app_type` VALUES (3, 'Off the shelf with customization', 1, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_app_type` VALUES (4, 'Test App Types', 0, 1, '2026-04-01 08:29:48', 1, '2026-04-01 08:43:50');

-- ----------------------------
-- Table structure for tbl_apps_approval
-- ----------------------------
DROP TABLE IF EXISTS `tbl_apps_approval`;
CREATE TABLE `tbl_apps_approval`  (
  `approval_id` int(11) NOT NULL AUTO_INCREMENT,
  `apps_id` int(11) NULL DEFAULT NULL,
  `user_role_id` int(11) NULL DEFAULT NULL,
  `status` int(11) NULL DEFAULT 0,
  `current` int(1) NULL DEFAULT 0,
  `remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `submit_date` datetime(0) NULL DEFAULT NULL,
  `created_by` int(11) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `modified_by` int(11) NULL DEFAULT NULL,
  `modified_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`approval_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 319 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_apps_approval
-- ----------------------------
INSERT INTO `tbl_apps_approval` VALUES (284, 72, 2, 1, 0, '- Application Name : \'Group Financial Management Systems\' -> \'Group Financial Management System\'\r\n- Short Name : \'GFMSs\' -> \'GFMS\'\r\n- Module Name : \'Group Financial Management Systems\' -> \'Group Financial Management System\'\r\n- Description : \'Group Financial Management System &#40;GFMS&#41; adalah Platform regional untuk Sistem Keuangan Terintegrasi Grup..\' -> \'Group Financial Management System &#40;GFMS&#41; adalah Platform regional untuk Sistem Keuangan Terintegrasi Grup.\'\r\n- IT Department Head : \'IT Department Heads\' -> \'IT Department Head\'\r\n- IT Group Head : \'IT Group Heads\' -> \'IT Group Head\'', '2026-03-27 11:02:58', 2, '2026-03-27 09:28:02', 2, '2026-03-27 11:02:58');
INSERT INTO `tbl_apps_approval` VALUES (296, 72, 3, 1, 0, '- Resilience : \'L2\' -> \'L1\'', '2026-03-27 11:12:12', 2, '2026-03-27 10:02:25', 3, '2026-03-27 11:12:12');
INSERT INTO `tbl_apps_approval` VALUES (297, 72, 1, 1, 0, '- Standard Category (%) : \'99.00\' -> \'98\'\r\n- Application Type : \'Off the shelf\' -> \'Custom-built\'\r\n- LOB Directorate : \'Directorates\' -> \'Directorate\'\r\n- LOB Sub-Directorate : \'Sub-Directorates\' -> \'Sub-Directorate\'\r\n- LOB Department Head : \'Department Heads\' -> \'Department Head\'\r\n- LOB Group : \'Groups\' -> \'Group\'\r\n- LOB Group Head : \'Group Heads\' -> \'Group Head\'\r\n- IT Sub-Directorate : \'IT Sub-Directorates\' -> \'IT Sub-Directorate\'\r\n- IT Support Group : \'IT Support Groups\' -> \'IT Support Group\'\r\n- IT Support Division : \'IT Support Divisions\' -> \'IT Support Division\'\r\n- IT Division Head : \'IT Division Heads\' -> \'IT Division Head\'\r\n- App Version : \'s\' -> \'(kosong)\'\r\n- Dev Language : \'s\' -> \'(kosong)\'\r\n- App Developer : \'s\' -> \'(kosong)\'\r\n- Supporting Web Server : \'s\' -> \'(kosong)\'\r\n- Supporting App Server : \'s\' -> \'(kosong)\'\r\n- Supporting Others : \'s\' -> \'(kosong)\'\r\n- Network : \'Internal User (Include Branches)\' -> \'Internal User (Exclude Branches)\'\r\n- Operational Day : \'Monday - Saturday\' -> \'Monday - Sunday\'\r\n- Operational Hour : \'00:00:00 - 23:59:00\' -> \'08:00:00 - 23:59:00\'\r\n- Source Code Owned : \'No\' -> \'Yes\'\r\n- Database : \'DB2, Oracle\' -> \'Oracle\'\r\n- Server Type : \'Physical, VM, GCM\' -> \'Physical, VM\'', '2026-03-27 11:13:31', 3, '2026-03-27 10:03:00', 1, '2026-03-27 11:13:31');
INSERT INTO `tbl_apps_approval` VALUES (302, 86, 2, 1, 0, '', '2026-03-27 16:31:03', 2, '2026-03-27 16:22:36', 2, '2026-03-27 16:31:03');
INSERT INTO `tbl_apps_approval` VALUES (303, 86, 3, 1, 0, '', '2026-03-30 13:38:35', 2, '2026-03-27 16:23:53', 3, '2026-03-30 13:38:35');
INSERT INTO `tbl_apps_approval` VALUES (304, 86, 1, 1, 0, '', '2025-02-01 13:44:11', 3, '2026-03-27 16:25:25', 1, '2026-03-30 13:38:49');
INSERT INTO `tbl_apps_approval` VALUES (305, 87, 2, 1, 0, '', '2026-03-30 14:09:16', 2, '2026-03-30 11:58:02', 2, '2026-03-30 14:09:16');
INSERT INTO `tbl_apps_approval` VALUES (306, 87, 3, 1, 0, '', '2026-03-30 14:09:41', 2, '2026-03-30 14:09:16', 3, '2026-03-30 14:09:41');
INSERT INTO `tbl_apps_approval` VALUES (307, 87, 1, 1, 0, '- Application Name : \'as\' -> \'asaaaa\'\r\n- Short Name : \'a\' -> \'aaaaa\'\r\n- Module Name : \'a\' -> \'aaaa\'\r\n- Operational Day : \'Monday - Saturday\' -> \'Wednesday - Saturday\'\r\n- Operational Hour : \'09:00:00 - 09:30:00\' -> \'00:00:00 - 23:59:00\'', '2026-03-31 16:07:05', 3, '2026-03-30 14:09:41', 1, '2026-03-31 16:07:05');
INSERT INTO `tbl_apps_approval` VALUES (308, 88, 2, 1, 0, '', '2026-03-31 16:08:47', 2, '2026-03-30 14:17:10', 2, '2026-03-31 16:08:47');
INSERT INTO `tbl_apps_approval` VALUES (309, 89, 2, 1, 0, '- Category : \'Necessary\' -> \'Very Important\'', '2026-03-31 15:49:53', 2, '2026-03-30 16:38:12', 2, '2026-03-31 15:49:53');
INSERT INTO `tbl_apps_approval` VALUES (311, 89, 3, 1, 0, '- Description : \'a\' -> \'aa\'\r\n- Deployment Model : \'On-Premises\' -> \'IaaS\'\r\n- Database : \'DB2, Oracle\' -> \'DB2, Oracle, Ms SQL Server\'\r\n- Server Type : \'GCM\' -> \'VM, GCM\'', '2026-03-31 15:51:02', 2, '2026-03-31 15:42:38', 3, '2026-03-31 15:51:02');
INSERT INTO `tbl_apps_approval` VALUES (312, 89, 1, 1, 0, '- Standard Category (%) : \'98.50\' -> \'99\'', '2026-03-31 15:51:36', 3, '2026-03-31 15:46:12', 1, '2026-03-31 15:51:36');
INSERT INTO `tbl_apps_approval` VALUES (313, 88, 3, 1, 0, '- Resilience : \'L2\' -> \'L3\'\r\n- Source Code Owned : \'Yes\' -> \'No\'', '2026-03-31 16:09:10', 2, '2026-03-31 16:08:47', 3, '2026-03-31 16:09:10');
INSERT INTO `tbl_apps_approval` VALUES (314, 88, 1, 1, 0, '- Standard Category (%) : \'(kosong)\' -> \'100\'\r\n- Operational Day : \'(kosong)\' -> \'Tuesday - Friday\'\r\n- Operational Hour : \'(kosong)\' -> \'00:00:00 - 23:59:00\'', '2026-03-31 16:10:16', 3, '2026-03-31 16:09:10', 1, '2026-03-31 16:10:16');
INSERT INTO `tbl_apps_approval` VALUES (316, 92, 2, 1, 0, 'Tes buat SLA', '2026-04-01 08:19:56', 2, '2026-04-01 08:19:12', 2, '2026-04-01 08:19:56');
INSERT INTO `tbl_apps_approval` VALUES (317, 92, 3, 1, 0, 'Tes SLA\r\n\r\n- Application Name : \'tes1\' -> \'tes1000\'\r\n- Category : \'Necessary\' -> \'Very Important\'\r\n- Application Type : \'Custom-built\' -> \'Off the shelf\'\r\n- Resilience : \'L2\' -> \'L1\'\r\n- Operating Software : \'Ms Windows Server\' -> \'OS400, Ms Windows Server\'\r\n- Database : \'CloudSQL for MySQL 8.0\' -> \'Oracle, CloudSQL for MySQL 8.0\'', '2026-04-01 08:22:34', 2, '2026-04-01 08:19:56', 3, '2026-04-01 08:22:34');
INSERT INTO `tbl_apps_approval` VALUES (318, 92, 1, 1, 0, 'Tes SLA\r\n\r\n- Standard Category (%) : \'(kosong)\' -> \'99\'\r\n- Deployment Model : \'IaaS\' -> \'On-Premises\'\r\n- Operational Day : \'(kosong)\' -> \'Monday - Sunday\'\r\n- Operational Hour : \'(kosong)\' -> \'08:00:00 - 22:00:00\'', '2026-04-01 08:24:36', 3, '2026-04-01 08:22:34', 1, '2026-04-01 08:24:36');

-- ----------------------------
-- Table structure for tbl_apps_audit_trail
-- ----------------------------
DROP TABLE IF EXISTS `tbl_apps_audit_trail`;
CREATE TABLE `tbl_apps_audit_trail`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `apps_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `action` enum('SUBMIT','RENEWAL','DEACTIVATE','CANCEL') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime(0) NOT NULL DEFAULT current_timestamp(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 139 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_apps_audit_trail
-- ----------------------------
INSERT INTO `tbl_apps_audit_trail` VALUES (82, 72, 2, 'SUBMIT', '-', 0, '2026-03-27 10:02:25');
INSERT INTO `tbl_apps_audit_trail` VALUES (83, 72, 3, 'SUBMIT', 'Application Approved', 0, '2026-03-27 10:03:00');
INSERT INTO `tbl_apps_audit_trail` VALUES (84, 72, 1, 'SUBMIT', '- Standard Category (%) : \'(kosong)\' -> \'98\'', 0, '2026-03-27 10:05:39');
INSERT INTO `tbl_apps_audit_trail` VALUES (85, 72, 2, 'RENEWAL', 'Aplikasi masuk masa perpanjangan(Renewal)', 0, '2026-03-27 10:16:04');
INSERT INTO `tbl_apps_audit_trail` VALUES (86, 72, 2, 'SUBMIT', '-', 0, '2026-03-27 10:16:11');
INSERT INTO `tbl_apps_audit_trail` VALUES (87, 72, 3, 'SUBMIT', '- Application Type : \'Custom-built\' -> \'Off the shelf\'', 0, '2026-03-27 10:18:04');
INSERT INTO `tbl_apps_audit_trail` VALUES (88, 72, 1, 'SUBMIT', 'Application Approved', 0, '2026-03-27 10:18:55');
INSERT INTO `tbl_apps_audit_trail` VALUES (89, 72, 1, 'SUBMIT', '- Standard Category (%) : \'98.00\' -> \'99.00\'', 0, '2026-03-27 10:20:07');
INSERT INTO `tbl_apps_audit_trail` VALUES (90, 72, 2, 'RENEWAL', 'Aplikasi masuk masa perpanjangan(Renewal)', 0, '2026-03-27 10:26:01');
INSERT INTO `tbl_apps_audit_trail` VALUES (91, 72, 2, 'SUBMIT', '-', 0, '2026-03-27 10:38:21');
INSERT INTO `tbl_apps_audit_trail` VALUES (92, 72, 3, 'SUBMIT', '- Application Name : \'Group Financial Management System\' -> \'Group Financial Management Systems\'\r\n- Short Name : \'GFMS\' -> \'GFMSs\'\r\n- Module Name : \'Group Financial Management System\' -> \'Group Financial Management Systems\'\r\n- Description : \'Group Financial Management System &#40;GFMS&#41; adalah Platform regional untuk Sistem Keuangan Terintegrasi Grup.\' -> \'Group Financial Management System &#40;GFMS&#41; adalah Platform regional untuk Sistem Keuangan Terintegrasi Grup..\'\r\n- Category : \'Important\' -> \'Very Important\'\r\n- Application Type : \'Off the shelf\' -> \'Custom-built\'\r\n- Solution Vendor : \'Oracle\' -> \'Oracles\'\r\n- Services Vendor : \'Mitra Integrasi Informatika\' -> \'Mitra Integrasi Informatikas\'\r\n- Live Year : \'2025\' -> \'2026\'\r\n- Deployment Model : \'On-Premises\' -> \'IaaS\'\r\n- Deployment Provider : \'CIMB\' -> \'GCP\'\r\n- Deployment Site : \'Bintaro\' -> \'Asia-southeast2 (Jakarta)\'\r\n- LOB Directorate : \'Directorate\' -> \'Directorates\'\r\n- LOB Sub-Directorate : \'Sub-Directorate\' -> \'Sub-Directorates\'\r\n- LOB Department Head : \'Department Head\' -> \'Department Heads\'\r\n- LOB Group : \'Group\' -> \'Groups\'\r\n- LOB Group Head : \'Group Head\' -> \'Group Heads\'\r\n- IT Sub-Directorate : \'IT Sub-Directorate\' -> \'IT Sub-Directorates\'\r\n- IT Department Head : \'IT Department Head\' -> \'IT Department Heads\'\r\n- IT Support Group : \'IT Support Group\' -> \'IT Support Groups\'\r\n- IT Group Head : \'IT Group Head\' -> \'IT Group Heads\'\r\n- IT Support Division : \'IT Support Division\' -> \'IT Support Divisions\'\r\n- IT Division Head : \'IT Division Head\' -> \'IT Division Heads\'\r\n- App Version : \'(kosong)\' -> \'s\'\r\n- Dev Language : \'(kosong)\' -> \'s\'\r\n- App Developer : \'(kosong)\' -> \'s\'\r\n- Supporting Web Server : \'(kosong)\' -> \'s\'\r\n- Supporting App Server : \'(kosong)\' -> \'s\'\r\n- Supporting Others : \'(kosong)\' -> \'s\'\r\n- Resilience : \'L1\' -> \'L2\'\r\n- Network : \'Internal User (Exclude Branches)\' -> \'Internal User (Include Branches)\'\r\n- Source Code Owned : \'Yes\' -> \'No\'\r\n- Operating Software : \'Ms Windows Server, Solaris\' -> \'Ms Windows Server\'\r\n- Database : \'Oracle\' -> \'DB2, Oracle\'\r\n- Server Type : \'Physical, VM\' -> \'Physical, VM, GCM\'', 0, '2026-03-27 10:39:16');
INSERT INTO `tbl_apps_audit_trail` VALUES (93, 72, 1, 'SUBMIT', '- Category : \'Very Important\' -> \'Important\'\r\n- Application Type : \'Custom-built\' -> \'Off the shelf\'\r\n- Solution Vendor : \'Oracles\' -> \'Oracle\'\r\n- Services Vendor : \'Mitra Integrasi Informatikas\' -> \'Mitra Integrasi Informatika\'\r\n- Live Year : \'2026\' -> \'2025\'\r\n- Deployment Model : \'IaaS\' -> \'On-Premises\'\r\n- Deployment Provider : \'GCP\' -> \'CIMB\'\r\n- Deployment Site : \'Asia-southeast2 (Jakarta)\' -> \'Bintaro\'', 0, '2026-03-27 10:52:59');
INSERT INTO `tbl_apps_audit_trail` VALUES (94, 72, 2, 'RENEWAL', 'Aplikasi masuk masa perpanjangan (Renewal)', 0, '2026-03-27 11:02:08');
INSERT INTO `tbl_apps_audit_trail` VALUES (95, 72, 2, 'SUBMIT', '- Application Name : \'Group Financial Management Systems\' -> \'Group Financial Management System\'\r\n- Short Name : \'GFMSs\' -> \'GFMS\'\r\n- Module Name : \'Group Financial Management Systems\' -> \'Group Financial Management System\'\r\n- Description : \'Group Financial Management System &#40;GFMS&#41; adalah Platform regional untuk Sistem Keuangan Terintegrasi Grup..\' -> \'Group Financial Management System &#40;GFMS&#41; adalah Platform regional untuk Sistem Keuangan Terintegrasi Grup.\'\r\n- IT Department Head : \'IT Department Heads\' -> \'IT Department Head\'\r\n- IT Group Head : \'IT Group Heads\' -> \'IT Group Head\'', 0, '2026-03-27 11:02:58');
INSERT INTO `tbl_apps_audit_trail` VALUES (96, 72, 3, 'SUBMIT', '- Resilience : \'L2\' -> \'L1\'', 0, '2026-03-27 11:12:12');
INSERT INTO `tbl_apps_audit_trail` VALUES (97, 72, 1, 'SUBMIT', '- Standard Category (%) : \'99.00\' -> \'98\'\r\n- Application Type : \'Off the shelf\' -> \'Custom-built\'\r\n- LOB Directorate : \'Directorates\' -> \'Directorate\'\r\n- LOB Sub-Directorate : \'Sub-Directorates\' -> \'Sub-Directorate\'\r\n- LOB Department Head : \'Department Heads\' -> \'Department Head\'\r\n- LOB Group : \'Groups\' -> \'Group\'\r\n- LOB Group Head : \'Group Heads\' -> \'Group Head\'\r\n- IT Sub-Directorate : \'IT Sub-Directorates\' -> \'IT Sub-Directorate\'\r\n- IT Support Group : \'IT Support Groups\' -> \'IT Support Group\'\r\n- IT Support Division : \'IT Support Divisions\' -> \'IT Support Division\'\r\n- IT Division Head : \'IT Division Heads\' -> \'IT Division Head\'\r\n- App Version : \'s\' -> \'(kosong)\'\r\n- Dev Language : \'s\' -> \'(kosong)\'\r\n- App Developer : \'s\' -> \'(kosong)\'\r\n- Supporting Web Server : \'s\' -> \'(kosong)\'\r\n- Supporting App Server : \'s\' -> \'(kosong)\'\r\n- Supporting Others : \'s\' -> \'(kosong)\'\r\n- Network : \'Internal User (Include Branches)\' -> \'Internal User (Exclude Branches)\'\r\n- Operational Day : \'Monday - Saturday\' -> \'Monday - Sunday\'\r\n- Operational Hour : \'00:00:00 - 23:59:00\' -> \'08:00:00 - 23:59:00\'\r\n- Source Code Owned : \'No\' -> \'Yes\'\r\n- Database : \'DB2, Oracle\' -> \'Oracle\'\r\n- Server Type : \'Physical, VM, GCM\' -> \'Physical, VM\'', 0, '2026-03-27 11:13:31');
INSERT INTO `tbl_apps_audit_trail` VALUES (98, 72, 2, 'DEACTIVATE', 'Application Deactivated', 0, '2026-03-27 11:15:13');
INSERT INTO `tbl_apps_audit_trail` VALUES (109, 86, 2, 'SUBMIT', '-', 0, '2026-03-27 16:23:53');
INSERT INTO `tbl_apps_audit_trail` VALUES (110, 86, 3, 'SUBMIT', 'Application Approved', 0, '2026-03-27 16:25:25');
INSERT INTO `tbl_apps_audit_trail` VALUES (111, 86, 1, 'SUBMIT', '- Description : \'sss\' -> \'sssa\'\r\n- Standard Category (%) : \'(kosong)\' -> \'12\'', 0, '2026-03-27 16:27:17');
INSERT INTO `tbl_apps_audit_trail` VALUES (112, 86, 2, 'RENEWAL', 'Aplikasi masuk masa perpanjangan (Renewal)', 0, '2026-03-27 16:28:30');
INSERT INTO `tbl_apps_audit_trail` VALUES (113, 86, 2, 'CANCEL', 'Renewal Cancelled', 0, '2026-03-27 16:28:58');
INSERT INTO `tbl_apps_audit_trail` VALUES (114, 86, 2, 'RENEWAL', 'Aplikasi masuk masa perpanjangan (Renewal)', 0, '2026-03-27 16:30:57');
INSERT INTO `tbl_apps_audit_trail` VALUES (115, 86, 2, 'SUBMIT', '-', 0, '2026-03-27 16:31:03');
INSERT INTO `tbl_apps_audit_trail` VALUES (116, 86, 3, 'SUBMIT', 'Application Approved', 0, '2026-03-30 13:38:35');
INSERT INTO `tbl_apps_audit_trail` VALUES (117, 86, 1, 'SUBMIT', 'Application Approved', 0, '2026-03-30 13:38:49');
INSERT INTO `tbl_apps_audit_trail` VALUES (118, 87, 2, 'SUBMIT', '-', 0, '2026-03-30 14:09:16');
INSERT INTO `tbl_apps_audit_trail` VALUES (119, 87, 3, 'SUBMIT', 'Application Approved', 0, '2026-03-30 14:09:41');
INSERT INTO `tbl_apps_audit_trail` VALUES (120, 87, 1, 'SUBMIT', '- Standard Category (%) : \'(kosong)\' -> \'99\'', 0, '2026-03-30 14:13:48');
INSERT INTO `tbl_apps_audit_trail` VALUES (121, 86, 2, 'RENEWAL', 'Aplikasi masuk masa perpanjangan (Renewal)', 0, '2026-03-31 13:19:00');
INSERT INTO `tbl_apps_audit_trail` VALUES (122, 86, 2, 'CANCEL', 'Renewal Cancelled', 0, '2026-03-31 13:19:10');
INSERT INTO `tbl_apps_audit_trail` VALUES (123, 86, 2, 'RENEWAL', 'Aplikasi masuk masa perpanjangan (Renewal)', 0, '2026-03-31 15:30:59');
INSERT INTO `tbl_apps_audit_trail` VALUES (124, 86, 2, 'CANCEL', 'Renewal Cancelled', 0, '2026-03-31 15:31:09');
INSERT INTO `tbl_apps_audit_trail` VALUES (125, 89, 2, 'SUBMIT', '-', 0, '2026-03-31 15:42:38');
INSERT INTO `tbl_apps_audit_trail` VALUES (126, 89, 3, 'SUBMIT', '- Short Name : \'a\' -> \'aaa\'', 0, '2026-03-31 15:46:12');
INSERT INTO `tbl_apps_audit_trail` VALUES (127, 89, 1, 'SUBMIT', '- Standard Category (%) : \'(kosong)\' -> \'98.50\'\r\n- URL : \'pokemon.com\' -> \'pokemons.com\'', 0, '2026-03-31 15:47:29');
INSERT INTO `tbl_apps_audit_trail` VALUES (128, 89, 2, 'RENEWAL', 'Aplikasi masuk masa perpanjangan (Renewal)', 0, '2026-03-31 15:49:02');
INSERT INTO `tbl_apps_audit_trail` VALUES (129, 89, 2, 'SUBMIT', '- Category : \'Necessary\' -> \'Very Important\'', 0, '2026-03-31 15:49:53');
INSERT INTO `tbl_apps_audit_trail` VALUES (130, 89, 3, 'SUBMIT', '- Description : \'a\' -> \'aa\'\r\n- Deployment Model : \'On-Premises\' -> \'IaaS\'\r\n- Database : \'DB2, Oracle\' -> \'DB2, Oracle, Ms SQL Server\'\r\n- Server Type : \'GCM\' -> \'VM, GCM\'', 0, '2026-03-31 15:51:02');
INSERT INTO `tbl_apps_audit_trail` VALUES (131, 89, 1, 'SUBMIT', '- Standard Category (%) : \'98.50\' -> \'99\'', 0, '2026-03-31 15:51:36');
INSERT INTO `tbl_apps_audit_trail` VALUES (132, 87, 1, 'SUBMIT', '- Application Name : \'as\' -> \'asaaaa\'\r\n- Short Name : \'a\' -> \'aaaaa\'\r\n- Module Name : \'a\' -> \'aaaa\'\r\n- Operational Day : \'Monday - Saturday\' -> \'Wednesday - Saturday\'\r\n- Operational Hour : \'09:00:00 - 09:30:00\' -> \'00:00:00 - 23:59:00\'', 0, '2026-03-31 16:07:05');
INSERT INTO `tbl_apps_audit_trail` VALUES (133, 88, 2, 'SUBMIT', '-', 0, '2026-03-31 16:08:47');
INSERT INTO `tbl_apps_audit_trail` VALUES (134, 88, 3, 'SUBMIT', '- Resilience : \'L2\' -> \'L3\'\r\n- Source Code Owned : \'Yes\' -> \'No\'', 0, '2026-03-31 16:09:10');
INSERT INTO `tbl_apps_audit_trail` VALUES (135, 88, 1, 'SUBMIT', '- Standard Category (%) : \'(kosong)\' -> \'100\'\r\n- Operational Day : \'(kosong)\' -> \'Tuesday - Friday\'\r\n- Operational Hour : \'(kosong)\' -> \'00:00:00 - 23:59:00\'', 0, '2026-03-31 16:10:16');
INSERT INTO `tbl_apps_audit_trail` VALUES (136, 92, 2, 'SUBMIT', 'Tes buat SLA', 0, '2026-04-01 08:19:56');
INSERT INTO `tbl_apps_audit_trail` VALUES (137, 92, 3, 'SUBMIT', 'Tes SLA\r\n\r\n- Application Name : \'tes1\' -> \'tes1000\'\r\n- Category : \'Necessary\' -> \'Very Important\'\r\n- Application Type : \'Custom-built\' -> \'Off the shelf\'\r\n- Resilience : \'L2\' -> \'L1\'\r\n- Operating Software : \'Ms Windows Server\' -> \'OS400, Ms Windows Server\'\r\n- Database : \'CloudSQL for MySQL 8.0\' -> \'Oracle, CloudSQL for MySQL 8.0\'', 0, '2026-04-01 08:22:34');
INSERT INTO `tbl_apps_audit_trail` VALUES (138, 92, 1, 'SUBMIT', 'Tes SLA\r\n\r\n- Standard Category (%) : \'(kosong)\' -> \'99\'\r\n- Deployment Model : \'IaaS\' -> \'On-Premises\'\r\n- Operational Day : \'(kosong)\' -> \'Monday - Sunday\'\r\n- Operational Hour : \'(kosong)\' -> \'08:00:00 - 22:00:00\'', 0, '2026-04-01 08:24:36');

-- ----------------------------
-- Table structure for tbl_apps_category
-- ----------------------------
DROP TABLE IF EXISTS `tbl_apps_category`;
CREATE TABLE `tbl_apps_category`  (
  `category_id` int(10) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `standard_category` decimal(25, 2) NULL DEFAULT NULL,
  `created_by` int(11) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `modified_by` int(11) NULL DEFAULT NULL,
  `modified_at` datetime(0) NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`category_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_apps_category
-- ----------------------------
INSERT INTO `tbl_apps_category` VALUES (1, 'Critical', 99.50, NULL, NULL, 2, '2026-01-20 14:18:45', 1);
INSERT INTO `tbl_apps_category` VALUES (2, 'Very Important', 99.00, NULL, NULL, NULL, NULL, 1);
INSERT INTO `tbl_apps_category` VALUES (3, 'Important', 98.50, NULL, NULL, NULL, NULL, 1);
INSERT INTO `tbl_apps_category` VALUES (4, 'Necessary', NULL, NULL, NULL, NULL, NULL, 1);
INSERT INTO `tbl_apps_category` VALUES (5, 'Others', NULL, NULL, NULL, NULL, NULL, 1);
INSERT INTO `tbl_apps_category` VALUES (16, 'Test Categorys', 1.00, 1, '2026-04-01 08:30:05', 1, '2026-04-01 08:43:04', 0);

-- ----------------------------
-- Table structure for tbl_apps_database
-- ----------------------------
DROP TABLE IF EXISTS `tbl_apps_database`;
CREATE TABLE `tbl_apps_database`  (
  `id_apps_database` int(11) NOT NULL AUTO_INCREMENT,
  `apps_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `database_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_apps_database`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 263 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_apps_database
-- ----------------------------
INSERT INTO `tbl_apps_database` VALUES (227, '72', '2');
INSERT INTO `tbl_apps_database` VALUES (231, '85', '1');
INSERT INTO `tbl_apps_database` VALUES (236, '86', '2');
INSERT INTO `tbl_apps_database` VALUES (251, '89', '1');
INSERT INTO `tbl_apps_database` VALUES (252, '89', '2');
INSERT INTO `tbl_apps_database` VALUES (253, '89', '3');
INSERT INTO `tbl_apps_database` VALUES (254, '87', '2');
INSERT INTO `tbl_apps_database` VALUES (257, '88', '3');
INSERT INTO `tbl_apps_database` VALUES (261, '92', '2');
INSERT INTO `tbl_apps_database` VALUES (262, '92', '4');

-- ----------------------------
-- Table structure for tbl_apps_deployment
-- ----------------------------
DROP TABLE IF EXISTS `tbl_apps_deployment`;
CREATE TABLE `tbl_apps_deployment`  (
  `deployment_id` int(11) NOT NULL AUTO_INCREMENT,
  `deployment_model` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(11) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `modified_by` int(11) NULL DEFAULT NULL,
  `modified_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`deployment_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_apps_deployment
-- ----------------------------
INSERT INTO `tbl_apps_deployment` VALUES (1, 'On-Premises', 1, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_apps_deployment` VALUES (2, 'IaaS', 1, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_apps_deployment` VALUES (13, 'Tes Deploys', 0, 1, '2026-04-01 08:41:24', 1, '2026-04-01 08:41:32');

-- ----------------------------
-- Table structure for tbl_apps_deployment_model
-- ----------------------------
DROP TABLE IF EXISTS `tbl_apps_deployment_model`;
CREATE TABLE `tbl_apps_deployment_model`  (
  `deployment_provider_id` int(5) NOT NULL AUTO_INCREMENT,
  `deployment_provider_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `status` tinyint(1) NULL DEFAULT 1,
  `created_by` int(11) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `modified_by` int(11) NULL DEFAULT NULL,
  `modified_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`deployment_provider_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_apps_deployment_model
-- ----------------------------
INSERT INTO `tbl_apps_deployment_model` VALUES (1, 'CIMB', 1, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_apps_deployment_model` VALUES (2, 'GCP', 1, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_apps_deployment_model` VALUES (3, 'Tes Deploy Prvs', 0, 1, '2026-04-01 08:45:04', 1, '2026-04-01 08:45:12');

-- ----------------------------
-- Table structure for tbl_apps_deployment_site
-- ----------------------------
DROP TABLE IF EXISTS `tbl_apps_deployment_site`;
CREATE TABLE `tbl_apps_deployment_site`  (
  `deployment_site_id` int(5) NOT NULL AUTO_INCREMENT,
  `deployment_site_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `status` tinyint(1) NULL DEFAULT 1,
  `created_by` int(11) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `modified_by` int(11) NULL DEFAULT NULL,
  `modified_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`deployment_site_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_apps_deployment_site
-- ----------------------------
INSERT INTO `tbl_apps_deployment_site` VALUES (1, 'Bintaro', 1, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_apps_deployment_site` VALUES (2, 'Asia-southeast2 (Jakarta)', 1, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_apps_deployment_site` VALUES (3, 'NTT', 1, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_apps_deployment_site` VALUES (4, 'Tes Deploy Site', 0, 1, '2026-04-01 08:45:50', NULL, NULL);

-- ----------------------------
-- Table structure for tbl_apps_network
-- ----------------------------
DROP TABLE IF EXISTS `tbl_apps_network`;
CREATE TABLE `tbl_apps_network`  (
  `network_id` int(11) NOT NULL AUTO_INCREMENT,
  `network_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(11) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `modified_by` int(11) NULL DEFAULT NULL,
  `modified_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`network_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_apps_network
-- ----------------------------
INSERT INTO `tbl_apps_network` VALUES (1, 'Customer Facing', 1, NULL, NULL, 2, '2026-01-20 14:12:36');
INSERT INTO `tbl_apps_network` VALUES (2, 'Internal User (Include Branches)', 1, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_apps_network` VALUES (3, 'Internal User (Exclude Branches)', 1, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_apps_network` VALUES (16, 'Tes Networks', 0, 1, '2026-04-01 08:49:33', 1, '2026-04-01 08:49:40');

-- ----------------------------
-- Table structure for tbl_apps_operating_software
-- ----------------------------
DROP TABLE IF EXISTS `tbl_apps_operating_software`;
CREATE TABLE `tbl_apps_operating_software`  (
  `id_apps_operating_software` int(11) NOT NULL AUTO_INCREMENT,
  `apps_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `operating_software_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_apps_operating_software`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 287 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_apps_operating_software
-- ----------------------------
INSERT INTO `tbl_apps_operating_software` VALUES (248, '72', '2');
INSERT INTO `tbl_apps_operating_software` VALUES (252, '85', '2');
INSERT INTO `tbl_apps_operating_software` VALUES (261, '86', '1');
INSERT INTO `tbl_apps_operating_software` VALUES (262, '86', '2');
INSERT INTO `tbl_apps_operating_software` VALUES (276, '89', '2');
INSERT INTO `tbl_apps_operating_software` VALUES (277, '89', '4');
INSERT INTO `tbl_apps_operating_software` VALUES (278, '87', '1');
INSERT INTO `tbl_apps_operating_software` VALUES (281, '88', '2');
INSERT INTO `tbl_apps_operating_software` VALUES (285, '92', '1');
INSERT INTO `tbl_apps_operating_software` VALUES (286, '92', '2');

-- ----------------------------
-- Table structure for tbl_apps_operational_day
-- ----------------------------
DROP TABLE IF EXISTS `tbl_apps_operational_day`;
CREATE TABLE `tbl_apps_operational_day`  (
  `operational_day_id` int(10) NOT NULL AUTO_INCREMENT,
  `start_day` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `end_day` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `total_day` int(11) NULL DEFAULT NULL,
  `created_by` int(11) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `modified_by` int(11) NULL DEFAULT NULL,
  `modified_at` datetime(0) NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`operational_day_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 26 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_apps_operational_day
-- ----------------------------
INSERT INTO `tbl_apps_operational_day` VALUES (1, 'Monday', 'Sunday', 7, NULL, NULL, 2, '2026-01-20 14:03:18', 1);
INSERT INTO `tbl_apps_operational_day` VALUES (2, 'Monday', 'Saturday', 6, NULL, NULL, NULL, NULL, 1);
INSERT INTO `tbl_apps_operational_day` VALUES (3, 'Tuesday', 'Saturday', 5, NULL, NULL, 2, '2026-01-29 13:38:09', 1);
INSERT INTO `tbl_apps_operational_day` VALUES (19, 'Monday', 'Tuesday', 2, 2, '2026-02-02 09:24:58', NULL, NULL, 1);
INSERT INTO `tbl_apps_operational_day` VALUES (22, 'Wednesday', 'Saturday', 4, 2, '2026-02-02 10:40:21', NULL, NULL, 1);
INSERT INTO `tbl_apps_operational_day` VALUES (24, 'Tuesday', 'Friday', 4, 1, '2026-03-30 16:00:09', NULL, NULL, 1);
INSERT INTO `tbl_apps_operational_day` VALUES (25, 'Monday', 'Wednesday', 3, 1, '2026-04-01 08:53:36', 1, '2026-04-01 08:54:08', 0);

-- ----------------------------
-- Table structure for tbl_apps_operational_hour
-- ----------------------------
DROP TABLE IF EXISTS `tbl_apps_operational_hour`;
CREATE TABLE `tbl_apps_operational_hour`  (
  `operational_hour_id` int(10) NOT NULL AUTO_INCREMENT,
  `start_time` time(0) NULL DEFAULT NULL,
  `end_time` time(0) NULL DEFAULT NULL,
  `total_hour` decimal(10, 1) NULL DEFAULT NULL,
  `created_by` int(11) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `modified_by` int(11) NULL DEFAULT NULL,
  `modified_at` datetime(0) NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`operational_hour_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 33 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_apps_operational_hour
-- ----------------------------
INSERT INTO `tbl_apps_operational_hour` VALUES (1, '00:00:00', '23:59:00', 24.0, NULL, NULL, 2, '2026-01-20 07:59:46', 1);
INSERT INTO `tbl_apps_operational_hour` VALUES (2, '08:00:00', '18:00:00', 10.0, NULL, NULL, NULL, NULL, 1);
INSERT INTO `tbl_apps_operational_hour` VALUES (3, '08:00:00', '22:00:00', 14.0, NULL, NULL, NULL, NULL, 1);
INSERT INTO `tbl_apps_operational_hour` VALUES (18, '09:00:00', '09:30:00', 0.5, 2, '2026-01-20 00:00:00', NULL, NULL, 1);
INSERT INTO `tbl_apps_operational_hour` VALUES (31, '08:00:00', '23:59:00', 16.0, 1, '2026-03-27 10:05:11', NULL, NULL, 1);
INSERT INTO `tbl_apps_operational_hour` VALUES (32, '08:52:00', '09:52:00', 1.0, 1, '2026-04-01 08:51:59', 1, '2026-04-01 08:52:27', 0);

-- ----------------------------
-- Table structure for tbl_apps_server
-- ----------------------------
DROP TABLE IF EXISTS `tbl_apps_server`;
CREATE TABLE `tbl_apps_server`  (
  `apps_server_id` int(11) NOT NULL AUTO_INCREMENT,
  `apps_id` int(11) NULL DEFAULT NULL,
  `server_id` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`apps_server_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 235 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_apps_server
-- ----------------------------
INSERT INTO `tbl_apps_server` VALUES (191, 72, 1);
INSERT INTO `tbl_apps_server` VALUES (192, 72, 2);
INSERT INTO `tbl_apps_server` VALUES (196, 85, 1);
INSERT INTO `tbl_apps_server` VALUES (205, 86, 1);
INSERT INTO `tbl_apps_server` VALUES (206, 86, 2);
INSERT INTO `tbl_apps_server` VALUES (219, 89, 2);
INSERT INTO `tbl_apps_server` VALUES (220, 89, 3);
INSERT INTO `tbl_apps_server` VALUES (221, 87, 1);
INSERT INTO `tbl_apps_server` VALUES (222, 87, 3);
INSERT INTO `tbl_apps_server` VALUES (229, 88, 1);
INSERT INTO `tbl_apps_server` VALUES (230, 88, 4);
INSERT INTO `tbl_apps_server` VALUES (231, 88, 5);
INSERT INTO `tbl_apps_server` VALUES (234, 92, 1);

-- ----------------------------
-- Table structure for tbl_apps_sla_history
-- ----------------------------
DROP TABLE IF EXISTS `tbl_apps_sla_history`;
CREATE TABLE `tbl_apps_sla_history`  (
  `sla_id` int(11) NOT NULL AUTO_INCREMENT,
  `apps_id` int(11) NULL DEFAULT NULL,
  `version` int(11) NULL DEFAULT NULL,
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_by` int(11) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `remarks` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`sla_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 43 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_apps_sla_history
-- ----------------------------
INSERT INTO `tbl_apps_sla_history` VALUES (29, 72, 1, 'SLA_Group_Financial_Management_System_20260327_100542.pdf', 1, '2026-03-27 10:05:42', 'Auto-Generated SLA on Final Approval');
INSERT INTO `tbl_apps_sla_history` VALUES (30, 72, 2, 'SLA_Group_Financial_Management_System_20260327_101855.pdf', 1, '2026-03-27 10:18:55', 'Auto-Generated SLA on Final Approval');
INSERT INTO `tbl_apps_sla_history` VALUES (31, 72, 3, 'SLA_Group_Financial_Management_System_20260327_102010.pdf', 1, '2026-03-27 10:20:10', 'Auto-Generated SLA on Final Approval');
INSERT INTO `tbl_apps_sla_history` VALUES (32, 72, 4, 'SLA_Group_Financial_Management_Systems_20260327_105259.pdf', 1, '2026-03-27 10:52:59', 'Auto-Generated SLA on Final Approval');
INSERT INTO `tbl_apps_sla_history` VALUES (33, 72, 5, 'SLA_Group_Financial_Management_System_20260327_111331.pdf', 1, '2026-03-27 11:13:31', 'Auto-Generated SLA on Final Approval');
INSERT INTO `tbl_apps_sla_history` VALUES (35, 86, 1, 'SLA_tes_20260327_162717.pdf', 1, '2026-03-27 16:27:17', 'Auto-Generated SLA on Final Approval');
INSERT INTO `tbl_apps_sla_history` VALUES (36, 86, 2, 'SLA_tes_20260330_133851.pdf', 1, '2026-03-30 13:38:51', 'Auto-Generated SLA on Final Approval');
INSERT INTO `tbl_apps_sla_history` VALUES (37, 87, 1, 'SLA_as_20260330_141348.pdf', 1, '2026-03-30 14:13:48', 'Auto-Generated SLA on Final Approval');
INSERT INTO `tbl_apps_sla_history` VALUES (38, 89, 1, 'SLA_as_20260331_154732.pdf', 1, '2026-03-31 15:47:32', 'Auto-Generated SLA on Final Approval');
INSERT INTO `tbl_apps_sla_history` VALUES (39, 89, 2, 'SLA_as_20260331_155136.pdf', 1, '2026-03-31 15:51:36', 'Auto-Generated SLA on Final Approval');
INSERT INTO `tbl_apps_sla_history` VALUES (40, 87, 2, 'SLA_asaaaa_20260331_160705.pdf', 1, '2026-03-31 16:07:05', 'Auto-Generated SLA on Final Approval');
INSERT INTO `tbl_apps_sla_history` VALUES (41, 88, 1, 'SLA_x_20260331_161017.pdf', 1, '2026-03-31 16:10:17', 'Auto-Generated SLA on Final Approval');
INSERT INTO `tbl_apps_sla_history` VALUES (42, 92, 1, 'SLA_tes1000_20260401_082439.pdf', 1, '2026-04-01 08:24:39', 'Auto-Generated SLA on Final Approval');

-- ----------------------------
-- Table structure for tbl_audit_trail
-- ----------------------------
DROP TABLE IF EXISTS `tbl_audit_trail`;
CREATE TABLE `tbl_audit_trail`  (
  `audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` datetime(0) NULL DEFAULT current_timestamp(0),
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `action` enum('ADD','EDIT','DEACTIVATE','ACTIVATE','EXPORT','DELETE') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `table_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `foreign_id` int(11) NOT NULL,
  `field_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `old_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `new_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`audit_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 374 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_audit_trail
-- ----------------------------
INSERT INTO `tbl_audit_trail` VALUES (1, '2026-01-20 13:53:37', 'Vicken', 'ADD', 'tbl_database', 44, '-', '-', 'DB23', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (2, '2026-01-20 13:53:54', 'Vicken', 'EDIT', 'tbl_database', 44, 'database_name', 'DB23', 'DB234', 'test');
INSERT INTO `tbl_audit_trail` VALUES (3, '2026-01-21 02:46:14', 'Tes', 'EDIT', 'tbl_deployment', 13, 'deployment_model', 'On-Premises', 'On-Premises Test', 'No reason provided');
INSERT INTO `tbl_audit_trail` VALUES (4, '2026-01-21 02:59:11', 'Tes', 'EDIT', 'tbl_deployment', 13, 'deployment_model', 'On-Premises Test', 'On-Premises Test1', 'Test');
INSERT INTO `tbl_audit_trail` VALUES (5, '2026-01-21 02:59:11', 'Tes', 'EDIT', 'tbl_deployment', 13, 'deployment_provider', 'CIMB TEST 1', 'CIMB TEST 12', 'Test');
INSERT INTO `tbl_audit_trail` VALUES (6, '2026-01-21 02:59:11', 'Tes', 'EDIT', 'tbl_deployment', 13, 'main_deployment_site', 'Bintaro Test 2 ', 'Bintaro Test 2 3', 'Test');
INSERT INTO `tbl_audit_trail` VALUES (7, '2026-01-22 09:39:00', 'Tes', 'EDIT', 'tbl_operational_day', 3, 'Start Day', 'Monday', 'Tuesday', 'test');
INSERT INTO `tbl_audit_trail` VALUES (8, '2026-01-22 09:39:00', 'Tes', 'EDIT', 'tbl_operational_day', 3, 'End Day', 'Friday', 'Saturday', 'test');
INSERT INTO `tbl_audit_trail` VALUES (9, '2026-01-22 03:44:47', 'Tes', 'ADD', 'tbl_database', 46, '-', '-', 'DB22', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (10, '2026-01-22 03:44:53', 'Tes', 'EDIT', 'tbl_database', 46, 'database_name', 'DB22', 'DB222', 'test');
INSERT INTO `tbl_audit_trail` VALUES (11, '2026-01-22 03:54:31', 'Tes', 'ADD', 'tbl_network', 14, '-', '-', 'Tes', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (12, '2026-01-22 05:11:08', 'Tes', 'ADD', 'tbl_operational_hour', 20, 'Start Time', '-', '12:11', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (13, '2026-01-22 05:11:08', 'Tes', 'ADD', 'tbl_operational_hour', 20, 'End Time', '-', '15:11', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (14, '2026-01-22 05:11:34', 'Tes', 'EDIT', 'tbl_operational_hour', 20, 'End Time', '15:11:00', '18:11', 'tes');
INSERT INTO `tbl_audit_trail` VALUES (15, '2026-01-22 05:11:56', 'Tes', '', 'tbl_operational_hour', 20, 'Operational Hour', '12:11:00 - 18:11:00', '-', 'Data deleted by user');
INSERT INTO `tbl_audit_trail` VALUES (16, '2026-01-22 11:13:32', 'Tes', 'ADD', 'tbl_operational_hour', 21, 'Start Time', '-', '15:13', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (17, '2026-01-22 11:13:32', 'Tes', 'ADD', 'tbl_operational_hour', 21, 'End Time', '-', '17:13', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (18, '2026-01-22 11:13:41', 'Tes', 'ADD', 'tbl_operational_day', 16, 'Start Day', '-', 'Wednesday', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (19, '2026-01-22 11:13:41', 'Tes', 'ADD', 'tbl_operational_day', 16, 'End Day', '-', 'Saturday', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (20, '2026-01-22 11:13:46', 'Tes', '', 'tbl_operational_day', 16, '-', 'Wednesday - Saturday', '-', 'tes');
INSERT INTO `tbl_audit_trail` VALUES (21, '2026-01-22 05:16:08', 'Tes', '', 'tbl_operational_hour', 21, 'Operational Hour', '15:13:00 - 17:13:00', '-', 'Data deleted by user');
INSERT INTO `tbl_audit_trail` VALUES (22, '2026-01-22 05:16:23', 'Tes', 'ADD', 'tbl_operational_hour', 22, 'Start Time', '-', '14:16', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (23, '2026-01-22 05:16:23', 'Tes', 'ADD', 'tbl_operational_hour', 22, 'End Time', '-', '17:16', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (24, '2026-01-22 05:16:28', 'Tes', '', 'tbl_operational_hour', 22, 'Operational Hour', '14:16:00 - 17:16:00', '-', 'Data deleted by user');
INSERT INTO `tbl_audit_trail` VALUES (25, '2026-01-22 11:18:20', 'Tes', 'ADD', 'tbl_operational_day', 17, 'Start Day', '-', 'Thursday', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (26, '2026-01-22 11:18:20', 'Tes', 'ADD', 'tbl_operational_day', 17, 'End Day', '-', 'Saturday', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (27, '2026-01-22 11:18:26', 'Tes', '', 'tbl_operational_day', 17, 'operational_day', 'Thursday - Saturday', '-', 'tes');
INSERT INTO `tbl_audit_trail` VALUES (31, '2026-01-26 03:30:31', 'Tes', 'ADD', 'tbl_network_product', 10, 'All Fields', '-', 'tes | SLA: 12 | Net: Internal User (Include Branches)', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (33, '2026-01-26 03:34:41', 'Tes', 'ADD', 'tbl_network_product', 11, 'Product Name', '-', 'tres', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (34, '2026-01-26 03:34:41', 'Tes', 'ADD', 'tbl_network_product', 11, 'Product SLA', '-', '12', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (35, '2026-01-26 03:34:41', 'Tes', 'ADD', 'tbl_network_product', 11, 'Network Name', '-', 'Customer Facing', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (36, '2026-01-26 03:35:08', 'Tes', 'EDIT', 'tbl_network_product', 11, 'Network Name', 'Customer Facing', 'Internal User (Exclude Branches)', 'tes');
INSERT INTO `tbl_audit_trail` VALUES (37, '2026-01-26 03:35:28', 'Tes', '', 'tbl_network_product', 11, 'product_name', 'tres', '-', 'tes');
INSERT INTO `tbl_audit_trail` VALUES (38, '2026-01-26 03:40:57', 'Tes', 'ADD', 'tbl_network_product', 12, 'Product Name', '-', 'tres', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (39, '2026-01-26 03:40:57', 'Tes', 'ADD', 'tbl_network_product', 12, 'Product SLA', '-', '11', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (40, '2026-01-26 03:40:57', 'Tes', 'ADD', 'tbl_network_product', 12, 'Network Name', '-', 'Customer Facing', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (41, '2026-01-26 03:41:05', 'Tes', '', 'tbl_network_product', 12, 'Product Detail', 'tres 11.00 Customer Facing', '-', 'tesss');
INSERT INTO `tbl_audit_trail` VALUES (42, '2026-01-26 04:07:45', 'Tes', 'ADD', 'tbl_network_provider', 8, 'Provider Name', '-', 'pokemon', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (43, '2026-01-26 04:07:45', 'Tes', 'ADD', 'tbl_network_provider', 8, 'Network Name', '-', 'Customer Facing', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (44, '2026-01-26 04:08:13', 'Tes', 'EDIT', 'tbl_network_provider', 8, 'Network Name', 'Customer Facing', 'Internal User (Exclude Branches)', 'tes\r\n');
INSERT INTO `tbl_audit_trail` VALUES (45, '2026-01-26 04:08:37', 'Tes', '', 'tbl_network_provider', 8, 'Provider Detail', 'pokemon Internal User (Exclude Branches)', '-', 'tes');
INSERT INTO `tbl_audit_trail` VALUES (61, '2026-01-26 13:45:36', 'Tes', 'EXPORT', 'tbl_infra_server', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (63, '2026-01-26 14:18:01', 'Tes', 'EXPORT', 'tbl_history', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (64, '2026-01-27 10:37:24', 'Tes', 'EXPORT', 'tbl_apps_operational_hour', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (65, '2026-01-27 13:42:45', 'Tes', 'EXPORT', 'tbl_portofolio_apps_master', 0, NULL, NULL, NULL, 'Export Data Portofolio');
INSERT INTO `tbl_audit_trail` VALUES (66, '2026-01-27 13:42:46', 'Tes', 'EXPORT', 'tbl_portofolio_apps_master', 0, NULL, NULL, NULL, 'Export Data Portofolio');
INSERT INTO `tbl_audit_trail` VALUES (67, '2026-01-27 15:15:44', 'Tes', 'EXPORT', 'tbl_portofolio_apps_master', 0, NULL, NULL, NULL, 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (68, '2026-01-27 15:19:18', 'Tes', 'EXPORT', 'tbl_portofolio_apps_master', 0, NULL, NULL, NULL, 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (69, '2026-01-27 15:20:03', 'Tes', 'EXPORT', 'tbl_portofolio_apps_master', 0, NULL, NULL, NULL, 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (70, '2026-01-27 15:21:09', 'Tes', 'EXPORT', 'tbl_portofolio_apps_master', 0, NULL, NULL, NULL, 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (71, '2026-01-28 14:28:56', 'Tes', 'EXPORT', 'tbl_portofolio_apps_master', 0, NULL, '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (72, '2026-01-29 10:03:28', 'Tes', 'ADD', 'tbl_apps_operational_hour', 23, 'Start Time', '-', '08:03', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (73, '2026-01-29 10:03:28', 'Tes', 'ADD', 'tbl_apps_operational_hour', 23, 'End Time', '-', '10:05', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (74, '2026-01-29 10:04:04', 'Tes', '', 'tbl_apps_operational_hour', 23, 'Operational Hour', '08:03:00 - 10:05:00', '-', 'tes');
INSERT INTO `tbl_audit_trail` VALUES (75, '2026-01-29 10:04:19', 'Tes', 'ADD', 'tbl_apps_operational_hour', 24, 'Start Time', '-', '10:00', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (76, '2026-01-29 10:04:19', 'Tes', 'ADD', 'tbl_apps_operational_hour', 24, 'End Time', '-', '11:04', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (77, '2026-01-29 10:04:29', 'Tes', '', 'tbl_apps_operational_hour', 24, 'Operational Hour', '10:00:00 - 11:04:00', '-', 'yes');
INSERT INTO `tbl_audit_trail` VALUES (78, '2026-01-29 10:04:47', 'Tes', 'ADD', 'tbl_apps_operational_day', 18, 'Start Day', '-', 'Tuesday', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (79, '2026-01-29 10:04:47', 'Tes', 'ADD', 'tbl_apps_operational_day', 18, 'End Day', '-', 'Thursday', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (80, '2026-01-29 10:04:53', 'Tes', '', 'tbl_apps_operational_day', 18, 'Operational Day', 'Tuesday - Thursday', '-', 'tes');
INSERT INTO `tbl_audit_trail` VALUES (81, '2026-01-29 10:08:21', 'Tes', 'ADD', 'tbl_apps_operational_hour', 25, 'Start Time', '-', '10:08', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (82, '2026-01-29 10:08:21', 'Tes', 'ADD', 'tbl_apps_operational_hour', 25, 'End Time', '-', '11:08', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (83, '2026-01-29 10:08:21', 'Tes', 'ADD', 'tbl_apps_operational_hour', 25, 'Total Hour', '-', '1.00 Hours', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (84, '2026-01-29 10:08:27', 'Tes', '', 'tbl_apps_operational_hour', 25, 'Operational Hour', '10:08:00 - 11:08:00 (1.0 Hrs)', '-', 'tes');
INSERT INTO `tbl_audit_trail` VALUES (85, '2026-01-29 10:10:17', 'Tes', 'ADD', 'tbl_user_role', 6, 'New Assignment', '-', 'User: Vicken | Role: Inputer', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (86, '2026-01-29 10:10:27', 'Tes', '', 'tbl_user_role', 6, 'Assignment Detail', 'User: Vicken | Role: Inputer', '-', 'tes');
INSERT INTO `tbl_audit_trail` VALUES (87, '2026-01-29 10:10:46', 'Tes', 'EXPORT', 'tbl_user_role', 0, '-', '-', '-', 'Export All Data');
INSERT INTO `tbl_audit_trail` VALUES (88, '2026-01-29 10:22:32', 'Tes', '', 'tbl_user_role', 7, 'Assignment Detail', 'User: Vicken | Role: Inputer', '-', 'tes');
INSERT INTO `tbl_audit_trail` VALUES (89, '2026-01-29 10:26:47', 'Tes', '', 'tbl_user_role', 8, 'Assignment Detail', 'User: Vicken | Role: Inputer', '-', 'tes');
INSERT INTO `tbl_audit_trail` VALUES (90, '2026-01-29 10:34:35', 'Tes', 'ADD', 'tbl_user_role', 9, 'Assigned User', '-', 'Vicken', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (91, '2026-01-29 10:34:35', 'Tes', 'ADD', 'tbl_user_role', 9, 'Role', '-', 'Inputer', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (92, '2026-01-29 10:36:24', 'Tes', 'ADD', 'tbl_user_role', 10, 'Assigned User', '-', 'Tes', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (93, '2026-01-29 10:36:24', 'Tes', 'ADD', 'tbl_user_role', 10, 'Role', '-', 'Admin', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (94, '2026-01-29 10:36:32', 'Tes', 'EDIT', 'tbl_user_role', 10, 'Role', 'Admin', 'Approver', 'tes');
INSERT INTO `tbl_audit_trail` VALUES (95, '2026-01-29 10:37:51', 'Tes', 'EXPORT', 'tbl_user_role', 0, '-', '-', '-', 'Export All Data');
INSERT INTO `tbl_audit_trail` VALUES (96, '2026-01-29 10:50:29', 'Tes', 'EDIT', 'tbl_holiday', 13, 'Holiday_Description', '-', '-', 'tes');
INSERT INTO `tbl_audit_trail` VALUES (97, '2026-01-29 11:15:47', 'Tes', 'ADD', 'tbl_holiday', 16, 'Holiday Name', '-', 'Tessss', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (98, '2026-01-29 11:15:47', 'Tes', 'ADD', 'tbl_holiday', 16, 'Holiday Date', '-', '2026-01-30', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (99, '2026-01-29 11:15:47', 'Tes', 'ADD', 'tbl_holiday', 16, 'Holiday Description', '-', '-', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (100, '2026-01-29 11:16:12', 'Tes', 'EDIT', 'tbl_holiday', 16, 'Holiday_Description', '-', '-aa', 'tes');
INSERT INTO `tbl_audit_trail` VALUES (101, '2026-01-29 11:17:58', 'Tes', '', 'tbl_holiday', 16, 'Holiday_Date', '2026-01-30', '-', 'tes');
INSERT INTO `tbl_audit_trail` VALUES (102, '2026-01-29 11:21:00', 'Tes', 'ADD', 'tbl_holiday', 17, 'Holiday Name', '-', 'Libur Testss', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (103, '2026-01-29 11:21:00', 'Tes', 'ADD', 'tbl_holiday', 17, 'Holiday Date', '-', '2026-01-30', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (104, '2026-01-29 11:21:00', 'Tes', 'ADD', 'tbl_holiday', 17, 'Holiday Description', '-', 'tes', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (105, '2026-01-29 11:21:12', 'Tes', 'EDIT', 'tbl_holiday', 17, 'Holiday Description', 'tes', 'tess', 'rews');
INSERT INTO `tbl_audit_trail` VALUES (106, '2026-01-29 11:21:17', 'Tes', '', 'tbl_holiday', 17, 'Holiday Date', '2026-01-30 (Libur Testss)', '-', 'res');
INSERT INTO `tbl_audit_trail` VALUES (107, '2026-01-29 11:22:41', 'Tes', 'ADD', 'tbl_holiday', 18, 'Holiday Name', '-', 'Libur Test', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (108, '2026-01-29 11:22:41', 'Tes', 'ADD', 'tbl_holiday', 18, 'Holiday Date', '-', '2026-01-30', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (109, '2026-01-29 11:22:41', 'Tes', 'ADD', 'tbl_holiday', 18, 'Holiday Description', '-', '-', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (110, '2026-01-29 11:22:47', 'Tes', '', 'tbl_holiday', 18, 'Holiday Date', '2026-01-30 $old->Holiday_Name', '-', 'rs');
INSERT INTO `tbl_audit_trail` VALUES (111, '2026-01-29 11:23:42', 'Tes', 'ADD', 'tbl_holiday', 19, 'Holiday Name', '-', 'Libur Test', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (112, '2026-01-29 11:23:42', 'Tes', 'ADD', 'tbl_holiday', 19, 'Holiday Date', '-', '2026-01-30', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (113, '2026-01-29 11:23:42', 'Tes', 'ADD', 'tbl_holiday', 19, 'Holiday Description', '-', 'as', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (114, '2026-01-29 11:23:46', 'Tes', '', 'tbl_holiday', 19, 'Holiday Date', '2026-01-30$old->Holiday_Name', '-', 'as');
INSERT INTO `tbl_audit_trail` VALUES (115, '2026-01-29 11:24:44', 'Tes', 'ADD', 'tbl_holiday', 20, 'Holiday Name', '-', 'aaa', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (116, '2026-01-29 11:24:44', 'Tes', 'ADD', 'tbl_holiday', 20, 'Holiday Date', '-', '2026-01-30', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (117, '2026-01-29 11:24:44', 'Tes', 'ADD', 'tbl_holiday', 20, 'Holiday Description', '-', '-', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (118, '2026-01-29 11:24:49', 'Tes', '', 'tbl_holiday', 20, 'Holiday Date', '2026-01-30 . $old->Holiday_Name', '-', 'ad');
INSERT INTO `tbl_audit_trail` VALUES (119, '2026-01-29 11:26:24', 'Tes', 'ADD', 'tbl_holiday', 21, 'Holiday Name', '-', 'as', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (120, '2026-01-29 11:26:24', 'Tes', 'ADD', 'tbl_holiday', 21, 'Holiday Date', '-', '2026-01-30', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (121, '2026-01-29 11:26:24', 'Tes', 'ADD', 'tbl_holiday', 21, 'Holiday Description', '-', '-', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (122, '2026-01-29 11:26:30', 'Tes', '', 'tbl_holiday', 21, 'Holiday Date', '2026-01-30 - as', '-', 'a');
INSERT INTO `tbl_audit_trail` VALUES (123, '2026-01-29 11:28:00', 'Tes', 'ADD', 'tbl_apps_operational_hour', 26, 'Start Time', '-', '12:27', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (124, '2026-01-29 11:28:00', 'Tes', 'ADD', 'tbl_apps_operational_hour', 26, 'End Time', '-', '13:27', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (125, '2026-01-29 11:28:00', 'Tes', 'ADD', 'tbl_apps_operational_hour', 26, 'Total Hour', '-', '1.00 Hours', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (126, '2026-01-29 11:28:04', 'Tes', '', 'tbl_apps_operational_hour', 26, 'Operational Hour', '12:27:00 - 13:27:00 (1.0 Hrs)', '-', 'tes');
INSERT INTO `tbl_audit_trail` VALUES (127, '2026-01-29 13:37:58', 'Tes', 'EDIT', 'tbl_apps_operational_day', 3, 'End Day', 'Saturday', 'Sunday', 'tes');
INSERT INTO `tbl_audit_trail` VALUES (128, '2026-01-29 13:38:09', 'Tes', 'EDIT', 'tbl_apps_operational_day', 3, 'End Day', 'Sunday', 'Saturday', 'tes');
INSERT INTO `tbl_audit_trail` VALUES (129, '2026-01-30 11:07:54', 'Tes', 'EXPORT', 'tbl_portofolio_apps_master', 0, NULL, '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (130, '2026-01-30 11:08:16', 'Tes', 'EXPORT', 'tbl_portofolio_apps_master', 0, NULL, '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (131, '2026-01-30 11:09:02', 'Tes', 'EXPORT', 'tbl_portofolio_apps_master', 0, NULL, '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (132, '2026-01-30 11:11:50', 'Tes', 'EXPORT', 'tbl_portofolio_apps_master', 0, NULL, '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (133, '2026-01-30 11:12:11', 'Tes', 'EXPORT', 'tbl_portofolio_apps_master', 0, NULL, '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (134, '2026-01-30 11:15:04', 'Tes', 'EXPORT', 'tbl_portofolio_apps_master', 0, NULL, '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (135, '2026-01-30 11:16:11', 'Tes', 'EXPORT', 'tbl_portofolio_apps_master', 0, NULL, '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (136, '2026-01-30 11:16:24', 'Tes', 'EXPORT', 'tbl_portofolio_apps_master', 0, NULL, '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (137, '2026-01-30 11:18:20', 'Tes', 'EXPORT', 'tbl_portofolio_apps_master', 0, NULL, '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (138, '2026-01-30 11:53:21', 'Tes', 'EXPORT', 'tbl_apps_operational_hour', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (139, '2026-01-30 11:55:55', 'Tes', 'EXPORT', 'tbl_apps_operational_hour', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (140, '2026-01-30 12:00:31', 'Tes', 'EXPORT', 'tbl_apps_operational_day', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (141, '2026-01-30 12:52:04', 'Tes', 'EXPORT', 'tbl_apps_operational_day', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (142, '2026-01-30 14:00:19', 'Tes', 'EXPORT', 'tbl_database_master', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (143, '2026-01-30 14:14:33', 'Tes', 'EXPORT', 'tbl_apps_network', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (144, '2026-01-30 14:38:14', 'Tes', 'EXPORT', 'tbl_network_product', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (145, '2026-01-30 14:42:26', 'Tes', 'EXPORT', 'tbl_network_provider', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (146, '2026-01-30 15:07:19', 'Tes', 'EXPORT', 'tbl_operating_software', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (147, '2026-01-30 15:12:46', 'Tes', 'EXPORT', 'tbl_apps_deployment', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (148, '2026-02-02 09:24:58', 'Tes', 'ADD', 'tbl_apps_operational_day', 19, 'Start Day', '-', 'Monday', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (149, '2026-02-02 09:24:58', 'Tes', 'ADD', 'tbl_apps_operational_day', 19, 'End Day', '-', 'Tuesday', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (150, '2026-02-02 09:59:41', 'Tes', '', 'tbl_apps_operational_day', 19, 'status', '1', '0', 'ttes');
INSERT INTO `tbl_audit_trail` VALUES (151, '2026-02-02 09:59:51', 'Tes', '', 'tbl_apps_operational_day', 19, 'status', '0', '1', 'tes');
INSERT INTO `tbl_audit_trail` VALUES (152, '2026-02-02 10:34:20', 'Tes', 'ADD', 'tbl_apps_operational_hour', 27, 'Start Time', '-', '10:34', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (153, '2026-02-02 10:34:20', 'Tes', 'ADD', 'tbl_apps_operational_hour', 27, 'End Time', '-', '11:34', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (154, '2026-02-02 10:34:20', 'Tes', 'ADD', 'tbl_apps_operational_hour', 27, 'Total Hour', '-', '1.00 Hours', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (155, '2026-02-02 10:34:48', 'Tes', 'ADD', 'tbl_apps_operational_day', 20, 'Start Day', '-', 'Tuesday', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (156, '2026-02-02 10:34:48', 'Tes', 'ADD', 'tbl_apps_operational_day', 20, 'End Day', '-', 'Wednesday', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (157, '2026-02-02 10:39:05', 'Tes', 'ADD', 'tbl_apps_operational_day', 21, 'Start Day', '-', 'Monday', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (158, '2026-02-02 10:39:05', 'Tes', 'ADD', 'tbl_apps_operational_day', 21, 'End Day', '-', 'Wednesday', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (159, '2026-02-02 10:40:21', 'Tes', 'ADD', 'tbl_apps_operational_day', 22, 'Start Day', '-', 'Wednesday', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (160, '2026-02-02 10:40:21', 'Tes', 'ADD', 'tbl_apps_operational_day', 22, 'End Day', '-', 'Saturday', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (161, '2026-02-02 10:41:12', 'Tes', 'ADD', 'tbl_apps_operational_hour', 28, 'Start Time', '-', '12:41', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (162, '2026-02-02 10:41:12', 'Tes', 'ADD', 'tbl_apps_operational_hour', 28, 'End Time', '-', '14:41', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (163, '2026-02-02 10:41:12', 'Tes', 'ADD', 'tbl_apps_operational_hour', 28, 'Total Hour', '-', '2.00 Hours', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (164, '2026-02-02 10:41:32', 'Tes', 'ADD', 'tbl_apps_operational_hour', 29, 'Start Time', '-', '14:41', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (165, '2026-02-02 10:41:32', 'Tes', 'ADD', 'tbl_apps_operational_hour', 29, 'End Time', '-', '15:41', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (166, '2026-02-02 10:41:32', 'Tes', 'ADD', 'tbl_apps_operational_hour', 29, 'Total Hour', '-', '1.00 Hours', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (167, '2026-02-02 10:42:02', 'Tes', 'ADD', 'tbl_apps_operational_hour', 30, 'Start Time', '-', '11:41', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (168, '2026-02-02 10:42:02', 'Tes', 'ADD', 'tbl_apps_operational_hour', 30, 'End Time', '-', '12:42', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (169, '2026-02-02 10:42:02', 'Tes', 'ADD', 'tbl_apps_operational_hour', 30, 'Total Hour', '-', '1.02 Hours', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (170, '2026-02-02 10:58:14', 'Tes', 'ADD', 'tbl_database_master', 47, 'database_name', '-', 'tess', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (171, '2026-02-02 10:58:50', 'Tes', '', 'tbl_apps_operational_day', 22, 'status', '1', '0', 'tes');
INSERT INTO `tbl_audit_trail` VALUES (172, '2026-02-02 10:58:56', 'Tes', '', 'tbl_apps_operational_day', 22, 'status', '0', '1', 'tes');
INSERT INTO `tbl_audit_trail` VALUES (173, '2026-02-02 11:00:13', 'Tes', '', 'tbl_apps_operational_hour', 18, 'status', '1', '0', 'tes');
INSERT INTO `tbl_audit_trail` VALUES (174, '2026-02-02 11:00:18', 'Tes', '', 'tbl_apps_operational_hour', 18, 'status', '0', '1', 'aas');
INSERT INTO `tbl_audit_trail` VALUES (175, '2026-02-02 11:04:42', 'Tes', 'EDIT', 'tbl_database_master', 47, 'database_name', 'tess', 'tesss', 'aa');
INSERT INTO `tbl_audit_trail` VALUES (176, '2026-02-02 11:09:51', 'Tes', 'DEACTIVATE', 'tbl_database_master', 47, 'status', '1', '0', 'aaa');
INSERT INTO `tbl_audit_trail` VALUES (177, '2026-02-02 11:10:46', 'Tes', 'ADD', 'tbl_database_master', 48, 'database_name', '-', 'asasa', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (178, '2026-02-02 11:20:44', 'Tes', 'ADD', 'tbl_apps_network', 15, 'network_name', '-', 'aaaa', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (204, '2026-02-02 16:40:37', 'Tes', 'DEACTIVATE', 'tbl_database_master', 47, 'status', '1', '0', 'tes');
INSERT INTO `tbl_audit_trail` VALUES (205, '2026-02-02 16:59:32', 'Tes', 'EXPORT', 'tbl_apps_operational_hour', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (206, '2026-02-02 16:59:37', 'Tes', 'EXPORT', 'tbl_apps_operational_day', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (207, '2026-02-02 16:59:40', 'Tes', 'EXPORT', 'tbl_database_master', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (208, '2026-02-02 16:59:42', 'Tes', 'EXPORT', 'tbl_apps_network', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (209, '2026-02-02 16:59:44', 'Tes', 'EXPORT', 'tbl_network_product', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (210, '2026-02-02 16:59:46', 'Tes', 'EXPORT', 'tbl_apps_category', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (211, '2026-02-02 16:59:48', 'Tes', 'EXPORT', 'tbl_operating_software', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (212, '2026-02-02 16:59:50', 'Tes', 'EXPORT', 'tbl_apps_deployment', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (213, '2026-02-02 17:03:40', 'Tes', 'EXPORT', 'tbl_apps_operational_hour', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (214, '2026-02-02 17:05:19', 'Tes', 'EXPORT', 'tbl_apps_operational_day', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (215, '2026-02-02 17:06:08', 'Tes', 'EXPORT', 'tbl_database_master', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (216, '2026-02-02 17:06:43', 'Tes', 'DEACTIVATE', 'tbl_apps_deployment', 3, 'status', '1', '0', 'tes');
INSERT INTO `tbl_audit_trail` VALUES (217, '2026-02-02 17:06:56', 'Tes', 'ACTIVATE', 'tbl_apps_deployment', 3, 'status', '0', '1', 'tes');
INSERT INTO `tbl_audit_trail` VALUES (218, '2026-02-02 17:08:38', 'Tes', 'DEACTIVATE', 'tbl_apps_deployment', 1, 'status', '1', '0', 'tes');
INSERT INTO `tbl_audit_trail` VALUES (219, '2026-02-02 17:08:56', 'Tes', 'ACTIVATE', 'tbl_apps_deployment', 1, 'status', '0', '1', 'tes');
INSERT INTO `tbl_audit_trail` VALUES (220, '2026-02-03 17:16:39', 'Tes', 'EXPORT', 'tbl_portofolio_apps_master', 0, NULL, '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (221, '2026-02-04 10:04:40', 'Tes', 'EXPORT', 'tbl_portofolio_apps_master', 0, NULL, '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (222, '2026-02-04 10:05:11', 'Tes', 'EXPORT', 'tbl_portofolio_apps_master', 0, NULL, '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (223, '2026-02-20 15:12:30', 'role1', 'EXPORT', 'tbl_infra_module', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (224, '2026-02-20 15:12:36', 'role1', 'EXPORT', 'tbl_apps_network', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (225, '2026-02-25 13:45:55', 'role1', 'EXPORT', 'tbl_portofolio_apps_master', 0, NULL, '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (226, '2026-03-12 10:12:01', 'role1', 'EXPORT', 'tbl_app_type', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (227, '2026-03-12 10:15:43', 'role1', 'EXPORT', 'tbl_database_master', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (228, '2026-03-17 09:35:10', 'role1', 'EXPORT', 'tbl_app_type', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (229, '2026-03-17 09:35:45', 'role1', 'DEACTIVATE', 'tbl_apps_operational_hour', 18, 'status', '1', '0', 'AA');
INSERT INTO `tbl_audit_trail` VALUES (230, '2026-03-17 09:36:21', 'role1', 'ACTIVATE', 'tbl_apps_operational_hour', 18, 'status', '0', '1', 'test');
INSERT INTO `tbl_audit_trail` VALUES (231, '2026-03-17 09:37:09', 'role1', 'DEACTIVATE', 'tbl_apps_operational_hour', 18, 'status', '1', '0', 'aa');
INSERT INTO `tbl_audit_trail` VALUES (232, '2026-03-17 09:40:05', 'role1', 'ACTIVATE', 'tbl_apps_operational_hour', 18, 'status', '0', '1', 'res');
INSERT INTO `tbl_audit_trail` VALUES (233, '2026-03-17 09:40:17', 'role1', 'DEACTIVATE', 'tbl_apps_operational_hour', 18, 'status', '1', '0', 'res');
INSERT INTO `tbl_audit_trail` VALUES (234, '2026-03-17 09:40:22', 'role1', 'ACTIVATE', 'tbl_apps_operational_hour', 18, 'status', '0', '1', 'test');
INSERT INTO `tbl_audit_trail` VALUES (235, '2026-03-17 09:56:35', 'role1', 'DEACTIVATE', 'tbl_apps_deployment_model', 2, 'status', '1', '0', 'AA');
INSERT INTO `tbl_audit_trail` VALUES (236, '2026-03-27 10:05:11', 'role1', 'ADD', 'tbl_apps_operational_hour', 31, 'Start Time', '-', '08:00', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (237, '2026-03-27 10:05:11', 'role1', 'ADD', 'tbl_apps_operational_hour', 31, 'End Time', '-', '23:59', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (238, '2026-03-27 10:05:11', 'role1', 'ADD', 'tbl_apps_operational_hour', 31, 'Total Hour', '-', '15.98 Hours', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (239, '2026-03-27 17:13:46', 'role1', 'DEACTIVATE', 'tbl_app_type', 2, 'status', '1', '0', 'tess aja');
INSERT INTO `tbl_audit_trail` VALUES (240, '2026-03-27 17:16:58', 'role1', 'ACTIVATE', 'tbl_app_type', 2, 'status', '0', '1', 'a');
INSERT INTO `tbl_audit_trail` VALUES (241, '2026-03-30 09:45:02', 'role1', 'ACTIVATE', 'tbl_apps_deployment_model', 2, 'status', '0', '1', 't');
INSERT INTO `tbl_audit_trail` VALUES (242, '2026-03-30 15:31:30', 'role1', 'EXPORT', 'tbl_app_type', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (243, '2026-03-30 15:31:34', 'role1', 'EXPORT', 'tbl_apps_category', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (244, '2026-03-30 15:31:52', 'role1', 'EXPORT', 'tbl_app_type', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (245, '2026-03-30 15:32:01', 'role1', 'EXPORT', 'tbl_apps_category', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (246, '2026-03-30 15:32:08', 'role1', 'EXPORT', 'tbl_database_master', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (247, '2026-03-30 15:32:22', 'role1', 'EXPORT', 'tbl_apps_deployment', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (248, '2026-03-30 15:32:44', 'role1', 'EXPORT', 'tbl_apps_deployment_model', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (249, '2026-03-30 15:33:05', 'role1', 'EXPORT', 'tbl_apps_deployment_site', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (250, '2026-03-30 15:33:12', 'role1', 'EXPORT', 'tbl_apps_network', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (251, '2026-03-30 15:33:19', 'role1', 'EXPORT', 'tbl_network_product', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (252, '2026-03-30 15:33:30', 'role1', 'EXPORT', 'tbl_network_provider', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (253, '2026-03-30 15:33:39', 'role1', 'EXPORT', 'tbl_operating_software', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (254, '2026-03-30 15:33:56', 'role1', 'EXPORT', 'tbl_apps_operational_hour', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (255, '2026-03-30 15:34:12', 'role1', 'EXPORT', 'tbl_apps_operational_day', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (256, '2026-03-30 15:34:19', 'role1', 'EXPORT', 'tbl_server', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (257, '2026-03-30 15:35:04', 'role1', 'EXPORT', 'tbl_history', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (258, '2026-03-30 15:35:17', 'role1', 'EXPORT', 'tbl_user_role', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (259, '2026-03-30 15:37:20', 'role1', 'EXPORT', 'tbl_user_role', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (260, '2026-03-30 15:47:59', 'role1', 'ADD', 'tbl_apps_operational_day', 23, 'Start Day', '-', 'Tuesday', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (261, '2026-03-30 15:47:59', 'role1', 'ADD', 'tbl_apps_operational_day', 23, 'End Day', '-', 'Wednesday', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (262, '2026-03-30 16:00:09', 'role1', 'ADD', 'tbl_apps_operational_day', 24, 'Start Day', '-', 'Tuesday', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (263, '2026-03-30 16:00:09', 'role1', 'ADD', 'tbl_apps_operational_day', 24, 'End Day', '-', 'Friday', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (264, '2026-03-30 16:01:18', 'role1', 'EXPORT', 'tbl_apps_operational_day', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (265, '2026-03-30 16:01:54', 'role1', 'EXPORT', 'tbl_apps_operational_day', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (266, '2026-03-30 16:05:19', 'role1', 'EXPORT', 'tbl_apps_operational_day', 0, '-', '-', '-', 'Export Data (Keyword: 4)');
INSERT INTO `tbl_audit_trail` VALUES (267, '2026-03-30 16:08:50', 'role1', 'EXPORT', 'tbl_apps_category', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (268, '2026-03-30 16:09:08', 'role1', 'EXPORT', 'tbl_apps_operational_day', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (269, '2026-03-30 16:10:20', 'role1', 'EXPORT', 'tbl_apps_deployment', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (270, '2026-03-30 16:19:03', 'role1', 'EXPORT', 'tbl_apps_operational_hour', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (271, '2026-03-31 08:48:09', 'role1', 'EXPORT', 'tbl_apps_operational_day', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (272, '2026-03-31 08:49:09', 'role1', 'EXPORT', 'tbl_user_role', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (273, '2026-03-31 08:51:40', 'role1', 'EXPORT', 'tbl_history', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (274, '2026-03-31 08:52:27', 'role1', 'EXPORT', 'tbl_app_type', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (275, '2026-03-31 08:52:52', 'role1', 'EXPORT', 'tbl_apps_operational_day', 0, '-', '-', '-', 'Export Data (Keyword: 7)');
INSERT INTO `tbl_audit_trail` VALUES (276, '2026-03-31 09:00:48', 'role1', 'EXPORT', 'tbl_user_role', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (277, '2026-03-31 09:01:32', 'role1', 'EXPORT', 'tbl_user_role', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (278, '2026-03-31 13:23:35', 'role1', 'EXPORT', 'tbl_history', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (279, '2026-03-31 14:43:21', 'role1', 'EXPORT', 'tbl_history', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (280, '2026-03-31 14:43:30', 'role1', 'EXPORT', 'tbl_history', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (281, '2026-03-31 14:43:36', 'role1', 'EXPORT', 'tbl_history', 0, '-', '-', '-', 'Export Data');
INSERT INTO `tbl_audit_trail` VALUES (282, '2026-04-01 08:29:48', 'role1', 'ADD', 'tbl_app_type', 4, 'app_type_name', '-', 'Test App Type', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (283, '2026-04-01 08:30:05', 'role1', 'ADD', 'tbl_apps_category', 16, 'Category Name', '-', 'Test Category', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (284, '2026-04-01 08:30:05', 'role1', 'ADD', 'tbl_apps_category', 16, 'Standard Category', '-', '0', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (285, '2026-04-01 08:30:34', 'role1', 'DEACTIVATE', 'tbl_apps_category', 16, 'status', '1', '0', 'Tes Category');
INSERT INTO `tbl_audit_trail` VALUES (286, '2026-04-01 08:30:53', 'role1', 'DEACTIVATE', 'tbl_app_type', 4, 'status', '1', '0', 'Tes Category');
INSERT INTO `tbl_audit_trail` VALUES (287, '2026-04-01 08:34:25', 'role1', 'ACTIVATE', 'tbl_app_type', 4, 'status', '0', '1', 'Tes App Type');
INSERT INTO `tbl_audit_trail` VALUES (288, '2026-04-01 08:34:33', 'role1', 'DEACTIVATE', 'tbl_app_type', 4, 'status', '1', '0', 'Tes');
INSERT INTO `tbl_audit_trail` VALUES (289, '2026-04-01 08:34:40', 'role1', 'ACTIVATE', 'tbl_app_type', 4, 'status', '0', '1', 's');
INSERT INTO `tbl_audit_trail` VALUES (290, '2026-04-01 08:35:49', 'role1', 'DEACTIVATE', 'tbl_app_type', 4, 'status', '1', '0', 'tes');
INSERT INTO `tbl_audit_trail` VALUES (291, '2026-04-01 08:37:29', 'role1', 'ACTIVATE', 'tbl_apps_category', 16, 'status', '0', '1', 'tes');
INSERT INTO `tbl_audit_trail` VALUES (292, '2026-04-01 08:39:16', 'role1', 'ACTIVATE', 'tbl_app_type', 4, 'status', '0', '1', 'tes');
INSERT INTO `tbl_audit_trail` VALUES (293, '2026-04-01 08:40:02', 'role1', 'DEACTIVATE', 'tbl_apps_category', 16, 'status', '1', '0', 'Tes Category');
INSERT INTO `tbl_audit_trail` VALUES (294, '2026-04-01 08:40:07', 'role1', 'ACTIVATE', 'tbl_apps_category', 16, 'status', '0', '1', 'tes');
INSERT INTO `tbl_audit_trail` VALUES (295, '2026-04-01 08:40:38', 'role1', 'ADD', 'tbl_database_master', 49, 'database_name', '-', 'Tes Database', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (296, '2026-04-01 08:40:47', 'role1', 'DEACTIVATE', 'tbl_database_master', 49, 'status', '1', '0', 'Tes DB');
INSERT INTO `tbl_audit_trail` VALUES (297, '2026-04-01 08:41:24', 'role1', 'ADD', 'tbl_apps_deployment', 13, 'deployment_model', '-', 'Tes Deploy', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (298, '2026-04-01 08:41:32', 'role1', 'EDIT', 'tbl_apps_deployment', 13, 'deployment_model', 'Tes Deploy', 'Tes Deploys', 'tes');
INSERT INTO `tbl_audit_trail` VALUES (299, '2026-04-01 08:41:57', 'role1', 'DEACTIVATE', 'tbl_apps_deployment', 13, 'status', '1', '0', 'tes dep');
INSERT INTO `tbl_audit_trail` VALUES (300, '2026-04-01 08:42:16', 'role1', 'ACTIVATE', 'tbl_database_master', 49, 'status', '0', '1', 'tes');
INSERT INTO `tbl_audit_trail` VALUES (301, '2026-04-01 08:42:22', 'role1', 'EDIT', 'tbl_database_master', 49, 'database_name', 'Tes Database', 'Tes Databases', 'tes');
INSERT INTO `tbl_audit_trail` VALUES (302, '2026-04-01 08:42:26', 'role1', 'DEACTIVATE', 'tbl_database_master', 49, 'status', '1', '0', 'tes');
INSERT INTO `tbl_audit_trail` VALUES (303, '2026-04-01 08:42:49', 'role1', 'EDIT', 'tbl_apps_category', 16, 'Category Name', 'Test Category', 'Test Categorys', 'tes');
INSERT INTO `tbl_audit_trail` VALUES (304, '2026-04-01 08:43:04', 'role1', 'EDIT', 'tbl_apps_category', 16, 'Standard Category', '0.00', '1.00', 'tes');
INSERT INTO `tbl_audit_trail` VALUES (305, '2026-04-01 08:43:30', 'role1', 'DEACTIVATE', 'tbl_apps_category', 16, 'status', '1', '0', 'tes');
INSERT INTO `tbl_audit_trail` VALUES (306, '2026-04-01 08:43:50', 'role1', 'EDIT', 'tbl_app_type', 4, 'app_type_name', 'Test App Type', 'Test App Types', 'tes');
INSERT INTO `tbl_audit_trail` VALUES (307, '2026-04-01 08:43:55', 'role1', 'DEACTIVATE', 'tbl_app_type', 4, 'status', '1', '0', 'tes');
INSERT INTO `tbl_audit_trail` VALUES (308, '2026-04-01 08:45:04', 'role1', 'ADD', 'tbl_apps_deployment_model', 3, 'deployment_provider_name', '-', 'Tes Deploy Prv', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (309, '2026-04-01 08:45:12', 'role1', 'EDIT', 'tbl_apps_deployment_model', 3, 'deployment_provider_name', 'Tes Deploy Prv', 'Tes Deploy Prvs', 'Tes Edit');
INSERT INTO `tbl_audit_trail` VALUES (310, '2026-04-01 08:45:17', 'role1', 'DEACTIVATE', 'tbl_apps_deployment_model', 3, 'status', '1', '0', 'Tes off');
INSERT INTO `tbl_audit_trail` VALUES (311, '2026-04-01 08:45:50', 'role1', 'ADD', 'tbl_apps_deployment_site', 4, 'deployment_site_name', '-', 'Tes Deploy Site', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (312, '2026-04-01 08:46:03', 'role1', 'EDIT', 'tbl_apps_deployment_site', 4, 'deployment_site_name', 'Tes Deploy Site', 'Tes Deploy Sites', 'Tes Edit');
INSERT INTO `tbl_audit_trail` VALUES (313, '2026-04-01 08:46:08', 'role1', 'DEACTIVATE', 'tbl_apps_deployment_site', 4, 'status', '1', '0', 'Tes off');
INSERT INTO `tbl_audit_trail` VALUES (314, '2026-04-01 08:46:25', 'role1', 'ACTIVATE', 'tbl_apps_deployment_site', 4, 'status', '0', '1', 'tes on');
INSERT INTO `tbl_audit_trail` VALUES (315, '2026-04-01 08:46:35', 'role1', 'DEACTIVATE', 'tbl_apps_deployment_site', 4, 'status', '1', '0', 'tes off');
INSERT INTO `tbl_audit_trail` VALUES (316, '2026-04-01 08:46:42', 'role1', 'ACTIVATE', 'tbl_apps_deployment_model', 3, 'status', '0', '1', 'tes on');
INSERT INTO `tbl_audit_trail` VALUES (317, '2026-04-01 08:46:47', 'role1', 'DEACTIVATE', 'tbl_apps_deployment_model', 3, 'status', '1', '0', 'tes off');
INSERT INTO `tbl_audit_trail` VALUES (318, '2026-04-01 08:47:41', 'role1', 'ACTIVATE', 'tbl_apps_deployment', 13, 'status', '0', '1', 'tes on');
INSERT INTO `tbl_audit_trail` VALUES (319, '2026-04-01 08:47:49', 'role1', 'DEACTIVATE', 'tbl_apps_deployment', 13, 'status', '1', '0', 'tes off');
INSERT INTO `tbl_audit_trail` VALUES (320, '2026-04-01 08:48:14', 'role1', 'ACTIVATE', 'tbl_database_master', 49, 'status', '0', '1', 'tes on');
INSERT INTO `tbl_audit_trail` VALUES (321, '2026-04-01 08:48:18', 'role1', 'DEACTIVATE', 'tbl_database_master', 49, 'status', '1', '0', 'tes off');
INSERT INTO `tbl_audit_trail` VALUES (322, '2026-04-01 08:48:33', 'role1', 'ACTIVATE', 'tbl_apps_category', 16, 'status', '0', '1', 'tes on');
INSERT INTO `tbl_audit_trail` VALUES (323, '2026-04-01 08:48:38', 'role1', 'DEACTIVATE', 'tbl_apps_category', 16, 'status', '1', '0', 'tes off');
INSERT INTO `tbl_audit_trail` VALUES (324, '2026-04-01 08:48:48', 'role1', 'ACTIVATE', 'tbl_app_type', 4, 'status', '0', '1', 'tes on');
INSERT INTO `tbl_audit_trail` VALUES (325, '2026-04-01 08:48:54', 'role1', 'DEACTIVATE', 'tbl_app_type', 4, 'status', '1', '0', 'tes off');
INSERT INTO `tbl_audit_trail` VALUES (326, '2026-04-01 08:49:33', 'role1', 'ADD', 'tbl_apps_network', 16, 'network_name', '-', 'Tes Network', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (327, '2026-04-01 08:49:40', 'role1', 'EDIT', 'tbl_apps_network', 16, 'network_name', 'Tes Network', 'Tes Networks', 'tes edit');
INSERT INTO `tbl_audit_trail` VALUES (328, '2026-04-01 08:49:48', 'role1', 'DEACTIVATE', 'tbl_apps_network', 16, 'status', '1', '0', 'tes off');
INSERT INTO `tbl_audit_trail` VALUES (329, '2026-04-01 08:49:53', 'role1', 'ACTIVATE', 'tbl_apps_network', 16, 'status', '0', '1', 'tes on');
INSERT INTO `tbl_audit_trail` VALUES (330, '2026-04-01 08:49:59', 'role1', 'DEACTIVATE', 'tbl_apps_network', 16, 'status', '1', '0', 'tes off');
INSERT INTO `tbl_audit_trail` VALUES (331, '2026-04-01 08:50:54', 'role1', 'ADD', 'tbl_operating_software', 15, 'operating_software_name', '-', 'Tes OS', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (332, '2026-04-01 08:51:03', 'role1', 'EDIT', 'tbl_operating_software', 15, 'operating_software_name', 'Tes OS', 'Tes OSs', 'tes edit');
INSERT INTO `tbl_audit_trail` VALUES (333, '2026-04-01 08:51:07', 'role1', 'DEACTIVATE', 'tbl_operating_software', 15, 'status', '1', '0', 'tes off');
INSERT INTO `tbl_audit_trail` VALUES (334, '2026-04-01 08:51:12', 'role1', 'ACTIVATE', 'tbl_operating_software', 15, 'status', '0', '1', 'tes on');
INSERT INTO `tbl_audit_trail` VALUES (335, '2026-04-01 08:51:18', 'role1', 'DEACTIVATE', 'tbl_operating_software', 15, 'status', '1', '0', 'tes off');
INSERT INTO `tbl_audit_trail` VALUES (336, '2026-04-01 08:51:59', 'role1', 'ADD', 'tbl_apps_operational_hour', 32, 'Start Time', '-', '08:51', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (337, '2026-04-01 08:51:59', 'role1', 'ADD', 'tbl_apps_operational_hour', 32, 'End Time', '-', '09:51', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (338, '2026-04-01 08:51:59', 'role1', 'ADD', 'tbl_apps_operational_hour', 32, 'Total Hour', '-', '1.00 Hours', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (339, '2026-04-01 08:52:27', 'role1', 'EDIT', 'tbl_apps_operational_hour', 32, 'Start Time', '08:51:00', '08:52', 'tes edit');
INSERT INTO `tbl_audit_trail` VALUES (340, '2026-04-01 08:52:27', 'role1', 'EDIT', 'tbl_apps_operational_hour', 32, 'End Time', '09:51:00', '09:52', 'tes edit');
INSERT INTO `tbl_audit_trail` VALUES (341, '2026-04-01 08:52:32', 'role1', 'DEACTIVATE', 'tbl_apps_operational_hour', 32, 'status', '1', '0', 'tes off');
INSERT INTO `tbl_audit_trail` VALUES (342, '2026-04-01 08:52:37', 'role1', 'ACTIVATE', 'tbl_apps_operational_hour', 32, 'status', '0', '1', 'tes on');
INSERT INTO `tbl_audit_trail` VALUES (343, '2026-04-01 08:52:42', 'role1', 'DEACTIVATE', 'tbl_apps_operational_hour', 32, 'status', '1', '0', 'tes off');
INSERT INTO `tbl_audit_trail` VALUES (344, '2026-04-01 08:53:36', 'role1', 'ADD', 'tbl_apps_operational_day', 25, 'Start Day', '-', 'Monday', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (345, '2026-04-01 08:53:36', 'role1', 'ADD', 'tbl_apps_operational_day', 25, 'End Day', '-', 'Friday', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (346, '2026-04-01 08:54:08', 'role1', 'EDIT', 'tbl_apps_operational_day', 25, 'End Day', 'Friday', 'Wednesday', 'tes edit');
INSERT INTO `tbl_audit_trail` VALUES (347, '2026-04-01 08:54:23', 'role1', 'DEACTIVATE', 'tbl_apps_operational_day', 25, 'status', '1', '0', 'tes off');
INSERT INTO `tbl_audit_trail` VALUES (348, '2026-04-01 08:54:28', 'role1', 'ACTIVATE', 'tbl_apps_operational_day', 25, 'status', '0', '1', 'tes on');
INSERT INTO `tbl_audit_trail` VALUES (349, '2026-04-01 08:54:33', 'role1', 'DEACTIVATE', 'tbl_apps_operational_day', 25, 'status', '1', '0', 'tes off');
INSERT INTO `tbl_audit_trail` VALUES (350, '2026-04-01 08:55:32', 'role1', 'ADD', 'tbl_server', 14, 'server_name', '-', 'Tes Server', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (351, '2026-04-01 08:55:40', 'role1', 'EDIT', 'tbl_server', 14, 'server_name', 'Tes Server', 'Tes Servers', 'tes edit');
INSERT INTO `tbl_audit_trail` VALUES (352, '2026-04-01 08:55:46', 'role1', 'DEACTIVATE', 'tbl_server', 14, 'status', '1', '0', 'tes off');
INSERT INTO `tbl_audit_trail` VALUES (353, '2026-04-01 08:55:50', 'role1', 'ACTIVATE', 'tbl_server', 14, 'status', '0', '1', 'tes on');
INSERT INTO `tbl_audit_trail` VALUES (354, '2026-04-01 08:55:54', 'role1', 'DEACTIVATE', 'tbl_server', 14, 'status', '1', '0', 'tes off');
INSERT INTO `tbl_audit_trail` VALUES (355, '2026-04-01 08:56:29', 'role1', 'ADD', 'tbl_holiday', 22, 'Holiday Name', '-', 'Tes Holiday', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (356, '2026-04-01 08:56:29', 'role1', 'ADD', 'tbl_holiday', 22, 'Holiday Date', '-', '2026-04-02', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (357, '2026-04-01 08:56:29', 'role1', 'ADD', 'tbl_holiday', 22, 'Holiday Description', '-', 'Tes add', 'Initial creation');
INSERT INTO `tbl_audit_trail` VALUES (358, '2026-04-01 08:57:08', 'role1', 'EDIT', 'tbl_holiday', 22, 'Holiday Name', 'Tes Holiday', 'Tes Holidays', 'tes edit');
INSERT INTO `tbl_audit_trail` VALUES (359, '2026-04-01 08:57:36', 'role1', 'DELETE', 'tbl_holiday', 22, 'Holiday Date', '2026-04-02 Tes Holidays', '-', 'tes del');
INSERT INTO `tbl_audit_trail` VALUES (360, '2026-04-01 09:01:59', 'role1', 'ADD', 'users', 12, 'Username', '-', 'Tes User', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (361, '2026-04-01 09:01:59', 'role1', 'ADD', 'users', 12, 'Email', '-', 'tes0@gmail.com', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (362, '2026-04-01 09:01:59', 'role1', 'ADD', 'users', 12, 'Password', '-', '********', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (363, '2026-04-01 09:04:37', 'role1', 'ADD', 'tbl_user_role', 13, 'User Role', '-', 'EA', 'Initial Creation');
INSERT INTO `tbl_audit_trail` VALUES (364, '2026-04-01 09:05:08', 'role1', 'EDIT', 'users', 12, 'Username', 'Tes User', 'Tes Users', 'tes edit');
INSERT INTO `tbl_audit_trail` VALUES (365, '2026-04-01 09:05:08', 'role1', 'EDIT', 'users', 12, 'Email', 'tes0@gmail.com', 'tes1@gmail.com', 'tes edit');
INSERT INTO `tbl_audit_trail` VALUES (366, '2026-04-01 09:05:08', 'role1', 'EDIT', 'tbl_user_role', 13, 'Role', 'EA', 'IT SLM', 'tes edit');
INSERT INTO `tbl_audit_trail` VALUES (367, '2026-04-01 09:05:15', 'role1', 'DEACTIVATE', 'tbl_user_role', 13, 'status', '1', '0', 'tes off');
INSERT INTO `tbl_audit_trail` VALUES (368, '2026-04-01 09:05:35', 'role1', 'ACTIVATE', 'tbl_user_role', 13, 'status', '0', '1', 'tes on');
INSERT INTO `tbl_audit_trail` VALUES (369, '2026-04-01 09:05:40', 'role1', 'DEACTIVATE', 'tbl_user_role', 13, 'status', '1', '0', 'tees off');
INSERT INTO `tbl_audit_trail` VALUES (370, '2026-04-01 09:05:54', 'role1', 'ACTIVATE', 'tbl_user_role', 13, 'status', '0', '1', 'tes on');
INSERT INTO `tbl_audit_trail` VALUES (371, '2026-04-01 09:06:23', 'role1', 'DEACTIVATE', 'tbl_user_role', 13, 'status', '1', '0', 'tes off');
INSERT INTO `tbl_audit_trail` VALUES (372, '2026-04-01 09:12:47', 'role1', 'ACTIVATE', 'tbl_user_role', 13, 'status', '0', '1', 'tes');
INSERT INTO `tbl_audit_trail` VALUES (373, '2026-04-01 09:13:20', 'role1', 'DEACTIVATE', 'tbl_user_role', 13, 'status', '1', '0', 'tes off');

-- ----------------------------
-- Table structure for tbl_database_master
-- ----------------------------
DROP TABLE IF EXISTS `tbl_database_master`;
CREATE TABLE `tbl_database_master`  (
  `database_id` int(5) NOT NULL AUTO_INCREMENT,
  `database_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(11) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `modified_by` int(11) NULL DEFAULT NULL,
  `modified_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`database_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 50 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_database_master
-- ----------------------------
INSERT INTO `tbl_database_master` VALUES (1, 'DB2', 1, NULL, NULL, 2, '2026-01-20 00:00:00');
INSERT INTO `tbl_database_master` VALUES (2, 'Oracle', 1, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_database_master` VALUES (3, 'Ms SQL Server', 1, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_database_master` VALUES (4, 'CloudSQL for MySQL 8.0', 1, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_database_master` VALUES (49, 'Tes Databases', 0, 1, '2026-04-01 08:40:38', 1, '2026-04-01 08:42:22');

-- ----------------------------
-- Table structure for tbl_holiday
-- ----------------------------
DROP TABLE IF EXISTS `tbl_holiday`;
CREATE TABLE `tbl_holiday`  (
  `Holiday_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Holiday_Date` date NOT NULL,
  `Holiday_Description` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `Holiday_Name` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_by` int(11) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `modified_at` int(11) NULL DEFAULT NULL,
  `modified_by` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`Holiday_ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 23 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_holiday
-- ----------------------------
INSERT INTO `tbl_holiday` VALUES (1, '2026-02-16', 'Tahun Baru Imlek', 'Cuti Bersama', NULL, NULL, NULL, NULL);
INSERT INTO `tbl_holiday` VALUES (5, '1970-01-01', 'test', 'Libur Test', NULL, NULL, NULL, NULL);
INSERT INTO `tbl_holiday` VALUES (6, '1970-01-01', 'Test', 'Libur Test', NULL, NULL, NULL, NULL);
INSERT INTO `tbl_holiday` VALUES (7, '2026-01-24', 'TEST', 'Libur Test', NULL, NULL, NULL, NULL);
INSERT INTO `tbl_holiday` VALUES (8, '2026-01-22', 'test', 'Libur Test', NULL, NULL, NULL, NULL);
INSERT INTO `tbl_holiday` VALUES (9, '2026-01-31', 'test', 'Libur Test', NULL, NULL, NULL, NULL);
INSERT INTO `tbl_holiday` VALUES (10, '2026-02-11', 'test', 'Libur Test', NULL, NULL, NULL, NULL);
INSERT INTO `tbl_holiday` VALUES (11, '2026-01-28', 'test', 'test', NULL, NULL, NULL, NULL);
INSERT INTO `tbl_holiday` VALUES (13, '2026-01-29', '-', 'Libur Test', NULL, NULL, NULL, NULL);
INSERT INTO `tbl_holiday` VALUES (14, '2026-02-13', 'tEST', 'Libur Test', NULL, NULL, NULL, NULL);
INSERT INTO `tbl_holiday` VALUES (15, '2026-03-13', 'test', 'Libur Test', NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for tbl_module
-- ----------------------------
DROP TABLE IF EXISTS `tbl_module`;
CREATE TABLE `tbl_module`  (
  `module_id` int(11) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` tinyint(1) NULL DEFAULT 1,
  `created_by` int(11) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `modified_by` int(11) NULL DEFAULT NULL,
  `modified_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`module_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_module
-- ----------------------------
INSERT INTO `tbl_module` VALUES (1, 'Actimize', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_module` VALUES (2, 'ALM', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_module` VALUES (3, 'AML', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_module` VALUES (4, 'GatotKaca', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_module` VALUES (5, 'CREDIT', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_module` VALUES (6, 'ETP', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_module` VALUES (7, 'Trade', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_module` VALUES (8, 'BERPESTA', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_module` VALUES (9, 'FAST', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_module` VALUES (10, 'BIZ', NULL, NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for tbl_network_product
-- ----------------------------
DROP TABLE IF EXISTS `tbl_network_product`;
CREATE TABLE `tbl_network_product`  (
  `product_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id Network Produk',
  `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'Nama Network Produk',
  `product_sla` decimal(10, 2) NULL DEFAULT NULL COMMENT 'SLA Network',
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(11) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `modified_by` int(11) NULL DEFAULT NULL,
  `modified_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`product_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_network_product
-- ----------------------------
INSERT INTO `tbl_network_product` VALUES (1, 'Internet_SDWAN', 98.50, 1, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_network_product` VALUES (2, 'DWDM', 98.50, 1, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_network_product` VALUES (3, 'MPLS', 98.50, 1, NULL, NULL, 2, '2026-01-26 03:05:18');
INSERT INTO `tbl_network_product` VALUES (4, 'Metro-E', 98.50, 1, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_network_product` VALUES (5, 'Mobil-KAS', 98.50, 1, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_network_product` VALUES (6, 'PaymentPoint', 98.50, 1, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_network_product` VALUES (7, 'CallCenter', 98.50, 1, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_network_product` VALUES (8, 'NAC', 98.50, 1, NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for tbl_network_product_junc
-- ----------------------------
DROP TABLE IF EXISTS `tbl_network_product_junc`;
CREATE TABLE `tbl_network_product_junc`  (
  `network_product_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NULL DEFAULT NULL,
  `network_id` int(11) NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`network_product_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 36 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_network_product_junc
-- ----------------------------
INSERT INTO `tbl_network_product_junc` VALUES (1, 1, 1, 1);
INSERT INTO `tbl_network_product_junc` VALUES (2, 1, 2, 1);
INSERT INTO `tbl_network_product_junc` VALUES (3, 2, 3, 1);
INSERT INTO `tbl_network_product_junc` VALUES (5, 4, NULL, 1);
INSERT INTO `tbl_network_product_junc` VALUES (6, 5, NULL, 1);
INSERT INTO `tbl_network_product_junc` VALUES (7, 6, NULL, 1);
INSERT INTO `tbl_network_product_junc` VALUES (8, 7, NULL, 1);
INSERT INTO `tbl_network_product_junc` VALUES (9, 8, NULL, 1);
INSERT INTO `tbl_network_product_junc` VALUES (30, 3, 2, 1);
INSERT INTO `tbl_network_product_junc` VALUES (31, 9, 1, 1);
INSERT INTO `tbl_network_product_junc` VALUES (32, 10, 2, 1);
INSERT INTO `tbl_network_product_junc` VALUES (34, 11, 3, 1);
INSERT INTO `tbl_network_product_junc` VALUES (35, 12, 1, 1);

-- ----------------------------
-- Table structure for tbl_network_provider
-- ----------------------------
DROP TABLE IF EXISTS `tbl_network_provider`;
CREATE TABLE `tbl_network_provider`  (
  `provider_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id Network Provider',
  `provider_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'Nama Network Provider',
  `status` tinyint(1) NULL DEFAULT NULL,
  `created_by` int(11) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `modified_by` int(11) NULL DEFAULT NULL,
  `modified_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`provider_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_network_provider
-- ----------------------------
INSERT INTO `tbl_network_provider` VALUES (1, 'FiberStar', 1, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_network_provider` VALUES (2, 'iForte', 1, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_network_provider` VALUES (3, 'Indosat', 1, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_network_provider` VALUES (4, 'LinkNet', 1, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_network_provider` VALUES (5, 'Lintasarta', 1, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_network_provider` VALUES (6, 'Telkom', 1, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_network_provider` VALUES (7, 'XL', 1, NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for tbl_network_provider_junc
-- ----------------------------
DROP TABLE IF EXISTS `tbl_network_provider_junc`;
CREATE TABLE `tbl_network_provider_junc`  (
  `network_provider_id` int(11) NOT NULL AUTO_INCREMENT,
  `provider_id` int(11) NULL DEFAULT NULL,
  `network_id` int(11) NULL DEFAULT NULL,
  `status` tinyint(1) NULL DEFAULT NULL,
  PRIMARY KEY (`network_provider_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 32 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_network_provider_junc
-- ----------------------------
INSERT INTO `tbl_network_provider_junc` VALUES (1, 1, 1, 1);
INSERT INTO `tbl_network_provider_junc` VALUES (2, 2, 3, 1);
INSERT INTO `tbl_network_provider_junc` VALUES (3, 2, 1, 1);
INSERT INTO `tbl_network_provider_junc` VALUES (4, 2, 2, 1);
INSERT INTO `tbl_network_provider_junc` VALUES (5, 3, 2, 1);
INSERT INTO `tbl_network_provider_junc` VALUES (6, 3, 3, 1);
INSERT INTO `tbl_network_provider_junc` VALUES (7, 3, 1, 1);
INSERT INTO `tbl_network_provider_junc` VALUES (10, 4, 3, 1);
INSERT INTO `tbl_network_provider_junc` VALUES (11, 4, 2, 1);
INSERT INTO `tbl_network_provider_junc` VALUES (12, 4, NULL, 1);
INSERT INTO `tbl_network_provider_junc` VALUES (13, 5, 2, 1);
INSERT INTO `tbl_network_provider_junc` VALUES (14, 5, 3, 1);
INSERT INTO `tbl_network_provider_junc` VALUES (17, 5, NULL, 1);
INSERT INTO `tbl_network_provider_junc` VALUES (18, 6, 2, 1);
INSERT INTO `tbl_network_provider_junc` VALUES (19, 6, NULL, 1);
INSERT INTO `tbl_network_provider_junc` VALUES (20, 6, 3, 1);
INSERT INTO `tbl_network_provider_junc` VALUES (23, 6, 1, 1);
INSERT INTO `tbl_network_provider_junc` VALUES (26, 7, 2, 1);
INSERT INTO `tbl_network_provider_junc` VALUES (27, 7, 1, 1);
INSERT INTO `tbl_network_provider_junc` VALUES (29, 7, NULL, 1);
INSERT INTO `tbl_network_provider_junc` VALUES (31, 8, 3, 1);

-- ----------------------------
-- Table structure for tbl_operating_software
-- ----------------------------
DROP TABLE IF EXISTS `tbl_operating_software`;
CREATE TABLE `tbl_operating_software`  (
  `operating_software_id` int(5) NOT NULL AUTO_INCREMENT,
  `operating_software_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_by` int(11) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `modified_by` int(11) NULL DEFAULT NULL,
  `modified_at` datetime(0) NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`operating_software_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_operating_software
-- ----------------------------
INSERT INTO `tbl_operating_software` VALUES (1, 'OS400', NULL, NULL, 2, '2026-01-20 14:25:48', 1);
INSERT INTO `tbl_operating_software` VALUES (2, 'Ms Windows Server', NULL, NULL, NULL, NULL, 1);
INSERT INTO `tbl_operating_software` VALUES (3, 'Solaris', NULL, NULL, NULL, NULL, 1);
INSERT INTO `tbl_operating_software` VALUES (4, 'RHEL', NULL, NULL, NULL, NULL, 1);
INSERT INTO `tbl_operating_software` VALUES (15, 'Tes OSs', 1, '2026-04-01 08:50:54', 1, '2026-04-01 08:51:03', 0);

-- ----------------------------
-- Table structure for tbl_penanganan_dr
-- ----------------------------
DROP TABLE IF EXISTS `tbl_penanganan_dr`;
CREATE TABLE `tbl_penanganan_dr`  (
  `penanganan_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `recovery_time_dr` int(11) NOT NULL,
  PRIMARY KEY (`penanganan_id`) USING BTREE,
  INDEX `FK_CategoryPenanganan`(`category_id`) USING BTREE,
  CONSTRAINT `tbl_penanganan_dr_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `tbl_apps_category` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_penanganan_dr
-- ----------------------------
INSERT INTO `tbl_penanganan_dr` VALUES (1, 1, 5);
INSERT INTO `tbl_penanganan_dr` VALUES (2, 2, 3);
INSERT INTO `tbl_penanganan_dr` VALUES (3, 3, 2);
INSERT INTO `tbl_penanganan_dr` VALUES (4, 4, 5);
INSERT INTO `tbl_penanganan_dr` VALUES (5, 5, 4);

-- ----------------------------
-- Table structure for tbl_penanganan_insiden
-- ----------------------------
DROP TABLE IF EXISTS `tbl_penanganan_insiden`;
CREATE TABLE `tbl_penanganan_insiden`  (
  `Penanganan_insiden_id` int(11) NOT NULL,
  `Category_id` int(11) NOT NULL,
  `response_time` int(11) NOT NULL,
  `response_time_sat` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `recovery_time` int(11) NOT NULL,
  `recovery_time_sat` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`Penanganan_insiden_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_penanganan_insiden
-- ----------------------------
INSERT INTO `tbl_penanganan_insiden` VALUES (1, 1, 50, 'Menit', 5, 'Jam');
INSERT INTO `tbl_penanganan_insiden` VALUES (2, 2, 40, 'Menit', 9, 'Jam');
INSERT INTO `tbl_penanganan_insiden` VALUES (3, 3, 1, 'Jam', 9, 'Hari Kerja');
INSERT INTO `tbl_penanganan_insiden` VALUES (4, 4, 1, 'Hari Kerja', 9, 'Hari Kerja');

-- ----------------------------
-- Table structure for tbl_portofolio_apps_master
-- ----------------------------
DROP TABLE IF EXISTS `tbl_portofolio_apps_master`;
CREATE TABLE `tbl_portofolio_apps_master`  (
  `apps_id` int(5) NOT NULL AUTO_INCREMENT,
  `resilience_id` int(5) NULL DEFAULT NULL,
  `deployment_id` int(5) NULL DEFAULT NULL,
  `network_id` int(5) NULL DEFAULT NULL,
  `category_id` int(5) NULL DEFAULT NULL,
  `operational_hour_id` int(5) NULL DEFAULT NULL,
  `operational_day_id` int(5) NULL DEFAULT NULL,
  `deployment_provider_id` int(5) NULL DEFAULT NULL,
  `deployment_site_id` int(5) NULL DEFAULT NULL,
  `app_type_id` int(5) NULL DEFAULT NULL,
  `short_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `application_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `module` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `apps_description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `solution_vendor` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `services_vendor` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `standard_category` decimal(10, 2) NULL DEFAULT NULL,
  `live_year` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `decommission_year` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `lob_directorate` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `lob_subdirectorate` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `lob_department_head` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `lob_group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `lob_group_head` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `it_subdirectorate` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `it_department_head` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `it_support_group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `it_group_head` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `it_support_divison` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `it_division_head` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `application_version` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `development_language` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `application_developer` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `supporting_web_server` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `supporting_application_server` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `supporting_others` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `source_code_owned` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `Url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `attached_document` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_by` int(11) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `modified_by` int(11) NULL DEFAULT NULL,
  `modified_at` datetime(0) NULL DEFAULT NULL,
  `approved_by` int(11) NULL DEFAULT NULL,
  `approved_at` datetime(0) NULL DEFAULT NULL,
  `status` bigint(1) NULL DEFAULT 1,
  PRIMARY KEY (`apps_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 93 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_portofolio_apps_master
-- ----------------------------
INSERT INTO `tbl_portofolio_apps_master` VALUES (72, 2, 1, 3, 3, 31, 1, 1, 1, 1, 'GFMS', 'Group Financial Management System', 'Group Financial Management System', 'Group Financial Management System &#40;GFMS&#41; adalah Platform regional untuk Sistem Keuangan Terintegrasi Grup.', 'Oracle', 'Mitra Integrasi Informatika', 98.00, '2025', '2026', 'Directorate', 'Sub-Directorate', 'Department Head', 'Group', 'Group Head', 'IT Sub-Directorate', 'IT Department Head', 'IT Support Group', 'IT Group Head', 'IT Support Division', 'IT Division Head', NULL, NULL, NULL, NULL, NULL, NULL, 'Yes', 'https://www.cimbniaga.co.id/', '7cb4e9a865ae6fa46c97e2942364b0d9.pdf', 2, '2026-03-27 09:28:02', 1, '2026-03-27 11:13:31', NULL, NULL, 0);
INSERT INTO `tbl_portofolio_apps_master` VALUES (85, 3, 2, 1, 3, 2, 2, 1, 2, 1, 'a', 'tes', 'asas', 'a', 'er', 'ttess', 99.00, '1000', NULL, '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, NULL, NULL, NULL, NULL, NULL, 'Yes', 'pokemon.com', 'SLA_tes_20260327_155606.pdf', 2, '2026-03-27 15:36:34', 1, '2026-03-27 15:56:05', NULL, NULL, 1);
INSERT INTO `tbl_portofolio_apps_master` VALUES (86, 3, 1, 2, 4, 2, 2, 1, 2, 2, 'a', 'tes', 'ada', 'sssa', 's', 's', 12.00, '1212', NULL, 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', NULL, NULL, NULL, NULL, NULL, NULL, 'Yes', 'pokemon.com', 'SLA_tes_20260330_133851.pdf', 2, '2026-03-27 16:22:36', 1, '2026-03-30 13:38:49', NULL, NULL, 1);
INSERT INTO `tbl_portofolio_apps_master` VALUES (87, 3, 2, 2, 3, 1, 22, 2, 2, 2, 'aaaaa', 'asaaaa', 'aaaa', 'as', 'as', 'a', 99.00, '1111', NULL, 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', NULL, NULL, NULL, NULL, NULL, NULL, 'Yes', 'apatu.com', 'SLA_asaaaa_20260331_160705.pdf', 2, '2026-03-30 11:58:02', 1, '2026-03-31 16:07:05', NULL, NULL, 1);
INSERT INTO `tbl_portofolio_apps_master` VALUES (88, 4, 1, 2, 1, 1, 24, 1, 2, 2, 'a', 'x', 'x', 'a', 'a', 'a', 100.00, '12121', NULL, 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', NULL, NULL, NULL, NULL, NULL, NULL, 'No', 'https://www.cimbniaga.co.id/', 'SLA_x_20260331_161017.pdf', 2, '2026-03-30 14:17:10', 1, '2026-03-31 16:10:16', NULL, NULL, 1);
INSERT INTO `tbl_portofolio_apps_master` VALUES (89, 4, 2, 2, 2, 3, 19, 1, 1, 1, 'aaa', 'as', 'sss', 'aa', 'a', 'a', 99.00, '1111', NULL, 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', NULL, NULL, NULL, NULL, NULL, NULL, 'Yes', 'pokemons.com', 'SLA_as_20260331_155136.pdf', 2, '2026-03-30 16:38:12', 1, '2026-03-31 15:51:36', NULL, NULL, 1);
INSERT INTO `tbl_portofolio_apps_master` VALUES (92, 2, 1, 2, 2, 3, 1, 1, 1, 2, 'tes', 'tes1000', 'tes1001', 'Tes upload SLA', 'OTAA', 'OTAA', 99.00, '2025', NULL, 'Directorate', 'Sub-Directorate', 'Department Head', 'Group', 'Group Head', 'IT Sub-Directorate', 'IT Department Head', 'IT Support Group', 'IT Group Head', 'IT Support Division', 'IT Division Head', NULL, NULL, 'OTAA', NULL, NULL, NULL, 'Yes', 'tes.com', 'SLA_tes1000_20260401_082439.pdf', 2, '2026-04-01 08:19:12', 1, '2026-04-01 08:24:36', NULL, NULL, 1);

-- ----------------------------
-- Table structure for tbl_product_provider_junc
-- ----------------------------
DROP TABLE IF EXISTS `tbl_product_provider_junc`;
CREATE TABLE `tbl_product_provider_junc`  (
  `produk_provider_id` int(11) NOT NULL AUTO_INCREMENT,
  `produk_id` int(11) NULL DEFAULT NULL,
  `provider_id` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`produk_provider_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 27 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_product_provider_junc
-- ----------------------------
INSERT INTO `tbl_product_provider_junc` VALUES (1, 1, 1);
INSERT INTO `tbl_product_provider_junc` VALUES (2, 2, 2);
INSERT INTO `tbl_product_provider_junc` VALUES (3, 2, 1);
INSERT INTO `tbl_product_provider_junc` VALUES (4, 3, 3);
INSERT INTO `tbl_product_provider_junc` VALUES (5, 3, 2);
INSERT INTO `tbl_product_provider_junc` VALUES (6, 3, 1);
INSERT INTO `tbl_product_provider_junc` VALUES (7, 4, 3);
INSERT INTO `tbl_product_provider_junc` VALUES (8, 4, 4);
INSERT INTO `tbl_product_provider_junc` VALUES (9, 4, 2);
INSERT INTO `tbl_product_provider_junc` VALUES (10, 4, 1);
INSERT INTO `tbl_product_provider_junc` VALUES (11, 4, 5);
INSERT INTO `tbl_product_provider_junc` VALUES (12, 5, 3);
INSERT INTO `tbl_product_provider_junc` VALUES (13, 5, 2);
INSERT INTO `tbl_product_provider_junc` VALUES (14, 5, 6);
INSERT INTO `tbl_product_provider_junc` VALUES (15, 5, 1);
INSERT INTO `tbl_product_provider_junc` VALUES (16, 5, 5);
INSERT INTO `tbl_product_provider_junc` VALUES (17, 6, 3);
INSERT INTO `tbl_product_provider_junc` VALUES (18, 6, 4);
INSERT INTO `tbl_product_provider_junc` VALUES (19, 6, 2);
INSERT INTO `tbl_product_provider_junc` VALUES (20, 6, 6);
INSERT INTO `tbl_product_provider_junc` VALUES (21, 6, 7);
INSERT INTO `tbl_product_provider_junc` VALUES (22, 6, 1);
INSERT INTO `tbl_product_provider_junc` VALUES (23, 6, 8);
INSERT INTO `tbl_product_provider_junc` VALUES (24, 7, 3);
INSERT INTO `tbl_product_provider_junc` VALUES (25, 7, 1);
INSERT INTO `tbl_product_provider_junc` VALUES (26, 7, 9);

-- ----------------------------
-- Table structure for tbl_resilience
-- ----------------------------
DROP TABLE IF EXISTS `tbl_resilience`;
CREATE TABLE `tbl_resilience`  (
  `resilience_id` int(11) NOT NULL AUTO_INCREMENT,
  `resilience_category` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `dr` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `ha` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`resilience_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_resilience
-- ----------------------------
INSERT INTO `tbl_resilience` VALUES (1, 'L0', 'No', '');
INSERT INTO `tbl_resilience` VALUES (2, 'L1', 'Yes', 'No');
INSERT INTO `tbl_resilience` VALUES (3, 'L2', 'Yes', 'Yes');
INSERT INTO `tbl_resilience` VALUES (4, 'L3', 'Yes', 'Yes');

-- ----------------------------
-- Table structure for tbl_role
-- ----------------------------
DROP TABLE IF EXISTS `tbl_role`;
CREATE TABLE `tbl_role`  (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`role_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_role
-- ----------------------------
INSERT INTO `tbl_role` VALUES (1, 'IT SLM');
INSERT INTO `tbl_role` VALUES (2, 'EA');
INSERT INTO `tbl_role` VALUES (3, 'IT Dev');

-- ----------------------------
-- Table structure for tbl_server
-- ----------------------------
DROP TABLE IF EXISTS `tbl_server`;
CREATE TABLE `tbl_server`  (
  `server_id` int(11) NOT NULL AUTO_INCREMENT,
  `server_name` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `server_sla` decimal(10, 2) NOT NULL,
  `created_by` int(11) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `modified_by` int(11) NULL DEFAULT NULL,
  `modified_at` datetime(0) NULL DEFAULT NULL,
  `status` tinyint(1) NULL DEFAULT 1,
  PRIMARY KEY (`server_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_server
-- ----------------------------
INSERT INTO `tbl_server` VALUES (1, 'Physical', 99.00, NULL, NULL, NULL, NULL, 1);
INSERT INTO `tbl_server` VALUES (2, 'VM', 99.42, NULL, NULL, NULL, NULL, 1);
INSERT INTO `tbl_server` VALUES (3, 'GCM', 99.50, NULL, NULL, NULL, NULL, 1);
INSERT INTO `tbl_server` VALUES (4, 'AWS', 99.50, NULL, NULL, NULL, NULL, 1);
INSERT INTO `tbl_server` VALUES (5, 'Azure', 99.50, NULL, NULL, NULL, NULL, 1);
INSERT INTO `tbl_server` VALUES (14, 'Tes Servers', 0.00, 1, '2026-04-01 08:55:32', 1, '2026-04-01 08:55:40', 0);

-- ----------------------------
-- Table structure for tbl_service
-- ----------------------------
DROP TABLE IF EXISTS `tbl_service`;
CREATE TABLE `tbl_service`  (
  `service_id` int(11) NOT NULL AUTO_INCREMENT,
  `service_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `status` tinyint(1) NULL DEFAULT 1,
  `created_by` int(11) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `modified_by` int(11) NULL DEFAULT NULL,
  `modified_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`service_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_service
-- ----------------------------
INSERT INTO `tbl_service` VALUES (1, 'Actimize', 1, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_service` VALUES (2, 'ALM', 1, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_service` VALUES (3, 'AML DB', 1, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_service` VALUES (4, 'AML APPS', 1, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_service` VALUES (5, 'GatotKaca', 1, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_service` VALUES (6, 'CREDIT-CORE', 1, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_service` VALUES (7, 'CREDIT-CORE-IM-ASP', 1, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_service` VALUES (8, 'ETP-CONV', 1, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_service` VALUES (9, 'ETP-SYARIA', 1, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_service` VALUES (10, 'Trade', 1, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_service` VALUES (11, 'BERPESTA', 1, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_service` VALUES (12, 'FAST-CONV', 1, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_service` VALUES (13, 'FAS-SYARIA', 1, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_service` VALUES (14, 'BIZ-MOBILE', 1, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_service` VALUES (15, 'BIZ-VM', 1, NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for tbl_user_role
-- ----------------------------
DROP TABLE IF EXISTS `tbl_user_role`;
CREATE TABLE `tbl_user_role`  (
  `user_role_id` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `created_by` int(11) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `modified_by` int(11) NULL DEFAULT NULL,
  `modified_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`user_role_id`) USING BTREE,
  INDEX `user_id`(`id`) USING BTREE,
  INDEX `role_id`(`role_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_user_role
-- ----------------------------
INSERT INTO `tbl_user_role` VALUES (1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_user_role` VALUES (2, 2, 2, NULL, NULL, NULL, NULL);
INSERT INTO `tbl_user_role` VALUES (3, 3, 3, NULL, NULL, 2, '2026-01-23 04:55:10');
INSERT INTO `tbl_user_role` VALUES (13, 12, 1, 1, '2026-04-01 09:04:37', 1, '2026-04-01 09:05:08');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` tinyint(1) NULL DEFAULT NULL,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  `updated_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0) ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `unique_email`(`email`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'role1', 'role1@gmail.com', '11111', 1, '2025-12-30 10:52:45', '2026-02-20 09:16:05');
INSERT INTO `users` VALUES (2, 'role2', 'role2@gmail.com', '11111', 1, '2025-12-30 16:11:58', '2026-02-20 09:16:06');
INSERT INTO `users` VALUES (3, 'role3', 'role3@gmail.com', '11111', 1, '2026-01-23 09:27:33', '2026-02-20 09:16:06');
INSERT INTO `users` VALUES (12, 'Tes Users', 'tes1@gmail.com', '$2y$10$7aby7zC6haTYVgNSSRX/UOaBwPMhIWjdR.VTsnQ5qDpilLGoKG3aO', 0, '2026-04-01 09:01:59', '2026-04-01 09:13:20');

SET FOREIGN_KEY_CHECKS = 1;
