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

/*This is a Edit User  UI
 *
 */


// set up html elements
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('link','htmlelements');
$objIcon = $this->newObject('geticon','htmlelements');

// setup and show heading
$header = new htmlheading();
$header->type = 1;
$header->str = "Edit User:";
echo $header->show();

$myTable = $this->newObject('htmltable', 'htmlelements');
$myTable->width = '60%';
$myTable->border = '1';
$myTable->cellspacing = '1';
$myTable->cellpadding = '10';

$myTable->startHeaderRow();
$myTable->addHeaderCell('Unesco_oer Users');
$myTable->addHeaderCell('Edit');
$myTable->addHeaderCell('Delete');
$myTable->endHeaderRow();

$users = $this->objUseExtra->getAllUser();

if (count($users) > 0) {
    foreach ($users as $user) {
        $myTable->startRow();
        $myTable->addCell($user['username']);
        $button = new button('Edit Button', "Edit User");
        $button->setToSubmit();
        $editLink =new link($this->uri(array('action' => "userRegistrationForm",'id' => $user['id'])));
        $editLink->link = $button->show();
        $myTable->addCell($editLink->show());
        $button = new button('Delete', "delete User");
        $button->setToSubmit();
        $DeleteLink =new link($this->uri(array('action' => "deleteUser",'id' => $user['id'],'userid'=>$user['userid'])));
        $DeleteLink->link = $button->show();
        $myTable->addCell($DeleteLink->show());
        $myTable->endRow();
    }
}
echo $myTable->show();



?>
