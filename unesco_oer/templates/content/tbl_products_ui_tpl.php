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
$this->loadclass('htmltable','htmlelements');
$this->loadclass('textinput','htmlelements');

// setup and show heading
$header = new htmlHeading();
$header->str = $this->objLanguage->
        languageText('mod_unesco_oer_product_upload_heading', 'unesco_oer');
$header->type = 2;
echo $header->show();


// setup table and table headings with entries
$table = $this->newObject('htmltable', 'htmlelements');

$textinput = new textinput('title');
$textinput->size = 60;
$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_title', 'unesco_oer'));
$table->addCell($textinput->show());
$table->endRow();

$textinput = new textinput('creator');
$textinput->size = 60;
$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_creator', 'unesco_oer'));
$table->addCell($textinput->show());
$table->endRow();

$textinput = new textinput('keywords');
$textinput->size = 60;
$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_keywords', 'unesco_oer'));
$table->addCell($textinput->show());
$table->endRow();

$editor = $this->newObject('htmlarea', 'htmlelements');
$editor->name = 'description';
$editor->height = '100px';
$editor->width = '550px';
$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_description', 'unesco_oer'));
$table->addCell($editor->show());
$table->endRow();

$textinput = new textinput('theme');
$textinput->size = 60;
$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_theme', 'unesco_oer'));
$table->addCell($textinput->show());
$table->endRow();

$textinput = new textinput('language');
$textinput->size = 60;
$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_language', 'unesco_oer'));
$table->addCell($textinput->show());
$table->endRow();

$objUpload = $this->newObject('uploadinput', 'filemanager');
$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_thumbnail', 'unesco_oer'));
$table->addCell($objUpload->show());
$table->endRow();

// setup button for submission
$button = new button('submitform', $this->objLanguage->
        languageText('mod_unesco_oer_product_upload_button', 'unesco_oer'));
$button->setToSubmit();

$form_data = new form('add_products_ui',$this->uri(array('action'=>"uploadSubmit")));
//TODO find out what this does.
$form_data->extra = 'enctype="multipart/form-data"';
$form_data->addToForm($table->show().'<br />'.$button->show());
echo $form_data->show();

?>
