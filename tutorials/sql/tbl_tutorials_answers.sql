<?php
// Table Name
$tablename = 'tbl_tutorials_answers';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table to store the students answers to the tutorials.', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

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
	'question_name_space' => array(
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
    'answer' => array(
        'type' => 'clob',
    ),
    'moderator_mark' => array(
        'type' => 'integer',
        'length' => 3,
    ),
	'moderator_comment' => array(
		'type' => 'clob',
	),
	'moderator_id' => array(
		'type' => 'text',
		'length' => 32,
	),
    'peer_mark' => array(
        'type' => 'integer',
        'length' => 3,
    ),
	'peer_comment' => array(
		'type' => 'clob',
	),
	'peer_id' => array(
		'type' => 'text',
		'length' => 32,
	),
	'request_moderation' => array(
		'type' => 'integer',
		'length' => 1,
	),
	'moderation_reason' => array(
		'type' => 'clob',
	),
	'moderation_complete' => array(
		'type' => 'integer',
		'length' => 1,
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