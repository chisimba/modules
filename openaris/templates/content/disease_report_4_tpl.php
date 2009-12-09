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
$objHeading->str = $this->objLanguage->languageText('mod_ahis_diseasereport', 'openaris')." #4";
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

$outbreakCodeBox = new textinput('outbreakCode', $outbreakCode);
$outbreakCodeBox->extra = 'disabled';
$outbreakCodeBox->setCss('passive_surveillance');

$speciesDrop = new dropdown('speciesId');
$speciesDrop->addFromDB($arraySpecies, 'common_name', 'id');
$speciesDrop->setSelected($speciesId);
$speciesDrop->cssClass = 'passive_surveillance';

$ageDrop = new dropdown('ageId');
$ageDrop->addFromDB($arrayAgeGroup, 'agegroup', 'id');
$ageDrop->setSelected($ageId);
$ageDrop->cssClass = 'passive_surveillance';

$sexDrop = new dropdown('sexId');
$sexDrop->addFromDB($arraySex, 'name', 'id');
$sexDrop->setSelected($sexId);
$sexDrop->cssClass = 'passive_surveillance';

$createdBox = new textinput('createdBy', $createdBy, 'text');
$createdBox->setCss('passive_surveillance');
$createdDateBox = new textinput('createdDate', $createdDate, 'text');
$createdDateBox->setCss('passive_surveillance');
$modifiedBox = new textinput('modifiedBy', $modifiedBy, 'text');
$modifiedBox->setCss('passive_surveillance');
$modifiedDateBox = new textinput('modifiedDate', $modifiedDate, 'text');
$modifiedDateBox->setCss('passive_surveillance');
$createdBox->extra = $createdDateBox->extra = $modifiedBox->extra = $modifiedDateBox->extra = 'disabled';

$nextUri = $this->uri(array('action'=>'disease_report_screen_5', 'outbreakCode'=>$outbreakCode));
$sButton = new button('enter', $this->objLanguage->languageText('word_next'), "javascript: document.location='$nextUri'");
$sButton->setCSS('nextButton');
$backUri = $this->uri(array('action'=>'disease_report_screen_3', 'outbreakCode'=>$outbreakCode));
$bButton = new button('back', $this->objLanguage->languageText('word_back'), "javascript: document.location='$backUri'");
$bButton->setCSS('backButton');
$cButton = new button('clear', $this->objLanguage->languageText('word_clear'), "javascript: clearDiseaseLocality()");
$cButton->setCSS('clearButton');
$aButton = new button('add', $this->objLanguage->languageText('word_add'));
$aButton->setCSS('addButton');
$aButton->setToSubmit();

$objTableSpecies = $this->newObject('htmltable','htmlelements');
$objTableSpecies->cellspacing = 2;
$objTableSpecies->width = NULL;

$objTableSpecies->startRow();
$objTableSpecies->addCell($this->objLanguage->languageText('mod_ahis_outbreakcode', 'openaris'));
$objTableSpecies->addCell($outbreakCodeBox->show());
$objTableSpecies->endRow();
$objTableSpecies->startRow();
$objTableSpecies->addCell($this->objLanguage->languageText('word_species'));
$objTableSpecies->addCell($speciesDrop->show());
$objTableSpecies->endRow();
$objTableSpecies->startRow();
$objTableSpecies->addCell($this->objLanguage->languageText('word_age'));
$objTableSpecies->addCell($ageDrop->show());
$objTableSpecies->endRow();
$objTableSpecies->startRow();
$objTableSpecies->addCell($this->objLanguage->languageText('word_sex'));
$objTableSpecies->addCell($sexDrop->show());
$objTableSpecies->endRow();

$riskBox = new textinput('risk', $risk, 'text');
$riskBox->setCss('passive_surveillance');
$casesBox = new textinput('cases', $cases, 'text');
$casesBox->setCss('passive_surveillance');
$deathsBox = new textinput('deaths', $deaths, 'text');
$deathsBox->setCss('passive_surveillance');
$destroyedBox = new textinput('destroyed', $destroyed, 'text');
$destroyedBox->setCss('passive_surveillance');
$slaughteredBox = new textinput('slaughtered', $slaughtered, 'text');
$slaughteredBox->setCss('passive_surveillance');

$objTableActual = $this->newObject('htmltable','htmlelements');
$objTableActual->cellspacing = 2;
$objTableActual->width = NULL;

$objTableActual->startRow();
$objTableActual->addCell($this->objLanguage->languageText('mod_ahis_risk', 'openaris'));
$objTableActual->addCell($riskBox->show());
$objTableActual->endRow();
$objTableActual->startRow();
$objTableActual->addCell($this->objLanguage->languageText('mod_ahis_cases', 'openaris'));
$objTableActual->addCell($casesBox->show());
$objTableActual->endRow();
$objTableActual->startRow();
$objTableActual->addCell($this->objLanguage->languageText('mod_ahis_deaths', 'openaris'));
$objTableActual->addCell($deathsBox->show());
$objTableActual->endRow();
$objTableActual->startRow();
$objTableActual->addCell($this->objLanguage->languageText('mod_ahis_destroyed', 'openaris'));
$objTableActual->addCell($destroyedBox->show());
$objTableActual->endRow();
$objTableActual->startRow();
$objTableActual->addCell($this->objLanguage->languageText('mod_ahis_slaughtered', 'openaris'));
$objTableActual->addCell($slaughteredBox->show());
$objTableActual->endRow();

$cumulativeCasesBox = new textinput('cumulativeCases', $cumulativeCases, 'text');
$cumulativeCasesBox->setCss('passive_surveillance');
$cumulativeDeathsBox = new textinput('cumulativeDeaths', $cumulativeDeaths, 'text');
$cumulativeDeathsBox->setCss('passive_surveillance');
$cumulativeDestroyedBox = new textinput('cumulativeDestroyed', $cumulativeDestroyed, 'text');
$cumulativeDestroyedBox->setCss('passive_surveillance');
$cumulativeSlaughteredBox = new textinput('cumulativeSlaughtered', $cumulativeSlaughtered, 'text');
$cumulativeSlaughteredBox->setCss('passive_surveillance');

$objTableCumulative = $this->newObject('htmltable','htmlelements');
$objTableCumulative->cellspacing = 2;
$objTableCumulative->width = NULL;

$objTableCumulative->startRow();
$objTableCumulative->addCell($this->objLanguage->languageText('mod_ahis_cumulativecases', 'openaris'));
$objTableCumulative->addCell($cumulativeCasesBox->show());
$objTableCumulative->endRow();
$objTableCumulative->startRow();
$objTableCumulative->addCell($this->objLanguage->languageText('mod_ahis_cumulativedeaths', 'openaris'));
$objTableCumulative->addCell($cumulativeDeathsBox->show());
$objTableCumulative->endRow();
$objTableCumulative->startRow();
$objTableCumulative->addCell($this->objLanguage->languageText('mod_ahis_cumulativedestroyed', 'openaris'));
$objTableCumulative->addCell($cumulativeDestroyedBox->show());
$objTableCumulative->endRow();
$objTableCumulative->startRow();
$objTableCumulative->addCell($this->objLanguage->languageText('mod_ahis_cumulativeslaughtered', 'openaris'));
$objTableCumulative->addCell($cumulativeSlaughteredBox->show());
$objTableCumulative->endRow();

$speciesSet = new fieldset('speciesSet');
//$speciesSet->setExtra('style="max-width: 822px;"');
$speciesSet->setLegend($this->objLanguage->languageText('mod_ahis_speciesdetails', 'openaris'));
$speciesSet->addContent($objTableSpecies->show());

$actualSet = new fieldset('actualSet');
//$actualSet->setExtra('style="max-width: 822px;"');
$actualSet->setLegend($this->objLanguage->languageText('mod_ahis_actualdetails', 'openaris'));
$actualSet->addContent($objTableActual->show());

$cumulativeSet = new fieldset('cumulativeSet');
//$cumulativeSet->setExtra('style="max-width: 822px;"');
$cumulativeSet->setLegend($this->objLanguage->languageText('mod_ahis_cumulativedetails', 'openaris'));
$cumulativeSet->addContent($objTableCumulative->show());

$objTableArea2 = $this->newObject('htmltable','htmlelements');
$objTableArea2->cellspacing = 2;
$objTableArea2->width = NULL;

$objTableArea2->startRow();
$objTableArea2->addCell($speciesSet->show());
$objTableArea2->addCell($actualSet->show(), NULL, 'top', 'right', NULL, 'colspan="2"');
$objTableArea2->addCell($cumulativeSet->show(), NULL, 'top', 'right', NULL, 'colspan="2"');
$objTableArea2->endRow();
$objTableArea2->startRow();
$objTableArea2->addCell($this->objLanguage->languageText('phrase_createdby'), NULL, 'top', 'right', NULL, 'colspan="2"');
$objTableArea2->addCell($createdBox->show());
$objTableArea2->addCell($this->objLanguage->languageText('phrase_modifiedby'));
$objTableArea2->addCell($modifiedBox->show());
$objTableArea2->endRow();
$objTableArea2->startRow();
$objTableArea2->addCell($this->objLanguage->languageText('phrase_createddate'), NULL, 'top', 'right', NULL, 'colspan="2"');
$objTableArea2->addCell($createdDateBox->show());
$objTableArea2->addCell($this->objLanguage->languageText('phrase_modifieddate'));
$objTableArea2->addCell($modifiedDateBox->show());
$objTableArea2->endRow();
$objTableArea2->startRow();
$objTableArea2->addCell($cButton->show().$tab.$bButton->show().$tab.$aButton->show().$tab.$sButton->show(), NULL, 'top', 'center', NULL, 'colspan="6"');
$objTableArea2->endRow();

$objForm = new form('reportForm', $this->uri(array('action' => 'add_locality')));
$objForm->addToForm($objTableArea2->show());
$objForm->addRule('latitude', $this->objLanguage->languageText('mod_ahis_vallatitude', 'openaris'), 'numeric');
$objForm->addRule('longitude', $this->objLanguage->languageText('mod_ahis_vallongitude', 'openaris'), 'numeric');
$objForm->addRule('localityName', $this->objLanguage->languageText('mod_ahis_vallocalityname', 'openaris'), 'required');

$objTableArea3 = $this->newObject('htmltable','htmlelements');
$objTableArea3->cellspacing = 2;
$objTableArea3->width = NULL;

$objTableArea3->startHeaderRow();
$objTableArea3->addHeaderCell($this->objLanguage->languageText('mod_ahis_outbreakcode', 'openaris'));
$objTableArea3->addHeaderCell($this->objLanguage->languageText('word_species'));
$objTableArea3->addHeaderCell($this->objLanguage->languageText('word_age'));
$objTableArea3->addHeaderCell($this->objLanguage->languageText('word_sex'));
$objTableArea3->addHeaderCell($this->objLanguage->languageText('mod_ahis_norisk', 'openaris'));
$objTableArea3->addHeaderCell($this->objLanguage->languageText('mod_ahis_nocases', 'openaris'));
$objTableArea3->addHeaderCell($this->objLanguage->languageText('mod_ahis_nodeaths', 'openaris'));
$objTableArea3->addHeaderCell($this->objLanguage->languageText('mod_ahis_nodestroyed', 'openaris'));
$objTableArea3->addHeaderCell($this->objLanguage->languageText('mod_ahis_noslaughtered', 'openaris'));
$objTableArea3->addHeaderCell($this->objLanguage->languageText('mod_ahis_totalcases', 'openaris'));
$objTableArea3->addHeaderCell($this->objLanguage->languageText('mod_ahis_totaldeaths', 'openaris'));
$objTableArea3->addHeaderCell($this->objLanguage->languageText('mod_ahis_totaldestroyed', 'openaris'));
$objTableArea3->addHeaderCell($this->objLanguage->languageText('mod_ahis_totalslaughtered', 'openaris'));
$objTableArea3->addHeaderCell($this->objLanguage->languageText('phrase_createdby'));
$objTableArea3->addHeaderCell($this->objLanguage->languageText('phrase_createddate'));
$objTableArea3->addHeaderCell($this->objLanguage->languageText('phrase_modifiedby'));
$objTableArea3->addHeaderCell($this->objLanguage->languageText('phrase_modifieddate'));
$objTableArea3->endHeaderRow();

$scriptUri = $this->getResourceURI('util.js');
$this->appendArrayVar('headerParams', "<script type='text/javascript' src='$scriptUri'></script>");

$content = $objTableArea1->show()."<br />".$objForm->show()."<br />".$objTableArea3->show();
echo $objHeading->show()."<br />".$content;