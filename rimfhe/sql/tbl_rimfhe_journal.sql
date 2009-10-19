<?php

/*
*Table to hold journal category  details
*/

/*
*Set table name
*/
$tablename = 'tbl_rimfhe_journal';

/*
*options for comment
*/
	
$options = array(
	'comment' => 'This table journal data in tbl_rimfhe_journal',
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
	'journcatid' => array(
		'type' => 'text',
		'length'=> 32,
		'notnull' => TRUE
		),
	'journal' => array(
		'type' => 'text',
		'length'=> 200,
		'notnull' => TRUE
		),
	'issn' => array(
		'type' => 'text',
		'length'=> 200,
		'notnull' => FALSE
		),
	'description' => array(
		'type' => 'text',
		'length'=> 500,
		'notnull' => TRUE
		)
	);
?>
