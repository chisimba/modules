<?php
/**
 * ahis select_officer_tpl Template
 *
 * Template to select passive outbreak reporting officer
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
 * @copyright 2008 AVOIR
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
$objHeading->str = $this->objLanguage->languageText('mod_ahis_selectofficer','ahis');
$objHeading->type = 2;

$this->loadClass('textinput','htmlelements');
$this->loadClass('dropdown','htmlelements');
$this->loadClass('layer','htmlelements');

if ($feedback) {
    $timeout = $this->getObject('timeoutmessage', 'htmlelements');
    $timeout->setMessage($this->objLanguage->languageText('mod_ahis_added', 'ahis'));
    $msg = $timeout->show();
} else {
    $msg = '';
}

$inputOfficer = new dropdown('officerId');
$inputOfficer->addFromDB($userList, 'name', 'userid');
$inputOfficer->setSelected($officerId);
if (!$this->objUser->isAdmin()) {
    $inputOfficer->extra = 'disabled';
    $hiddenOfficer = new textinput('officerId', $officerId, 'hidden');
    $inputOfficer = $inputOfficer->show().$hiddenOfficer->show();
} else {
    $inputOfficer = $inputOfficer->show();
}

$inputGeo2 = new dropdown('geo2Id');
$inputGeo2->addFromDB($this->objGeo2->getAll("ORDER BY name"), 'name', 'id');
$inputGeo2->setSelected($geo2Id);
if (!$this->objUser->isAdmin()) {
    $inputGeo2->extra = 'disabled';
    $hiddenGeo2 = new textinput('geo2Id', $geo2Id, 'hidden');
    $inputGeo2 = $inputGeo2->show().$hiddenGeo2->show();
} else {
    $inputGeo2 = $inputGeo2->show();
}

$inputDate = $this->getObject('datepicker','htmlelements');
$inputDate->setDefaultDate($calendardate);
$allReportTypes = $this->objReport->getAll("ORDER BY name");
$inputType = new dropdown('reportType');
$inputType->addFromDB($allReportTypes, 'name', 'id');
$inputType->setSelected($reportType);

$buttonNext = new button('next',$this->objLanguage->languageText('word_next'));
$buttonNext->setToSubmit();
$buttonBack = new button('back',$this->objLanguage->languageText('word_back'));

$tab = "&nbsp;&nbsp;&nbsp;&nbsp;";
$objTable = $this->getObject('htmltable','htmlelements');
$objTable->cellspacing = 2;
$objTable->width = NULL;

$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('mod_ahis_reportofficer','ahis').": $tab");
$objTable->addCell($inputOfficer);
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('phrase_geolevel2').": $tab");
$objTable->addCell($inputGeo2);
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('mod_ahis_reportdate','ahis').": $tab");
$objTable->addCell($inputDate->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('mod_ahis_reporttype','ahis').": $tab");
$objTable->addCell($inputType->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell(/*$buttonBack->show()*/'');
$objTable->addCell($buttonNext->show(),NULL,'top','right');
$objTable->endRow();

$this->loadClass('form','htmlelements');
$objForm = new form('reportForm', $this->uri(array('action' => 'report_filter')));
$objForm->addToForm($objTable->show());
//$objForm->addRule('officer', $this->objLanguage->languageText('mod_ahis_officerrule','ahis'), 'required');
//$objForm->addRule('district', $this->objLanguage->languageText('mod_ahis_districtrule','ahis'), 'required');
$objForm->addRule('calendardate', $this->objLanguage->languageText('mod_ahis_valdatereport', 'ahis'), 'datenotfuture');

$objLayer = new layer();
$objLayer->addToStr($objHeading->show()."<hr class='ahis' />$msg".$objForm->show());
$objLayer->align = 'center';

echo $objLayer->show();