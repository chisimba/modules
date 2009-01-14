<?php

$tablename = 'tbl_ahis_users';

$options = array('comment'=> 'Table for extra user data','collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32,
        'notnull' => TRUE
		),
	
    'titleid' => array(
		'type' => 'text',
		'length' => 32,
        'notnull' => TRUE
		),
	
    'statusid' => array(
		'type' => 'text',
		'length' => 32,
        'notnull' => TRUE
		),
	
    'locationid' => array(
		'type' => 'text',
		'length' => 32,
        'notnull' => TRUE
		),
	
    'dateofbirth' => array(
		'type' => 'date'
		),
	
    'datehired' => array(
		'type' => 'date'
		),
	
    'retired' => array(
		'type' => 'text',
		'length' => 1,
        'notnull' => TRUE,
        'default' => 0
		),
	
    'dateretired' => array(
		'type' => 'date'
		),
	
    'departmentid' => array(
		'type' => 'text',
		'length' => 32,
        'notnull' => TRUE
		),
	
    'roleid' => array(
		'type' => 'text',
		'length' => 32,
        'notnull' => TRUE
		)
    );
//create other indexes here...

$name = 'index_tbl_ahis_users';

?>