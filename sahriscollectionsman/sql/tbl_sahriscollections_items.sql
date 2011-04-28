<?php
// Table Name
$tablename = 'tbl_sahriscollections_items';

//Options line for comments, encoding and character set
$options = array('comment' => 'SAHRIS collection items', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

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
	'collection' => array(
	    'type' => 'text',
	    'length' => 255,
	    ),
	'accno' => array(
	    'type' => 'text',
	    'length' => 255,
	    ),
	'title' => array(
	    'type' => 'text',
	    'length' => 255,
	    ),
	'description' => array(
	    'type' => 'clob',
	    ),
	'media' => array(
	    'type' => 'text',
	    'length' => 255,
	    ),
	'comment' => array(
	    'type' => 'text',
	    'length' => 255,
	    ),
	'datecreated' => array(
	    'type' => 'timestamp',
	    ),
	);

//create other indexes here...

$name = 'userid';

$indexes = array(
                'fields' => array(
                	'userid' => array(),
                )
        );
?>
