<?php
// Table Name
$tablename = 'tbl_academicprogramme_faculties';

//Options line for comments, encoding and character set
$options = array('comment' => 'The tbl_academicprogramme_faculties is managed by the marketingrecruitmentforum module', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
	'id'            => array(
    'type'        => 'text',
    'length'      => 32,
    'notnull'     => 1
    ),
   'code'         => array(
   'type'         => 'text',
   'length'       =>  255
  ),
  'name'          => array(
  'type'          => 'text',
  'length'        =>  255, 
  ),
  );
?>
