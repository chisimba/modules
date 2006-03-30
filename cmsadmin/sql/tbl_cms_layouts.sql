<?php
$sqldata[]='CREATE TABLE `tbl_cms_layouts` (
  `id` varchar(32) collate latin1_general_ci NOT NULL,
  `name` varchar(32) collate latin1_general_ci default NULL,
  `imagename` varchar(32) collate latin1_general_ci default NULL,
  `desciption` varchar(255) collate latin1_general_ci default NULL,
  PRIMARY KEY  (`id`),
  KEY `name` (`name`)
)';

$sqldata[] ='INSERT INTO `tbl_cms_layouts` VALUES ('1', 'previous', 'section_previous.gif', 'previous layout');';
$sqldata[] ='INSERT INTO `tbl_cms_layouts` VALUES ('2', 'page', 'section_page.gif', 'Page Layout');';
$sqldata[] ='INSERT INTO `tbl_cms_layouts` VALUES ('3', 'summaries', 'section_summaries.gif', 'Summaries Layout');';
$sqldata[] ='INSERT INTO `tbl_cms_layouts` VALUES ('4', 'list', 'section_list.gif', 'List Layout');';
?>