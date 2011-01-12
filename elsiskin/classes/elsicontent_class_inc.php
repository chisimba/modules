<?php
/*
 *
 * A module to get the content of the elsi website. The content displayed will depends on the
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


    /**
     * Constructor
     */
	 
    public function init() {
		$this->sidebar = $this->getObject('sidebar');
		$this->mainContent = $this->getObject('maincontent');	
		$this->objNews = $this->getObject('dbnewsstories','news');
		$this->objCategory = $this->getObject('dbnewscategories', 'news');
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
		 // set current action
		$this->sidebar->setCurrentAction($action);
		$this->sidebar->setSkinPath($this->skinpath);
		$this->mainContent->setCurrentAction($action);
		$this->mainContent->setSkinPath($this->skinpath);

		return $this->getContent($action);
	 }
	 
	 private function getContent($action) {	
		// get the content of the page
		$retstr = '<div id="Content">';
		$retstr .= $this->getIntroText($action);	
		$retstr .= $this->sidebar->show();
		$retstr .= '<div id="Main">'.$this->mainContent->show().'</div>
					<!-- End: Main -->
				<!-- End: Content -->';

	    return $retstr;
	 }
	 
	 private function getIntroText($action) {
		 if($action == 'about') {
			 $ret = '<div class="clear">&nbsp;</div>
					 <div class="grid_1">
						<h3><a href="">Welcome to eLSI</a></h3>
					</div>
					<div class="grid_3">
						<h2><a href="">About ELSI</a></h2>
					 </div>
					 <div class="grid_1 pull_3">
						<h3>More about eLSI</h3>
				  	 </div><div class="clear">&nbsp;</div>';
		 }
		 else if($action == 'staff') {
			$ret = '<div class="clear">&nbsp;</div>
					<div class="grid_1 ">
						<h3><a href="">Staff at eLSI</a></h3>
				    </div>
				    <div class="grid_3">
						<h2><a href="">Who works at ELSI?</a></h2>
			  		</div>
					<div class="clear">&nbsp;</div>'; 
		 }
		 else if($action == 'contact') {
			$ret = '<div class="clear">&nbsp;</div>
					<div class="grid_1">
						<h3><a href="index.html">Contact eLSI</a></h3>
				    </div>
					<div class="grid_3">
						<h2><a href="index.html">Can ELSI be of assistance?</a></h2>
					</div>
					<div class="grid_1 pull_3">
						<h3>More about eLSI</h3>
				  	</div>
					<div class="clear">&nbsp;</div>'; 
		 }
		 else {
			 $exists = $this->objCategory->categoryExists('home_content');
			 if($exists) {
			 	$categoryid = $this->objCategory->getCategoryById('home_content');
				$stories = $this->objNews->getCategoryStories($categoryid);
			 }
			 $ret = '<div class="clear">&nbsp;
				</div>
				<div class="grid_1">
	
					<h3><a href="">';
					foreach($stories as $row) {
						if(stristr($row['storytitle'],'welcome') != FALSE) {
							$ret .= $row['storytitle'];
						}
					}
			$ret .= '</a></h3>
					
					</div>
					<div class="grid_3">
						<h2><a href="">';
					foreach($stories as $row) {
						if(stristr($row['storytitle'],'help') != FALSE) {
							$ret .= $row['storytitle'];
							
							$ret .= '</a></h2>
							</div>
							<!-- end .grid_1 -->
							<div class="clear">&nbsp;
							</div>
			
							<div class="grid_1">
							&nbsp;
							</div>
							<div class="grid_3">'.$row['storytext'].'</div>
							<!-- end .grid_1 -->
			
							<div class="clear">&nbsp;</div>';
						}
					}
		}
		 
	 	return $ret;
	 }
}