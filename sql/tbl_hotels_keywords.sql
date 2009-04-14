<?php
// Table Name
$tablename = 'tbl_hotels_keywords';

//Options line for comments, encoding and character set
$options = array('comment' => 'The table contains the keywords of stories', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32,
		'notnull' => 1
    ),
	'storyid' => array (
		'type' => 'text',
		'length' =>32,
		'notnull' => 1
	),
	'keyword' => array (
		'type' => 'text',
		'length' => 255
	),
	'creatorid' => array (
		'type' => 'text',
		'length' => 25,
		'notnull' => 1
	),
	'datecreated' => array (
		'type' => 'timestamp',
		'notnull' => 1
	),
);
//create other indexes here...
//create other indexes here...
$name = 'tbl_hotels_keywords_idx';

$indexes = array(
                'fields' => array(
                	'storyid' => array(),
                	'keyword' => array(),
                	'creatorid' => array(),
                	'datecreated' => array(),
                )
        );
		



?>