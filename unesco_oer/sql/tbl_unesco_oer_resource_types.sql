<?php

//define table
$tablename = 'tbl_unesco_oer_resource_types';

$options = array('comment'=>'Table to store resources types','collate'=>'utf8_general_ci','character_set'=>'utf8');

//define fields
$fields = array(
		'id' => array('type' => 'text','length' => 32,'not null'),
		'description '=>array('type'=>'text','length'=>128)
		
);
?>
