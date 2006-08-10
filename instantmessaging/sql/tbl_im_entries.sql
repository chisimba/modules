<?php

//5ive definition
$tablename = 'tbl_im_entries';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table to hold instant messages', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
		),
	'recipient' => array(
		'type' => 'text',
		'length' => 25
		),
	'sender' => array(
		'type' => 'text',
		'length' => 25
		),
	'content' => array(
		'type' => 'text',
		'length' => 255
		),
	'isread' => array(
		'type' => 'integer'
		),
	'updated' => array(
		'type' => 'date',
		)
	);

//create other indexes here...

?>