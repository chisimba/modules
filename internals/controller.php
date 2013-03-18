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
        $date = new DateTime();
        $date->modify('+2 day');
        $startDate = "2013-02-10";
        $endDate = "2013-02-15";
        $startMonth = substr($startDate, '5', '2');
        $startDay = substr($startDate, '8','2');
        $endMonth = substr($endDate, '5','2');
        $endDay = substr($endDate, '8','2');
        echo $startMonth.'<br/>'.$startDay;
        echo "<br/>".$endMonth."<br/>".$endDay;
        //weekend checker
        if(strpos($date->format('Y-M-D'), 'Tue') == TRUE){
//            checkdate(, $day, $year)$startDate
        }
        //holidays
        $holidays = array(
            date('Y').'-01-01',
            date('Y').'-02-01',
            date('Y').'-03-21',
            date('Y').'-04-06',
            date('Y').'-04-27',
            date('Y').'-05-27',
            date('Y').'-06-16',
            date('Y').'-08-09',
            date('Y').'-09-24',
            date('Y').'-10-24',
            date('Y').'-12-17',
            date('Y').'-12-17',
            date('Y').'-12-25',
            date('Y').'-12-26'
        );
        //get database object
        $objDB = $this->getObject('dbinternals', 'internals');
        $objUser = $this->getObject('user', 'security');
        //get user id
        $userId = $objUser->getUserId($objUser->userName());
        //leave name
        $leaveName = $this->getParam('leaveName', 'annual');
        //change the leave name to ID
        $leaveID = $objDB->getLeaveID($leaveName);
        //start date
        $startDate = $this->getParam('startdate', NULL);
        //end date
        $endDate = $this->getParam('enddate', NULL);
        
        //insert the data into the database
//        $objDB->postRequest($userId, $leaveID, $startDate, $endDate);
//        die();
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
        if ($requestStatus == 'reject') {
            if (!empty($comments)) {
                $statement = 'Reason being ' . $comments;
            } else {
                $statement = 'No reason was provided';
            }
        }
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
        $objMail->setValue('body', "{$userFullName} your leave request has been " . $requestStatus . '<br/>' . $statement);
        $objMail->setValue('htmlbody', "{$userFullName}your leave request has been " . $requestStatus . '<br/>' . $statement);
        //send the message
        return $objMail->send();
    }

    public function __addLeavetype() {
        $objDB = $this->getObject('dbinternals', 'internals');
        $leaveName = $this->getParam('leavename');
        $numberOfDays = $this->getParam('numberofdays');
        $objDB->addLeaveType($leaveName, $numberOfDays);
//        echo $this->getParam('numberofdays').'<br />'.$this->getParam('leavename');
//        header('location:index.php');
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