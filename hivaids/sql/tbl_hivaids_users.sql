<?php

//5ive definition
$tablename = 'tbl_hivaids_users';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table extending the tbl_users table', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
		),
	'user_id' => array(
		'type' => 'text',
		'length' => 32
		),
	'sports' => array(
		'type' => 'text',
		'length' => 255
		),
	'hobbies' => array(
		'type' => 'text',
		'length' => 255
		),
	'updated' => array(
		'type' => 'timestamp'
		)
	);

// create other indexes here...

$name = 'hivaids_users_index';

$indexes = array(
                'fields' => array(
                	'user_id' => array()
                )
        );
?>