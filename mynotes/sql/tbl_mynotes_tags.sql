-- phpMyAdmin SQL Dump
-- version 3.4.5deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 27, 2012 at 10:32 AM
-- Server version: 5.1.62
-- PHP Version: 5.3.6-13ubuntu3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `chisimba`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_mynotes_tags`
--

CREATE TABLE IF NOT EXISTS `tbl_mynotes_tags` (
  `id` varchar(32) DEFAULT NULL,
  `name` varchar(32) DEFAULT NULL,
  `count` varchar(16) DEFAULT NULL,
  `userid` varchar(32) DEFAULT NULL,
  `modifiedby` varchar(32) DEFAULT NULL,
  `datecreated` datetime DEFAULT NULL,
  `datemodified` datetime DEFAULT NULL,
  `puid` bigint(20) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`puid`),
  KEY `pk90927_idx` (`id`),
  KEY `ame860_idx` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Storage of tags for the mynotes module' AUTO_INCREMENT=26 ;

--
-- Dumping data for table `tbl_mynotes_tags`
--

INSERT INTO `tbl_mynotes_tags` (`id`, `name`, `count`, `userid`, `modifiedby`, `datecreated`, `datemodified`, `puid`) VALUES
('gen17Srv7Nme3_22850_1335119888', 'note', '3', '1', '1', '2012-04-25 05:14:56', '2012-04-25 05:14:56', 1),
('gen17Srv7Nme3_29743_1335119888', ' first', '3', '1', '1', '2012-04-25 05:14:56', '2012-04-25 05:14:56', 2),
('gen17Srv7Nme3_99697_1335119888', ' tag', '1', '1', NULL, '2012-04-22 20:38:08', NULL, 3),
('gen17Srv7Nme3_92348_1335119888', ' cloud', '3', '1', '1', '2012-04-25 05:14:56', '2012-04-25 05:14:56', 4),
('gen17Srv7Nme3_17464_1335119888', ' code', '4', '1', '1', '2012-04-25 07:11:42', '2012-04-25 07:11:42', 5),
('gen17Srv7Nme3_96414_1335119925', ' helloworld', '3', '1', '1', '2012-04-25 05:14:56', '2012-04-25 05:14:56', 6),
('gen17Srv7Nme3_49489_1335119991', 'php', '4', '1', '1', '2012-04-27 07:23:44', '2012-04-27 07:23:44', 7),
('gen17Srv7Nme3_81657_1335119992', ' css', '3', '1', '1', '2012-04-25 07:11:42', '2012-04-25 07:11:42', 8),
('gen17Srv7Nme3_97537_1335119992', ' sql', '4', '1', '1', '2012-04-25 07:11:42', '2012-04-25 07:11:42', 9),
('gen17Srv7Nme3_58940_1335323696', ' debug', '3', '1', '1', '2012-04-25 05:14:56', '2012-04-25 05:14:56', 10),
('gen17Srv7Nme3_75811_1335504224', ' believe', '4', '1', '1', '2012-04-27 07:23:44', '2012-04-27 07:23:44', 11),
('gen17Srv7Nme3_82915_1335504224', ' april', '4', '1', '1', '2012-04-27 07:23:44', '2012-04-27 07:23:44', 12),
('gen17Srv7Nme3_4571_1335504224', ' atlas', '4', '1', '1', '2012-04-27 07:23:44', '2012-04-27 07:23:44', 13),
('gen17Srv7Nme3_90817_1335504224', ' marketing', '4', '1', '1', '2012-04-27 07:23:44', '2012-04-27 07:23:44', 14),
('gen17Srv7Nme3_48431_1335504290', 'opera', '1', '1', NULL, '2012-04-27 07:24:50', NULL, 15),
('gen17Srv7Nme3_63427_1335504290', ' love', '1', '1', NULL, '2012-04-27 07:24:50', NULL, 16),
('gen17Srv7Nme3_58612_1335504290', ' affair', '1', '1', NULL, '2012-04-27 07:24:50', NULL, 17),
('gen17Srv7Nme3_72922_1335504290', ' challenges', '1', '1', NULL, '2012-04-27 07:24:50', NULL, 18),
('gen17Srv7Nme3_87897_1335504290', ' passionate', '1', '1', NULL, '2012-04-27 07:24:50', NULL, 19),
('gen17Srv7Nme3_81307_1335513513', 'daily', '1', '1', NULL, '2012-04-27 09:58:33', NULL, 20),
('gen17Srv7Nme3_98007_1335513513', ' dime', '1', '1', NULL, '2012-04-27 09:58:33', NULL, 21),
('gen17Srv7Nme3_24576_1335513513', ' freedom', '1', '1', NULL, '2012-04-27 09:58:33', NULL, 22),
('gen17Srv7Nme3_80196_1335513513', ' day', '1', '1', NULL, '2012-04-27 09:58:33', NULL, 23),
('gen17Srv7Nme3_70145_1335513513', ' nba', '1', '1', NULL, '2012-04-27 09:58:33', NULL, 24),
('gen17Srv7Nme3_62768_1335513513', ' kobe', '1', '1', NULL, '2012-04-27 09:58:33', NULL, 25);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
