<?php
/**
 * tbl_bestwishespostevents
 *
 * 
 *
 * PHP version 5
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the
 * Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 *
 * @package   wishes
 * @author    Emmanuel Natalis  <matnatalis@udsm.ac.tz>
 * @University Computing center
 * @Dar es salaam university of Tanzania 
 * @copyright 2008 Emmanuel Natalis
 */
/**
*
* Table for holdig the birth dates for users
*
*/
/*
Set the table name
*/
$tablename = 'tbl_bestwishespostevents';
/*
Options line for comments, encoding and character set
*/
$options = array(
    'comment' => 'Table for tbl_birthdates',
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

    'userid' => array(
        'type' => 'text',
        'length' => 50,
        'notnull' => 'TRUE'
        ),
   
    'eventtitle' => array(
        'type' => 'text',
        'notnull' => 'TRUE',
         'length'=>70
        ),
   'eventdescription' => array(
        'type' => 'text',
        'notnull' => 'TRUE',
         'length'=>200,
        ),
    'datestamp' => array(
        'type' => 'date',
        'notnull' => 'TRUE'
        )
);
?>
