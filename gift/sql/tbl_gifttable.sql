<?php

//define table
$tablename = 'tbl_gifttable';
$options = array('comment'=>'Table to store Gifts','collate'=>'utf8_general_ci','character_set'=>'utf8');

//define fields
$fields = array(
		'id' => array('type' => 'text','length' => 32),
		'donor'=>array('type'=>'text','length'=>128),
		'recipient'=>array('type'=>'text','length'=>128),
		'giftname'=>array('type'=>'text','length'=>128),
		'description'=>array('type'=>'blob'),
		'value'=>array('type'=>'integer'),
		'listed'=>array('type'=>'boolean'));
?>
