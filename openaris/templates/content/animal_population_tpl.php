<?php
/**
 * ahis Add Animal Population
 *
 * File containing the Add Animal Population template
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
 * @category  Chisimba
 * @package   ahis
 * @author    Patrick Kuti <pkuti@ug.edu.gh>
 * @copyright 2009 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: animal_population_tpl.php 
 * @link      http://avoir.uwc.ac.za
 */
// security check - must be included in all scripts
if (!
/**
 * Description for $GLOBALS
 * @global entry point $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check
$title = $this->objLanguage->languageText('mod_ahis_animalpopulation1','openaris');
$objHeading = $this->getObject('htmlheading','htmlelements');
$objHeading->str = $title;
$objHeading->type = 2;

$this->loadClass('textinput','htmlelements');
$this->loadClass('textarea','htmlelements');
$this->loadClass('dropdown','htmlelements');
$this->loadClass('button','htmlelements');
$this->loadClass('layer','htmlelements');
$this->loadClass('form','htmlelements');
$this->loadClass('label', 'htmlelements');

$formAction = 'animal_population_save';  
$buttonText = 'Save';

$sButton = new button('enter', $this->objLanguage->languageText('word_next'));
$sButton->setCSS('nextButton');
$sButton->setToSubmit();
$backUri = $this->uri(array('action'=>'select_officer'));
$bButton = new button('back', $this->objLanguage->languageText('word_back'), "javascript: document.location='$backUri'");
$bButton->setCSS('cancelButton');
$cButton = new button('clear', $this->objLanguage->languageText('word_clear'), "javascript: clearPassiveSurveillance()");
$cButton->setCSS('clearButton');

//buttons

$backUri = $this->uri(array('action'=>'select_officer'));
$btcancel = new button('cancel', 'Cancel', "javascript: document.location='$backUri'");
$btcancel->setCSS('cancelButton');

$classDrop = new dropdown('classification');
$classDrop->addOption('null', $this->objLanguage->languageText('mod_ahis_selectdefault', 'openaris'));
$classDrop->addFromDB($species, 'name', 'name'); 

//drop down for country
$countryDrop = new dropdown('countryId');
$countryDrop->addOption('-1', $this->objLanguage->languageText('mod_ahis_selectdefault', 'openaris'));
$countryDrop->addFromDB($arrayCountry, 'common_name', 'id');
$countryDrop->setSelected($countryId);
$countryDrop->cssClass = 'animal_population_add';

$admin1Drop = new dropdown('admin1Id');
$admin1Drop->addOption('-1', $this->objLanguage->languageText('mod_ahis_selectdefault', 'openaris'));
$admin1Drop->addFromDB($arrayAdmin1, 'name', 'id');
$admin1Drop->cssClass = 'animal_population_add';

$partitionLDrop = new dropdown('admin2Id');
$partitionLDrop ->addOption('-1', $this->objLanguage->languageText('mod_ahis_selectdefault', 'openaris'));
$partitionLDrop->addFromDB($arrayAdmin2, 'name', 'id');
$partitionLDrop->cssClass = 'animal_population_add';

$partitionNDrop = new dropdown('admin2Id');
$partitionNDrop ->addOption('-1', $this->objLanguage->languageText('mod_ahis_selectdefault', 'openaris'));
$partitionNDrop->addFromDB($arrayAdmin2, 'name', 'id');
$partitionNDrop->cssClass = 'animal_population';

$admin3Drop = new dropdown('admin3Id');
$admin3Drop->addFromDB($arrayAdmin3, 'name', 'id');


$monthBox = new textinput('month', date('F', strtotime($calendardate)), 'text', 23);
$dateBox = new textinput('reportdate', date('Y/m/d', strtotime($calendardate)),'text', 30);
$yearBox = new textinput('year', date('Y'), 'text', 4);

$repDate = $this->newObject('datepicker','htmlelements');
$repDate->setName('rDate');
$repDate->setDefaultDate($rDate);

$ibarDate=$this->newObject('datepicker', 'htmlelements');
$ibarDate->setName('iDate');
$ibarDate->setDefaultDate($iDate);


$reportOfficerDrop = new dropdown('repoff');
$reportOfficerDrop->addOption('null',$this->objLanguage->languageText('mod_ahis_selectdefault', 'openaris'));
$reportOfficerDrop->addFromDB($userList, 'name', 'name');
$reportOfficerDrop->setSelected($repoff);

//Data entry officer
$dataEntryOfficerDrop = new dropdown('dataoff',$dataoff);
$dataEntryOfficerDrop->addOption('null', $this->objLanguage->languageText('mod_ahis_selectdefault', 'openaris'));
$dataEntryOfficerDrop->addFromDB($userList, 'name', 'name');
$dataEntryOfficerDrop->setSelected($dataoff);

//Vet officer
$valOfficerDrop = new dropdown('vetoff',$vetoff);
$valOfficerDrop->addOption('null', $this->objLanguage->languageText('mod_ahis_selectdefault', 'openaris'));
$valOfficerDrop->addFromDB($userList, 'name', 'name');
$valOfficerDrop->setSelected($vetoff);



$phoneBox = new textinput('dataEntryOfficerPhone', $dataEntryOfficerPhone, 'text');
$faxBox = new textinput('dataEntryOfficerFax', $dataEntryOfficerFax, 'text');
$emailBox = new textinput('dataEntryOfficerEmail', $dataEntryOfficerEmail, 'text');

$vphoneBox = new textinput('valOfficerPhone', $valOfficerPhone, 'text');
$vfaxBox = new textinput('valOfficerFax', $valOfficerFax, 'text');
$vemailBox = new textinput('valOfficerEmail', $valOfficerEmail, 'text');

$speciesDrop = new dropdown('speciesId');
$speciesDrop->addOption('null', $this->objLanguage->languageText('mod_ahis_selectdefault', 'openaris'));
$speciesDrop->addFromDB($species, 'fullname', 'id');
$speciesDrop->setSelected($speciesId);


$breedDrop = new dropdown('breedId');
$breedDrop->addFromDB($breed, 'fullname', 'id');
$breedDrop->setSelected($breedId);



$admin3Drop = new dropdown('admin3Id');
$admin3Drop->addFromDB($arrayAdmin3, 'name', 'id');

$objTopTable = $this->newObject('htmltable', 'htmlelements');
$objTopTable->cellspacing = 2;
$objTopTable->width = NULL;

//Reporting Officer
 $tab= "&nbsp;&nbsp;&nbsp;&nbsp;";
 $tabs=$tab.$tab.$tab;

$objTopTable->startRow();
$objTopTable->addCell($this->objLanguage->languageText('mod_ahis_reportofficer','openaris'));
$objTopTable->addCell($reportOfficerDrop->show());

$objTopTable->addCell($tab.$this->objLanguage->languageText('mod_ahis_reportdate','openaris'),NULL,'centre');
$objTopTable->addCell($repDate->show());
$objTopTable->endRow();

//IBAR date
$objTopTable->startRow();
$objTopTable->addCell('&nbsp;');
$objTopTable->addCell('&nbsp;');

$objTopTable->addCell($this->objLanguage->languageText('mod_ahis_ibardate','openaris'));
$objTopTable->addCell($ibarDate->show());
$objTopTable->endRow();


$objEntryOfficerTable = $this->newObject('htmltable', 'htmlelements');
$objEntryOfficerTable->cellspacing = 2;
$objEntryOfficerTable->width = NULL;

//Data entry officer
$objEntryOfficerTable->startRow();
$objEntryOfficerTable->addCell($this->objLanguage->languageText('mod_ahis_entryofficer','openaris').": "."&nbsp;");
$objEntryOfficerTable->addCell($dataEntryOfficerDrop->show());
$objEntryOfficerTable->endRow();

$objEntryOfficerTable->startRow();
$objEntryOfficerTable->addCell($this->objLanguage->languageText('mod_ahis_phone', 'openaris').": ");
$objEntryOfficerTable->addCell($phoneBox->show());
$objEntryOfficerTable->addCell($tab.$this->objLanguage->languageText('mod_ahis_faxn','openaris').": ");
$objEntryOfficerTable->addCell($faxBox->show());
$objEntryOfficerTable->addCell($tab.$this->objLanguage->languageText('mod_ahis_email','openaris').": ");
$objEntryOfficerTable->addCell($emailBox->show());
$objEntryOfficerTable->endRow();
//vet officer
$objVetOfficerTable = $this->newObject('htmltable', 'htmlelements');
$objVetOfficerTable->cellspacing = 2;
$objVetOfficerTable->width = NULL;

$objVetOfficerTable->startRow();
$objVetOfficerTable->addCell($this->objLanguage->languageText('mod_ahis_vofficer','openaris').": ".$tabs);
$objVetOfficerTable->addCell($valOfficerDrop->show());
$objVetOfficerTable->endRow();

$objVetOfficerTable->startRow();
$objVetOfficerTable->addCell($this->objLanguage->languageText('mod_ahis_phone', 'openaris').": ");
$objVetOfficerTable->addCell($vphoneBox->show());
$objVetOfficerTable->addCell($tab.$this->objLanguage->languageText('mod_ahis_faxn','openaris').": ");
$objVetOfficerTable->addCell($vfaxBox->show());
$objVetOfficerTable->addCell($tab.$this->objLanguage->languageText('mod_ahis_email','openaris').": ");
$objVetOfficerTable->addCell($vemailBox->show());
$objVetOfficerTable->endRow();

$objBottomTable = $this->newObject('htmltable', 'htmlelements');
$objBottomTable->cellspacing = 2;
$objBottomTable->width = NULL;

$objBottomTable->startRow();
$objBottomTable->addCell($this->objLanguage->languageText('word_country').": ");
$objBottomTable->addCell($countryDrop->show(),NULL,'center');
$objBottomTable->addCell($tab.$this->objLanguage->languageText('mod_ahis_partitions', 'openaris'));
$objBottomTable->addCell($admin1Drop->show(),NULL,'center');
$objBottomTable->addCell($tab.$this->objLanguage->languageText('mod_ahis_word_species', 'openaris'));
$objBottomTable->addCell($classDrop->show());
$objBottomTable->endRow();

$objBottomTable->startRow();
$objBottomTable->addCell($this->objLanguage->languageText('word_year').": ");
$objBottomTable->addCell($yearBox->show(),NULL,'center');
$objBottomTable->addCell($tab.$this->objLanguage->languageText('mod_ahis_partitionlevel', 'openaris'));
$objBottomTable->addCell($partitionLDrop->show(),NULL,'center');
$objBottomTable->addCell($tab.$this->objLanguage->languageText('word_breed'));
$objBottomTable->addCell($breedDrop->show(),NULL,'center');
$objBottomTable->endRow();

$objBottomTable->startRow();$objBottomTable->addCell($this->objLanguage->languageText('mod_ahis_partitionname', 'openaris'));
$objBottomTable->addCell($partitionNDrop->show(),NULL,'center');
//animal production
$label = new label ('Animal Production:', ' input_production');
$production = new dropdown('animal_production');

$production->addFromDB($animprod, 'name','name');$objBottomTable->addCell($tab.$label->show());
$objBottomTable->addCell($production->show());
$objBottomTable->endRow();	

$objButtonTable = $this->newObject('htmltable','htmlelements');
$objButtonTable->cellspacing = 2;
$objButtonTable->width = '40%';
$objButtonTable->startRow();
$objButtonTable->addCell($bButton->show(), NULL, 'top', 'center');
$objButtonTable->addCell($sButton->show(), NULL, 'top', 'center');
$objButtonTable->endRow();

// Create Form
$content=$objTopTable->show()."<hr />".$objEntryOfficerTable->show()."<hr /> ".$objVetOfficerTable->show()."<hr /> ".$objBottomTable->show()."<br />".$objButtonTable->show();
$form = new form ('add', $this->uri(array('action'=>'animal_population1')));
$form->addToForm($content);
$form->addRule('num_animals', 'Please enter number of animals', 'required');
$form->addRule('num_animals', 'Please enter valid number ', 'numeric');
$form->addRule('source', 'Please enter source of animals', 'required');
$form->addRule('source', 'Please enter valid source', 'nonnumeric');


//$form->addToForm($btcancel->show().$tab);
//$form->addToForm($sButton->show());
$scriptUri = $this->getResourceURI('util.js');
$this->appendArrayVar('headerParams', "<script type='text/javascript' src='$scriptUri'></script>");

$objLayer = new layer();
$objLayer->addToStr($objHeading->show()."<hr class='openaris' />".$form->show());

echo $objLayer->show();
?>