-- phpMyAdmin SQL Dump
-- version 4.3.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 31, 2014 at 08:00 PM
-- Server version: 5.6.19-log
-- PHP Version: 5.6.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `imc`
--
CREATE DATABASE IF NOT EXISTS `imc` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `imc`;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` bigint(20) unsigned NOT NULL,
  `student_id` varchar(9) COLLATE utf8_unicode_ci NOT NULL COMMENT 'รหัสนักศึกษา',
  `password` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `nickname` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `permission` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Binary value: 00000-11111, ให้ sorted_id, ตรวจหลักฐานโอนเงิน, ตรวจ quiz, แก้ไขผู้สมัคร, แก้ไข admin'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='ข้อมูล admin';

-- --------------------------------------------------------

--
-- Table structure for table `coach_info`
--

DROP TABLE IF EXISTS `coach_info`;
CREATE TABLE `coach_info` (
  `id` bigint(20) unsigned NOT NULL,
  `team_id` bigint(20) unsigned NOT NULL COMMENT 'รหัสทีม (id จาก team_info)',
  `title` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'คำนำหน้าชื่อ',
  `firstname` text COLLATE utf8_unicode_ci NOT NULL,
  `lastname` text COLLATE utf8_unicode_ci NOT NULL,
  `gender` tinyint(1) NOT NULL COMMENT '1 = male, 0 = female',
  `phone` varchar(10) CHARACTER SET utf8 NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 NOT NULL,
  `school` text COLLATE utf8_unicode_ci NOT NULL,
  `sci_grade` decimal(3,2) NOT NULL COMMENT 'เกรดวิทย์',
  `is_pass` tinyint(4) NOT NULL DEFAULT '2' COMMENT 'สถานะการกรอกข้อมูล',
  `is_upload` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'สถานะของปพ.1',
  `sorted_id` varchar(9) CHARACTER SET utf8 DEFAULT NULL COMMENT 'รหัสผู้แข่งขัน',
  `exam_room` text COLLATE utf8_unicode_ci COMMENT 'ห้องสอบ ที่นั่งสอบ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='ข้อมูลผู้สมัครรายคน';

-- --------------------------------------------------------

--
-- Table structure for table `new_account`
--

DROP TABLE IF EXISTS `new_account`;
CREATE TABLE `new_account` (
  `id` bigint(20) unsigned NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 NOT NULL,
  `password` varchar(32) CHARACTER SET utf8 NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `confirm_code` varchar(32) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='account ใหม่ รอ confirm email';

-- --------------------------------------------------------

--
-- Table structure for table `observer_info`
--

DROP TABLE IF EXISTS `observer_info`;
CREATE TABLE `observer_info` (
  `id` bigint(20) unsigned NOT NULL,
  `team_id` bigint(20) unsigned NOT NULL COMMENT 'รหัสทีม (id จาก team_info)',
  `title` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'คำนำหน้าชื่อ',
  `firstname` text COLLATE utf8_unicode_ci NOT NULL,
  `lastname` text COLLATE utf8_unicode_ci NOT NULL,
  `gender` tinyint(1) NOT NULL COMMENT '1 = male, 0 = female',
  `phone` varchar(10) CHARACTER SET utf8 NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 NOT NULL,
  `school` text COLLATE utf8_unicode_ci NOT NULL,
  `sci_grade` decimal(3,2) NOT NULL COMMENT 'เกรดวิทย์',
  `is_pass` tinyint(4) NOT NULL DEFAULT '2' COMMENT 'สถานะการกรอกข้อมูล',
  `is_upload` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'สถานะของปพ.1',
  `sorted_id` varchar(9) CHARACTER SET utf8 DEFAULT NULL COMMENT 'รหัสผู้แข่งขัน',
  `exam_room` text COLLATE utf8_unicode_ci COMMENT 'ห้องสอบ ที่นั่งสอบ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='ข้อมูลผู้สมัครรายคน';

-- --------------------------------------------------------

--
-- Table structure for table `participant_info`
--

DROP TABLE IF EXISTS `participant_info`;
CREATE TABLE `participant_info` (
  `id` bigint(20) unsigned NOT NULL,
  `team_id` bigint(20) unsigned NOT NULL COMMENT 'รหัสทีม (id จาก team_info)',
  `title` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'คำนำหน้าชื่อ',
  `firstname` text COLLATE utf8_unicode_ci NOT NULL,
  `lastname` text COLLATE utf8_unicode_ci NOT NULL,
  `gender` tinyint(1) NOT NULL COMMENT '1 = male, 0 = female',
  `phone` varchar(10) CHARACTER SET utf8 NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 NOT NULL,
  `school` text COLLATE utf8_unicode_ci NOT NULL,
  `sci_grade` decimal(3,2) NOT NULL COMMENT 'เกรดวิทย์',
  `is_pass` tinyint(4) NOT NULL DEFAULT '2' COMMENT 'สถานะการกรอกข้อมูล',
  `is_upload` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'สถานะของปพ.1',
  `sorted_id` varchar(9) CHARACTER SET utf8 DEFAULT NULL COMMENT 'รหัสผู้แข่งขัน',
  `exam_room` text COLLATE utf8_unicode_ci COMMENT 'ห้องสอบ ที่นั่งสอบ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='ข้อมูลผู้สมัครรายคน';

-- --------------------------------------------------------

--
-- Table structure for table `team_info`
--

DROP TABLE IF EXISTS `team_info`;
CREATE TABLE `team_info` (
  `id` bigint(20) unsigned NOT NULL,
  `email` varchar(127) CHARACTER SET utf8 NOT NULL,
  `password` varchar(32) CHARACTER SET utf8 NOT NULL,
  `team_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'ชื่อทีม',
  `t_firstname` text COLLATE utf8_unicode_ci COMMENT 'ชื่อครูที่ปรึกษา',
  `t_lastname` text COLLATE utf8_unicode_ci COMMENT 'นามสกุลครูที่ปรึกษา',
  `t_phone` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'เบอร์โทรครูที่ปรึกษา',
  `is_pass` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'สถานะข้อมูลทีม ตาม status มาตรฐาน',
  `is_pay` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'สถานะการจ่ายเงิน ตาม status มาตรฐาน',
  `sorted_id` varchar(9) CHARACTER SET utf8 DEFAULT NULL COMMENT 'รหัสทีมที่เรียงใหม่'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='ข้อมูลผู้สมัครรายทีม';

-- --------------------------------------------------------

--
-- Table structure for table `team_message`
--

DROP TABLE IF EXISTS `team_message`;
CREATE TABLE `team_message` (
  `id` bigint(20) unsigned NOT NULL,
  `team_id` bigint(20) unsigned NOT NULL,
  `title` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `detail` text COLLATE utf8_unicode_ci,
  `sender_id` bigint(20) unsigned NOT NULL COMMENT 'คนประกาศ',
  `show_page` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'แสดงในหน้าไหนบ้าง',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='ข้อความที่ส่งให้แต่ละทีม';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coach_info`
--
ALTER TABLE `coach_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `new_account`
--
ALTER TABLE `new_account`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `observer_info`
--
ALTER TABLE `observer_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `participant_info`
--
ALTER TABLE `participant_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `team_info`
--
ALTER TABLE `team_info`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `email` (`email`,`team_name`), ADD UNIQUE KEY `team_name` (`team_name`);

--
-- Indexes for table `team_message`
--
ALTER TABLE `team_message`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `coach_info`
--
ALTER TABLE `coach_info`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `new_account`
--
ALTER TABLE `new_account`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `observer_info`
--
ALTER TABLE `observer_info`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `participant_info`
--
ALTER TABLE `participant_info`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `team_info`
--
ALTER TABLE `team_info`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `team_message`
--
ALTER TABLE `team_message`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
