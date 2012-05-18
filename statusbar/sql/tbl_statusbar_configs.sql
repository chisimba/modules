<?php
/**
*
* A sample SQL file for statusbar. Please adapt this to your requirements.
*
*/
// Table Name
$tablename = 'tbl_statusbar_configs';

//Options line for comments, encoding and character set
$options = array('comment' => 'Storage of configs for the statusbar module', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
    'id' => array(
        'type' => 'text',
        'length' => 32
    ),
    'param' => array(
        'type' => 'text',
        'length' => 255,
    ),
    'default_value' => array(
        'type' => 'text',
        'length' => 255,
    ),
    'created_by' => array(
        'type' => 'text',
        'length' => 32,
    ),
    'date_created' => array(
        'type' => 'timestamp'
    ),
    'modified_by' => array(
        'type' => 'text',
        'length' => 32,
    ),
    'date_modified' => array(
        'type' => 'timestamp'
    ),
);

//create other indexes here...

$name = 'tbl_statusbar_configs_idx';

$indexes = array(
    'fields' => array(
         'id' => array(),
         'param' => array(),
    )
);
?>