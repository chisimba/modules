<?php
/**
* conferenceprelogin class extends controller
* @package conferenceprelogin
* @filesource
*/

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* Controller class for conferenceprelogin module
* Module is a delivery system for conference specific information including registration to the conference and site, submission of abstracts and or papers.
*
* @author Megan Watson
* @copyright (c) 2004-2006 UWC
* @version 0.1
*/

class conferenceprelogin extends controller
{
    /**
    * Method to construct the class.
    */
    function init()
    {
        $this->confReg = $this->getObject('conferenceregister', 'conferenceprelogin');
        $this->confContent = $this->getObject('conferencecontent', 'conferenceprelogin');
        $this->objDBContext = $this->getObject('dbcontext', 'context');
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objUser = $this->getObject('user', 'security');
        $this->objConfig = $this->getObject('dbsysconfig', 'sysconfig');

    }

    /**
    * Standard dispatch function
    */
    function dispatch($action)
    {
    
        switch($action){
     
            case 'saveregister':
                $mode = $this->getParam('mode');
                $display = $this->confReg->show($mode);
                return $this->nextAction('register', array('mode' => 'confirm'));
		break;

            case 'register':
                $mode = $this->getParam('mode');
                $display = $this->confReg->show($mode);
                $this->setVarByRef('display', $display);
                return 'home_tpl.php';
                break;

            case 'viewcontent':
                $mode = $this->getParam('mode');
                $display = $this->confContent->show($mode);
                $this->setVarByRef('display', $display);
                return 'home_tpl.php';
                break;


            default:
                return $this->nextAction('register');
        }
    }

    /**
    * Method to set the login icon in the footer
    *
    * @access public
    * @return
    */

   function setFooter()
    {
        $objIcon =& $this->newObject('geticon', 'htmlelements');
        $this->loadClass('link', 'htmlelements');

        $title = $this->objLanguage->languageText('mod_conferenceprelogin_manageconferences');

        $objIcon->setIcon('admin');
        $objIcon->title = $title;

        $objLink = new link($this->uri('', 'splashscreen'));
        $objLink->link = $objIcon->show();
        $icon = $objLink->show();

       
    }

    /**
    * This method is called by Ajax for the Advanced Test
    * Puts a response whether a username exists or not.
    * @author James Scoble
    * @param string $username Username to check
    */
    function usernameExists($username)
    {
        $objStringValidate =& $this->getObject('strvalidate', 'strings');
        $this->loadClass('xajaxresponse','htmlelements');
        if ($username == '') {
            $response = NULL;
        }
        else if (!$objStringValidate->isAlphaNumeric($username) || substr_count($username, ' ')>0) {
            $response = '<span class=\'error\'>Error</span> - Username may only contain letters and digits.';
        }
        else {
            $objUser =& $this->getObject('user', 'security');
            $found = $objUser->valueExists('username', $username);
            if ($found) {
                $response = '<span class=\'Registrationerror\'>Error</span> - Username <strong>'.$username.'</strong> has already been taken.';
            }
            else {
                $response = '<span class=\'confirm\'>Username <strong>'.$username.'</strong> is available.</span>';
            }
        }
        $objResponse = new xajaxResponse();
        $objResponse->addAssign('usernameDiv', 'innerHTML', $response);
        return $objResponse->getXML();
    }

    /**
     * Method to show the confirmation of the file upload
     *
     */
    function showFileUploaded()
    {

        if($this->getParam('error'))
        {
            return 'There was an error uploading your file:<br><span class="warning">'.$this->getParam('error').'</span>';
        } else {
            $str = 'Your '.$this->getParam('documenttype').' has been successfully uploaded!';
           
            return $str;
        }
    }

    /**
    * Method to set login requirement to False
    * Required to be false.
    */
    function requiresLogin()
    {
        return FALSE;
    }
} // end of controller class
?>