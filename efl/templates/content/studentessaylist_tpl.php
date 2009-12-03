<?php
/**
 * This class provides functionality to access the essays table
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

 * @author
 * @copyright  2009 AVOIR
 */
// security check - must be included in all scripts
if (!
/**
 * Description for $GLOBALS
 * @global unknown $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
$this->loadclass('button','htmlelements');

echo "Essay Table Updated Succesfully";
$this->loadClass('htmltable','htmlelements');
    
$table = new htmltable();
$table->addCell('Essayid');
$table->addCell('content');
$table->addCell('Submitdate');

$essays=$this->objStudentEssays->getstudentEssays($essayid);
foreach($essays as $essay) {
    $table->width='30%';
    $table->border='2';
    $table->cellspacing='1';
    $table->startRow();
    $table->addCell($essay['essayid']);
    $table->addCell($essay['content']);
    $table->addCell($essay['submitdate']);
    $table->endRow();
}

$backbutton=new button('back');
$backbutton->setValue('Back');
$backbutton->setOnClick('alert(\'An onclick Event\')');

echo $table->show();
$backbutton->show();
?>
