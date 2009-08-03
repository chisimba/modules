<?php
    $tablename = 'tbl_course_comments';
    $options = array('comment' => 'Table holding course proposals comments', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');
    $fields = array('id' => array('type' => 'text','length' => 32),
                    'courseid'  => array('type'  =>  'text','length'=> 32,'notnull' => TRUE),
                    'comment' => array('type' => 'text','length' => 255,'notnull' => TRUE),
                    'version'=> array('type' => 'integer','length' => 11,'notnull' => TRUE),
              );
?>