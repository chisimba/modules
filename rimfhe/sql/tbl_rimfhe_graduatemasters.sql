<?php

/*
*Table to hold book  details
*/

/*
*Set table name
*/
$tablename = 'tbl_rimfhe_graduatemasters';

/*
*options for comment
*/
	
$options = array(
	'comment' => 'This table stores data of chapters books in tbl_rimfhe_graduatemasters',
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
	'ms_surname' => array(
		'type' => 'text',
		'length'=> 100,
		'notnull' => TRUE
		),
	'ms_initials' => array(
		'type' => 'text',
		'length'=> 10,
		'notnull' => TRUE
		),
	'ms_firstname' => array(
		'type' => 'text',
		'length'=> 100,
		'notnull' => TRUE
		),
	'ms_gender' => array(
		'type' => 'text',
		'length'=> 6,
		'notnull' => TRUE
		),
	'ms_regnumber' => array(
		'type' => 'text',
		'length'=> 20,
		'notnull' => TRUE
		),
	'ms_deptschoool' => array(
		'type' => 'text',
		'length'=> 40,
		'notnull' => TRUE
		),
	'ms_faculty' => array(
		'type' => 'text',
		'length'=> 40,
		'notnull' => TRUE
		),
	'ms_thesistitle' => array(
		'type' => 'text',
		'length'=> 100,
		'notnull' => TRUE
		),
	'ms_degree' => array(
		'type' => 'text',
		'length'=> 10,
		'notnull' => TRUE
		)
	);
/*
*index
*/
$name = 'tbl_rimfhe_graduatemasters_idx';	

$indexes = array(
                'fields' => array(
				'ms_regnumber' => array()
		)
	);

?>
