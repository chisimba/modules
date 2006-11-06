<?php
// Table Name
$tablename = 'tbl_studcard';

//Options line for comments, encoding and character set
$options = array('comment' => 'The table studcard is managed by the marketingrecruitmentforum module', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');
// Fields
$fields = array(
	'id'        => array(
  'type'      => 'text',
  'length'    => 32,
  'notnull'   => 1
    ),
  'createdby'  => array(
  'type'       =>  'text',
  'length'     =>  32,
  'notnull'    => 1
    ),
  'datecreated' =>  array(
      'type'    =>  'date',
      'notnull' => 1
    ),
   'date'        =>  array(
   'type'        =>  'date',
   'notnull'     =>   1
   ),
   'surname'    => array(
    'type'      => 'text',
    'length'    => 45,
    'notnull'   => 1
   ),
   'name'      => array(
    'type'     => 'text',
    'length'   => 45,
    'notnull'  => 1
   ),
   'schoolname' =>array(
   'type'       =>  'text',
   'length'     =>  255,
   'notnull'   => 1
   ),
   'postaddress'  =>  array(
    'type'        =>  'text',
    'length'      => 255,
    'notnull'     => 1
   ),
   'postcode' =>  array(
      'type'      =>  'text',
      'length'       => 4,
      'notnull'     => 1
  ),
  'telnumber'  =>  array(
   'type'      =>  'text',
   'length'    =>  32
  ),
  
  'telcode'     =>  array(
  'type'        =>  'text',
  'length'      =>  3
  ),
  'exemption' =>  array(
  'type'      =>  'boolean',
  ),
  'faculty' =>  array(
  'type'        =>  'text',
  'length'      =>  255,
  'notnull'    => 1
  ),
  'course'  =>  array(
  'type'        =>  'text',
  'length'      =>  255,
  'notnull'    => 1
  ),
  'relevantsubject' =>array(
  'type'            => 'boolean',
  ),
  'sdcase'      =>  array(
  'type'        =>  'boolean',
  ),
  );
?>
