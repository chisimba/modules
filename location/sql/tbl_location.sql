<?php
// Table Name
$tablename = 'tbl_location';

//Options line for comments, encoding and character set
$options = array('comment' => 'User location cache', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
    'id' => array(
        'type' => 'text',
        'length' => 32
        ),
    'userid' => array(
        'type' => 'text',
        'length' => 25
        ),
    'latitude' => array(
        'type' => 'decimal'
        ),
    'longitude' => array(
        'type' => 'decimal'
        ),
    'name' => array(
        'type' => 'text',
        'length' => 255
        ),
    'fireeagle_token' => array(
        'type' => 'text',
        'length' => 255
        ),
    'fireeagle_secret' => array(
        'type' => 'text',
        'length' => 255
        )
    );

?>
