<?php
/**
 * ahis Add Territory Template
 *
 * Template for creating a territory
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


$this->loadClass('textinput','htmlelements');
$this->loadClass('button','htmlelements');
$this->loadClass('form','htmlelements');
$this->loadClass('dropdown','htmlelements');

$objHeading = $this->getObject('htmlheading','htmlelements');
$objHeading->type = 2;
$objHeading->str = $this->objLanguage->languageText('mod_ahis_createterritory', 'ahis');

$nameText = new textinput('territory');
$latNText = new textinput('latitude_north');
$latSText = new textinput('latitude_south');
$lonEText = new textinput('longitude_east');
$lonWText = new textinput('longitude_west');
$areaText = new textinput('area');
$unitText = new textinput('unit_of_measure');

$geo2Drop = new dropdown('geo2');
$geo2Drop->addFromDB($geo2, 'name', 'id');

$sButton = new button('enter', $this->objLanguage->languageText('word_enter'));
$sButton->setToSubmit();
$backUri = $this->uri(array('action' => 'admin'));
$bButton = new button('back', $this->objLanguage->languageText('word_back'), "javascript: document.location='$backUri'");

$objTable = $this->getObject('htmltable','htmlelements');
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText("word_territory"));
$objTable->addCell($nameText->show());
$objTable->endRow();

$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText("mod_ahis_latitudenorth", "ahis"));
$objTable->addCell($latNText->show());
$objTable->addCell($this->objLanguage->languageText("mod_ahis_latitudesouth", "ahis"));
$objTable->addCell($latSText->show());
$objTable->endRow();

$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText("mod_ahis_longitudeeast", "ahis"));
$objTable->addCell($lonEText->show());
$objTable->addCell($this->objLanguage->languageText("mod_ahis_longitudewest", "ahis"));
$objTable->addCell($lonWText->show());
$objTable->endRow();

$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText("word_area"));
$objTable->addCell($areaText->show());
$objTable->addCell($this->objLanguage->languageText("mod_ahis_geolevel", "ahis")." 2");
$objTable->addCell($geo2Drop->show());
$objTable->endRow();

$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText("mod_ahis_unitofmeasure", "ahis"));
$objTable->addCell($unitText->show());
$objTable->endRow();

$objTable->startRow();
$objTable->addCell("&nbsp;");
$objTable->addCell($sButton->show());
$objTable->addCell($bButton->show());
$objTable->endRow();

$objForm = new form("territoryform", $this->uri(array('action' => 'territory_insert')));
$objForm->addToForm($objTable->show());
$objForm->addRule('territory', $this->objLanguage->languageText('mod_ahis_territoryrequired', 'ahis'), 'required');
$objForm->addRule('latitude_north', $this->objLanguage->languageText('mod_ahis_latitudenorthrequired', 'ahis'), 'required');
$objForm->addRule('latitude_south', $this->objLanguage->languageText('mod_ahis_latitudesouthrequired', 'ahis'), 'required');
$objForm->addRule('longitude_east', $this->objLanguage->languageText('mod_ahis_longitudeeastrequired', 'ahis'), 'required');
$objForm->addRule('longitude_west', $this->objLanguage->languageText('mod_ahis_longitudewestrequired', 'ahis'), 'required');
$objForm->addRule('area', $this->objLanguage->languageText('mod_ahis_arearequired', 'ahis'), 'required');
$objForm->addRule('unit_of_measure', $this->objLanguage->languageText('mod_ahis_unitofmeasurementrequired', 'ahis'), 'required');

$heading = $objHeading->show()."<hr />";
$body = $objForm->show();

echo $heading.$body;