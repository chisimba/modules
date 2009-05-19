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

/*$msg = '<br />';

if(($output=='yes')) {
        $objMsg = $this->getObject('timeoutmessage','htmlelements');
        $objMsg->setMessage($this->objLanguage->languageText('mod_ahis_promptyear', 'ahis'));
        $msg = $objMsg->show()."<br />";

}*/
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('layer', 'htmlelements');

//title
//$title = $this->objLanguage->languageText('mod_movement_title', 'movement', 'Animal Movement');
$title = 'Vaccine inventory';

// Header
$header = new htmlheading();
$header->type = 2;
$header->str = $title;

$formTable = $this->newObject('htmltable', 'htmlelements');
$formTable->cellspacing = 2;
$formTable->width = NULL;
$formTable->cssClass = 'min50';

//district name
$label_district = new label ('District name: ', 'district');
$district = new textinput('district',$dist);
$district->extra = 'readonly';
$formTable->startRow();
$formTable->addCell($label_district->show());
$formTable->addCell($district->show(),NULL,NULL,'left');
$formTable->endRow();

// vaccine name	
//$label = new label ('Vaccine name: ', 'vaccinename');
//$vaccinename = new dropdown('vaccinename');
//$vaccinename->addFromDB($vaccination, 'name', 'name');
//$vaccinename->addOption('vibe','vibe');
//$formTable->startRow();
//$formTable->addCell($label->show());
//$formTable->addCell($vaccinename->show(),NULL,NULL,'left');
//$formTable->endRow();

// vaccine name	
$label = new label ('Vaccine name: ', 'vaccinename');
$vaccinename = new dropdown('vaccinename');
$vaccinename->addFromDB($vaccination, 'name', 'name'); 

$formTable->startRow();
$formTable->addCell($label->show());
$formTable->addCell($vaccinename->show(),NULL,NULL,'left');
$formTable->endRow();


//$datePickerOne = $this->newObject('datepicker', 'htmlelements');
//$datePickerOne->name = 'endmonth';

$label_doses = new label ('Total doses in hand: ', 'doses');
$doses = new textinput('doses');
//$doses->extra = "onclick = 'numberVal();'";

$formTable->startRow();
$formTable->addCell($label_doses->show());
$formTable->addCell($doses->show(),NULL,NULL,'left');
$formTable->endRow();

$label_start = new label ('Total doses at start of month: ', 'dosesstartofmonth');
$doses_start = new textinput('dosesstartofmonth');
//$doses_start->extra = "onclick = 'numberVal();'";
$formTable->startRow();
$formTable->addCell($label_start->show());
$formTable->addCell($doses_start->show(),NULL,NULL,'left');
$formTable->endRow();

$datePicker = $this->newObject('datepicker', 'htmlelements');
$datePicker->name = 'startmonth';

$label_start = new label('Month start date: ','startmonth');
$formTable->startRow();
$formTable->addCell($label_start->show());
$formTable->addCell($datePicker->show(),NULL,NULL,'left');
$formTable->endRow();

$label_end = new label ('Total doses at end of month: ', 'dosesendofmonth');
$doses_end = new textinput('dosesendofmonth');
$formTable->startRow();
$formTable->addCell($label_end->show());
$formTable->addCell($doses_end->show(),NULL,NULL,'left');
$formTable->endRow();

$datePickerOne = $this->newObject('datepicker', 'htmlelements');
$datePickerOne->name = 'endmonth';
$label_end = new label('Month end date: ','endmonth');

$formTable->startRow();
$formTable->addCell($label_end->show());
$formTable->addCell($datePickerOne->show(),NULL,NULL,'left');
$formTable->endRow();

$label_received = new label ('Total doses received in month: ', 'dosesreceived');
$doses_received= new textinput('dosesreceived');
$formTable->startRow();
$formTable->addCell($label_received->show());
$formTable->addCell($doses_received->show(),NULL,NULL,'left');
$formTable->endRow();

$label_used = new label ('Doses used: ', 'dosesused');
$doses_used= new textinput('dosesused');
$formTable->startRow();
$formTable->addCell($label_used->show());
$formTable->addCell($doses_used->show(),NULL,NULL,'left');
$formTable->endRow();

$label_wasted = new label ('Doses wasted: ', 'doseswasted');
$doses_wasted= new textinput('doseswasted');
$formTable->startRow();
$formTable->addCell($label_wasted->show());
$formTable->addCell($doses_wasted->show(),NULL,NULL,'left');
$formTable->endRow();
		
$save = new button('animalvaccine_save', 'Save');
$save->setToSubmit();

$backUri = $this->uri(array('action' => 'select_officer'));
$bButton = new button('back', $this->objLanguage->languageText('word_cancel'), "javascript: document.location='$backUri'");

$formTable->startRow();
$formTable->addCell($bButton->show(),NULL,NULL,'right');
$formTable->addCell($save->show());
$formTable->endRow();


$formAction = 'animalvaccine_save';  
$buttonText = 'Save';
	
// Create Form
$form = new form ('add', $this->uri(array('action'=>$formAction)));

//form validations
$form->addRule('district', $this->objLanguage->languageText('mod_ahis_districterror','ahis'),'required');

$form->addRule('doses', $this->objLanguage->languageText('mod_ahis_doseserror','ahis'),'required');
$form->addRule('doses', $this->objLanguage->languageText('mod_ahis_dosesnumbererror','ahis'),'numeric');

$form->addRule('dosesstartofmonth', $this->objLanguage->languageText('mod_ahis_starterror','ahis'),'required');
$form->addRule('dosesstartofmonth', $this->objLanguage->languageText('mod_ahis_startnumbererror','ahis'),'numeric');

//$form->addRule('startmonth', $this->objLanguage->languageText('mod_ahis_startnontherror','ahis'),'required');
//$form->addRule('startmonth', $this->objLanguage->languageText('mod_ahis_startmontherror','ahis'),'datenotfuture');

$form->addRule('dosesendofmonth', $this->objLanguage->languageText('mod_ahis_enderror','ahis'),'required');
$form->addRule('dosesendofmonth', $this->objLanguage->languageText('mod_ahis_endnumbererror','ahis'),'numeric');

//$form->addRule('endmonth', $this->objLanguage->languageText('mod_ahis_endmontherror','ahis'),'datenotfuture');
$form->addRule('dosesreceived', $this->objLanguage->languageText('mod_ahis_receivederror', 'ahis'), 'required');
$form->addRule('dosesreceived', $this->objLanguage->languageText('mod_ahis_receivednumbererror', 'ahis'), 'numeric');
$form->addRule('dosesused', $this->objLanguage->languageText('mod_ahis_usederror', 'ahis'), 'required');
$form->addRule('dosesused', $this->objLanguage->languageText('mod_ahis_usednumbererror', 'ahis'), 'numeric');
$form->addRule('doseswasted', $this->objLanguage->languageText('mod_ahis_wastederror', 'ahis'), 'required');
$form->addRule('doseswasted', $this->objLanguage->languageText('mod_ahis_wastednumbererror', 'ahis'), 'numeric');



$form->addToForm($formTable->show());

$objLayer = new layer();
$objLayer->addToStr($header->show()." ".$msg."<hr />".$form->show());
$objLayer->align = 'center';

echo $objLayer->show(); 


?>

