<?php

/*
 *
 * A class to get the content of the elsi website. The content displayed will depends on the
 * page being displayed. It could be content for home page, about us, staff or contact us.
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
 * @version   CVS: $Id: elsicontent_class_inc.php,v 1.1 2011-01-12 09:13:27 nguni52 Exp $
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

class elsicontent extends object {

    // path to root folder of skin
    private $skinpath;
    // instance of sidebar
    private $sidebar;
    // instance of the main contents of a page
    private $mainContent;
    // news stories object
    private $objNews;
    // news category object
    private $objCategory;
    private $objLanguage;

    /**
     * Constructor
     */
    public function init() {
        $this->sidebar = $this->getObject('sidebar', 'elsiskin');
        $this->mainContent = $this->getObject('maincontent', 'elsiskin');
        $this->objNews = $this->getObject('dbnewsstories', 'news');
        $this->objCategory = $this->getObject('dbnewscategories', 'news');
        $this->objLanguage = $this->getObject("language", "language");
    }

    /**
     * Method to set the skin path
     * @return none
     * @access public
     */
    public function setSkinPath($skinpath) {
        $this->skinpath = $skinpath;
    }

    /**
     * Method to show the rotating images with news right below the toolbar
     * @return string  string $retstr with all the content and sidebar info.
     * @access public
     */
    public function show($action, $id=null) {
        // set current action
        $this->sidebar->setCurrentAction($action);
        $this->sidebar->setSkinPath($this->skinpath);
        $this->mainContent->setCurrentAction($action);
        $this->mainContent->setSkinPath($this->skinpath);

        if($action == 'viewstory') {
            $id = $this->getParam('id');
            if(!empty($id)) {
                $this->mainContent->setNewsId($id);
            }
        }
    
        return $this->getContent($action);
    }

    /* Method to display the content of the page
     * @return string  string $retstr with all the content and sidebar info.
     * @access private
     */

    private function getContent($action) {
        // get the content of the page
        echo '<!-- Start: Content -->
              <div id="Content">';
        $retstr = $this->getIntroText($action);
        $retstr .= $this->sidebar->show();
        $retstr .= '<div id="Main">' 
                        . $this->mainContent->show()
                   .'</div>
                    <!-- End: Main -->
              ';

        return $retstr;
    }

    /*
     * Method for displaying intro text for the main content of a page.
     * @return string $retstring content for the main part of the page inside the div main.
     * @access private
     */

    private function getIntroText($action) {
        $this->loadClass("link", "htmlelements");

        if($action == 'about') {
            $ret = $this->getAboutContent();
        }
        else if($action == 'staff') {
            $ret = $this->getStaffContent();
        }
        else if($action == 'contact') {
            $ret = $this->getContactContent();
        }
        else if($action == 'projectsresearch') {
            $ret = $this->getProjectsContent();
        }
        else if($action == 'supporttraining') {
            $ret = $this->getSupportContent();
        }
        else if($action == 'currentnews') {
            $ret = $this->getCurrentNewsContent();
        }
        else if($action == 'viewsingle' || $action == 'viewstory' || $action == 'projectsresearch' || $action == 'supporttraining'){
            $ret = $this->getNoContent();
        }
        else if($action == 'allsiteblogs') {
            $ret = $this->getAllSiteBlogsContent();
        }
        else {
            $homeLink = new link($this->uri(array("action"=>"home")));
            $homeLink->link = $this->objLanguage->languageText('mod_elsiskin_welcome', 'elsiskin');
            $ret = '
                <div class="clear">&nbsp;</div>
                <div class="grid_1">
                    <h3>'.$homeLink->show().'</h3>
                </div>';
           $homeLink->link = $this->objLanguage->languageText('mod_elsiskin_homehowtohelp', 'elsiskin');
            $ret.='
                <div class="grid_3">
                    <h2>'.$homeLink->show().'</h2>
                </div>
                <!-- end .grid_1 -->
                <div class="clear">&nbsp;</div>
                <div class="grid_1">&nbsp;</div>
                <div class="grid_3">
                    '.$this->getHomeIntroTextContent().'
                </div>
                <!-- end .grid_1 -->
                <div class="clear">&nbsp;</div>';
        }
        
        return $ret;
    }

    /*
     * This method displays the introduction text for the home page
     * @param none
     * @access public
     * @return string $retstr The string that contains the content for the home introduction text
     *
     */
    public function getHomeIntrotextContent() {
        $retstr = "";
        $objCategories = $this->getObject("dbnewscategories", "news");
        $news = $this->getObject("dbnewsstories", "news");
        $categories = $objCategories->getCategories();

        $documentation = "Introduction Text for the home page has not yet been set up";

        foreach ($categories as $cat) {

            if ($cat['categoryname'] == 'home_intro_text') {
                $documentationId = $cat['id'];
                $documentationStories = $news->getCategoryStories($documentationId);
                $documentation = $documentationStories[0]['storytext'];
            }
        }

        $retstr .= $documentation;
        
        $retstr .= '<a 
                        href=\'javascript:popOnLoad();\'>Click Me </a>';
        $retstr .= '<script type="text/javascript">
                        function popOnLoad()
                    {
                        mywindow = window.open("http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'].$this->getResourceURI('flash').'/zawww.html",
                                                "mywindow","location=1, status=1,scrollbars=1, width=850,height=450");
                        mywindow.moveTo(0, 0);
                    }

                    </script>';
        
        return $retstr;
    }

    /*
     * This method displays the introduction text for the about page
     * @param none
     * @access private
     * @return string $retstr The string that contains the content for the about introduction text
     *
     */
    private function getAboutContent() {
        $aboutLink = new link($this->uri(array("action"=>"about")));
        $aboutLink->link = $this->objLanguage->languageText('mod_elsiskin_welcome', 'elsiskin');
        $ret = '
                <div class="clear">&nbsp;</div>
                <div class="grid_1">
                    <h3>'.$aboutLink->show().'</h3>
                </div>';
        $aboutLink->link = $this->objLanguage->languageText('mod_elsiskin_aboutelsi', 'elsiskin');
        $ret .= '
                <div class="grid_3">
                    <h2>'.$aboutLink->show().'</h2>
                </div>
                <!-- end .grid_1.pull_3 -->
                <div class="clear">&nbsp;</div>';

        return $ret;
    }

    /*
     * This method displays the introduction text for the staff page
     * @param none
     * @access private
     * @return string $retstr The string that contains the content for the staff introduction text
     *
     */
    private function getStaffContent() {
        $staffLink = new link($this->uri(array("action"=>"staff")));
        $staffLink->link = $this->objLanguage->languageText('mod_elsiskin_elsistaff', 'elsiskin');
        $ret = '
            <div class="clear">&nbsp;</div>
            <div class="grid_1 ">
                <h3>'.$staffLink->show().'</h3>
            </div>';
        $staffLink->link = $this->objLanguage->languageText('mod_elsiskin_elsiwhoworks', 'elsiskin');
        $ret .='
            <div class="grid_3">
                <h2><a href="">'.$staffLink->show().'</a></h2>
            </div>
            <!-- end .grid_1.pull_3 -->
            <div class="clear">&nbsp;</div>';

        return $ret;
    }

    /*
     * This method displays the introduction text for the contact us page
     * @param none
     * @access public
     * @return string $retstr The string that contains the content for the contact us introduction text
     *
     */
    private function getContactContent() {
        $contactLink = new link($this->uri(array("action"=>"contact")));
        $contactLink->link = $this->objLanguage->languageText('mod_elsiskin_contactus', 'elsiskin');
        $ret = '
            <div class="clear">&nbsp;</div>
            <div class="grid_1">
                <h3>'.$contactLink->show().'</h3>
            </div>';
        $contactLink->link = $this->objLanguage->languageText('mod_elsiskin_contactassist', 'elsiskin');
        $ret .='
            <div class="grid_3">
                <h2>'.$contactLink->show().'</h2>
            </div>
            <!-- end .grid_1.pull_3 -->
            <div class="clear">&nbsp;</div>';

        return $ret;
    }

    /*
     * This method displays no introduction text
     * @param none
     * @access private
     * @return string $retstr The string that contains no content
     *
     */
    private function getNoContent() {
        $sideBarLink = new link($this->uri(array(), "news"));
        $sideBarLink->link = $this->objLanguage->languageText('mod_elsiskin_welcome', 'elsiskin');
        $ret = '
            <div class="clear">&nbsp;</div>
            <div class="grid_1">
                <h3>'.$sideBarLink->show().'</h3>
            </div>
            <div class="grid_3">&nbsp;</div>
            <div class="clear">&nbsp;</div>';

        return $ret;
    }

    /*
     * This method displays the introduction text for the projects page
     * @param none
     * @access private
     * @return string $retstr The string that contains the content for the projects introduction text
     *
     */
    private function getProjectsContent() {
        $researchLink = new link($this->uri(array("action"=>"projectsresearch")));
        $researchLink->link = $this->objLanguage->languageText('mod_elsiskin_welcomeprojects', 'elsiskin');
        $ret = '
            <div class="clear">&nbsp;</div>
            <div class="grid_1">
                <h3>'.$researchLink->show().'</h3>
            </div>';
        $researchLink->link = $this->objLanguage->languageText('mod_elsiskin_projectsheading', 'elsiskin');
        $ret .='
            <div class="grid_3">
                <h2>'.$researchLink->show().'</h2>
            </div>
            <!-- end .grid_1.pull_3 -->
            <div class="clear">&nbsp;</div>';

        return $ret;
    }

    /*
     * This method displays the introduction text for the support page
     * @param none
     * @access private
     * @return string $retstr The string that contains the content for the support introduction text
     *
     */
    private function getSupportContent() {
        $supportLink = new link($this->uri(array("action"=>"supporttraining")));
        $supportLink->link = $this->objLanguage->languageText('mod_elsiskin_welcomesupport', 'elsiskin');
        $ret = '
            <div class="clear">&nbsp;</div>
            <div class="grid_1">
                <h3>'.$supportLink->show().'</h3>
            </div>';
        $supportLink->link = $this->objLanguage->languageText('mod_elsiskin_supportheading', 'elsiskin');
        $ret .='
            <div class="grid_3">
                <h2>'.$supportLink->show().'</h2>
            </div>
            <!-- end .grid_1.pull_3 -->
            <div class="clear">&nbsp;</div>';

        return $ret;
    }

    /*
     * This method displays the introduction text for the current news page
     * @param none
     * @access private
     * @return string $retstr The string that contains the content for the current news introduction text
     *
     */
    private function getCurrentNewsContent() {
        $currentNewsLink = new link($this->uri(array("action"=>"currentnews")));
        $currentNewsLink->link = $this->objLanguage->languageText('mod_elsiskin_welcomenews', 'elsiskin');
        $ret = '
            <div class="clear">&nbsp;</div>
            <div class="grid_1">
                <h3>'.$currentNewsLink->show().'</h3>
            </div>';
        $currentNewsLink->link = $this->objLanguage->languageText('mod_elsiskin_newsheading', 'elsiskin');
        $ret .='
            <div class="grid_3">
                <h2>'.$currentNewsLink->show().'</h2>
            </div>
            <!-- end .grid_1.pull_3 -->
            <div class="clear">&nbsp;</div>';

        return $ret;
    }

    /*
     * This method displays the introduction text for the site-wide blogs page
     * @param none
     * @access private
     * @return string $retstr The string that contains the content for the site-wide blogs introduction text
     *
     */
    private function getAllSiteBlogsContent() {
        $allSiteBlogsLink = new link($this->uri(array("action"=>"allsiteblogs")));
        $allSiteBlogsLink->link = $this->objLanguage->languageText('mod_elsiskin_welcomeallblogs', 'elsiskin');
        $ret = '
            <div class="clear">&nbsp;</div>
            <div class="grid_1">
                <h3>'.$allSiteBlogsLink->show().'</h3>
            </div>';
        $allSiteBlogsLink->link = $this->objLanguage->languageText('mod_elsiskin_allblogsheading', 'elsiskin');
        $ret .='
            <div class="grid_3">
                <h2>'.$allSiteBlogsLink->show().'</h2>
            </div>
            <!-- end .grid_1.pull_3 -->
            <div class="clear">&nbsp;</div>';

        return $ret;
    }
}
