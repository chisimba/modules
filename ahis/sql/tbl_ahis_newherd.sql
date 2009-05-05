<?php

$tablename = 'tbl_ahis_newherd';

$options = array('comment'=> 'table to store newherd','collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32,
        'notnull' => TRUE
		),
	'activeid' => array(
		'type' => 'text',
		'length' => 32,
        'notnull' => TRUE
		),
	'territory' => array(
		'type' => 'text',
		'length' => 32,
        'notnull' => TRUE
		),
	'geolevel2' => array(
		'type' => 'text',
		'length' => 32,
        'notnull' => TRUE
		),
	'farmname' => array(
		'type' => 'text',
		'length' => 64,
        'notnull' => TRUE
		),
	'farmingtype' => array(
		'type' => 'text',
		'length' => 64,
        'notnull' => TRUE
		)
    );
//create other indexes here...

$name = 'index_tbl_ahis_newherd';

?>