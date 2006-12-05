<?php
//Table Name
$tablename = 'tbl_worksheet_answers';

//Options line for comments, encoding and character set
$options = array('comment' => 'List of students answers to questions in a worksheet', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

//
$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32,
		'notnull'=> 1
		),
	'question_id' => array(
		'type' => 'text',
		'length' => 32,
		'notnull' => 1
		),
	'student_id' => array(
		'type' => 'text',
		'length' => 32,
		'notnull' => 1
		),		
	'dateanswered' => array(
		'type' => 'timestamp',
		'notnull' => 1
		),
	'answer' => array(
		'type' => 'blob'		
		),
	'mark' => array(
		'type' => 'integer',
		'length' => 11,
		'notnull' => 1
		),
	'comments' => array(
		'type' => 'clob',
		'notnull' => 1
		), 
	'lecturer_id' => array(
		'type' => 'text',
		'length' => 32,
		'notnull' => 1,
		),
	'datemarked' => array(
		'type' => 'timestamp',
		'notnull' => 1
		),
	'updated' => array(
		'type' => 'timestamp',
		'length' => 14,
		'notnull' => 1
		)
	);
?>