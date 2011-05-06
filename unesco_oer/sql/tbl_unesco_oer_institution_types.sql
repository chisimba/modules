<?php

//define table
$tablename = 'tbl_unesco_oer_institution_types';
$options = array('comment'=>'Table to store institution types','collate'=>'utf8_general_ci','character_set'=>'utf8');

//define fields
$fields = array(
		'id' => array('type' => 'text','length' => 32,'not null'),
                'name' =>array('type' =>'text','length'=>255)
                );
?>

