<?php

//define table
$tablename = 'tbl_unesco_oer_userextra';
$options = array('comment'=>'Table to store user extra INFO','collate'=>'utf8_general_ci','character_set'=>'utf8');

//define fields
$fields = array(
		'id' => array('type' => 'text','length' => 32,'not null'),
                'useid' =>array('type' =>'text','length'=>255,'not null'),
                'birthday' =>array('type' =>'text','length'=>255),
                'address' =>array('type' =>'text','length'=>255),
                'city' =>array('type' =>'text','length'=>255),
                'state' =>array('type' => 'text', 'length' =>255),
                'postaladdress'=>array('type'=>'text','length'=>128,'not null'),
                'organisation/company' => array('type' => 'text','length' =>32,'not null'),
                'jobtittle'=>array('type'=>'text','length'=>128,'not null'),
                'typeoccapation' => array('type' => 'text','length' => 32,'not null'),
                'workingphone' =>array('type' =>'text','length'=>32,'not null'),
                'description' =>array('type' =>'text','length'=>32,'not null'),
                'websitelink' => array('type' => 'text','length' => 32,'not null'),
                'groupmembership' =>array('type' =>'text','length'=>32,'not null')
            
		
);
?>