<?php

$tablename = 'tbl_learningcontent_activitystreamer';

// Options line for comments, encoding and character set
$options = array('collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
    'id' => array(
        'type' => 'text',
        'length' => 32,
        'notnull' => TRUE
        ),
    'userid' => array(
        'type' => 'text',
        'length' => 25,
        'notnull' => TRUE
        ),
    'contextcode' => array(
        'type' => 'text',
        'length' => 25,
        'notnull' => TRUE
        ),
    'contextitemid' => array(
        'type' => 'text',
        'length' => 32,
        'notnull' => TRUE
        ),
    'datecreated' => array(
        'type' => 'timestamp',
        'notnull' => TRUE
        )
    );
   
?>
