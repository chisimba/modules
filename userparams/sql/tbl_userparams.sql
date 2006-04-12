<?php
$sqldata[]="CREATE TABLE `tbl_userparams` (
  `id` varchar(32) NOT NULL default '',
  `userId` varchar(25) NOT NULL default '',
  `pname` varchar(32) NOT NULL default '',
  `pvalue` varchar(32) NOT NULL default '',
  `creatorId` varchar(25) default NULL,
  `dateCreated` datetime NOT NULL default '0000-00-00 00:00:00',
  `modifierId` varchar(25) default NULL,
  `dateModified` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `userId` (`userId`),
  KEY `pname` (`pname`),
  CONSTRAINT `tbl_userparams_ibfk_3` FOREIGN KEY (`pname`) REFERENCES `tbl_userparamsadmin` (`pname`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_userparams_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `tbl_users` (`userId`) 
    ON DELETE CASCADE ON UPDATE CASCADE, 
  CONSTRAINT `tbl_userparams_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `tbl_users` (`userId`) 
    ON DELETE CASCADE ON UPDATE CASCADE
) TYPE=InnoDB ROW_FORMAT=DYNAMIC  COMMENT='Table to hold configurable user parameters'";
?>