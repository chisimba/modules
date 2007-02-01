<?php

//5ive definition for table tbl_consol_coursedetails
$tablename = 'tbl_consol_coursederails';

//Options line for comments, encoding and character set
$options = array('comment' => 'Used to store course details', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
	),
	'contextcode' => array(
		'type' => 'text',
		'length' => 255
	),
	'category_order' => array(
		'type' => 'integer',
		'length' => 2
	),
	'category_image' => array(
		'type' => 'text',
		'length' => 32
	)
);

?>