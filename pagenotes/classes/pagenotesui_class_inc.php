<?php
/**
 *
 * User interface elements for Page notes
 *
 * User interface elements for Page notes, which allow users
 * to add notes to any page containing one of the pagenotes 
 * blocks or keyelements. A key element is one that when clicked
 * loads a pagenote note taker for the current page.
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
 * @package   pagenotes
 * @author    Derek Keats <derek@dkeats.com>
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
 *
 * User interface elements for Page notes
 *
 * User interface elements for Page notes, which allow users
 * to add notes to any page containing one of the pagenotes 
 * blocks or keyelements. A key element is one that when clicked
 * loads a pagenote note taker for the current page.
*
* @package   pagenotes
* @author    Derek Keats <derek@dkeats.com>
*
*/
class pagenotesui extends object
{
    
    /**
    * 
    * @var string $objLanguage String object property for holding the 
    * language object
    * @access public
    * 
    */
    public $objLanguage;

    /**
    *
    * Intialiser for the pagenotes user interface builder
    * @access public
    * @return VOID
    *
    */
    public function init()
    {
        $this->objUser = $this->getObject('user', 'security');
        $this->objLanguage = $this->getObject('language', 'language');
        $arrayVars['noterequired'] = "mod_pagenotes_noterequired";
        $arrayVars['status_success'] = "mod_pagenotes_status_success";
        $arrayVars['status_fail'] = "mod_pagenotes_status_fail";
        $objSerialize = $this->getObject('serializevars', 'utilities');
        $objSerialize->languagetojs($arrayVars, 'pagenotes');
        // Load the jquery validate plugin
        $this->appendArrayVar('headerParams',
        $this->getJavaScriptFile('plugins/validate/jquery.validate.min.js',
          'jquery'));
        $this->appendArrayVar('headerParams',
          $this->getJavaScriptFile('pagenotes.js',
          'pagenotes'));
    }

    /**
     *
     * Get the text of the init_overview that we have in the sample database.
     *
     * @return string The text of the init_overview
     * @access public
     *
     */
    public function showAddEditBox()
    {
        return $this->makeEditForm();

    }
    
    /**
     *
     * Create a small for for adding to a side block
     * 
     * @return string The rendered form
     * @access private
     * 
     */
    private function makeEditForm()
    {
        $note = NULL;
        $this->loadClass('form','htmlelements');
        $this->loadClass('textinput','htmlelements');
        $this->loadClass('textarea','htmlelements');
        $this->loadClass('hiddeninput', 'htmlelements');
        $this->loadClass('button', 'htmlelements');
        
        // The form
        $formNote = new form('noteEditor', NULL);
        
        // Hidden input for the page.
        $objUrl = $this->getObject('urlutils', 'utilities');
        $page = $objUrl->curPageURL();
        // Remove passthroughlogin as it will mess up the page.
        $page = str_replace('&passthroughlogin=true', NULL, $page);
        $hidPage = new hiddeninput('page');
        $hidPage->cssId = "page";
        $hidPage->value = $page;
        $formNote->addToForm($hidPage->show() . $page);
        
        // The page hash key.
        $hash = md5($page);
        $hidHash = new hiddeninput('hash');
        $hidHash->cssId = "hash";
        $hidHash->value = $hash;
        $formNote->addToForm($hidHash->show() . "<br />$hash");
        
        // The edit/add mode.
        $mode = $this->getParam('mode', NULL);
        $hidMode = new hiddeninput('mode');
        $hidMode->cssId = "mode";
        $hidMode->value = $mode;
        $formNote->addToForm($hidMode->show());
        
        // The note type will always be pagenote.
        $hidType = new hiddeninput('notetype');
        $hidType->cssId = "notetype";
        $hidType->value = "pagenote";
        $formNote->addToForm($hidType->show());
        
        // The note editor box.
        $noteText = new textarea('note');
        $noteText->setValue($note);
        $noteText->cssClass = 'required';
        $formNote->addToForm($noteText->show());
        
        // Save button.
        $buttonTitle = $this->objLanguage->languageText('word_save');
        $button = new button('submitNote', $buttonTitle);
        $button->setToSubmit();
        $formNote->addToForm($button->show());
        
        // The results area.
        $formNote->addToForm("<div id='save_results' class='noticearea'></div>");
        
        return $formNote->show();
    }

}
?>