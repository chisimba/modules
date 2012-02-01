<?php
//define table
$tablename = 'tbl_oer_adaptations';
$options = array('comment'=>'Table to store adaptations','collate'=>'utf8_general_ci','character_set'=>'utf8');

//define fields
$fields = array(
    'id' => array('type' => 'text', 'length' => 32),
    'parent_productid' => array('type' => 'text', 'length' => 32),
    'userid'=> array('type' => 'text', 'length' => 32),
    'section_title' => array('type' => 'text', 'length' => 250),
    'current_path'=> array('type' => 'text', 'length' => 250),
    'section_content' => array('type' => 'text'),
    'status'=> array('type' => 'text', 'length' => 10),
    'attachment' => array('type' => 'text', 'length' => 500),
    'keywords' => array('type' => 'text', 'length' => 250),
    'contributed_by'=> array('type' => 'text', 'length' => 250),
    'adaptation_notes' => array('type' => 'text')
);
?>