<?php

/*
*Table to hold journal article  details
*/

/*
*Set table name
*/
$tablename = 'tbl_rimfhe_doe_accr_journal';

/*
*options for comment
*/
	
$options = array(
	'comment' => 'This table stores data of chapters in books in tbl_rimfhe_doe_accr_journal',
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
	'journalname' => array(
		'type' => 'text',
		'length'=> 200,
		'notnull' => TRUE
		),
	'journalcategory' => array(
		'type' => 'text',
		'length'=> 45,
		'notnull' => TRUE
		),
	'articletitle' => array(
		'type' => 'text',
		'length'=> 200,
		'notnull' => TRUE
		),
	'publicationyear' => array(
		'type' => 'integer',
		'length'=> 4,
		'notnull' => TRUE
		),
	'volume' => array(
		'type' => 'text',
		'length'=> 10,
		'notnull' => TRUE
		),
	'firstpageno' => array(
		'type' => 'integer',
		'notnull' => TRUE
		),
	'lastpageno' => array(
		'type' => 'integer',
		'notnull' => TRUE
		),
	'pagetotal' => array(
		'type' => 'integer',
		'notnull' => TRUE
		),
	'authorname' => array(
		'type' => 'text',
		'length'=> 300,
		'notnull' => TRUE
		),
	'fractweightedavg' => array(
		'type' => 'float',
		'length'=> 4,
		'notnull' => FALSE
		)
	);
/*
*index
*/
$name = 'tbl_rimfhe_doe_accr_journal_idx';	

$indexes = array(
                'fields' => array(
				'journalname' => array()
		)
	);
?>
