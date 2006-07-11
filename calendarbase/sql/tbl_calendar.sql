<?php

//5ive definition
$tablename = 'tbl_calendar';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table to hold calendar events', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
		),
	'multiday_event' => array(
		'type' => 'text',
		'length' => 1,
		'notnull' => 1,
        'default' => 0
		),
	'eventdate' => array(
		'type' => 'date',
		'notnull' => 1,
		'default' => '0000-00-00'
		),
	'multiday_event_start_id' => array(
		'type' => 'text'
		),
	'eventtile' => array(
		'type' => 'text',
		'length' => 100
		),
	'eventdetails' => array(
		'type' => 'text'
		),
	'eventurl' => array(
		'type' => 'text',
		'length' => 100
		),
	'userorcontext' => array(
		'type' => 'text',
		'length' => 1
		),
	'context' => array(
		'type' => 'text',
		'length' => 32
		),
	'workgroup' => array(
		'type' => 'text',
		'length' => 32
		),
	'showusers' => array(
		'type' => 'text',
		'length' => 1
		),
	'userFirstEntry' => array(
		'type' => 'text',
		'length' => 32,
		),
	'userLastModified' => array(
		'type' => 'text',
		'length' => 32
		),
	'dateFirstEntry' => array(
		'type' => 'date',
		'notnull' => 1,
		'default' => '0000-00-00 00:00:00'
		),
	'dateLastModified' => array(
		'type' => 'date',
		'notnull' => 1,
		'default' => '0000-00-00 00:00:00'
		),
	'updated' => array(
		'type' => 'date',
		'notnull' => 1,
		'default' => '0000-00-00 00:00:00'
		),
	);
	
//create other indexes here...
	

?>