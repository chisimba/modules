<?php
/**
 * ahis Passive Surveillance Species Template
 *
 * Template for capturing passive surveillance species data
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
$objHeading->str = $this->objLanguage->languageText('phrase_passive')." ".$this->objLanguage->languageText('word_species');
$objHeading->type = 2;

$this->loadClass('textinput','htmlelements');
$this->loadClass('dropdown','htmlelements');
$this->loadClass('button','htmlelements');
$this->loadClass('layer','htmlelements');
$this->loadClass('form','htmlelements');

$sButton = new button('enter', $this->objLanguage->languageText('word_next'));
$sButton->setToSubmit();
$backUri = $this->uri(array('action'=>'passive_outbreak'));
$bButton = new button('back', $this->objLanguage->languageText('word_back'), "javascript: document.location='$backUri'");
$cButton = new button('clear', $this->objLanguage->languageText('word_clear'), "javascript: clearPassiveSpecies()");

$refNoBox = new textinput('refNo', $refNo);
$monthBox = new textinput('month', date('F', strtotime($calendardate)));
$yearBox = new textinput('year', date('Y', strtotime($calendardate)), 'text', 8);
//$yearBox->extra = $monthBox->extra = $refNoBox->extra = "readonly";

$geo2Drop = new dropdown('geo2Id');
$geo2Drop->addFromDB($arrayGeo2, 'name', 'id');
$geo2Drop->setSelected($geo2Id);
//$geo2Drop->extra = 'disabled';

$speciesDrop = new dropdown('speciesId');
$speciesDrop->addFromDB($arraySpecies, 'name', 'id');
$speciesDrop->setSelected($speciesId);
$ageDrop = new dropdown('ageId');
$ageDrop->addFromDB($arrayAge, 'name', 'id');
$ageDrop->setSelected($ageId);
$sexDrop = new dropdown('sexId');
$sexDrop->addFromDB($arraySex, 'name', 'id');
$sexDrop->setSelected($sexId);
$productionDrop = new dropdown('productionId');
$productionDrop->addFromDB($arrayProduction, 'name', 'id');
$productionDrop->setSelected($productionId);
$controlDrop = new dropdown('controlId');
$controlDrop->addFromDB($arrayControl, 'name', 'id');
$controlDrop->setSelected($controlId);
$basisDrop = new dropdown('basisId');
$basisDrop->addFromDB($arrayBasis, 'name', 'id');
$basisDrop->setSelected($basisId);
//$oStatusDrop = new dropdown('oStatusId');
//$oStatusDrop->addFromDB($arrayOStatus, 'name', 'id');
//$oStatusDrop->setSelected($oStatusId);

$susceptibleBox = new textinput('susceptible', $susceptible, 'text', 4);
$casesBox = new textinput('cases', $cases, 'text', 4);
$deathsBox = new textinput('deaths', $deaths, 'text', 4);
$vaccinatedBox = new textinput('vaccinated', $vaccinated, 'text', 4);
$slaughteredBox = new textinput('slaughtered', $slaughtered, 'text', 4);
$destroyedBox = new textinput('destroyed', $destroyed, 'text', 4);
$productionBox = new textinput('production', $production, 'text', 4);
$newcasesBox = new textinput('newcases', $newcases, 'text', 4);
$recoveredBox = new textinput('recovered', $recovered, 'text', 4);
$prophylacticBox = new textinput('prophylactic', $prophylactic, 'text', 4);

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
$objTable->addCell($this->objLanguage->languageText('word_species').": ");
$objTable->addCell($speciesDrop->show());
$objTable->addCell($this->objLanguage->languageText('phrase_age').": ");
$objTable->addCell($ageDrop->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_sex').": ");
$objTable->addCell($sexDrop->show());
$objTable->addCell($this->objLanguage->languageText('word_production').": ");
$objTable->addCell($productionDrop->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('phrase_control').": ");
$objTable->addCell($controlDrop->show());
$objTable->addCell($this->objLanguage->languageText('phrase_diagnosis').": ");
$objTable->addCell($basisDrop->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_susceptible').": ");
$objTable->addCell($susceptibleBox->show());
$objTable->addCell($this->objLanguage->languageText('word_cases').": ");
$objTable->addCell($casesBox->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_deaths').": ");
$objTable->addCell($deathsBox->show());
$objTable->addCell($this->objLanguage->languageText('word_vaccinated').": ");
$objTable->addCell($vaccinatedBox->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_slaughtered').": ");
$objTable->addCell($slaughteredBox->show());
$objTable->addCell($this->objLanguage->languageText('word_destroyed').": ");
$objTable->addCell($destroyedBox->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_production').": ");
$objTable->addCell($productionBox->show());
$objTable->addCell($this->objLanguage->languageText('phrase_newcases').": ");
$objTable->addCell($newcasesBox->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_recovered').": ");
$objTable->addCell($recoveredBox->show());
$objTable->addCell($this->objLanguage->languageText('word_prophylactic').": ");
$objTable->addCell($prophylacticBox->show());
$objTable->endRow();

$objTable->startRow();
$objTable->addCell('');
$objTable->addCell($bButton->show());
$objTable->addCell($cButton->show());
$objTable->addCell($sButton->show(),NULL,'top','right');
$objTable->addCell('');
$objTable->endRow();

$valStr = $this->objLanguage->languageText('mod_ahis_valnumeric', 'ahis');

$objForm = new form('reportForm', $this->uri(array('action' => 'passive_vaccine')));
$objForm->addToForm($objTable->show());
$objForm->addRule('susceptible', $valStr." ".$objLanguage->languageText('word_susceptible'), 'numeric');
$objForm->addRule('cases', $valStr." ".$objLanguage->languageText('word_cases'), 'numeric');
$objForm->addRule('deaths', $valStr." ".$objLanguage->languageText('word_deaths'), 'numeric');
$objForm->addRule('vaccinated', $valStr." ".$objLanguage->languageText('word_vaccinated'), 'numeric');
$objForm->addRule('slaughtered', $valStr." ".$objLanguage->languageText('word_slaughtered'), 'numeric');
$objForm->addRule('destroyed', $valStr." ".$objLanguage->languageText('word_destroyed'), 'numeric');
$objForm->addRule('production', $valStr." ".$objLanguage->languageText('word_production'), 'numeric');
$objForm->addRule('newcases', $valStr." ".$objLanguage->languageText('phrase_newcases'), 'numeric');
$objForm->addRule('recovered', $valStr." ".$objLanguage->languageText('word_recovered'), 'numeric');
$objForm->addRule('prophylactic', $valStr." ".$objLanguage->languageText('word_prophylactic'), 'numeric');

$objLayer = new layer();
$objLayer->addToStr($objHeading->show()."<hr />".$objForm->show());
$objLayer->align = 'center';

$scriptUri = $this->getResourceURI('util.js');
$this->appendArrayVar('headerParams', "<script type='text/javascript' src='$scriptUri'></script>");

echo $objLayer->show();