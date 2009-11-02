<?php
    $tablename = 'tbl_ads_documenthistory';
    $options = array('comment' => 'Table for document history', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');
    $fields = array('id' => array('type' => 'text','length' => 32, 'notnull'=>TRUE),
                    'courseid' => array('type' => 'text','length' => 50, 'notnull'=>TRUE),
                    'phase' => array('type' => 'text','length' => 50, 'notnull'=>TRUE),
                    'forwardedto' => array('type' => 'text','length' => 32, 'notnull'=>TRUE),
                    'forwardedby' => array('type' => 'text','length' => 32, 'notnull'=>TRUE),
                    'dateforwarded' =>  array('type'  =>  'timestamp', 'notnull'=>TRUE));
?>