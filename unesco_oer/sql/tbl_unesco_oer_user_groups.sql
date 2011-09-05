<?php

//define table
$tablename = 'tbl_unesco_oer_user_groups';
$options = array('comment'=>'Table to store a user and his/her belonging groups','collate'=>'utf8_general_ci','character_set'=>'utf8');

//define fields
$fields = array(
                'id'=> array('type' => 'text','length' => 32,'not null'),
		'userid'=> array('type' => 'text', 'length' => 32),
                'groupid'=> array('type' => 'text', 'length' => 32),
                'approved'=> array('type' => 'text', 'length' => 32)

                );
?>