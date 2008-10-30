<?php
// Table Name
$tablename = 'tbl_im';

//Options line for comments, encoding and character set
$options = array('comment' => 'table to hold IM messages from users', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
    'id' => array(
        'type' => 'text',
        'length' => 32
        ),
    'parentid' => array(
        'type' => 'text',
        'length' => 32
        ),

    'msgtype' => array(
        'type' => 'text',
        'length' => 255,
        ),
    'msgfrom' => array(
        'type' => 'text',
        'length' => 255,
        ),
    'msgbody' => array(
        'type' => 'clob',
        ),
    'msg_returned' =>array(
        'type' => 'text',
        'length' => 60,
      ),
    'datesent' => array(
        'type' => 'timestamp',
        ),
    );

?>