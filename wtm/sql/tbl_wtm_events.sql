<?php

// security check
/**
* The $GLOBALS is an array used to control access to certain constants.
* Here it is used to check if the file is opening in engine, if not it
* stops the file from running.
*
* @global entry point $GLOBALS['kewl_entry_point_run']
* @name $kewl_entry_point_run
*/
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
// end security check

/**
*
* Table for holdig history of greetings of users for hellochisimba
*
*/
/*
Set the table name
*/
$tablename = 'tbl_wtm_events';
/*
Options line for comments, encoding and character set
*/
$options = array(
'comment' => 'Table for tbl_wtm_events',
'collate' => 'utf8_general_ci',
'character_set' => 'utf8');
/*
Create the table fields
*/
$fields = array(
'id' => array(
'type' => 'text',
'length' => 32,
'notnull' => 1
),
'buildingid' => array(
'type' => 'text',
'length' => 32,
'notnull' => TRUE
),
'event' => array(
'type' => 'text',
'length' => 50,
'notnull' => TRUE
),
'date' => array(
'type' => 'date',
'notnull' => TRUE
),
'description' => array(
'type' => 'text',
'length' => 200,
),
'imagename' => array(
'type' => 'text',
'length' => 100,
),
'videoname' => array(
'type' => 'text',
'length' => 100,
),
'modified' => array(
'type' => 'timestamp',
'notnull' => TRUE
)
);

?>

