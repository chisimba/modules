<?php

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
// end security check
                                                                                                                                             
/**
* Demo of Ajax within KEWL.NextGen
* @copyright 2005 KEWL.NextGen
* @author Tohir Solomons
*/
class xajaxwrapper extends controller
{

    /**
    * Constructor
    */
    function init()
    {
        $this->loadClass('xajax');
        $this->loadClass('xajaxresponse');
    }
    
    /**
    * Dispatch Method
    */
    function dispatch($action)
    {
        switch ($action)
        {
            case 'advanced' : return $this->advancedAjaxTest();
            default: return $this->simpleAjaxTest();
        }
        
    }
    
    /**
    * Method to demo a simple AJAX test
    */
    function simpleAjaxTest()
    {
        // Instantiate Class - Parameter MUST be the URL with the current action
        $xajaxTest = new xajax($this->uri(NULL));
        
        $xajaxTest->registerFunction(array($this,"getDateTime")); // Register another function in this controller
        
        $xajaxTest->processRequests(); // XAJAX method to be called
        
        $this->appendArrayVar('headerParams', $xajaxTest->getJavascript()); // Send JS to header
        
        return 'page1.php';
    }
    
    /**
    * This method is called by Ajax for the Simple Test
    */
    function getDateTime()
    {
        $objResponse = new xajaxResponse();
        $objResponse->addAppend('someDiv', 'innerHTML', '<p>Current Time is '.date('H:m:s').'</p>');
        return $objResponse->getXML();
    }
    
    /**
    * Method to demo the advanced AJAX test
    */
    function advancedAjaxTest()
    {
        // Instantiate Class - Parameter MUST be the URL with the current action
        $xajaxTest = new xajax($this->uri(array('action'=>'advanced')));
        
        $xajaxTest->registerFunction(array($this,"userNameExists")); // Register another function in this controller
        
        $xajaxTest->processRequests(); // XAJAX method to be called
        
        $this->appendArrayVar('headerParams', $xajaxTest->getJavascript()); // Send JS to header
        
        return 'page2.php';
    }
    
    /**
    * This method is called by Ajax for the Advanced Test 
    * Puts a response whether a username exists or not.
    * @param string $username Username to check
    */
    function userNameExists($username)
    {
        $this->objUser =& $this->getObject('user', 'security');
        $objStringValidate =& $this->getObject('strvalidate', 'strings');
        $result = $this->objUser->valueExists('username', $username);
        
        $objResponse = new xajaxResponse();
        
        if ($result) {
            $response = 'Username <strong>'.$username.'</strong> has been taken already.';
            $objResponse->addAssign('submitbutton', 'disabled', 'true');
        } else {
            $response = 'Username <strong>'.$username.'</strong> is available.';
            $objResponse->addClear('submitbutton', 'disabled');
        }
        
        if (!$objStringValidate->isAlphaNumeric($username) || substr_count($username, ' ')>0) {
            $response = 'Error - Username may only contain letters of the alphabet and numbers';
            $objResponse->addAssign('submitbutton', 'disabled', 'true');
        }
        
        if ($username == '') {
            $response = NULL;
            $objResponse->addAssign('submitbutton', 'disabled', 'true');
        }
        
        
        $objResponse->addAssign('someDiv', 'innerHTML', $response);
        return $objResponse->getXML();
    }
    
    /**
    * Method to override login for demo purposes
    */
    function requiresLogin()
    {
        return FALSE;
    }

}

?>