<?php
/**
*
*Table for holding comments submitted by user
*
*/

/*
Set the table name
*/
$tablename = 'tbl_formbuilder_textarea_entity';

/*
Options line for comments, encoding and character set
*/
$options = array (
 'comment' => 'table to store text area entities for forms',
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
 'textareaformname' => array(
 'type' => 'text',
 'length' => 65,
 'notnull' => TRUE
),
 'textareaname' => array(
 'type' => 'text',
 'length' => 100,
 'notnull' => 1
),
 'textareavalue' => array(
 'type' => 'clob'
),
 'columnsize' => array(
 'type' => 'text',
 'length' => 5,
 'notnull' => 1
),
 'rowsize' => array(
 'type' => 'text',
 'length' => 5,
 'notnull' => 1
),
 'simpleoradvancedchoice' => array(
 'type' => 'text',
 'length' => 10,
 'notnull' => 1
),
 'toolbarchoice' => array(
 'type' => 'text',
 'length' => 20,
 'notnull' => 1
)
);
?>
