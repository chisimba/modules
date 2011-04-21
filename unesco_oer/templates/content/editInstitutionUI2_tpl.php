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
$this->loadClass('adddatautil', 'unesco_oer');

// setup table and table headings with input options
$table = $this->newObject('htmltable', 'htmlelements');

$header = new htmlHeading();
$header->str = "Edit Institution"; // must use be ObjLanguae
echo $header->show();

$table->startRow();
$header = new htmlHeading();
$header->str = 'Type Institution';
$header->type = 2;
$table->addCell($header->show());
$textinput = new textinput('name');
$textinput->size = 60;
$textinput->setValue();
$table->addCell($textinput->show());
$table->endRow();

$table->startRow();
$header = new htmlHeading();
$header->str = 'Latitued';
$header->type = 2;
$table->addCell($header->show());
$textinput = new textinput('loclat');
$textinput->size = 60;
$textinput->setValue();
$table->addCell($textinput->show());
$table->endRow();


$table->startRow();
$header = new htmlHeading();
$header->str = 'Longitued';
$header->type = 2;
$table->addCell($header->show());
$textinput = new textinput('loclong');
$textinput->size = 60;
$textinput->setValue();
$table->addCell($textinput->show());
$table->endRow();

//$objUpload = $this->getObject('uploadinput', 'filemanager');
//$table->startRow();
//$table->addCell('Upload Thumbnail');
//$table->addCell($objUpload->show());
//$table->endRow();


//Cancel submission Button
$table->startRow();
$button = new button('CancelButton', "Cancel");
$button->setToSubmit();
//linl to cancel Button
$CancelLink = new link($this->uri(array('action' => "cancelEditInstitution",)));
$CancelLink->link = $button->show();
$table->addCell($CancelLink->show());

//Save Button Submission button

$button = new button('saveButtonUI', "save");
$button->setToSubmit();
$table->addCell($button->show());
$table->endRow();

$uri = $this->uri(array('action' => "editInstitution",'puid'=>$InstitutionPUID,'id'=>$InstitutionID));
$form_data = new form('editInstitutionUI2',$uri);
//$form_data->extra = 'enctype="multipart/form-data';
$form_data->addToForm($table->show());
echo $form_data->show()
?>
