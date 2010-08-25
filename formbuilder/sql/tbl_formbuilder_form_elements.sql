<?php
/**
*
*Table for holding comments submitted by user
*
*/

/*
Set the table name
*/
$tablename = 'tbl_formbuilder_form_elements';

/*
Options line for comments, encoding and character set
*/
$options = array (
 'comment' => 'table to store all form element names and their orders for all forms',
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
 'formnumber' => array(
 'type' => 'integer',
 'notnull' => TRUE
),
 'formname' => array(
 'type' => 'text',
 'length' => 150,
 'notnull' => 1
),
 'formelementtpye' => array(
 'type' => 'text',
 'length' => 150,
 'notnull' => 1
),
 'formelementname' => array(
 'type' => 'text',
 'length' => 150,
 'notnull' => 1

),
'formelementorder' => array(
 'type' => 'integer',
 'notnull' => 1,
'auto_increment' => 1
)
);
?>
