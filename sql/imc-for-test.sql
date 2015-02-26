-- phpMyAdmin SQL Dump
-- version 4.3.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 26, 2015 at 10:16 PM
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

DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` bigint(20) unsigned NOT NULL,
  `student_id` varchar(9) COLLATE utf8_unicode_ci NOT NULL COMMENT 'รหัสนักศึกษา',
  `password` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `nickname` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `permission` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Binary value: 00000-11111, อยู่ในไฟล์ class.SesAdm.php'
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='ข้อมูล admin (กรรมการ)';

--
-- Truncate table before insert `admin`
--

TRUNCATE TABLE `admin`;
--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `student_id`, `password`, `nickname`, `permission`) VALUES
(4, '111', '111111', '111', 63),
(5, '1111', 'asd_123;', 'one', 33),
(6, '123456789', '123456789', '0987654321', 56),
(8, '999999999', '999999999', '๙', 28);

-- --------------------------------------------------------

--
-- Table structure for table `observer_info`
--

DROP TABLE IF EXISTS `observer_info`;
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='ข้อมูลผู้สังเกตการณ์ประจำแต่ละทีม';

--
-- Truncate table before insert `observer_info`
--

TRUNCATE TABLE `observer_info`;
--
-- Dumping data for table `observer_info`
--

INSERT INTO `observer_info` (`id`, `team_id`, `title`, `firstname`, `middlename`, `lastname`, `gender`, `birth`, `nationality`, `phone`, `email`, `fb`, `tw`, `religion`, `cuisine`, `allergy`, `disease`, `other_req`, `shirt_size`, `info_state`) VALUES
(1, 1, 'Prof. Dr.', 'AA', '', 'BB', 1, '1969-01-06', 'TH', '+66456456456', '', 'AA BB', '@aabb', 'POI', 'asdf rewfrsdf', '', '', '', 'L', 6),
(2, 6, 'DF', 'dfssd', 'dfdfs', 'fdfdh', 1, '1948-12-26', 'jk', '', '', '', '', '', '', '', '', '', 'S', 1),
(3, 7, 'DF', 'dfssd', 'dfdfs', 'fdfdh', 1, '1948-12-26', 'jk', '', '', '', '', '', '', '', '', '', 'S', 1),
(5, 2, 'Assit.Prof. Dr.', 'AA', '', 'MO:DKFL', 1, '1978-12-31', 'TH', '+18114654', '', '', '', 'B', '', '', '', '', 'XL', 1);

-- --------------------------------------------------------

--
-- Table structure for table `participant_info`
--

DROP TABLE IF EXISTS `participant_info`;
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
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='ข้อมูลผู้สังเกตการณ์ประจำแต่ละทีม';

--
-- Truncate table before insert `participant_info`
--

TRUNCATE TABLE `participant_info`;
--
-- Dumping data for table `participant_info`
--

INSERT INTO `participant_info` (`id`, `team_id`, `part_no`, `title`, `firstname`, `middlename`, `lastname`, `gender`, `std_y`, `birth`, `nationality`, `phone`, `email`, `fb`, `tw`, `emerg_contact`, `religion`, `cuisine`, `allergy`, `disease`, `other_req`, `shirt_size`, `info_state`) VALUES
(1, 1, 1, 'Miss', 'C', 'D', 'E', 0, 10, '1998-12-27', 'UK', '+544645456', 'asd@asd.net', 'C D E', '@cbd', '+78981256', 'Uioe', '', 'adsfa', '', '', 'SS', 6),
(17, 1, 2, 'Miss', 'Ugf', '', 'HOJuoi', 0, 2, '1995-07-12', '', '+544615644545', 'asdf@asd.org', 'http://fb.com/asd', '', '+1232165', 'Opd', '', 'ADS', '', 'SdsD\r\ndsf\r\nswet\r\nwergab\r\nd', 'M', 6),
(24, 1, 3, 'Mr.', 'Ioidsf', 'D.', 'Fdsgwds', 1, 3, '1989-06-13', 'Po', '+0123456789', 'a@aa.ac.au', 'http://www.facebook.com/SOD.IJF', '', '+8948155640', 'Coif', '', '', 'OSFDnklj\r\nsak fd\r\n\r\nsdf \r\ndf\r\ndsa fert', '14', 'XXL', 6),
(25, 1, 4, 'Miss', 'Joae', '', 'Pawe', 0, 4, '1994-10-25', 'JDS', '', 'team@team.us', 'https://www.facebook.com/kojm', '@kojm', '+1232165', 'OIP', 'FNSD djdk  POSD', 'POIKJL', '', '', 'S', 6),
(26, 2, 1, 'Miss', '', '', '', 0, NULL, '1995-07-13', '', '', '', '', '', '', '', '', '', '', '', 'M', 1),
(27, 2, 4, '', '', '', '', 0, NULL, NULL, '', '', '', '', '', '', 'IUSDON', 'sdfasdf\r\nsd\r\n\r\nsd\r\nsd', '', '', '', 'M', 1);

-- --------------------------------------------------------

--
-- Table structure for table `team_info`
--

DROP TABLE IF EXISTS `team_info`;
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='ข้อมูลทีมผู้สมัคร';

--
-- Truncate table before insert `team_info`
--

TRUNCATE TABLE `team_info`;
--
-- Dumping data for table `team_info`
--

INSERT INTO `team_info` (`id`, `email`, `pw`, `team_name`, `institution`, `university`, `address`, `country`, `phone`, `arrive_by`, `arrive_time`, `depart_by`, `depart_time`, `route`, `team_state`, `pay_state`, `post_reg_state`) VALUES
(1, 'asdf@asdf.net', 'asdf_123;', 'asdfas ds', 'asdfdb', 'fdasda', '213 st mdfd d\r\netomcop\r\nooioiioiooofd\r\n\r\n5555555', 'Åland Islands', '+66869144572', 'car', '2015-11-26 03:30:00', 'car', '2015-11-30 09:20:00', 2, 6, 6, 7),
(2, 'team@team.us', '1', 'sdaadfsfsd', 'fdfdfd', 'lklklj', '', 'Thailand', '+6653752287', NULL, NULL, NULL, NULL, NULL, 1, 0, 0),
(4, 'a@aa.ac.au', 'aaaaaa', 'a', 'a', 'a', NULL, 'Antigua and Barbuda', NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0),
(5, 'fda@klsd.ac.jp', 'asdf_123;', 'dafser dv', 'Jpdsd i ndsa', 'JIO dsfk', NULL, 'Japan', NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0),
(6, 'jkl@lj.ac.jp', 'asdfasdf', 'iuoltklu', 'ut,oy,u', 'o,yuoifmt', '', 'Peru', '', NULL, NULL, NULL, NULL, NULL, 1, 0, 0),
(7, 'jklsdth@lj.ac.jp', 'asdfasdf', 'iuoltkludas', 'ut,oy,u', 'o,yuoifmt', '', 'Peru', '', NULL, NULL, NULL, NULL, NULL, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `team_message`
--

DROP TABLE IF EXISTS `team_message`;
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
-- Truncate table before insert `team_message`
--

TRUNCATE TABLE `team_message`;
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
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `observer_info`
--
ALTER TABLE `observer_info`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `participant_info`
--
ALTER TABLE `participant_info`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT for table `team_info`
--
ALTER TABLE `team_info`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `team_message`
--
ALTER TABLE `team_message`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
