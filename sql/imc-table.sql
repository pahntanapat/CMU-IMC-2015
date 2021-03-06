-- phpMyAdmin SQL Dump
-- version 4.4.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 29, 2015 at 01:55 PM
-- Server version: 5.6.19-log
-- PHP Version: 5.6.6

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
  `birth` date DEFAULT NULL COMMENT 'date of birth',
  `nationality` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'สัญชาติในปัจจุบัน',
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'mobile phone No.',
  `email` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `fb` text COLLATE utf8_unicode_ci COMMENT 'Facebook Name, Facebook Profile''s URL',
  `tw` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'twitter name',
  `religion` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'ศาสนา',
  `cuisine` text COLLATE utf8_unicode_ci COMMENT 'อาหาร เช่น Halal, Vegetarian',
  `allergy` text COLLATE utf8_unicode_ci COMMENT 'allergy',
  `disease` text COLLATE utf8_unicode_ci COMMENT 'underlying disease + other medical requirement',
  `other_req` text COLLATE utf8_unicode_ci COMMENT 'other requirement: religion, vegeterian',
  `shirt_size` varchar(4) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'M',
  `info_state` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT 'สถานะการกรอกข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='ข้อมูลผู้สังเกตการณ์ประจำแต่ละทีม';

-- --------------------------------------------------------

--
-- Table structure for table `participant_info`
--

CREATE TABLE `participant_info` (
  `id` bigint(20) unsigned NOT NULL,
  `team_id` bigint(20) unsigned NOT NULL COMMENT 'รหัสทีม (id จาก team_info)',
  `part_no` tinyint(3) unsigned NOT NULL COMMENT 'ผู้แข่งขันลำดับที่',
  `title` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'คำนำหน้าชื่อ',
  `firstname` text COLLATE utf8_unicode_ci,
  `middlename` text COLLATE utf8_unicode_ci,
  `lastname` text COLLATE utf8_unicode_ci,
  `gender` tinyint(1) DEFAULT NULL COMMENT '1 = male, 0 = female',
  `std_y` tinyint(3) unsigned DEFAULT NULL COMMENT 'medical student year (ชั้นปี)',
  `birth` date DEFAULT NULL COMMENT 'date of birth',
  `nationality` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'สัญชาติในปัจจุบัน',
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'mobile phone No.',
  `email` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `fb` text COLLATE utf8_unicode_ci COMMENT 'Facebook Name, Facebook Profile''s URL',
  `tw` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'twitter name',
  `emerg_contact` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'emergency contact (เบอร์โทรผู้ปกครอง กรณีฉุกเฉิน)',
  `religion` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'ศาสนา',
  `cuisine` text COLLATE utf8_unicode_ci COMMENT 'อาหาร เช่น Halal, Vegetarian',
  `allergy` text COLLATE utf8_unicode_ci COMMENT 'allergy',
  `disease` text COLLATE utf8_unicode_ci COMMENT 'underlying disease + other medical requirement',
  `other_req` text COLLATE utf8_unicode_ci COMMENT 'other requirement: religion, vegeterian',
  `shirt_size` varchar(4) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'M',
  `info_state` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT 'สถานะการกรอกข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='ข้อมูลผู้แข่งขันประจำแต่ละทีม';

-- --------------------------------------------------------

--
-- Table structure for table `team_info`
--

CREATE TABLE `team_info` (
  `id` bigint(20) unsigned NOT NULL,
  `email` varchar(127) CHARACTER SET utf8 NOT NULL,
  `pw` varchar(32) CHARACTER SET utf8 NOT NULL COMMENT 'password',
  `team_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'team''s name',
  `institution` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Medical school''s name',
  `university` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'University''s name',
  `address` text COLLATE utf8_unicode_ci COMMENT 'Medical school''s address',
  `country` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Medical school''s country',
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Medical school''s phone number',
  `arrive_by` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'การเดินทางมา',
  `arrive_time` datetime DEFAULT NULL COMMENT 'เวลามาถึง',
  `depart_by` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'การเดินทางกลับ',
  `depart_time` datetime DEFAULT NULL COMMENT 'เวลากลับ',
  `route` tinyint(3) unsigned DEFAULT NULL COMMENT 'Routes of Chiang Mai Tour',
  `team_state` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT 'สถานะข้อมูลทีม ตาม status มาตรฐาน',
  `pay_state` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT 'สถานะการจ่ายเงิน ตาม status มาตรฐาน',
  `post_reg_state` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT 'สถานะข้อมูล update หลังจ่ายเงิน (หลังปิดรับสมัคร)'
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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

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
