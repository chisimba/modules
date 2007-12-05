<?php
// Table Name
$tablename = 'tbl_tutorials_late';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table to store the late submission information.', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

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