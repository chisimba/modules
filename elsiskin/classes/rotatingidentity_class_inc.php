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
	
	// file manager object
	private $objFileManager;
	
	// stories for rotating banners at the top
	private $stories;


    /**
     * Constructor
     */
    public function init() {
		$this->objCategory = $this->getObject('dbnewscategories', 'news');
		$this->objNews = $this->getObject('dbnewsstories','news');
		$this->objFileManager = $this->getObject('dbfile', 'filemanager');
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
		 switch($action) {
			default:$category = 'rotating_identity';
		 }
		 
		 $exists = $this->objCategory->categoryExists($category);
		 if($exists) {
			$categoryid = $this->objCategory->getCategoryById($category);
			$this->stories = $this->objNews->getCategoryStories($categoryid);;
		 }
		 
		 $retstr = '<div id="Identity_image"> 
            <div class="clear">&nbsp;</div>
                <div class="grid_1 push_3"><a onmouseover="MM_swapImage(\'Login\',\'\',\''.$this->skinpath.'images/login_1.png\',1)" onmouseout="MM_swapImgRestore()" href="https://elearn.wits.ac.za/">
                    <img border="0" width="220" height="245" name="Login" alt="Login" src="'.$this->skinpath.'images/login.png">
                  </a></div>
    		<!-- end .grid_1 .push_3 -->
            <div class="grid_3 pull_1">
                <div class="transparent-overlay-holder">
                    <div class="transparent-overlay">';
			if($action == 'about') {
				$retstr .= '<div class="text-holder">
						  <span class="head-main">More About ELSI</span>
						  <span class="head-text">The Unit has been established to assist staff and students at Wits to make better use of ICT in their teaching 
						  learning.<br>  
						  <img width="16" height="16" src="'.$this->skinpath.'images/plus_more.gif">&nbsp;<a href="">Contact us</a></span>
						</div>';	
			}
			else if($action == 'staff') {
				$retstr .= '<div class="text-holder">
							  <span class="head-main">ELSI Staff Team</span>
							  <span class="head-text">The Unit has been established to assist staff and students at Wits to make better use of ICT in their teaching 
							  learning.<br>  
							  <img width="16" height="16" src="'.$this->skinpath.'images/plus_more.gif">&nbsp;<a href="">Contact us</a></span>
							</div>';	
			}
			else if($action == 'contact') {
				$retstr .= '<div class="text-holder">
							   <span class="head-main">Contact us at ELSI</span>
							   <span class="head-text">Visit us, pick up the phone, write an email, tweet a message. We\'d love to get in contact with you. <br>             
							   <img width="16" height="16" src="'.$this->skinpath.'images/plus_more.gif">&nbsp;<a href="">Office Administrator</a><br>
							   <img width="16" height="16" src="'.$this->skinpath.'images/plus_more.gif">&nbsp;<a href="">Directions</a></span>
                           </div>';
			}
			else {
				$retstr .= '
                        <div class="text-holder">
                            <span class="head-main">'.$this->stories[0]['storytitle'].'</span>
                            <span class="head-text">'.$this->stories[0]['storytext'].'<br>
                                <img width="16" height="16" src="'.$this->skinpath.'images/plus_more.gif">&nbsp;<a href="about/index.html">Latest New</a>s<br>
                                <img width="16" height="16" src="'.$this->skinpath.'images/plus_more.gif">&nbsp;<br>
                            </span>
                        </div>';
			}
			$retstr .= '
                    </div>
                </div>
                ';
		 
		 switch($action) {
			 case 'staff': $retstr .= $this->showStaffBanner();
			               break;
			 case 'about': $retstr .= $this->showAboutBanner();
			 			   break;
			 case 'contact': $retstr .= $this->showContactBanner();
			 			   break;
		 	 default: $retstr .= $this->showHomeBanner();
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
	
	public function showHomeBanner() {
		$retstr = '<div style="z-index: 1; position: relative; width: 700px; height: 245px;" class="slideshow">';
		
		foreach($this->stories as $row) {
			$fileDetails = $this->objFileManager->getFile($row['storyimage']);
			$path = $fileDetails['path'];
			$retstr .= '<img src="usrfiles/'.$path.'"  
						 style="position: absolute; top: 0px; left: 0px; display: none; z-index: 4; opacity: 0; width: 700px; height: 245px;">';
		}
		$retstr .= '</div>';
		
		return $retstr;
	}
	
	
	public function showAboutBanner() {
		$retstr = '
			<ul class="slideshow">
				<li class="show">
					<a href="http://www.google.com"><img width="700" height="245" alt="eLSI is located on the 18th floor at University Corner." title="Find us at University Corner" src="'.$this->skinpath .'images/about_office.jpg"></a>
				</li>
			</ul>';
			
		return $retstr;		
	}
	
	public function showStaffBanner() {
		$retstr = '<img src="'.$this->skinpath.'images/staff_cubicles.jpg">';
					
		return $retstr;
	}
	
	public function showContactBanner() {
		$retstr = '<img src="'.$this->skinpath.'images/contact_address.jpg">';	
		
		return $retstr;
	}
}