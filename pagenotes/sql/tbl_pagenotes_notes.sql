<?php
/**
*
* A sample SQL file for pagenotes. Please adapt this to your requirements.
*
*/
// Table Name
$tablename = 'tbl_pagenotes_notes';

//Options line for comments, encoding and character set
$options = array('comment' => 'Storage of page level notes and annotations within the pagenotes module', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
    'id' => array(
        'type' => 'text',
        'length' => 32
        ),
    'datecreated' => array(
        'type' => 'timestamp'
        ),
    'datemodified' => array(
        'type' => 'timestamp'
        ),
    'hash' => array(
        'type' => 'text',
        'length' => 250,
        ),
    'userid' => array(
        'type' => 'text',
        'length' => 25,
        'notnull' => TRUE,
        ),
    'notetype' => array(
        'type' => 'text',
        'length' => 10,
        'notnull' => TRUE,
        ),
    'note' => array(
        'type' => 'clob',
        ),
    );

//create other indexes here...

$name = 'tbl_pagenotes_notes_idx';

$indexes = array(
    'fields' => array(
         'note' => array(),
    )
);
?>