<?php
// Table Name
$tablename = 'tbl_wiki2_wikis';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table used to keep the wiki data.', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32,
	),
	'group_type' => array(
	   'type' => 'text',
	   'length' => 255,
    ),
    'group_id' => array(
        'type' => 'text',
        'length' => 255,
    ),
    'description' => array(
        'type' => 'text',
        'length' => 255,
    ),
	'visibility' => array(
		'type' => 'integer',
		'length' => 1,
	),
	'creator_id' => array(
		'type' => 'text',
		'length' => 32,
	),
	'date_created' => array(
		'type' => 'timestamp',
	),
);
?>