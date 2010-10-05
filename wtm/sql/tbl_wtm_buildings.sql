<?php
/**
*
* WTM building database table
*
* This file provides the data structure of the WTM module's buildings database
* 
* @category Chisimba
* @package wtm
* @author Yen-Hsiang Huang <wtm.jason@gmail.com>
* @copyright 2007 AVOIR
* @license http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
* @version CVS: $Id:$
* @link: http://avoir.uwc.ac.za 
*/

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
$tablename = 'tbl_wtm_buildings';
/*
Options line for comments, encoding and character set
*/
$options = array(
'comment' => 'Table for tbl_wtm_buildings',
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
'building' => array(
'type' => 'text',
'length' => 40,
'notnull' => TRUE
),
'longcoordinate' => array(
'type' => 'integer',
'length' => 7,
//'values' => 5,
'notnull' => TRUE
),
'latcoordinate' => array(
'type' => 'integer',
'length' => 7,
//'values' => 5,
'notnull' => TRUE
),
'xexpand' => array(
'type' => 'integer',
'length' => 7,
//'values' => 5,
'notnull' => TRUE
),
'yexpand' => array(
'type' => 'integer',
'length' => 7,
//'values' => 5,
'notnull' => TRUE
),
'modified' => array(
'type' => 'timestamp',
'notnull' => TRUE
)
);

?>

