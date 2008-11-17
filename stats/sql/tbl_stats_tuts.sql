<?php
$tablename = 'tbl_stats_tuts';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table to store tutorial attempts', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
    'id' => array(
        'type' => 'text',
        'length' => 32,
        'notnull' => true
        ),
    'studentno' => array(
        'type' => 'text',
        'length' => 32,
        'notnull' => true
        ),
    'testno' => array(
        'type' => 'integer',
        'length' => 2
        ),
    'mark' => array(
        'type' => 'integer',
        'length' => 3
        ),
    'time' => array(
        'type' => 'text',
        'length' => 10
        )
    );

$name = 'tbl_stats_tuts_index';

$indexes = array(
                'fields' => array(
                    'studentno' => array()
                )
        );
?>