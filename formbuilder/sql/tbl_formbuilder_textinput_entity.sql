<?php
/**
*
*Table for holding comments submitted by user
*
*/

/*
Set the table name
*/
$tablename = 'tbl_formbuilder_textinput_entity';

/*
Options line for comments, encoding and character set
*/
$options = array (
 'comment' => 'table to store text input entities for forms',
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
 'textinputformname' => array(
 'type' => 'text',
 'length' => 45,
 'notnull' => TRUE
),
 'textinputname' => array(
 'type' => 'text',
 'length' => 100,
 'notnull' => 1
), 'textvalue' => array(
 'type' => 'clob'
),
 'texttype' => array(
 'type' => 'text',
 'length' => 10,
 'notnull' => 1
),
 'textsize' => array(
 'type' => 'text',
 'length' => 5,
 'notnull' => 1
),
 'maskedinputchoice' => array(
 'type' => 'text',
 'length' => 20,
 'notnull' => 1
)
);
?>
