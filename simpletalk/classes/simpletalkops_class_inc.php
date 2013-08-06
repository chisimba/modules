<?php
/**
 *
 * Basic ops class for simpletalk
 *
 * Basic ops class for simpletalk, a simple module to allow a user to Submit a 
 * conference talk and have it accepted or rejected. 
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
 * @package   simpletalk
 * @author    Derek Keats derek@dkeats.com
 * @copyright 2007 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   0.001
 * @link      http://www.chisimba.com
 *
 */

// security check - must be included in all scripts
if (!
/**
 * The $GLOBALS is an array used to control access to certain constants.
 * Here it is used to check if the file is opening in engine, if not it
 * stops the file from running.
 *
 * @global entry point $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 *
 */
$GLOBALS['kewl_entry_point_run'])
{
        die("You cannot view this page directly");
}
// end security check

/**
*
 * Basic ops class for simpletalk
 *
 * Basic ops class for simpletalk, a simple module to allow a user to Submit a 
 * conference talk and have it accepted or rejected.
*
* @package   simpletalk
* @author    Derek Keats derek@dkeats.com
*
*/
class simpletalkops extends object
{
    
    /**
     *
     * @var string Object $objLanguage String for the language object
     * @access public
     *
     */
    public $objLanguage;
    /**
     *
     * @var string Object $objUser String for the user object
     * @access public
     *
     */
    public $objUser;

    /**
    *
    * Intialiser for the simpletalk database connector
    * @access public
    * @return VOID
    *
    */
    public function init()
    {
        // Get an instance of the languate object
        $this->objLanguage = $this->getObject('language', 'language');
        // Instantiate the user object.
        $this->objUser = $this->getObject('user', 'security');
    }

    /**
     *
     * Get the text of the init_overview that we have in the sample database.
     *
     * @return string The text of the init_overview
     * @access public
     *
     */
    public function showForm($edit=FALSE)
    {
        
        // Load required classes from htmlelements.
        $this->loadClass('form','htmlelements');
        $this->loadClass('textinput','htmlelements');
        $this->loadClass('textarea','htmlelements');
        $this->loadClass('dropdown','htmlelements');
        $this->loadClass ('hiddeninput', 'htmlelements');
        
        // Initialise the return string.
        $ret = "";
        
        // Use DOM to build the wrapper
        $doc = new DOMDocument('UTF-8');
        $wrapper = $doc->createElement('div');
        $wrapper->setAttribute('class', 'simpletalk_form');
        
        // Create the form for the talk submission.
        $paramArray=array(
            'action'=>'save');
        $formAction = $this->uri($paramArray, 'simpletalk');
        $formAction =  str_replace("&amp;", "&", $formAction);
        $objForm = new form('simplefeedback');
        $objForm->setAction($formAction);
        $objForm->displayType=3;
        
        // Check for edit and load values
        if ($edit) { 
            die("EDIT not ready yet");
        } else {
            $title = NULL;
            $authors = NULL;
            $duration = NULL;
            $track = NULL;
            $abstract = NULL;
            $requirements = NULL;
        }
        
        // Add the title to the form.
        $objTitle = new textinput('title', $title);
        $objTitle->id='simpletalk_title';
        $titleLabel = $this->objLanguage->languageText("mod_simpletalk_title",
            "simpletalk", "Title of your proposed talk");
        $titleShow = $titleLabel . ":<br />" . $objTitle->show() . "<br />";
        $objForm->addToForm($titleShow);
        
        // Add the authors to the form.
        $objAuth = new textinput('authors', $authors);
        $objAuth->id='simpletalk_authors';
        $authLabel = $this->objLanguage->languageText("mod_simpletalk_authors",
            "simpletalk", "List of authors, as SURNAME, INITIAL; SURNAME, INITIAL");
        $authShow = $authLabel . ":<br />" . $objAuth->show() . "<br />";
        $objForm->addToForm($authShow);
        
        // Add the talk type dropdown to the form.
        $shortTalk = $this->objLanguage->languageText("mod_simpletalk_short",
            "simpletalk", "Short talk (10-25 minutes)");
        $longTalk = $this->objLanguage->languageText("mod_simpletalk_long",
            "simpletalk", "Full talk (45 minutes)");
        $objDd = new dropdown('duration');
        $dbDd = $this->getObject('dbdurations', 'simpletalk');
        $rsDd = $dbDd->getDurations();
        foreach ($rsDd as $item) {
            $objDd->addOption($item['duration'], $item['duration_label']);
        }
        $ddLabel = $this->objLanguage->languageText("mod_simpletalk_duration",
            "simpletalk", "Talk duration");
        $ddShow = $ddLabel . ":<br />" . $objDd->show() . "<br />";
        $objForm->addToForm($ddShow);
        
        // Add the talk track dropdown to the form.
        $objDd = new dropdown('track');
        $dbDd = $this->getObject('dbtracks', 'simpletalk');
        $rsDd = $dbDd->getTracks();
        foreach ($rsDd as $item) {
            $objDd->addOption($item['track'], $item['track_label']);
        }
        $ddLabel = $this->objLanguage->languageText("mod_simpletalk_track",
            "simpletalk", "Talk track");
        $ddShow = $ddLabel . ":<br />" . $objDd->show() . "<br />";
        $objForm->addToForm($ddShow);
        
        // Add the abstract to the form.
        $abstract = new textarea('abstract');
        $abstractLabel = $this->objLanguage->languageText("mod_simpletalk_abstract",
            "simpletalk", "Talk abstract");
        $absShow = $abstractLabel . ":<br />" . $abstract->show() . "<br />";
        $objForm->addToForm($absShow);
        
        // Add the special requirements to the form.
        $req = new textarea('requirements');
        $reqLabel = $this->objLanguage->languageText("mod_simpletalk_requirements",
            "simpletalk", "Indicate any special requirements you may have for your presentation");
        $reqShow = $reqLabel . ":<br />" . $req->show() . "<br />";
        $objForm->addToForm($reqShow);
        
        //Add a save button
        $objButton = $this->newObject('button', 'htmlelements');
        $objButton->button('go',$this->objLanguage->languageText('word_go'));
        $objButton->setCSS('sb_searchbutton');
        $objButton->sexyButtons=FALSE;
        $objButton->setToSubmit();
        $objForm->addToForm($objButton->show());
        
        // Sent back the form
        return $objForm->show();
        
    }

}
?>