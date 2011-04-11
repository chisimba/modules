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
$this->loadClass('adddatautil','unesco_oer');

$utility = new adddatautil();

// setup and show heading
$header = new htmlHeading();
$header->str = $this->objLanguage->languageText('mod_unesco_oer_group_heading', 'unesco_oer');
$header->type = 2;
echo $header->show();

// setup table and table headings with input options
$table = $this->newObject('htmltable', 'htmlelements');

//group name input options
$title = $this->objLanguage->languageText('mod_unesco_oer_group_name', 'unesco_oer');
$utility->addTextInputToTable($title, 4, 'newGroup', 60, '', $table);

//group latitude input options
$title = $this->objLanguage->languageText('mod_unesco_oer_group_loclat', 'unesco_oer');
$utility->addTextInputToTable($title, 4, 'loclat', 60, '', $table);

//group longitued input options
$title = $this->objLanguage->languageText('mod_unesco_oer_group_loclong', 'unesco_oer');
$utility->addTextInputToTable($title, 4, 'loclong', 60, '', $table);

//group thumbnail input options
$objUpload = $this->getObject('uploadinput', 'filemanager');
$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_thumbnail', 'unesco_oer'));
$table->addCell($objUpload->show());
$table->endRow();

//submission button
$table->startRow();
$button = new button('submitGroupUI', "Submit Group");
$button->setToSubmit();
$table->addCell($button->show());
$table->endRow();

//createform, add fields to it and display
$form_data = new form('createGroup_ui',$this->uri(array('action'=>'createGroupSubmit')));
$form_data->extra = 'enctype="multipart/form-data"';
$form_data->addToForm($table->show());
echo $form_data->show();
?>
