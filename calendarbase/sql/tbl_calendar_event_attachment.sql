<?
$sqldata[]="CREATE TABLE `tbl_calendar_event_attachment` (
  `id` varchar(32) NOT NULL default '',
  `event_id` varchar(32) NOT NULL default '',
  `attachment_id` varchar(32) NOT NULL default '',
  `userId` varchar(25) NOT NULL default '',
  `dateLastUpdated` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `attachment_id` (`event_id`, `attachment_id`, `userId`)
) TYPE=InnoDB   COMMENT='This table stores temporary uploads while a post is being created';";
?>