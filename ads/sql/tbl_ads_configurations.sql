<?php
    $tablename = 'tbl_ads_dbconfigurations';
    $options = array('comment' => 'Table holding user configurations', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');
    $fields = array('id' => array('type' => 'text','length' => 32, 'notnull'=>TRUE),
                    'emailOption' =>  array('type' => 'text', 'length' => 10),
                    'userid' => array('type' => 'text','length' => 32, 'notnull'=>TRUE));
?>