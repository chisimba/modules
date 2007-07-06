<?php

$sqldata[]="CREATE TABLE `tbl_maillist_subscribers` (
  `id` varchar(32) NOT NULL default '',
  `userId` varchar(50) NOT NULL default '',
  `modifiedBy` varchar(50) NOT NULL default '',
  `dateCreated` timestamp NOT NULL default '0000-00-00 00:00:00',
  `dateUpdated` varchar(50) default NULL,
  `list` varchar(255) NOT NULL default '',
  `updated` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `userId` (`userId`)
) ENGINE=InnoDB COMMENT='Mailing list subscriber information';
";
?>