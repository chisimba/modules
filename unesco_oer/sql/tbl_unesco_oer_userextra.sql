<?php

//define table
$tablename = 'tbl_unesco_oer_userextra';
$options = array('comment'=>'Table to store user extra INFO','collate'=>'utf8_general_ci','character_set'=>'utf8');

//define fields
$fields = array(
		'id' => array('type' => 'text','length' => 32),
                'userid'=>array('type'=>'text','lenghth'=>25),
                'birthday' =>array('type' =>'text','length'=>255),
                'address' =>array('type' =>'text','length'=>255),
                'city' =>array('type' =>'text','length'=>255),
                'state' =>array('type' => 'text', 'length' =>255),
                'postaladdress'=>array('type'=>'text','length'=>255),
                'organisation' => array('type' => 'text','length' =>255),
                'jobtittle'=>array('type'=>'text','length'=>255),
                'typeoccapation' => array('type' => 'text','length' => 255),
                'workingphone' =>array('type' =>'text','length'=>255),
                'description' =>array('type' =>'text','length'=>255),
                'thumbnail'=>array('type'=>'text','length'=>255),
                'websitelink' => array('type' => 'text','length' => 255)

            
		
);
?>