<?php

//Display errors
error_reporting(E_ALL);
ini_set('display_errors', 'Off');
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

/*This is a FEATURED PRODUCT UI
 *
 */


// set up html elements
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('link','htmlelements');
$objIcon = $this->newObject('geticon','htmlelements');

// setup and show heading
$header = new htmlheading();
$header->type = 1;
$header->str = "Edit Institution:";
echo $header->show();

$myTable = $this->newObject('htmltable', 'htmlelements');
$myTable->width = '60%';
$myTable->border = '1';
$myTable->cellspacing = '1';
$myTable->cellpadding = '10';

$myTable->startHeaderRow();
$myTable->addHeaderCell('INSTITUTION');
$myTable->addHeaderCell('DELETE');
$myTable->addHeaderCell('EDIT');
$myTable->endHeaderRow();

//$institutionGUI = $this->getObject('institutiongui', 'unesco_oer');
//$institutionGUI->showAllInstitutions();

// retrieve data from tbl_unesco_oer_feturedproducts
$Institution = $this->objDbInstitution->getAllInstitution();
if (count($Institution) > 0) {
    foreach ($Institution as $Institutions) {


    echo  '             <div class="discussionList">
                            <img src="' . $Institutions['thumbnail'] . '" alt="Adaptation placeholder" width="45" height="49" class="smallAdaptationImageGrid">
                            <div class="textNextToGroupIcon">
                                <h2>' . $Institutions['name'] . '</h2>
                                <a href="#" class="bookmarkLinks">' . $Institutions['country'] . '</a> | <a href="#" class="bookmarkLinks">' . $Institutions['websiteLink'] . '</a>
                            </div>
                        </div>';
//
//        $myTable->startRow();
//        $myTable->addCell($Institutions['name']);
//        $objIcon->setIcon('delete');
//        $deleteLink = new link($this->uri(array('action' => "deleteInstitution", 'institutionId' => $Institutions['id'])));
//        $deleteLink->link = $objIcon->show();
//        $myTable->addCell($deleteLink->show());
//        $objIcon->setIcon('edit');
//        $EditLink = new link($this->uri(array("action" => "institutionEditor", 'institutionId' => $Institutions['id'])));
//        $EditLink->link = $objIcon->show();
//        $myTable->addCell($EditLink->show());
//        $myTable->endRow();
    }
}
//echo $myTable->show();
?>
