<?php
// Table Name
$tablename = 'tbl_tutorials_audit';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table to store audit trail information on the tutorials module.', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32,
	),
	'table_affected' => array(
	   'type' => 'text',
	   'length' => 255,
    ),
	'name_space' => array(
	   'type' => 'text',
	   'length' => 255,
    ),
    'user_id' => array(
        'type' => 'text',
        'length' => 32,
    ),
	'date_record_updated' => array(
		'type' => 'timestamp',
	),
	'audit_comment' => array(
	   'type' => 'text',
	   'length' => 255,
	),
	'updated' => array(
		'type' => 'timestamp',
	),
);
?>