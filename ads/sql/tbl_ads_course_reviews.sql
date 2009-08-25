<?php
// Table Name
$tablename = 'tbl_ads_course_reviews';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table holding course proposals titles and status', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
    'id' => array(
    'type' => 'text',
    'length' => 32
    ),
  'course'  => array(
     'type'  =>  'text',
     'length'=>  255
    ),
   'userid' => array(
        'type' => 'text',
        'length' => 255,
        'notnull' => TRUE
        ),
  'review_date' =>  array(
      'type'  =>  'timestamp'
    ),
    'review' => array(
        'type' => 'text',
        'length' => 255,
        'notnull' => TRUE
        )
    );
?>