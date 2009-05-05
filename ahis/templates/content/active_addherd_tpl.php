<?php
/**
 * ahis Active Survaillance add Herd screen Template
 *
 * Template for capturing active surveillance for new herd 
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

if ($id) {
    $hstr = $this->objLanguage->languageText('phrase_active')." ".$this->objLanguage->languageText('word_edit')."  ".$this->objLanguage->languageText('word_farm');
    $formUri = $this->uri(array('action'=>'newherd_insert', 'id'=>$id));
    $record = $this->objNewherd->getRow('id', $id);
    
} else {
    $hstr = $this->objLanguage->languageText('phrase_active')." ".$this->objLanguage->languageText('word_add')."  ".$this->objLanguage->languageText('word_farm');
    $formUri = $this->uri(array('action'=>'newherd_insert'));
    
    $record['territory'] = '';
    $record['farm'] = '';
    $record['farmingtype'] = '';

}

$objHeading = $this->getObject('htmlheading','htmlelements');
$objHeading->str = $hstr;
$objHeading->type = 2;
$objHeading->align = 'center';


$this->loadClass('textinput','htmlelements');
$this->loadClass('dropdown','htmlelements');
$this->loadClass('button','htmlelements');
$this->loadClass('layer','htmlelements');
$objIcon = $this->newObject('geticon', 'htmlelements');
$objConfirm = $this->loadClass('confirm', 'utilities');
$message = $this->objLanguage->languageText('mod_ahis_confirmdel','ahis');


$nextButton = $this->uri(array('action'=>'active_addsample'));
$nextButton = new button('finish', $this->objLanguage->languageText('word_finished'), "javascript: document.location='$nextButton'");

$backButton = $this->uri(array('action'=>'active_addherd'));
$backButton = new button('back', $this->objLanguage->languageText('word_back'), "javascript: document.location='$backButton'");

$addButton = new button('next', $this->objLanguage->languageText('phrase_addfarm'));
$addButton->setToSubmit();

$add2Button = new button('next', $this->objLanguage->languageText('word_enter'));
$add2Button->setToSubmit();
//$addUri = $this->uri(array('action'=>'active_newherd'));
//$addButton = new button('cancel', $this->objLanguage->languageText('word_add'), "javascript: document.location='$addUri'");

$reporterBox = new dropdown('reporter');
$reporterBox->addFromDB($userList, 'name', 'userid');
$reporterBox->setSelected($reporter);
$reporterBox->extra = 'disabled';

$geo2Box  = new dropdown('geolevel2');
$geo2Box->addFromDB($arraygeo2,'name', 'id');
$geo2Box->setSelected($geo2);
$geo2Box->extra = 'disabled';

$campNameBox = new textinput('campname',$campName,'hidden');
$campNameBox->extra ="readonly";
$diseaseBox = new textinput('disease',$disease,'hidden');
$diseaseBox->extra ="readonly";
//$reporterBox = new textinput('reporter',$reporter);
$surveyBox = new textinput('survey',$survey,'hidden');
$surveyBox->extra ="readonly";
//$geo2Box = new textinput('geo2',$geo2);
$reportdateBox = new textinput('reportdate',$reportdate,'hidden');
$reportdateBox->extra ="readonly";

$territoryDrop = new dropdown('territory');
$territoryDrop->addFromDB($arrayTerritory, 'name', 'name');
$territoryDrop->setSelected($record['territory']);

$farmsystemDrop = new dropdown('farmingsystem');
$farmsystemDrop->addFromDB($arrayFarmingsystem, 'name', 'name');
$farmsystemDrop->setSelected($record['farmingtype']);




$farmBox = new textinput('farm', $record['farmname']);

$activeBox = new textinput('activeid',$activeid,'hidden');
$objTable = $this->getObject('htmltable','htmlelements');
$objTable->cellspacing = 2;
$objTable->width = '60%';
$objTable->cssClass = 'min50';

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
$objTable->addCell($campNameBox->show());
$objTable->addCell($diseaseBox->show());
$objTable->addCell($surveyBox->show());
$objTable->addCell($reportdateBox->show());
$objTable->endRow();

$this->loadClass('form','htmlelements');
$objForm = new form('reportForm');
$objForm->addToForm($objTable->show());

$objLayer = new layer();
$objLayer->addToStr($objHeading->show()."<hr class='ahis' /><br/>".$objForm->show());
//$objLayer->align = 'center';

echo $objLayer->show();

if(!$id){
$objHeading = $this->getObject('htmlheading','htmlelements');
$objHeading->str = $this->objLanguage->languageText('phrase_farmsummary');
$objHeading->type = 2;
$objTable = new htmlTable();
$objTable->cellpadding =4;
$objTable->cellspacing = 2;
$objTable->width = '90%';
$objTable->cssClass = 'min50';

$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_farm')." ".$this->objLanguage->languageText('word_name'), '', '', '', 'heading');
$objTable->addCell($this->objLanguage->languageText('word_farming')." ".$this->objLanguage->languageText('word_system'), '', '', '', 'heading');
$objTable->addCell($this->objLanguage->languageText('word_location'), '', '', '', 'heading');
$objTable->addCell($this->objLanguage->languageText('word_action'), '', '', '', 'heading');
$objTable->endRow();
foreach($hdata as $line ){

$objTable->startRow();
$objTable->addCell($line['farmname']);
$objTable->addCell($line['farmingtype']);
$objTable->addCell($line['territory']);
 $editUrl = $this->uri(array(
            'action' => 'active_addherd',
            'id' => $line['id'],

        ));
 $icons = $objIcon->getEditIcon($editUrl);
 $objIcon->title = $objLanguage->languageText('word_delete');
 $objIcon->setIcon('delete');
 $objConfirm = new confirm();
 $objConfirm->setConfirm($objIcon->show() , $this->uri(array(
            'action' => 'newherd_delete',
            'id' => $line['id'],
        )) , $message);
$icons.= $objConfirm->show();
$objTable->addCell($icons);
$objTable->endRow();
}
$this->loadClass('form','htmlelements');
$objForm = new form('reportForm', $formUri);
$objForm->addToForm($objTable->show());
$objForm->addRule('farm', $this->objLanguage->languageText('mod_ahis_valreq', 'ahis'), 'required');
$objLayer = new layer();
$objLayer->addToStr("<br/><b>".$this->objLanguage->languageText('mod_ahis_addfarmcomment','ahis')."</b>");
$objLayer->addToStr("<br/>");
$objLayer->addToStr($objHeading->show()."<hr class='ahis' />".$objForm->show()."<br/>");
$objLayer->addToStr("<br/>");
echo $objLayer->show();
}
$rep=array(
'campName'=>$campName,

);
//$objHeading = $this->getObject('htmlheading','htmlelements');
//$objHeading->str = $this->objLanguage->code2Txt('mod_ahis_addfarmcomment2','ahis',$rep);
//$objHeading->type = 2;
$objTable = new htmlTable();
$objTable->cellpadding =4;
$objTable->cellspacing = 2;
$objTable->width = '60%';
$objTable->cssClass = 'min50';

$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_farm').": ");
$objTable->addCell($farmBox->show());
$objTable->addCell($this->objLanguage->languageText('word_farming')." ".$this->objLanguage->languageText('word_system').": $tab");
$objTable->addCell($farmsystemDrop->show());
$objTable->addCell($this->objLanguage->languageText('word_location').": ");
$objTable->addCell($territoryDrop->show());
$objTable->endRow();


$objTable->startRow();

$objTable->addCell($activeBox->show());
$objTable->endRow();



$objTable->startRow();
if($id){
$objTable->addCell($add2Button->show());
$objTable->addCell($backButton->show());
}else
{
$objTable->addCell($addButton->show());
$objTable->addCell($nextButton->show());
}
$objTable->endRow();




$this->loadClass('form','htmlelements');
$objForm = new form('reportForm', $formUri);
$objForm->addToForm($objTable->show());
$objForm->addRule('farm', $this->objLanguage->languageText('mod_ahis_valreq', 'ahis'), 'required');

$objLayer = new layer();
if($id){
$objLayer->addToStr("<hr class='ahis' />".$this->objLanguage->languageText('mod_ahis_editfarmcomment','ahis')."<br/>".$objForm->show());
}else
$objLayer->addToStr("<hr class='ahis' />".$this->objLanguage->code2Txt('mod_ahis_addfarmcomment2','ahis',$rep)."<br/>".$objForm->show());
//$objLayer->align = 'center';


echo $objLayer->show();

?>