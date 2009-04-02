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

$listHead = $this->newObject('htmlheading','htmlelements');
$listHead->str = $this->objLanguage->languageText("mod_stats_studentlist",'stats');
$listHead->type = 4;

$link = $this->getObject('link','htmlelements');

$objTable = $this->newObject('htmltable','htmlelements');
$objTable->addHeader(array($this->objLanguage->languageText("mod_stats_studentno","stats"),
                           $this->objLanguage->languageText("mod_stats_studentname","stats")),
                     null,"align='left'");
$objTable->cellpadding = $objTable->cellspacing = 2;
$class = 'odd';

foreach ($students as $student) {
    $link->link($this->uri(array('action'=>'marks','studentno'=>$student['studentno'], 'back'=>'admin')));
    $link->link = $student['studentno'];
    $objTable->startRow($class);
    $objTable->addCell($link->show());
    $userId = $this->objUser->getUserId($student['studentno']);
    $link->link = $this->objUser->fullName($userId);
    $objTable->addCell($link->show());
    $objTable->endRow();
    $class = ($class == 'odd')? "even" : "odd";
}

$leftContent = $listHead->show().$objTable->show();

$listHead->str = $this->objLanguage->languageText("mod_stats_adminfunc",'stats');
$listHead->type = 4;

$link->link($this->uri(array('action'=>'download', 'type' => '1')));
$link->link = $this->objLanguage->languageText("mod_stats_downloadpreq","stats");
$list = "<ul><li>".$link->show();
$link->link($this->uri(array('action'=>'download', 'type' => '2')));
$link->link = $this->objLanguage->languageText("mod_stats_downloadpostq","stats");
$list .= "</li><li>".$link->show();

$clearLink = $this->newObject('link','htmlelements');
$clearLink->link("#");
$clearLink->link = $this->objLanguage->languageText("mod_stats_clearall","stats");
$confirm = $this->objLanguage->languageText("mod_stats_clearconfirm","stats");
$location = $this->uri(array('action'=>'admin_clearall'));
$clearLink->extra = "onclick = 'if (confirm(\"$confirm\")) { window.location = \"$location\"; }'";
$list .= "</li><li>".$clearLink->show();
$list .= "</li></ul>";
$rightContent = $listHead->show().$list;
$subContent = "<div style='float:left; width: 50%;'>$leftContent</div><div style='float:right; width: 50%;'>$rightContent</div>";

$link->link($this->uri(array('action'=>'home')));
$link->link = $this->objLanguage->languageText('word_back');
$backLink =  "<div style='clear:both; float:right;'>".$link->show()."</div>";

echo $objHead->show()."<br />".$subContent."$backLink<br /><br />";
?>