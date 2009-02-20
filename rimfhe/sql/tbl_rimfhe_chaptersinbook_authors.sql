<?php

/*
*Table to hold book  details
*/

/*
*Set table name
*/
$tablename = 'tbl_rimfhe_chaptersinbook_authors';

/*
*options for comment
*/
	
$options = array(
	'comment' => 'This table stores data of chapters books in tbl_rimfhe_chaptersinbook_authors',
	'collate' => 'utf8_general_ci',
	'character_set' => 'utf8'
	);

/*
Create the table fields
*/

$fields = array(
	'id' => array(
		'type' => 'text',
		'length'=> 32,
		'notnull' => TRUE
		),
	'authorname' => array(
		'type' => 'text',
		'length'=> 100,
		'notnull' => TRUE
		),
	'fractweightedavg' => array(
		'type' => 'float',
		'length'=> 4,
		'notnull' => TRUE
		),
	'uwcaffiliate' => array(
		'type' => 'text',
		'length'=> 5,
		'notnull' => TRUE
		),
	'chaptertitle' => array(
		'type' => 'text',
		'length'=> 100,
		'notnull' => TRUE
		)
	);

?>
