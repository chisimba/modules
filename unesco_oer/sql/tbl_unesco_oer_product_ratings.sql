<?php

//define table
$tablename = 'tbl_unesco_oer_product_ratings';
$options = array('comment'=>'Table to store Product_Ratings','collate'=>'utf8_general_ci','character_set'=>'utf8');

//define fields
$fields = array(
		'id' => array('type' => 'varchar','length' => 32,'not null'),
                'product_id' =>array('type' =>'varchar','length'=>32),
		'score' => array('type' => 'integer')
);
?>
