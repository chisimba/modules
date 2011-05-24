<?php

//define table
$tablename = 'tbl_unesco_oer_institution';
$options = array('comment'=>'Table to store institution','collate'=>'utf8_general_ci','character_set'=>'utf8');

//define fields
$fields = array(
		'id'            => array('type' => 'text', 'length' => 32,'not null'),
                'name'          => array('type' => 'text', 'length' => 128),
                'description'   => array('type' => 'text', 'length' => 500),
                'type'          => array('type' => 'text', 'length' => 32),
                'country'       => array('type' => 'text', 'length' => 32),
                'address'       => array('type' => 'text', 'length' => 100),
                'zip'           => array('type' => 'integer'),
                'city'          => array('type' => 'text', 'length' => 50),
                'websitelink'   => array('type' => 'text', 'length' => 100),
                'keywords'      => array('type' => 'text', 'length' => 32),
                'linkedGroups'  => array('type' => 'text', 'length' => 32),
                'thumbnail'     => array('type' => 'text', 'length' => 255)
);
?>

