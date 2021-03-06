<?php

$tablename = 'tbl_ahis_exchangerate';

$options = array('comment'=> 'table to store exchange rates','collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32,
        'notnull' => TRUE
		),
    'defaultcurrencyid' => array(
		'type' => 'text',
		'length' => 64,
        'notnull' => TRUE
		),
	'exchangecurrencyid' => array(
		'type' => 'text',
		'length' => 64,
        'notnull' => TRUE
		),
	'startdate' => array(
		'type' => 'text',
		'length' => 64,
        'notnull' => TRUE
		),
	'enddate' => array(
		'type' => 'text',
		'length' => 64,
        'notnull' => TRUE
		),
	'datecreated' => array(
		'type' => 'text',
		'length' => 64,
        'notnull' => TRUE
		),
	'createdby' => array(
		'type' => 'text',
		'length' => 64,
        'notnull' => TRUE
		),
	'datemodified' => array(
		'type' => 'text',
		'length' => 64,
        'notnull' => TRUE
		),
	'modifiedby' => array(
		'type' => 'text',
		'length' => 64,
        'notnull' => TRUE
		)
	
    );
//create other indexes here...

$name = 'index_tbl_ahis_exchangerate';

?>