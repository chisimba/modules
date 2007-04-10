<?php
/**
* hivaids class extends controller
* @package hivaids
* @filesource
*/

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* Controller class for hivaids module
* @author Megan Watson
* @copyright (c) 2007 UWC
* @version 0.1
*/

class hivaids extends controller
{
    /**
    * Method to construct the class.
    */
    public function init()
    {
        try{
            $this->hivTools = $this->getObject('hivaidstools', 'hivaids');
            $this->objUser = $this->getObject('user', 'security');
            $this->objUserAdmin = $this->getObject('useradmin_model2','security');
            
            //Get the activity logger class and log this module call
            $objLog = $this->getObject('logactivity', 'logger');
            $objLog->log();
        }catch(Exception $e){
            throw customException($e->message());
            exit();
        }
    }

    /**
    * Standard dispatch function
    *
    * @access public
    * @param string $action The action to be performed
    * @return string Template to be displayed
    */
    public function dispatch($action)
    {
        switch($action){
            case 'showregister':
                $display = $this->hivTools->showRegistration();
                $this->setVarByRef('display', $display);
                return 'home_tpl.php';
                
            case 'register':
                $id = $this->saveRegister();
                return $this->nextAction('confirm', array('newId' => $id), 'userregistration');
                
            default:
                $display = '';
                $this->setVarByRef('display', $display);
                return 'home_tpl.php';
        }
    }
    
    /**
    * Method to register the user on the site
    *
    * @access private
    */
    private function saveRegister()
    {
        $userId = $this->objUserAdmin->generateUserId();
        
        $username = $this->getParam('username');
        $password = $this->getParam('password');
        $password2 = $this->getParam('confirmpassword');
        $title = $this->getParam('title');
        $firstname = $this->getParam('firstname');
        $surname = $this->getParam('surname');
        $gender = $this->getParam('gender');
        $country = $this->getParam('country');
        $sports = $this->getParam('sports');
        $hobbies = $this->getParam('hobbies');
        
        // Check that username is available
        if ($this->objUserAdmin->userNameAvailable($username) == FALSE) {
            
        }
        
        $pkid = $this->objUserAdmin->addUser($userId, $username, $password, $title, $firstname, $surname, '', $gender, $country, '', '', 'useradmin', '1');
        
        return $pkid;
    }
    
    /**
    * Method to allow user to view the forum without being logged in
    *
    * @access public
    */
    public function requiresLogin()
    {
        return FALSE;
    }
} // end of controller class
?>