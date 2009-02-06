<?php
$tablename = 'tbl_twitsocial_locs';

//Options line for comments, encoding and character set
$options = array('comment' => 'location tags', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
    'id' => array(
        'type' => 'text',
        'length' => 32,
        'notnull' => true
        ),
    'location' => array(
        'type' => 'text',
        'length' => 255,
        ),
    'weight' => array(
        'type' => 'integer',
        'length' => 50,
        ),
);

$name = 'location';

$indexes = array(
                'fields' => array(
                    //'location' => array(),
                    //'weight' => array(),
                )
        );
?>