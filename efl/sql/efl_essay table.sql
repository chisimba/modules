<?php

$tablename = 'efl_essay table';



$options = array(
    'comment' => 'Table for efl_essays',
    'collate' => 'utf8_general_ci', 
    'character_set' => 'utf8');

$fields = array(
    'id' => array(
        'type' => 'text',
        'length' => 32,
        'notnull' => 1
        ),
    'userid' => array(
        'type' => 'text',
        'length' => 25,
        'notnull' => TRUE
        ),
    'essayid' => array(
        'type' => 'text',
        'length' => 150,
        'notnull' => 1
        ),
	'title' => array(
        'type' => 'text',
        'length' => 150,
        'notnull' => 1
        ),
    'content' => array(
        'type' => 'text',
		'length' => '1000',
		'notnull' =>TRUE
        ), 
    'date' => array(
        'type' => 'timestamp',
        'notnull' => TRUE
        )
);
?>
