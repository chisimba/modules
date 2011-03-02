<?php
/*
 *
 * A class to display the sidebar of the different pages of the elsi skin
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
 * @version   CVS: $Id: sidebar_class_inc.php,v 1.1 2007-11-25 09:13:27 nguni52 Exp $
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

class sidebar extends object {

    // page main content displayed according to current action
    private $currentAction;
    // path to root folder of skin
    private $skinpath;
    // link object
    private $objLink;

    /**
     * Constructor
     */
    public function init() {
        $this->objLink = $this->getObject("link", "htmlelements");
    }

    /**
     * Method to set the current action based on url parameter action
     * @return none
     * @access public
     */
    public function setCurrentAction($action) {
        $this->currentAction = $action;
    }

    /**
     * Method to set the base skin path
     * @param string $skinpath the absolute path to the location of elsiskin
     * @return none
     * @access public
     */
    public function setSkinPath($skinpath) {
        $this->skinpath = $skinpath;
    }

    /**
     * Method to control which sidebar will display
     * @return string
     * @access public
     */
    public function show() {
        switch ($this->currentAction) {
            case 'about':return $this->showAboutSidebar();
            case 'staff':return $this->showStaffSidebar();
            case 'contact': return $this->showContactSidebar();
            case 'projectsresearch': return $this->showProjectsSidebar();
            case 'supporttraining': return $this->showSupportTrainingSidebar();
            case 'currentnews': return $this->showCurrentNewsSidebar();
            case 'viewstory':
            case 'viewsingle':return $this->showNewsBlogSidebar();
            default: return $this->showHomeSidebar();
        }
    }

    /**
     * Method to show the home page sidebar
     * @return string $retstr with the home sidebar content
     * @access public
     */
    public function showHomeSidebar() {
        $retstr = '<!-- Start: Sidebar -->
                <div class="grid_1">
                    <div id="Sidebar">&nbsp;</div>
                </div>
                <!-- end .grid_1 -->
                <!-- End: Sidebar -->';

        return $retstr;
    }

    /**
     * Method to show the about us page sidebar
     * @return string $retstr with the about us sidebar content
     * @access public
     */
    public function showAboutSidebar() {
        $retstr = '<!-- Start: Sidebar -->
                <div id="Sidebar">
					<div class="grid_1">
                    	<p><img src="' . $this->skinpath . '/images/dots_point.png"></p>
					</div>
				</div>
				 <!-- end .grid_1 -->
                <!-- End: Sidebar -->';

        return $retstr;
    }

    /**
     * Method to show the staff page sidebar
     * @return string $retstr with the staff sidebar content
     * @access public
     */
    public function showStaffSidebar() {
        $retstr = '<!-- Start: Sidebar -->
                <div id="Sidebar">
					<div class="grid_1">
                    	<p><img src="' . $this->skinpath . 'images/dots_who.png"></p>
					</div>
				</div>
				 <!-- end .grid_1 -->
                <!-- End: Sidebar -->';
        return $retstr;
    }

    /**
     * Method to show the contact us page sidebar
     * @param none
     * @return string $retstr with the contact us sidebar content
     * @access public
     */
    public function showContactSidebar() {
        $retstr = '<!-- Start: Sidebar -->
                <div id="Sidebar">
					<div class="grid_1">
                    	<p><img src="' . $this->skinpath . 'images/dots_email.png"></p>
					</div>
				</div>
				 <!-- end .grid_1 -->
                <!-- End: Sidebar -->';

        return $retstr;
    }

    /*
     * Method to read the facebook script file and display the facebook like page
     * @param none
     * @access public
     * @return str $retstr containing the string that shows the facebook like page
     *
     */
    public function getFacebook() {
        $this->objLink->link("http://www.facebook.com/pages/Wits-eLSI-eLearning-Support-and-Innovation/143838025636947");
        $this->objLink->link = '<img src="' . $this->skinpath . 'images/ELSIFacebook.png" width="230" height="239" />';
        $retstr =
        '<div id="facebook">';
        /*$fbwidgetpath = $this->getResourceUri('fb-widget.inc.php', 'elsiskin');
        $fh = fopen($fbwidgetpath, 'r'); // or die($php_errormsg);
        $fbwidget = fread($fh, filesize($fbwidgetpath));
        $retstr .= $fbwidget;
        fclose($fh); // or die($php_errormsg);*/
        $retstr .= $this->objLink->show();

        $retstr .= '</div>';

        return $retstr;
    }

    /*
     * Method to read display the twitter link
     * @param none
     * @access public
     * @return str $retstr containing the string that shows the twitter link
     */
    public function getTwitter() {
        $this->objLink->link("http://twitter.com/elsi_wits");
        $this->objLink->link = '<img src="' . $this->skinpath . 'images/ELSITwitter.png" width="230" height="239" />';
        $retstr = '
        <div id="twitter">';
        $retstr .= $this->objLink->show();
        $retstr .= '</div>';

        return $retstr;
    }

    /*
     * Method to display the sidebar of the News and Blog pages that are linked
     * to the home page. This is not with regard to the news module. Customized
     * news or blog for one view.
     * @param none
     * @access public
     * @return string blank image for layout control
     *
     */
    public function showNewsBlogSidebar() {
        $retstr = '<!-- Start: Sidebar -->
                   <div id="Sidebar">
                        <div class="grid_1">
                            <p><img src="' . $this->skinpath . 'images/dots_blank.png"></p>
                        </div>
                   </div>';

        return $retstr;
    }

    /**
    * Method to show the Projects and Research page sidebar
    * @param none
    * @return string $retstr with the Projects and Research sidebar content
    * @access private
    */
    private function showProjectsSidebar() {
        $retstr = '<!-- Start: Sidebar -->
                <div id="Sidebar">
                    <div class="grid_1">
                        <p><img src="' . $this->skinpath . 'images/dots_clipboard.png"></p>
                    </div>
		</div>
		<!-- end .grid_1 -->
                <!-- End: Sidebar -->';
        return $retstr;
    }

    /*
     * Method to show the support and training page sidebar
     * @param none
     * @return string $retstr with the support and training sidebar content
     * @access private
     *
     */

    private function showSupportTrainingSidebar() {
        $retstr = '<!-- Start: Sidebar -->
                <div id="Sidebar">
                    <div class="grid_1">
                        <p><img src="' . $this->skinpath . 'images/dots_question.png"></p>
                    </div>
		</div>
		<!-- end .grid_1 -->
                <!-- End: Sidebar -->';


        return $retstr;
    }

    /*
     * Method to show the current news page sidebar
     * @param none
     * @return string $retstr with the current news sidebar content
     * @access private
     *
     */
    private function showCurrentNewsSidebar() {
        $retstr = '<!-- Start: Sidebar -->
                <div id="Sidebar">
                    <div class="grid_1">
                        <p><img src="' . $this->skinpath . 'images/dots_news.png"></p>
                    </div>
		</div>
		<!-- end .grid_1 -->
                <!-- End: Sidebar -->';


        return $retstr;
    }

    /*
     * Method to show the current news page sidebar
     * @param none
     * @return string $retstr with the current news sidebar content
     * @access private
     *
     */
    private function showAllSiteBlogsSidebar() {
        $retstr = '<!-- Start: Sidebar -->
                <div id="Sidebar">
                    <div class="grid_1">
                        <p><img src="' . $this->skinpath . 'images/dots_news.png"></p>
                    </div>
		</div>
		<!-- end .grid_1 -->
                <!-- End: Sidebar -->';


        return $retstr;
    }
}