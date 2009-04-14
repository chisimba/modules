<?php

// Table Name
$tablename = 'tbl_ahis_deworming';

//Options line for comments, encoding and character set
$options = array('comment' => 'The table contains the keywords for deworming', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32,
        'notnull' => TRUE
		),
	'district' => array(
		'type' => 'text',
		'length' => 32,
		'notnull' => 1
    ),
	'classification' => array(
		'type' => 'text',
		'length' => 255,
		'notnull' => 1
    ),
	'numberofanimals' => array(
		'type' => 'text',
		'length' => 255,
		'notnull' => 1
    ),
	'antiemitictype' => array(
		'type' => 'text',
		'length' => 255,
		'notnull' => 1
    ),
	
	'remarks' => array(
		'type' => 'text',
		'length' => 255,
		'notnull' => 1
    )
);


?>