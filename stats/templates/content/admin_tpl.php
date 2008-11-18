<?php
/**
 * Stats tutorials on Chisimba
 * 
 * Admin template for stats module
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
 * @package   stats
 * @author    Nic Appleby <nappleby@uwc.ac.za>
 * @copyright 2008 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 */

$objHead = $this->newObject('htmlheading', 'htmlelements');
$objHead->str = $objLanguage->languageText('mod_stats_adminheading', 'stats');
$objHead->type = 2;

$link = $this->getObject('link','htmlelements');

$objTable = $this->newObject('htmltable','htmlelements');
$objTable->addHeader(array($this->objLanguage->languageText("mod_stats_studentlist",'stats')),null,"align='left'");

foreach ($students as $student) {
    $link->link($this->uri(array('action'=>'marks','studentno'=>$student['studentno'], 'back'=>'admin')));
    $link->link = "{$student['studentno']} - ".$this->objUser->fullName($student['studentno']);
    $objTable->startRow();
    $objTable->addCell($link->show());
    $objTable->endRow();
}


$link->link($this->uri(array('action'=>'home')));
$link->link = $this->objLanguage->languageText('word_back');
$backLink =  "<div style='float:right'>".$link->show()."</div>";

echo $objHead->show()."<br />".$objTable->show()."$backLink<br /><br />";
?>