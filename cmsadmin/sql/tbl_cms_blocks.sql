<?php
//5ive definition
$tablename = 'tbl_cms_blocks';

//Options line for comments, encoding and character set
$options = array('comment' => 'cms blocks', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
		),
	'pageid' => array(
		'type' => 'text',
		'length' => 32
		),
        'blockid' => array(
		'type' => 'text',
		'length' => 32
		),
        'ordering' => array(
		'type' => 'integer',
                'length' => 11
		)
);

?>
