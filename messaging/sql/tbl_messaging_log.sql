<?php
//5ive definition
$tablename = 'tbl_messaging_log';

//Options line for comments, encoding and character set
$options = array('comment' => 'Logs for users', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
    'id' => array(
        'type' => 'text',
        'length' => 32,
        ),
    'user_id' => array(
        'type' => 'text',
        'length' => 32,
        ),
    'room_id' => array(
        'type' => 'text',
        'length' => 32,
        ),
    'log_desc' => array(
        'type' => 'clob',
        ),
    'log_start' => array(
        'type' => 'timestamp',
        ),
    'log_stop' => array(
        'type' => 'timestamp',
        ),  
    'updated' => array(
        'type' => 'timestamp',
        ),
    );

// create other indexes here...
$name = 'tbl_messaging_log_index';

$indexes = array(
                'fields' => array(
                    'room_id' => array(),
                    'user_id' => array(),
                )
        );
?>