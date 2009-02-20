<?php

/*
*Table to hold book  details
*/

/*
*Set table name
*/
$tablename = 'tbl_rimfhe_accr_journal_authors';

/*
*options for comment
*/
	
$options = array(
	'comment' => 'This table stores data of chapters books in tbl_rimfhe_accr_journal_authors',
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
		'notnull' => FALSE
		),
	'authorsaffiliate' => array(
		'type' => 'text',
		'length'=> 10,
		'notnull' => FALSE
		),
	'articletitle' => array(
		'type' => 'text',
		'length'=> 200,
		'notnull' => TRUE
		)	
	);

?>
