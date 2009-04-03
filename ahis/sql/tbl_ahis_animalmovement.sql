<?php

// Table Name
$tablename = 'tbl_ahis_animalmovement';

//Options line for comments, encoding and character set
$options = array('comment' => 'The table contains the keywords for animal movement', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32,
		'notnull' => 1
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
	'purpose' => array(
		'type' => 'text',
		'length' => 255,
		'notnull' => 1
    ),
	'destination' => array(
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
//create other indexes here...
//create other indexes here...
$name = 'tbl_ahis_animalmovement_idx';

$indexes = array(
                'fields' => array(
                	'districtid' => array(),
                	'keyword' => array(),
                )
        );

?>