<?php
//5ive definition
$tablename = 'tbl_discussion_ratings_discussion';

//Options line for comments, encoding and character set
$options = array('collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32,
        'notnull' => 1
		),
    'discussion_id' => array(
		'type' => 'text',
		'length' => 32,
        'notnull' => 1
		),
    'rating_description' => array(
		'type' => 'text',
		'length' => 50,
        'notnull' => 1
		),
    'rating_value' => array(
		'type' => 'integer',
		'length' => 4,
        'default' => 0
		),
    'userid' => array(
		'type' => 'text',
        'length' => '25',
        'notnull' => 1
		),
    'datecreated' => array(
		'type' => 'timestamp'
		),
    'modifierid' => array(
		'type' => 'text',
        'length' => '25',
        'notnull' => 1
		),
    'datelastupdated' => array(
		'type' => 'timestamp'
		)
    );
    
//create other indexes here...

$name = 'tbl_discussion_ratings_discussion_idx';

$indexes = array(
                'fields' => array(
                	'discussion_id' => array(),
                    'userid' => array()
                )
        );
?>