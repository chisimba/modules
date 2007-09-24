<?php
/*$sqldata[]="
CREATE TABLE `tbl_buddies` (
`id` varchar(32) NOT NULL,
`userId` varchar(25) default NULL,
`contactId` varchar(25) default NULL,
`isContact` char(1) default NULL,
`isFriend` char(1) default NULL,
`updated` timestamp(14),
`created_by` varchar(32),
`created_by_alias` varchar(100),
`modified` timestamp,
`modified_by` integer(11),
PRIMARY KEY  (`id`),
KEY `userId` (`userId`),
KEY `contactId` (`contactId`),
CONSTRAINT `tbl_phonebook_ibfk_2` FOREIGN KEY (`contactId`) REFERENCES `tbl_users` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `tbl_phonebook_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `tbl_users` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE
)
TYPE=InnoDB ROW_FORMAT=DYNAMIC
";*/

//5ive definition
$tablename = 'tbl_phonebook;

//Options line for comments, encoding and character set
$options = array('comment' => 'Used to store your contact list', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
	),
	'userId' => array(
		'type' => 'text',
		'length' => 25
	),
	'contactId' => array(
		'type' => 'text',
		'length' => 25
	),
	'isContact' => array(
		'type' => 'text',
		'length' => 1
	),
	'isFriend' => array(
		'type' => 'text',
		'length' => 1
	),	
	'updated' => array(
	'type' => 'timestamp'
	),
	'created_by' => array(
		'type' => 'text',
      'length' => 32,
	),
    'created_by_alias' => array(
		'type' => 'text',
   	'length' => 100
	),
    'modified' => array(
		'type' => 'timestamp',
	),
	 'modified_by' => array(
		'type' => 'integer',
      'length' => 11,
      'unsigned' => TRUE,
   ),   
);
?>
