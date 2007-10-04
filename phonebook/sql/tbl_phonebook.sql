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
		'length' => 32
	),
	'contactid' => array(
		'type' => 'text',
		'length' => 32
	),
	'emailAddressTwo' => array(
		'type' => 'integer',
		'length' => 13
	),

	'landlinenumber' => array(
		'type' => 'integer',
		'length' => 13
	),
	'address' => array(
		'type' => 'text',
		'length' => 255
	),
	'updated' => array(
	'type' => 'timestamp',
	),
	  'modified' => array(
		'type' => 'timestamp',
	),
  
    'modified_by' => array(
		'type' => 'integer',
        'length' => 11,
        'unsigned' => TRUE,

	),
   
    'checked_out' => array(
		'type' => 'integer',
        'length' => 11,
        'unsigned' => TRUE,
  	), 
     
     'created' => array(
		'type' => 'timestamp',

	),

    'created_by' => array(
		'type' => 'text',
        'length' => 32,
	),
	
);
?>
