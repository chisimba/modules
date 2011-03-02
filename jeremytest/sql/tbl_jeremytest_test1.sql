<?php

// Table Name
$tablename = 'tbl_jeremytest_test1';

//Options line for comments, encoding and character set
$options = array('comment' => 'A test table', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
    'id' => array(
        'type' => 'text',
        'length' => 32
    ),
    'content'  => array(
         'type'  =>  'text',
         'length'=>  255
    )
);
?>