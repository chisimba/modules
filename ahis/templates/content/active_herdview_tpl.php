<?php
/**
 * ahis Active Survaillance Herd screen Template
 *
 * Template for capturing active surveillance herd data
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
$objHeading->str = $this->objLanguage->languageText('phrase_active')." ".$this->objLanguage->languageText('word_herd');
$objHeading->type = 2;


$this->loadClass('layer','htmlelements');


$objTable = $this->getObject('htmltable','htmlelements');


$addButton = new button('add', $this->objLanguage->languageText('word_add'));
$addButton->setToSubmit();


$objTable->cellspacing = 2;
$objTable->width = NULL;
$objTable->cssClass = 'min50';


$objTable->startRow();
$objTable->addCell("<h6>".$this->objLanguage->languageText('word_campaign')." ".$this->objLanguage->languageText('word_name').": </h6>");
$objTable->addCell($campName);
$objTable->addCell('&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp');
$objTable->addCell('&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp');
$objTable->addCell("<h6>".$this->objLanguage->languageText('word_disease').": </h6>");
$objTable->addCell($disease);
$objTable->endRow();
$objTable->startRow();$objTable = $this->getObject('htmltable','htmlelements');

$objTable->cellspacing = 2;
$objTable->width = NULL;
$objTable->cssClass = 'min50';


$objTable->addCell($this->objLanguage->languageText('mod_ahis_reportofficer','ahis').": $tab");
$objTable->addCell($reportofficer);
$objTable->addCell('');
$objTable->addCell('');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('phrase_reportid').": $tab");
$objTable->addCell('');
$objTable->addCell('');
$objTable->endRow();

$objLayer = new layer();
$objLayer->addToStr($objHeading->show()."<hr class='ahis' />".$objTable->show());
$objLayer->align = 'center';

echo $objLayer->show();

$objTable = new htmlTable();
$objTable->cellpadding =4;
$objTable->cellspacing = 2;
$objTable->width = NULL;
$objTable->cssClass = 'min50';

$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_farming')." ".$this->objLanguage->languageText('word_localities'),'','','','heading');

$objTable->addCell($this->objLanguage->languageText('phrase_geolevel2'), '', '', '', 'heading');

$objTable->addCell($this->objLanguage->languageText('phrase_geolevel3'), '', '', '', 'heading');
$objTable->addCell($this->objLanguage->languageText('word_farm')." ".$this->objLanguage->languageText('word_name'), '', '', '', 'heading');
$objTable->addCell($this->objLanguage->languageText('word_farming')." ".$this->objLanguage->languageText('word_system'), '', '', '', 'heading');
$objTable->addCell($this->objLanguage->languageText('word_action'), '', '', '', 'heading');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell('');
$objTable->addCell('');
$objTable->addCell('');
$objTable->addCell('');
$objTable->addCell('');
$objTable->addCell('');
$objTable->addCell($addButton->show());
$objTable->endRow();

$this->loadClass('form','htmlelements');
$objForm = new form('reportForm', $this->uri(array('action' => 'active_addherd')));
$objForm->addToForm($objTable->show());

$objLayer = new layer();
$objLayer->addToStr($objHeading->show()."<hr />".$objForm->show());
$objLayer->align = 'center';

echo $objLayer->show();

?>













