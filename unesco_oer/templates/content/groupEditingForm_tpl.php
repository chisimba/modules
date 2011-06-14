
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

$this->loadClass('form', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('htmltable','htmlelements');
$this->loadClass('textinput','htmlelements');
$objIcon = $this->newObject('geticon','htmlelements');


$form = new form ('editer', $this->uri(array('action'=>'saveNewGroup')));
// setup and show heading
$header = new htmlheading();
$header->type = 1;
$header->str = "Group Details:";
echo $header->show();

//Get Group details
$group=$this->objDbGroups->getAllGroups();

//$table = $this->newObject('htmltable', 'htmlelements');
$table = $this->newObject('htmltable', 'htmlelements');
$table->width = '100%';
$table->border = '0';
$tableable->cellspacing = '0';
$table->cellpadding = '2';

//Group name
$textinput = new textinput('group_name');
$textinput->size = 70;
$textinput->value = $group['name'];
$table->startRow();
$table->addCell('Group Name');
$table->addCell($textinput->show());
$table->endRow();

$textinput = new textinput('group_email');
$textinput->size = 70;
$textinput->value = $group['email'];
$table->startRow();
$table->addCell('E-mail');
$table->addCell($textinput->show());
$table->endRow();

$textinput = new textinput('group_address');
$textinput->size = 70;
$textinput->value = $group['address'];
$table->startRow();
$table->addCell('Address');
$table->addCell($textinput->show());
$table->endRow();

$textinput = new textinput('group_city');
$textinput->size = 70;
$textinput->value = $group['city'];
$table->startRow();
$table->addCell('City');
$table->addCell($textinput->show());
$table->endRow();

$textinput = new textinput('group_state');
$textinput->size = 70;
$textinput->value = $group['state'];
$table->startRow();
$table->addCell('State/Province');
$table->addCell($textinput->show());
$table->endRow();

$textinput = new textinput('group_loclat');
$textinput->size = 70;
$textinput->value = $group['loclat'];
$table->startRow();
$table->addCell('Latitude');
$table->addCell($textinput->show());
$table->endRow();

$textinput = new textinput('group_loclong');
$textinput->size = 70;
$textinput->value = $group['loclong'];
$table->startRow();
$table->addCell('Longitude');
$table->addCell($textinput->show());
$table->endRow();



$table->startRow();
$objCountries = &$this->getObject('languagecode', 'language');
$table->addCell($this->objLanguage->languageText('word_country', 'system'));
if ($mode == 'addfixup') {
    $table->addCell($objCountries->countryAlpha($this->getParam('country')));
} else {
    $table->addCell($objCountries->countryAlpha());
}
$table->endRow();


$textinput = new textinput('group_postalcode');
$textinput->size = 70;
$textinput->value = $group['postalcode'];
$table->startRow();
$table->addCell('Postal Code');
$table->addCell($textinput->show());
$table->endRow();

$textinput = new textinput('group_website');
$textinput->size = 70;
$textinput->value = $group['website'];
$table->startRow();
$table->addCell('Website');
$table->addCell($textinput->show());
$table->endRow();

$editor = $this->newObject('htmlarea', 'htmlelements');
$editor->name = 'description';
$editor->height = '150px';
$editor->width = '60%';
$editor->setBasicToolBar();
$editor->setContent();
$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_description', 'unesco_oer'));
$table->addCell($editor->show());
$table->endRow();

$dd=new dropdown('group_institutionlink');
$dd->addOption('1','A');
$dd->addOption('2','B');
$dd->addOption('3','c');
$dd->addOption('4','d'); /// this must be cathed from the database
$table->startRow();
$table->addCell('Institution');
$table->addCell($dd->show());
$table->endRow();
//$form->addToForm($table->show());



$button = new button ('submitform', 'Save');
$button->setToSubmit();

$Cancelbutton = new button ('submitform', 'Cancel');
$Cancelbutton->setToSubmit();
$CancelLink = new link($this->uri(array('action' => "groupListingForm")));
$CancelLink->link =$Cancelbutton->show();


$form->addToForm($table->show());
$form->addToForm('<p align="right">'.$button->show().$CancelLink->show().'</p>');
$returnlink = new link($this->uri(array('action'=>'UserListingForm')));
$returnlink->link = 'Return to Home Page';
echo '<br clear="left" />'.$returnlink->show();

echo $form->show();
