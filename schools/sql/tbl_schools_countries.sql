<?php
/**
*
* A sample SQL file for schools. Please adapt this to your requirements.
*
*/
// Table Name
$tablename = 'tbl_schools_countries';

//Options line for comments, encoding and character set
$options = array('comment' => 'Storage of countries for the schools module', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
		),
        'code' => array(
                'type' => 'text',
                'length' => 5
                ),
        'country' => array(
                'type' => 'text',
                'length' => 150
                ),
	'created_by' => array(
		'type' => 'text',
		'length' => 32,
		),
	'date_created' => array(
		'type' => 'timestamp'
		),
	'modified_by' => array(
		'type' => 'text',
		'length' => 32,
		),
	'date_modified' => array(
		'type' => 'timestamp'
		),
	);

//create other indexes here...

$name = 'tbl_schools_countries_idx';

$indexes = array(
    'fields' => array(
        'id' => array(),
        'country' => array(),
    )
);
?>