<?php

//define table
$tablename = 'tbl_unesco_oer_available_product_languages';
$options = array('comment'=>'Table to store Product_languages','collate'=>'utf8_general_ci','character_set'=>'utf8');

//define fields
$fields = array(
		'id' => array('type' => 'text','length' => 32,'not null'),
                'product_id' =>array('type' =>'text','length'=>32),
                'language'=>array('type'=>'text','length'=>255)
		);
?>



