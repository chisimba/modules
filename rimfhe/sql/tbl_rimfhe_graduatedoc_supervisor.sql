<?php

/*
*Table to hold book  details
*/

/*
*Set table name
*/
$tablename = 'tbl_rimfhe_graduatedoc_supervisor';

/*
*options for comment
*/
	
$options = array(
	'comment' => 'This table stores data of chapters books in tbl_rimfhe_graduatedoc_supervisor',
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
	'supervisorname' => array(
		'type' => 'text',
		'length'=> 200,
		'notnull' => TRUE
		),
	'uwcaffiliate' => array(
		'type' => 'text',
		'length'=> 5,
		'notnull' => TRUE
		),
	'ds_regnumber' => array(
		'type' => 'text',
		'length'=> 20,
		'notnull' => TRUE
		)
	);
?>
