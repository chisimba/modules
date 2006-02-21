<?php

$sqldata[]= "CREATE TABLE `tbl_calendar` (
  `id` varchar(32) NOT NULL default '',
  `multiday_event` char(1) NOT NULL default '0',
  `eventdate` date NOT NULL default '0000-00-00',
  `multiday_event_start_id` varchar(32) default NULL,
  `eventtitle` varchar(100) NOT NULL default '',
  `eventdetails` text,
  `eventurl` varchar(100) default NULL,
  `userorcontext` char(1) NOT NULL default '0',
  `context` varchar(32) default NULL,
  `workgroup` varchar(32) default NULL,
  `showusers` char(1) NOT NULL default '0',
  `userFirstEntry` varchar(32) NOT NULL default '',
  `userLastModified` varchar(32) default NULL,
  `dateFirstEntry` datetime NOT NULL default '0000-00-00 00:00:00',
  `dateLastModified` datetime default '0000-00-00 00:00:00',
  `updated` timestamp(14) NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=InnoDB;";

?>
