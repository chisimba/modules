<?php
/**
*
* A sample SQL file for gallery. Please adapt this to your requirements.
*
*/
// Table Name
$tablename = 'tbl_gallery_galleries';

//Options line for comments, encoding and character set
$options = array('comment' => 'Storage of galleries for the gallery module', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
    'id' => array(
        'type' => 'text',
        'length' => 32
    ),
    'context_code' => array(
        'type' => 'text',
        'length' => 250,
    ),
    'user_id' => array(
        'type' => 'text',
        'length' => 32,
    ),
    'name' => array(
        'type' => 'text',
        'length' => 250,
    ),
    'description' => array(
        'type' => 'text',
        'length' => 250,
    ),
    'cover_image_id' => array(
        'type' => 'text',
        'length' => 32,
    ),
    'is_shared' => array(
        'type' => 'integer',
        'length' => 1,
        'default' => 0,
    ),
    'display_order' => array(
        'type' => 'integer',
        'length' => 1,
    ),    
    'date_created' => array(
        'type' => 'timestamp',
    ),
    'created_by' => array(
        'type' => 'text',
        'length' => 32,
    ),
    'date_updated' => array(
        'type' => 'timestamp',
    ),
    'updated_by' => array(
        'type' => 'text',
        'length' => 32,
    ),
);

//create other indexes here...

$name = 'tbl_gallery_galleries_idx';

$indexes = array(
    'fields' => array(
         'id' => array(),
         'context_code' => array(),
         'user_id' => array(),
    )
);
?>