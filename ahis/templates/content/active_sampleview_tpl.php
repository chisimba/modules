<?php
/**
 * ahis Active Survaillance Samples screen Template
 *
 * Template for capturing active surveillance sample data
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
 * @author    Rosina Ntow <rntow@ug.edu.gh>
 * @copyright 2009 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: active_herdview_tpl.php 
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
$objHeading->str = $this->objLanguage->languageText('phrase_active')." ".$this->objLanguage->languageText('word_samples');
$objHeading->type = 2;


$this->loadClass('layer','htmlelements');
$this->loadClass('dropdown','htmlelements');
$this->loadClass('textinput','htmlelements');

$objTable = $this->getObject('htmltable','htmlelements');


$addButton = new button('add', $this->objLanguage->languageText('word_add'));
$addButton->setToSubmit();
$backUri = $this->uri(array('action'=>'active_herdsampling'));
$backButton = new button('cancel', $this->objLanguage->languageText('word_back'), "javascript: document.location='$backUri'");
$nextUri = $this->uri(array('action'=>'sero_surveillance'));
$nextButton = new button('cancel', $this->objLanguage->languageText('word_next'), "javascript: document.location='$nextUri'");

$campBox = new textinput('campName',$campName);
$diseaseBox = new textinput('diseaseBox',$diseaseBox);


$numberBox = new textinput('number',$number);

$officerDrop = new dropdown('officerId');
$officerDrop->addFromDB($userList, 'name', 'userid');
$officerDrop->setSelected($officerId);
$officerDrop->extra = 'disabled';

$inputDate = $this->getObject('datepicker','htmlelements');
$inputDate->setDefaultDate($calendardate);


$objTable->cellspacing = 2;
$objTable->width = NULL;
$objTable->cssClass = 'min50';


$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_campaign')." ".$this->objLanguage->languageText('word_name').": ");
$objTable->addCell($campBox->show());
$objTable->addCell($this->objLanguage->languageText('word_disease').":");
$objTable->addCell($diseaseBox->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('mod_ahis_reportofficer','ahis').": $tab");
$objTable->addCell($officerDrop->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('phrase_samplingdate').": ");
$objTable->addCell($inputDate->show());

$objTable->addCell($this->objLanguage->languageText('phrase_numberofsamples').": ");
$objTable->addCell($numberBox->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_samples').": $tab");

$objTable->endRow();

$objLayer = new layer();
$objLayer->addToStr($objHeading->show()."<hr class='ahis' />".$objTable->show());
$objLayer->align = 'center';

echo $objLayer->show();


$objTable = new htmlTable();
$objTable->cellpadding =4;
$objTable->cellspacing = 2;
$objTable->width = '60%';
$objTable->cssClass = 'min50';

$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('phrase_sampleid'),'','','','heading');

$objTable->addCell($this->objLanguage->languageText('phrase_dateoftesting'), '', '', '', 'heading');

$objTable->addCell($this->objLanguage->languageText('word_species'), '', '', '', 'heading');
$objTable->addCell($this->objLanguage->languageText('word_age'), '', '', '', 'heading');
$objTable->addCell($this->objLanguage->languageText('phrase_sampletype'), '', '', '', 'heading');
$objTable->addCell($this->objLanguage->languageText('phrase_typeoftest'), '', '', '', 'heading');
$objTable->addCell($this->objLanguage->languageText('word_number'), '', '', '', 'heading');
$objTable->addCell($this->objLanguage->languageText('phrase_vaccinationhistory'), '', '', '', 'heading');
$objTable->addCell($this->objLanguage->languageText('word_action'), '', '', '', 'heading');

$objTable->endRow();
$objTable->startRow();
$objTable->addCell('');
$objTable->addCell('');
$objTable->addCell('');
$objTable->addCell('');
$objTable->addCell('');
$objTable->addCell('');
$objTable->startRow();
$objTable->addCell('&nbsp');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($backButton->show());
$objTable->addCell($addButton->show());
$objTable->addCell($nextButton->show());
$objTable->endRow();
$this->loadClass('form','htmlelements');
$objForm = new form('reportForm', $this->uri(array('action' => 'active_addsample')));
$objForm->addToForm($objTable->show());

$objLayer = new layer();
$objLayer->addToStr($objForm->show());
$objLayer->align = 'center';

echo $objLayer->show();

?>
