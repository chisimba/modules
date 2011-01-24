<?php
    $tablename = 'tbl_apo_outcomesandassessmentone';
    $options = array('comment' => 'Table used to save data from user input in the outcomes and assessment one form', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');
    $fields = array('id' => array('type' => 'text','length' => 32, 'notnull'=>TRUE),
                    'data' => array('type' => 'text', 'notnull'=>TRUE)
                    );
?>