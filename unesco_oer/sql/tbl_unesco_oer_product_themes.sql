<?php

//define table
$tablename = 'tbl_unesco_oer_product_themes';

$options = array('comment'=>'Table to store product_themes','collate'=>'utf8_general_ci','character_set'=>'utf8');

//define fields
$fields = array(
		'id' => array('type' =>'text','length' => 32,'not null'),
                'theme' => array('type'=>'text','length'=>128), //main theme
		'umbrella_theme_id' => array('type'=>'text','length'=>128) //subtheme
);
?>
