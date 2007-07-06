<?php
$sqldata[]="CREATE TABLE `tbl_maillist_mail` (
  `id` varchar(32) NOT NULL default '',
  `body` longtext NOT NULL,
  `subject` varchar(255) NOT NULL default '',
  `sender` varchar(255) NOT NULL default '',
  `userId` varchar(50) default NULL,
  `fileid` varchar(255) default NULL,
  `updated` timestamp NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=InnoDB COMMENT='mail table';
";
?>
