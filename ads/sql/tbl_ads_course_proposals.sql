<?php
// Table Name
$tablename = 'tbl_ads_course_proposals';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table holding course proposals titles and status', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
    'id' => array(
    'type' => 'text',
    'length' => 32
    ),
    'faculty'  => array(
     'type'  =>  'text',
     'length'=>  50
    ),
    'title'  => array(
     'type'  =>  'text',
     'length'=>  255
    ),
    'userid' => array(
        'type' => 'text',
        'length' => 255,
        'notnull' => TRUE
    ),
    'creation_date' =>  array(
      'type'  =>  'timestamp'
    ),
    'status' => array(
        'type' => 'text',
        'length' => 32,
        'notnull' => TRUE
    ),
    'deleteStatus' => array(
        'type' => 'integer',
        'length' => 4,
        'notnull' => TRUE
    )
);
?>