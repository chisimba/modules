<?php

//define table
$tablename = 'tbl_unesco_oer_institution';
$options = array('comment'=>'Table to store institution','collate'=>'utf8_general_ci','character_set'=>'utf8');

//define fields
$fields = array(
		'id' => array('type' => 'text','length' => 32,'not null'),
                'name' =>array('type' =>'text','length'=>128),
                'loclat' =>array('type' =>'text','length'=>255),
                'loclong' =>array('type' =>'text','length'=>255),
                'country' =>array('type' =>'text','length'=>255),
                'type' => array('type' => 'text','length' => 32),
		'thumbnail' =>array('type' => 'text', 'length' =>255)
);
?>

