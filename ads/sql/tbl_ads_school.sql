<?php
    $tablename = 'tbl_ads_school';
    $options = array('comment' => 'Table holding schools for each faculty', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');
    $fields = array('id' => array('type' => 'text','length' => 32, 'notnull'=>TRUE),
                    'schoolname' => array('type' => 'text','length' => 50, 'notnull'=>TRUE),
                    'userid' => array('type' => 'text','length' => 32, 'notnull'=>TRUE),
                    'faculty' => array('type' => 'text','length' => 32, 'notnull'=>TRUE),
                    'deletestatus' => array('type' => 'text','length' => 2, 'default' => 0, 'notnull'=>TRUE),);
?>