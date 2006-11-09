<?php
//5ive definition
$tablename = 'tbl_bookmarks';

//Options line for comments, encoding and character set
$options = array('comment' => 'Bookmarks', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32,
		'notnull' => TRUE,
		'default' => '',
		),
	'groupid' => array(
		'type' => 'text',
		'length' => 32,
		'notnull' => TRUE,
		'default' => '',
		),
	'title' => array(
		'type' => 'text',
		'length' => 100,
		'notnull' => TRUE,
		'default' => '',
		),
	'url' => array(
		'type' => 'text',
		'length' => 255,
		'notnull' => TRUE,
		'default' => '',
		),
	'description' => array(
		'type' => 'text',
		'length' => 255,
		'notnull' => TRUE,
		'default' => '',
		),
	'datecreated' => array(
		'type' => 'timestamp',
		'notnull' => TRUE,
		'default' => '0000-00-00 00:00:00',
		),
	'isprivate' => array(
		'type' => 'text',
		'length' => 1,
		'notnull' => TRUE,
		'default' => '',
		),
	'datelastaccessed' => array(
		'type' => 'timestamp',
		'notnull' => TRUE,
		'default' => '0000-00-00 00:00:00',
		),
	'creatorid' => array(
		'type' => 'text',
		'length' => 32,
		'notnull' => TRUE,
		'default' => '',
		),
	'visitcount' => array(
		'type' => 'integer',
		'length' => 11,
		'notnull' => TRUE,
		'default' => ''
		),
	'datemodified' => array(
		'type' => 'timestamp',
		'notnull' => TRUE,
		'default' => '0000-00-00 00:00:00',
		),
	'isdeleted' => array(
		'type' => 'text',
		'length' => 1,
		'notnull' => TRUE,
		'default' => ''
		)
	);
?>
