<?php
/*
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
 */

//define table
$tablename = 'tbl_unesco_oer_modules';
$options = array('comment'=>'Table to store the modules of a year','collate'=>'utf8_general_ci','character_set'=>'utf8');

//define fields
$fields = array(
		'id' => array('type' => 'text','length' => 32,'not null'),
                'year_id' => array('type' => 'text','length' => 32),
                'title' => array('type' => 'text', 'length' => 255),
                'content' => array('type' =>'text'),
                'audience' => array('type' => 'text', 'length' => 255), //_level
                'entry_requirements' => array('type' =>'text'),
                'rules_of_combination' => array('type' => 'text'),
                'outcomes' => array('type' =>'text'), //objectives
                'mode' => array('type' =>'text', 'length' => 255), // delivery mode
                'no_of_hours' => array('type' => 'text', 'length' => 32),
                'associated_material' => array('type' => 'text'),
                'assesment' => array('type' => 'text'),
                'comments_history' => array('type' => 'text'),
                'schedule_of_classes' => array('type' => 'text'),
                'parentid' => array('type' => 'text','length' => 32,'null'),
                'deleted' => array('type' => 'integer','length' => 1,'default' => '0'),
                'remark' => array('type' => 'text', 'length' => 255)
 		);

?>
