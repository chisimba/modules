<?php
//Table Name
$tablename = 'tbl_ad_banners';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table holding all the information and configuration for each ad banner', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

//Fields
$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32, 
		'notnull'=>TRUE
		),
	'banner_title' => array(
		'type' => 'text',
		'length' => 50, 
		'notnull'=>TRUE
		),
                    
	'comment_desc' => array(
		'type' => 'text',
		'length' => 225, 
		'notnull'=>TRUE
		),
	            
	'banner_width' => array(
		'type' => 'text', 
		'length' => 20,
		'notnull'=>TRUE
		),

	'banner_height' => array(
		'type' => 'text', 
		'length' => 20,
		'notnull'=>TRUE
		),
	            
	'image_name' => array(
		'type' => 'text', 
		'length' => 20,
		'notnull'=>TRUE
		),

	'image_path' => array(
		'type' => 'text', 
		'length' => 100,
		'notnull'=>TRUE
		),

	'image_url' => array(
		'type' => 'text', 
		'length' => 100,
		'notnull'=>TRUE
		),

	'date_created' => array(
		'type' => 'timestamp'
		),

	'date_updated' => array(
		'type' => 'timestamp'
		),

	'deleted' => array(
		'type' => 'integer',
		'length' => 1,
		'notnull'=>TRUE
		),
	
	'is_active' => array(
		'type' => 'integer',
		'length' => 1,
		'notnull'=>TRUE
		)

);
?>
