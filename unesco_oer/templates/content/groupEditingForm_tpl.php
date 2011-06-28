
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
$this->loadClass('fieldset','htmlelements');
$objIcon = $this->newObject('geticon','htmlelements');

//Get Group details
$group=$this->objDbGroups->getGroupInfo($this->getParam('id'));

$form = new form ('editer', $this->uri(array('action'=>'editGroup','id'=>$this->getParam('id'))));
// setup and show heading
$header = new htmlheading();
$header->type = 1;
$header->str = $group[0]['name'].":"."Profile";  //objLang
echo $header->show();



//$table = $this->newObject('htmltable', 'htmlelements');
$table = $this->newObject('htmltable', 'htmlelements');
$table->width = '100%';
$table->border = '0';
$tableable->cellspacing = '0';
$table->cellpadding = '2';

//Group name
$textinput = new textinput('group_name');
$textinput->size = 70;
$textinput->value = $group[0]['name'];
$table->startRow();
$table->addCell('Group Name');
$table->addCell($textinput->show());
$table->endRow();
//group website
$textinput = new textinput('group_website');
$textinput->size = 70;
$textinput->value = $group[0]['website'];
$table->startRow();
$table->addCell('Website');
$table->addCell($textinput->show());
$table->endRow();
//group desctription
$editor = $this->newObject('htmlarea', 'htmlelements');
$editor->name = 'description';
$editor->height = '150px';
$editor->width = '75%';
$editor->setBasicToolBar();
$editor->setContent($group[0]['description']);
$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_description', 'unesco_oer'));
$table->addCell($editor->show());
$table->endRow();

$fs = new fieldset();
$fs->setLegend("Group Details"); //objLang
$fs->addContent($table->show());
echo $fs->show();


//group contact details
$tablec = $this->newObject('htmltable', 'htmlelements');
// EMAIL
$textinput = new textinput('group_email');
$textinput->size = 70;
$textinput->value = $group[0]['email'];
$tablec->startRow();
$tablec->addCell('E-mail');
$tablec->addCell($textinput->show());
$tablec->endRow();
//addressa
$textinput = new textinput('group_address');
$textinput->size = 70;
$textinput->value = $group[0]['address'];
$tablec->startRow();
$tablec->addCell('Address');
$tablec->addCell($textinput->show());
$tablec->endRow();
//CITY
$textinput = new textinput('group_city');
$textinput->size = 70;
$textinput->value = $group[0]['city'];
$tablec->startRow();
$tablec->addCell('City');
$tablec->addCell($textinput->show());
$tablec->endRow();
//STATE
$textinput = new textinput('group_state');
$textinput->size = 70;
$textinput->value = $group[0]['state'];
$tablec->startRow();
$tablec->addCell('State/Province');
$tablec->addCell($textinput->show());
$tablec->endRow();

//postal code
$textinput = new textinput('group_postalcode');
$textinput->size = 70;
$textinput->value = $group[0]['postalcode'];
$tablec->startRow();
$tablec->addCell('Postal Code');
$tablec->addCell($textinput->show());
$tablec->endRow();

$fs = new fieldset();
$fs->setLegend("Group contact details"); //objLang
$fs->addContent($tablec->show());
echo $fs->show();


//Group geographical location
$tableL = $this->newObject('htmltable', 'htmlelements');

$textinput = new textinput('group_loclat');
$textinput->size = 70;
$textinput->value = $group[0]['loclat'];
$tableL->startRow();
$tableL->addCell('Latitude');
$tableL->addCell($textinput->show());
$tableL->endRow();

$textinput = new textinput('group_loclong');
$textinput->size = 70;
$textinput->value = $group[0]['loclong'];
$tableL->startRow();
$tableL->addCell('Longitude');
$tableL->addCell($textinput->show());
$tableL->endRow();

//country
$tableL->startRow();
$objCountries = &$this->getObject('languagecode', 'language');
$tableL->addCell($this->objLanguage->languageText('word_country', 'system'));
if ($mode == 'addfixup') {
    $tableL->addCell($objCountries->countryAlpha($this->getParam('country')));
} else {
    $tableL->addCell($objCountries->countryAlpha());
}
$tableL->endRow();

$fs = new fieldset();
$fs->setLegend("Group Geographical Location"); //objLang
$fs->addContent($tableL->show());
echo $fs->show();

$tableI = $this->newObject('htmltable', 'htmlelements');
// Linked institution
// first the belonging instituion
// then the list of all the insstritution the thedatabase
$id=$this->getParam('id');
$linkedInstitution=$this->objDbGroups->getLinkedInstitution($id);
$Institutions=$this->objDbInstitution->getAllInstitutions();
$dd=new dropdown('group_institutionlink');
if(count($Institutions)>0){
     $dd->addOption('1',$linkedInstitution);
     $i=2;
     foreach ($Institutions as $Institution) {
        $dd->addOption($i,$Institution['name']);
        $i=$i+1;
        }
    }else{
         $dd->addOption('1','none');// obj lang

    }

//
//$dd=new dropdown('group_institutionlink');
//$dd->addOption('1','A');
//$dd->addOption('2','B');
//$dd->addOption('3','c');
//$dd->addOption('4','d'); /// this must be cathed from the database
$tableI->startRow();
$tableI->addCell('Institution');
$tableI->addCell($dd->show());
$tableI->endRow();

$fs = new fieldset();
$fs->setLegend("Group Linked Institutions"); //objLang
$fs->addContent($tableI->show());
echo $fs->show();


$button = new button ('submitform', 'Save');
$button->setToSubmit();

$Cancelbutton = new button ('submitform', 'Cancel');
$Cancelbutton->setToSubmit();
$CancelLink = new link($this->uri(array('action' => "groupListingForm")));
$CancelLink->link =$Cancelbutton->show();


$form->addToForm('<p align="right">'.$button->show().$CancelLink->show().'</p>');
//$returnlink = new link($this->uri(array('action'=>'UserListingForm')));
//$returnlink->link = 'Return to Home Page';
//echo '<br clear="left" />'.$returnlink->show();

echo $form->show();
