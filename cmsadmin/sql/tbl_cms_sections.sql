<?php

$tablename = 'tbl_cms_sections';

$options = array('collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
		),
	'title' => array(
		'type' => 'text',
		'length' => 50
		),
	'menutext' => array(
		'type' => 'text',
		'length' => 255
		),
	'image' => array(
		'type' => 'text',
		'length' => 100
		),
    'image_position' => array(
		'type' => 'text',
		'length' => 10
		),
	'description' => array(
		'type' => 'text'
		),
	'published' => array(
		'type' => 'integer',
		'default' => 0
		),
    'checked_out' => array(
		'type' => 'integer',
        'length' => 11,
        'unsigned' => TRUE,
        'default' => 0
		),
    'checked_out_time' => array(
		'type' => 'datetime',
		'default' => '0000-00-00 00:00:00'
		),
    'ordering' => array(
		'type' => 'integer',
        'length' => 11,
        'default' => 0
		),
    'access' => array(
		'type' => 'integer',
        'length' => 3,
        'unsigned' => TRUE,
        'default' => 0
		),
    'count' => array(
		'type' => 'integer',
        'length' => 11,
        'default' => 0
		),
	'params' => array(
		'type' => 'text'
		),
	'layout' => array(
		'type' => 'text',
        'length' => 32
		)
	);
?>