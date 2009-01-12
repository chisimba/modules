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
$objTable->trClass = "ahisLinkTable";

$objTable->startRow();
$link = new link($this->uri(array('action' => 'employee_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_employeeadmin', 'ahis');
$objTable->addCell($link->show());
$link = new link($this->uri(array('action' => 'age_group_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_agegroupadmin', 'ahis');
$objTable->addCell($link->show());
$link = new link($this->uri(array('action' => 'geography_level3_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_geo3admin', 'ahis');
$objTable->addCell($link->show());
$objTable->endRow();

$objTable->startRow();
$link = new link($this->uri(array('action' => 'title_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_titleadmin', 'ahis');
$objTable->addCell($link->show());
$link = new link($this->uri(array('action' => 'sex_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_sexadmin', 'ahis');
$objTable->addCell($link->show());
$link = new link($this->uri(array('action' => 'geography_level2_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_geo2admin', 'ahis');
$objTable->addCell($link->show());
$objTable->endRow();

$objTable->startRow();
$link = new link($this->uri(array('action' => 'status_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_statusadmin', 'ahis');
$objTable->addCell($link->show());
$link = new link($this->uri(array('action' => 'production_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_productionadmin', 'ahis');
$objTable->addCell($link->show());
$link = new link($this->uri(array('action' => 'create_territory')));
$link->link = $this->objLanguage->languageText('mod_ahis_territoryadmin', 'ahis');
$objTable->addCell($link->show());
$objTable->endRow();

$objTable->startRow();
$link = new link($this->uri(array('action' => 'report_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_reportadmin', 'ahis');
$objTable->addCell($link->show());
$link = new link($this->uri(array('action' => 'control_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_controlmeasureadmin', 'ahis');
$objTable->addCell($link->show());
$link = new link($this->uri(array('action' => 'outbreak_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_outbreakstatusadmin', 'ahis');
$objTable->addCell($link->show());
$objTable->endRow();

$objTable->startRow();
$link = new link($this->uri(array('action' => 'diagnosis_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_diagnosisadmin', 'ahis');
$objTable->addCell($link->show());
$link = new link($this->uri(array('action' => 'quality_admin')));
$link->link = $this->objLanguage->languageText('mod_ahis_reportqualityadmin', 'ahis');
$objTable->addCell($link->show());
$objTable->endRow();

$objLayer = new layer();
$objLayer->addToStr($objHeading->show()."<hr class='ahis'/>".$objTable->show());
$objLayer->align = 'center';

echo $objLayer->show();
