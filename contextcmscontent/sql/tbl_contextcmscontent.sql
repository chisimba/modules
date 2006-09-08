<?php
//5ive definition
$tablename = 'tbl_contextcmscontent';

//Options line for comments, encoding and character set
$options = array('comment' => 'cms layouts', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
		),
	'contextcode' => array(
		'type' => 'text',
		'length' => 32
		),
	'sectionid' => array(
		'type' => 'text',
		'length' => 32
		)
);

// Other Indexes

$name = 'name';

$indexes = array(
                'fields' => array(
                	'name' => array()
                )
        );
?>