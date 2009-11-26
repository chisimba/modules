<?php
/**
 * ahis Add Animal Population
 *
 * File containing the Add Animal Population template
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
 * @copyright 2009 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: animal_population_tpl.php 
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
 $title = 'Animal Population Census';
$objHeading = $this->getObject('htmlheading','htmlelements');
$objHeading->str = $title;
$objHeading->type = 2;

$this->loadClass('textinput','htmlelements');
$this->loadClass('dropdown','htmlelements');
$this->loadClass('layer','htmlelements');
$this->loadClass('label', 'htmlelements');

$formAction = 'animal_population_save';  
$buttonText = 'Save';

$classDrop = new dropdown('classification');
$classDrop->addFromDB($species, 'name', 'name'); 

$formTable = $this->newObject('htmltable', 'htmlelements');
$formTable->cellspacing = 2;
$formTable->width = NULL;

//district name
$district = new textinput('district',$dist);
$district->extra='readonly';
$label = new label ('District:', 'district');
$formTable->startRow();
$formTable->addCell($label->show());
$formTable->addCell($district->show());
$formTable->endRow();

//animal classification
$label = new label ('Animal Classification:', 'input_animal_class');

$formTable->startRow();
$formTable->addCell($label->show());
$formTable->addCell($classDrop->show());
$formTable->endRow();

//number of animals
$label = new label ('Number of Animals:', 'input_no_animal');
$num_animals = new textinput('num_animals');
$formTable->startRow();
$formTable->addCell($label->show());
$formTable->addCell($num_animals->show());
$formTable->endRow();

//animal production
$label = new label ('Animal Production:', ' input_production');
$production = new dropdown('animal_production');

$production->addFromDB($animprod, 'name','name');

$formTable->startRow();
$formTable->addCell($label->show());
$formTable->addCell($production->show());
$formTable->endRow();	

// animal source	
$label = new label ('Source:', 'input_source');
$source=new textinput('source');
$formTable->startRow();
$formTable->addCell($label->show());
$formTable->addCell($source->show());
$formTable->endRow();

// Create Form
$form = new form ('add', $this->uri(array('action'=>$formAction)));
$form->addToForm($formTable->show());
$form->addRule('num_animals', 'Please enter number of animals', 'required');
$form->addRule('num_animals', 'Please enter valid number ', 'numeric');
$form->addRule('source', 'Please enter source of animals', 'required');
$form->addRule('source', 'Please enter valid source', 'nonnumeric');


//buttons
$button = new button ('animal_population_save', 'Save');
$button->setCSS('saveButton');
$button->setToSubmit();
$backUri = $this->uri(array('action'=>'select_officer'));
$btcancel = new button('cancel', 'Cancel', "javascript: document.location='$backUri'");
$btcancel->setCSS('cancelButton');

$form->addToForm($button->show()." ");
$form->addToForm($btcancel->show());

$objLayer = new layer();
$objLayer->addToStr($objHeading->show()."<hr class='openaris' />".$form->show());

echo $objLayer->show();
?>