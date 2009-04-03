<?php
/**
 * ahis Meat Inspection Template
 *
 * Template to select passive outbreak reporting officer
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
 * @author    Samuel Onyach <sonyach@icsit.jkuat.ac.ke>
 * @copyright 2008 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: meat_inspection_tpl.php
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
  $title = 'Meat Inspection';
$objHeading = $this->getObject('htmlheading','htmlelements');
$objHeading->str = $title;
$objHeading->type = 2;

$this->loadClass('textinput','htmlelements');
$this->loadClass('dropdown','htmlelements');
$this->loadClass('layer','htmlelements');
$this->loadClass('label', 'htmlelements');



  $formAction = 'saveinspectiondata';
  
    $buttonText = 'Save';

$form = new form ('add', $this->uri(array('action'=>$formAction)));

$formTable = $this->newObject('htmltable', 'htmlelements');

/*$district = new textinput('district');
$district->size = 50;
*/
$geo2Drop = new dropdown('district');
$geo2Drop->addFromDB($arrayGeo2, 'name', 'name'); 
$geo2Drop->setSelected($geo2Id); 

//district name
$label = new label ('District', 'input_district');

$formTable->startRow();
$formTable->addCell($label->show());
$formTable->addCell($geo2Drop->show());
$formTable->endRow();
//date of inspection
$datePicker = $this->newObject('datepicker', 'htmlelements');
$datePicker->name = 'inspectiondate';
$formTable->startRow();
$formTable->addCell('Inspection Date (Date when disease was found)');
$formTable->addCell($datePicker->show());
$formTable->endRow();


//number of cases
$label = new label ('Number of Cases', 'input_no_of_cases');
$num_of_cases= new textinput('num_of_cases');
$num_of_cases->size = 50;
//number at risk
$label2 = new label ('Number at Risk', 'input_no_at_risk');
$num_at_risk = new textinput('num_at_risk');
$num_at_risk->size = 50;

$formTable->startRow();
$formTable->addCell($label->show());
$formTable->addCell($num_of_cases->show());
$formTable->addCell($label2->show());
$formTable->addCell($num_at_risk->show());
$formTable->endRow();

//container-table
$topTable = $this->newObject('htmltable', 'htmlelements');

$topTable->startRow();
$topTable->addCell($formTable->show());
$topTable->endRow();
$form->addToForm($topTable->show());
//buttons
$button = new button ('saveinspectiondata', 'Save');
$button->setToSubmit();

$btcancel = new button ('cancel', 'Cancel');
$btcancel->setToSubmit();

$form->addToForm($button->show());
$form->addToForm($btcancel->show());
$objLayer = new layer();
$objLayer->addToStr($objHeading->show()."<hr class='ahis' />".$form->show());
$objLayer->align = 'center';

echo $objLayer->show();
