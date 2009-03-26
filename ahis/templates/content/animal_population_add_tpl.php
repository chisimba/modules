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
 * @author    Samuel Onyach <onyach@icsit.jkuat.ac.ke>
 * @copyright 2009 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: add_animal_population_add_tpl.php 
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
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');


    $formAction = 'animal_population_save';
    $title = 'Animal Population';
    $buttonText = 'Save';


// Header
$header = new htmlheading();
$header->type = 1;
$header->str = $title;
//echo $header->show();

// Create Form
$form = new form ('add', $this->uri(array('action'=>$formAction)));

$formTable = $this->newObject('htmltable', 'htmlelements');

$formTable->startRow();
$formTable->addCell($header->show(),NULL,NULL,'center');
$formTable->endRow();
$district = new textinput('district');
$district->size = 50;


//district name
$label = new label ('District', 'input_district');

$formTable->startRow();
$formTable->addCell($label->show(),NULL,NULL,'right');
$formTable->addCell($district->show(),NULL,NULL,'left');
$formTable->endRow();

//animal classification
$label = new label ('Animal Classification', 'input_animal_class');
$class=new dropdown('classification');

$class->addOption('A','A');
$class->addOption('B','B');
$class->addOption('C','C');


$formTable->startRow();
$formTable->addCell($label->show(),NULL,NULL,'right');
$formTable->addCell($class->show(),NULL,NULL,'left');
$formTable->endRow();

//number of animals
$label = new label ('Number of Animals', 'input_no_animal');
$num_animals = new textinput('num_animals');
$num_animals->size = 50;
$formTable->startRow();
$formTable->addCell($label->show(),NULL,NULL,'right');
$formTable->addCell($num_animals->show(),NULL,NULL,'left');
$formTable->endRow();

//animal production
$label = new label ('Animal Production', ' input_production');
$production = new dropdown('animal_production');

$production->multiple=false; 

$production->addOption('Milk','Milk');
$production->addOption('Eggs', 'Eggs');
$production->addOption('Cheese', 'Cheese');
$production->addOption('Beef', 'Beef');
$production->addOption('Other','Other');
$formTable->startRow();
$formTable->addCell($label->show(),NULL,NULL,'right');
$formTable->addCell($production->show(),NULL,NULL,'left');
$formTable->endRow();	

// animal source	
$label = new label ('Source', 'input_source');
$source=new dropdown('source');
$source->addOption('A','A');
$source->addOption('B','B');
$source->addOption('C','C');

$formTable->startRow();
$formTable->addCell($label->show(),NULL,NULL,'right');
$formTable->addCell($source->show(),NULL,NULL,'left');
$formTable->endRow();
//container-table
$topTable = $this->newObject('htmltable', 'htmlelements');

$topTable->startRow();
$topTable->addCell($formTable->show());
$topTable->endRow();
$form->addToForm($topTable->show());
//buttons
$button = new button ('animal_population_save', 'Save');
$button->setToSubmit();

$btcancel = new button ('cancel', 'Cancel');
$btcancel->setToSubmit();

$form->addToForm($button->show());
$form->addToForm($btcancel->show());


echo $form->show();


?>
