<?php
//Table Name
$tablename = 'tbl_assignment_blob';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table containing the segments uf uploaded assignments', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');
//Fields
$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
		),
	'fileId' => array(
		'type' => 'text',
		'length' => 100
		),	
	'segment int' => array(
		'type' => 'int'
		),
	'filedata' => array(
		'type' => 'blob'
		),
	'updated' => array(
		'type' => 'TIMESTAMP',
		'length'=> 14
		)
	);
?>
