?php

//define table
$tablename = 'tbl_product_languages';
$options = array('comment'=>'Table to store Product_languages','collate'=>'utf8_general_ci','character_set'=>'utf8');

//define fields
$fields = array(
		'id' => array('type' => 'varchar','length' => 32,'not null'),
                'code' =>array('type' =>'varchar','length'=>3),
                'name'=>array('type'=>'varchar','length'=>255)
		);
?>



