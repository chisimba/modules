<?php

//define table
$tablename = 'tbl_sectiond';
$options = array('comment'=>'test','collate'=>'utf8_general_ci','character_set'=>'utf8');

//define fields
$fields = array(
		'id' => array('type' => 'text','length' => 32),
		'coursecode'=>array('type'=>'text','length'=>40),
		'user'=>array('type'=>'text','length'=>40),
		'question'=>array('type'=>'text','length'=>20),
		'value'=>array('type'=>'text','length'=>500));


?>
