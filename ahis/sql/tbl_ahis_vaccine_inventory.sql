<?php

$tablename = 'tbl_ahis_vaccine_inventory';

$options = array('comment'=> 'table to store vaccine inventory data','collate' => 'utf8_general_ci', 'character_set' => 'utf8');

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
	'vaccine' => array(
		'type' => 'text',
		'length' => 32,
        'notnull' => TRUE
		),
	'totaldosesonhand' => array(
		'type' => 'text',
		'length' => 32,
        'notnull' => TRUE
		),
	'startmonthdose' => array(
		'type' => 'text',
		'length' => 32,
        'notnull' => TRUE
		),
	'startmonthdate' => array(
		'type' => 'text',
		'length' => 32,
        'notnull' => TRUE
		),
	'endmonthdose' => array(
		'type' => 'text',
		'length' => 32,
        'notnull' => TRUE
		),
	'endmonthdate' => array(
		'type' => 'text',
		'length' => 32,
        'notnull' => TRUE
		),
	'monthdoses' => array(
		'type' => 'text',
		'length' => 32,
        'notnull' => TRUE
		),
	'dosesused' => array(
		'type' => 'text',
		'length' => 32,
        'notnull' => TRUE
		),
	'doseswasted' => array(
		'type' => 'text',
		'length' => 32,
        'notnull' => TRUE
		)
	
    );
//create other indexes here...

$name = 'index_tbl_ahis_vaccine_inventory';

?>