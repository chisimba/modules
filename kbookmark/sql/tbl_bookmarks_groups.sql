<?php
//5ive definition
$tablename = 'tbl_bookmarks_groups';

//Options line for comments, encoding and character set
$options = array('comment' => 'Bookmarks Groups', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
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
	'description' => array(
		'type' => 'text',
		'length' => 255,
		'notnull' => TRUE,
		'default' => '',
		),
	'isprivate' => array(
		'type' => 'text',
		'length' => 1,
		'notnull' => TRUE,
		'default' => '',
		),
	'datecreated' => array(
		'type' => 'timestamp',
		'notnull' => TRUE,
		'default' => '0000-00-00 00:00:00',
		),
	'datemodified' => array(
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
	'isdefault' => array(
		'type' => 'text',
		'length' => 1,
		'notnull' => TRUE,
		'default' => ''
		)
	);
?>
