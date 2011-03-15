?php

//define table
$tablename = 'tbl_unesco_oer_products';
$options = array('comment'=>'Table to store Products','collate'=>'utf8_general_ci','character_set'=>'utf8');

//define fields
$fields = array(
		'id' => array('type' => 'varchar','length' => 32,'not null'),
                'parent_id ' =>array('type' =>'varchar','length'=>32),
                'title'=>array('type'=>'varchar','length'=>255,'not null'),
                'creator ' => array('type' => 'varchar','length' => 32),
                'keywords ' =>array('type' =>'varchar','length'=>255),
                'description '=>array('type'=>'text'),
                'created_on' => array('type' => 'timestamp'),
                'resource_type ' =>array('type' =>'varchar','length'=>32),
                'content_type'=>array('type'=>'varchar','length'=>32)
                'format ' => array('type' => 'varchar','length' => 32),
                'source ' =>array('type' =>'varchar','length'=>32),
                 'theme  ' =>array('type' =>'varchar','length'=>32),
                 'language   ' =>array('type' =>'varchar','length'=>3),
                 'content    ' =>array('type' =>'text'),
                'thumbnail '=>array('type'=>'varchar','length'=>512)
 		);
?>
