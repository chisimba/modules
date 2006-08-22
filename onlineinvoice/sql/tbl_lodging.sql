<?php
//create table for lodging expenses
// Table Name
$tablename = 'tbl_lodging';

//Options line for comments, encoding and character set
$options = array('comment' => 'The table lodging is managed by the onlineinvoice module', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

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
   'date'         =>  array(
      'type'      =>  'date',
      'notnull'     => 1
   ),
   'vendor'       =>  array(
      'type'      =>  'text',
      'length'    =>  32,
      'notnull'     => 1
   ),
   'currency'     =>  array(
      'type'      =>  'text',
      'length'    =>  3,
      'notnull'     => 1
   ),
   'cost'         =>  array(
    'type'        =>  'float',
    'notnull'     => 1
   ),
   'exchangerate' =>  array(
    'type'        =>  'float',
    'notnull'     => 1
    ) 
    );
?>
