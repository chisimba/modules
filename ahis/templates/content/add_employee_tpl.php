<?php
/**
 * ahis Add Employee Template
 *
 * File containing the add/edit employee template
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

if ($id) {
    $hStr = $this->objLanguage->languageText('word_edit')." ".$this->objLanguage->languageText('word_employee');
    $formUri = $this->uri(array('action'=>'employee_insert', 'id'=>$id));
    //$record = $this->objGeo2->getRow('id', $id);
} else {
    $hStr = $this->objLanguage->languageText('word_add')." ".$this->objLanguage->languageText('word_employee');
    $formUri = $this->uri(array('action'=>'employee_insert'));
    $record['surname'] = $record['firstname'] = $record['isactive'] = $record['title'] =
    $record['username'] = $ahisRecord['location'] = $ahisRecord['department'] = $ahisRecord['role'] = '';
    $ahisRecord['birthdate'] = $ahisRecord['hireddate'] = $ahisRecord['retireddate'] = date('Y-m-d');
}

$objHeading = $this->getObject('htmlheading','htmlelements');
$objHeading->type = 2;
$objHeading->str = $hStr;

$surnameInput = new textinput('surname',$record['surname']);
$nameInput = new textinput('name',$record['firstname']);
$usernameInput = new textinput('username',$record['username']);
$passwordInput = new textinput('password', NULL, 'password');
$confirmInput = new textinput('confirm', NULL, 'password');

$titleDrop = new dropdown('title');
//$titleDrop->addFromDB($title, 'name', 'name');
$titleDrop->setSelected($record['title']);
$statusDrop = new dropdown('status');
//$statusDrop->addFromDB($status, 'name', 'name');
$statusDrop->setSelected($record['isactive']);
$locationDrop = new dropdown('location');
//$locationDrop->addFromDB($location, 'name', 'id');
$locationDrop->setSelected($ahisRecord['location']);
$departmentDrop = new dropdown('department');
//$departmentDrop->addFromDB($department, 'name', 'id');
$departmentDrop->setSelected($ahisRecord['department']);
$roleDrop = new dropdown('role');
//$roleDrop->addFromDB($role, 'name', 'id');
$roleDrop->setSelected($ahisRecord['role']);

$birthDate = $this->newObject('datepicker','htmlelements');
$birthDate->setName('birthdate');
$birthDate->setDefaultDate($ahisRecord['birthdate']);
$hiredDate = $this->newObject('datepicker','htmlelements');
$hiredDate->setName('hireddate');
$hiredDate->setDefaultDate($ahisRecord['hireddate']);
$retiredDate = $this->newObject('datepicker','htmlelements');
$retiredDate->setName('retireddate');
$retiredDate->setDefaultDate($ahisRecord['retireddate']);

$sButton = new button('enter', $this->objLanguage->languageText('word_enter'));
$sButton->setToSubmit();
$backUri = $this->uri(array('action' => 'employee_admin'));
$bButton = new button('back', $this->objLanguage->languageText('word_back'), "javascript: document.location='$backUri'");

$objTable = $this->getObject('htmltable','htmlelements');
$objTable->width = '50%';
$objTable->cellspacing = 2;

$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_surname').": ");
$objTable->addCell($surnameInput->show());
$objTable->addCell($this->objLanguage->languageText('phrase_firstname').": ");
$objTable->addCell($nameInput->show());
$objTable->endRow();

$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_title').": ");
$objTable->addCell($titleDrop->show());
$objTable->addCell($this->objLanguage->languageText('word_status').": ");
$objTable->addCell($statusDrop->show());
$objTable->endRow();

$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_location').": ");
$objTable->addCell($locationDrop->show());
$objTable->addCell($this->objLanguage->languageText('phrase_dob').": ");
$objTable->addCell($birthDate->show());
$objTable->endRow();

$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('phrase_datehired').": ");
$objTable->addCell($hiredDate->show());
$objTable->addCell($this->objLanguage->languageText('phrase_dateretired').": ");
$objTable->addCell($retiredDate->show());
$objTable->endRow();

$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_department').": ");
$objTable->addCell($departmentDrop->show());
$objTable->addCell($this->objLanguage->languageText('word_role').": ");
$objTable->addCell($roleDrop->show());
$objTable->endRow();

$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_username').": ");
$objTable->addCell($usernameInput->show());
$objTable->endRow();

$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_password').": ");
$objTable->addCell($passwordInput->show());
$objTable->addCell($this->objLanguage->languageText('phrase_confirmpassword').": ");
$objTable->addCell($confirmInput->show());
$objTable->endRow();

$objTable->startRow();
$objTable->addCell('&nbsp;');
$objTable->addCell($bButton->show());
$objTable->addCell($sButton->show());
$objTable->endRow();

$objForm = new form('employeeadd', $formUri);
$objForm->id = 'form_employeeadd';
$objForm->addToForm($objTable->show());
$objForm->addRule('name', $this->objLanguage->languageText('mod_ahis_namerequired', 'ahis'), 'required');
$objForm->addRule(array('password', 'confirm'), $this->objLanguage->languageText('mod_ahis_pwordmismatch', 'ahis'), 'compare');

echo $objHeading->show()."<hr />".$objForm->show();