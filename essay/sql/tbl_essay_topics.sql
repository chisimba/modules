<?php
// Table Name
$tablename = 'tbl_essay_topics';

//Options line for comments, encoding and character set
$options = array('comment' => 'The table equipment is managed by the onlineinvoice module', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
	'id' => array(
    'type' => 'text',
    'length' => 32,
    'notnull'     => 1
    ),
  'name'  => array(
     'type'  =>  'text',
     'length'=>  255,
   'notnull' => 1
    ),
    'context' =>  array(
     'type'  =>  'text',
    'notnull'     => 1,
    'length' =>  255,
    'notnull'=> 1
    ),
  'description'  =>  array(
      'type'    =>  'clob',
    'notnull'     => 1

    ),
   'instructions' =>  array(
    'type'        =>  'clob',
	'notnull'     => 1
    ),
    'closing_date'     =>  array(
    'type'        =>  'timestamp',
    'length'=>  14,
	'notnull'     => 1
    ),
   'bypass'         =>  array(
      'type'      =>  'integer',
    'length'=>  4,
    'notnull'   => 1
      
   ),
   'forceone'  =>  array(
      'type'        =>  'integer',
       'length'=>  4,   
      'notnull'     => 1
   ),
   'rubric'  =>  array(
      'type'        =>  'text',
      'length'      => 32,
      'notnull'     => 1
   ),
   'percentage'  =>  array(
      'type'        =>  'integer',
      'length'      => 11,
      'notnull'     => 1
   ),
   'userid'  =>  array(
      'type'        =>  'text',
    'length' =>   32,
      'notnull'     => 1
   ),
    'last_modified' =>  array(
    'type'        =>  'timestamp',
    'length'      =>  32,
    'notnull'     => 1
    ),
   'updated'  =>  array(
      'type'        =>  'timestamp',
      'length' =>   14,
      'notnull'     => 1
      )
   );
//create other indexes here...




