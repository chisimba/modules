<?php
/**
*
*Table for holding all radio entity submitted by users
*
*/

/*
Set the table name
*/
$tablename = 'tbl_formbuilder_htmlheading_entity';

/*
Options line for comments, encoding and character set
*/
$options = array (
 'comment' => 'table to store HTML Heading entities for forms',
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
 'headingname' => array(
 'type' => 'text',
 'length' => 50,
 'notnull' => TRUE
),
 'heading' => array(
 'type' => 'text',
 'length' => 150,
 'notnull' => 1
),
 'size' => array(
 'type' => 'integer',
 'notnull' => TRUE
),
 'alignment' => array(
 'type' => 'text',
 'length' => 10,
 'notnull' => 1
)
);
?>
