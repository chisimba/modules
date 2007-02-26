<?php
//5ive definition
$tablename = 'tbl_messaging_banned';

//Options line for comments, encoding and character set
$options = array('comment' => 'List of users banned from a chat room', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
    'id' => array(
        'type' => 'text',
        'length' => 32,
        ),
    'room_id' => array( // room banned from
        'type' => 'text',
        'length' => 32,
        ),
    'user_id' => array( // user banned
        'type' => 'text',
        'length' => 32,
        ),
    'ban_type' => array( // tempory or indefinite ban
        'type' => 'integer',
        'length' => 1,
        ),
    'ban_start' => array( // from when does the tempory ban start
        'type' => 'timestamp',
        ),
    'ban_stop' => array( // when does the tempory ban stop
        'type' => 'timestamp',
        ),  
    'creator_id' => array(
        'type' => 'text',
        'length' => 32,
        ),  
    'updated' => array(
        'type' => 'timestamp',
        ),
    );

// create other indexes here...
$name = 'tbl_messaging_banned_index';

$indexes = array(
                'fields' => array(
                    'room_id' => array(),
                    'user_id' => array(),
                    'creator_id' => array(),
                )
        );
?>