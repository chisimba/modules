<?php
    $tablename = 'tbl_apo_comments';
    $options = array('comment' => 'Table used to save data from user input in the comments form', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');
    $fields = array('id' => array('type' => 'text','length' => 32, 'notnull'=>TRUE),
                    'docid' => array('type' => 'text','length' => 32, 'notnull'=>TRUE),
                    'apo' => array('type' => 'text', 'notnull'=>TRUE),
                    'subsidy' => array('type' => 'text', 'notnull'=>TRUE),
                    'library' => array('type' => 'text', 'notnull'=>TRUE),
                    'legal' => array('type' => 'text', 'notnull'=>TRUE),
                    'faculty' => array('type' => 'text', 'notnull'=>TRUE)
                    );
?>