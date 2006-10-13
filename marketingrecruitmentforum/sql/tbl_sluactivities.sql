<?php
// Table Name
$tablename = 'tbl_sluactivities';

//Options line for comments, encoding and character set
$options = array('comment' => 'The table sluactivities is managed by the marketingrecruitmentforum module', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

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
   'date'         =>  array(
    'type'        =>  'date',
    'notnull'     => 1
   ),
   'activity'       =>  array(
    'type'        =>  'text',
    'notnull'     => 1
   ),
   'schoolname'   => array(
    'type'         => 'text',
    'length'       => 255
   ),
   'area'      => array(
    'type'         => 'text',
    'notnull'     => 1
   ),
   'province'         =>  array(
    'type'        =>  'text',
    'notnull'     => 1
   )
   );
?>
