<?php
/*
 *
 * A module to display the logo of the elsi skin. 
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
 * @package   elsiskin
 * @author    Nguni Phakela nonkululeko.phakela@wits.ac.za
 * @copyright 2007 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   CVS: $Id: elsilogo_class_inc.php,v 1.1 2011-01-12 09:13:27 nguni52 Exp $
 * @link      http://avoir.uwc.ac.za
 *
 *
 *
 */
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

// end of security

class elsilogo extends object {

    // path to root folder of skin
    private $skinpath;

    /**
     * Constructor
     */
    public function init() {
        
    }

    /**
     * Method to set the base skin path
     * @return none
     */
    public function setSkinPath($skinpath) {
        $this->skinpath = $skinpath;
    }

    /* 
     * Method to display the logo section of the skin
     * @access public
     * @return string $retstr which displays the wits logo and elsi logo
     */
    public function show() {
        $retstr = '<div class="clear">&nbsp;</div>
            <div class="grid_3">
                <img src="' . $this->skinpath . 'images/logo_wits.png">
            </div>
    		<!-- end .grid_3 -->
            <div class="grid_1">
                <img src="' . $this->skinpath . 'images/logo_elsi.gif">
            </div>';

        $retstr .= '<!-- Start: Horizontal nav -->
			 <div class="clear">&nbsp;</div> 
				<div id="Horizontalnav"> 
					<div class="wide">
					</div>
			<!-- end .grid_2 .push_2 -->
				</div> 
			<!-- end .grid_1 -->
			<!-- End: header -->
			 </div>
			 <!-- End: Horizontal nav -->';

        return $retstr;
    }

}