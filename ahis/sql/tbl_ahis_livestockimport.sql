<?php

// Table Name
$tablename = 'tbl_ahis_livestockimport';

//Options line for comments, encoding and character set
$options = array('comment' => 'The table contains the keywords for livestock import', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32,
		'notnull' => 1
    ),
	'districtid' => array(
		'type' => 'text',
		'length' => 32,
		'notnull' => 1
    ),
	
	'entrypoint' => array(
		'type' => 'text',
		'length' => 32,
		'notnull' => 1
    ),
	
	'origin' => array(
		'type' => 'text',
		'length' => 32,
		'notnull' => 1
    ),
	
	'destination' => array(
		'type' => 'text',
		'length' => 32,
		'notnull' => 1
    ),
	'classification' => array(
		'type' => 'text',
		'length' => 255,
		'notnull' => 1
    ),
	'products' => array(
		'type' => 'text',
		'length' => 255,
		'notnull' => 1
    )
);
//create other indexes here...
//create other indexes here...
$name = 'tbl_ahis_livestockimport_idx';

$indexes = array(
                'fields' => array(
                	'districtid' => array(),
                	'keyword' => array(),
                )
        );

?>