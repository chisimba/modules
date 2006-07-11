<?php
/*
$sqldata[] = '
CREATE TABLE `tbl_cms_content_frontpage` (
  `content_id` int(11) NOT NULL default '0',
  `ordering` int(11) NOT NULL default '0',
  PRIMARY KEY  (`content_id`)
)';*/

$tablename = 'tbl_cms_content_frontpage';

$options = array('collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
		),
	'content_id' => array(
		'type' => 'integer',
		'length' => 11,
        'notnull' => TRUE,
        'default' => 0
		),
	'ordering' => array(
		'type' => 'integer',
		'length' => 11,
        'notnull' => TRUE,
        'default' => 0
		)
    );
    
$name = 'content_id';

$indexes = array(
                'fields' => array(
                	'content_id' => array()
                )
        );
?>