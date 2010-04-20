<?php
    $tablename = 'tbl_wicid_savedata';
    $options = array('comment' => 'Table for saving the form data', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');
    $fields = array('id' => array('type' => 'text','length' => 32, 'notnull'=>TRUE),
                    'formname' => array('type' => 'text', 'notnull'=>TRUE),
                    'formdata' => array('type' => 'text', 'notnull'=>TRUE),
                    'docid' => array('type' => 'text','length' => 15, 'notnull'=>TRUE),
                     
                    );
?>