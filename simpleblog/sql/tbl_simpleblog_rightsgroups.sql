<?php
// Table for holding the blog posts

//Table Name
$tablename = 'tbl_simpleblog_rightsgroups';

//Options line for comments, encoding and character set
$options = array('comment' => 'This table holds lists of groups that have edit rights by blogid', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

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
    'editgroup' => array(
        'type' => 'text',
        'length' => 255,
        ),
);

// Indexes for the blogs table
$name = 'tbl_simpleblog_rightsgroups_idx';

$indexes = array(
    'fields' => array(
        'blogid' => array(),
     )
);
?>