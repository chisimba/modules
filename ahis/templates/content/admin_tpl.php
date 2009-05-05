<?php
/**
 * ahis admin Template
 *
 * Administration home template
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
 * @copyright 2008 AVOIR
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

$this->loadClass('layer', 'htmlelements');
$this->loadClass('link', 'htmlelements');

$objHeading = $this->getObject('htmlheading', 'htmlelements');
$objHeading->type = 2;
$objHeading->str = $this->objLanguage->languageText('mod_ahis_adminheading', 'ahis');

$objSubHeading = $this->newObject('htmlheading', 'htmlelements');
$objSubHeading->type = 3;
$objSubHeading->str = $this->objLanguage->languageText('mod_ahis_arisusers', 'ahis');

$objDataHeading = $this->newObject('htmlheading', 'htmlelements');
$objDataHeading->type = 3;
$objDataHeading->str = $this->objLanguage->languageText('mod_ahis_editdataentry', 'ahis');

$employeeLink = new link($this->uri(array('action' => 'employee_admin')));
$employeeLink->link = $this->objLanguage->languageText('mod_ahis_employeeadmin', 'ahis');

/*$objTable->startRow();
$link = new link($this->uri(array('action' => 'animalmovement_admin')));
$link->link = "Animal Movement Admin";
$objTable->addCell($link->show(),NULL,NULL,'center');
$link = new link($this->uri(array('action' => 'livestockimport_admin')));
$link->link = "Livestock Import Admin";
$objTable->addCell($link->show(),NULL,NULL,'center');
$link = new link($this->uri(array('action' => 'livestockexport_admin')));
$link->link = "Livestock Export Admin";
$objTable->addCell($link->show(),NULL,NULL,'center');
$objTable->endRow();*/
$userList = "<strong>".$this->objLanguage->languageText('mod_ahis_userfields', 'ahis')."</strong><ul class='admin'>";
$link = new link($this->uri(array('action' => 'department_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_departmentadmin', 'ahis');
$userList .= "<li>".$link->show()."</li>";
$link = new link($this->uri(array('action' => 'role_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_roleadmin', 'ahis');
$userList .= "<li>".$link->show()."</li>";
$link = new link($this->uri(array('action' => 'status_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_statusadmin', 'ahis');
$userList .= "<li>".$link->show()."</li>";
$link = new link($this->uri(array('action' => 'title_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_titleadmin', 'ahis');
$userList .= "<li>".$link->show()."</li>";
$userList .= "</ul>";

$dataList = "<strong>".$this->objLanguage->languageText('mod_ahis_datafields', 'ahis')."</strong><ul class='admin'>";
$link = new link($this->uri(array('action' => 'age_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_ageadmin', 'ahis');
$dataList .= "<li>".$link->show()."</li>";
$link = new link($this->uri(array('action' => 'diagnosis_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_diagnosisadmin', 'ahis');
$dataList .= "<li>".$link->show()."</li>";
$link = new link($this->uri(array('action' => 'breed_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_breedadmin', 'ahis');
$dataList .= "<li>".$link->show()."</li>";
$link = new link($this->uri(array('action' => 'causative_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_causativeadmin', 'ahis');
$dataList .= "<li>".$link->show()."</li>";
$link = new link($this->uri(array('action' => 'control_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_controladmin', 'ahis');
$dataList .= "<li>".$link->show()."</li>";
$link = new link($this->uri(array('action' => 'disease_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_diseaseadmin', 'ahis');
$dataList .= "<li>".$link->show()."</li>";
$link = new link($this->uri(array('action' => 'geography_level2_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_geo2admin', 'ahis');
$dataList .= "<li>".$link->show()."</li>";
$link = new link($this->uri(array('action' => 'geography_level3_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_geo3admin', 'ahis');
$dataList .= "<li>".$link->show()."</li>";
$link = new link($this->uri(array('action' => 'farmingsystem_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_farmingsystemadmin', 'ahis');
$dataList .= "<li>".$link->show()."</li>";
$link = new link($this->uri(array('action' => 'territory_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_locationadmin', 'ahis');
$dataList .= "<li>".$link->show()."</li>";
$link = new link($this->uri(array('action' => 'outbreak_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_outbreakstatusadmin', 'ahis');
$dataList .= "<li>".$link->show()."</li>";
$link = new link($this->uri(array('action' => 'production_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_productionadmin', 'ahis');
$dataList .= "<li>".$link->show()."</li>";
$link = new link($this->uri(array('action' => 'quality_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_qualityadmin', 'ahis');
$dataList .= "<li>".$link->show()."</li>";
$link = new link($this->uri(array('action' => 'report_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_reportadmin', 'ahis');
$dataList .= "<li>".$link->show()."</li>";
$link = new link($this->uri(array('action' => 'test_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_testadmin', 'ahis');
$dataList .= "<li>".$link->show()."</li>";
$link = new link($this->uri(array('action' => 'testresult_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_testresultadmin', 'ahis');
$dataList .= "<li>".$link->show()."</li>";
$link = new link($this->uri(array('action' => 'sample_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_sampleadmin', 'ahis');
$dataList .= "<li>".$link->show()."</li>";
$link = new link($this->uri(array('action' => 'sex_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_sexadmin', 'ahis');
$dataList .= "<li>".$link->show()."</li>";
$link = new link($this->uri(array('action' => 'species_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_speciesadmin', 'ahis');
$dataList .= "<li>".$link->show()."</li>";
$link = new link($this->uri(array('action' => 'survey_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_surveyadmin', 'ahis');
$dataList .= "<li>".$link->show()."</li>";

$link = new link($this->uri(array('action' => 'vaccinationhistory_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_vaccinationadmin', 'ahis');
$dataList .= "<li>".$link->show()."</li>";
$dataList .= "</ul>";


//$objLayer = new layer();
//$objLayer->addToStr($objHeading->show()."<hr class='ahis'/>".$objTable->show());
//$objLayer->align = 'center';

//echo $objLayer->show();
$content = $objHeading->show().$objSubHeading->show()."<div class='admin'>".$employeeLink->show()."<br /><br />";
$content .= $objDataHeading->show().$this->objLanguage->languageText('mod_ahis_selectformfield', 'ahis')."</div>";
$content .= "<br />$userList<br />$dataList";

echo $content;