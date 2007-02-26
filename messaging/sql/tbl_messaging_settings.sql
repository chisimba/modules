<?php
//5ive definition
$tablename = 'tbl_messaging_settings';

//Options line for comments, encoding and character set
$options = array('comment' => 'The table to hold message settings', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
    'id' => array(
        'type' => 'text',
        'length' => 32,
        ),
    'user_id' => array(
        'type' => 'text',
        'length' => 32,
        ),
    'notify_type' => array( // on logon, anytime, time interval
        'type' => 'integer',
        'length' => 1,
        ),
    'time_interval' => array( // time in minutes
        'type' => 'integer',
        'length' => 2,
        ),
    'user_available' => array(
        'type' => 'integer',
        'length' => 1,
        ),
    'date_created' => array(
        'type' => 'timestamp',
        ),
    'updated' => array(
        'type' => 'timestamp',
        ),
    );

// create other indexes here...
$name = 'tbl_messaging_settings_index';

$indexes = array(
                'fields' => array(
                    'user_id' => array(),
                )
        );
?>