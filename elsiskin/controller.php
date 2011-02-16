<?php

/**
 * This module contains utilities for rendering elsi skin.
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

 * @author
 * @copyright  2009 AVOIR
 */
// security check - must be included in all scripts
if (!
/**
 * Description for $GLOBALS
 * @global unknown $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
class elsiskin extends controller {


    function init() {
    	//Instantiate the language object
        //$this->objLanguage = $this->getObject('language', 'language');
        $this->objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $this->adminEmail = $this->objSysConfig->getValue('ADMIN_EMAIL', 'elsiskin');
    }


    public function dispatch($action) {
    /*
    * Convert the action into a method (alternative to
    * using case selections)
    */
        $method = $this->getMethod($action);
    /*
    * Return the template determined by the method resulting
    * from action
    */
        return $this->$method();
    }

    /**
     *
     * Method to convert the action parameter into the name of
     * a method of this class.
     *
     * @access private
     * @param string $action The action parameter passed byref
     * @return string the name of the method
     *
     */
    function getMethod(& $action) {
        if ($this->validAction($action)) {
            return '__'.$action;
        }
        else {
            return '__home';
        }
    }

    /**
     *
     * Method to check if a given action is a valid method
     * of this class preceded by double underscore (__). If it __action
     * is not a valid method it returns FALSE, if it is a valid method
     * of this class it returns TRUE.
     *
     * @access private
     * @param string $action The action parameter passed byref
     * @return boolean TRUE|FALSE
     *
     */
    function validAction(& $action) {
        if (method_exists($this, '__'.$action)) {
            return TRUE;
        }
        else {
            return FALSE;
        }
    }

    /**
     * landing page.
     * @return
     */
    function __home() {
        return "home_tpl.php";
    }

    /**
     * Method to submit the details from the contact us form page.
     *
     * @access private
     * @param string $subject The subject of the matter for which user is asking for assistance.
     * @param string $name The name of the user
     * @param string $email the email address of the user
     * @param string $comments the comments that the user has submitted
     * @return none
     */
    function __contactformsubmit() {
        $subject = $this->objSysConfig->getValue('EMAIL_SUBJECT', 'elsiskin');
        $body  = "Message: ".$this->getParam('c_message')."\n";
        $body .= "Topic: ".$this->getParam('c_topic')."\n";
        $body .= "Name: ". $this->getParam('c_name')."\n";
        $email.= "Email: ". $this->getParam('c_email')."\n";

        $toEmail = $this->adminEmail.",".$this->getParam('c_email');
        
        $objMailer = $this->getObject('email', 'mail');
        $objMailer->setValue('to', $toEmail);
        $objMailer->setValue('from', $this->adminEmail);
        $objMailer->setValue('subject', $subject);
        $objMailer->setValue('body', strip_tags($body));
        $objMailer->send();
        
        return $this->nextAction("contact", array("submission"=>"true"));
    }


    function requiresLogin(){
        return false;
    }
}
?>
