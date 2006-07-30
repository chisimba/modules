<?php
//5ive definition
$tablename = 'tbl_formcatcher';

//Options line for comments, encoding and character set
$options = array('comment' => 'Metadata for uploaded forms used in the Formcatcher module.', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
	),
	'creatorId' => array(
		'type' => 'text',
		'length' => 25
	),
	'dateCreated' => array(
		'type' => 'timestamp'
	),
	'modifierId' => array(
		'type' => 'text',
		'length' => 26
	),	
	'dateModified' => array(
		'type' =>  'timestamp'
	),	
	'usefullpage' => array(
		'type' => 'text',
		'length' => 1
	),
	'title' => array(
		'type' => 'text',
		'length' => 250
	),
	'email' => array(
		'type' => 'text',
		'length' => 250
	),
	'link' => array(
		'type' => 'text',
		'length' => 250
	),
	'filename' => array(
		'type' => 'text',
		'length' => 250
	),
	'description' => array(
		'type' => 'text',
		'length' => 250
	),
	'context' => array(
		'type' => 'text',
		'length' => 50
	),
	'updated' => array(
		'type' => 'timestamp'
	)
);
?>