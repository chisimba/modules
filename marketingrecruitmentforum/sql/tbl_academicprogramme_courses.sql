<?php
// Table Name
$tablename = 'tbl_academicprogramme_courses';

//Options line for comments, encoding and character set
$options = array('comment' => 'The tbl_academicprogramme_courses is managed by the marketingrecruitmentforum module', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
	'id'            => array(
    'type'        => 'text',
    'length'      => 32,
    'notnull'     => 1
    ),
  'faculty_code'     => array(
     'type'       =>  'text',
     'length'     =>  255,
  ),
   'code'         => array(
   'type'         => 'text',
   'length'       =>  255
  ),
  'name'          => array(
  'type'          => 'text',
  'length'        =>  255, 
  ),
  'status'        =>  array(
  'type'          =>  'text',
  'length'        =>  255
  ),
  'year_level'    =>  array(
  'type'          =>  'integer',
  'length'        =>  11
  ),
  )
?>
