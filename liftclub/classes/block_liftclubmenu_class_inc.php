<?php

/**
 * liftclub blocks
 * 
 * Chisimba lift Club blocks class
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
 * @package   activitystreamer
 * @author    Paul Mungai <paulwando@gmail.com>
 * @copyright 2009 Paul Mungai
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @link      http://avoir.uwc.ac.za
 */


// security check - must be included in all scripts
if (!
/**
 * Description for $GLOBALS
 * @global entry point $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
// end security check


/**
 * Context blocks
 * 
 * Chisimba Lift Club Menu blocks class
 * 
 * @category  Chisimba
 * @package   liftclub
 * @author    Paul Mungai <paulwando@gmail.com>
 * @copyright 2009 Paul Mungai
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @link      http://avoir.uwc.ac.za
 */
class block_liftclubmenu extends object
{
    /**
    * @var string $title The title of the block
    */
    public $title;
    
    /**
    * @var object $objLanguage String to hold the language object
    */
    private $objLanguage;

    /**
    * Standard init function to instantiate language object
    * and create title, etc
    */
    public function init()
    {
        try {
            $this->objLanguage =  $this->getObject('language', 'language');
            $this->objUser =  $this->getObject('user', 'security');
            $this->objConfig =  $this->getObject('altconfig', 'config');
            //$this->objConfig->getSiteName()
            $this->title = ucwords($this->objLanguage->code2Txt('mod_liftclub_liftclubname', 'liftclub', NULL, 'Lift Club'));
            
            $this->loadClass('checkbox', 'htmlelements');
        } catch (customException $e) {
            customException::cleanUp();
        }
    }
    
    /**
    * Standard block show method. It uses the renderform
    * class to render the login box
    */
    public function show()
    {
							$this->loadclass('link','htmlelements');
							$objBlocks = $this->getObject('blocks', 'blocks');
							$cssLayout = $this->getObject('csslayout', 'htmlelements');

							$registerLink =new link($this->uri(array('action'=>'startregister')));
							$registerLink->link = $this->objLanguage->languageText("mod_liftclub_register","liftclub","Register");
							$registerLink->title = $this->objLanguage->languageText("mod_liftclub_register","liftclub","Register");

							$modifyLink =new link($this->uri(array('action'=>'modifydetails')));
							$modifyLink->link = $this->objLanguage->languageText("mod_liftclub_modifyregister","liftclub","Modify Registration");
							$modifyLink->title = $this->objLanguage->languageText("mod_liftclub_modifyregister","liftclub","Modify Registration");

							$findLink =new link($this->uri(array('action'=>'findlift')));
							$findLink->link = $this->objLanguage->languageText("mod_liftclub_liftneeded","liftclub","Lifts Needed");
							$findLink->title = $this->objLanguage->languageText("mod_liftclub_liftneeded","liftclub","Lifts Needed");

							$offerLink =new link($this->uri(array('action'=>'offeredlifts')));
							$offerLink->link = $this->objLanguage->languageText("mod_liftclub_liftonoffer","liftclub","Lifts On Offer");
							$offerLink->title = $this->objLanguage->languageText("mod_liftclub_liftonoffer","liftclub","Lifts On Offer");

							$favLink =new link($this->uri(array('action'=>'myfavourites')));
							$favLink->link = $this->objLanguage->languageText("mod_liftclub_myfavourites","liftclub","My Favourites");
							$favLink->title = $this->objLanguage->languageText("mod_liftclub_myfavourites","liftclub","My Favourites");

							$msgLink =new link($this->uri(array('action'=>'messages')));
							$msgLink->link = $this->objLanguage->languageText("mod_liftclub_receivedmessages","liftclub","Inbox");
							$msgLink->title = $this->objLanguage->languageText("mod_liftclub_receivedmessages","liftclub","Inbox");

							$msgOutLink =new link($this->uri(array('action'=>'outboxmessages')));
							$msgOutLink->link = $this->objLanguage->languageText("mod_liftclub_sentmessages","liftclub","Outbox");
							$msgOutLink->title = $this->objLanguage->languageText("mod_liftclub_sentmessages","liftclub","Outbox");

							$msgTrashLink =new link($this->uri(array('action'=>'trashedmessages')));
							$msgTrashLink->link = $this->objLanguage->languageText("mod_liftclub_trashedmessages","liftclub","Trash");
							$msgTrashLink->title = $this->objLanguage->languageText("mod_liftclub_trashedmessages","liftclub","Trash");

							$objFeatureBox = $this->newObject ( 'featurebox', 'navigation' );

							$pageLink = "<ul>";
							$mailFeatBox = "";
							if($this->objUser->userId()!==null){ 
								$pageLink .= "<li>&nbsp;&nbsp;&nbsp;".$offerLink->show()."</li>";
								$pageLink .= "<li>&nbsp;&nbsp;&nbsp;".$findLink->show()."</li>";
								$pageLink .= "<li>&nbsp;&nbsp;&nbsp;".$favLink->show()."</li>";
								$pageLink .= "<li>&nbsp;&nbsp;&nbsp;".$modifyLink->show()."</li>"; 
		
								$mailLink = "<ul>";
								$mailLink .= "<li>&nbsp;&nbsp;&nbsp;".$msgLink->show()."</li>";
								$mailLink .= "<li>&nbsp;&nbsp;&nbsp;".$msgOutLink->show()."</li>";
								$mailLink .= "<li>&nbsp;&nbsp;&nbsp;".$msgTrashLink->show()."</li>";
								$mailLink .= "</ul>";
								$mailfieldset = $this->newObject('fieldset', 'htmlelements');
								$mailfieldset->contents = $mailLink;
								$mailFeatBox = $objFeatureBox->show ($this->objLanguage->languageText("mod_liftclub_mailbox","liftclub","Mail Box"), $mailfieldset->show()."<br />","mailbox", $blockType = NULL, $titleLength = 20, $wrapStr = TRUE, $showToggle = TRUE, $hidden = 'default', $showTitle = TRUE, $cssClass = 'featurebox', $cssId = '');
								$mailFeatBox = $mailFeatBox."<br /><br /><br /><br />";
							}else{
								$pageLink .= "<li>&nbsp;&nbsp;&nbsp;".$registerLink->show()."</li>";
								$pageLink .= "<li>&nbsp;&nbsp;&nbsp;".$offerLink->show()."</li>";
								$pageLink .= "<li>&nbsp;&nbsp;&nbsp;".$findLink->show()."</li>";
							}
							$pageLink .= "</ul>";

							$fieldset = $this->newObject('fieldset', 'htmlelements');
							$fieldset->contents = $pageLink;
       $firstBox = $objFeatureBox->show ($this->objLanguage->languageText("mod_liftclub_liftclubname","liftclub","Lift Club"), $fieldset->show()."<br />","clubox", $blockType = NULL, $titleLength = 20, $wrapStr = TRUE, $showToggle = TRUE, $hidden = 'default', $showTitle = TRUE, $cssClass = 'featurebox', $cssId = '')."<br />";
							//$cssLayout->setLeftColumnContent($objFeatureBox->show ($this->objLanguage->languageText("mod_liftclub_liftclubname","liftclub","Lift Club"), $fieldset->show()."<br />","clubox", $blockType = NULL, $titleLength = 20, $wrapStr = TRUE, $showToggle = TRUE, $hidden = 'default', $showTitle = TRUE, $cssClass = 'featurebox', $cssId = '')."<br />".$mailFeatBox.$objBlocks->showBlock('login', 'security'));
							//$cssLayout->setMiddleColumnContent($this->getContent());
							//echo $cssLayout->show();        
       // return $cssLayout->show();
       return $firstBox.$mailFeatBox.$objBlocks->showBlock('login', 'security');
    }
}
?>
