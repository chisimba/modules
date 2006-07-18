<?php
// $sqldata[] = '
// CREATE TABLE `tbl_cms_content` (
  // `id` varchar(32) collate latin1_general_ci NOT NULL,
  // `title` varchar(100) collate latin1_general_ci default NULL,
  // `menutext` varchar(100) collate latin1_general_ci default NULL,
  // `introtext` mediumtext collate latin1_general_ci,
  // `body` mediumtext collate latin1_general_ci,
  // `published` tinyint(1) default '0',
  // `sectionid` varchar(32) collate latin1_general_ci default '',
  // `mask` int(11) unsigned default '0',
  // `catid` varchar(32) collate latin1_general_ci default '',
  // `created` datetime default '0000-00-00 00:00:00',
  // `created_by` varchar(32) collate latin1_general_ci default '0',
  // `created_by_alias` varchar(100) collate latin1_general_ci default NULL,
  // `modified` datetime default '0000-00-00 00:00:00',
  // `modified_by` int(11) unsigned default '0',
  // `checked_out` int(11) unsigned default '0',
  // `checked_out_time` datetime default '0000-00-00 00:00:00',
  // `publish_up` datetime default '0000-00-00 00:00:00',
  // `publish_down` datetime default '0000-00-00 00:00:00',
  // `images` text collate latin1_general_ci,
  // `urls` text collate latin1_general_ci,
  // `attribs` text collate latin1_general_ci,
  // `version` int(11) unsigned default '1',
  // `parentid` int(11) unsigned default '0',
  // `ordering` int(11) default '0',
  // `metakey` text collate latin1_general_ci,
  // `metadesc` text collate latin1_general_ci,
  // `access` int(11) unsigned default '0',
  // `hits` int(11) unsigned default '0',
  // PRIMARY KEY  (`id`),
  // KEY `idx_section` (`sectionid`),
  // KEY `idx_access` (`access`),
  // KEY `idx_checkout` (`checked_out`),
  // KEY `idx_state` (`published`),
  // KEY `idx_catid` (`catid`),
  // KEY `idx_mask` (`mask`)
// )';

$tablename = 'tbl_cms_content';

$options = array('comment' => 'cms_contents', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32,

		),
	'title' => array(
		'type' => 'text',
		'length' => 100
		),
	'menutext' => array(
		'type' => 'text',
		'length' => 100
		),
	'introtext' => array(
		'type' => 'text',
		'length' => 255
		),
    'body' => array(
		'type' => 'text',
		'length' => 255
		),
    'published' => array(
		'type' => 'integer',
        'length' => 1,
        'default' => 0
		),
    'mask' => array(
		'type' => 'integer',
        'unsigned' => TRUE,
        'default' => 0
		),
    'catid' => array(
		'type' => 'text',
        'length' => 32
		),
	'created' => array(
		'type' => 'date',

		),
    'created_by' => array(
		'type' => 'text',
        'length' => 32,
        'default' => '0'
		),
    'created_by_alias' => array(
		'type' => 'text',
        'length' => 100
		),
    'modified' => array(
		'type' => 'date',

		),
    'modified_by' => array(
		'type' => 'integer',
        'length' => 11,
        'unsigned' => TRUE,
        'default' => 0
		),
    'checked_out' => array(
		'type' => 'integer',
        'length' => 11,
        'unsigned' => TRUE,
        'default' => 0
		),
    'checked_out_time' => array(
		'type' => 'date',

		),
    'publish_up' => array(
		'type' => 'date',

		),
    'publish_down' => array(
		'type' => 'date',

		),
    'images' => array(
		'type' => 'text',
		'length' => 255
		),
    'urls' => array(
		'type' => 'text',
		'length' => 255
		),
    'attribs' => array(
		'type' => 'text',
		'length' => 255
		),
    'version' => array(
		'type' => 'integer',
        'length' => 11,
        'unsigned' => TRUE,
        'default' => 0
		),
    'parentid' => array(
		'type' => 'integer',
        'length' => 11,
        'unsigned' => TRUE,
        'default' => 0
		),
    'ordering' => array(
		'type' => 'integer',
        'length' => 11,
        'default' => 0
		),
    'metakey' => array(
		'type' => 'text',
		'length' => 255
		),
    'metadesc' => array(
		'type' => 'text',
		'length' => 255
		),
    'access' => array(
		'type' => 'integer',
        'length' => 11,
        'unsigned' => TRUE,
        'default' => 0
		),
    'hits' => array(
		'type' => 'integer',
        'length' => 11,
        'unsigned' => TRUE,
        'default' => 0
		),
	);


//create other indexes here...

$name = 'idx_content';

$indexes = array(
                'fields' => array(
                	'sectionid' => array(),
                	'access' => array(),
                	'checked_out' => array(),
                    'published' => array(),
                	'catid' => array(),
                	'mask' => array()
                )
        );
?>
