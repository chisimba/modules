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
//clear button
$cButton = $this->uri(array('action'=>'animal_population_clear'));
//$cButton = new button('clear', $this->objLanguage->languageText('word_clear'), "javascript: clearAnimalPopulation()");
$cButton = new button('clear', $this->objLanguage->languageText('word_clear'), "javascript: document.location='$clearButton'");
$cButton->setCSS('clearButton');

//buttons

$backUri = $this->uri(array('action'=>'select_officer'));
$btcancel = new button('cancel', 'Cancel', "javascript: document.location='$backUri'");
$btcancel->setCSS('cancelButton');

$classDrop = new dropdown('classification');
$classDrop->addOption('-1', $this->objLanguage->languageText('mod_ahis_selectdefault', 'openaris'));
$classDrop->addFromDB($arrayspecies, 'name', 'id');
$classDrop->setSelected($species); 
$classDrop->extra = 'onchange="javascript:changeBreed();"';


$breedDrop = new dropdown('breedId');
$breedDrop->addOption('-1', $this->objLanguage->languageText('mod_ahis_selectdefault', 'openaris'));
$breedDrop->addFromDB($arraybreed, 'name', 'id');
$breedDrop->setSelected($breed);

//drop down for country
$countryDrop = new dropdown('countryId');
$countryDrop->addOption('-1', $this->objLanguage->languageText('mod_ahis_selectdefault', 'openaris'));
$countryDrop->addFromDB($arrayCountry, 'common_name', 'id');
$countryDrop->setSelected($country);
$countryDrop->cssClass = 'animal_population_add';
$countryDrop->extra = 'onchange="javascript:changeNames();"';

$admin1Drop = new dropdown('partitionTypeId');
$admin1Drop->addOption('-1', $this->objLanguage->languageText('mod_ahis_selectdefault', 'openaris'));
$admin1Drop->addFromDB($arrayAdmin1, 'partitioncategory', 'id');
$admin1Drop->setSelected($admin1);
$admin1Drop->cssClass = 'animal_population_add';
$admin1Drop->extra = 'onchange="javascript:changePartitionType();"';


$partitionLDrop = new dropdown('partitionLevelId');
$partitionLDrop ->addOption('-1', $this->objLanguage->languageText('mod_ahis_selectdefault', 'openaris'));
$partitionLDrop->addFromDB($arrayAdmin2, 'partitionlevel', 'id');
$partitionLDrop->setSelected($admin2);
$partitionLDrop->cssClass = 'animal_population_add';
$partitionLDrop->extra = 'onchange="javascript:changeNames();"';

$partitionNDrop = new dropdown('partitionId');
$partitionNDrop ->addOption('-1', $this->objLanguage->languageText('mod_ahis_selectdefault', 'openaris'));
$partitionNDrop->addFromDB($arrayAdmin3, 'partitionname', 'id');
$partitionNDrop->setSelected($admin3);
$partitionNDrop->cssClass = 'animal_population';
$partitionNDrop->extra = 'onchange="javascript:changeNames();"';

$admin3Drop = new dropdown('admin3Id');
$admin3Drop->addFromDB($arrayAdmin3, 'name', 'id');


//$monthBox = new textinput('month', date('F', strtotime($calendardate)), 'text', 23);
$dateBox = new textinput('reportdate', date('Y/m/d', strtotime($calendardate)),'text', 30);
$yearBox = new textinput('year', date('Y'), 'text', 4);

$repDate = $this->newObject('datepicker','htmlelements');
$repDate->setName('rDate');
$repDate->setDefaultDate($rDate);

$ibarDate=$this->newObject('datepicker', 'htmlelements');
$ibarDate->setName('iDate');
$ibarDate->setDefaultDate($iDate);


$reportOfficerDrop = new dropdown('repOfficerId');
$reportOfficerDrop->addOption('-1',$this->objLanguage->languageText('mod_ahis_selectdefault', 'openaris'));
$reportOfficerDrop->addFromDB($arrayrepoff, 'name', 'userid');
$reportOfficerDrop->setSelected($repoff);
$reportOfficerDrop->extra = 'onchange = \'javascript:getOfficerInfo("rep");\'';
//Data entry officer
$dataEntryOfficerDrop = new dropdown('dataOfficerId');
$dataEntryOfficerDrop->addOption('-1', $this->objLanguage->languageText('mod_ahis_selectdefault', 'openaris'));
$dataEntryOfficerDrop->addFromDB($arraydataoff, 'name', 'userid');
$dataEntryOfficerDrop->setSelected($dataoff);
$dataEntryOfficerDrop->extra = 'onchange = \'javascript:getOfficerInfo("data");\'';
//Vet officer
$valOfficerDrop = new dropdown('vetOfficerId');
$valOfficerDrop->addOption('-1', $this->objLanguage->languageText('mod_ahis_selectdefault', 'openaris'));
$valOfficerDrop->addFromDB($arrayvetoff, 'name', 'userid');
$valOfficerDrop->setSelected($vetoff);
$valOfficerDrop->extra = 'onchange = \'javascript:getOfficerInfo("vet");\'';


$phoneBox = new textinput('dataOfficerTel', $dphone, 'text');
$phoneBox->extra = 'disabled';
$faxBox = new textinput('dataOfficerFax', $dfax, 'text');
$faxBox->extra = 'disabled';
$emailBox = new textinput('dataOfficerEmail', $demail, 'text');
$emailBox->extra = 'disabled';

$vphoneBox = new textinput('vetOfficerTel', $vphone, 'text');
$vphoneBox->extra = 'disabled';
$vfaxBox = new textinput('vetOfficerFax', $vfax, 'text');
$vfaxBox->extra = 'disabled';
$vemailBox = new textinput('vetOfficerEmail', $vemail, 'text');
$vemailBox->extra = 'disabled';



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
$objBottomTable->addCell($tab.$this->objLanguage->languageText('mod_ahis_productiontype', 'openaris'));
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

$objBottomTable->startRow();
$objBottomTable->addCell('&nbsp;');
$objBottomTable->addCell('&nbsp;');$objBottomTable->addCell($this->objLanguage->languageText('mod_ahis_partitionname', 'openaris'));
$objBottomTable->addCell($partitionNDrop->show(),NULL,'center');
//animal production
$objBottomTable->addCell($this->objLanguage->LanguageText('mod_ahis_prodname','openaris'));
//$label = new label ('Animal Production:', ' input_production');
$production = new textinput('animal_production',$prodname);//$objBottomTable->addCell($tab.$label->show());
$objBottomTable->addCell($production->show());
$objBottomTable->endRow();	

$objButtonTable = $this->newObject('htmltable','htmlelements');
$objButtonTable->cellspacing = 2;
$objButtonTable->width = '40%';
$objButtonTable->startRow();
$objButtonTable->addCell($bButton->show(), NULL, 'top', 'center');
$objButtonTable->addCell($cButton->show(), NULL, 'top', 'center');
$objButtonTable->addCell($sButton->show(), NULL, 'top', 'center');
$objButtonTable->endRow();

// Create Form
$content=$objTopTable->show()."<hr />".$objEntryOfficerTable->show()."<hr /> ".$objVetOfficerTable->show()."<hr /> ".$objBottomTable->show()."<br />".$objButtonTable->show();
$form = new form ('add', $this->uri(array('action'=>'animal_population1')));
$form->addToForm($content);
//$form->addRule('repOfficerId', $this->objLanguage->languageText('mod_ahis_valreportofficer', 'openaris'), 'select');
//$form->addRule('dataOfficerId', $this->objLanguage->languageText('mod_ahis_valentryofficer', 'openaris'), 'select');
//$form->addRule('vetOfficerId', $this->objLanguage->languageText('mod_ahis_valvalidationofficer', 'openaris'), 'select');
$form->addRule('year', $this->objLanguage->languageText('mod_ahis_promptyear', 'openaris'), 'required');
//$form->addRule(array('month'=>'month','year'=>'year'), $this->objLanguage->languageText('mod_ahis_valdate', 'openaris'), 'twofielddate');
$form->addRule('countryId', $this->objLanguage->languageText('mod_ahis_valcountry', 'openaris'), 'select');
$form->addRule('partitionTypeId', $this->objLanguage->languageText('mod_ahis_valparttype', 'openaris'), 'select');
$form->addRule('classification', $this->objLanguage->languageText('mod_ahis_valspecies', 'openaris'), 'select');
$form->addRule('breedId', $this->objLanguage->languageText('mod_ahis_valbreed', 'openaris'), 'select');
$form->addRule('animal_production', $this->objLanguage->languageText('mod_ahis_valprodname', 'openaris'), 'required');

$scriptUri = $this->getResourceURI('util.js');
$this->appendArrayVar('headerParams', "<script type='text/javascript' src='$scriptUri'></script>");

$objLayer = new layer();
$objLayer->addToStr($objHeading->show()."<hr class='openaris' />".$form->show());

echo $objLayer->show();
?>