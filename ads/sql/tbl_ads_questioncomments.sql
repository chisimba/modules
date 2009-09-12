<?php

//define table
$tablename = 'tbl_ads_questioncomments';
$options = array('comment'=>'test','collate'=>'utf8_general_ci','character_set'=>'utf8');

//define fields
$fields = array(
		'id' => array('type' => 'text','length' => 32),
		'coursecode'=>array('type'=>'text','length'=>40),
		'formnumber'=>array('type'=>'text', 'length'=>20),
		'question'=>array('type'=>'text','length'=>20),
		'comment'=>array('type'=>'text'),
		'userid'=>array('type'=>'text','length'=>32),
		'commentdate'=>array('type'=>'date'));
?>
