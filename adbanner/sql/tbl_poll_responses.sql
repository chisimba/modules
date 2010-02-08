<?php

//5ive definition
$tablename = 'tbl_ad_banner_responses';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table containing the user responses to ad_banner', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
		),
	'question_id' => array(
		'type' => 'text',
		'length' => 32
		),
	'answer_id' => array(
		'type' => 'text',
		'length' => 32
		),
	'date_created' => array(
		'type' => 'timestamp'
		),
	);

// create other indexes here...

$name = 'ad_banner_reponses_index';

$indexes = array(
                'fields' => array(
                	'answer_id' => array()
                )
        );
?>