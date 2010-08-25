<?php
/**
*
*Table for holding comments submitted by user
*
*/

/*
Set the table name
*/
$tablename = 'tbl_formbuilder_dropdown_entity';

/*
Options line for comments, encoding and character set
*/
$options = array (
 'comment' => 'table to store dropdown entities for forms',
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
 'dropdownname' => array(
 'type' => 'text',
 'length' => 35,
 'notnull' => TRUE
),
 'ddoptionlabel' => array(
 'type' => 'text',
 'length' => 150,
 'notnull' => 1
),
 'ddoptionvalue' => array(
 'type' => 'text',
 'length' => 150,
 'notnull' => 1
),
'defaultvalue' => array(
 'type' => 'boolean',
 'notnull'
)
);
?>
