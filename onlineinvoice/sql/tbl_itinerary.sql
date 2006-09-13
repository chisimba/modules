<?php
// Table Name
$tablename = 'tbl_itinerary';

//Options line for comments, encoding and character set
$options = array('comment' => 'The table itinerary is managed by the onlineinvoice module', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

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
   'departuredate'  =>  array(
      'type'        =>  'date',
      'notnull'     => 1
   ),
   'departuretime'  =>  array(
      'type'        =>  'time',
      'notnull'     => 1
   ),
   'departurecity'  =>  array(
      'type'        =>  'text',
      'length'      =>  32,
      'notnull'     => 1
   ),
   'arrivaledate'  =>  array(
      'type'        =>  'date',
      'notnull'     => 1
   ),
   'arrivaltime'  =>  array(
      'type'        =>  'time',
      'notnull'     => 1
   ),
   'arrivalcity'  =>  array(
      'type'        =>  'text',
      'length'      =>  32,
      'notnull'     => 1
   )
   );





?>
