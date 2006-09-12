<?php
//create table for lodging expenses
// Table Name
$tablename = 'tbl_pierdiem';

//Options line for comments, encoding and character set
$options = array('comment' => 'The table pierdiem is managed by the onlineinvoice module', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
	'id' => array(
    'type' => 'text',
    'length' => 32,
    'notnull'     => 1
    ),
  'createdby'  => array(
     'type'  =>  'text',
     'length'=>  32
    ),
  'datecreated' =>  array(
      'type'  =>  'date'
    ),
  'modifiedby'  =>  array(
      'type'    =>  'text',
      'length'  =>  32
    ),
   'datemodified' =>  array(
    'type'        =>  'date'
    ),
    'updated'     =>  array(
      'type'        =>  'date'
    ),
    'date'        =>  array(
      'type'        =>  'date',
      'notnull'     => 1
    ),
    'breakfastchoice' => array(
      'type'          => 'boolean'
    ),
    'breakfastlocation' =>  array(
      'type'            =>  'text',
      'length'          =>  32
    ),
    'breakfastrate' =>  array(
      'type'        =>  'float',
      'notnull'     => 1
    ),
    'lunchchoice' => array(
      'type'     => 'boolean'
    ),
    'lunchlocation' =>  array(
      'type'        =>  'text',
      'length'      =>  32
    ),
    'lunchrate'     =>  array(
      'type'        =>  'float',
      'notnull'     => 1
    ),
    'dinnerchoice' => array(
      'type'       => 'boolean'
    ),
    'dinnerlocation' =>  array(
      'type'         =>  'text',
      'length'       =>  32
    ),
    'dinnerrate'     =>  array(
      'type'         =>  'float',
      'notnull'     => 1
    )
    
    );
?>
