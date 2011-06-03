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
$myTable->addHeaderCell('Unesco_oer Group');
$myTable->addHeaderCell('Edit');
$myTable->addHeaderCell('Delete');
$myTable->endHeaderRow();

$groups = $this->objDbGroups->getAllGroups();

if (count($groups) > 0) {
    foreach ($groups as $group) {
        $myTable->startRow();
        $myTable->addCell($group['name']);
        $button = new button('Edit Button', "Edit Group");
        $button->setToSubmit();
        $editLink =new link($this->uri(array('action' => "addNewGroupForm",'id' => $group['id'])));
        $editLink->link = $button->show();
        $myTable->addCell($editLink->show());
        $button = new button('Delete', "Delete Group");
        $button->setToSubmit();
        $DeleteLink =new link($this->uri(array('action' => "deleteGroup",'id' => $group['id'])));
        $DeleteLink->link = $button->show();
        $myTable->addCell($DeleteLink->show());
        $myTable->endRow();
    }
}
echo $myTable->show();



?>
