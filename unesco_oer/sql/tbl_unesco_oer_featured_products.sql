<?php

//define table
$tablename = 'tbl_unesco_oer_featured_products';
$options = array('comment'=>'Table to store featured_Products','collate'=>'utf8_general_ci','character_set'=>'utf8');

//define fields
$fields = array(
		'id'=> array('type' =>'text','length' => 32,'not null'),
                'product_id' =>array('type' =>'text','length'=>32)
		
);
?>
