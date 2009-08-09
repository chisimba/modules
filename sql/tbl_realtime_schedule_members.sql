<?php
// Table Name
$tablename = 'tbl_realtime_schedule_members';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table holding course schedules members for realtime', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
    'id' => array(
    'type' => 'text',
    'length' => 32
    ),
    'sessionid'  => array(
     'type'  =>  'text',
     'length'=>  32
    ),
    'userid' => array(
        'type' => 'text',
        'length' => 255,
        'notnull' => TRUE
    )
);
?>