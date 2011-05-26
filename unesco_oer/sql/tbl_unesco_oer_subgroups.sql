<?php

//define table
$tablename = 'tbl_unesco_oer_subgroups';
$options = array('comment'=>'Table to store subgroups','collate'=>'utf8_general_ci','character_set'=>'utf8');

//define fields
$fields = array(
		'id' => array('type' => 'text','length' => 32,'not null'),
                'name' =>array('type' =>'text','length'=>128),
                'loclat' =>array('type' =>'text','length'=>255),
                'loclong' =>array('type' =>'text','length'=>255),
                'country' =>array('type' =>'text','length'=>255),
                'thumbnail' =>array('type' => 'text', 'length' =>255),
                'Description'=>array('type'=>'text','length'=>128),
                'LinkedInstitution' => array('type' => 'text','length' =>32),
                'LinkedDiscussion'=>array('type'=>'text','length'=>128),
                'Members' => array('type' => 'text','length' => 128),
                'groupid' => array('type' => 'text','length' => 32,'not null'),

);
?>