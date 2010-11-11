<?php
/**
*
*Table for holding all radio entity submitted by users
*
*/

/*
Set the table name
*/
$tablename = 'tbl_formbuilder_checkbox_entity';

/*
Options line for comments, encoding and character set
*/
$options = array (
 'comment' => 'table to store checkbox entities for forms',
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
 'checkboxname' => array(
 'type' => 'text',
 'length' => 150,
 'notnull' => TRUE
),
 'checkboxvalue' => array(
 'type' => 'text',
 'length' => 150,
 'notnull' => TRUE
),
 'checkboxlabel' => array(
 'type' => 'text',
 'length' => 150,
 'notnull' => 1
),
'ischecked' => array(
 'type' => 'boolean',
 'notnull'
),
 'breakspace' => array(
 'type' => 'text',
 'length' => 50,
'notnull'=>0
),
 'label' => array(
 'type' => 'text',
 'length' => 550,
 'notnull' => 0
),
 'labelorientation' => array(
 'type' => 'text',
 'length' => 20,
 'notnull' => 0
)
);
?>
