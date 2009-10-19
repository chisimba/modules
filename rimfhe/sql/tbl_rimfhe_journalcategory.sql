<?php

/*
*Table to hold journal category  details
*/

/*
*Set table name
*/
$tablename = 'tbl_rimfhe_journalcategory';

/*
*options for comment
*/
	
$options = array(
	'comment' => 'This table journal category data in tbl_rimfhe_journalcategory',
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
	'category' => array(
		'type' => 'text',
		'length'=> 200,
		'notnull' => TRUE
		),
	'description' => array(
		'type' => 'text',
		'length'=> 500,
		'notnull' => TRUE
		)
	);
?>
