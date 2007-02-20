<?php
//5ive definition
$tablename = 'tbl_request';

//Options line for comments, encoding and character set
$options = array('comment' => 'IconRequest', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32,
		'notnull' => TRUE,
		'default' => '',
		),
	'reqid' => array(
		'type' => 'text',
		'length' => 32,
//		'notnull' => TRUE,
		'default' => '',
		),
	'modname' => array(
		'type' => 'text',
		'length' => 25,
//		'notnull' => TRUE,
		'default' => '',
		),
	'priority' => array(
		'type' => 'text',
		'length' => 1,
//		'notnull' => TRUE,
		'default' => '',
		),
	'type' => array(
		'type' => 'text',
		'length' => 1,
//		'notnull' => TRUE,
		'default' => '',
		),
	'phptype'  => array(
		'type' => 'text',
		'length' => 1,
//		'notnull' => TRUE,
		'default' => '',
		),
	'iconname' => array(
		'type' => 'text',
		'length' => 25,
//		'notnull' => TRUE,
		'default' => '',
		),
	'description' => array(
		'type' => 'text',
		'length' => 255,
//		'notnull' => TRUE,
		'default' => '',
		),
	'uri1' => array(
		'type' => 'text',
		'length' => 32,
//		'notnull' => TRUE,
		'default' => '',
		),
	'uri2' => array(
		'type' => 'text',
		'length' => 32,
//		'notnull' => TRUE,
		'default' => '',
		),
	'complete' => array(
		'type' => 'integer',
		'length' => 11,
//		'notnull' => TRUE,
		'default' => '0',
		),
	'uploaded' => array(
		'type' => 'text',
		'length' => 32,
//		'notnull' => TRUE,
		'default' => '',
		),
	'time' => array(
		'type' => 'timestamp',
//		'notnull' => TRUE,
		'default' => '0000-00-00 00:00:00'
		)
	);
?>
