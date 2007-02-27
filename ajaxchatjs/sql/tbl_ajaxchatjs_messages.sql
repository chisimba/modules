<?php
// Table Name
$tablename = 'tbl_ajaxchatjs_messages';

//Options line for comments, encoding and character set
$options = array('comment' => 'Stores posted chat messages', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
		),
	'userid' => array(
		'type' => 'text',
		'length' => 25,
		),
	'message' => array(
		'type' => 'text'
		),
	'chattime' => array(
		'type' => 'timestamp'
		),
	'chatmicrotime' => array(
		'type' => 'integer',
		'length' => 15,
        'unsigned' => true,
		)
	);

//create other indexes here...

$name = 'tbl_ajaxchatjs_messages_idx';

$indexes = array(
                'fields' => array(
                	'userid' => array(),
                	'chattime' => array(),
                	'chatmicrotime' => array(),
                )
        );
?>