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

// set up html elements
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('htmltable','htmlelements');
$this->loadClass('textinput','htmlelements');

// setup and show heading
$header = new htmlHeading();
$header->str = "Create new type of product";
$header->type = 2;
echo $header->show();

// setup table and table headings with input options
$table = $this->newObject('htmltable', 'htmlelements');

//input options for resource description
$textinput = new textinput('newTypeDescription');
$textinput->size = 60;
$table->startRow();
$table->addCell('Type Description:');
$table->addCell($textinput->show());
$table->endRow();

//input options for resource table
$textinput = new textinput('newTypeTable');
$textinput->size = 60;
$table->startRow();
$table->addCell('Type Table Name:');
$table->addCell($textinput->show());
$table->endRow();

//input optins for submit button
$button = new button('submitProductType', "Submit Product Type");
$button->setToSubmit();
$table->startRow();
$table->addCell($button->show());
$table->endRow();

//createform, add fields to it and display
$form_data = new form('newResourceType_ui',$this->uri(array('action'=>'resourceTypeSubmit')));
$form_data->addToForm($table->show());
echo $form_data->show();
?>
