<?php

/**
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
$this->setVar('canvas', '_default');
$this->loadClass('link', 'htmlelements');
$this->loadClass('htmltable', 'htmlelements');
$loginForm = $this->getObject("elsilogininterface");
echo '<div id="login">' . $loginForm->show() . '</div>';
$viewer = $this->getObject('viewer');
$objTable = $this->newObject('htmltable', 'htmlelements');

$objTable->startRow();
$objTable->addCell($viewer->getFeaturedNews());

$objTable->endRow();
$content = '<div id= "scroll">' . $objTable->show() . '</div>';



//developer news/blogs

$objTable = $this->newObject('htmltable', 'htmlelements');

$objTable->startRow();

$objTable->addCell($viewer->getLatestNews(),"25%");
$objTable->addCell($viewer->getLatestBlogs(),"25%");
$objTable->addCell($viewer->getEvents(),"25%");
$objTable->addCell($viewer->getDocumentation(),"25%");
$objTable->endRow();



// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);
$cssLayout->setLeftColumnContent($viewer->getTweets());
// Add Right Column
$cssLayout->setMiddleColumnContent($content.'<div id="stories">' . $objTable->show() . "</div>");

//Output the content to the page
echo $cssLayout->show();
?>
