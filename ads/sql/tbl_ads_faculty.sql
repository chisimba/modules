<?php
    $tablename = 'tbl_ads_faculty';
    $options = array('comment' => 'Table holding rules and syllabus book information', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');
    $fields = array('id' => array('type' => 'text','length' => 32, 'notnull'=>TRUE),
                    'name' => array('type' => 'text','length' => 50, 'notnull'=>TRUE),
                    'userid' => array('type' => 'text','length' => 32, 'notnull'=>TRUE));
?>