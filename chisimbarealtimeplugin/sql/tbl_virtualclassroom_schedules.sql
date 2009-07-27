<?php
// Table Name
$tablename = 'tbl_virtualclassroom_schedules';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table holding course schedules for realtime virtual classroom', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
    'id' => array(
    'type' => 'text',
    'length' => 32
    ),
    'contextcode'  => array(
     'type'  =>  'text',
     'length'=>  255
    ),
    'title'  => array(
     'type'  =>  'text',
     'length'=>  512
    ),
    'category'  => array(
     'type'  =>  'text',
     'length'=>  512
    ),
    'about' => array(
        'type' => 'text'
        ),

    'owner' => array(
        'type' => 'text',
        'length' => 255,
        'notnull' => TRUE
    ),
    'participants' => array(
        'type' => 'integer',
        'length' => 10
	),
    'creation_date' =>  array(
      'type'  =>  'timestamp'
    ),
    'start_date' =>  array(
      'type'  =>  'date'
    ),
    'start_time' =>  array(
      'type'  =>  'text',
      'length' => '12'
    ),

    'end_date' =>  array(
      'type'  =>  'date'
    ),
    'end_time' =>  array(
      'type'  =>  'text',
      'length' => '12'
    ),
    'status' => array(
        'type' => 'text',
        'length' => 32,
        'notnull' => TRUE
    )
);
?>