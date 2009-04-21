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

$objTable = $this->getObject('htmltable', 'htmlelements');
$objTable->width = NULL;
$objTable->attributes = "style='min-width: 65%;'";
//$objTable->trClass = "ahisLinkTable";

$objTable->startRow();
$link = new link($this->uri(array('action' => 'employee_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_employeeadmin', 'ahis');
$objTable->addCell($link->show(),NULL,NULL,'center');
$link = new link($this->uri(array('action' => 'age_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_ageadmin', 'ahis');
$objTable->addCell($link->show(),NULL,NULL,'center');
$link = new link($this->uri(array('action' => 'geography_level3_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_geo3admin', 'ahis');
$objTable->addCell($link->show(),NULL,NULL,'center');
$objTable->endRow();

$objTable->startRow();
$link = new link($this->uri(array('action' => 'title_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_titleadmin', 'ahis');
$objTable->addCell($link->show(),NULL,NULL,'center');
$link = new link($this->uri(array('action' => 'sex_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_sexadmin', 'ahis');
$objTable->addCell($link->show(),NULL,NULL,'center');
$link = new link($this->uri(array('action' => 'geography_level2_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_geo2admin', 'ahis');
$objTable->addCell($link->show(),NULL,NULL,'center');
$objTable->endRow();

$objTable->startRow();
$link = new link($this->uri(array('action' => 'status_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_statusadmin', 'ahis');
$objTable->addCell($link->show(),NULL,NULL,'center');
$link = new link($this->uri(array('action' => 'production_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_productionadmin', 'ahis');
$objTable->addCell($link->show(),NULL,NULL,'center');
$link = new link($this->uri(array('action' => 'territory_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_locationadmin', 'ahis');
$objTable->addCell($link->show(),NULL,NULL,'center');
$objTable->endRow();

$objTable->startRow();
$link = new link($this->uri(array('action' => 'report_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_reportadmin', 'ahis');
$objTable->addCell($link->show(),NULL,NULL,'center');
$link = new link($this->uri(array('action' => 'control_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_controladmin', 'ahis');
$objTable->addCell($link->show(),NULL,NULL,'center');
$link = new link($this->uri(array('action' => 'outbreak_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_outbreakstatusadmin', 'ahis');
$objTable->addCell($link->show(),NULL,NULL,'center');
$objTable->endRow();

$objTable->startRow();
$link = new link($this->uri(array('action' => 'diagnosis_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_diagnosisadmin', 'ahis');
$objTable->addCell($link->show(),NULL,NULL,'center');
$link = new link($this->uri(array('action' => 'quality_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_qualityadmin', 'ahis');
$objTable->addCell($link->show(),NULL,NULL,'center');
$link = new link($this->uri(array('action' => 'role_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_roleadmin', 'ahis');
$objTable->addCell($link->show(),NULL,NULL,'center');
$objTable->endRow();

$objTable->startRow();
$link = new link($this->uri(array('action' => 'department_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_departmentadmin', 'ahis');
$objTable->addCell($link->show(),NULL,NULL,'center');
$link = new link($this->uri(array('action' => 'disease_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_diseaseadmin', 'ahis');
$objTable->addCell($link->show(),NULL,NULL,'center');
$link = new link($this->uri(array('action' => 'test_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_testadmin', 'ahis');
$objTable->addCell($link->show(),NULL,NULL,'center');

$objTable->endRow();

$objTable->startRow();
$link = new link($this->uri(array('action' => 'testresult_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_testresultadmin', 'ahis');
$objTable->addCell($link->show(),NULL,NULL,'center');
$link = new link($this->uri(array('action' => 'sample_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_sampleadmin', 'ahis');
$objTable->addCell($link->show(),NULL,NULL,'center');
$link = new link($this->uri(array('action' => 'survey_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_surveyadmin', 'ahis');
$objTable->addCell($link->show(),NULL,NULL,'center');
$objTable->endRow();

$objTable->startRow();
$link = new link($this->uri(array('action' => 'farmingsystem_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_farmingsystemadmin', 'ahis');
$objTable->addCell($link->show(),NULL,NULL,'center');
$link = new link($this->uri(array('action' => 'vaccinationhistory_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_vaccinationadmin', 'ahis');
$objTable->addCell($link->show(),NULL,NULL,'center');
$link = new link($this->uri(array('action' => 'species_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_speciesadmin', 'ahis');
$objTable->addCell($link->show(),NULL,NULL,'center');
$objTable->endRow();

$objTable->startRow();
$link = new link($this->uri(array('action' => 'breed_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_breedadmin', 'ahis');
$objTable->addCell($link->show(),NULL,NULL,'center');
$link = new link($this->uri(array('action' => 'causative_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_causativeadmin', 'ahis');
$objTable->addCell($link->show(),NULL,NULL,'center');
$objTable->endRow();

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


$objLayer = new layer();
$objLayer->addToStr($objHeading->show()."<hr class='ahis'/>".$objTable->show());
$objLayer->align = 'center';

echo $objLayer->show();
