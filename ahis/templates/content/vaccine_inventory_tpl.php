<?php
/**
 * ahis Passive Survaillance main screen Template
 *
 * Template for passive surveillance main capture screen
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
 * @author    Joseph Gatheru<mujoga@gmail.com>
 * @copyright 2009 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: passive_surveillance_tpl.php 12903 2009-03-17 14:17:34Z nic $
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
$this->loadClass('link', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('fieldset', 'htmlelements');

$header = new htmlHeading();
$header->type = 1;

$header->str = 'Vaccine Inventory';
echo $header->show();
echo '<hr>';
$form = new form ('vaccinereport', $this->uri(array('action'=>'vaccinereport')));

$table = $this->newObject('htmltable', 'htmlelements');

$table->startRow();
$label=new label('District Name','districtname');
$table->addCell($label->show(),120);

$input=new textinput('district_name');
$input->size=20;
$table->addCell($input->show());
$table->endRow();

$table->startRow();
$label=new label('Vaccine Name','vaccine_name');
$table->addCell($label->show(),120);

$dropdown=new dropdown('vaccine_name');
$dropdown->addOption('Vaccine Name...');
$dropdown->addOption('...');
$dropdown->addOption('...');
$table->addCell($dropdown->show());
$table->endRow();

$table->startRow();
$label=new label('Total Doses on hand','total_doses_on_hand');
$table->addCell($label->show(),120);


$input=new textinput('total_doses_on_hand');
$input->size=5;
$table->addCell($input->show());
$table->endRow();

$label = new label ('Doses at start of Month', 'doses_monthstart');
$dose = new textinput('doseatstartmonth');
$dose->size = 10;
$date = new textinput('doseatstartmonth');
$date->size = 10;
$date->value='date';
$dosestart=$dose->show().'<br>'.$date->show();
$table->addCell($label->show(), 120);
$table->addCell($dosestart);
$label = new label ('Doses at start of End', 'doses_endstart');
$dose = new textinput('doseatstartend');
$dose->size = 10;
$date = new textinput('doseatstartend');
$date->size = 10;
$date->value='date';
$dosestart=$dose->show().'<br>'.$date->show();
$table->addCell($label->show(), 120);
$table->addCell($dosestart);
$table->endRow();

$table->startRow();

$label = new label ('Doses Received During the Month', 'monthdoses');
$dose = new textinput('monthdoses');
$dose->size = 15;
$table->addCell($label->show(), 120);
$table->addCell($dose->show());
$table->endRow();

$table->startRow();
$label = new label ('Doses Used', 'doses_used');
$doseused = new textinput('doses_used');
$doseused->size = 15;
$table->addCell($label->show(), 120);
$table->addCell($doseused->show());
$table->endRow();

$table->startRow();
$label = new label ('Doses Wasted', 'doses_wasted');
$dosewasted = new textinput('doses_wasted');
$dosewasted->size = 15;
$table->addCell($label->show(), 120);
$table->addCell($dosewasted->show());
$table->endRow();

$table->startRow();

$save = new button ('vaccine_inventory_save', 'Save');
$save->setToSubmit();
$cancel = new button ('reset', 'Cancel');
$cancel->setToReset();

$table->addCell('&nbsp;');
$table->addCell($save->show());
$table->addCell($cancel->show());
$table->endRow();


$form->addToForm($table->show());

echo $form->show();

?>