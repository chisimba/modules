<?
/**
*Table structure for table `tbl_glossary_images`
*
*@author Alastair Pursch
*
*@package glossary
* 
*/

/*
$sqldata[]="CREATE TABLE `tbl_glossary_images` ("
."  `id` VARCHAR(32) NOT NULL ,"
."  `item_id` VARCHAR(32) NOT NULL default '0',"
."  `image` varchar(32) NOT NULL default '',"
."  `caption` varchar(50) NOT NULL default '',"
."  `userId` varchar(32) NOT NULL default '',"
."  `dateLastUpdated` datetime NOT NULL,"
."  PRIMARY KEY  (`id`),"
."  INDEX `item_id` (`item_id`),"
."  FOREIGN KEY (`item_id`) REFERENCES `tbl_glossary` (`id`) ON DELETE CASCADE ON UPDATE CASCADE"
.") TYPE=InnoDB;";
*/

$tablename = 'tbl_glossary_images';
/*
Options line for comments, encoding and character set
*/
$options = array('comment' => 'Table for tbl_glossary_images', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32,
		'notnull' => 1
		),
	'item_id' => array(
		'type' => 'text',
		'length' => 32
		),
	'image' => array(
		'type' => 'text',
		'length' => 32
		),		
	'caption' => array(
		'type' => 'text',
		'length' => 50
		),
	'userid' => array(
		'type' => 'text',
		'length' => 32,
		'notnull' => 1		
		),	
	'datelastupdated' => array(
		'type' => 'timestamp',
		'notnull' => 1
		),
	);
?>
