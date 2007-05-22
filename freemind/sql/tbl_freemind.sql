<?php
$tablename = 'tbl_freemind';

//Options line for comments, encoding and character set
$options = array('collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32,
		),
    'contextcode' => array(
		'type' => 'text',
		'length' => 255,
		),
    'title' => array(
		'type' => 'text',
		'length' => 255
		),
    'map' => array(
		'type' => 'clob',
		),
    'datecreated' => array(
		'type' => 'timestamp',
        ),
 );
//create other indexes here...

$name = 'userid';

$indexes = array(
                'fields' => array(
                	'userid' => array(),
                )
        );
?>