<?php
/**
 * Stats tutorials on Chisimba
 * 
 * Deafult view template for stats module
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
$objHead->str = $objLanguage->languageText('mod_stats_heading', 'stats');
$objHead->type = 2;

$note = "Before you can start with tutorial 1,  you must download the Web Player.
    It is only necessary to do this once.";

$objPop = $this->getObject('windowpop','htmlelements');
$objPop->set('location',$this->getResourceURI("cabload.html"));
$objPop->set('linktext','Click here');
$objPop->set('width','550');
$objPop->set('height','350');
$objPop->putJs();

$link = $this->getObject('link','htmlelements');

$link->link($this->uri(array('action'=>'tutorials')));
$link->link = "Click here to access the online tutorials";
$tutLink = $link->show();

if ($this->objUser->isLecturer()) {
    $link->link($this->uri(array('action'=>'admin')));
    $link->link = "Click here for lecturer options";
    $marksLink = $link->show();
} else {
    $link->link($this->uri(array('action'=>'marks')));
    $link->link = "Click here to view your marks";
    $marksLink = $link->show();

}

$install = $objPop->show()." to install the player. Please note this will only work with Internet Explorer.";
$options = "<ul>
                <li>$tutLink</li>
                <li>$marksLink</li>
            </ul>";
echo $objHead->show()."$note<br />$install<br /><br />$options<br />";
?>