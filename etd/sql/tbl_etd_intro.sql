<?php
//5ive definition
$tablename = 'tbl_etd_intro';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table containing the introduction to the module', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
		),
	'introduction' => array(
		'type' => 'clob'
		),
	'language' => array(
		'type' => 'text',
		'length' => 5
		),
	'creatorid' => array(
		'type' => 'text',
		'length' => 32
		),
	'modifierid' => array(
		'type' => 'text',
		'length' => 32
		),
	'datecreated' => array(
		'type' => 'timestamp'
		),
	'updated' => array(
		'type' => 'timestamp'
		),
	);

// create other indexes here...

?>