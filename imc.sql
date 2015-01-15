-- phpMyAdmin SQL Dump
-- version 4.3.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 15, 2015 at 02:55 PM
-- Server version: 5.6.19-log
-- PHP Version: 5.6.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
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

CREATE TABLE `admin` (
  `id` bigint(20) unsigned NOT NULL,
  `student_id` varchar(9) COLLATE utf8_unicode_ci NOT NULL COMMENT 'รหัสนักศึกษา',
  `password` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `nickname` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `permission` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Binary value: 00000-11111, อยู่ในไฟล์ class.SesAdm.php'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='ข้อมูล admin (กรรมการ)';

-- --------------------------------------------------------

--
-- Table structure for table `indy_observer_info`
--

CREATE TABLE `indy_observer_info` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='ข้อมูลผู้สังเกตการณ์อิสระ';

-- --------------------------------------------------------

--
-- Table structure for table `observer_info`
--

CREATE TABLE `observer_info` (
  `id` bigint(20) unsigned NOT NULL,
  `team_id` bigint(20) unsigned NOT NULL COMMENT 'รหัสทีม (id จาก team_info)',
  `title` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'คำนำหน้าชื่อ',
  `firstname` text COLLATE utf8_unicode_ci,
  `middlename` text COLLATE utf8_unicode_ci,
  `lastname` text COLLATE utf8_unicode_ci,
  `gender` tinyint(1) DEFAULT NULL COMMENT '1 = male, 0 = female',
  `shirt_size` varchar(4) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'M',
  `email` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `soc_network` text COLLATE utf8_unicode_ci COMMENT 'Social Network',
  `med_req` text COLLATE utf8_unicode_ci COMMENT 'medical condition, allergy, medical requirement',
  `other_req` text COLLATE utf8_unicode_ci COMMENT 'other requirement: religion, vegeterian',
  `info_state` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT 'สถานะการกรอกข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='ข้อมูลผู้สังเกตการณ์ประจำแต่ละทีม';

-- --------------------------------------------------------

--
-- Table structure for table `participant_info`
--

CREATE TABLE `participant_info` (
  `id` bigint(20) unsigned NOT NULL,
  `team_id` bigint(20) unsigned NOT NULL COMMENT 'รหัสทีม (id จาก team_info)',
  `part_no` tinyint(3) unsigned NOT NULL COMMENT 'order of participant in team',
  `title` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'คำนำหน้าชื่อ',
  `firstname` text COLLATE utf8_unicode_ci,
  `middlename` text COLLATE utf8_unicode_ci,
  `lastname` text COLLATE utf8_unicode_ci,
  `gender` tinyint(1) DEFAULT NULL COMMENT '1 = male, 0 = female',
  `shirt_size` varchar(4) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'M',
  `email` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `soc_network` text COLLATE utf8_unicode_ci COMMENT 'Social Network',
  `med_req` text COLLATE utf8_unicode_ci COMMENT 'medical condition, allergy, medical requirement',
  `other_req` text COLLATE utf8_unicode_ci COMMENT 'other requirement: religion, vegeterian',
  `info_state` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT 'สถานะการกรอกข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='ข้อมูลผู้สมัครรายคน';

-- --------------------------------------------------------

--
-- Table structure for table `team_info`
--

CREATE TABLE `team_info` (
  `id` bigint(20) unsigned NOT NULL,
  `email` varchar(127) CHARACTER SET utf8 NOT NULL,
  `password` varchar(32) CHARACTER SET utf8 NOT NULL,
  `institution` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'ชื่อทีม',
  `country` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `team_state` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT 'สถานะข้อมูลทีม ตาม status มาตรฐาน',
  `pay_state` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT 'สถานะการจ่ายเงิน ตาม status มาตรฐาน',
  `ticket_state` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT 'รหัสทีมที่เรียงใหม่'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='ข้อมูลทีมผู้สมัคร';

-- --------------------------------------------------------

--
-- Table structure for table `team_message`
--

CREATE TABLE `team_message` (
  `id` bigint(20) unsigned NOT NULL,
  `team_id` bigint(20) unsigned NOT NULL COMMENT 'ID ของทีมที่รับข้อความ',
  `title` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `detail` text COLLATE utf8_unicode_ci,
  `admin_id` bigint(20) unsigned NOT NULL COMMENT 'คนประกาศ',
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
-- Indexes for table `indy_observer_info`
--
ALTER TABLE `indy_observer_info`
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `email` (`email`);

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
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `indy_observer_info`
--
ALTER TABLE `indy_observer_info`
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
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
