<?php
$sqldata[] = 'CREATE TABLE `tbl_cms_sections` (
  `id` varchar(32) collate latin1_general_ci NOT NULL,
  `title` varchar(50) collate latin1_general_ci default NULL,
  `menutext` varchar(255) collate latin1_general_ci default NULL,
  `image` varchar(100) collate latin1_general_ci default NULL,
  `image_position` varchar(10) collate latin1_general_ci default NULL,
  `description` text collate latin1_general_ci,
  `published` tinyint(1) default '0',
  `checked_out` int(11) unsigned default '0',
  `checked_out_time` datetime default '0000-00-00 00:00:00',
  `ordering` int(11) default '0',
  `access` tinyint(3) unsigned default '0',
  `count` int(11) default '0',
  `params` text collate latin1_general_ci,
  `layout` varchar(32) collate latin1_general_ci default NULL,
  PRIMARY KEY  (`id`)
) ';

$tablename = 'tbl_cms_sections';

$options = array('collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
		),
	'title' => array(
		'type' => 'text',
		'length' => 50
		),
	'menutext' => array(
		'type' => 'text',
		'length' => 255
		),
	'image' => array(
		'type' => 'text',
		'length' => 100
		),
    'image_position' => array(
		'type' => 'text',
		'length' => 10
		),
	'description' => array(
		'type' => 'text'
		),
	'published' => array(
		'type' => 'integer',
		'default' => 0
		),
    'checked_out' => array(
		'type' => 'integer',
        'length' => 11,
        'unsigned' => TRUE,
        'default' => 0
		),
    'checked_out_time' => array(
		'type' => 'datetime',
		'default' => '0000-00-00 00:00:00'
		),
    'ordering' => array(
		'type' => 'integer',
        'length' => 11,
        'default' => 0
		),
    'access' => array(
		'type' => 'integer',
        'length' => 3,
        'unsigned' => TRUE,
        'default' => 0
		),
    'count' => array(
		'type' => 'integer',
        'length' => 11,
        'default' => 0
		),
	'params' => array(
		'type' => 'text'
		),
	'layout' => array(
		'type' => 'text',
        'length' => 32
		)
	);
?>