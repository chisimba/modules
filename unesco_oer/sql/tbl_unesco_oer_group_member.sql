<?php

//define table
$tablename = 'tbl_unesco_oer_group_member';
$options = array('comment'=>'Table to store group_member','collate'=>'utf8_general_ci','character_set'=>'utf8');

//define fields
$fields = array(
		'id' => array('type' => 'text','length' => 32,'not null'),
                'username' =>array('type' =>'text','length'=>255),
                'groupid'=>array('type'=>'text','length'=>32),
                'approved'=>array('type'=>'text','length'=>32)
		);
?>



