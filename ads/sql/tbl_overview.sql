<?php
// Table Name
$tablename = 'tbl_overview';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table holding course overview information', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
    'id' => array(
    'type' => 'text',
    'length' => 32
    ),
    'userid' => array(
    'type' => 'text',
    'length' => 32
    ),
    'unit_name' => array(
    'type' => 'text',
    'length' => 255
    ),
  'unit_type'  => array(
     'type'  =>  'text',
     'length'=>  32
    ),
   'motiv' => array(
     'type'  =>  'text',
     'length'=>  255
        ),
  'qual' =>  array(
     'type'  =>  'text',
     'length'=>  255
    ),
    'unit_type2' => array(
     'type'  =>  'text',
     'length'=>  32
        )
    );
?>
