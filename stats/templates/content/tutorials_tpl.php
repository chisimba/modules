<?php
/**
 * Stats tutorials on Chisimba
 * 
 * Tutorial template which loads the authorware modules for taking a tutorial
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

$note = "This section is for practicing tutorials.<br />
The online tutorials consist of sixteen tests based on different sections of the syllabus.<br />
Login below to access the tutorials. When you have finished writing a tutorial, you immediately will get your mark
for the specific tutorial you have completed. The mark will be saved to a database on the server.
You may complete the same tutorial more than once to improve your mark.
All attempts will be saved and your time will be recorded.<br /><br />";

$link = $this->getObject('link','htmlelements');

$link->link($this->uri(array('action'=>'home')));
$link->link = $this->objLanguage->languageText("word_back");
$backLink = "<div style='float:left'>Good luck.</div><div style='float:right'>".$link->show()."</div>";

$aam = $this->getResourceURI('login.aam');
//$iFrame = "<iframe width='100%' height='400' frameborder='0' scrolling='no' src='$aam'></iframe>";
$iFrame = "<EMBED SRC='$aam' WIDTH=100% HEIGHT=400 PALETTE=Background>";

echo $objHead->show()."$note$backLink<br /><br />$iFrame";
?>