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
            $this->objConfig = $this->getObject('altconfig', 'config');
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
                
            case 'playyourmoves':
                $check = $this->inIpRange();
                if($check){
                    $skinroot = $this->objConfig->getskinRoot().'uwchivaids/';
                    $this->setVarByRef('skin', $skinroot);
                    return 'game_tpl.php';
                }
                $display = $this->notAllowed();
                $this->setVarByRef('display', $display);
                return 'home_tpl.php';
                
            default:
                $display = $this->hivTools->showManagement();
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
            return $this->nextAction('showregister');
        }
        
        $pkid = $this->objUserAdmin->addUser($userId, $username, $password, $title, $firstname, $surname, '', $gender, $country, '', '', 'useradmin', '1');
        
        return $pkid;
    }
    
    /**
    * Method to check if the users IP is within the allowed IP range
    *
    * @access private
    * @return bool
    */
    private function inIpRange()
    {
        // Proxy: 192.102.x
        // Intranet: 172.16.x
        $ipRange = '172.16.';
        
        $ip = $_SERVER['REMOTE_ADDR'];
        $pos = strpos($ip, $ipRange, 0);
                
        if($pos === FALSE){
            return FALSE;
        }
        return TRUE;
    }
    
    /**
    * Method to display a message if the user is outside the IP range
    *
    * @access private
    * @return string html
    */
    private function notAllowed()
    {
        $objLanguage = $this->getObject('language', 'language');
        $institution = $this->objConfig->getinstitutionName();
        $arr = array('institution' => $institution);
        $msg = $objLanguage->code2Txt('mod_hivaids_notallowedplaygame', 'hivaids', $arr);
                
        $str = '<p class="noRecordsMessage error">'.$msg.'</p>';
        return $str;
    }
    
    /**
    * Method to allow user to view the forum without being logged in
    *
    * @access public
    */
    public function requiresLogin($action)
    {
        switch($action){
            case 'showregister':
            case 'register':
            case 'playyourmoves':
                return FALSE;
        }
        return TRUE;
    }
} // end of controller class
?>