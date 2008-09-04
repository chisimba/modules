<?php
// Table Name
$tablename = 'tbl_im_presence';

//Options line for comments, encoding and character set
$options = array('comment' => 'table to hold IM presence information', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
		),
	'person' => array(
		'type' => 'text',
		'length' => 255,
		),
	'status' => array(
		'type' => 'text',
		'length' => 255,
		),
	'presshow' => array(
		'type' => 'text',
		'length' => 255,
		),
	'datesent' => array(
		'type' => 'timestamp',
		),
	);

?>