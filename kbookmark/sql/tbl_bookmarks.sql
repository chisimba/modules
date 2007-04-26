<?php
//5ive definition
$tablename = 'tbl_bookmarks';

//Options line for comments, encoding and character set
$options = array('comment' => 'Bookmarks', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
		),
	'groupid' => array(
		'type' => 'text',
		'length' => 32
		),
	'title' => array(
		'type' => 'text',
		'length' => 100
		),
	'url' => array(
		'type' => 'text',
		'length' => 255
		),
	'description' => array(
		'type' => 'text',
		'length' => 255
		),
	'datecreated' => array(
		'type' => 'timestamp'
		),
	'isprivate' => array(
		'type' => 'text',
		'length' => 1
		),
	'datelastaccessed' => array(
		'type' => 'timestamp'
		),
	'creatorid' => array(
		'type' => 'text',
		'length' => 32
		),
	'visitcount' => array(
		'type' => 'integer',
		'length' => 11
		),
	'datemodified' => array(
		'type' => 'timestamp'
		),
	'isdeleted' => array(
		'type' => 'text',
		'length' => 1
		)
	);
?>