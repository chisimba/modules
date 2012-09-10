<?php
// Table for holding the blog types (usually: site, context, personal)

//Table Name
$tablename = 'tbl_simpleblog_posts';

//Options line for comments, encoding and character set
$options = array(
   'comment' => 'This table holds content data for the simpleblog module', 
   'collate' => 'utf8_general_ci', 
   'character_set' => 'utf8',
   'engine' => 'myisam'
);

// Fields
$fields = array(
    'id' => array(
        'type' => 'text',
        'length' => 32
        ),
    'blogid' => array(
        'type' => 'text',
        'length' => 32
        ),
    'userid' => array(
        'type' => 'text',
        'length' => 25,
        'notnull' => TRUE,
        ),
    'datecreated' => array(
            'type' => 'timestamp',
            ),
    'modifierid' => array(
        'type' => 'text',
        'length' => 25,
        'notnull' => TRUE,
        ),
    'datemodified' => array(
            'type' => 'timestamp',
            ),
    'post_title' => array(
            'type' => 'clob',
            ),
    'post_content' => array(
            'type' => 'clob',
            ),
    'post_status' => array(
            'type' => 'text',
            'length' => 50,
            ),
    'post_type' => array(
            'type' => 'text',
            'length' => 50,
            ),
    'post_tags' => array(
            'type' => 'clob',
            ),
    );


// Indexes for the blog posts table
$name = 'tbl_simpleblog_posts_idx';

$indexes = array(
    'fields' => array(
        'userid' => array(),
        'blogid' => array(),
        'post_tags' => array()
     )
);
?>