<?php
/**
 * ahis Passive Survaillance main screen Template
 *
 * Template for passive surveillance main capture screen
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
 * @version   $Id: passive_surveillance_tpl.php 13592 2009-06-02 14:00:40Z nic $
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
$objHeading->str = $this->objLanguage->languageText('phrase_passive')." ".$this->objLanguage->languageText('word_main');
$objHeading->type = 2;

$this->loadClass('textinput','htmlelements');
$this->loadClass('textarea','htmlelements');
$this->loadClass('dropdown','htmlelements');
$this->loadClass('button','htmlelements');
$this->loadClass('layer','htmlelements');

$sButton = new button('enter', $this->objLanguage->languageText('word_next'));
$sButton->setCSS('nextButton');
$sButton->setToSubmit();
$backUri = $this->uri(array('action'=>'select_officer'));
$bButton = new button('back', $this->objLanguage->languageText('word_back'), "javascript: document.location='$backUri'");
$bButton->setCSS('backButton');
$cButton = new button('clear', $this->objLanguage->languageText('word_clear'), "javascript: clearPassiveSurveillance()");
$cButton->setCSS('clearButton');

$geo2Drop = new dropdown('geo2Id');
$geo2Drop->addFromDB($arrayGeo2, 'name', 'id');
$geo2Drop->setSelected($geo2Id);
$geo2Drop->cssClass = 'passive_surveillance';
$oStatusDrop = new dropdown('oStatusId');
$oStatusDrop->addFromDB($arrayOutbreakStatus, 'name', 'id');
$oStatusDrop->setSelected($oStatusId);
$oStatusDrop->cssClass = 'passive_surveillance';
$qualityDrop = new dropdown('qualityId');
$qualityDrop->addFromDB($arrayQuality, 'name', 'id');
$qualityDrop->setSelected($qualityId);
$qualityDrop->cssClass = 'passive_surveillance';

$preparedDate = $this->newObject('datepicker','htmlelements');
$preparedDate->setName('datePrepared');
$preparedDate->setDefaultDate($datePrepared);
$IBARDate = $this->newObject('datepicker','htmlelements');
$IBARDate->setName('dateIBAR');
$IBARDate->setDefaultDate($dateIBAR);
$receivedDate = $this->newObject('datepicker','htmlelements');
$receivedDate->setName('dateReceived');
$receivedDate->setDefaultDate($dateReceived);
$isReportedDate = $this->newObject('datepicker','htmlelements');
$isReportedDate->setName('dateIsReported');
$isReportedDate->setDefaultDate($dateIsReported);

$refNoBox = new textinput('refNo', $refNo, 'text', 30);
$monthBox = new textinput('month', date('F', strtotime($calendardate)),'text', 23);
$yearBox = new textinput('year', date('Y', strtotime($calendardate)), 'text', 4);
$yearBox->extra = $monthBox->extra = $refNoBox->extra = "readonly";
//if (!$this->objUser->isAdmin()) {
    $geo2Drop->extra = 'disabled';
//}
$remarksBox = new textarea('remarks', $remarks, 4, 29);

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
$objTable->addCell($this->objLanguage->languageText('mod_ahis_monthandyear', 'openaris').":$tab");
$objTable->addCell($monthBox->show()."&nbsp; ".$yearBox->show(),NULL,'center');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('phrase_geolevel2').":$tab");
$objTable->addCell($geo2Drop->show(),NULL,'center');
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('phrase_outbreak').":$tab");
$objTable->addCell($oStatusDrop->show(),NULL,'center');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('mod_ahis_dateprepared', 'openaris').":$tab");
$objTable->addCell($preparedDate->show(),NULL,'center');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('mod_ahis_ibardate', 'openaris').":$tab");
$objTable->addCell($IBARDate->show(),NULL,'center');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('mod_ahis_dvsdate', 'openaris').":$tab");
$objTable->addCell($receivedDate->show(),NULL,'center');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('mod_ahis_isreporteddate', 'openaris').":$tab");
$objTable->addCell($isReportedDate->show(),NULL,'center');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('phrase_quality').":$tab");
$objTable->addCell($qualityDrop->show(),NULL,'center');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_remarks').":$tab");
$objTable->addCell($remarksBox->show(),NULL,'center');
$objTable->endRow();

$objTable->startRow();
$objTable->addCell('');
$objTable->addCell("&nbsp;".$bButton->show().$tab.$cButton->show().$tab.$sButton->show());
$objTable->endRow();

$this->loadClass('form','htmlelements');
$objForm = new form('reportForm', $this->uri(array('action' => 'passive_outbreak')));
$objForm->addToForm($objTable->show());
$objForm->addRule('datePrepared', $this->objLanguage->languageText('mod_ahis_valdateprepared', 'openaris'), 'datenotfuture');
$objForm->addRule('dateIBAR', $this->objLanguage->languageText('mod_ahis_valdateibar', 'openaris'), 'datenotfuture');
$objForm->addRule('dateReceived', $this->objLanguage->languageText('mod_ahis_valdatedvs', 'openaris'), 'datenotfuture');
$objForm->addRule('dateIsReported', $this->objLanguage->languageText('mod_ahis_valdateisreported', 'openaris'), 'datenotfuture');

//$objLayer = new layer();
//$objLayer->addToStr($objHeading->show()."<hr />".$objForm->show());
//$objLayer->align = 'center';

$scriptUri = $this->getResourceURI('util.js');
$this->appendArrayVar('headerParams', "<script type='text/javascript' src='$scriptUri'></script>");

//echo $objLayer->show();
echo $objHeading->show()."<br />".$objForm->show();