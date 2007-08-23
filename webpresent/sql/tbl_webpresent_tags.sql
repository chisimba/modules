<?php
// name of the table
$tablename = 'tbl_webpresent_tags';

// Options line for comments, encoding and character set
$option = array('comment' =>  'file tags', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
    'id' => array(
        'type' => 'text',
        'length' => 32,
        ),
    'fileid' => array(  
        'type'=> 'text',
        'length' => 32,
        ),
    'tag' => array(
        'type' => 'clob',
        ),
    );

// create other indexes here

$name = 'webpresent_tags_index';

$indexes = array(
    'fields' => array(
        'fileid' => array(),
        ),
);
?>
