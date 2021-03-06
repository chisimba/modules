<?php
// Table Name
$tablename = 'tbl_blog_post2cat';

//Options line for comments, encoding and character set
$options = array('comment' => 'post to category relationship table', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
		),
	'userid' => array(
		'type' => 'text',
		'length' => 50,
		),
	'postid' => array(
		'type' => 'text',
		'length' => 32,
		),
	'categoryid' => array(
		'type' => 'text',
		'length' => 32,
		),
	);

//create other indexes here...

$name = 'userid';

$indexes = array(
                'fields' => array(
                	'userid' => array(),
                )
        );
?>
