?php

//define table
$tablename = 'tbl_unesco_oer_products';
$options = array('comment'=>'Table to store Products','collate'=>'utf8_general_ci','character_set'=>'utf8');

//define fields
$fields = array(
		'id' => array('type' => 'text','length' => 32,'not null'),
                'parent_id ' =>array('type' =>'text','length'=>32),
                'title'=>array('type'=>'text','length'=>255,'not null'),
                'creator ' => array('type' => 'text','length' => 32),
                'keywords ' =>array('type' =>'text','length'=>255),
                'description '=>array('type'=>'text'),
                'created_on' => array('type' => 'timestamp'),
                'resource_type ' =>array('type' =>'text','length'=>32),
                'content_type'=>array('type'=>'text','length'=>32),
                'format ' => array('type' => 'text','length' => 32),
                'source ' =>array('type' =>'text','length'=>32),
                 'theme  ' =>array('type' =>'text','length'=>32),
                 'language   ' =>array('type' =>'text','length'=>3),
                 'content    ' =>array('type' =>'text'),
                'thumbnail '=>array('type'=>'text','length'=>512)
 		);
?>
