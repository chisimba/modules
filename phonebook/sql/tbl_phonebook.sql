<?php
//Chisimba definition
$tablename = 'tbl_phonebook';

//Options line for comments, encoding and character set
$options = array('comment' => 'Used to store your contact list', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
	),
	'userid' => array(
		'type' => 'text',
		'length' => 25
	),
	'contactid' => array(
		'type' => 'text',
		'length' => 25
	),
	'iscontact' => array(
		'type' => 'text',
		'length' => 1
	),
	'isfriend' => array(
		'type' => 'text',
		'length' => 1
	),	
	'updated' => array(
	'type' => 'timestamp'
	),
	'created_by' => array(
		'type' => 'text',
      'length' => 32,
	),
    'created_by_alias' => array(
		'type' => 'text',
   	'length' => 100
	),
    'modified' => array(
		'type' => 'timestamp',
	),
	 'modified_by' => array(
		'type' => 'integer',
      'length' => 11,
      'unsigned' => TRUE,
   ),   
);
?>
