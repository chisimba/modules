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

// Make the banner as a layer
//$bannerLayer = $this->newObject('layer', 'htmlelements');
//$bannerLayer->id = "bannerLayer";

// Make the banner bar as a layer
//$bannerBar = $this->newObject('layer', 'htmlelements');
//$bannerBar->id = "bannerBar";

// Make the body
$createYourVenture = $this->newObject('short_courses_create_your_venture_body', 'cfe');
// Make the footer
//$footer = $this->newObject('footer', 'cfe');


// Echo out the bannerLayer, banner bar, the body and the footerLayer in sequence:
//echo $bannerLayer->show();
//echo $bannerBar->show();
echo $createYourVenture->show();
//echo $footer->show();
?>