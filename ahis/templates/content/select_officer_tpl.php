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

//$inputOfficer = new textinput('officer', $officer, 'text', 25);
$inputOfficer = new dropdown('officerId');
$inputOfficer->addFromDB($userList, 'name', 'userid');
$inputOfficer->setSelected($officerId);
if (!$this->objUser->isAdmin()) {
    $inputOfficer->extra = 'disabled';
}
$inputDistrict = new dropdown('districtId');
$inputDistrict->addFromDB($this->objTerritory->getAll("ORDER BY NAME"), 'name', 'id');
$inputDistrict->setSelected($districtId);
if (!$this->objUser->isAdmin()) {
    $inputDistrict->extra = 'disabled';
}
//$inputDistrict = new textinput('district', $district, 'text', 25);
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
$objTable->addCell($inputOfficer->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_district').": $tab");
$objTable->addCell($inputDistrict->show());
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
$objForm->addRule('officer', $this->objLanguage->languageText('mod_ahis_officerrule','ahis'), 'required');
$objForm->addRule('district', $this->objLanguage->languageText('mod_ahis_districtrule','ahis'), 'required');

$objLayer = new layer();
$objLayer->addToStr($objHeading->show()."<hr class='ahis' />".$objForm->show());
$objLayer->align = 'center';

echo $objLayer->show();