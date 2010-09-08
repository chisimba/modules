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

 * @author Palesa Mokwena, Thato Selebogo, Mmbudzeni Vhengani
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
$pageContent = $this->newObject('csslayout', 'htmlelements');

	//set the layout to two columns
	$pageContent->setNumColumns(2);
	//write on the left column 
	$pageContent->setLeftColumnContent('<div id="content">'.$this->viewer->getStory($category,1).'</div>');
	//write on the right column
	$pageContent->setMiddleColumnContent('<div id="rightColumn">' . 
	'<div class="storybl">'. 
	'<div class="storybr">'. 
	'<div class="storytl">'. 
	'<div class="storytr">'.
	$this->viewer->getStory($category,0).
	'</div>'.
	'</div>'.
	'</div>'. 
	'</div>'.
	'</div>');

echo $pageContent->show();
?>
