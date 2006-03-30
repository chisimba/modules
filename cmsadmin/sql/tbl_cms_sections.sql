<?php
$sqldata[] = 'CREATE TABLE `tbl_cms_sections` (
  `id` varchar(32) collate latin1_general_ci NOT NULL,
  `title` varchar(50) collate latin1_general_ci default NULL,
  `menutext` varchar(255) collate latin1_general_ci default NULL,
  `image` varchar(100) collate latin1_general_ci default NULL,
  `image_position` varchar(10) collate latin1_general_ci default NULL,
  `description` text collate latin1_general_ci,
  `published` tinyint(1) default '0',
  `checked_out` int(11) unsigned default '0',
  `checked_out_time` datetime default '0000-00-00 00:00:00',
  `ordering` int(11) default '0',
  `access` tinyint(3) unsigned default '0',
  `count` int(11) default '0',
  `params` text collate latin1_general_ci,
  `layout` varchar(32) collate latin1_general_ci default NULL,
  PRIMARY KEY  (`id`)
) ';
?>