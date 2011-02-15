<?php

/*
 *
 * A class to display the staff cards that pop up as modal windows when the staff
 * link is clicked on the staff page
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
 * @version   CVS: $Id: staffcards_class_inc.php,v 1.1 2007-11-25 09:13:27 nguni52 Exp $
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

class staffcards extends object {

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
     * @access public
     */
    public function setSkinPath($skinpath) {
        $this->skinpath = $skinpath;
    }

    /*
     * Method to show the staff cards modal windows
     * @access public
     * @return string $retstr containing the different modal window pop ups for the different
     * elsi staff
     */
    public function show() {
        /*$profiles = array(
                        array("href"=>"1", "name"=>"Taurai Hungwe", "image"=>"THungwe.jpg", "jobtitle"=>"Instructional Designer","ext"=>"7184", "email"=>"taurai.hungwe@wits.ac.za", "bio"=>"I am a career learner. Teaching and technology is my passion. My interest vary from blended learning, mobile technology, data mining and basketball.")
                    );
        $networks = array(
                        array("href"=>"1","network"=>"No Networks")
                    );
        $retstr = '
            <div id="boxes">';
        foreach($profile as $row) {
            $retstr .= '
		<!-- #customize your modal window here -->
		<div id="dialog'.$row['href'].'" class="window">
                    <a href="#" class="close">Close</a>
                    <div class="card_header">
                        <h1>'.$row['name'].'</h1>
                        <div class="cardnav">
                            <ul class="htabs">
                                <li><a href="#myprofile" class="current">About Me</a></li>
                                <li><a href="#mynetwork">My Networks</a></li>
                                <li> <a href="#contact">Contact Me</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="vcard">
			<div class="tabs">
				<div class="tab" id="myprofile">
					<div class="main_content">
                                            <h2>About <span class="fn">'.$row['name'].'</span></h2>
                                                <span class="jobtitle"><strong>'.$row['jobtitle'].'</strong>,&nbsp;</span>
                                                <span class="fn org"><span class="unit">eLearning Support & Innovation</span>,&nbsp;
                                                <span class="division">KIM</span></span>
                                                <p><img src="' . $this->skinpath . 'images/'.$row['image'].' class="left" alt="Taurai Hungwe"></p>
						<p class="bio">'.$row['bio'].'</p>
					</div>
				</div>
				<div style="display: none;" class="tab" id="mynetwork">
					<div id="profiles">
                                            <h2><span class="fn">Taurai Hungwe\'s</span> networks</h2>
                                            <div id="networkmedia">
                                              <ul>
                                                    <li>Taurai Hungwe has supplied no networks</li>
                                              </ul>
                                              <div class="clear"></div>
                                            </div>
					</div>
				</div>
				<div style="display: none;" class="tab bmod" id="contact">
					<div class="adr">  <h2>Contact <span class="fn">'.$row['name'].'</span></h2>
						<ul>
                                                    <li class="street-address">Wits Private Bag X3</li>
                                                    <li><span class="locality"> Johannesburg </span>  <span class="postal-code"> 2050 </span></li>

                                                    <li class="country-name">South Africa</li>
						</ul>
					</div>
					<div class="telecommunications">
						<p class="tel">Phone
                                                    <span class="tel">
                                                    <span class="type">Work</span>: <span class="value">+27 11 717 '.$row['ext'].'</span>
                                                     <span class="type">Home</span>: <span class="value">+27 84 967 7358</span>
                                                    <span class="type">Cell</span>: <span class="value">+27 79 040 7170</span>
                                                    </span>
						</p>
						<p class="email">Email: <a class="value" href="mailto:'.$row['name'].'">'.$row['email'].'</a></p>
						<p class="skype">Skype: Taurai.Hungwe</p>
						<a href=""><img src="' . $this->skinpath . 'images/Icon_vCard.png" alt="download vcard icon" align="right"></a>
					</div>
				</div>
			</div>
		</div>';
        }

        $retstr .= '</div>';*/

        $retstr = '
		<!-- #dialog is the id of a DIV defined in the code below -->
		<div id="boxes">
			<!-- #customize your modal window here -->
		<div id="dialog1" class="window">
				<a href="#" class="close">Close</a>
				<div class="card_header">
					<h1>Taurai Hungwe</h1>
						<div class="cardnav">
						<ul class="htabs">
							<li><a href="#myprofile" class="current">About Me</a></li>
							<li><a href="#mynetwork">My Networks</a></li>
							<li> <a href="#contact">Contact Me</a></li>
						</ul>
						</div> 
				</div> 
		<div class="vcard">    
			<div class="tabs">
				<div class="tab" id="myprofile">
					<div class="main_content">
						<h2>About <span class="fn">Taurai Hungwe</span></h2>
							<span class="jobtitle"><strong>Instructional Designer</strong>,&nbsp;</span>
							<span class="fn org"><span class="unit">eLearning Support & Innovation</span>,&nbsp;
							<span class="division">KIM</span></span>
							<p><img src="' . $this->skinpath . 'images/staff/THungwe.jpg" class="left" alt="Taurai Hungwe"></p>
							<p class="bio">I am a career learner. Teaching and technology is my passion. My interest vary from blended learning, mobile technology, data mining and basketball.</p>
					</div>            
				</div>
				<div style="display: none;" class="tab" id="mynetwork">
					<div id="profiles">
					<h2><span class="fn">Taurai Hungwe\'s</span> networks</h2>
						<div id="networkmedia">
						  <ul>        
							<li>Taurai Hungwe has supplied no networks</li>
						  </ul>
						  <div class="clear"></div>
						</div>
					</div>
				</div>
				<div style="display: none;" class="tab bmod" id="contact">
					<div class="adr">  <h2>Contact <span class="fn">Taurai Hungwe</span></h2>
						<ul>
							<li class="street-address">Wits Private Bag X3</li>
							<li><span class="locality"> Johannesburg </span>  <span class="postal-code"> 2050 </span></li>
		
							<li class="country-name">South Africa</li>
						</ul>
					</div>
					<div class="telecommunications">
						<p class="tel">Phone 
							<span class="tel">
							<span class="type">Work</span>: <span class="value">+27 11 717 7184</span>
							 <span class="type">Home</span>: <span class="value">+27 84 967 7358</span>
							<span class="type">Cell</span>: <span class="value">+27 79 040 7170</span>
							</span>
						</p>
						<p class="email">Email: <a class="value" href="Taurai.Hungwe@wits.ac.za">Taurai.Hungwe@wits.ac.za</a></p>
						<p class="skype">Skype: Taurai.Hungwe</p>
						<a href=""><img src="' . $this->skinpath . 'images/Icon_vCard.png" alt="download vcard icon" align="right"></a>
					</div>
				</div>
			</div>
				</div>               
			</div>
			<div id="dialog2" class="window">
				<a href="#" class="close">Close</a>
				<div class="card_header">
					<h1>Rabelani Dagada</h1>
						<div class="cardnav">
						<ul class="htabs">
							<li><a href="#myprofile" class="current">About Me</a></li>
							<li><a href="#mynetwork">My Networks</a></li>
							<li> <a href="#contact">Contact Me</a></li>
						</ul>
						</div> 
				</div>
		<div class="vcard">    
			<div class="tabs">
				<div class="tab" id="myprofile">
					<div class="main_content">
						<h2>About <span class="fn">Rabelani Dagada</span></h2>
							<span class="jobtitle"><strong>eLearning Manager</strong>,&nbsp;</span>
							<span class="fn org"><span class="unit">eLearning Support & Innovation</span>,&nbsp;
							<span class="division">KIM</span></span>
							<p><img src="' . $this->skinpath . 'images/staff/RDagada.jpg" class="left" alt="Rabelani Dagada"></p>
							<p class="bio"></p>
					</div>            
				</div>
				<div style="display: none;" class="tab" id="mynetwork">
					<div id="profiles">
					<h2><span class="fn">Rabelani.Dagada\'s</span> networks</h2>
						<div id="networkmedia">
				  		  <ul>        
							<li><a href="http://www.facebook.com/pages/Rabelani-Dagada-Author-and-Intellectual/127030800684042"><img src="' . $this->skinpath . 'images/profiles/facebook.png"><strong>Facebook</strong>facebook.com</a></li>
							<li><a href="http://twitter.com/#!/Rabelani_Dagada"><img src="' . $this->skinpath . 'images/profiles/twitter.png"><strong>Twitter</strong>twitter.com</a></li>
                                                        <li><a href="http://za.linkedin.com/pub/rabelani-dagada/13/443/28"><img src="' . $this->skinpath . 'images/profiles/linkedin.png"><strong>LinkedIn</strong>linkedin.com</a></li>
							<li><a href="http://www.amazon.com/Rabelani-Dagada/e/B004AOEDQY/ref=sr_tc_2_rm?qid=1297599658&sr=1-2-ent"><img src="' . $this->skinpath . 'images/profiles/amazon.png"><strong>Amazon</strong>amazon.com</a></li>
                                                        <li><a href="http://www.slideshare.net/RabelaniDagada/presentations"><img src="' . $this->skinpath . 'images/profiles/slideshare.png"><strong>Slideshare</strong>slideshare.com</a></li>
						  </ul>
						  <div class="clear"></div>
						</div>
					</div>
				</div>
				<div style="display: none;" class="tab bmod" id="contact">
					<div class="adr">  <h2>Contact <span class="fn">Rabelani Dagada</span></h2>
						<ul>
							<li class="street-address">Wits Private Bag X3</li>
							<li><span class="locality"> Johannesburg </span>  <span class="postal-code"> 2050 </span></li>
							<li class="country-name">South Africa</li>
						</ul>
					</div>
					<div class="telecommunications">
						<p class="tel">Phone 
							<span class="tel">
							<span class="type">Work</span>: 011 717 7162<span class="value"></span>
							 <span class="type">Home</span>: <span class="value"></span>
							<span class="type">Cell</span>: <span class="value"></span>
							</span>
						</p>
						<p class="email">Email: <a class="value" href="Rabelani.Dagada@wits.ac.za">Rabelani.Dagada@wits.ac.za</a></p>
							<a href="">
								<img src="' . $this->skinpath . 'images/Icon_vCard.png" alt="download vcard icon" align="right">
							</a>
					</div>
				</div>
			</div>
				</div>                
			</div>
			<div id="dialog3" class="window">
				<a href="#" class="close">Close</a>
				<div class="card_header">
					<h1>Nkululeko Phakela</h1>
						<div class="cardnav">
						<ul class="htabs">
							<li><a href="#myprofile" class="current">About Me</a></li>
							<li><a href="#mynetwork">My Networks</a></li>
							<li> <a href="#contact">Contact Me</a></li>
		
						</ul>
						</div> 
				</div> 
		<div class="vcard">    
			<div class="tabs">
				<div class="tab" id="myprofile">
					<div class="main_content">
						<h2>About <span class="fn">Nkululeko Phakela</span></h2>
						
						 
							<span class="jobtitle"><strong>Software Developer</strong>,&nbsp;</span>
		
							<span class="fn org"><span class="unit">eLearning Support & Innovation</span>,&nbsp;
							<span class="division">KIM</span></span>
							<p><img src="' . $this->skinpath . 'images/staff/NkululekoPhakela.jpg" class="left" alt="Nkululeko Phakela"></p>
					  <p class="bio">Bio: Born and Bred in Lesotho. I have had the pleasures of being able to travel the world a bit, and Africa is where my heart is. Johannesburg provides for me the world class services that I have come to expect. I am enjoying my time here. Working for the ELSI unit at Wits University. 
		
					  </p>
							
			
			
			
					</div>            
				</div>
				<div style="display: none;" class="tab" id="mynetwork">
		
					<div id="profiles">
					<h2><span class="fn">Nkululeko Phakela\'s</span> networks</h2>
						<div id="networkmedia">
						  <ul>        
							<li><a href="http://www.facebook.com/nphakela"><img src="' . $this->skinpath . 'images/profiles/facebook.png"><strong>Facebook</strong>facebook.com</a></li>
							<li><a href="http://twitter.com/nguni52/"><img src="' . $this->skinpath . 'images/profiles/twitter.png"><strong>Twitter</strong>twitter.com</a></li>
                                                        <li><a href="http://www.delicious.com/nphakela/"><img src="' . $this->skinpath . 'images/profiles/delicious.png"><strong>Delicious</strong>delicious.com</a></li>
							<li><a href="http://www.flickr.com/photos/56799340@N08/"><img src="' . $this->skinpath . 'images/profiles/flickr.png"><strong>Flickr</strong>flickr.com</a></li>
                                                  </ul>
						  <div class="clear"></div>
						</div>
					</div>
				</div>
				<div style="display: none;" class="tab bmod" id="contact">
					<div class="adr">  <h2>Contact <span class="fn">Nkululeko Phakela</span></h2>
		
						<ul>
							<li class="street-address">Wits Private Bag X3</li>
							<li><span class="locality"> Johannesburg </span>  <span class="postal-code"> 2050 </span></li>
							<li class="country-name">South Africa</li>
						</ul>
		
					</div>
				
					<div class="telecommunications">
						<p class="tel">Phone 
							<span class="tel">
							<span class="type">Work</span>: <span class="value">+27 11 717 7182</span>
							 <span class="type">Home</span>: <span class="value"></span>
							<span class="type">Cell</span>: <span class="value">+27 78 029 0917</span>
		
							</span>
						</p>
						<p class="email">Email: <a class="value" href="nguni52@gmail.com">nguni52@gmail.com</a></p>
						<p class="Skype">Skype: nguni52</p>
						<p class="IM">IM (Google Talk): nguni52@gmail.com </p>
		
							<a href="">
		
								<img src="' . $this->skinpath . 'images/Icon_vCard.png" alt="download vcard icon" align="right">
							</a>
					</div>
				</div>
			</div>
			
				</div>        
				  
					 
			</div>    
		
			<div id="dialog4" class="window">
				<a href="#" class="close">Close</a>
		
					<div class="card_header">
					<h1>
					  Derek Moore
					</h1>
						<div class="cardnav">
						<ul class="htabs">
							<li><a href="#myprofile" class="current">About Me</a></li>
							<li><a href="#mynetwork">My Networks</a></li>
		
							<li> <a href="#contact">Contact Me</a></li>
						</ul>
						</div> 
				</div>
			
			<div class="vcard">    
			<div class="tabs">
				<div class="tab" id="myprofile">
					<div class="main_content">
						<h2>About <span class="fn">Derek Moore</span></h2>
		
						
						 
							<span class="jobtitle"><strong>Content Developer</strong>,&nbsp;</span>
							<span class="fn org"><span class="unit">eLearning Support & Innovation</span>,&nbsp;
							<span class="division">KIM</span></span>
							<p><img src="' . $this->skinpath . 'images/staff/dmoore.jpg" class="left" alt="Derek Moore"></p>
							<p class="bio">Teacher, learning designer & aspiring heutagogist exploring the wild & wooly frontiers of electronic education.
							Working for the ELSI unit at Wits University.
							</p>
		
							
			
			
			
					</div>            
				</div>
				<div style="display: none;" class="tab" id="mynetwork">
					<div id="profiles">
					<h2><span class="fn">Derek Moore\'s</span> networks</h2>
						<div id="networkmedia">
						  <ul>        
							<li><a href="http://twitter.com/weblearning/"><img src="' . $this->skinpath . 'images/profiles/twitter.png"><strong>Twitter</strong>twitter.com</a></li>
		
							<li><a href="http://www.facebook.com/weblearning"><img src="' . $this->skinpath . 'images/profiles/facebook.png"><strong>Facebook</strong>facebook.com</a></li>
							<li><a href="http://za.linkedin.com/in/weblearning"><img src="' . $this->skinpath . 'images/profiles/linkedin.png"><strong>LinkedIn</strong>linkedin.com</a></li>
							<li><a href="http://www.delicious.com/weblearning/"><img src="' . $this->skinpath . 'images/profiles/delicious.png"><strong>Delicious</strong>delicious.com</a></li>
							<li><a href="http://www.flickr.com/photos/weblearning"><img src="' . $this->skinpath . 'images/profiles/flickr.png"><strong>Flickr</strong>flickr.com</a></li>
							<li><a href="http://www.weblearning.co.za/blog/"><img src="' . $this->skinpath . 'images/profiles/wordpress.png"><strong>Wordpress</strong>My Blog</a></li>
		
						  </ul>
						  <div class="clear"></div>
						</div>
					</div>
				</div>
				<div style="display: none;" class="tab bmod" id="contact">
					<div class="adr">  <h2>Contact <span class="fn">Derek Moore</span></h2>
		
						<ul>
							<li class="street-address">Wits Private Bag X3</li>
							<li><span class="locality"> Johannesburg </span>  <span class="postal-code"> 2050 </span></li>
							<li class="country-name">South Africa</li>
						</ul>
		
					</div>
				
					<div class="telecommunications">
						<p class="tel">Phone 
							<span class="tel">
							<span class="type">Work</span>: <span class="value">+27 11 717 7171</span>
							<span class="type">Cell</span>: <span class="value">+27 72 776 1001</span>
		
							</span>
						</p>
						<p class="email">Email: <a class="value" href="derek.moore@wits.ac.za">derek.moore@wits.ac.za</a></p>
							<a href="">
								<img src="' . $this->skinpath . 'images/Icon_vCard.png" alt="download vcard icon" align="right">
							</a>
					</div>
				</div>
		
			</div>
			
				</div>
			 </div>
			<div id="dialog5" class="window">
				<a href="#" class="close">Close</a>
				<div class="card_header">
					<h1>
					  James Smurthwaite
					</h1>
		
						<div class="cardnav">
						<ul class="htabs">
							<li><a href="#myprofile" class="current">About Me</a></li>
							<li><a href="#mynetwork">My Networks</a></li>
							<li> <a href="#contact">Contact Me</a></li>
						</ul>
						</div> 
				</div>   
		
		<div class="vcard">    
			<div class="tabs">
				<div class="tab" id="myprofile">
					<div class="main_content">
						<h2>About <span class="fn">James Smurthwaite</span></h2>
						
						 
							<span class="jobtitle"><strong>Content Developer</strong>,&nbsp;</span>
							<span class="fn org"><span class="unit">eLearning Support & Innovation</span>,&nbsp;
		
							<span class="division">KIM</span></span>
							<p><img src="' . $this->skinpath . 'images/staff/Profile_Pic_James.gif" class="left" alt="James Smurthwaite"></p>
							<p class="bio">A background in journalism, media studies, communication and graphic design, with a keen interest in harnessing technology for creating and communicating information rich content.
							</p>
							
			
			
			
					</div>            
				</div>
				<div style="display: none;" class="tab" id="mynetwork">
					<div id="profiles">
					<h2><span class="fn">James Smurthwaite\'s</span> networks</h2>
		
						<div id="networkmedia">
						  <ul>        
							<li><a href="http://twitter.com/JimmyNts/"><img src="' . $this->skinpath . 'images/profiles/twitter.png"><strong>Twitter</strong>twitter.com</a></li>
						  </ul>
						  <div class="clear"></div>
						</div>
					</div>
				</div>
		
				<div style="display: none;" class="tab bmod" id="contact">
					<div class="adr">  <h2>Contact <span class="fn">James Smurthwaite</span></h2>
						<ul>
							<li class="street-address">Wits Private Bag X3</li>
							<li><span class="locality"> Johannesburg </span>  <span class="postal-code"> 2050 </span></li>
		
							<li class="country-name">South Africa</li>
						</ul>
					</div>
				
					<div class="telecommunications">
						<p class="tel">Phone 
							<span class="tel">
							<span class="type">Work</span>: <span class="value">+27 11 717 7169</span>
		
							</span>
		
		
						</p>
						<p class="email">Email: <a class="value" href="james.smurthwaite@wits.ac.za">james.smurthwaite@wits.ac.za</a></p>
						<p class="skype">Skype: jimmy.smurthwaite</p>
							<a href="">
								<img src="' . $this->skinpath . 'images/Icon_vCard.png" alt="download vcard icon" align="right">
		
							</a>
					</div>
				</div>
			</div>
			
				</div>             
			</div>  
			<div id="dialog6" class="window">
				<a href="#" class="close">Close</a>
				<div class="card_header">
		
					<h1>
					  Shakira Choonara
					</h1>
						<div class="cardnav">
						<ul class="htabs">
							<li><a href="#myprofile" class="current">About Me</a></li>
							<li><a href="#mynetwork">My Networks</a></li>
							<li> <a href="#contact">Contact Me</a></li>
		
						</ul>
						</div> 
				</div> 
		<div class="vcard">    
			<div class="tabs">
				<div class="tab" id="myprofile">
					<div class="main_content">
						<h2>About <span class="fn">Shakira Choonara</span></h2>
						
						 
							<span class="jobtitle"><strong>Office Administrator</strong>,&nbsp;</span>
		
							<span class="fn org"><span class="unit">eLearning Support & Innovation</span>,&nbsp;
							<span class="division">KIM</span></span>
							<p><img src="' . $this->skinpath . 'images/staff/schoonara.jpg" class="left" alt="Shakira Choonara"></p>
					  <p class="bio">
					  </p>
							
			
			
			
					</div>            
				</div>
				<div style="display: none;" class="tab" id="mynetwork">
		
					<div id="profiles">
					<h2><span class="fn">Shakira Choonara\'s</span> networks</h2>
						<div id="networkmedia">
						  <ul>        
							<li>No networks supplied</li>
						  </ul>
						  <div class="clear"></div>
		
						</div>
					</div>
				</div>
				<div style="display: none;" class="tab bmod" id="contact">
					<div class="adr">  <h2>Contact <span class="fn">Shakira.Choonara</span></h2>
						<ul>
							<li class="street-address">Wits Private Bag X3</li>
		
							<li><span class="locality"> Johannesburg </span>  <span class="postal-code"> 2050 </span></li>
							<li class="country-name">South Africa</li>
						</ul>
					</div>
				
					<div class="telecommunications">
						<p class="tel">Phone 
							<span class="tel">
		
							<span class="type">Work</span>: <span class="value">+27 11 717 7161</span>
							</span>
		
		
						</p>
						<p class="email">Email: <a class="value" href="Shakira.Choonara2@wits.ac.za">Shakira.Choonara2@wits.ac.za</a></p>
											<a href="">
		
								<img src="' . $this->skinpath . 'images/Icon_vCard.png" alt="download vcard icon" align="right">
							</a>
					</div>
				</div>
			</div>
			
				</div>               
			</div> 
			<div id="dialog7" class="window">
				<a href="#" class="close">Close</a>
		
				<div class="card_header">
					<h1>
						Fatima Rahiman
					</h1>
						<div class="cardnav">
						<ul class="htabs">
							<li><a href="#myprofile" class="current">About Me</a></li>
							<li><a href="#mynetwork">My Networks</a></li>
		
							<li> <a href="#contact">Contact Me</a></li>
						</ul>
						</div> 
				</div>   
		<div class="vcard">    
			<div class="tabs">
				<div class="tab" id="myprofile">
					<div class="main_content">
						<h2>About <span class="fn">Fatima Rahiman</span></h2>
		
							<span class="jobtitle"><strong>Team Leader: Learning Design</strong>,&nbsp;</span>
							<span class="fn org"><span class="unit">eLearning Support & Innovation</span>,&nbsp;
							<span class="division">KIM</span></span>
							<p><img src="' . $this->skinpath . 'images/staff/frahiman.jpg" class="left" alt="Fatima Rahiman"></p>
							<p class="bio">Fatima\'s  oscillating career path has stabilized, laying her hat at her alma mater where she is presently  reading her Masters in Educational Technology by night,  whilst advocating online learning by day. 
		An innate curiosity about the machinations of a mind , a curiosity fueled by its betrayal in earlier academic years which left her a medical manqué , coupled with previous stints as an educator, citizen journalism portal manager, and as a project manager of a primary school broadcast channel, has endowed her with a keen propensity for Information Technology tools - an aid in her desire to assist in the augmentation of effective learning and teaching practices. 
		In addition, she has completed a certificate course in Instructional Design and Content writing, and has a BSc in Life Sciences as well as a  BA Hons in Journalism and Media studies.  </p>
				  </div>            
				</div>
		
				<div style="display: none;" class="tab" id="mynetwork">
					<div id="profiles">
					<h2><span class="fn">Fatima Rahiman\'s</span> networks</h2>
						<div id="networkmedia">
						  <ul>        
							<li><a href="http://twitter.com/fatima1507/"><img src="' . $this->skinpath . 'images/profiles/twitter.png"><strong>Twitter</strong>twitter.com</a></li>
						  </ul>
		
						  <div class="clear"></div>
						</div>
					</div>
				</div>
				<div style="display: none;" class="tab bmod" id="contact">
					<div class="adr">  <h2>Contact <span class="fn">Fatima Rahiman</span></h2>
						<ul>
		
							<li class="street-address">Wits Private Bag X3</li>
							<li><span class="locality"> Johannesburg </span>  <span class="postal-code"> 2050 </span></li>
							<li class="country-name">South Africa</li>
						</ul>
					</div>
		
				
					<div class="telecommunications">
						<p class="tel">Phone 
							<span class="tel">
							<span class="type">Work</span>: <span class="value">+ 27 11 717 7174</span>
							<span class="type">Cell</span>: <span class="value">+ 27 798 737 633</span>
							</span>
		
						</p>
						<p class="email">Email: <a class="value" href="Fatima.Rahiman@wits.ac.za">Fatima.Rahiman@wits.ac.za</a></p>
						 <p class="skype">Skype: fatimar1507</p>
		
							<a href="">
								<img src="' . $this->skinpath . 'images/Icon_vCard.png" alt="download vcard icon" align="right">
							</a>
		
					</div>
				</div>
			</div>
			
				</div>             
			</div> 
			<div id="dialog8" class="window">
				<a href="#" class="close">Close</a>
				<div class="card_header">
					<h1>
		
						Ofentse Tabane
					</h1>
						<div class="cardnav">
						<ul class="htabs">
							<li><a href="#myprofile" class="current">About Me</a></li>
							<li><a href="#mynetwork">My Networks</a></li>
							<li> <a href="#contact">Contact Me</a></li>
		
						</ul>
						</div> 
				</div> 
		<div class="vcard">    
			<div class="tabs">
				<div class="tab" id="myprofile">
					<div class="main_content">
						<h2>About <span class="fn">Ofentse Tabane</span></h2>
						
						 
							<span class="jobtitle"><strong>Assistant Instructional Designer </strong>,&nbsp;</span>
		
							<span class="fn org"><span class="unit">eLearning Support & Innovation</span>,&nbsp;
							<span class="division">KIM</span></span>
							<p><img src="' . $this->skinpath . 'images/staff/otabane.jpg" alt="Ofentse Tabane" width="75" height="90" class="left"></p>
					  <p class="bio">On earth to acquire as much knowledge as my mind can absorb in any form
					  </p>
				  </div>            
				</div>
				<div style="display: none;" class="tab" id="mynetwork">
		
					<div id="profiles">
					<h2><span class="fn">Ofentse Tabane\'s</span> networks</h2>
						<div id="networkmedia">
						  <ul>        
							<li><a href="http://twitter.com/KeOfentse/"><img src="' . $this->skinpath . 'images/profiles/twitter.png"><strong>Twitter</strong>twitter.com</a></li>
                                                        <li><a href="http://www.linkedin.com/groupsDirectory?results=&sik=1297677498286&pplSearchOrigin=GLHD&keywords=Ofentse+Tabane"><img src="'.$this->skinpath . 'images/profiles/linkedin.png"<strong>LinkedIn</strong>linkedin.com</a></li>
                                                        <li><a href="http://www.flickr.com/photos/56709923@N05/"><img src="'.$this->skinpath .'images/profiles/flickr.png"><strong>Flickr</strong>flickr.com</a></li>
						  </ul>
						  <div class="clear"></div>
		
						</div>
					</div>
				</div>
				<div style="display: none;" class="tab bmod" id="contact">
					<div class="adr">  <h2>Contact <span class="fn">Ofentse Tabane</span></h2>
						<ul>
							<li class="street-address">Wits Private Bag X3</li>
		
							<li><span class="locality"> Johannesburg </span>  <span class="postal-code"> 2050 </span></li>
							<li class="country-name">South Africa</li>
						</ul>
					</div>
				
					<div class="telecommunications">
						<p class="tel">Phone 
							<span class="tel">
		
							<span class="type">Work</span>: <span class="value">+ 27 11 717 7172</span>
							<span class="type">Cell</span>: <span class="value">+ 27 822 542 427</span>
							</span>
		
						</p>
						<p class="email">Email: <a class="value" href="ofentse.tabane@wits.ac.za">ofentse.tabane@wits.ac.za</a></p>
		
						
		
							<a href="">
								<img src="' . $this->skinpath . 'images/Icon_vCard.png" alt="download vcard icon" align="right">
							</a>
					</div>
				</div>
			</div>
			
				</div>               
			</div>   
			<div id="dialog9" class="window">
				<a href="#" class="close">Close</a>
		
				<div class="card_header">
					<h1>
						David Wafula 
					</h1>
						<div class="cardnav">
						<ul class="htabs">
							<li><a href="#myprofile" class="current">About Me</a></li>
							<li><a href="#mynetwork">My Networks</a></li>
		
							<li> <a href="#contact">Contact Me</a></li>
						</ul>
						</div> 
				</div> 
		<div class="vcard">    
			<div class="tabs">
				<div class="tab" id="myprofile">
					<div class="main_content">
						<h2>About <span class="fn">David Wafula</span></h2>
		
						
						 
							<span class="jobtitle"><strong>Team Leader, Software Development.</strong>,&nbsp;</span>
							<span class="fn org"><span class="unit">eLearning Support & Innovation</span>,&nbsp;
							<span class="division">KIM</span></span>
							<p><img src="' . $this->skinpath . 'images/staff/DavidWafula.jpg" alt="Ofentse Tabane" width="100" height="120" class="left"></p>
					  <p class="bio">Specializing in Java, PHP, Realtime Communications Outside computer world: an avid runner
					  </p>
				  </div>            
				</div>
		
				<div style="display: none;" class="tab" id="mynetwork">
					<div id="profiles">
					<h2><span class="fn">David Wafula\'s</span> networks</h2>
						<div id="networkmedia">
						  <ul>        
							<li></li>
						  </ul>
						  <div class="clear"></div>
		
						</div>
					</div>
				</div>
				<div style="display: none;" class="tab bmod" id="contact">
					<div class="adr">  <h2>Contact <span class="fn">David Wafula</span></h2>
						<ul>
							<li class="street-address">Wits Private Bag X3</li>
		
							<li><span class="locality"> Johannesburg </span>  <span class="postal-code"> 2050 </span></li>
							<li class="country-name">South Africa</li>
						</ul>
					</div>
				
					<div class="telecommunications">
						<p class="tel">Phone 
							<span class="tel">
		
							<span class="type">Work</span>: <span class="value">+ 27 11 717 7180</span>
							</span>
		
						</p>
						<p class="email">Email: <a class="value" href="david.wafula@wits.ac.za">david.wafula@wits.ac.za</a></p>
				  
							<a href="">
								<img src="' . $this->skinpath . 'images/Icon_vCard.png" alt="download vcard icon" align="right">
		
							</a>
					</div>
				</div>
			</div>
			
				</div>               
			</div> 
			<div id="dialog10" class="window">
				<a href="#" class="close">Close</a>
				<div class="card_header">
		
					<h1>
						Tessa Murray 
					</h1>
						<div class="cardnav">
						<ul class="htabs">
							<li><a href="#myprofile" class="current">About Me</a></li>
							<li><a href="#mynetwork">My Networks</a></li>
							<li> <a href="#contact">Contact Me</a></li>
		
						</ul>
						</div> 
				</div>        
			</div>    
			<div id="dialog11" class="window">
				<a href="#" class="close">Close</a>
				<div class="card_header">
					<h1>
						Agnes Chigona 
					</h1>
		
						<div class="cardnav">
						<ul class="htabs">
							<li><a href="#myprofile" class="current">About Me</a></li>
							<li><a href="#mynetwork">My Networks</a></li>
							<li> <a href="#contact">Contact Me</a></li>
						</ul>
						</div> 
				</div>   
		
		<div class="vcard">    
			<div class="tabs">
				<div class="tab" id="myprofile">
					<div class="main_content">
						<h2>About <span class="fn">Agnes Chigona</span></h2>
						
						 
							<span class="jobtitle"><strong>Research Fellow</strong>,&nbsp;</span>
							<span class="fn org"><span class="unit">eLearning Support & Innovation</span>,&nbsp;
		
							<span class="division">KIM</span></span>
							<p><img src="' . $this->skinpath . 'images/staff/AgnesChigona.jpg" class="left" alt="Agnes Chigona"></p>
							<p class="bio">I have studied in different countries including South Africa (PhD.); Germany (MA); New Zealand (Graduate Dipl. in Women & Gender Studies); Malawi (B.Ed.). 
				  Research interests: ICTs and Education; African Women and ICTs 
		
		
							</p>
					 </div>            
				</div>
				<div style="display: none;" class="tab" id="mynetwork">
					<div id="profiles">
					<h2><span class="fn">Agnes Chigona\'s</span> networks</h2>
		
						<div id="networkmedia">
						  <ul>        
							<li>Mixit</li>
						  </ul>
						  <div class="clear"></div>
						</div>
					</div>
				</div>
				<div style="display: none;" class="tab bmod" id="contact">
		
					<div class="adr">  <h2>Contact <span class="fn">Agnes Chigona</span></h2>
						<ul>
							<li class="street-address">Wits Private Bag X3</li>
							<li><span class="locality"> Johannesburg </span>  <span class="postal-code"> 2050 </span></li>
		
							<li class="country-name">South Africa</li>
						</ul>
					</div>
				
					<div class="telecommunications">
						<p class="tel">Phone 
							<span class="tel">
							<span class="type">Work</span>: <span class="value">+ 27 11 717 7181</span>
		
							 <span class="type">Home</span>: <span class="value"></span>
							<span class="type">Cell</span>: <span class="value">+ 27 832 711 810</span>
							</span>
		
		
						</p>
						<p class="email">Email: <a class="value" href="agnes.chigona@wits.ac.za">agnes.chigona@wits.ac.za</a></p>
		
						 <p class="skype">Skype: Agnes.Chigona</p>
		
							<a href="">
								<img src="' . $this->skinpath . 'images/Icon_vCard.png" alt="download vcard icon" align="right">
							</a>
					</div>
				</div>
			</div>
			
				</div>             
			</div>
		
			<div id="dialog12" class="window">
				<a href="#" class="close">Close</a>
				<div class="card_header">
					<h1>
						Neo Petlele
					</h1>
						<div class="cardnav">
						<ul class="htabs">
							<li><a href="#myprofile" class="current">About Me</a></li>
		
							<li><a href="#mynetwork">My Networks</a></li>
							<li> <a href="#contact">Contact Me</a></li>
						</ul>
						</div> 
				</div>   
		<div class="vcard">    
			<div class="tabs">
				<div class="tab" id="myprofile">
					<div class="main_content">
		
						<h2>About <span class="fn">Neo Petlele</span></h2>
						
						 
							<span class="jobtitle"><strong>Research Assistant</strong>,&nbsp;</span>
							<span class="fn org"><span class="unit">eLearning Support & Innovation</span>,&nbsp;
							<span class="division">KIM</span></span>
							<p><img src="' . $this->skinpath . 'images/staff/Neo.Petlele.jpg" class="left" alt="Neo Petlele"></p>
							<p class="bio">Research Assistant for the eLSI unit. Currently serving on the ZAW 2011 Conference Organising Committee as well as the Wits LMS evaluation Working Group Committee.
		
		
							</p>
		
					 </div>            
				</div>
				<div style="display: none;" class="tab" id="mynetwork">
					<div id="profiles">
					<h2><span class="fn">Neo Petlele\'s</span> networks</h2>
						<div id="networkmedia">
						  <ul> 
							<li><a href="http://za.linkedin.com/pub/neo-petlele/b/7b9/61b"><img src="' . $this->skinpath . 'images/profiles/linkedin.png"><strong>LinkIn</strong>linkin.com</a></li>
							<li><a href="http://twitter.com/newis/"><img src="' . $this->skinpath . 'images/profiles/twitter.png"><strong>Twitter</strong>twitter.com</a></li>
		
							<li><a href="http://www.facebook.com/Neo%20Petlele#!/profile.php?id=730956553"><img src="' . $this->skinpath . 'images/profiles/twitter.png"><strong>Facebook</strong>facebook.com</a></li>
						  </ul>
						  <div class="clear"></div>
						</div>
					</div>
				</div>
				<div style="display: none;" class="tab bmod" id="contact">
					<div class="adr">  <h2>Contact <span class="fn">Neo Petlele</span></h2>
		
						<ul>
							<li class="street-address">Wits Private Bag X3</li>
							<li><span class="locality"> Johannesburg </span>  <span class="postal-code"> 2050 </span></li>
							<li class="country-name">South Africa</li>
						</ul>
		
					</div>
				
					<div class="telecommunications">
						<p class="tel">Phone 
							<span class="tel">
							<span class="type">Work</span>: <span class="value">+ 27 11 717 7183</span>
							</span>
						</p>
						<p class="email">Email: <a class="value" href="Neo.Petlele@wits.ac.za">Neo.Petlele@wits.ac.za</a></p>
		
						<span class="type">Cell</span>: <span class="value">+ 27 8 2533914</span>
		
							<a href="">
								<img src="' . $this->skinpath . 'images/Icon_vCard.png" alt="download vcard icon" align="right">
							</a>
					</div>
				</div>
			</div>
		
			
				</div>             
			</div>  
			
			<div id="dialog13" class="window">
				<a href="#" class="close">Close</a>
				<div class="card_header">
					<h1>
						Paul Mungai  
					</h1>
						<div class="cardnav">
						<ul class="htabs">
		
							<li><a href="#myprofile" class="current">About Me</a></li>
							<li><a href="#mynetwork">My Networks</a></li>
							<li> <a href="#contact">Contact Me</a></li>
						</ul>
						</div> 
				</div>  
		<div class="vcard">    
			<div class="tabs">
		
				<div class="tab" id="myprofile">
					<div class="main_content">
						<h2>About <span class="fn">Paul Mungai</span></h2>
						
						 
							<span class="jobtitle"><strong>Instructional Developer</strong>,&nbsp;</span>
							<span class="fn org"><span class="unit">eLearning Support & Innovation</span>,&nbsp;
		
							<span class="division">KIM</span></span>
							<p><img src="' . $this->skinpath . 'images/staff/PaulMungai.jpg" class="left" alt="Paul Mungai"></p>
							<p class="bio">I specialize in building simple tools that can assist in teaching and learning.
							</p>
				  </div>            
				</div>
				<div style="display: none;" class="tab" id="mynetwork">
					<div id="profiles">
					<h2><span class="fn">Paul Mungai\'s</span> networks</h2>
		
						<div id="networkmedia">
						  <ul>   
							<li><a href="http://www.facebook.com/#!/paulwando "><img src="' . $this->skinpath . 'images/profiles/facebook.png"><strong>Facebook</strong>facebook.com</a></li>
							<li><a href="http://twitter.com/pwando/"><img src="' . $this->skinpath . 'images/profiles/twitter.png"><strong>Twitter</strong>twitter.com</a></li>
							<li><a href="http://www.linkedin.com/profile/view?id=33772766"><img src="' . $this->skinpath . 'images/profiles/linkedin.png"><strong>LinkIn</strong>linkin.com</a></li>
							<li><a href="http://www.flickr.com/photos/52238056@N04/"><img src="' . $this->skinpath . 'images/profiles/flickr.png"><strong>Flickr</strong>flickr.com</a></li>                  </ul>
		
						  <div class="clear"></div>
						</div>
					</div>
				</div>
				<div style="display: none;" class="tab bmod" id="contact">
					<div class="adr">  <h2>Contact <span class="fn">Paul Mungai</span></h2>
						<ul>
		
							<li class="street-address">Wits Private Bag X3</li>
							<li><span class="locality"> Johannesburg </span>  <span class="postal-code"> 2050 </span></li>
							<li class="country-name">South Africa</li>
						</ul>
					</div>
		
				
					<div class="telecommunications">
						<p class="tel">Phone 
							<span class="tel">
							<span class="type">Work</span>: <span class="value">+27 11 717-77166</span>
							<span class="type">Cell</span>: <span class="value">+27 727 604 534</span>
							</span>
		
						</p>
						<p class="email">Email: <a class="value" href="paul.mungai@wits.ac.za">paul.mungai@wits.ac.za</a></p>
		
		
							<a href="">
								<img src="' . $this->skinpath . 'images/Icon_vCard.png" alt="download vcard icon" align="right">
							</a>
					</div>
				</div>
		
			</div>
			
				</div>              
			</div>
			
			
			<div id="dialog14" class="window">
				<a href="#" class="close">Close</a>
				<div class="card_header">
					<h1>
					   Reginald Moledi
					</h1>
		
						<div class="cardnav">
						<ul class="htabs">
							<li><a href="#myprofile" class="current">About Me</a></li>
							<li><a href="#mynetwork">My Networks</a></li>
							<li> <a href="#contact">Contact Me</a></li>
						</ul>
						</div> 
				</div> 
		
		<div class="vcard">    
			<div class="tabs">
				<div class="tab" id="myprofile">
					<div class="main_content">
						<h2>About <span class="fn">Reginald Moledi</span></h2>
						
						 
							<span class="jobtitle"><strong>Instructional Developer</strong>,&nbsp;</span>
							<span class="fn org"><span class="unit">eLearning Support & Innovation</span>,&nbsp;
		
							<span class="division">KIM</span></span>
							<p><img src="' . $this->skinpath . 'images/staff/regimoledi.jpg" class="left" alt="Reginald Moledi"></p>
							<p class="bio">Facilitator and trainer for lecturers and students in the use of LMS for teaching and learning.
		Certified Trainer(Blackboard), IT(UJ), Project Management(TSA).
							</p>
				  </div>            
				</div>
				<div style="display: none;" class="tab" id="mynetwork">
					<div id="profiles">
					<h2><span class="fn">Reginald Moledi\'s</span> networks</h2>
		
						<div id="networkmedia">
						  <ul>        
							<li><a href="http://www.facebook.com/#!/nmbana "><img src="' . $this->skinpath . 'images/profiles/facebook.png"><strong>Facebook</strong>facebook.com</a></li>
							<li><a href="http://twitter.com/Chosen_Ukwanda/"><img src="' . $this->skinpath . 'images/profiles/twitter.png"><strong>Twitter</strong>twitter.com</a></li>
							<li><a href="http://za.linkedin.com/pub/noxolo-mbana/a/281/283"><img src="' . $this->skinpath . 'images/profiles/linkedin.png"><strong>LinkIn</strong>linkin.com</a></li>
						  </ul>
		
						  <div class="clear"></div>
						</div>
					</div>
				</div>
				<div style="display: none;" class="tab bmod" id="contact">
					<div class="adr">  <h2>Contact <span class="fn">Reginald Moledi</span></h2>
						<ul>
		
							<li class="street-address">Wits Private Bag X3</li>
							<li><span class="locality"> Johannesburg </span>  <span class="postal-code"> 2050 </span></li>
							<li class="country-name">South Africa</li>
						</ul>
					</div>
		
				
					<div class="telecommunications">
						<p class="tel">Phone 
							<span class="tel">
							<span class="type">Work</span>: <span class="value">+ 27 11 717 7170</span>
							<span class="type">Cell</span>: <span class="value">+ 27 73 540 3683</span>
							</span>
		
						</p>
						<p class="email">Email: <a class="value" href="Reginald.moledi@wits.ac.za">Reginald.moledi@wits.ac.za</a></p>
		
		
							<a href="">
								<img src="' . $this->skinpath . 'images/Icon_vCard.png" alt="download vcard icon" align="right">
							</a>
					</div>
				</div>
		
			</div>
			
				</div>               
			</div> 
			
			  
												  
			<div id="dialog15" class="window">
				<a href="#" class="close">Close</a>
				<div class="card_header">
					<h1>
						Noxolo Kahlana-Mbana
					</h1>
						<div class="cardnav">
		
						<ul class="htabs">
							<li><a href="#myprofile" class="current">About Me</a></li>
							<li><a href="#mynetwork">My Networks</a></li>
							<li> <a href="#contact">Contact Me</a></li>
						</ul>
						</div> 
				</div> 
		
		<div class="vcard">    
			<div class="tabs">
				<div class="tab" id="myprofile">
					<div class="main_content">
						<h2>About <span class="fn">Noxolo Kahlana-Mbana</span></h2>
						
						 
							<span class="jobtitle"><strong>Researcher</strong>,&nbsp;</span>
							<span class="fn org"><span class="unit">eLearning Support & Innovation</span>,&nbsp;
		
							<span class="division">KIM</span></span>
							<p><img src="' . $this->skinpath . 'images/staff/noxolombana.jpg" class="left" alt="Noxolo Kahlana-Mbana"></p>
							<p class="bio">Community developer, perpetual student, transformation, change management and sustainability enthusiast, working on eLearning Research for eLSI unit at Wits.
		
		
							</p>
				  </div>            
				</div>
				<div style="display: none;" class="tab" id="mynetwork">
					<div id="profiles">
					<h2><span class="fn">Noxolo Kahlana-Mbana\'s</span> networks</h2>
		
						<div id="networkmedia">
						  <ul>        
							<li><a href="http://www.facebook.com/#!/nmbana "><img src="' . $this->skinpath . 'images/profiles/facebook.png"><strong>Facebook</strong>facebook.com</a></li>
							<li><a href="http://twitter.com/Chosen_Ukwanda/"><img src="' . $this->skinpath . 'images/profiles/twitter.png"><strong>Twitter</strong>twitter.com</a></li>
							<li><a href="http://za.linkedin.com/pub/noxolo-mbana/a/281/283"><img src="' . $this->skinpath . 'images/profiles/linkedin.png"><strong>LinkIn</strong>linkin.com</a></li>
						  </ul>
		
						  <div class="clear"></div>
						</div>
					</div>
				</div>
				<div style="display: none;" class="tab bmod" id="contact">
					<div class="adr">  <h2>Contact <span class="fn">Noxolo Kahlana-Mbana</span></h2>
						<ul>
		
							<li class="street-address">Wits Private Bag X3</li>
							<li><span class="locality"> Johannesburg </span>  <span class="postal-code"> 2050 </span></li>
							<li class="country-name">South Africa</li>
						</ul>
					</div>
		
				
					<div class="telecommunications">
						<p class="tel">Phone 
							<span class="tel">
							<span class="type">Work</span>: <span class="value">+ 27 11 717 7164</span>
							<span class="type">Cell</span>: <span class="value">+ 27 767 397 686</span>
							</span>
		
						</p>
						<p class="email">Email: <a class="value" href="noxolo.mbana@wits.ac.za">noxolo.mbana@wits.ac.za</a></p>
						 <p class="skype">Skype: nox7910</p>
		
							<a href="">
								<img src="' . $this->skinpath . 'images/Icon_vCard.png" alt="download vcard icon" align="right">
							</a>
					</div>
		
				</div>
			</div>
			
				</div>               
			</div>';
											   
			
	$retstr .='	<!-- Do not remove div#mask, because you\'ll need it to fill the whole screen -->
			<div id="mask"></div>';

        return $retstr;
    }

}