<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of controller
 *
 * @author monwabisi
 */
class internals extends controller {

    /**
     * 
     * The standard dispatch method for the species module.
     * The dispatch method uses methods determined from the action 
     * parameter of the  querystring and executes the appropriate method, 
     * returning its appropriate template. This template contains the code 
     * which renders the module output.
     * 
     */
    public function dispatch() {
        //Get action from query string and set default to view
        $action = $this->getParam('action', 'view');
        /*
         * Convert the action into a method (alternative to 
         * using case selections)
         */
        $method = $this->__getMethod($action);
        // Set the layout template to compatible one
        $this->setLayoutTemplate('layout_tpl.php');
        /*
         * Return the template determined by the method resulting 
         * from action
         */
        return $this->$method();
    }

    /**
     * @access public
     * @return string
     */
    private function __view() {
        // All the action is in the blocks
        return "main_tpl.php";
    }

    /**
     * Method to push the request to the module administrator
     *
     *  @access private
     */
    private function __pushRequest() {
        //get database object
        $objDB = $this->getObject('dbinternals', 'internals');
        $objUser = $this->getObject('user', 'security');
        //get user id
        $userId = $objUser->getUserId($objUser->userName());
        //leave name
        $leaveName = $this->getParam('leaveName', 'annual');
        //change the leave name to ID
        $leaveID = $objDB->getLeaveID($leaveName);
        //number of days requested
        $numDays = $this->getParam('txtdaysrequested', NULL);
        //start date
        $startDate = $this->getParam('startdate', NULL);
        //end date
        $endDate = $this->getParam('enddate', NULL);
        //insert the data into the database
        $objDB->postRequest($userId, $leaveID, $startDate, $endDate);
        die();
    }

    /**
     * 
     * Method to return an error when the action is not a valid 
     * action method
     * 
     * @access private
     * @return string Redirect to module home page
     * 
     */
    private function __actionError() {
        return header('location:index.php?module=internals');
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
    function __validAction(& $action) {
        if (method_exists($this, "__" . $action)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Method to add users to the internals module
     * 
     * @access public
     * @return type
     */
    public function __addUser() {
        //config object
        $objConfig = $this->getObject('altconfig', 'config');
        //dom object
        $domDoc = new DOMDocument('utf-8');
        //get the id parameter
        $userId = $this->getParam('id', NULL);
        //instantiate the user object
        $objUser = $this->getObject('user', 'security');
        //check if user is logged in
        if ($objUser->isLoggedIn()) {
            //instantiate the database object
            $dbObject = $this->getObject('dbinternals', 'internals');
            //call the function to add the user to the internals table
            return $dbObject->addUser($userId);
        }
    }

    public function __removeUser() {
        $userId = $this->getParam('id', NULL);
        $objUser = $this->getObject('user', 'security');
    }

    /**
     * Method for updating a request
     * @access public
     * @return type Description
     */
    public function __updateRequest() {
        //record Id
        $id = $this->getParam('id', NULL);
        //user ID
        $userID = $this->getParam('x_data', NULL);
        //request status
        $requestStatus = $this->getParam('status', NULL);
        //database object
        $objDB = $this->getObject('dbinternals', 'internals');
        //comments
        $comments = $this->getParam('comments', NULL);
//                $objDB->insert($values, 'tbl_requests');
        $values = array(
            'status' => $requestStatus,
            'comments' => $comments
        );
        //html to pdf test

        $objMail = & $this->getObject('mailer', 'mail');
        //pdate the database
        $objDB->update('id', $id, $values, 'tbl_requests');
        //prepare to send message
        $objUser = $this->getObject('user', 'security');
        //get the user email address
        $userEmail = $objUser->email($userID);
        //get the user's full name
        $userFullName = $objUser->fullname($userID);
        //Prepare the message
        $objMail->setValue('to', "wsifumba@gmail.com");
        $objMail->setValue('from', 'noreply@hermes');
        $objMail->setValue('fromName', 'Monwabisi');
        $objMail->setValue('subject', 'Leaves appliction');
        $objMail->setValue('body', "{$userFullName} your leave request has been " . $requestStatus);
        $objMail->setValue('htmlbody', "{$userFullName}<br /> your leave request has been " . $requestStatus);
        //send the message
        return $objMail->send();
    }

    /**
     * 
     * Method to convert the action parameter into the name of 
     * a method of this class.
     * 
     * @access private
     * @param string $action The action parameter passed byref
     * @return stromg the name of the method
     * 
     */
    function __getMethod(& $action) {
        if ($this->__validAction($action)) {
            return "__" . $action;
        } else {
            return "__actionError";
        }
    }

    /**
     *
     * This is a method to determine if the user has to 
     * be logged in or not. Note that this is an example, 
     * and if you use it view will be visible to non-logged in 
     * users. Delete it if you do not want to allow annonymous access.
     * It overides that in the parent class
     *
     * @return boolean TRUE|FALSE
     *
     */
    public function requiresLogin() {
        $action = $this->getParam('action', 'view');
        switch ($action) {
            case 'view':
                return TRUE;
                break;
            case 'pushRequest':
                return TRUE;
                break;
            case 'updateRequest':
                return TRUE;
                break;
            case 'addUser':
                return TRUE;
                break;
            default:
                return TRUE;
                break;
        }
    }
}
?>