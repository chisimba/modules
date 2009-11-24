<?php
    $tablename = 'tbl_dms_fileuploads';
    $options = array('comment' => 'Table for saving file information', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');
    $fields = array('id' => array('type' => 'text','length' => 32, 'notnull'=>TRUE),
                    'filename' => array('type' => 'text','length' => 20, 'notnull'=>TRUE),
                    'filetype' => array('type' => 'text','length' => 10, 'notnull'=>TRUE),
                    'date_uploaded' => array('type' => 'timestamp', 'notnull'=>TRUE),
                    'userid' => array('type' => 'text','length' => 15, 'notnull'=>TRUE),
                    'shared' => array('type' => 'text','length' => 3, 'notnull'=>TRUE, 'default'=> 0));
?>