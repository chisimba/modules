<?php
// $sqldata[] = "
// CREATE TABLE `tbl_cms_categories` (
  // `id` int(11) NOT NULL auto_increment,
  // `parent_id` int(11) default '0',
  // `title` varchar(50) collate latin1_general_ci default NULL,
  // `menutext` varchar(255) collate latin1_general_ci default NULL,
  // `image` varchar(100) collate latin1_general_ci default NULL,
  // `sectionid` varchar(50) collate latin1_general_ci default NULL,
  // `image_position` varchar(10) collate latin1_general_ci default NULL,
  // `description` text collate latin1_general_ci,
  // `published` tinyint(1) default '0',
  // `checked_out` int(11) unsigned default '0',
  // `checked_out_time` datetime default '0000-00-00 00:00:00',
  // `editor` varchar(50) collate latin1_general_ci default NULL,
  // `ordering` int(11) default '0',
  // `access` tinyint(3) unsigned default '0',
  // `count` int(11) default '0',
  // `params` text collate latin1_general_ci,
  // PRIMARY KEY  (`id`),
  // KEY `cat_idx` (`sectionid`,`published`,`access`),
  // KEY `idx_section` (`sectionid`),
  // KEY `idx_access` (`access`),
  // KEY `idx_checkout` (`checked_out`)
// )";

// Table Name
$tablename = 'tbl_cms_categories';

//Options line for comments, encoding and character set
$options = array('collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
		),
	'parent_id' => array(
		'type' => 'text',
		'length' => 32,
        'notnull' => 1,
		'default' => '0'
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
    'sectionid' => array(
		'type' => 'text',
		'length' => 50
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
		'length' => 1,
        'notnull' => TRUE,
		'default' => '0'
		),
    'checked_out' => array(
		'type' => 'int',
		'length' => 11,
        'notnull' => TRUE,
		'default' => '0'
		),
    'checked_out_time' => array(
		'type' => 'datetime',
		'notnull' => TRUE,
		'default' => '0000-00-00 00:00:00'
		),
    'editor' => array(
		'type' => 'text',
		'length' => 32
		),
    'ordering' => array(
		'type' => 'text',
		'length' => 32
		),
    'access' => array(
		'type' => 'text',
		'length' => 32
		),
    'count' => array(
		'type' => 'integer',
		'length' => 11,
        'default' => '0'
		),
    'params' => array(
		'type' => 'text'
		)
    );

//create other indexes here...

$name = 'cat_idx';

$indexes = array(
                'fields' => array(
                	'sectionid' => array(),
                	'published' => array(),
                	'access' => array(),
                    'checked_out' => array(),
                )
        );
?>