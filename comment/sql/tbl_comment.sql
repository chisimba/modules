<?php

//Table Name
$tablename = 'tbl_comment';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table to hold the comments ','collate' => 'utf8_general_ci', 'character_set' => 'utf8');
//
$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32,
		'notnull'=> 1,
		),

	'tableName' => array(
		'type' => 'text',
		'length' => 250,
		),
	'sourceId' => array(
		'type' => 'text',
		'length' => 32,
		),
	'type' => array(
		'type' => 'text',
		'length' => 50,
		),
    	'comment' => array(
		'type' => 'text',
		'length' => 32,
		),
	'approved' => array(
		'type' => 'integer',
		'length' => 3,
		),
	'dateCreated' => array(
		'type' => 'date'
		),
	'creatorId' => array(
		'type' => 'text',
		'length' => 25,
		),
   	'dateModified' => array(
		'type'=> 'date'
		),
	
	'modifierId' => array(
		'type' => 'text',
		'length' => 24,
		),
	'modified' => array(
		'type' => 'text',
		'length' => 14,
		'notnull' => 1
		)
    	);

?>