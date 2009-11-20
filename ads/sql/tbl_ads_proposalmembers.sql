<?php
    $tablename = 'tbl_ads_proposalmembers';
    $options = array('comment' => 'Table holding proposal members', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');
    $fields = array('id' => array('type' => 'text','length' => 32, 'notnull'=>TRUE),
                    'courseid' => array('type' => 'text','length' => 32, 'notnull'=>TRUE),
                    'userid' => array('type' => 'text','length' => 32, 'notnull'=>TRUE),
                    'unit' => array('type' => 'text','length' => 32, 'notnull'=>TRUE),
                    'unit_type' => array('type' => 'text','length' => 32),
                    'phase' => array('type' => 'text','length' => 32, 'notnull'=>TRUE));
?>
