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
$this->objNewherd = $this->getObject('newherd');
$objIcon = $this->newObject('geticon', 'htmlelements');
$objConfirm = $this->loadClass('confirm', 'utilities');
$message = $this->objLanguage->languageText('mod_ahis_confirmdel','ahis');


if ($id) {
    $hstr = $this->objLanguage->languageText('phrase_active')." ".$this->objLanguage->languageText('word_edit')."  ".$this->objLanguage->languageText('word_sample');
    $formUri = $this->uri(array('action'=>'sampleview_insert', 'id'=>$id));
    $record = $this->objSampledetails->getRow('id', $id);
    
} else {
    $hstr = $this->objLanguage->languageText('phrase_active')." ".$this->objLanguage->languageText('word_add')."  ".$this->objLanguage->languageText('word_sample');
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
    $record['newherdid']='';

}

$objHeading = $this->getObject('htmlheading','htmlelements');
$objHeading->str = $hstr;
$objHeading->type = 2;
$objHeading->align = 'center';

$addButton = new button('add', $this->objLanguage->languageText('phrase_addsample'));
$addButton->setToSubmit();

$finUri = $this->uri(array('action'=>'active_feedback','success'=>1));
$finButton = new button('finish', $this->objLanguage->languageText('word_finished'), "javascript: document.location='$finUri'");


$backButton = $this->uri(array('action'=>'active_addsample'));
$backButton = new button('back', $this->objLanguage->languageText('word_back'), "javascript: document.location='$backButton'");


$add2Button = new button('next', $this->objLanguage->languageText('word_enter'));
$add2Button->setToSubmit();

$campBox = new textinput('campname',$campName,'hidden');
$campBox->extra = "readonly";
//$farmBox = new textinput('farm',$farm);
//$farmBox->extra = "readonly";
//$farmsysBox = new textinput('farmingsystem',$farmingsystem);
//$farmsysBox->extra = "readonly";


$inputDate = $this->getObject('datepicker','htmlelements');
$inputDate->setDefaultDate($calendardate);

//$testDate = $this->newObject('datepicker','htmlelements');
//$testDate->setName('dateTest');
//$testDate->setDefaultDate($calenderdate);


$reporterBox = new dropdown('reporter');
$reporterBox->addFromDB($userList, 'name', 'userid');
$reporterBox->setSelected($reporter);
$reporterBox->extra = 'disabled';

$geo2Box  = new dropdown('geolevel2');
$geo2Box->addFromDB($arraygeo2,'name', 'id');
$geo2Box->setSelected($geo2);
$geo2Box->extra = 'disabled';

$diseaseBox = new textinput('disease',$disease,'hidden');
$diseaseBox->extra ="readonly";
//$reporterBox = new textinput('reporter',$reporter);
$surveyBox = new textinput('survey',$survey,'hidden');
$surveyBox->extra ="readonly";
$reportdateBox = new textinput('reportdate',$reportdate,'hidden');
$reportdateBox->extra ="readonly";


$farmDrop = new dropdown('farm');
$farmDrop->addFromDB($newherd, 'farmname', 'id');
$farmDrop->setSelected($record['newherdid']);
//print_r($farmDrop);
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
$objTable->cellpadding =4;
$objTable->cellspacing = 2;
//$objTable->width = '60%';
//$objTable->cssClass = 'min50';

$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_campaign').": $tab");
$objTable->addCell("<b>".$campName."</b>");
$objTable->addCell($this->objLanguage->languageText('word_disease').": $tab");
$objTable->addCell("<b>".$disease."</b>");
$objTable->addCell($this->objLanguage->languageText('mod_ahis_reportofficer','ahis').": $tab");
$objTable->addCell($reporterBox->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('phrase_surveytype').": ");
$objTable->addCell("<b>".$survey."</b>");
$objTable->addCell($this->objLanguage->languageText('phrase_geolevel2').": $tab");
$objTable->addCell($geo2Box->show());
$objTable->addCell($this->objLanguage->languageText('mod_ahis_reportdate','ahis').": $tab");
$objTable->addCell("<b>".$reportdate."</b>");
$objTable->endRow();

$objTable->startRow();
$objTable->addCell($campBox->show());
$objTable->addCell($diseaseBox->show());
$objTable->addCell($surveyBox->show());
$objTable->addCell($reportdateBox->show());
$objTable->endRow();

$this->loadClass('form','htmlelements');
$objForm = new form('reportForm', $formUri);
$objForm->addToForm($objTable->show());
$objForm->addRule('farm', $this->objLanguage->languageText('mod_ahis_valreq', 'ahis'), 'required');
$objLayer = new layer();
$objLayer->addToStr($objHeading->show()."<hr class='ahis' />".$objForm->show());
echo $objLayer->show();

if(!$id){
$objHeading = $this->getObject('htmlheading','htmlelements');
$objHeading->str = $this->objLanguage->languageText('phrase_samplesummary');
$objHeading->type = 2;
$objTable = new htmlTable();
$objTable->cellpadding =4;
$objTable->cellspacing = 2;
$objTable->width = '90%';
$objTable->cssClass = 'min50';

$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('phrase_sampleid'),'','','','heading');
$objTable->addCell($this->objLanguage->languageText('word_species'), '', '', '', 'heading');
$objTable->addCell($this->objLanguage->languageText('word_farm'), '', '', '', 'heading');
$objTable->addCell($this->objLanguage->languageText('phrase_farmingsystem'), '', '', '', 'heading');

$objTable->addCell($this->objLanguage->languageText('word_location'), '', '', '', 'heading');
$objTable->addCell($this->objLanguage->languageText('phrase_testtype'), '', '', '', 'heading');
$objTable->addCell($this->objLanguage->languageText('phrase_dateoftest'), '', '', '', 'heading');
$objTable->addCell($this->objLanguage->languageText('word_action'), '', '', '', 'heading');
$objTable->endRow();

foreach($datan as $line){
foreach($newherd as $var){
if($line['newherdid']==$var['id']){


$objTable->startRow();
$objTable->addCell($line['sampleid']);
$objTable->addCell($line['species']);
$objTable->addCell($var['farmname']);
$objTable->addCell($var['farmingtype']);
$objTable->addCell($var['territory']);
$objTable->addCell($line['testtype']);
$objTable->addCell($line['testdate']);



 $editUrl = $this->uri(array(
            'action' => 'active_addsample',
            'id' => $line['id'],
            'newherdid' => $newherdid
        ));
 $icons = $objIcon->getEditIcon($editUrl);
 $objIcon->title = $objLanguage->languageText('word_delete');
 $objIcon->setIcon('delete');
 $objConfirm = new confirm();
 $objConfirm->setConfirm($objIcon->show() , $this->uri(array(
            'action' => 'sampleview_delete',
            'id' => $line['id'],
        )) , $message);
$icons.= $objConfirm->show();
$objTable->addCell($icons);
$objTable->endRow();
}
}
}
$rep=array(
'campName'=>$campName,

);
$this->loadClass('form','htmlelements');
$objForm = new form('reportForm', $formUri);
$objForm->addToForm($objTable->show());
$objForm->addRule('farm', $this->objLanguage->languageText('mod_ahis_valreq', 'ahis'), 'required');
$objLayer = new layer();
$objLayer->addToStr("<br/><b>".$this->objLanguage->code2Txt('mod_ahis_addsamplecomment2','ahis',$rep)."</b>");
$objLayer->addToStr("<br/>");
$objLayer->addToStr($objHeading->show()."<hr class='ahis' /><br/>".$objForm->show()."<br/>");
echo $objLayer->show();
}
$rep=array(
'campName'=>$campName,

);
$objTable = new htmlTable();
$objTable->cellpadding =4;
$objTable->cellspacing = 2;
$objTable->width = '90%';
$objTable->cssClass = 'min50';
$objTable->startRow();

$objTable->addCell($this->objLanguage->languageText('word_farm').":");
$objTable->addCell($farmDrop->show());
$objTable->addCell($this->objLanguage->languageText('word_species'));
$objTable->addCell($speciesDrop->show());
$objTable->addCell($this->objLanguage->languageText('phrase_sampletype'));
$objTable->addCell($sampletypeDrop->show());
$objTable->endRow();

//$objTable->startRow();
//$objTable->addCell($this->objLanguage->languageText('word_farming')." ".$this->objLanguage->languageText('word_system').":");
//$objTable->addCell($farmsysBox->show());
//$objTable->endRow();


$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('phrase_sampleid'));
$objTable->addCell($sampleidBox->show());
$objTable->addCell($this->objLanguage->languageText('word_age'));
$objTable->addCell($ageDrop->show());
$objTable->addCell($this->objLanguage->languageText('phrase_testtype'));
$objTable->addCell($testtypeDrop->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('phrase_animalid'));
$objTable->addCell($animalidBox->show());
$objTable->addCell($this->objLanguage->languageText('word_sex'));
$objTable->addCell($sexDrop->show());
$objTable->addCell($this->objLanguage->languageText('phrase_testresult'));
$objTable->addCell($testresultDrop->show());
$objTable->endRow();


$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('phrase_vaccinationhistory'));
$objTable->addCell($vachistoryDrop->show());
 
$objTable->addCell($this->objLanguage->languageText('phrase_dateoftest'));
$objTable->addCell($inputDate->show());
$objTable->addCell($this->objLanguage->languageText('word_number'));
$objTable->addCell($numberBox->show());
$objTable->endRow();

$objTable->startRow();

$objTable->addCell($this->objLanguage->languageText('word_remarks'));
$objTable->addCell($remarksBox->show());
$objTable->endRow();
$objTable->startRow();

$objTable->endRow();
$objTable->startRow();

$objTable->endRow();
$objTable->startRow();
 
$objTable->endRow();
$objTable->startRow();
$objTable->addCell('&nbsp');
$objTable->endRow();
$objTable->startRow();
if($id){
$objTable->addCell($add2Button->show());
$objTable->addCell($backButton->show());
}else
{
$objTable->addCell($addButton->show());
$objTable->addCell($finButton->show());
}$objTable->endRow();


$this->loadClass('form','htmlelements');
$objForm = new form('reportForm', $formUri);
$objForm->addToForm($objTable->show());
//$objForm->addRule('sampleid', $this->objLanguage->languageText('mod_ahis_valnum', 'ahis'), 'numeric');
$objForm->addRule('sampleid', $this->objLanguage->languageText('mod_ahis_valreq', 'ahis'), 'required');
//$objForm->addRule('animalid', $this->objLanguage->languageText('mod_ahis_valnum', 'ahis'), 'numeric');
$objForm->addRule('animalid', $this->objLanguage->languageText('mod_ahis_valnum', 'ahis'), 'required');

$objForm->addRule('number', $this->objLanguage->languageText('mod_ahis_valnum', 'ahis'), 'numeric');
$objForm->addRule('number', $this->objLanguage->languageText('mod_ahis_valreq', 'ahis'), 'required');
$objForm->addRule('remarks', $this->objLanguage->languageText('mod_ahis_valreq', 'ahis'), 'required');

echo "<hr class='ahis' /><br/>".$this->objLanguage->code2Txt('mod_ahis_addsamplecomment','ahis',$rep)."<br /><br />".$objForm->show();

?>