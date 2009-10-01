<?php
    $tablename = 'tbl_ads_course_comments';
    $options = array('comment' => 'Table holding course proposals comments', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');
    $fields = array('id' => array('type' => 'text','length' => 32),
                    'courseid'  => array('type'  =>  'text','length'=> 32,'notnull' => TRUE),
                    'comment' => array('type' => 'text','length' => 255,'notnull' => TRUE),
                    'version'=> array('type' => 'integer','length' => 11,'notnull' => TRUE),
                    'status'  => array('type'  =>  'text','length'=> 999,'notnull' => TRUE),
                    'username' => array('type'  =>  'text','length'=> 32,'notnull' => TRUE),
                    'datemodified'=>array('type'=>'text','length'=>30,'notnull' => TRUE),
                    'unit_type'  => array('type'  =>  'integer','length'=> 4,'notnull' => TRUE),
              );
?>