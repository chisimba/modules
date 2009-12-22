<?php

//define table
$tablename = 'tbl_gift';
$options = array('comment'=>'Table to store Gifts','collate'=>'utf8_general_ci','character_set'=>'utf8');

//define fields
$fields = array(
		'id' => array('type' => 'text','length' => 32),
		'donor'=>array('type'=>'text','length'=>128,'not null'),
		'recipient'=>array('type'=>'text','length'=>128,'not null'),
		'giftname'=>array('type'=>'text','length'=>128,'not null'),
		'description'=>array('type'=>'blob','not null'),
		'value'=>array('type'=>'integer','not null'),
		'listed'=>array('type'=>'boolean','not null'));
?>
