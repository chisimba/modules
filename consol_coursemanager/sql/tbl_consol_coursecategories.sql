<?php

//5ive definition for table tbl_consol_coursecatergories
$tablename = 'tbl_consol_coursecategories';

//Options line for comments, encoding and character set
$options = array('comment' => 'Used to store course catergories', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
	),
	'category_name' => array(
		'type' => 'text',
		'length' => 255
	),
	'category_image' => array(
		'type' => 'text',
		'length' => 32
	),
	'category_order' => array(
		'type' => 'integer',
		'length' => 2
	)
);

?>