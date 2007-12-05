<?php
// Table Name
$tablename = 'tbl_tutorials_questions';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table to store questions to the tutorials.', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

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
    'question' => array(
        'type' => 'clob',
    ),
    'model_answer' => array(
        'type' => 'clob',
    ),
	'question_value' => array(
		'type' => 'integer',
		'length' => 3,
	),
	'question_order' => array(
		'type' => 'integer',
		'length' => 3,
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