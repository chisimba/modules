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
  'modifiedby'  =>  array(
  'type'        =>  'text',
  'length'      =>  32,
  'notnull'     => 1
    ),
   'datemodified' =>  array(
    'type'        =>  'date',
    'notnull'     => 1
    ),
    'updated'     =>  array(
    'type'        =>  'date',
    'notnull'     => 1
    ),
   'date'        =>  array(
   'type'        =>  'date',
   'notnull'     =>   1
   ),
   'surname'    => array(
    'type'      => 'text',
    'length'    => 255,
    'notnull'   => 1
   ),
   'name'      => array(
    'type'     => 'text',
    'length'   => 255,
    'notnull'  => 1
   ),
   'schoolname' =>array(
   'type'       =>  'text',
   'length'     =>  255
   ),
   'postaddress'  =>  array(
    'type'        =>  'text',
    'length'      => 255,
    'notnull'     => 1
   ),
   'postcode' =>  array(
      'type'      =>  'text',
      'length'       => 255,
      'notnull'     => 1
  ),
  'telnumber'  =>  array(
   'type'      =>  'text',
   'length'    =>  32
  ),
  
  'telcode'     =>  array(
  'type'        =>  'text',
  'length'      =>  32
  ),
  'exemption' =>  array(
  'type' =>  'text',
  'length'          =>  1
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
  'type'            => 'text',
  'length'          =>  1
  ),
  'sdcase'      =>  array(
  'type'        =>  'text',
  'length'          =>  1
  ),
  );
?>
