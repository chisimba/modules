<?php
//Chisimba definition
$tablename = 'tbl_phonebook';

//Options line for comments, encoding and character set
$options = array('comment' => 'Used to store your contact list', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
	),
	'userid' => array(
		'type' => 'text',
		'length' => 25
	),
	'cellnumber' => array(
		'type' => 'integer',
		'length' => 25
	),
	'landlinenumber' => array(
		'type' => 'integer',
		'length' => 25
	),
	'address' => array(
		'type' => 'text',
		'length' => 255
	),	
	'updated' => array(
	'type' => 'timestamp'
	),
	
);
?>
