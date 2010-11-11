<?php
/**
*
*Table for holding all button entity submitted by users
*
*/

/*
Set the table name
*/
$tablename = 'tbl_formbuilder_form_list';

/*
Options line for comments, encoding and character set
*/
$options = array (
 'comment' => 'table to store a list of forms and their details',
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
 'name' => array(
 'type' => 'text',
 'length' => 150,
 'notnull' => TRUE
),
 'label' => array(
 'type' => 'text',
 'length' => 150,
 'notnull' => TRUE
),
 'details' => array(
 'type' => 'clob',
),
'author' => array(
 'type' => 'text',
 'length' => 60,
 'notnull' => 1
),
'submissionemailaddress' => array(
 'type' => 'text',
 'length' => 100,
 'notnull' => 1
),
'submissionoption' => array(
 'type' => 'text',
 'length' => 60,
 'notnull' => 1
),
 'created' => array(
 'type' => 'timestamp',
 'notnull' => TRUE
)
);
?>
