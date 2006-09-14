<?php
//Table Name
$tablename = 'tbl_quotes';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table to hold the random quotes', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

//
$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32,
		'notnull'=> 1
		),
	'quote' => array(
		'type'=>'text'
		),
	'whosaidit' => array(
		'type'=>'text',
		'length'=> 150
		),
	'dateCreated' => array(
		'type' => 'date'
		),
	'creatorId' => array(
		'type' => 'text',
		'length' => 32
		),
	'dateModified' => array(
		'type'=> 'date'
		),
	'modifierId' => array(
		'type' => 'text',
		'length' => 32 	
		),
	'modified' => array(
		'type' => 'text',
		'length' => 14,
		'notnull' => 1
		)
	);
?>
