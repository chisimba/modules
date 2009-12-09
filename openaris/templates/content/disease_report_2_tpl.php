<?php
/**
 * ahis Passive Surveillance Outbreak Template
 *
 * Template for capturing passive surveillance outbreak data
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
 * @author    Nic Appleby <nappleby@uwc.ac.za>
 * @copyright 2009 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: passive_outbreak_tpl.php 13733 2009-06-23 11:04:26Z nic $
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

$objHeading = $this->getObject('htmlheading','htmlelements');
$objHeading->str = $this->objLanguage->languageText('mod_ahis_diseasereport', 'openaris')." #2";
$objHeading->type = 2;

$this->loadClass('textinput','htmlelements');
$this->loadClass('fieldset','htmlelements');
$this->loadClass('dropdown','htmlelements');
$this->loadClass('button','htmlelements');
$this->loadClass('form','htmlelements');

$tab = "&nbsp;&nbsp;&nbsp;&nbsp;";

$objTableArea1 = $this->getObject('htmltable','htmlelements');
$objTableArea1->cellspacing = 2;
$objTableArea1->width = NULL;

$objTableArea1->startHeaderRow();
$objTableArea1->addHeaderCell($this->objLanguage->languageText('mod_ahis_outbreakcode', 'openaris'));
$objTableArea1->addHeaderCell($this->objLanguage->languageText('mod_ahis_partitiontype'));
$objTableArea1->addHeaderCell($this->objLanguage->languageText('mod_ahis_partitionlevel', 'openaris'));
$objTableArea1->addHeaderCell($this->objLanguage->languageText('mod_ahis_partitionname', 'openaris'));
$objTableArea1->addHeaderCell($this->objLanguage->languageText('word_year'));
$objTableArea1->addHeaderCell($this->objLanguage->languageText('word_month'));
$objTableArea1->endHeaderRow();

$outbreakCodeBox = new textinput('outbreakCode', $outbreakCode);
$outbreakCodeBox->extra = 'disabled';
$outbreakCodeBox->setCss('passive_surveillance');

$latitudeBox = new textinput('latitude', $latitude);
$latitudeBox->setCss('geo');
$longitudeBox = new textinput('longitude', $longitude);
$longitudeBox->setCss('geo');

$latDirecDrop = new dropdown('latDirec');
$latDirecDrop->addOption('N', 'N');
$latDirecDrop->addOption('S', 'S');
$latDirecDrop->setSelected($latDirec);

$longDirecDrop = new dropdown('longDirec');
$longDirecDrop->addOption('E', 'E');
$longDirecDrop->addOption('W', 'W');
$longDirecDrop->setSelected($longDirec);

$localityNameBox = new textinput('localityName', $localityName);
$localityNameBox->setCss('passive_surveillance');

$localityTypeDrop = new dropdown('localityTypeId');
$localityTypeDrop->addFromDB($arrayLocalityType, 'locality_type', 'id');
$localityTypeDrop->setSelected($localityTypeId);
$localityTypeDrop->cssClass = 'passive_surveillance';

$farmingSystemDrop = new dropdown('farmingSystemId');
$farmingSystemDrop->addFromDB($arrayFarmingSystem, 'farmingsystem', 'id');
$farmingSystemDrop->setSelected($farmingSystemId);
$farmingSystemDrop->cssClass = 'passive_surveillance';

$createdBox = new textinput('createdBy', $createdBy, 'text');
$createdBox->setCss('passive_surveillance');
$createdDateBox = new textinput('createdDate', $createdDate, 'text');
$createdDateBox->setCss('passive_surveillance');
$modifiedBox = new textinput('modifiedBy', $modifiedBy, 'text');
$modifiedBox->setCss('passive_surveillance');
$modifiedDateBox = new textinput('modifiedDate', $modifiedDate, 'text');
$modifiedDateBox->setCss('passive_surveillance');
$createdBox->extra = $createdDateBox->extra = $modifiedBox->extra = $modifiedDateBox->extra = 'disabled';

$objTableArea2 = $this->newObject('htmltable','htmlelements');
$objTableArea2->cellspacing = 2;
$objTableArea2->width = NULL;

$objTableArea2->startRow();
$objTableArea2->addCell($this->objLanguage->languageText('mod_ahis_outbreakcode', 'openaris'));
$objTableArea2->addCell($outbreakCodeBox->show());
$objTableArea2->addCell($this->objLanguage->languageText('word_latitude'));
$objTableArea2->addCell($latitudeBox->show()." ".$latDirecDrop->show());
$objTableArea2->addCell($this->objLanguage->languageText('word_createdby'));
$objTableArea2->addCell($createdBox->show());
$objTableArea2->endRow();
$objTableArea2->startRow();
$objTableArea2->addCell($this->objLanguage->languageText('mod_ahis_localitytype', 'openaris'));
$objTableArea2->addCell($localityTypeDrop->show());
$objTableArea2->addCell($this->objLanguage->languageText('word_longitude'));
$objTableArea2->addCell($longitudeBox->show()." ".$longDirecDrop->show());
$objTableArea2->addCell($this->objLanguage->languageText('phrase_createddate'));
$objTableArea2->addCell($createdDateBox->show());
$objTableArea2->endRow();
$objTableArea2->startRow();
$objTableArea2->addCell($this->objLanguage->languageText('mod_ahis_localityname', 'openaris'));
$objTableArea2->addCell($localityNameBox->show());
$objTableArea2->addCell($this->objLanguage->languageText('phrase_farmingsystem'));
$objTableArea2->addCell($farmingSystemDrop->show());
$objTableArea2->addCell($this->objLanguage->languageText('word_modifiedby'));
$objTableArea2->addCell($modifiedBox->show());
$objTableArea2->endRow();
$objTableArea2->startRow();
$objTableArea2->addCell($this->objLanguage->languageText('phrase_modifieddate'), NULL, 'top', NULL, NULL, 'colspan="5"');
$objTableArea2->addCell($modifiedDateBox->show());
$objTableArea2->endRow();

$localitySet = new fieldset('localitySet');
$localitySet->setLegend($this->objLanguage->languageText('mod_ahis_localitydataentry', 'openaris'));
$localitySet->addContent($objTableArea2->show());

$objForm = new form('reportForm', $this->uri(array('action' => 'passive_species')));
$objForm->addToForm($localitySet->show());

$objTableArea3 = $this->newObject('htmltable','htmlelements');
$objTableArea3->cellspacing = 2;
$objTableArea3->width = NULL;

$objTableArea3->startHeaderRow();
$objTableArea3->addHeaderCell($this->objLanguage->languageText('mod_ahis_outbreakcode', 'openaris'));
$objTableArea3->addHeaderCell($this->objLanguage->languageText('mod_ahis_localitytype', 'openaris'));
$objTableArea3->addHeaderCell($this->objLanguage->languageText('mod_ahis_localityname', 'openaris'));
$objTableArea3->addHeaderCell($this->objLanguage->languageText('word_latitude'));
$objTableArea3->addHeaderCell($this->objLanguage->languageText('word_direction'));
$objTableArea3->addHeaderCell($this->objLanguage->languageText('word_longitude'));
$objTableArea3->addHeaderCell($this->objLanguage->languageText('word_direction'));
$objTableArea3->addHeaderCell($this->objLanguage->languageText('phrase_farmingsystem'));
$objTableArea3->addHeaderCell($this->objLanguage->languageText('phrase_createdby'));
$objTableArea3->endHeaderRow();


$scriptUri = $this->getResourceURI('util.js');
$this->appendArrayVar('headerParams', "<script type='text/javascript' src='$scriptUri'></script>");

$content = $objTableArea1->show()."<hr />".$objForm->show()."<hr />".$objTableArea3->show();
echo $objHeading->show()."<br />".$content;

/*$sButton = new button('enter', $this->objLanguage->languageText('word_next'));
$sButton->setToSubmit();
$sButton->setCSS('nextButton');
$backUri = $this->uri(array('action'=>'passive_surveillance'));
$bButton = new button('back', $this->objLanguage->languageText('word_back'), "javascript: document.location='$backUri'");
$bButton->setCSS('backButton');
$cButton = new button('clear', $this->objLanguage->languageText('word_clear'), "javascript: clearPassiveOutbreak()");
$cButton->setCSS('clearButton');

$refNoBox = new textinput('refNo', $refNo, 'text', 30);
$monthBox = new textinput('month', date('F', strtotime($calendardate)), 'text', 23);
$yearBox = new textinput('year', date('Y', strtotime($calendardate)), 'text', 4);
$dateBox = new textinput('reportdate', date('Y/m/d', strtotime($calendardate)),'text', 30);
$yearBox->extra = $monthBox->extra = $dateBox->extra = $refNoBox->extra = "readonly";

$geo2Drop = new dropdown('geo2Id');
$geo2Drop->addFromDB($arrayGeo2, 'name', 'id');
$geo2Drop->setSelected($geo2Id);
$geo2Drop->extra = 'disabled';
$geo2Drop->cssClass = "passive_surveillance";

$locationDrop = new dropdown('locationId');
$locationDrop->addFromDB($arrayLocation, 'name', 'id');
$locationDrop->setSelected($locationId);
$locationDrop->cssClass = "passive_surveillance";
$diseaseDrop = new dropdown('diseaseId');
$diseaseDrop->addFromDB($arrayDisease, 'name', 'id');
$diseaseDrop->setSelected($diseaseId);
$diseaseDrop->cssClass = "passive_surveillance";
$causativeDrop = new dropdown('causativeId');
$causativeDrop->addFromDB($arrayCausative, 'name', 'id');
$causativeDrop->setSelected($causativeId);
$causativeDrop->cssClass = "passive_surveillance";

$vetDate = $this->newObject('datepicker','htmlelements');
$vetDate->setName('dateVet');
$vetDate->setDefaultDate($dateVet);
$occurenceDate = $this->newObject('datepicker','htmlelements');
$occurenceDate->setName('dateOccurence');
$occurenceDate->setDefaultDate($dateOccurence);
$diagnosisDate = $this->newObject('datepicker','htmlelements');
$diagnosisDate->setName('dateDiagnosis');
$diagnosisDate->setDefaultDate($dateDiagnosis);
$investigationDate = $this->newObject('datepicker','htmlelements');
$investigationDate->setName('dateInvestigation');
$investigationDate->setDefaultDate($dateInvestigation);

$latitudeDegBox = new textinput('latdeg', $latdeg, 'text', 4);
$longitudeDegBox = new textinput('longdeg', $longdeg, 'text', 4);
$latitudeMinBox = new textinput('latmin', $latmin, 'text', 4);
$longitudeMinBox = new textinput('longmin', $longmin, 'text', 4);
$latDrop = new dropdown('latdirection');
$latDrop->addOption('N','N');
$latDrop->addOption('S','S');
$latDrop->setSelected($latdirec);
$longDrop = new dropdown('longdirection');
$longDrop->addOption('E','E');
$longDrop->addOption('W','W');
$longDrop->setSelected($longdirec);
$degrees = $this->objLanguage->languageText('word_degrees');
$minutes = $this->objLanguage->languageText('word_minutes');

$objTable = $this->getObject('htmltable','htmlelements');
$objTable->cellspacing = 2;
$objTable->width = NULL;
//$objTable->cssClass = 'min50';
$tab = "&nbsp;&nbsp;&nbsp;&nbsp;";

$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('phrase_outbreakref').":$tab");
$objTable->addCell($refNoBox->show(),NULL,'center');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('phrase_geolevel2').":$tab");
$objTable->addCell($geo2Drop->show(),NULL,'center');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('mod_ahis_monthandyear', 'openaris').":$tab");
$objTable->addCell($monthBox->show()."&nbsp; ".$yearBox->show(),NULL,'center');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('mod_ahis_dateprepared', 'openaris').":$tab");
$objTable->addCell($dateBox->show(),NULL,'center');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('mod_ahis_vetdate', 'openaris').":$tab");
$objTable->addCell($vetDate->show(),NULL,'center');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('mod_ahis_dateoccurence', 'openaris').":$tab");
$objTable->addCell($occurenceDate->show(),NULL,'center');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('mod_ahis_diagnosisdate', 'openaris').":$tab");
$objTable->addCell($diagnosisDate->show(),NULL,'center');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('mod_ahis_investigationdate', 'openaris').":$tab");
$objTable->addCell($investigationDate->show(),NULL,'center');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_location').":$tab");
$objTable->addCell($locationDrop->show(),NULL,'center');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_latitude').":$tab");
$objTable->addCell("$degrees: ".$latitudeDegBox->show()." $minutes: ".$latitudeMinBox->show().$latDrop->show(),NULL,'center');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_longitude').":$tab");
$objTable->addCell("$degrees: ".$longitudeDegBox->show()." $minutes: ".$longitudeMinBox->show().$longDrop->show(),NULL,'center');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_disease').":$tab");
$objTable->addCell($diseaseDrop->show(),NULL,'center');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_causative').":$tab");
$objTable->addCell($causativeDrop->show(),NULL,'center');
$objTable->endRow();

$objTable->startRow();
$objTable->addCell('');
$objTable->addCell("&nbsp;".$bButton->show().$tab.$cButton->show().$tab.$sButton->show());
$objTable->endRow();

$objForm = new form('reportForm', $this->uri(array('action' => 'passive_species')));
$objForm->addToForm($objTable->show());
$objForm->addRule('dateVet', $this->objLanguage->languageText('mod_ahis_valdatevet', 'openaris'), 'datenotfuture');
$objForm->addRule('dateOccurence', $this->objLanguage->languageText('mod_ahis_valdateoccurence', 'openaris'), 'datenotfuture');
$objForm->addRule('dateDiagnosis', $this->objLanguage->languageText('mod_ahis_valdatediagnosis', 'openaris'), 'datenotfuture');
$objForm->addRule('dateInvestigation', $this->objLanguage->languageText('mod_ahis_valdateinvestigation', 'openaris'), 'datenotfuture');
$objForm->addRule('latdeg', $this->objLanguage->languageText('mod_ahis_vallatitude', 'openaris'), 'numeric');
$objForm->addRule('longdeg', $this->objLanguage->languageText('mod_ahis_vallongitude', 'openaris'), 'numeric');
$objForm->addRule('latmin', $this->objLanguage->languageText('mod_ahis_vallatitude', 'openaris'), 'numeric');
$objForm->addRule('longmin', $this->objLanguage->languageText('mod_ahis_vallongitude', 'openaris'), 'numeric');
*/