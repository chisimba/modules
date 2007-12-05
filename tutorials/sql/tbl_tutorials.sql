<?php
// Table Name
$tablename = 'tbl_tutorials';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table to store the information of the tutorials.', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32,
	),
	'name_space' => array(
		'type' => 'text',
		'length' => 255,
	),
	'name_space_order' => array(
		'type' => 'integer',
		'length' => 7,
	),
	'contextcode' => array(
	   'type' => 'text',
	   'length' => 32,
    ),
    'name' => array(
        'type' => 'text',
        'length' => 255,
    ),
    'type' => array( // standard, interactive
        'type' => 'integer',
        'length' => 1,
    ),
	'description' => array(
		'type' => 'clob',
	),
	'percentage' => array(
		'type' => 'integer',
		'length' => 3,
	),
	'total_mark' => array(
		'type' => 'integer',
		'length' => 4,
	),
	'answer_open_date' => array(
		'type' => 'timestamp',
	),
	'answer_close_date' => array(
		'type' => 'timestamp',
	),
	'marking_close_date' => array(
		'type' => 'timestamp',
	),
	'moderating_close_date' => array(
		'type' => 'timestamp',
	),
	'deleted' => array( // active, deleted
		'type' => 'integer', 
		'length' => 1,
	),
	'updated' => array(
		'type' => 'timestamp',
	),
);
?>