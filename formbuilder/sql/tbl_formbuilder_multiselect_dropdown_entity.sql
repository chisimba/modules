<?php
/**
*
*Table for holding comments submitted by user
*
*/

/*
Set the table name
*/
$tablename = 'tbl_formbuilder_multiselect_dropdown_entity';

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
 'multiselectdropdownname' => array(
 'type' => 'text',
 'length' => 150,
 'notnull' => TRUE
),
 'msddoptionlabel' => array(
 'type' => 'text',
 'length' => 150,
 'notnull' => 1
),
 'msddoptionvalue' => array(
 'type' => 'text',
 'length' => 150,
 'notnull' => 1
),
'defaultvalue' => array(
 'type' => 'boolean',
 'notnull'
),
 'msddsize' => array(
 'type' => 'integer',
 'notnull' => TRUE
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
