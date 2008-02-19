<?php
//table name
$tablename = 'tbl_faq2_categories_lang';

//options array
$options = array('comment' => 'faq','collate' => 'utf8_general_ci', 'character_set' => 'utf8');

//fields

$fields = array(
	'id' =>array(
		'type' => 'text',
		'length' => 32,
		'notnull' => TRUE,
		'default' => '',
		 ),
	'categoryid' => array(
		'type' => 'text',
		'length' => 32,
		'notnull' => TRUE,
		'default' => '',
		 ),
        'categoryname' => array(
		'type' => 'text',
		'length' => 255,
		'notnull' => TRUE,
		'default' => '',
		 ),
	'language' => array(
		'type' => 'text',
		'length' => 2,
		'notnull' => TRUE,
		'default' => '',
		 ),
        'isdefaultlang' => array(
		'type' => 'text',
		'length' => 1,
		'notnull' => TRUE,
		'default' => '',
		 ),
	'userid' => array(
	  	'type' => 'text',
		'length' => 32,
		'notnull' => TRUE,
		'default' => '',
		),		  
	'datelastupdated' => array(
		'type' => 'timestamp',
		'notnull' => TRUE,
		'default' => '0000-00-00 00:00:00',
		)
	
	);
?>
