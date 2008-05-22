<?php
// Table Name
$tablename = 'tbl_unihr_faculty';

//Options line for comments, encoding and character set
$options = array('comment' => 'Faculty names and ID', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
		),
	'faculty_name' => array(
		'type' => 'text',
		'length' => 255,
		),
	'faculty_desc' => array(
		'type' => 'clob',
		),
	);

//create other indexes here...
?>