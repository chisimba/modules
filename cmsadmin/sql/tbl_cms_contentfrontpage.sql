<?php
$tablename = 'tbl_cms_content_frontpage';

$options = array('comment' => 'cms front page','collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
		),
	'content_id' => array(
		'type' => 'varchar',
		'length' => 32,
        'notnull' => TRUE,        
		),
	'ordering' => array(
		'type' => 'integer',
		'length' => 11,
        'notnull' => TRUE,
        'default' => '0'
		)
    );

$name = 'content_id';

$indexes = array(
                'fields' => array(
                	'content_id' => array()
                )
        );
?>