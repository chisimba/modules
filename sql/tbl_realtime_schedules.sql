<?php
// Table Name
$tablename = 'tbl_realtime_schedules';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table holding course schedules for realtime', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
    'id' => array(
    'type' => 'text',
    'length' => 32
    ),
    'title'  => array(
     'type'  =>  'text',
     'length'=>  512
    ),
    'owner' => array(
        'type' => 'text',
        'length' => 255,
        'notnull' => TRUE
    ),
    'creation_date' =>  array(
      'type'  =>  'timestamp'
    ),
    'meeting_date' =>  array(
      'type'  =>  'date'
    ),
    'start_time' =>  array(
      'type'  =>  'text',
      'length' => '12'
    ),

    'end_time' =>  array(
      'type'  =>  'text',
      'length' => '12'
    ),
    'session_type' =>  array(
      'type'  =>  'text',
      'length' => '20'
    ),

);
?>