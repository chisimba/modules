<?php
/**
 * ahis Active Survaillance Add Test screen Template
 *
 * Template for capturing active surveillance test data
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
 * @version   $Id: active_addtest_tpl.php 
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
$objHeading->str = $this->objLanguage->languageText('phrase_active')." ".$this->objLanguage->languageText('phrase_addtest');
$objHeading->type = 2;

$this->loadClass('textinput','htmlelements');
$this->loadClass('dropdown','htmlelements');
$this->loadClass('button','htmlelements');
$this->loadClass('layer','htmlelements');


$finButton = new button('finish', $this->objLanguage->languageText('word_finish'));
$finButton->setToSubmit();
$backUri = $this->uri(array('action'=>'active_surveillance'));
$backButton = new button('back', $this->objLanguage->languageText('word_back'), "javascript: document.location='$backUri'");

$testTypeDrop = new dropdown('testtype');
$testTypeDrop->addFromDB($arraytest, 'name', 'name');
$testTypeDrop->setSelected($testtype);

$diseaseDrop = new dropdown('disease');
$diseaseDrop->addFromDB($arraydisease, 'name', 'name');
$diseaseDrop->setSelected($disease);
$diseaseDrop->extra = 'disabled';

$activeBox = new textinput('activeid',$activeid,'hidden');
$campBox = new textinput('campName',$campName);
$sensitivityBox = new textinput('sensitivity', $sensitivity);
$specificityBox = new textinput('specificity', $specificity);
$campBox->extra ="readonly";


$objTable = $this->getObject('htmltable','htmlelements');
$objTable->cellspacing = 2;
$objTable->width = NULL;
$objTable->cssClass = 'min50';

$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_campaign')." ".$this->objLanguage->languageText('word_name').":");
$objTable->addCell($campBox->show());
$objTable->addCell('');
$objTable->addCell('');
$objTable->addCell($this->objLanguage->languageText('word_disease').": ");
$objTable->addCell($diseaseDrop->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('phrase_testtype').": $tab");
$objTable->addCell($testTypeDrop->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_sensitivity').": $tab");
$objTable->addCell($sensitivityBox->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_specificity').": $tab");
$objTable->addCell($specificityBox->show());
$objTable->addCell($activeBox->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell('&nbsp');
$objTable->endRow();

$objTable->startRow();
$objTable->addCell('');
$objTable->addCell($backButton->show());

$objTable->addCell($nbsp);
$objTable->addCell($nbsp);
$objTable->addCell($finButton->show());//,'top','right');
$objTable->addCell('');
$objTable->endRow();

$this->loadClass('form','htmlelements');
$objForm = new form('reportForm', $this->uri(array('action' => 'active_insert')));
$objForm->addToForm($objTable->show());
$objForm->addRule('sensitivity', $this->objLanguage->languageText('mod_ahis_valsens', 'ahis'), 'numeric');
$objForm->addRule('sensitivity', $this->objLanguage->languageText('mod_ahis_valsens', 'ahis'), 'required');
$objForm->addRule('specificity', $this->objLanguage->languageText('mod_ahis_valspec', 'ahis'), 'numeric');
$objForm->addRule('specificity', $this->objLanguage->languageText('mod_ahis_valspec', 'ahis'), 'required');

$objLayer = new layer();
$objLayer->addToStr($objHeading->show()."<hr class='ahis' />".$objForm->show());
$objLayer->align = 'center';

$scriptUri = $this->getResourceURI('util.js');
$this->appendArrayVar('headerParams', "<script type='text/javascript' src='$scriptUri'></script>");

echo $objLayer->show();


?>