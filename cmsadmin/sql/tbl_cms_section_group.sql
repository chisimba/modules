<?php
//
// Table Name
$tablename = 'tbl_cms_section_group';

//Options line for comments, encoding and character set
$options = array('comment' => 'This table stores groups and their respective permissions associated with cms sections for cms.', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
		),
	'section_id' => array(
		'type' => 'text',
		'length' => 32
		),
	'group_id' => array(
		'type' => 'text',
		'length' => 32
		),
	'read_access' => array(
		'type' => 'boolean'
		),
	'write_access' => array(
		'type' => 'boolean'
		)
    );

?>
