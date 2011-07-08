<?php
/**
 * Artdir UI elements file.
 *
 * This file controls the artdir UI elements.
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
 * @version    $Id: blogui_class_inc.php 20147 2010-12-31 12:30:20Z dkeats $
 * @package    artdir
 * @subpackage artdirui
 * @author     Paul Scott <pscott@uwc.ac.za>
 * @copyright  2006-2007 AVOIR
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @link       http://avoir.uwc.ac.za
 * @see        References to other sections (if any)...
 */
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
 * class to control artdir ui elements
 *
 * This class controls the artdir UI elements. 
 *
 * @category  Chisimba
 * @package   artdir
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2006-2007 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: $
 * @link      http://avoir.uwc.ac.za
 * @see       References to other sections (if any)...
 */
class artdirui extends object
{
    
    /**
     * Blog categories object
     *
     * @var    object
     * @access public
     */
    public $objArtdirCategories;
    
    /**
     * left Column layout
     *
     * @var    object
     * @access public
     */
    public $leftCol;
    
    /**
     * Right column layout
     *
     * @var    object
     * @access public
     */
    public $rightCol;
    
    /**
     * middle column layout
     *
     * @var    object
     * @access public
     */
    public $middleCol;
    
    /**
     * Template header
     *
     * @var    object
     * @access public
     */
    public $tplHeader;
    
    /**
     * CSS Layout
     *
     * @var    object
     * @access public
     */
    public $cssLayout;
    
    /**
     * Left user menu
     *
     * @var    object
     * @access public
     */
    public $leftMenu;
    
    /**
     * user object
     *
     * @var    object
     * @access public
     */
    public $objUser;
    
    /**
     * Standard init function
     *
     * Initialises and constructs the object via the framework
     *
     * @return void
     * @access public
     */
    public function init()
    {
        $this->objArtdirCategories = $this->getObject('artdircategories');
        // user class
        $this->objUser = $this->getObject('user', 'security');
        // load up the htmlelements
        $this->loadClass('href', 'htmlelements');
        // get the css layout object
        $this->cssLayout = $this->newObject('csslayout', 'htmlelements');
        // get the sidebar object
        $this->leftMenu = $this->newObject('usermenu', 'toolbar');
        // initialise the columns
        // left column
        $this->leftCol = NULL;
        // right column
        $this->rightCol = NULL;
        // middle column
        $this->middleCol = NULL;
        $this->objConfig = $this->getObject('altconfig', 'config');
        $this->objUser = $this->getObject('user', 'security');
        $this->objLanguage = $this->getObject('language', 'language');
    }
    
    /**
     * three col layout
     *
     * Creates a 3 column css layout
     *
     * @return object CSS layout template header
     * @access public
     */
    public function threeCols()
    {
        // Set columns to 3
        $this->cssLayout->setNumColumns(3);
        $this->tplHeader = $this->cssLayout;
        return $this->tplHeader;
    }
    
    /**
     * Left blocks
     *
     * Blocks that will show up in the left hand column
     *
     * @param integer $userid The User id
     * @param string  $cats   The categories menu
     *
     * @return string  Return string
     * @access public
     */
    public function leftBlocks($userid = NULL, $cats = NULL)
    {
        $leftCol = "left";
        return NULL; 
    }
    
    /**
     * Right side blocks
     *
     * CSS layout for the right hand side blocks
     *
     * @param integer $userid The user id
     * @param string  $cats   categories
     *
     * @return string  string of blocks
     * @access public
     */
    public function rightBlocks($userid = NULL, $cats = NULL)
    {
        $rightCol = "right";
        return $rightCol;
    }
    
    public function slider() {
        $html = '<div class="main_view">
                     <div class="window">
                         <div class="image_reel">
                             <a href="#"><img src="http://placekitten.com/716/394" alt="blerk" /></a>
                             <a href="#"><img src="http://placekitten.com/716/394" alt="" /></a>
                             <a href="#"><img src="http://placekitten.com/716/394" alt="" /></a>
                             <a href="#"><img src="http://placekitten.com/716/394" alt="" /></a>
                         </div>
                     </div>
                     <div class="paging">
                         <a href="#" rel="1">1</a>
                         <a href="#" rel="2">2</a>
                         <a href="#" rel="3">3</a>
                         <a href="#" rel="4">4</a>
                     </div>
                 </div>';
        
        $js = '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.min.js"></script>';
        $js .= '<script type="text/javascript">
                $(document).ready(function() {
	                //Code goes here
	                //Show the paging and activate its first link
                    $(".paging").show(); 
                    $(".paging a:first").addClass("active");

                    //Get size of the image, how many images there are, then determin the size of the image reel.
                    var imageWidth = $(".window").width();
                    var imageSum = $(".image_reel img").size(); 
                    var imageReelWidth = imageWidth * imageSum;

                    //Adjust the image reel to its new size
                    $(".image_reel").css({\'width\' : imageReelWidth});
                    //Paging  and Slider Function
                    rotate = function(){
                    var triggerID = $active.attr("rel") - 1; //Get number of times to slide
                    var image_reelPosition = triggerID * imageWidth; //Determines the distance the image reel needs to slide

                    $(".paging a").removeClass(\'active\'); //Remove all active class
                    $active.addClass(\'active\'); //Add active class (the $active is declared in the rotateSwitch function)

                    //Slider Animation
                    $(".image_reel").animate({
                        left: -image_reelPosition
                    }, 500 );

                    }; 

                    //Rotation  and Timing Event
                    rotateSwitch = function(){
                        play = setInterval(function(){ //Set timer - this will repeat itself every 7 seconds
                        $active = $(\'.paging a.active\').next(); //Move to the next paging
                        if ( $active.length === 0) { //If paging reaches the end...
                            $active = $(\'.paging a:first\'); //go back to first
                        }
                        rotate(); //Trigger the paging and slider function
                        }, 7000); //Timer speed in milliseconds (7 seconds)
                    };

                    rotateSwitch(); //Run function on launch
                    
                    //On Hover
                    $(".image_reel a").hover(function() {
                        clearInterval(play); //Stop the rotation
                    }, function() {
                        rotateSwitch(); //Resume rotation timer
                    });	

                    //On Click
                    $(".paging a").click(function() {
                        $active = $(this); //Activate the clicked paging
                        //Reset Timer
                        clearInterval(play); //Stop the rotation
                        rotate(); //Trigger rotation immediately
                        rotateSwitch(); // Resume rotation timer
                        return false; //Prevent browser jump to link anchor
                     });
                });</script>';
                
                return $js.$html;
    }
    
    public function directorySearch($compact = TRUE) {
        // Load the form building classes.
        $this->loadClass('label', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('form','htmlelements');
        $this->loadClass('button','htmlelements');
        
        $slabel = new label($this->objLanguage->languageText('mod_artdir_dirsearch', 'search', 'Directory Search') .':', 'input_search');
        $sform = new form('query', $this->uri(array('action' => 'search'),'artdir'));
        //$sform->addRule('searchterm', $this->objLanguage->languageText("mod_blog_phrase_searchtermreq", "blog") , 'required');
        $query = new textinput('search');
        $query->size = 1;
        $objSButton = new button($this->objLanguage->languageText('word_go', 'system'));
        // Add the search icon
        $objSButton->setIconClass("search");
        //$this->objSButton->setValue($this->objLanguage->languageText('mod_skin_find', 'skin'));
        $objSButton->setValue('Find');
        $objSButton->setToSubmit();
        if ($compact) {
            $sform->addToForm($query->show()." ".$objSButton->show());
        } else {
            $sform->addToForm($slabel->show().' '.$query->show().' '.$objSButton->show());
        }
        $sform = '<div id="dirsearch">'.$sform->show().'</div>';
        return $sform;
    }
    
    public function signinBox() {
        $ret = $this->showSignInBox();
        $ret .= $this->showSignUpBox();
        return $ret;
    }
    
    /**
     * Sign in block
     *
     * Used in conjunction with the welcome block as a alertbox link. The sign in simply displays the block to sign in to Chisimba
     *
     * @return string
     */
    public function showSignInBox() {
        $objBlocks = $this->getObject('blocks', 'blocks');
        $objFeatureBox = $this->getObject('featurebox', 'navigation');
        return $objFeatureBox->show($this->objLanguage->languageText("mod_artdir_signin", "artdir"), $objBlocks->showBlock('login', 'security', 'none'));
    }

    /**
     * Sign up block
     *
     * Method to generate a sign up (register) block for the module. It uses a linked alertbox to format the response
     *
     * @return string
     */
    public function showSignUpBox() {
        $objBlocks = $this->getObject('blocks', 'blocks');
        $objFeatureBox = $this->getObject('featurebox', 'navigation');
        return $objFeatureBox->show($this->objLanguage->languageText("mod_artdir_signup", "artdir"), $objBlocks->showBlock('register', 'security', 'none'));
    }
    
}
?>
