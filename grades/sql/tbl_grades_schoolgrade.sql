<?php
/**
*
* A sample SQL file for grades. Please adapt this to your requirements.
*
*/
// Table Name
$tablename = 'tbl_grades_schoolgrade';

//Options line for comments, encoding and character set
$options = array('comment' => 'Bridging table between schools and grades for the grades module', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
		),
	'school_id' => array(
		'type' => 'text',
		'length' => 32
		),
	'grade_id' => array(
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

$name = 'tbl_grades_schoolgrade_idx';

$indexes = array(
    'fields' => array(
         'id' => array(),
         'school_id' => array(),
         'grade_id' => array(),
    )
);
?>