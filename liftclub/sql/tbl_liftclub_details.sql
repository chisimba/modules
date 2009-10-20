<?php
// Table Name
$tablename = 'tbl_liftclub_details';

//Options line for comments, encoding and character set
$options = array('comment' => 'lift club details', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
		),
	'userid' => array(
		'type' => 'text',
		'length' => 50,
		),
	'times' => array(
		'type' => 'text',
		'length' => 20,
		),
	'additionalinfo' => array(
		'type' => 'text',
		'length' => 500,
		),	
	'specialoffer' => array(
		'type' => 'boolean'
		),
	'emailnotifications' => array(
		'type' => 'boolean'
		),
	'daysvary' => array(
		'type' => 'boolean'
		),
	'smoke' => array(
		'type' => 'boolean'
		),
	'monday' => array(
		'type' => 'boolean'
		),
	'tuesday' => array(
		'type' => 'boolean'
		),
	'wednesday' => array(
		'type' => 'boolean'
		),
	'thursday' => array(
		'type' => 'boolean'
		),
	'friday' => array(
		'type' => 'boolean'
		),
	'saturday' => array(
		'type' => 'boolean'
		),
	'sunday' => array(
		'type' => 'boolean'
		)
	);
?>
