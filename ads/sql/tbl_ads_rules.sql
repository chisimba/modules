<?php
// Table Name
$tablename = 'tbl_ads_rules';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table holding rules and syllabus book information', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
    'id' => array(
    'type' => 'text',
    'length' => 32
    ),
    'unit_name' => array(
    'type' => 'text',
    'length' => 32
    ),
    'userid' => array(
    'type' => 'text',
    'length' => 32
    ),
    'b1' => array(
    'type' => 'text',
    'length' => 255
    ),
  'b2'  => array(
     'type'  =>  'text',
     'length'=>  255
    ),
   'b3a' => array(
     'type'  =>  'text',
     'length'=>  255
        ),
  'b3b' =>  array(
     'type'  =>  'text',
     'length'=>  255
    ),
  'b4a' =>  array(
     'type'  =>  'text',
     'length'=>  32
    ),
'b4b' =>  array(
     'type'  =>  'text',
     'length'=>  255
    ),
'b4c' =>  array(
     'type'  =>  'text',
     'length'=>  255
    ),
'b5a' =>  array(
     'type'  =>  'text',
     'length'=>  32
    ),
'b5b' =>  array(
     'type'  =>  'text',
     'length'=>  255
    ),
    );
?>
