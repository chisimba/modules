<?php

$tablename = 'tbl_ahis_deworming';

$options = array('comment'=> 'table to deworming details','collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32,
        'notnull' => TRUE
		),
	
    'district' => array(
		'type' => 'text',
		'length' => 32,
        'notnull' => TRUE
		),		
    'animalclass' => array(
		'type' => 'text',
		'length' => 32,
        'notnull' => TRUE
		),
    'numanimals' => array(
		'type' => 'text',
		'length' => 32,
        'notnull' => TRUE
		),
    'antiemetictype' => array(
		'type' => 'text',
		'length' => 64,
        'notnull' => TRUE
		),
      'remarks' => array(
		'type' => 'clob',
		
		),
		
		
	
    );

?>