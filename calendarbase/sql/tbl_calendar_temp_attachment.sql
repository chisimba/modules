<?
$sqldata[]="CREATE TABLE `tbl_calendar_temp_attachment` (
  `id` varchar(32) NOT NULL default '',
  `temp_id` varchar(64) NOT NULL default '',
  `attachment_id` varchar(32) NOT NULL default '',
  `userId` varchar(32) NOT NULL default '',
  `dateLastUpdated` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `attachment_id` (`attachment_id`, `userId`,`temp_id`)
) TYPE=InnoDB   COMMENT='This table stores temporary uploads while a post is being created';";
?>