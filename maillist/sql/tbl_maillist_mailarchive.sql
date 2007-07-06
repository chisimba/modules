<?php
$sqldata[] = "CREATE TABLE `tbl_maillist_mailarchive` (
  `id` varchar(32) NOT NULL default '',
  `body` longtext NOT NULL,
  `subject` varchar(255) NOT NULL default '',
  `sender` varchar(255) NOT NULL default '',
  `userId` varchar(50) default NULL,
  `fileid` varchar(255) default NULL,
  `updated` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mail archive';";
