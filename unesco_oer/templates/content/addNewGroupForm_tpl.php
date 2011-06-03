<?php
/*
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
 */

/*This is a Add New Group UI
 *
 */


// set up html elements
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('link','htmlelements');
$objIcon = $this->newObject('geticon','htmlelements');

// setup and show heading
$header = new htmlheading();
$header->type = 1;
$header->str = "Add New Group:";
echo $header->show();

$table = $this->newObject('htmltable', 'htmlelements');
$table->width = '60%';
$table->border = '1';
$tableable->cellspacing = '1';
$table->cellpadding = '10';

$textinput = new textinput('groupname');
$textinput->size = 20;
$table->startRow();
$table->addCell('Group Name');
$table->addCell($textinput->show());
$table->endRow();

$textinput = new textinput('email');
$textinput->size = 20;
$table->startRow();
$table->addCell('Email');
$table->addCell($textinput->show());
$table->endRow();

$textinput = new textinput('address');
$textinput->size = 20;
$table->startRow();
$table->addCell('Address');
$table->addCell($textinput->show());
$table->endRow();

$textinput = new textinput('city');
$textinput->size = 20;
$table->startRow();
$table->addCell('City');
$table->addCell($textinput->show());
$table->endRow();

$textinput = new textinput('state');
$textinput->size = 20;
$table->startRow();
$table->addCell('State/Province');
$table->addCell($textinput->show());
$table->endRow();

$textinput = new textinput('postalcode');
$textinput->size = 20;
$table->startRow();
$table->addCell('Postal Code');
$table->addCell($textinput->show());
$table->endRow();

$textinput = new textinput('website');
$textinput->size = 20;
$table->startRow();
$table->addCell('Website');
$table->addCell($textinput->show());
$table->endRow();

$textinput = new textinput('institution');
$textinput->size = 20;
$table->startRow();
$table->addCell('Institution');
$table->addCell($textinput->show());
$table->endRow();

$textinput = new textinput('description');
$textinput->size = 20;
$table->startRow();
$table->addCell('Description');
$table->addCell($textinput->show());
$table->endRow();

$textinput = new textinput('loclat');
$textinput->size = 20;
$table->startRow();
$table->addCell('Latitude');
$table->addCell($textinput->show());
$table->endRow();

$textinput = new textinput('loclong');
$textinput->size = 20;
$table->startRow();
$table->addCell('Longitude');
$table->addCell($textinput->show());
$table->endRow();

$button = new button('Save Button', "Save");
$button->setToSubmit();
$SaveLink =new link($this->uri(array('action' => "userRegistrationForm",'id' => $user['id'])));
$SaveLink->link = $button->show();
$table->addCell($SaveLink->show());

$button = new button('Cancel Button', "Cancel");
$button->setToSubmit();
$SaveLink =new link($this->uri(array('action' => "userRegistrationForm",'id' => $user['id'])));
$SaveLink->link = $button->show();
$table->addCell($SaveLink->show());
$table->endRow();

echo $table->show();



?>
