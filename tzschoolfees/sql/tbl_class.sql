<?php


/**
table for class 
*/
    $tablename = 'tbl_class';
/**

*/

    $options = array('comment' => 'Table for saving class information', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');
/**
   
*/


$fields = array(
"class_id"=>array(
'type'=>'integer'
'length'=>32,
'notnulll'=>TRUE
),
'class_name' => array(
       'type' => 'text',
       'length' => 25,
       'notnull' => TRUE
       ),
   'class_stream' => array(
       'type' => 'text',
       'length' => 25,
       'notnull' => TRUE
       ),

);
?>