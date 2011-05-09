<?php

//define table
$tablename = 'tbl_unesco_oer_product_languages';
$options = array('comment'=>'Table to store Product_languages','collate'=>'utf8_general_ci','character_set'=>'utf8');

//define fields
$fields = array(
		'id' => array('type' => 'text','length' => 32,'not null'),
                'code' =>array('type' =>'text','length'=>3),
                'name'=>array('type'=>'text','length'=>255)
		);
?>



