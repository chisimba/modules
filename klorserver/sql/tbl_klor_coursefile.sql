<?php

$tablename = 'tbl_klor_coursefile';

$options = array('collate' => 'utf8_general_ci', 'character_set' => 'utf8', 'comment' => 'klor');
$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
		),
	'userId' => array(
		'type' => 'text',
		'length' => 120
		),
	'rating' => array(
		'type' => 'int',
		'length' => 11
		),

	'bittorrentlocation' => array(
		'type' => 'longtext'
		
		),
	'title' => array(
		'type' => 'text',
		'length' => 120
		),
    'description' => array(
		'type' => 'text',
		'length'=> 225
		),
	'version' => array(
		'type' => 'text',
		'length'=> 60
		),
	'file' => array(
		'type' => 'longtext'
	
		),
    'link' => array(
		'type' => 'text'
		),
	'license' => array(
		'type' => 'longtext'
		),
	'name' => array(
		'type' => 'text',
		'length'=> 120
		),
	'datatype' => array(
		'type' => 'text',
		'length'=> 120
		),
	'size' => array(
		'type' => 'bigint',
		'length'=> 20
		),
	
	'filedate' => array(
		'type' => 'text'
		),
	'path' => array(
		'type' => 'text',
		'length'=> 225
		),
	'category' => array(
		'type' => 'text',
		'length'=> 32
		),

	'updated' => array(
		'type' => 'timestamp'
		)
    );
?>
