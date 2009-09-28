<?php

//define table
$tablename = 'tbl_ads_emailtemplates';
$options = array('comment'=>'email templates','collate'=>'utf8_general_ci','character_set'=>'utf8');

//define fields
$fields = array(
		'id' => array('type' => 'text','length' => 32),
		'code'=>array('type'=>'text','length'=>40),
                'subject'=>array('type'=>'text','length'=>128),
		'content'=>array('type'=>'text')
		);


?>