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
        $GLOBALS['kewl_entry_point_run']) {
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
class riops extends object {

    /**
     *
     * Intialiser for the registerinterest ops class
     * @access public
     * @return VOID
     *
     */
    public function init() {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objUser = $this->getObject('user', 'security');
        $this->objDB = $this->getObject('dbregisterinterest', 'registerinterest');
        $this->objAltConfig = $this->getObject('altconfig', 'config');
        //the DOM document
        $this->doc = new DOMDocument('UTF-8');
    }

    /**
     *
     * Build the form
     *
     * @return string The form
     * @access public
     *
     */
    public function buildForm() {
        // Load all the required HTML classes from HTMLElements module
        $this->loadClass('form', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('button', 'htmlelements');

        // Load the javascript.
        $this->appendArrayVar('headerParams', $this->getJavaScriptFile('registerinterest.js', 'registerinterest'));

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
    public function buildMsgForm() {
        // Load all the required HTML classes from HTMLElements module
        $this->loadClass('form', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('textarea', 'htmlelements');
        $this->loadClass('button', 'htmlelements');
        $this->loadClass('checkbox', 'htmlelements');
        $this->loadClass('dropdown', 'htmlelements');
        $this->loadClass('htmlarea', 'htmlelements');

        // Load the javascript.
        $this->appendArrayVar('headerParams', $this->getJavaScriptFile('registerinterest.js', 'registerinterest'));

        // Create the form and set the action to save
        $formAction = $this->uri(array('action' => 'sendmessage'), 'registerinterest');

        $myForm = new form('editmsg', $formAction);

        $dropDown = new dropdown();
        /* foreach($this->objDB->getAll() as $data){
          //the table
          $chekbox = new checkbox('chk'.$data['email'],$data['fullname'],TRUE);
          $dropDown->addOption('To'.'<br />');
          $dropDown->addOption('Cc'.'<br />');
          $dropDown->addOption('Bcc'.'<br />');
          $myForm->addToForm($dropDown->show());
          } */
        $subjectLabel = $this->objLanguage->languageText('mod_registerinterest_msgsubject', 'registerinterest');
        $subject = new textinput('subject');
        $sj = $subjectLabel . "<br />" . $subject->show();

        $msgLabel = $this->objLanguage->languageText('mod_registerinterest_msglabel', 'registerinterest');
        //$msg = new textarea('message');
        $msg = $this->getObject('htmlarea', 'htmlelements');
        $msg->setName('emailmessage');
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
    public function listAll() {
        if ($this->objUser->isAdmin()) {
            $dataArray = $this->objDB->getAll();
            //if there are no values, return text indicating as such
            if (count($dataArray) == 0) {
                return $this->objLanguage->languageText(" mod_registerinterest_noentries", "registerinterest", "There are no names on the list ");
            } else {
                $domElements['table'] = $this->doc->createElement('table');
                $domElements['table']->setAttribute('class', 'ri_viewall');
                // The link for the message writer.
                $sendlink = $this->uri(array('action' => 'writemessage'), 'registerinterest');
                $sendlink = str_replace("&amp;", "&", $sendlink);
                $div = $this->doc->createElement('div');
                $div->setAttribute('class', 'ri_msgpopper');
                $h3 = $this->doc->createElement('h3');
                $a = $this->doc->createElement('a');
                $a->setAttribute('class', 'ri_msglink');
                $a->setAttribute('href', $sendlink);
                $a->appendChild($this->doc->createTextNode($this->objLanguage->languageText('phrase_sendmessage', 'system')));
                $h3->appendChild($a);
                $domElements['tr'] = $this->doc->createElement('tr');
                $domElements['td'] = $this->doc->createElement('td');
                $domElements['td']->appendChild($h3);
                $domElements['tr']->appendChild($domElements['td']);
                $domElements['table']->appendChild($domElements['tr']);
                // Create the header row.
                $domElements['tr'] = $this->doc->createElement('tr');
                $domElements['th'] = $this->doc->createElement('td');
                $domElements['th']->appendChild($this->doc->createTextNode("Name"));
                $domElements['tr']->appendChild($domElements['th']);
                $domElements['th'] = $this->doc->createElement('td');
                $domElements['th']->appendChild($this->doc->createTextNode("Email"));
                $domElements['tr']->appendChild($domElements['th']);
                $domElements['th'] = $this->doc->createElement('td');
                $domElements['th']->appendChild($this->doc->createTextNode("Registered"));
                $domElements['tr']->appendChild($domElements['th']);
                // Add the row to the table.
                $domElements['table']->appendChild($domElements['tr']);

                $class = "odd";
                foreach ($dataArray as $usr) {
                    //the icon object
                    $objIcon = $this->getObject('geticon', 'htmlelements');
                    // Reatrieve the data.
                    $id = $usr['id'];
                    $fullName = $usr['fullname'];
                    $emailAddress = $usr['email'];
                    $dateCreated = $usr['datecreated'];
                    $objIcon->getDeleteIconWithConfirm('delete', array('action' => 'remove', 'id' => $id), NULL, NULL);
                    // Create the table row.
                    $domElements['tr'] = $this->doc->createElement('tr');
                    //label
                    $domElements['label'] = $this->doc->createElement('label');
                    // Fullname to table.
                    $domElements['td'] = $this->doc->createElement('td');
                    $domElements['tr']->setAttribute('class', $class);
                    $domElements['td']->appendChild($this->doc->createTextNode($fullName));
                    $domElements['tr']->appendChild($domElements['td']);
                    // Email address to table.
                    $domElements['td'] = $this->doc->createElement('td');
                    //check if user is admin so to display the correct control
                    $domElements['td']->setAttribute('id', $id);
                    $domElements['label']->setAttribute('value', $emailAddress);
                    $domElements['td']->setAttribute('class', 'interestEmail');
                    $domElements['label']->appendChild($this->doc->createTextNode($emailAddress));
                    $domElements['label']->setAttribute('for', 'interestEmail');
                    $domElements['label']->setAttribute('id', $id);
                    $domElements['td']->appendChild($domElements['label']);
                    $domElements['tr']->appendChild($domElements['td']);
                    // Date registered to table.
                    $domElements['td'] = $this->doc->createElement('td');
                    $domElements['td']->appendChild($this->doc->createTextNode($dateCreated));
                    $domElements['tr']->appendChild($domElements['td']);

                    // Add the row to the table.
                    $domElements['table']->appendChild($domElements['tr']);

                    //if user is admin, add the delete link
                    //create the delete link
                    $domElements['rmLink'] = $this->doc->createElement('a');
                    //create the confirmation object which to retrieve the javascript confirmation message from
                    $confirmLink = $this->getObject('confirm', 'utilities');
                    $confirmLink->setConfirm(NULL, str_replace('amp;', '', $this->uri(array('action' => 'remove', 'id' => $id))), $this->objLanguage->languageText('mod_registerinterest_removealert', 'registerinterest'));
                    //td element for the remove link
                    $domElements['td'] = $this->doc->createElement('td');
                    //create the delete icon
                    $domElements['delIcon'] = $this->doc->createElement('image');
                    $domElements['delIcon']->setAttribute('id', 'deleteIcon');
                    $domElements['delIcon']->setAttribute('src', $objIcon->getSrc());
                    //add link text
                    //set the href attribute of the link
                    $domElements['rmLink']->setAttribute('href', $confirmLink->href);
                    //$domElements['rmLink']->appendChild($domElements['delIcon']);
                    $domElements['rmLink']->appendChild($domElements['delIcon']);
                    $domElements['td']->appendChild($domElements['rmLink']);
                    $domElements['tr']->appendChild($domElements['td']);
                    $domElements['td']->appendChild($this->doc->createElement('br'));
                    $domElements['td']->appendChild($this->doc->createElement('br'));
                    $domElements['table']->appendChild($domElements['tr']);

                    // Convoluted odd/even.
                    if ($class == "odd") {
                        $class = "even";
                    } else {
                        $class = "odd";
                    }
                }
                //update form
                $this->doc->appendChild($domElements['table']);
                return $this->doc->saveHTML();
            }
        }
    }

    /**
     * The function which carries out the message sending
     * 
     * @param string $subject The message subject
     * @param string $message The message content
     * @param string $userId The userID of the sender
     * @return boolean TRUE on success|else FALSE
     */
    public function sendMessage($subject = NULL, $message, $userId) {
        if (!empty($message)) {
            $objMail = & $this->getObject('mailer', 'mail');
            //setting fromName
            $fromName = $this->objUser->fullname($userId);
            //setting the email address using the servername and the username   
            $from = $this->objUser->userName() . '@' . $_SERVER['SERVER_NAME'];
            //setting the subject
            if (empty($subject)) {
                $subject = $this->objLanguage->languageText('phrase_nosubject', 'system');
            }
            $subject = '[Register interest]' . $subject;
            $objMail->setValue('subject', $subject);
            $objMail->setValue('from', $from);
            $objMail->setValue('fromName', $fromName);
            // Add alternative message - same version minus html tags
            //loop through the available email addresses
            foreach ($this->objDB->getAll() as $data) {
                $messageTwo = "
        {$this->objLanguage->languageText('word_from', 'system')} : {$fromName} \n\r
        {$this->objLanguage->languageText('word_subject', 'system')} : {$subject}
        ________________________________________________\n
        Hi {$data['fullname']} \n
        " . $message . " \n\r
        ________________________________________________\n
        {$this->objLanguage->languageText('mod_registerinterest_optoutmsg', 'registerinterest')} \n
                ";
                $plainMessage = strip_tags($messageTwo);
                $objMail->setValue('useHTMLMail', TRUE);
                $objMail->setValue('htmlbody', $messageTwo);
                $objMail->setValue('to', $data['email']);
                $objMail->setValue('body', $plainMessage);
                if ($objMail->send()) {
                    $retValue = TRUE;
                } else {
                    $retValue = FALSE;
                }
            }
        } else {
            $retValue = FALSE;
        }
        return $retValue;
    }

}

?>