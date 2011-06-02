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
$tablename = 'tbl_unesco_oer_curriculum';
$options = array('comment'=>'Table to store Curricula','collate'=>'utf8_general_ci','character_set'=>'utf8');

//define fields
$fields = array(
		'id' => array('type' => 'text','length' => 32,'not null'),
                'product_id' =>array('type' =>'text','length'=>32, 'not null'),
                'title' => array('type' =>'text', 'length' => 255),
                'content'=>array('type'=>'text','length'=>32),
                'forward'=>array('type' =>'text'),
                'background'=>array('type' =>'text'),
                'introductory_description'=>array('type' =>'text'),
                'calendar' => array('type' =>'text', 'length' => 255)
 		);
?>
