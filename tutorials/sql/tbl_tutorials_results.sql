<?php
// Table Name
$tablename = 'tbl_tutorials_results';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table to store the students results of the tutorials.', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32,
	),
	'tutorial_name_space' => array(
	   'type' => 'text',
	   'length' => 255,
    ),
	'name_space' => array(
	   'type' => 'text',
	   'length' => 255,
    ),
	'name_space_order' => array(
	   'type' => 'integer',
	   'length' => 7,
    ),
	'student_id' => array(
	   'type' => 'text',
	   'length' => 32,
    ),
    'completed' => array(
        'type' => 'integer',
        'length' => 1,
    ),
    'marked' => array(
        'type' => 'integer',
        'length' => 1,
    ),
    'student_marked' => array(
        'type' => 'text',
        'length' => 32,
    ),
	'mark_obtained' => array(
		'type' => 'integer',
		'length' => 4,
	),
	'peer_id' => array(
	   'type' => 'text',
	   'length' => 32,
    ),
	'moderator_id' => array(
	   'type' => 'text',
	   'length' => 32,
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