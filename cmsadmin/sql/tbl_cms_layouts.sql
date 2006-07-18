<?php
/*
$sqldata[]='CREATE TABLE `tbl_cms_layouts` (
  `id` varchar(32) collate latin1_general_ci NOT NULL,
  `name` varchar(32) collate latin1_general_ci default NULL,
  `imagename` varchar(32) collate latin1_general_ci default NULL,
  `desciption` varchar(255) collate latin1_general_ci default NULL,
  PRIMARY KEY  (`id`),
  KEY `name` (`name`)
)';

$sqldata[] ='INSERT INTO `tbl_cms_layouts` VALUES ('1', 'previous', 'section_previous.gif', 'previous layout');';
$sqldata[] ='INSERT INTO `tbl_cms_layouts` VALUES ('2', 'page', 'section_page.gif', 'Page Layout');';
$sqldata[] ='INSERT INTO `tbl_cms_layouts` VALUES ('3', 'summaries', 'section_summaries.gif', 'Summaries Layout');';
$sqldata[] ='INSERT INTO `tbl_cms_layouts` VALUES ('4', 'list', 'section_list.gif', 'List Layout');';

*/
//5ive definition
$tablename = 'tbl_cms_layouts';

//Options line for comments, encoding and character set
$options = array('comment' => 'cms layouts', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
		),
	'name' => array(
		'type' => 'text',
		'length' => 32
		),
    'imagename' => array(
		'type' => 'text',
		'length' => 32
		),
    'desciption' => array(
		'type' => 'text',
		'length' => 255
		)
);

// Other Indexes

$name = 'name';

$indexes = array(
                'fields' => array(
                	'name' => array()
                )
        );
?>
