<?php
/**
 * ahis Passive Surveillance Vaccine Template
 *
 * Template for capturing passive surveillance vaccine data
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
$objHeading->str = $this->objLanguage->languageText('phrase_passive')." ".$this->objLanguage->languageText('word_vaccine');
$objHeading->type = 2;

$this->loadClass('textinput','htmlelements');
$this->loadClass('checkbox','htmlelements');
$this->loadClass('dropdown','htmlelements');
$this->loadClass('button','htmlelements');
$this->loadClass('layer','htmlelements');
$this->loadClass('form','htmlelements');

$sButton = new button('enter', $this->objLanguage->languageText('word_finish'));
$sButton->setToSubmit();
$sButton->setCSS('submitButton');
$backUri = $this->uri(array('action'=>'passive_species'));
$bButton = new button('back', $this->objLanguage->languageText('word_back'), "javascript: document.location='$backUri'");
$bButton->setCSS('backButton');
$cButton = new button('clear', $this->objLanguage->languageText('word_clear'), "javascript: clearPassiveVaccine()");
$cButton->setCSS('cancelButton');

$refNoBox = new textinput('refNo', $refNo);
$monthBox = new textinput('month', date('F', strtotime($calendardate)));
$yearBox = new textinput('year', date('Y', strtotime($calendardate)), 'text', 8);
$yearBox->extra = $monthBox->extra = $refNoBox->extra = "readonly";

$geo2Drop = new dropdown('geo2Id');
$geo2Drop->addFromDB($arrayGeo2, 'name', 'id');
$geo2Drop->setSelected($geo2Id);
$geo2Drop->extra = 'disabled';

$manufactureDate = $this->newObject('datepicker','htmlelements');
$manufactureDate->setName('dateManufactured');
$manufactureDate->setDefaultDate(date('Y-m-d'));
$expireDate = $this->newObject('datepicker','htmlelements');
$expireDate->setName('dateExpire');
$expireDate->setDefaultDate(date('Y-m-d'));

$sourceBox = new textinput('source');
$batchBox = new textinput('batch');

$panvacCheck = new checkbox('panvac');

$objTable = $this->getObject('htmltable','htmlelements');
$objTable->cellspacing = 2;
$objTable->width = NULL;
$objTable->cssClass = 'min50';

$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('phrase_outbreakref').": ");
$objTable->addCell($refNoBox->show());
$objTable->addCell($this->objLanguage->languageText('phrase_geolevel2').": ");
$objTable->addCell($geo2Drop->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_month').": ");
$objTable->addCell($monthBox->show());
$objTable->addCell($this->objLanguage->languageText('word_year').": ");
$objTable->addCell($yearBox->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('mod_ahis_vacsource', 'ahis').": ");
$objTable->addCell($sourceBox->show());
$objTable->addCell($this->objLanguage->languageText('mod_ahis_batch', 'ahis').": ");
$objTable->addCell($batchBox->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('mod_ahis_manufacturedate', 'ahis').": ");
$objTable->addCell($manufactureDate->show());
$objTable->addCell($this->objLanguage->languageText('mod_ahis_expiredate', 'ahis').": ");
$objTable->addCell($expireDate->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('mod_ahis_panvactested', 'ahis').": ");
$objTable->addCell($panvacCheck->show());
$objTable->endRow();

$objTable->startRow();
$objTable->addCell('');
$objTable->addCell($bButton->show());
$objTable->addCell($cButton->show());
$objTable->addCell($sButton->show(), NULL, 'top', 'right');
$objTable->addCell('');
$objTable->endRow();

$objForm = new form('reportForm', $this->uri(array('action' => 'passive_save')));
$objForm->addToForm($objTable->show());
$objForm->addRule('dateManufactured', $this->objLanguage->languageText('mod_ahis_valdatemanufactured', 'ahis'), 'datenotfuture');
$objForm->addRule('source', $this->objLanguage->languageText('mod_ahis_valvacsourcerequired', 'ahis'), 'required');
$objForm->addRule('batch', $this->objLanguage->languageText('mod_ahis_valvacbatchrequired', 'ahis'), 'required');

$objLayer = new layer();
$objLayer->addToStr($objHeading->show()."<hr />".$objForm->show());
$objLayer->align = 'center';

$scriptUri = $this->getResourceURI('util.js');
$this->appendArrayVar('headerParams', "<script type='text/javascript' src='$scriptUri'></script>");

echo $objLayer->show();