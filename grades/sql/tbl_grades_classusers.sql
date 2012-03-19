<?php
/**
*
* A sample SQL file for grades. Please adapt this to your requirements.
*
*/
// Table Name
$tablename = 'tbl_grades_classusers';

//Options line for comments, encoding and character set
$options = array('comment' => 'Bridging table between classes and educators for the grades module', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
		),
	'subject_id' => array(
		'type' => 'text',
		'length' => 32
		),
	'class_id' => array(
		'type' => 'text',
		'length' => 32
		),
	'created_by' => array(
		'type' => 'text',
		'length' => 32,
		),
	'date_created' => array(
		'type' => 'timestamp'
		),
	'modified_by' => array(
		'type' => 'text',
		'length' => 32,
		),
	'date_modified' => array(
		'type' => 'timestamp'
		),
	);

//create other indexes here...

$name = 'tbl_grades_subjectclass_idx';

$indexes = array(
    'fields' => array(
         'id' => array(),
         'subject_id' => array(),
         'class_id' => array(),
    )
);
?>