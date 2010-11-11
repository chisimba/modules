<?php
/**
*
*Table for holding all radio entity submitted by users
*
*/

/*
Set the table name
*/
$tablename = 'tbl_formbuilder_label_entity';

/*
Options line for comments, encoding and character set
*/
$options = array (
 'comment' => 'table to store label entities for forms',
 'character_set' => 'utfs');


/*
Create the table fields
*/
$fields = array(
 'id' => array(
 'type' => 'text',
 'length' => 32,
 'notnull' => 1
),
 'labelname' => array(
 'type' => 'text',
 'length' => 150,
 'notnull' => TRUE
),
 'label' => array(
 'type' => 'text',
 'length' => 150,
 'notnull' => 1
),
 'breakspace' => array(
 'type' => 'text',
 'length' => 50,
'notnull'=>0
)
);
?>
