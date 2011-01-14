<?php
/*
 *
 * A class to get the main content of the elsi wits site. The content displayed will depends on the
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
 * @version   CVS: $Id: maincontent_class_inc.php,v 1.1 2011-01-12 09:13:27 nguni52 Exp $
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

class maincontent extends object {
	// page main content displayed according to current action
	private $currentAction;
	
	// path to root folder of skin
	private $skinpath;
	
	// news stories object
	private $objNews;
	
	// news category object
	private $objCategory;
	
	// file manager object
	private $objFileManager;

        // object for language elements
        private $objLanguage;
	
	/**
     * Constructor
     */
    public function init() {
		$this->currentAction = 'home';
		$this->objNews = $this->getObject('dbnewsstories','news');
		$this->objCategory = $this->getObject('dbnewscategories', 'news');
		$this->objFileManager = $this->getObject('dbfile', 'filemanager');
                $this->objLanguage = $this->getObject("language","language");
	}
	
	/* Method to set the current action of the page
	 *
	 */
	public function setCurrentAction($action) {
		$this->currentAction = $action;
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
	 public function show() {
		 	switch($this->currentAction) {
				case 'about': return $this->showAboutMain();
				case 'staff': return $this->showStaffMain();
				case 'contact': return $this->showContactMain();
				default: return $this->showHomeMain();	
			}
	 }
			
	 private function showHomeMain() {		
		$exists = $this->objCategory->categoryExists('home_body_content');
		 if($exists) {
			$categoryid = $this->objCategory->getCategoryById('home_body_content');
			$stories = $this->objNews->getCategoryStories($categoryid);
		 }
		//print_r($stories);
		
		$retstr = "";
		foreach($stories as $row) {
			$fileDetails = $this->objFileManager->getFile($row['storyimage']);
			$path = $fileDetails['path'];
			$retstr .= '<div class="grid_1">
						<img src="usrfiles/'.$path.'">';
			$retstr .= '<h4>'.$row['storytitle'].'</h4>
						<p>'.$row['storytext'].'</p>
					</div>';
		}
		
		$retstr .= '<!-- end .grid_1 -->';						
		
			
		$retstr .= '<div class="clear">&nbsp;</div>
                                    <div class="grid_2">
                                            <div class="info-box-holder">
                                                    <div class="left_wrap">
                                                            <h2>ELSI Staff Blog</h2>
                                                    </div>
                                            </div>
                                    </div>
                                    <div class="grid_2">
                                            <p>&nbsp;</p>
                                    </div>
                                    <!-- end .grid_1 --> <div class="clear">&nbsp;</div>

                                    <div class="grid_2">
                                            <p>Blogs Outline goes here</p>
                                    </div>
                                    <div class="grid_2">
                                            <div class="info-box-holder">
                                                    <div class="right_wrap">
                                                            <h2>Support and Help</h2>
                                                    </div>

                                            </div>
                                            <p>Placeholder for support and documentation</p>


                                    </div>
                                    <div class="clear">&nbsp;</div>
                                    <div class="grid_2">
                                            <p>&nbsp;</p>
                                    </div>

                                    <div class="grid_2">

                                    </div>

                            </div>';
		return $retstr;
	 }
	 
	 public function showAboutMain() {
            $exists = $this->objCategory->categoryExists('about_content');
            if($exists) {
                $categoryid = $this->objCategory->getCategoryById('about_content');
                $stories = $this->objNews->getCategoryStories($categoryid);
            }

            $retstr .= '<div class="grid_3">';
            foreach($stories as $row) {
                $retstr .= $row['storytext'];
            }
            $retstr .= '<ol id="'.$stories[0]['storytitle'].'">';
            $exists = $this->objCategory->categoryExists('about_body_content');
            if($exists) {
                $categoryid = $this->objCategory->getCategoryById('about_body_content');
                $stories = $this->objNews->getCategoryStories($categoryid);
            }

            foreach($stories as $row) {
                $retstr .= '<li>'.$row['storytext'].'</li>';
            }

            $retstr .= '</ol></div><!-- End: grid_3 -->';

            return $retstr;
	 }
	 
	 public function showStaffMain() {
		$retstr = '<div class="grid_3">
					<p>ELSI staff have an interest and the ability to assist with the effective educational use of ICTs 
					</p><div id="container">
					
					
					<ul class="business_cards">
						<li>
							<a name="modal" href="#dialog11">
							<h3 class="name">Agnes Chigona</h3>
							<img width="75" height="90" class="left" alt="" src="'.$this->skinpath.'images/AgnesChigona.jpg">
								<p class="jobtitle">Research Fellow</p>
								<span class="phone">+ 27 11 717 7181</span><br>
								<span class="email">agnes.chigona@wits.ac.za</span><br>	
							</a>
						</li>
						<li>
							<a name="modal" href="#dialog6">
							<h3 class="name">Shakira Choonara</h3>
							<img width="75" height="90" alt="Shakira Choonara" class="left" src="'.$this->skinpath.'images/schoonara.jpg">
								<p class="jobtitle">Office Administrator</p>
								<span class="phone">+27 11 717 7161</span><br>
								<span class="email">shakira.choonara2@wits.ac.za</span><br>
							</a>
						</li>
						<li>
							<a name="modal" href="#dialog2">
							<h3 class="name">Rabelani Dagada</h3>
							<img width="75" height="90" class="left" alt="Rabelani Dagada" src="'.$this->skinpath.'images/RDagada.jpg">
								<p class="jobtitle">e Learning Manager</p>
								<span class="phone">+ 27 11 717 7184</span><br>
								<span class="email">rabelani.dagada@wits.ac.za</span><br>
							</a>
						</li>
						<li>
							<a name="modal" href="#dialog1">
							<h3 class="name">Taurai Hungwe</h3>
							<img width="75" height="90" class="left" alt="T Hungwe" src="'.$this->skinpath.'images/THungwe.jpg">
								<p class="jobtitle">Instructional Designer</p>
								<span class="phone">+ 27 11 717 7184</span><br>
								<span class="email">taurai.hungwe@wits.ac.za</span><br>	
							</a>	
						</li>
						<li>
							<a name="modal" href="#dialog15"><h3 class="name">Noxolo Mbana </h3>
							<img width="75" height="90" class="left" alt="" src="'.$this->skinpath.'images/noxolombana.jpg">
								<p class="jobtitle">Researcher</p>
								<span class="phone">+27 11 717 7164</span><br>
								<span class="email">noxolo.mbana@wits.ac.za</span><br>	
							</a>
						</li>
						<li>
							<a name="modal" href="#dialog14">
							<h3 class="name">Reginald Moledi</h3>
							<img width="75" height="90" class="left" alt="" src="'.$this->skinpath.'images/regimoledi.jpg">
								<p class="jobtitle">Instructional Developer</p>
								<span class="phone">+27 11 717 7170</span><br>
								<span class="email">reginald.moledi@wits.ac.za</span><br>
							</a>
						</li>	
						<li>
							<a name="modal" href="#dialog4">
							<h3 class="name">Derek Moore</h3>
							<img width="75" height="90" alt="Derek Moore" class="left" src="'.$this->skinpath.'images/dmoore.jpg">
									<p class="jobtitle">Content Developer</p>
									<span class="phone">+ 27 11 717 7171</span><br>
									<span class="email">derek.moore@wits.ac.za</span><br>	
							</a>
						</li>
						<li>
							<a name="modal" href="#dialog13">
							<h3 class="name">Paul Mungai</h3>
							<img width="75" height="90" class="left" alt="Paul Mungai" src="'.$this->skinpath.'images/PaulMungai.jpg">
									<p class="jobtitle">Software Developer</p>
									<span class="phone">+27 11 717 7183</span><br>
									<span class="email">paul.mungai@wits.ac.za</span><br>	
							</a>
						</li>
						<li>
							<a name="modal" href="#dialog10">
							<h3 class="name">Tessa Murray </h3>
							<img width="75" height="90" class="left" alt="Tessa Murray" src="'.$this->skinpath.'images/tessa_murry.jpg">
								<p class="jobtitle">Team leader: Content developer</p>
								<span class="phone">+ 27 11 717 7178</span><br>
								<span class="email">tessa.murray@wits.ac.za</span><br>	
							</a>
						</li>
						<li>
							<a name="modal" href="#dialog12">
							<h3 class="name"> Neo Petlele </h3>
							<img width="75" height="90" class="left" alt="Neo.Petlele" src="'.$this->skinpath.'images/Neo.Petlele.jpg">
									<p class="jobtitle">Research Assistant</p>
									<span class="phone">+27 11 717 7183</span><br>
									<span class="email">neo.Petlele@wits.ac.za</span><br>	
							</a>
						</li>
						<li>
							<a name="modal" href="#dialog3">
							<h3 class="name">Nkululeko (Nguni) Phakela</h3>
							<img width="75" height="90" class="left" alt="Nkululeko Phakela" src="'.$this->skinpath.'images/NkululekoPhakela.jpg">
							<p class="jobtitle">Software Developer</p>
							<span class="phone">+ 27 11 717 7182</span><br>
							<span class="email">nguni52@gmail.com</span><br>	
							</a>
						</li>	
						<li>
							<a name="modal" href="#dialog7">
							<h3 class="name">Fatima Rahiman</h3>
							<img width="75" height="90" alt="Fatima Rahiman" class="left" src="'.$this->skinpath.'images/frahiman.jpg">
							<p class="jobtitle">Team leader: Instructional designer</p>
							<span class="phone">+ 27 11 717 7174</span><br>
							<span class="email">fatima.rahiman@wits.ac.za</span><br>	
							</a>
						</li>
						<li>
							<a name="modal" href="#dialog5">
							<h3 class="name">James Smurthwaite</h3>
							<img width="75" height="90" class="left" alt="James Smurthwaite" src="'.$this->skinpath.'images/Profile_Pic_James.gif">
							<p class="jobtitle">Content Developer</p>
							<span class="phone">+27 11 717 7169</span><br>
							<span class="email">james.smurthwaite@wits.ac.za</span><br>			
							</a>
						</li>
						<li>
							<a name="modal" href="#dialog8">
							<h3 class="name">Ofentse Tabane</h3>
							<img width="75" height="90" alt="Ofentse Tabane" class="left" src="'.$this->skinpath.'images/otabane.jpg">
							<p>Assistant Instructional Designer</p>
							<span class="phone">+ 27 11 717 7172</span><br>
							<span class="email">ofentse.tabane@wits.ac.za</span><br>			
							</a>		
						</li>	
						<li>
							<a name="modal" href="#dialog9">
							<h3 class="name">David Wafula </h3>
							<img width="75" height="90" class="left" alt="" src="'.$this->skinpath.'images/DavidWafula.jpg">
							<p class="jobtitle">Team Leader Software Development</p>
							<span class="phone">+ 27 11 717 7180</span><br>
							<span class="email">david.wafula@wits.ac.za</span><br>	
							</a>
						</li>	
					</ul>
					</div>
					</div>
					<!-- end .grid_3 -->';
		
		return $retstr; 
	 }
	 
	 public function showContactMain() {
            $topics = array(
                array('text'=>'I\'d like to make ...'),
                array('value'=>'General', 'text'=>'A general enquiry '),
                array('value'=>'Admissions', 'text'=>'An admissions enquiry'),
                array('value'=>'Finance', 'text'=>'A financial enquiry'),
                array('value'=>'Other', 'text'=>'An enquiry about another matter')
            );

             $retstr = '<div class="grid_3">
				   <h4>Fill in the form</h4>
					
				   <form onsubmit="return ContactDetails_Field_Validator(this)" id="loginform" name="loginform" method="POST" action="./?sub=process">
					<fieldset id="topdialogue">
					<legend><span>'.$this->objLanguage->languagetext('mod_elsiskin_contactdetailsspan','elsiskin').'</legend>
					<label>Subject</label> 
					<select size="1" name="c_topic">';

                                        foreach($topics as $row) {
                                            $retstr .= '
                                                <option value="'.$row['value'].'">'.$row['text'].'</option>';
                                        }
                                        
                                        $retstr .= '
                                            </select>
					<br>';
                                        $myLabel = new label($this->objLanguage->languageText('mod_elsiskin_namelabel','elsiskin'));
					
                                        $retstr .= $myLabel->show().'
					<input type="text" name="c_name" maxlength="256" size="35">
					<br>
					<label>Email</label>
					<input type="text" name="c_email" maxlength="256" size="35">
					<br>
					<label>Your Comment:</label>
					<textarea cols="45" rows="7" name="c_message"></textarea>
					<br>
					<input type="submit" value="Send">
					</fieldset>
				  </form>
	  			  </div>';
  
  		return $retstr; 
	 }
}