<?php
/**
*
* A sample SQL file for mynotes. Please adapt this to your requirements.
*
*/
// Table Name
$tablename = 'tbl_mynotes_text';

//Options line for comments, encoding and character set
$options = array('comment' => 'Storage of text for the mynotes module', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
		),
        'userid' => array(
                'type' => 'text',
                'length' => 32
                ),
	'title' => array(
		'type' => 'text',
		'length' => 250,
		),
	'content' => array(
		'type' => 'clob',
		),
        'tags' => array(
                'type' => 'text',
                ),
        'datecreated' => array(
		'type' => 'timestamp'
		),
        'datemodified' => array(
		'type' => 'timestamp'
		)
	);

//create other indexes here...

$name = 'tbl_mynotes_text_idx';
$indexes = array(
    'fields' => array(
         'title' => array(),
    )
);
?>