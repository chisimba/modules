<?php

//define table
$tablename = 'tbl_unesco_oer_group_resources';
$options = array('comment'=>'Table to store group_resources','collate'=>'utf8_general_ci','character_set'=>'utf8');

//define fields
$fields = array(
		'id' => array('type' => 'text','length' => 32,'not null'),
                'groupid'=>array('type' => 'text','length' => 32),
                'resource_name' =>array('type' =>'text','length'=>255),
                'resource_type'=>array('type'=>'text','length'=>255),
                'author'=>array('type'=>'text','length'=>255),
                'file'=>array('type'=>'text','length'=>255),
                'publisher'=>array('type'=>'text','length'=>255)

		);
?>



