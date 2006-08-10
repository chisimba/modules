<?php

//5ive definition
$tablename = 'tbl_im_options';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table to hold instant messages options', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
		),
	'userid' => array(
		'type' => 'text',
		'length' => 25
		),
	'notifyLogin' => array(
		'type' => 'integer'
		),
	'notifyReceive' => array(
		'type' => 'integer'
		),
	'updated' => array(
		'type' => 'date',
		)
	);

//create other indexes here...

?>
