<?php
// Table Name
$tablename = 'tbl_ahis_currencies';

//Options line for comments, encoding and character set
$options = array('comment' => 'The table contains currencies data', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32,
		'notnull' => 1
    ),
	'iso_currency_code' => array (
		'type' => 'text',
		'length' => 255,
		'notnull' =>1
	),
	'currency' => array (
		'type' => 'text',
		'length' => 255,
		'notnull' => 1
	),
	'symbol' => array (
		'type' => 'text',
		'length' => 255,
		'notnull' => 1
	),
	'remarks' => array (
		'type' => 'text',
		'length' => 255,
		'notnull' => 1
	),
	'start_date' => array (
		'type' => 'date',
        'notnull' => TRUE
	),
	'end_date' => array (
		'type' => 'date',
        'notnull' =>0
	),
	'date_created' => array(
		'type' => 'date',
        'notnull' => TRUE
		),
	'created_by' => array(
		'type' => 'text',
		'length' => 255,
		'notnull' => 0
		),
	'date_modified' => array (
		'type' => 'date',
        'notnull' =>0
	),	
	'modified_by' => array (
		'type' => 'text',
		'length' => 255,
		'notnull' => 0
	)

);
//create other indexes here...

$name = 'index_tbl_ahis_currencies';

?>