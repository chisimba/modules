<?php
// Table Name
$tablename = 'tbl_schoollist';

//Options line for comments, encoding and character set
$options = array('comment' => 'The table studcard is managed by the marketingrecruitmentforum module', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
	'id' => array(
    'type' => 'text',
    'length' => 32,
    'notnull'     => 1
    ),
  'createdby'  => array(
     'type'  =>  'text',
     'length'=>  32,
     'notnull'     => 1
    ),
  'datecreated' =>  array(
      'type'  =>  'date',
      'notnull'     => 1
    ),
  'modifiedby'  =>  array(
      'type'    =>  'text',
      'length'  =>  32,
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
    'schoolname'       =>  array(
    'type'        =>  'text',
    'length'      =>  255,
    'notnull'     => 1
   ),
   'schooladdress'   => array(
    'type'         => 'text',
    'length'       => 255,
    'notnull'     => 1
   ),
   'telnumber'      => array(
    'type'         => 'text',
    'notnull'     => 1
   ),
   'faxnumber'         =>  array(
    'type'        =>  'text',
    'notnull'     => 1
   ),
   'email' =>  array(
   'type'      =>  'text',
   'notnull'     => 1
  ),
  'principal'  =>  array(
  'type'          =>  'text'
  ),
  'guidanceteacher'     =>  array(
  'type'        =>  'text',
  'length'      =>  255
  )
  );
?>
