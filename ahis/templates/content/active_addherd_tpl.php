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
    $hstr = $this->objLanguage->languageText('phrase_active')." ".$this->objLanguage->languageText('word_edit')."  ".$this->objLanguage->languageText('word_herd');
    $formUri = $this->uri(array('action'=>'newherd_insert', 'id'=>$id));
    $record = $this->objNewherd->getRow('id', $id);
    
} else {
    $hstr = $this->objLanguage->languageText('phrase_active')." ".$this->objLanguage->languageText('word_add')."  ".$this->objLanguage->languageText('word_herd');
    $formUri = $this->uri(array('action'=>'newherd_insert'));
    $record['geolevel2'] = '';
    $record['geolevel3'] = '';
    $record['territory'] = '';
    $record['farm'] = '';
    $record['farmingtype'] = '';

}

$objHeading = $this->getObject('htmlheading','htmlelements');
$objHeading->str = $hstr;
$objHeading->type = 2;


$this->loadClass('textinput','htmlelements');
$this->loadClass('dropdown','htmlelements');
$this->loadClass('button','htmlelements');
$this->loadClass('layer','htmlelements');


$addButton = new button('add', $this->objLanguage->languageText('word_add'));
$addButton->setToSubmit();
$backUri = $this->uri(array('action'=>'active_newherd'));
$backButton = new button('cancel', $this->objLanguage->languageText('word_back'), "javascript: document.location='$backUri'");


$territoryDrop = new dropdown('territory');
$territoryDrop->addFromDB($arrayTerritory, 'name', 'name');
$territoryDrop->setSelected($record['territory']);

$farmsystemDrop = new dropdown('farmingsystem');
$farmsystemDrop->addFromDB($arrayFarmingsystem, 'name', 'name');
$farmsystemDrop->setSelected($record['farmingtype']);

$geo2Drop = new dropdown('Geo2');
$geo2Drop->addFromDB($arraygeo2, 'name', 'name');
$geo2Drop->setSelected($geo2);

$geo3Drop = new dropdown('Geo3');
$geo3Drop->addFromDB($arraygeo3, 'name', 'name');
$geo3Drop->setSelected($record['farmingtype']);



$farmBox = new textinput('farm', $record['farmname']);

$activeBox = new textinput('activeid',$activeid,'hidden');
$objTable = $this->getObject('htmltable','htmlelements');
$objTable->cellspacing = 2;
$objTable->width = NULL;
$objTable->cssClass = 'min50';


$objTable->startRow();

$objTable->addCell($this->objLanguage->languageText('word_territory'));
$objTable->addCell($territoryDrop->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('phrase_geolevel3'));
$objTable->addCell($geo3Drop->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('phrase_geolevel2').": ");
$objTable->addCell($geo2Drop->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_farm').": $tab");
$objTable->addCell($farmBox->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_farming')." ".$this->objLanguage->languageText('word_system')." $tab");
$objTable->addCell($farmsystemDrop->show());
$objTable->addCell($activeBox->show());
$objTable->endRow();
$objTable->startRow();
$objTable->endRow();
$objTable->startRow();
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