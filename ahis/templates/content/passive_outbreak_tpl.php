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
 * @version   $Id$
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
$objHeading->str = $this->objLanguage->languageText('phrase_passive')." ".$this->objLanguage->languageText('word_outbreak');
$objHeading->type = 2;

$this->loadClass('textinput','htmlelements');
$this->loadClass('textarea','htmlelements');
$this->loadClass('dropdown','htmlelements');
$this->loadClass('button','htmlelements');
$this->loadClass('layer','htmlelements');
$this->loadClass('form','htmlelements');

$sButton = new button('enter', $this->objLanguage->languageText('word_next'));
$sButton->setToSubmit();
$backUri = $this->uri(array('action'=>'passive_surveillance'));
$bButton = new button('back', $this->objLanguage->languageText('word_back'), "javascript: document.location='$backUri'");

$refNoBox = new textinput('refNo', $refNo);
$monthBox = new textinput('month', date('F', strtotime($calendardate)));
$yearBox = new textinput('year', date('Y', strtotime($calendardate)), 'text', 8);
$dateBox = new textinput('reportdate', date('Y/m/d', strtotime($calendardate)));
$yearBox->extra = $monthBox->extra = $dateBox->extra = $refNoBox->extra = "readonly";

$territoryDrop = new dropdown('territoryId');
$territoryDrop->addFromDB($arrayTerritory, 'name', 'id');
$territoryDrop->setSelected($territoryId);
$territoryDrop->extra = 'disabled';

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

$locationBox = new textinput('location', $location);
$latitudeBox = new textinput('latitude', $latitude);
$longitudeBox = new textinput('longitude', $longitude);
$diseaseBox = new textinput('disease', $disease);
$causitiveBox = new textinput('causitive', $causitive);

$objTable = $this->getObject('htmltable','htmlelements');
$objTable->cellspacing = 2;
$objTable->width = NULL;
$objTable->cssClass = 'min50';

$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('phrase_outbreakref').": ");
$objTable->addCell($refNoBox->show());
$objTable->addCell($this->objLanguage->languageText('word_territory').": ");
$objTable->addCell($territoryDrop->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_month').": ");
$objTable->addCell($monthBox->show());
$objTable->addCell($this->objLanguage->languageText('word_year').": ");
$objTable->addCell($yearBox->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('mod_ahis_dateprepared', 'ahis').": ");
$objTable->addCell($dateBox->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('mod_ahis_vetdate', 'ahis').": ");
$objTable->addCell($vetDate->show());
$objTable->addCell($this->objLanguage->languageText('mod_ahis_dateoccurence', 'ahis').": ");
$objTable->addCell($occurenceDate->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('mod_ahis_diagnosisdate', 'ahis').": ");
$objTable->addCell($diagnosisDate->show());
$objTable->addCell($this->objLanguage->languageText('mod_ahis_investigationdate', 'ahis').": ");
$objTable->addCell($investigationDate->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_location').": ");
$objTable->addCell($locationBox->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_latitude').": ");
$objTable->addCell($latitudeBox->show());
$objTable->addCell($this->objLanguage->languageText('word_longitude').": ");
$objTable->addCell($longitudeBox->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_disease').": ");
$objTable->addCell($diseaseBox->show());
$objTable->addCell($this->objLanguage->languageText('word_causitive').": ");
$objTable->addCell($causitiveBox->show());
$objTable->endRow();

$objTable->startRow();
$objTable->addCell('');
$objTable->addCell($bButton->show());
$objTable->addCell($sButton->show(),NULL);//,'top','right');
$objTable->addCell('');
$objTable->endRow();

$objForm = new form('reportForm', $this->uri(array('action' => 'passive_species')));
$objForm->addToForm($objTable->show());
$objForm->addRule('dateVet', $this->objLanguage->languageText('mod_ahis_valdatevet', 'ahis'), 'datenotfuture');
$objForm->addRule('dateOccurence', $this->objLanguage->languageText('mod_ahis_valdateoccurence', 'ahis'), 'datenotfuture');
$objForm->addRule('dateDiagnosis', $this->objLanguage->languageText('mod_ahis_valdatediagnosis', 'ahis'), 'datenotfuture');
$objForm->addRule('dateInvestigation', $this->objLanguage->languageText('mod_ahis_valdateinvestigation', 'ahis'), 'datenotfuture');
$objForm->addRule('location', $this->objLanguage->languageText('mod_ahis_locationreq', 'ahis'), 'required');
$objForm->addRule('disease', $this->objLanguage->languageText('mod_ahis_diseasereq', 'ahis'), 'required');
$objForm->addRule('causitive', $this->objLanguage->languageText('mod_ahis_causitivereq', 'ahis'), 'required');

$objLayer = new layer();
$objLayer->addToStr($objHeading->show()."<hr />".$objForm->show());
$objLayer->align = 'center';

echo $objLayer->show();