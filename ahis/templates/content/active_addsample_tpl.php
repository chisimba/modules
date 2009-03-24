<?php
/**
 * ahis Active Survaillance add new samples screen Template
 *
 * Template for capturing active surveillance new samples of herd 
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





$this->loadClass('textinput','htmlelements');
$this->loadClass('textarea','htmlelements');
$this->loadClass('dropdown','htmlelements');
$this->loadClass('button','htmlelements');
$this->loadClass('layer','htmlelements');




if ($id) {
    $hstr = $this->objLanguage->languageText('phrase_active')." ".$this->objLanguage->languageText('word_edit')."  ".$this->objLanguage->languageText('word_samples');
    $formUri = $this->uri(array('action'=>'sampleview_insert', 'id'=>$id));
    $record = $this->objSampledetails->getRow('id', $id);
    
} else {
    $hstr = $this->objLanguage->languageText('phrase_active')." ".$this->objLanguage->languageText('word_add')."  ".$this->objLanguage->languageText('word_samples');
    $formUri = $this->uri(array('action'=>'sampleview_insert'));
    $record['sampleid'] = '';
    $record['animalid'] = '';
    $record['species'] = '';
    $record['age'] = '';
    $record['sex'] = '';
    $record['sampletype'] = '';
    $record['testtype'] = '';
    $record['testresult'] = '';
    $record['specification'] = '';
    $record['vachist'] = '';
    $record['number'] = '';
    $record['remarks'] = '';

}

$objHeading = $this->getObject('htmlheading','htmlelements');
$objHeading->str = $hstr;
$objHeading->type = 2;

$addButton = new button('add', $this->objLanguage->languageText('word_add'));
$addButton->setToSubmit();
$backUri = $this->uri(array('action'=>'active_sampleview'));
$backButton = new button('back', $this->objLanguage->languageText('word_back'), "javascript: document.location='$backUri'");




$inputDate = $this->getObject('datepicker','htmlelements');
$inputDate->setDefaultDate($calendardate);

//$testDate = $this->newObject('datepicker','htmlelements');
//$testDate->setName('dateTest');
//$testDate->setDefaultDate($calenderdate);


$speciesDrop = new dropdown('species');
$speciesDrop->addFromDB($arraySpecies, 'name', 'name');
$speciesDrop->setSelected($record['species']);
$ageDrop = new dropdown('age');
$ageDrop->addFromDB($arrayAge, 'name', 'name');
$ageDrop->setSelected($record['age']);

$sexDrop = new dropdown('sex');
$sexDrop->addFromDB($arraySex, 'name', 'name');
$sexDrop->setSelected($record['sex']);
$sampletypeDrop = new dropdown('sampletype');
$sampletypeDrop->addFromDB($arraySample, 'name', 'name');
$sampletypeDrop->setSelected($record['sampletype']);
$testtypeDrop = new dropdown('testtype');
$testtypeDrop->addFromDB($arrayTest, 'name', 'name');
$testtypeDrop->setSelected($record['testtype']);
$testresultDrop = new dropdown('testresult');
$testresultDrop->addFromDB($arrayTestresult, 'name', 'name');
$testresultDrop->setSelected($record['testresult']);
$vachistoryDrop = new dropdown('vachistory');
$vachistoryDrop->addFromDB($arrayVac, 'name', 'name');
$vachistoryDrop->setSelected($record['vachist']);


$specArea = new textarea('spec',$record['specification'],0,25);

$sampleidBox = new textinput('sampleid', $record['sampleid']);
$animalidBox = new textinput('animalid', $record['animalid']);
$numberBox = new textinput('number', $record['number']);
$remarksBox = new textarea('remarks', $record['remarks']);

$objTable = $this->getObject('htmltable','htmlelements');
$objTable->cellspacing = 2;
$objTable->width = NULL;
$objTable->cssClass = 'min50';


$objTable->startRow();

$objTable->addCell($this->objLanguage->languageText('phrase_sampleid'));
$objTable->addCell($sampleidBox->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('phrase_animalid'));
$objTable->addCell($animalidBox->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_species'));
$objTable->addCell($speciesDrop->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_age'));
$objTable->addCell($ageDrop->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_sex'));
$objTable->addCell($sexDrop->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('phrase_sampletype'));
$objTable->addCell($sampletypeDrop->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('phrase_testtype'));
$objTable->addCell($testtypeDrop->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('phrase_dateoftest'));
$objTable->addCell($inputDate->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('phrase_testresult'));
$objTable->addCell($testresultDrop->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_specification'));
$objTable->addCell($specArea->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('phrase_vaccinationhistory'));
$objTable->addCell($vachistoryDrop->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_number'));
$objTable->addCell($numberBox->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_remarks'));
$objTable->addCell($remarksBox->show());  
$objTable->endRow();
$objTable->startRow();
$objTable->addCell('&nbsp');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($backButton->show());
$objTable->addCell($addButton->show());
$objTable->endRow();


$this->loadClass('form','htmlelements');
$objForm = new form('reportForm', $formUri);
$objForm->addToForm($objTable->show());

$objLayer = new layer();
$objLayer->addToStr($objHeading->show()."<hr class='ahis' />".$objForm->show());
$objLayer->align = 'center';

echo $objLayer->show();

?>