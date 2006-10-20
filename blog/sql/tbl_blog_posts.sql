<?php
// Table Name
$tablename = 'tbl_blog_posts';

//Options line for comments, encoding and character set
$options = array('comment' => 'post table', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

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
	'post_author' => array(
		'type' => 'text',
		'length' => 50,
		),
	'post_date' => array(
		'type' => 'timestamp',
		),
	'post_date_gmt' => array(
		'type' => 'timestamp',
		),
	'post_content' => array(
		'type' => 'clob',
		),
	'post_title' => array(
		'type' => 'clob',
		),
	'post_category' => array(
		'type' => 'text',
		'length' => 32,
		),
	'post_excerpt' => array(
		'type' => 'clob',
		),
	'post_status' => array(
		'type' => 'text',
		'length' => 50,
		),
	'comment_status' => array(
		'type' => 'text',
		'length' => 50,
		),
	'ping_status' => array(
		'type' => 'text',
		'length' => 50,
		),
	'post_password' => array(
		'type' => 'text',
		'length' => 50,
		),
	'post_name' => array(
		'type' => 'text',
		'length' => 200,
		),
	'to_ping' => array(
		'type' => 'text',
		'length' => 255,
		),
	'pinged' => array(
		'type' => 'text',
		'length' => 255,
		),
	'post_modified' => array(
		'type' => 'timestamp',
		),
	'post_modified_gmt' => array(
		'type' => 'timestamp',
		),
	'post_content_filtered' => array(
		'type' => 'clob',
		),
	'post_parent' => array(
		'type' => 'text',
		'length' => 20
		),
	'guid' => array(
		'type' => 'text',
		'length' => 255
		),
	'menu_order' => array(
		'type' => 'integer',
		'length' => 20
		),
	'post_type' => array(
		'type' => 'text',
		'length' => 255
		),
	'post_mimetype' => array(
		'type' => 'text',
		'length' => 255
		),
	'comment_count' => array(
		'type' => 'integer',
		'length' => 20
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