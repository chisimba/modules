<?php
/**
 * 
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
 * @author    Isaac N. Oteyo <ioteyo@jkuat.ac.ke, isaacoteyo@gmail.com>
 * @copyright 2009 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: add_animalmovement_tpl.php 12780 2009-03-11 10:46:10Z rosina $
 * @link      http://avoir.uwc.ac.za, http://www.jkuat.ac.ke
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

$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('csslayout', 'htmlelements');
$this->loadClass('layer', 'htmlelements');
$this->loadClass('datepicker', 'htmlelements');

// Create an instance of the css layout class
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
// Set columns to 2
$cssLayout->setNumColumns(3);
// get the sidebar object
$this->leftMenu = $this->newObject('usermenu', 'toolbar');
// Initialize left column
$leftSideColumn = $this->leftMenu->show();
$rightSideColumn = NULL;
$middleColumn = NULL;

$objIcon = $this->newObject('geticon', 'htmlelements');
$objIcon->setIcon('loader');

$link = new link($this->uri(array('action' => 'default')));

$loadingIcon = $objIcon->show();

//title
//$title = $this->objLanguage->languageText('mod_movement_title', 'movement', 'Animal Movement');
$title = 'Vaccine inventory';

// Header
$header = new htmlheading();
$header->type = 2;
$header->str = $title;


$formTable = $this->newObject('htmltable', 'htmlelements');

//district name
$label_district = new label ('District name: ', 'district');
$district = new textinput('district',$dist);
$district->extra = 'readonly';
$formTable->startRow();
$formTable->addCell($label_district->show(),NULL,NULL,'right');
$formTable->addCell($district->show(),NULL,NULL,'left');
$formTable->endRow();

// vaccine name	
$label = new label ('Vaccine name: ', 'vaccinename');
$vaccinename = new dropdown('vaccinename');
$vaccinename->addFromDB($vaccination, 'name', 'name'); 
$formTable->startRow();
$formTable->addCell($label->show(),NULL,NULL,'right');
$formTable->addCell($vaccinename->show(),NULL,NULL,'left');
$formTable->endRow();


//$datePickerOne = $this->newObject('datepicker', 'htmlelements');
//$datePickerOne->name = 'endmonth';

$label_doses = new label ('Total doses in hand: ', 'doses');
$doses = new textinput('doses');
$formTable->startRow();
$formTable->addCell($label_doses->show(),NULL,NULL,'right');
$formTable->addCell($doses->show(),NULL,NULL,'left');
$formTable->endRow();

$label_start = new label ('Total doses at start of month: ', 'dosesstartofmonth');
$doses_start = new textinput('dosesstartofmonth');
$formTable->startRow();
$formTable->addCell($label_start->show(),NULL,NULL,'right');
$formTable->addCell($doses_start->show(),NULL,NULL,'left');
$formTable->endRow();

$datePicker = $this->newObject('datepicker', 'htmlelements');
$datePicker->name = 'startmonth';

$label_start = new label('Month start date: ','startmonth');
$formTable->startRow();
$formTable->addCell($label_start->show(),NULL,NULL,'right');
$formTable->addCell($datePicker->show(),NULL,NULL,'left');
$formTable->endRow();

$label_end = new label ('Total doses at end of month: ', 'dosesendofmonth');
$doses_end = new textinput('dosesendofmonth');
$formTable->startRow();
$formTable->addCell($label_end->show(),NULL,NULL,'right');
$formTable->addCell($doses_end->show(),NULL,NULL,'left');
//$formTable->addCell($datePickerOne->show(),NULL,NULL,'left');
$formTable->endRow();

$datePicker = $this->newObject('datepicker', 'htmlelements');
$datePicker->name = 'endmonth';
$label_end = new label('Month end date: ','startmonth');

$formTable->startRow();
$formTable->addCell($label_end->show(),NULL,NULL,'right');
$formTable->addCell($datePicker->show(),NULL,NULL,'left');
$formTable->endRow();

$label_received = new label ('Total doses received in month: ', 'dosesreceived');
$doses_received= new textinput('dosesreceived');
$formTable->startRow();
$formTable->addCell($label_received->show(),NULL,NULL,'right');
$formTable->addCell($doses_received->show(),NULL,NULL,'left');
$formTable->endRow();

$label_used = new label ('Doses used: ', 'dosesused');
$doses_used= new textinput('dosesused');
$formTable->startRow();
$formTable->addCell($label_used->show(),NULL,NULL,'right');
$formTable->addCell($doses_used->show(),NULL,NULL,'left');
$formTable->endRow();

$label_wasted = new label ('Doses wasted: ', 'doseswasted');
$doses_wasted= new textinput('doseswasted');
$formTable->startRow();
$formTable->addCell($label_wasted->show(),NULL,NULL,'right');
$formTable->addCell($doses_wasted->show(),NULL,NULL,'left');
$formTable->endRow();


$formAction = 'animalvaccine_save';  
    $buttonText = 'Save';
	
	// Create Form
$form = new form ('add', $this->uri(array('action'=>$formAction)));

//form validations
$form->addRule('district', $this->objLanguage->languageText('mod_ahis_districterror','ahis'),'required');
$form->addRule('vaccinename', $this->objLanguage->languageText('mod_ahis_vaccinenameerror','ahis'),'required');
$form->addRule('doses', $this->objLanguage->languageText('mod_ahis_doseserror','ahis'),'required');
$form->addRule('dosesstartofmonth', $this->objLanguage->languageText('mod_ahis_starterror','ahis'),'required');
//$form->addRule('startmonth', $this->objLanguage->languageText('mod_ahis_startmontherror','ahis'),'required');
$form->addRule('dosesendofmonth', $this->objLanguage->languageText('mod_ahis_enderror','ahis'),'required');
//$form->addRule('endmonth', $this->objLanguage->languageText('mod_ahis_endmontherror','ahis'),'required');
$form->addRule('dosesreceived', $this->objLanguage->languageText('mod_ahis_receivederror', 'ahis'), 'required');
$form->addRule('dosesused', $this->objLanguage->languageText('mod_ahis_usederror', 'ahis'), 'required');
$form->addRule('doseswasted', $this->objLanguage->languageText('mod_ahis_wastederror', 'ahis'), 'required');

//container-table
$topTable = $this->newObject('htmltable', 'htmlelements');
 $topTable->startRow();
$topTable->addCell($formTable->show());
$topTable->endRow();

$form->addToForm($topTable->show());
 
 $save = new button('animalvaccine_save', 'Save');
 $save->setToSubmit();
 
 $cancel = new button('cancel','Cancel');
$cancel->setToSubmit();

$form->addToForm($save->show(),NULL,NULL,'right');
$form->addToForm($cancel->show());

$objLayer = new layer();
$objLayer->addToStr($header->show()."<hr />".$form->show());
$objLayer->align = 'center';

echo $objLayer->show(); 

?>
<style type="text/css">
	.labels
	{
		padding-left:100px;		
	}
	.inputtextboxes
	{
		padding-left:200;
	}
</style>