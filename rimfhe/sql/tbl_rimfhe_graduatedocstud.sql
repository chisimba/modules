<?php

/*
*Table to hold book  details
*/

/*
*Set table name
*/
$tablename = 'tbl_rimfhe_graduatedocstud';

/*
*options for comment
*/
	
$options = array(
	'comment' => 'This table stores data of chapters books in tbl_rimfhe_graduatedocstud',
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
	'ds_surname' => array(
		'type' => 'text',
		'length'=> 100,
		'notnull' => TRUE
		),
	'ds_initials' => array(
		'type' => 'text',
		'length'=> 10,
		'notnull' => TRUE
		),
	'ds_firstname' => array(
		'type' => 'text',
		'length'=> 100,
		'notnull' => TRUE
		),
	'ds_gender' => array(
		'type' => 'text',
		'length'=> 6,
		'notnull' => TRUE
		),
	'ds_regnumber' => array(
		'type' => 'text',
		'length'=> 20,
		'notnull' => TRUE
		),
	'ds_deptschoool' => array(
		'type' => 'text',
		'length'=> 40,
		'notnull' => TRUE
		),
	'ds_faculty' => array(
		'type' => 'text',
		'length'=> 20,
		'notnull' => TRUE
		),
	'ds_thesistitle' => array(
		'type' => 'text',
		'notnull' => TRUE
		),
	'ds_degree' => array(
		'type' => 'text',
		'length'=> 10,
		'notnull' => TRUE
		)
	);
/*
*index
*/
$name = 'tbl_rimfhe_graduatedocstud_idx';	

$indexes = array(
                'fields' => array(
				'ds_regnumber' => array()
		)
	);
?>
