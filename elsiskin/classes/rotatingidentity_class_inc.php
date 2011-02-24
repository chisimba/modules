<?php

/*
 *
 * A class to display the banner images at the top of the site. The content displayed will depends on the
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
 * @version   CVS: $Id: rotatingidentity_class_inc.php,v 1.1 2007-11-25 09:13:27 nguni52 Exp $
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

class rotatingidentity extends object {

    // path to root folder of skin
    private $skinpath;
    // news stories object
    private $objNews;
    // news category object
    private $objCategory;

    /**
     * Constructor
     */
    public function init() {
        $this->objCategory = $this->getObject('dbnewscategories', 'news');
        $this->objNews = $this->getObject('dbnewsstories', 'news');
    }

    /**
     * Method to show the Toolbar
     * @return string $retstr containing all the toolbar links.
     */
    public function setSkinPath($skinpath) {
        $this->skinpath = $skinpath;
    }

    /**
     * Method to show the rotating images with news right below the toolbar
     * @return string
     */
    public function show($action) {
        $this->loadClass('link', 'htmlelements');
        if($action == 'viewsingle' || $action == 'viewstory') {
            $category = 'home_news';
        }
        else {
            $category = $action."_news";
        }
        $exists = $this->objCategory->categoryExists($category);
        //$exists = FALSE;
        if($exists) {
            $categoryId = $this->objCategory->getCategoryId($category);
            $news = $this->objNews->getCategoryStories($categoryId);
        }
        else {
            $news = "";
        }
        $objUser = $this->getObject('user', 'security');
        $userIsLoggedIn = $objUser->isLoggedIn();

        $retstr = '<div id="Identity_image">
                    <div class="clear">&nbsp;</div>
                    <div class="grid_1 push_3">
                        <a onmouseover="MM_swapImage(\'Login\',\'\',\'' . $this->skinpath . 'images/';
                        if($userIsLoggedIn) {
                            $retstr .= 'logout_1.png\',1)"';
                        }
                        else {
                            $retstr .= '/login_1.png';
                        }
                        $retstr .= '\',1)" onmouseout="MM_swapImgRestore()" href="';
                        if($userIsLoggedIn) {
                          $retstr .= $this->uri(array("action"=>"logoff"),"security").'">';
                        }
                        else {
                          $retstr .= $this->uri(array("action"=>"home"),"postlogin").'">';
                        }
                        $retstr.='   <img border="0" width="220" height="245" name="Login" alt="Login" src="' . $this->skinpath . 'images/';
                        if($userIsLoggedIn) {
                            $retstr .='logout.png">';
                        }
                        else {
                            $retstr .='login.png">';
                        }

       $retstr .='                 </a>
                    </div>
                    <!-- end .grid_1 .push_3 -->
                    <div class="grid_3 pull_1">
                        <div class="transparent-overlay-holder">
                            <div class="transparent-overlay">';
        if ($action == 'about') {
            $retstr .= '    <div class="text-holder">
                                <span class="head-main">More About ELSI</span>
                                <span class="head-text">The Unit has been established to assist staff and students at Wits to make better use of ICT in their teaching
                          learning.<br>
                                <img width="16" height="16" src="' . $this->skinpath . 'images/plus_more.gif">&nbsp;<a href="">Contact us</a></span>
                            </div>';
        } else if ($action == 'staff') {
            $retstr .= '<div class="text-holder">
                          <span class="head-main">ELSI Staff Team</span>
                          <span class="head-text">The Unit has been established to assist staff and students at Wits to make better use of ICT in their teaching
                          learning.<br>
                          <img width="16" height="16" src="' . $this->skinpath . 'images/plus_more.gif">&nbsp;<a href="">Contact us</a></span>
                        </div>';
        } else if ($action == 'contact') {
            $contactLink = new link("http://maps.google.co.za/maps?q=11-17+jorissen+street&hl=en&ie=UTF8&hq=&hnear=17+Jorissen+St,+Johannesburg,+Gauteng+2000&gl=za&ll=-26.192797,28.033247&spn=0.023105,0.025749&z=14&iwloc=A&source=embed");
            $contactLink->link = '<img width="16" height="16" src="' . $this->skinpath . 'images/plus_more.gif">&nbsp;Directions';
            $retstr .= '<div class="text-holder">
                           <span class="head-main">Contact us at ELSI</span>
                           <span class="head-text">Visit us, pick up the phone, write an email, tweet a message. We\'d love to get in contact with you. <br>
                           <img width="16" height="16" src="' . $this->skinpath . 'images/plus_more.gif">&nbsp;<a href="">Office Administrator</a><br>';
            $retstr .= $contactLink->show();
            $retstr .= '</span>
                        </div>';
        } else if($action == 'projectsresearch') {
          $retstr .= '<div class="text-holder">
                          <span class="head-main">Projects & Research</span>
                          <span class="head-text">eLSI offers workshops for Schools or Faculties as well as individual face-to-face consultations.<br>
                          <img width="16" height="16" src="' . $this->skinpath . 'images/plus_more.gif">&nbsp;<a href="?module=elsiskin&action=contact">Contact us</a></span>
                        </div>';
        } else if($action == 'viewstory') {
            $retstr .= '
                            <div class="text-holder">';
                foreach($news as $row) {
                    if($row['id'] == $this->getParam('id')) {
                        $aboutLink = new link($this->uri(array("module"=>"elsiskin", "action"=>"about")));
                        $aboutLink->link = "Latest News";
                        $retstr .= '

                                        <div class = "news" id=\''.$row['id'].'\'>
                                            <span class="head-main">'.trim(strip_tags($row['storytitle'])).'</span>';
                            $retstr .= '    <span class="head-text">'.substr(trim(strip_tags($row['storytext'])), 0, 100).' ...<br>
                                                <img src="' . $this->skinpath . 'images/plus_more.gif" width="16" height="16">&nbsp;'.$aboutLink->show().'<br>
                                            </span>
                                        </div>';
                    }
                }
                $retstr .= '
                            <input type="hidden" id="newslink" value="'.$this->uri(array("action"=>"viewstory")).'" />
                            </div>';
        } else {
            
            if(!empty($news)) {
                $retstr .= '
                            <div class="text-holder">
                                <div id="s7">';
                foreach($news as $row) {
                    $aboutLink = new link($this->uri(array("module"=>"elsiskin", "action"=>"about")));
                    $aboutLink->link = "Latest News";
                    $retstr .= '
                                
                                    <div class = "news" id=\''.$row['id'].'\'>
                                        <span class="head-main">'.trim(strip_tags($row['storytitle'])).'</span>';
                        $retstr .= '    <span class="head-text">'.substr(trim(strip_tags($row['storytext'])), 0, 100).' ...<br>
                                            <img src="' . $this->skinpath . 'images/plus_more.gif" width="16" height="16">&nbsp;'.$aboutLink->show().'<br>
                                        </span>
                                    </div>';
                }
                $retstr .= '
                                </div>
                                <input type="hidden" id="newslink" value="'.$this->uri(array("action"=>"viewstory")).'" />
                            </div>';
            }
            else {
                $retstr .= '<div class="text-holder">
                    <span class="head-main">WELCOME TO ELSI</span>
                    <span class="head-text">No Current News.<br>
                        
                    </span>
                </div>';
            }
        }
        $retstr .= '
                    </div>
                </div>
                ';

        switch ($action) {
            case 'staff': $retstr .= $this->showStaffBanner();
                break;
            case 'about': $retstr .= $this->showAboutBanner();
                break;
            case 'contact': $retstr .= $this->showContactBanner();
                break;
            case 'projectsresearch': $retstr .= $this->showProjectsBanner();
                break;
            case 'viewstory':$retstr .= $this->showNewsBanner($news);
                break;
            default: $retstr .= $this->showHomeBanner($news);
        }

        $retstr .= '
            </div>
            <!-- end .grid_3.pull_1 -->
	    <div class="grid_4">&nbsp;
    </div>
    <!-- end .grid_4 -->
    <div class="clear">&nbsp;
    </div>
    <div class="grid_3 push_1">&nbsp;
    </div>
    <!-- end .grid_3 .push_1 -->
    </div>
    <!-- End: Identity image -->';

        return $retstr;
    }

    /*
     * Method to display the home banner
     * @param $news containing the array with the different news for the day
     * @access public
     * @return string $retstr contaning the div with the rotating images with
     * the news linked to them
     */
    public function showHomeBanner($news) {
        if(!empty($news)) {
            $objFile = $this->getObject('dbfile', 'filemanager');
            $retstr = '<div class="slideshow" style="z-index:1;">';
            foreach($news as $row) {
                $myFile = $objFile->getFile($row['storyimage']);
                $retstr .= '<img class="storyimage" id="'.$row['id'].'"  src="usrfiles/' .$myFile['path'].'">';
            }
            $retstr .= '</div>';
        }
        else {
            $retstr = '<div class="slideshow" style="z-index:1;">
                           <img src="' . $this->skinpath . 'images/front_identity/home_dread.jpg">
                           <img src="' . $this->skinpath . 'images/front_identity/mlearning_website.png">
                           <img src="' . $this->skinpath . 'images/front_identity/oaweek_website.png">
                           <img src="' . $this->skinpath . 'images/front_identity/passport_website.png">
                       </div>';
        }
        return $retstr;
    }

    /*
     * Method to display the about us page banner.
     * @access public
     * @return string $retstr containing the banner for about us page
     */
    public function showAboutBanner() {
        $retstr = '
                    <ul class="slideshow">
                        <li class="show">
                            <a href="http://www.google.com"><img width="700" height="245" alt="eLSI is located on the 18th floor at University Corner." title="Find us at University Corner" src="' . $this->skinpath . 'images/about_office.jpg"></a>
                        </li>
                    </ul>';

        return $retstr;
    }

    /*
     * Method to display the staff page banner.
     * @access public
     * @return string $retstr containing the banner for staff page
     */
    public function showStaffBanner() {
        $retstr = '<img src="' . $this->skinpath . 'images/staff_cubicles.jpg">';

        return $retstr;
    }

    /*
     * Method to display the contact us page banner.
     * @access public
     * @return string $retstr containing the banner for contact us page
     */
    public function showContactBanner() {
        $retstr = '<img src="' . $this->skinpath . 'images/contact_address.jpg">';

        return $retstr;
    }

    private function showProjectsBanner() {
        $retstr = '<img src="' . $this->skinpath . 'images/research_computer.jpg">';

        return $retstr;
    }

    public function showNewsBanner($news) {
        if(!empty($news)) {
            $objFile = $this->getObject('dbfile', 'filemanager');
            $retstr = '<div class="slideshow" style="z-index:1;">';
            foreach($news as $row) {
                if($row['id'] == $this->getParam('id')) {
                    $myFile = $objFile->getFile($row['storyimage']);
                    $retstr .= '<img class="storyimage" id="'.$row['id'].'"  src="usrfiles/' .$myFile['path'].'">';
                }
            }
            $retstr .= '</div>';
        }
        else {
            $retstr = '<div class="slideshow" style="z-index:1;">
                           <img src="' . $this->skinpath . 'images/front_identity/home_dread.jpg">
                           <img src="' . $this->skinpath . 'images/front_identity/mlearning_website.png">
                           <img src="' . $this->skinpath . 'images/front_identity/oaweek_website.png">
                           <img src="' . $this->skinpath . 'images/front_identity/passport_website.png">
                       </div>';
        }

        return $retstr;
    }

}