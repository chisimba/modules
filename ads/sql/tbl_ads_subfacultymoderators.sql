<?php
    $tablename = 'tbl_ads_subfacultymoderators';
    $options = array('comment' => 'Table holding sub faculty moderators', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');
    $fields = array('id' => array('type' => 'text','length' => 32, 'notnull'=>TRUE),
                    'facultyid' => array('type' => 'text','length' => 32, 'notnull'=>TRUE),
                    'schoolid' => array('type' => 'text','length' => 32, 'notnull'=>TRUE),
                    'userid' => array('type' => 'text','length' => 32, 'notnull'=>TRUE));
?>