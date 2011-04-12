<?php


/**
table for class
*/
    $tablename = 'tbl_student_class';
/**

*/

    $options = array('comment' => 'Table for saving student class information', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');
/**

*/


$fields = array(
"students_student_id"=>array(
'type'=>'integer'
'length'=>32,
'notnulll'=>TRUE
)
);
?>