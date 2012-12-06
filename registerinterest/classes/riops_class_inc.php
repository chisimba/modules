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
        $this->objUser = $this->getObject('user','security');
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
    /**
     * 
     * Build form for sending message to registered people.
     * 
     * @return string The form
     * @access public
     * 
     */
    public function buildMsgForm()
    {
        // Load all the required HTML classes from HTMLElements module
        $this->loadClass('form', 'htmlelements');
        $this->loadClass('textinput','htmlelements');
        $this->loadClass('textarea','htmlelements');
        $this->loadClass('button','htmlelements');
        
        // Load the javascript.
        $this->appendArrayVar('headerParams',
          $this->getJavaScriptFile('registerinterest.js',
          'registerinterest'));
        
        // Create the form and set the action to save
        $formAction = $this->uri(array('action' => 'sendmsg'), 'registerinterest');
        
        
        $myForm = new form('editmsg', $formAction);
               
        $subjectLabel = $this->objLanguage->languageText('mod_registerinterest_msgsubject', 'registerinterest');
        $subject = new textinput('subject');
        $sj = $subjectLabel . "<br />" . $subject->show();
        
        $msgLabel = $this->objLanguage->languageText('mod_registerinterest_msglabel', 'registerinterest');
        $msg = new textarea('message');
        $ms = $msgLabel . "<br />" . $msg->show();
        
        $buttonTitle = $this->objLanguage->languageText('word_send');
        $button = new button('saveDetails', $buttonTitle);
        $button->cssId = 'ri_savemsg_button';
        $button->setToSubmit();
        
        $myForm->addToForm($sj . "<br />");
        $myForm->addToForm($ms . "<br />");
        $myForm->addToForm($button->show());
        
        return "<div class='registerinterest_form' id = 'ri_form'><div id='before_riform'></div>" . $myForm->show() . "</div>";

    }
    
    
    /**
     * 
     * Render an HTML table of all registered people
     * 
     * @return string The rendered HTML table
     */
    public function listAll()
    {
        $objDb = $this->getObject('dbregisterinterest', 'registerinterest');
        $dataArray = $objDb->getAll();
        $doc = new DOMDocument('UTF-8');
        $domElements['table'] = $doc->createElement('table');
        $domElements['table'] = $doc->createElement('table');
        $domElements['table']->setAttribute('class', 'ri_viewall');
        // Create the header row.
        $domElements['tr'] = $doc->createElement('tr');
        $domElements['th'] = $doc->createElement('td');
        $domElements['th']->appendChild($doc->createTextNode("Name"));
        $domElements['tr']->appendChild($domElements['th']);
        $domElements['th'] = $doc->createElement('td');
        $domElements['th']->appendChild($doc->createTextNode("Email"));
        $domElements['tr']->appendChild($domElements['th']);
        $domElements['th'] = $doc->createElement('td');
        $domElements['th']->appendChild($doc->createTextNode("Registered"));
        $domElements['tr']->appendChild($domElements['th']);
        // Add the row to the table.
        $domElements['table']->appendChild($domElements['tr']);
        
        $class = "odd";
        foreach ($dataArray as $usr) {
            // Reatrieve the data.
            $id = $usr['id'];
            $fullName = $usr['fullname'];
            $emailAddress = $usr['email'];
            $dateCreated = $usr['datecreated'];
            // Create the table row.
            $domElements['tr'] = $doc->createElement('tr');
            // Fullname to table.
            $domElements['td'] = $doc->createElement('td');
            $domElements['td']->setAttribute('class', $class);
            $domElements['td']->appendChild($doc->createTextNode($fullName));
            $domElements['tr']->appendChild($domElements['td']);
            // Email address to table.
            $domElements['td'] = $doc->createElement('td');
            $domElements['td']->setAttribute('class', $class);
            //check if user is admin so to display the correct control
            if(!$this->objUser->isAdmin()){
                $domElements['td']->appendChild($doc->createTextNode($emailAddress));
            }  else {
                $domElements['txtEmail'] = $doc->createElement('input');
                $domElements['txtEmail']->setAttribute('value', $emailAddress);
                $domElements['txtEmail']->setAttribute('type', 'text');
                $domElements['txtEmail']->setAttribute('id', $id);
                $domElements['txtEmail']->setAttribute('class', 'interestEmail');
                $domElements['td']->appendChild($domElements['txtEmail']);
            }
            $domElements['tr']->appendChild($domElements['td']);
            // Date registered to table.
            $domElements['td'] = $doc->createElement('td');
            $domElements['td']->setAttribute('class', $class);
            $domElements['td']->appendChild($doc->createTextNode($dateCreated));
            $domElements['tr']->appendChild($domElements['td']);
            
            // Add the row to the table.
            $domElements['table']->appendChild($domElements['tr']);
            
            //if user is admin, add the delete link
            if($this->objUser->isAdmin()){
                //create the delete link
                $domElements['rmLink'] = $doc->createElement('a');
                //create the confirmation object which to retrieve the javascript confirmation message from
                $confirmLink = $this->getObject('confirm','utilities');
                $confirmLink->setConfirm(NULL,  str_replace('amp;','',$this->uri(array('action'=>'remove','id'=>$id))),  $this->objLanguage->languageText('mod_registerinterest_removealert','registerinterest'));
                //td element for the remove link
                $domElements['td'] = $doc->createElement('td');
                //assing the ID value
                $domElements['td']->setAttribute('id', $usr['id']);
                //add link text
                //set the href attribute of the link
                $domElements['rmLink']->setAttribute('href', $confirmLink->href);
                $domElements['rmLink']->appendChild($doc->createTextNode($this->objLanguage->languageText('word_remove','system')));
                $domElements['td']->appendChild($doc->createTextNode('|'));
                $domElements['td']->appendChild($domElements['rmLink']);
                $domElements['tr']->appendChild($domElements['td']);
                $domElements['table']->appendChild($domElements['tr']);
            }
            // Convoluted odd/even.
            if ($class == "odd") { 
                $class = "even";
            } else {
                $class = "odd";
            }
        }
        //update form
        $domElements['updtForm'] = $doc->createElement('form');
        $domElements['updtForm']->setAttribute('method', 'POST');
        $domElements['updtForm']->setAttribute('id', 'frmUpdate');
        $domElements['updtForm']->setAttribute('action', str_replace('amp;', '', $this->uri(array('action'=>'update'))));
        $domElements['updtForm']->setAttribute('name', 'frmUpdate');
        //update button
        $domElements['updtButton'] = $doc->createElement('input');
        $domElements['updtButton']->setAttribute('type', 'submit');
        $domElements['updtButton']->setAttribute('id', 'btnUpdate');
        $domElements['updtButton']->setAttribute('name', 'btnUpdate');
        $domElements['updtButton']->setAttribute('value',$this->objLanguage->languageText('word_update','system'));
        $domElements['updtForm']->appendChild($domElements['table']);
        $domElements['updtForm']->appendChild($domElements['updtButton']);
        $doc->appendChild($domElements['updtForm']);
        return $doc->saveHTML();
    }
    
    /**
     * 
     * Render a form for an admin to post a message.
     * 
     * @return type
     * 
     */
    public function renderPostMessage()
    {
        // The link for the message writer.
        $sendlink = $this->uri(array('action' => 'writemessage'), 'registerinterest');
        $sendlink = str_replace("&amp;", "&", $sendlink);
        
        $doc = new DOMDocument('UTF-8');
        $div = $doc->createElement('div');
        $div->setAttribute('class', 'ri_msgpopper');
        $a = $doc->createElement('a');
        $a->setAttribute('class', 'ri_msglink');
        $a->setAttribute('href', $sendlink);
        $a->appendChild($doc->createTextNode("Send message"));
        $div->appendChild($a);
        $doc->appendChild($div);
        return $doc->saveHTML();
    }

}
?>