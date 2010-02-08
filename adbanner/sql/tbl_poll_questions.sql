<?php

//5ive definition
$tablename = 'tbl_ad_banner_questions';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table containing the ad_banner questions', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
		),
	'ad_banner_id' => array(
		'type' => 'text',
		'length' => 32
		),
	'question' => array(
		'type' => 'clob'
		),
	'question_type' => array(
		'type' => 'text',
		'length' => 25
		),
	'is_visible' => array(
		'type' => 'integer',
		'length' => 2
		),
	'has_responses' => array(
		'type' => 'integer',
		'length' => 2
		),
	'order_num' => array(
		'type' => 'integer',
		'length' => 2
		),
	'start_date' => array(
		'type' => 'timestamp'
		),
	'end_date' => array(
		'type' => 'timestamp'
		),
	'creator_id' => array(
		'type' => 'text',
		'length' => 32
		),
	'modifier_id' => array(
		'type' => 'text',
		'length' => 32
		),
	'date_created' => array(
		'type' => 'timestamp'
		),
	'updated' => array(
		'type' => 'timestamp'
		),
	);

// create other indexes here...

$name = 'ad_banner_questions_index';

$indexes = array(
                'fields' => array(
                	'ad_banner_id' => array()
                )
        );
?>