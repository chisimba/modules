<?php
/*
 *
 * A class to display the logo of the elsi skin.
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
    // displays toolbar based on who is logged in.
    private $toolbar;

    /**
     * Constructor
     */
    public function init() {
        $this->objUser = $this->newObject('user', 'security');
    }

    /**
     * Method to set the base skin path
     * @return none
     */
    public function setSkinPath($skinpath) {
        $this->toolbar = $this->getObject('elsiskintoolbar', 'elsiskin');
        $this->skinpath = $skinpath;
    }

    /* 
     * Method to display the logo section of the skin
     * @access public
     * @return string $retstr which displays the wits logo and elsi logo
     */
    public function show() {
        $retstr = '
            <div id="body-wrapper">
    <!-- Print Header -->
    <div id="branding"><img height="130px" width="960px" alt="UNIVERSITY OF THE WITWATERSRAND, JOHANNESBURG" src="'.$this->skinpath.'images/print-branding.gif"></div>

    	<!-- The Wits Crest -->
    	<div id="wits-crest"></div>

        <!-- Generic Links -->
    	<div id="generic-links">

            <!-- Top links -->
            <div id="top-links">
            	A-Z listing  |  Contact Us  |  Maps
                <form>
                	<input type="text" value="Search »" class="searchbar">
                    <input type="submit" value="" class="searchbutton">
                 </form>
            </div>'.
            $this->getTabLinks().'
        </div>
                    <div class="clear">&nbsp;
            </div>
        <div id="dropList">
<ul id="menu">'.$this->toolbar->show().'</ul>
</div>
        <!-- To clear floats -->
        <div id="clearfix"></div>
';

        return $retstr;
    }

    public function getTabLinks() {
        if($this->objUser->isLoggedIn()) {
            $profileLink = new link($this->uri(array(), "postlogin"));
            $profileLink->link = "Profile";
            $newsLink = new link($this->uri(array(), "news"));
            $newsLink->link = "News";
            $blogLink = new link($this->uri(array(),"blog"));
            $blogLink->link = "Blog";
            $adminLink = new link($this->uri(array(),"toolbar"));
            $adminLink->link = "Admin";
            $logoutLink = new link($this->uri(array("action"=>"logoff"), "security"));
            $logoutLink->link = "Logout";
            $retstr = '<!-- Tab links -->
                <div id="tab-links">
                    <ul>
                        <li>'.$profileLink->show().'<li>
                        <li>&nbsp;&nbsp;&nbsp;&nbsp;'.$newsLink->show().'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
                        <li>&nbsp;&nbsp;&nbsp;&nbsp;'.$blogLink->show().'</li>';
           if($this->objUser->isAdmin()) {
                $retstr .= '<li>&nbsp;&nbsp;&nbsp;&nbsp;'.$adminLink->show().'</li>';
           }
           else {
                $retstr .= '<li>&nbsp;&nbsp;&nbsp;&nbsp;'.$logoutLink->show().'</li>';
           }
           $retstr .= '
                    </ul>
                </div>';

        }
        else {
            $witsHome = new link("http://web.wits.ac.za/");
            $witsHome->link = "Wits Home";
            $witsAlumni = new link("http://web.wits.ac.za/Alumni/");
            $witsAlumni->link = "Alumni";
            $aboutLink = new link("http://web.wits.ac.za/AboutWits");
            $aboutLink->link = "About";
            $retstr = '<!-- Tab links -->
                <div id="tab-links">
                    <ul>
                        <li>'.$witsHome->show().'</li>
                        <li>&nbsp;&nbsp;&nbsp;&nbsp;'.$witsAlumni->show().'</li>
                        <li>'.$aboutLink->show().'</li>
                    </ul>
                </div>';
        }

        return $retstr;
    }

}