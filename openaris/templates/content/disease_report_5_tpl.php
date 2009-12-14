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
 * @version   $Id: passive_outbreak_tpl.php 13733 2009-06-23 11:04:26Z nic $
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
$objHeading->str = $this->objLanguage->languageText('mod_ahis_diseasereport', 'openaris')." #5";
$objHeading->type = 2;

$this->loadClass('textinput','htmlelements');
$this->loadClass('fieldset','htmlelements');
$this->loadClass('dropdown','htmlelements');
$this->loadClass('button','htmlelements');
$this->loadClass('form','htmlelements');

$tab = "&nbsp;&nbsp;&nbsp;&nbsp;";

$objTableArea1 = $this->getObject('htmltable','htmlelements');
$objTableArea1->cellspacing = 2;
$objTableArea1->width = NULL;

$objTableArea1->startHeaderRow();
$objTableArea1->addHeaderCell($this->objLanguage->languageText('mod_ahis_outbreakcode', 'openaris'));
$objTableArea1->addHeaderCell($this->objLanguage->languageText('phrase_partitiontype'));
$objTableArea1->addHeaderCell($this->objLanguage->languageText('mod_ahis_partitionlevel', 'openaris'));
$objTableArea1->addHeaderCell($this->objLanguage->languageText('mod_ahis_partitionname', 'openaris'));
$objTableArea1->addHeaderCell($this->objLanguage->languageText('word_year'));
$objTableArea1->addHeaderCell($this->objLanguage->languageText('word_month'));
$objTableArea1->endHeaderRow();

foreach ($outbreaks as $outbreak) {
    $objTableArea1->startRow();
    $objTableArea1->addCell($outbreak['outbreakCode']);
    $objTableArea1->addCell($outbreak['partitionType']);
    $objTableArea1->addCell($outbreak['partitionLevel']);
    $objTableArea1->addCell($outbreak['partitionName']);
    $objTableArea1->addCell($outbreak['month']);
    $objTableArea1->addCell($outbreak['year']);
    $objTableArea1->endRow();
}

$outbreakCodeBox = new textinput('outbreakCode', $outbreakCode);
$outbreakCodeBox->extra = 'readonly';
$outbreakCodeBox->setCss('passive_surveillance');

$controlDrop = new dropdown('controlId');
$controlDrop->addFromDB($arrayControlMeasure, 'controlmeasure', 'id');
$controlDrop->setSelected($controlId);
$controlDrop->cssClass = 'passive_surveillance';

$otherControlDrop = new dropdown('otherControlId');
$otherControlDrop->addFromDB($arrayOtherMeasure, 'control_measure', 'id');
$otherControlDrop->setSelected($otherId);
$otherControlDrop->cssClass = 'passive_surveillance';

$createdBox = new textinput('createdBy', $createdBy, 'text');
$createdBox->setCss('passive_surveillance');
$createdDateBox = new textinput('createdDate', $createdDate, 'text');
$createdDateBox->setCss('passive_surveillance');
$modifiedBox = new textinput('modifiedBy', $modifiedBy, 'text');
$modifiedBox->setCss('passive_surveillance');
$modifiedDateBox = new textinput('modifiedDate', $modifiedDate, 'text');
$modifiedDateBox->setCss('passive_surveillance');
$createdBox->extra = $createdDateBox->extra = $modifiedBox->extra = $modifiedDateBox->extra = 'disabled';

$objTableArea2 = $this->newObject('htmltable','htmlelements');
$objTableArea2->cellspacing = 2;
$objTableArea2->width = NULL;

$nextUri = $this->uri(array('action'=>'disease_report_screen_6', 'outbreakCode'=>$outbreakCode));
if (count($diseaseControlMeasures) > 0) {
    $function = "javascript: document.location='$nextUri'";
} else {
    $message = $this->objLanguage->languageText('mod_ahis_mustaddcontrolmeasure', 'openaris');
    $function = "javascript: alert('$message')";
}
$sButton = new button('enter', $this->objLanguage->languageText('word_next'), $function);
$sButton->setCSS('nextButton');
$backUri = $this->uri(array('action'=>'disease_report_screen_4', 'outbreakCode'=>$outbreakCode));
$bButton = new button('back', $this->objLanguage->languageText('word_back'), "javascript: document.location='$backUri'");
$bButton->setCSS('backButton');
$cButton = new button('clear', $this->objLanguage->languageText('word_clear'), "javascript: clearControlMeasures()");
$cButton->setCSS('clearButton');
$aButton = new button('add', $this->objLanguage->languageText('word_add'));
$aButton->setCSS('addButton');
$aButton->setToSubmit();


$objTableArea2->startRow();
$objTableArea2->addCell($this->objLanguage->languageText('mod_ahis_outbreakcode', 'openaris'));
$objTableArea2->addCell($outbreakCodeBox->show());
$objTableArea2->addCell($this->objLanguage->languageText('word_createdby'));
$objTableArea2->addCell($createdBox->show());
$objTableArea2->endRow();
$objTableArea2->startRow();
$objTableArea2->addCell($this->objLanguage->languageText('phrase_control'));
$objTableArea2->addCell($controlDrop->show());
$objTableArea2->addCell($this->objLanguage->languageText('phrase_createddate'));
$objTableArea2->addCell($createdDateBox->show());
$objTableArea2->endRow();
$objTableArea2->startRow();
$objTableArea2->addCell($this->objLanguage->languageText('mod_ahis_othermeasure', 'openaris'));
$objTableArea2->addCell($otherControlDrop->show());
$objTableArea2->addCell($this->objLanguage->languageText('word_modifiedby'));
$objTableArea2->addCell($modifiedBox->show());
$objTableArea2->endRow();
$objTableArea2->startRow();
$objTableArea2->addCell($this->objLanguage->languageText('phrase_modifieddate'), NULL, 'top', 'right', NULL, 'colspan="3"');
$objTableArea2->addCell($modifiedDateBox->show());
$objTableArea2->endRow();
$objTableArea2->startRow();
$objTableArea2->addCell($cButton->show().$tab.$bButton->show().$tab.$aButton->show().$tab.$sButton->show(), NULL, 'top', 'center', NULL, 'colspan="4"');
$objTableArea2->endRow();


$diagnosisSet = new fieldset('diagnosisSet');
$diagnosisSet->setExtra('style="max-width: 572px;"');
$diagnosisSet->setLegend($this->objLanguage->languageText('phrase_control'));
$diagnosisSet->addContent($objTableArea2->show());

$objForm = new form('reportForm', $this->uri(array('action' => 'add_diseasecontrolmeasure')));
$objForm->addToForm($diagnosisSet->show());

$objTableArea3 = $this->newObject('htmltable','htmlelements');
$objTableArea3->cellspacing = 2;
$objTableArea3->width = NULL;

$objTableArea3->startHeaderRow();
$objTableArea3->addHeaderCell($this->objLanguage->languageText('mod_ahis_outbreakcode', 'openaris'));
$objTableArea3->addHeaderCell($this->objLanguage->languageText('phrase_control'));
$objTableArea3->addHeaderCell($this->objLanguage->languageText('mod_ahis_othermeasure', 'openaris'));
$objTableArea3->addHeaderCell($this->objLanguage->languageText('phrase_createdby'));
$objTableArea3->addHeaderCell($this->objLanguage->languageText('phrase_createddate'));
$objTableArea3->addHeaderCell($this->objLanguage->languageText('phrase_modifiedby'));
$objTableArea3->addHeaderCell($this->objLanguage->languageText('phrase_modifieddate'));
$objTableArea3->endHeaderRow();

foreach ($diseaseControlMeasures as $measure) {
    $controlMeasure = $this->objControlmeasures->getRow('id', $measure['controlmeasureid']);
    $otherMeasure = $this->objOtherControlMeasures->getRow('id', $measure['othermeasureid']);
    $objTableArea3->startRow();
    $objTableArea3->addCell($measure['outbreakcode']);
    $objTableArea3->addCell($controlMeasure['controlmeasure']);
    $objTableArea3->addCell($otherMeasure['control_measure']);
    $objTableArea3->addCell($this->objUser->Username($measure['created_by']));
    $objTableArea3->addCell($measure['date_created']);
    $modifier = ($measure['modified_by'] == NULL)? '' : $this->objUser->Username($measure['modified_by']);
    $objTableArea3->addCell($modifier);
    $objTableArea3->addCell($measure['date_modified']);
    $objTableArea3->endRow();

}

$scriptUri = $this->getResourceURI('util.js');
$this->appendArrayVar('headerParams', "<script type='text/javascript' src='$scriptUri'></script>");

$content = $objTableArea1->show()."<br />".$objForm->show()."<br />".$objTableArea3->show();
echo $objHeading->show()."<br />".$content;