<?php

$tablename = 'tbl_cms_content';

$options = array('comment' => 'cms_contents', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32,

		),
	'title' => array(
		'type' => 'text',
		'length' => 100
		),
	'menutext' => array(
		'type' => 'text',
		'length' => 100
		),
	'introtext' => array(
		'type' => 'text',
		'length' => 255
		),
    'body' => array(
		'type' => 'text',
		'length' => 255
		),
    'published' => array(
		'type' => 'integer',
        'length' => 1,
        'notnull' => TRUE,
        'default' => '0'
		),
	'sectionid' => array(
		'type' => 'text',
		'length' => 32,

		),
    'mask' => array(
		'type' => 'integer',
        'unsigned' => TRUE,

		),
    'catid' => array(
		'type' => 'text',
        'length' => 32
		),
	'created' => array(
		'type' => 'date',

		),
    'created_by' => array(
		'type' => 'text',
        'length' => 32,

		),
    'created_by_alias' => array(
		'type' => 'text',
        'length' => 100
		),
    'modified' => array(
		'type' => 'date',

		),
    'modified_by' => array(
		'type' => 'integer',
        'length' => 11,
        'unsigned' => TRUE,

		),
    'checked_out' => array(
		'type' => 'integer',
        'length' => 11,
        'unsigned' => TRUE,

		),
    'checked_out_time' => array(
		'type' => 'date',

		),
    'publish_up' => array(
		'type' => 'date',

		),
    'publish_down' => array(
		'type' => 'date',

		),
    'images' => array(
		'type' => 'text',
		'length' => 255
		),
    'urls' => array(
		'type' => 'text',
		'length' => 255
		),
    'attribs' => array(
		'type' => 'text',
		'length' => 255
		),
    'version' => array(
		'type' => 'integer',
        'length' => 11,
        'unsigned' => TRUE,

		),
    'parentid' => array(
		'type' => 'integer',
        'length' => 11,
        'unsigned' => TRUE,

		),
    'ordering' => array(
		'type' => 'integer',
        'length' => 11,

		),
    'metakey' => array(
		'type' => 'text',
		'length' => 255
		),
    'metadesc' => array(
		'type' => 'text',
		'length' => 255
		),
    'access' => array(
		'type' => 'integer',
        'length' => 11,
        'unsigned' => TRUE,

		),
    'hits' => array(
		'type' => 'integer',
        'length' => 11,
        'unsigned' => TRUE,

		),
	);


//create other indexes here...

$name = 'idx_content';

$indexes = array(
                'fields' => array(
                	'sectionid' => array(),
                	'access' => array(),
                	'checked_out' => array(),
                    'published' => array(),
                	'catid' => array(),
                	'mask' => array()
                )
        );
?>