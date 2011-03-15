<?php

//define table
$tablename = 'tbl_unesco_oer_product_themes';

$options = array('comment'=>'Table to store product_themes','collate'=>'utf8_general_ci','character_set'=>'utf8');

//define fields
$fields = array(
		'id' => array('type' => 'text','length' => 32,'not null'),
		'description '=>array('type'=>'text','length'=>128)
		
);
?>
