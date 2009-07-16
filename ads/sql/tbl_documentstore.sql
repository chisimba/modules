<?php

//define table
$tablename = 'tbl_documentstore';
$options = array('comment'=>'test','collate'=>'utf8_general_ci','character_set'=>'utf8');

//define fields
$fields = array(
		'id' => array('type' => 'text','length' => 32),
		'coursecode'=>array('type'=>'text','length'=>40),
		'formnumber'=>array('type'=>'text', 'length'=>20),
		'question'=>array('type'=>'text','length'=>20),
		'value'=>array('type'=>'text','length'=>4000),
		'status'=>array('type'=>'text','length'=>30),
		'version'=>array('type'=>'text','length'=>30),
		'currentuser'=>array('type'=>'text','length'=>32),
        'admincomment'=>array('type'=>'text','length'=>5000));
		//if status = editmode, only currentuser can edit it
		//if status = submitted, only currentuser can't edit it
		//this is basically a lock


?>