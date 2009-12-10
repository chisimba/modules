-- phpMyAdmin SQL Dump
-- version 2.11.3deb1ubuntu1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 09, 2009 at 10:11 PM
-- Server version: 5.0.51
-- PHP Version: 5.2.4-2ubuntu5.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `openaris2`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_ahis_vacinventory`
--

CREATE TABLE IF NOT EXISTS `tbl_ahis_animal_population_census` (
  `id` int(25) NOT NULL auto_increment,
  `dataoff` varchar(45) NOT NULL,
  `vetoff` varchar(45) default NULL,
  `repdate` date default NULL,
  `repoff` varchar(45) default NULL,
  `ibardate` date default NULL,
  `demail` varchar(100) default NULL,
  `dfax` varchar(45) default NULL,
  `dphone` varchar(45) default NULL,
  `vphone` varchar(45) default NULL,
  `vfax` varchar(45) default NULL,
  `vemail` varchar(100) default NULL,
  `country` varchar(45) default NULL,
  `month` varchar(45) default NULL,
  `year` varchar(45) default NULL,
  `parttype` varchar(45) default NULL,
  `partlevel` varchar(45) default NULL,
  `partname` varchar(45) default NULL,
  `loctype` varchar(45) default NULL,
  `locname` varchar(45) default NULL,
  `species` varchar(45) default NULL,
  `prodname` varchar(45) default NULL,
  `breed`    varchar(45)default NULL,
  `troplivestock` varchar(45)default NULL,
  `prodnum` varchar(45)default NULL,
  `breedno` varchar(45)default NULL,
  `crossbreednum` varchar(45)default NULL,
  `animalcat` varchar(45)default NULL,
  `totnum` varchar(45) default NULL,
  `catnum` varchar(45)default NULL,
  `comments` varchar(200) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;
