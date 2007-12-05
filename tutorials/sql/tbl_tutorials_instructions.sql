<?php
// Table Name
$tablename = 'tbl_tutorials_instructions';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table to store instructions to the tutorials.', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32,
	),
	'contextcode' => array(
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
    'instructions' => array(
        'type' => 'clob',
    ),
	'deleted' => array(
		'type' => 'integer',
		'length' => 1,
	),
	'updated' => array(
		'type' => 'timestamp',
	),
);
?>