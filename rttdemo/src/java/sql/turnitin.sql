-- phpMyAdmin SQL Dump
-- version 3.3.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 04, 2011 at 03:14 PM
-- Server version: 5.1.54
-- PHP Version: 5.3.5-1ubuntu7.2

--
-- This is the latest schema that should work with new install of rttdemo -- Nguni --
--
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `rttdemo`
--

-- --------------------------------------------------------

--
-- Table structure for table `authorities`
--

CREATE TABLE IF NOT EXISTS `authorities` (
  `username` varchar(50) NOT NULL,
  `authority` varchar(50) NOT NULL,
  KEY `fk_authorities_users` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `authorities`
--

INSERT INTO `authorities` (`username`, `authority`) VALUES
('admin', 'ROLE_USER'),
('admin', 'ROLE_USER'),
('1111111A', 'ROLE_USER'),
('0006629y', 'ROLE_USER'),
('a0023310', 'ROLE_USER'),
('lecturer', 'ROLE_USER'),
('guest', 'ROLE_USER'),
('a0017615', 'ROLE_USER');

-- --------------------------------------------------------

--
-- Table structure for table `rttdemo_assignments`
--

CREATE TABLE IF NOT EXISTS `rttdemo_assignments` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `coursecode` varchar(28) DEFAULT NULL,
  `submissionid` varchar(28) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `instructions` varchar(512) DEFAULT NULL,
  `instructoremail` varchar(255) DEFAULT NULL,
  `startdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `duedate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=213 ;

--
-- Dumping data for table `rttdemo_assignments`
--


-- --------------------------------------------------------

--
-- Table structure for table `rttdemo_autoenrolled_users`
--

CREATE TABLE IF NOT EXISTS `rttdemo_autoenrolled_users` (
  `username` varchar(50) DEFAULT NULL,
  `coursecode` varchar(28) DEFAULT NULL,
  `auto_enroll` tinyint(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rttdemo_autoenrolled_users`
--


-- --------------------------------------------------------

--
-- Table structure for table `rttdemo_classes`
--

CREATE TABLE IF NOT EXISTS `rttdemo_classes` (
  `emailaddress` varchar(255) DEFAULT NULL,
  `coursecode` varchar(28) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `startdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `duedate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `auto_enroll` tinyint(1) DEFAULT NULL,
  `deleted` char(3) NOT NULL DEFAULT 'no'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rttdemo_classes`
--


-- --------------------------------------------------------

--
-- Table structure for table `rttdemo_class_instructors`
--

CREATE TABLE IF NOT EXISTS `rttdemo_class_instructors` (
  `coursecode` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rttdemo_class_instructors`
--


-- --------------------------------------------------------

--
-- Table structure for table `rttdemo_uploaditems`
--

CREATE TABLE IF NOT EXISTS `rttdemo_uploaditems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` char(50) NOT NULL,
  `date_uploaded` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `filepath` varchar(100) NOT NULL,
  `usertype` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `rttdemo_uploaditems`
--


-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `emailaddress` varchar(255) DEFAULT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `instructor` tinyint(1) DEFAULT NULL,
  `deleted` char(3) NOT NULL DEFAULT 'no',
  PRIMARY KEY (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`username`, `password`, `enabled`, `emailaddress`, `firstname`, `lastname`, `instructor`, `deleted`) VALUES
('admin', 'd41d8cd98f00b204e9800998ecf8427e', 1, '', '', '', 1, 'no'),
('0006629y', 'f5bf5642e5b1d0b6e3bcb8eba79b2f24', 1, 'Palesa.Mokwena@students.wits.ac.za', 'Palesa', 'Mokwena', 0, 'no'),
('a0023310', 'fc46b9adc905f4e6aa4fb38a7da3ca47', 1, 'Paul.Mungai@wits.ac.za', 'Paul', 'Mungai', 1, 'no'),
('guest', '65d15fe9156f9c4bbffd98085992a44e', 1, 'guest@localhost.com', 'Guest', 'User', 0, 'no'),
('lecturer', 'a564de63c2d0da68cf47586ee05984d7', 1, 'lecturer.demo@wits.ac.za', 'Lecturer', 'Demo', 1, 'no'),
('a0017615', 'c4f0d4efd81a830b9bc95a38e33d8197', 1, 'David.Wafula@wits.ac.za', 'David Wafula', 'Wanyonyi', 1, 'no'),
('1111111A', '78ef53e38c997c445f2fe1cc63c13139', 1, 'franz.meier@wits.ac.za', 'ADT_FirstName', 'ADT_Surname', 0, 'no');
