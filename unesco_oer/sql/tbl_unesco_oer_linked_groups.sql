<?php

//define table
$tablename = 'tbl_unesco_oer_linked_groups';
$options = array('comment'=>'Table to store links between institutions and groups','collate'=>'utf8_general_ci','character_set'=>'utf8');

//define fields
$fields = array(
		'id'                => array('type' => 'text', 'length' => 32),
                'institution_id'    => array('type' => 'text', 'length' => 32),
                'group_id'          => array('type' => 'text', 'length' => 32)
                );
?>