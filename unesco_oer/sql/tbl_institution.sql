<?php

//define table
$tablename = 'tbl_institution';
$options = array('comment'=>'Table to store institution','collate'=>'utf8_general_ci','character_set'=>'utf8');

//define fields
$fields = array(
		'id' => array('type' => 'varchar','length' => 32,'not null'),
                'name ' =>array('type' =>'varchar','length'=>128),
                'loclat ' =>array('type' =>'varchar','length'=>255),
                'loclong' =>array('type' =>'varchar','length'=>255)
		
);


