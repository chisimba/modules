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
        $retstr = '<div id="Content">';
        $retstr .= $this->getIntroText($action);
        $retstr .= $this->sidebar->show();
        $retstr .= '<div id="Main">' . $this->mainContent->show() . '</div>
                    <!-- End: Main -->
            <!-- End: Content -->';

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
                <div class="grid_1 pull_3">
                    <h3>'.$this->objLanguage->languageText('mod_elsiskin_moreaboutelsi', 'elsiskin').'</h3>
                </div>
                <!-- end .grid_1.pull_3 -->
                <div class="clear">&nbsp;</div>';
        }
        else if($action == 'staff') {
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
        }
        else if($action == 'contact') {
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
                <div class="grid_1 pull_3">
                    <h3>More about eLSI</h3>
                </div>
                <!-- end .grid_1.pull_3 -->
                <div class="clear">&nbsp;</div>';
        }
        else if($action == 'viewsingle' || $action == 'viewstory'){
            $ret = '
                <div class="clear">&nbsp;</div>
                <div class="grid_3">

                </div>
                <div class="clear">&nbsp;</div>';
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

        return $retstr;
    }

}