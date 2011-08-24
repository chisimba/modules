<?php

//define table
$tablename = 'tbl_unesco_oer_countries';
$options = array('comment'=>'Table to store participating countries','collate'=>'utf8_general_ci','character_set'=>'utf8');

//define fields
$fields = array(
		'id'            => array('type' => 'text', 'length' => 32,'not null'),
                'countrycode'   => array('type' => 'text', 'length'=>32, 'not null'),
                'countryname'   => array('type' => 'text', 'length'=>255, 'not null')
		);
?>



