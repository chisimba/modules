<?php
    $tablename = 'tbl_ads_commentsadmin';
    $options = array('comment' => 'Table holding comments admin info', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');
    $fields = array('id' => array('type' => 'text','length' => 32, 'notnull'=>TRUE),
                    'comment_desc' => array('type' => 'text','length' => 20, 'notnull'=>TRUE),
                    'userid' => array('type' => 'text','length' => 32, 'notnull'=>TRUE));
?>