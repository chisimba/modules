<?php


/**
table for class
*/
    $tablename = 'tbl_status';
/**

*/

    $options = array('comment' => 'Table for saving status information', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');
/**

*/
$fields = array(
"status_id"=>array(
'type'=>'integer'
'length'=>32,
'notnulll'=>TRUE
),
'status_name' => array(
       'type' => 'varchar',
       'length' => 25,
       'notnull' => TRUE
       )
);
?>