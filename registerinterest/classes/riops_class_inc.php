<?php
/**
 *
 * Ops Class for Register interest
 *
 * Ops Class for Register interest - builds various user interface elements
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
 * @package   registerinterest
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
* Ops Class for Register interest
*
* Ops Class for Register interest - builds various user interface elements
*
* @package   registerinterest
* @author    Derek Keats derek@dkeats.com
*
*/
class riops extends object
{

    /**
    *
    * Intialiser for the registerinterest ops class
    * @access public
    * @return VOID
    *
    */
    public function init()
    {
        $this->objLanguage = $this->getObject('language', 'language');
    }

    /**
     *
     * Build the form
     *
     * @return string The form
     * @access public
     *
     */
    public function buildForm()
    {
        
        // Load all the required HTML classes from HTMLElements module
        $this->loadClass('form', 'htmlelements');
        $this->loadClass('textinput','htmlelements');
        $this->loadClass('button','htmlelements');
        
        // Load the javascript.
        $this->appendArrayVar('headerParams',
          $this->getJavaScriptFile('registerinterest.js',
          'registerinterest'));
        
        // Create the form and set the action to save
        $formAction = $this->uri(array('action' => 'save'), 'registerinterest');
        $myForm = new form('edituser', $formAction);
        
        $fnLabel = $this->objLanguage->languageText('mod_registerinterest_fullname', 'registerinterest');
        $fullName = new textinput('fullname');
        $fn = $fnLabel . "<br />" . $fullName->show();
        
        $emLabel = $this->objLanguage->languageText('mod_registerinterest_email', 'registerinterest');
        $emCheck = $this->objLanguage->languageText('mod_registerinterest_confirm', 'registerinterest');
        $eMailAddr = new textinput('email');
        $em = $emLabel . "<br />" . $eMailAddr->show() . "<br />" . $emCheck;
        
        $buttonTitle = $this->objLanguage->languageText('word_save');
        
        $button = new button('saveDetails', $buttonTitle);
        $button->cssId = 'ri_save_button';
        $button->setToSubmit();
        
        $myForm->addToForm($fn . "<br />");
        $myForm->addToForm($em . "<br />");
        $myForm->addToForm($button->show());
        
        return "<div class='registerinterest_form' id = 'ri_form'><div id='before_riform'></div>" . $myForm->show() . "</div>";

    }

}
?>