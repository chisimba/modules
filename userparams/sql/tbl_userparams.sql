<?php

/*
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

*/

// Table Name
$tablename = 'tbl_userparams';

//Options line for comments, encoding and character set
$options = array('comments' => 'Table to hold configurable user parameters', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32,
        'notnull' => TRUE
		),
    'userId' => array(
        'type' => 'text',
		'length' => 32,
        'notnull' => TRUE
        )
    'pname' => array(
		'type' => 'text',
        'length' => 32,
        'notnull' => TRUE,
        'default' => ''
		),
    'pvalue' => array(
		'type' => 'text',
        'length' => 32,
        'notnull' => TRUE,
        'default' => ''
		),
    'creatorId' => array(
		'type' => 'text',
        'length' => 25
		),
    'dateCreated' => array(
		'type' => 'datetime',
        'notnull' => TRUE,
        'default' => '0000-00-00 00:00:00'
		),
    'modifierId' => array(
		'type' => 'text',
        'length' => 25
		),
    'dateModified' => array(
		'type' => 'datetime'
		)
    );

//create other indexes here...

$name = 'userId';

$indexes = array(
                'fields' => array(
                	'userId' => array(),
                    'pname' => array()
                )
        );

?>