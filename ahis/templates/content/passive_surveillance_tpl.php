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
$objHeading->str = $this->objLanguage->languageText('phrase_passive')." ".$this->objLanguage->languageText('word_main');
$objHeading->type = 2;

$this->loadClass('textinput','htmlelements');
$this->loadClass('textarea','htmlelements');
$this->loadClass('dropdown','htmlelements');
$this->loadClass('button','htmlelements');
$this->loadClass('layer','htmlelements');

$sButton = new button('enter', $this->objLanguage->languageText('word_next'));
$sButton->setToSubmit();
$backUri = $this->uri(array('action'=>'select_officer'));
$bButton = new button('back', $this->objLanguage->languageText('word_back'), "javascript: document.location='$backUri'");

$territoryDrop = new dropdown('territoryId');
$territoryDrop->addFromDB($arrayTerritory, 'name', 'id');
$territoryDrop->setSelected($territoryId);
$oStatusDrop = new dropdown('oStatusId');
$oStatusDrop->addFromDB($arrayOutbreakStatus, 'name', 'id');
$oStatusDrop->setSelected($oStatusId);
$qualityDrop = new dropdown('qualityId');
$qualityDrop->addFromDB($arrayQuality, 'name', 'id');
$qualityDrop->setSelected($qualityId);

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

$refNoBox = new textinput('refNo', $refNo);
$monthBox = new textinput('month', date('F', strtotime($calendardate)));
$yearBox = new textinput('year', date('Y', strtotime($calendardate)));
$yearBox->extra = $monthBox->extra = $refNoBox->extra = "readonly";
//if (!$this->objUser->isAdmin()) {
    $territoryDrop->extra = 'disabled';
//}
$remarksBox = new textarea('remarks', $remarks, 4, 40);

$objTable = $this->getObject('htmltable','htmlelements');
$objTable->cellspacing = 2;
$objTable->width = NULL;
$objTable->cssClass = 'min50';

$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('phrase_outbreakref').": ");
$objTable->addCell($refNoBox->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_month').": ");
$objTable->addCell($monthBox->show());
$objTable->addCell($this->objLanguage->languageText('word_year').": ");
$objTable->addCell($yearBox->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_territory').": ");
$objTable->addCell($territoryDrop->show());
$objTable->addCell($this->objLanguage->languageText('phrase_outbreak').": ");
$objTable->addCell($oStatusDrop->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('mod_ahis_dateprepared', 'ahis').": ");
$objTable->addCell($preparedDate->show());
$objTable->addCell($this->objLanguage->languageText('mod_ahis_ibardate', 'ahis').": ");
$objTable->addCell($IBARDate->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('mod_ahis_dvsdate', 'ahis').": ");
$objTable->addCell($receivedDate->show());
$objTable->addCell($this->objLanguage->languageText('mod_ahis_isreporteddate', 'ahis').": ");
$objTable->addCell($isReportedDate->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('phrase_quality').": ");
$objTable->addCell($qualityDrop->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_remarks').": ");
$objTable->addCell($remarksBox->show());
$objTable->endRow();

$objTable->startRow();
$objTable->addCell('');
$objTable->addCell($bButton->show());
$objTable->addCell($sButton->show(),NULL);//,'top','right');
$objTable->addCell('');
$objTable->endRow();

$this->loadClass('form','htmlelements');
$objForm = new form('reportForm', $this->uri(array('action' => 'passive_outbreak')));
$objForm->addToForm($objTable->show());

$objLayer = new layer();
$objLayer->addToStr($objHeading->show()."<hr />".$objForm->show());
$objLayer->align = 'center';

echo $objLayer->show();