<?php
//Table Name
$tablename = 'tbl_assignment_filestore';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table containing the details uf uploaded assigments', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');
/*Fields
*/
$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
		),
	'context_id'=> array(
		'type' => 'text',
		'length' => 32
		),
	'userId' => array(
		'type' => 'text',
		'length' => 100,		
		),
	'fileId' => array(
		'type' => 'text',
		'length'=>100
		),
	'filename'=> array(
		'type' => 'text',
		'length' => 120
		),
	'filetype' => array(
		'type' => 'text',
		'length' => 32
		),
	'size'	=> array(
		'type' => 'int',
		),
	'uploadtime'  => array(
		'type'=> 'int'
		),
	'updated'  => array(
		'type'=> 'TIMESTAMP',
		'length'=>14
		)
	);
?>
