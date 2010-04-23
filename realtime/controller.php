<?php

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check
/**
 * Realtime Controller
 * This class controls all functionality to run the realtime module.
 * @package realtime
 * @author David Wafula
 * @version $Id$
 */
class realtime extends controller {
    /**
     * @var object $objUser: The user class in the security module
     * @access public
     */
    public $objUser;

    /**
     * @var string $userId: The user id of the currently logged in user
     * @access public
     */
    public $userId;

    /**
     * @var string $userName: The username of the currently logged in user
     * @access public
     */
    public $userName;

    /**
     * @var string $userLevel: The user's access level
     * @access public
     */
    public $userLevel;

    /**
     * @var object $objConfig: The altconfig class in the config module
     * @access public
     */
    public $objConfig;

    /**
     * @var object $objLog: The logactivity class in the logger module
     * @access public
     */
    public $objLog;


    /**
     * @access public
     * @var contexctcode
     */
    public $contextCode;

    /**
     * This points to module root path
     * @var <type>
     */
    public $moduleRootPath;

    /**
     * config object
     * @var <type>
     */
    public $objAltConfig;

    /**
     * Link object
     * @var <type>
     */
    public $objLink;

    /**
     * JOD doc converter path
     * @var <type>
     */
    public $jodconverterPath;

    /**
     * Files object
     * @var <type>
     */
    public $objFiles;

    /**
     *  convert obj
     * @var <type>
     */
    public $converter;

    /**
     * Upload path
     * @var <type>
     */
    public $uploadPath;
    /**
     *For starting the slide server
     * @var <type>
     */
    public  $realtimeManager;

    /**
     * link to requirements test
     * @var String
     */
    public $reqTest;

    /**
     *unique session id
     * @var String
     */
    public $sessionId;

    /**
     * This is the session title
     * @var String
     */
    public $sessionTitle;

    /**
     *Need a room, everything rotates around a room
     * @var <type>
     */
    public $room='default';


    /**
     * flag to shw whether we are starting a presentation or joining one
     * @var <type>
     */
    public $ispresenter='yes';

    /**
     * whether password is required for the meeting or not
     * @var <type>
     */

    public $passwordrequired='no';
    /**
     *unique id of jnlp generated
     * @var <type>
     */

    public $jnlpId="";
    /**
     *the id generated as short url
     * @var <type>
     */

    public $joinMeetingId="none";
    /**
     *Holds reference to class dbschedules used to manipulating entries
     * in tbl_virtualclassroon_schedules table
     * @var <type>
     */
    public $objDbSchedules;


    /**
     *holds reference to schedule members
     * @var <type>
     */
    public $objDbScheduleMembers;

    /**
     * the context obj
     */
    public $objContext;
    function init() {
        $this->objLink= $this->getObject('link', 'htmlelements');
        //Get configuration class
        $this->objConfig =$this->getObject('config','config');
        $this->objUserAdmin = $this->getObject('useradmin_model2', 'security');
        $this->objAltConfig = $this->getObject('altconfig','config');

        //Get language class
        $this->objLanguage = $this->getObject('language', 'language');

        $this->config = $this->getObject('config','config');
        $this->objStarter= $this->getObject('realtimestarter');
        $this->realtimeManager = $this->getObject('realtimemanager','webpresent');
        // classes we need
        $this->objUser = $this->newObject('user', 'security');
        $this->userId = $this->objUser->userId();
        $this->userName = $this->objUser->username($this->userId);
        if ($this->objUser->isAdmin()) {
            $this->userLevel = 'admin';
        }
        elseif ($this->objUser->isLecturer()) {
            $this->userLevel = 'lecturer';
        }
        elseif ($this->objUser->isStudent()) {
            $this->userLevel = 'student';
        } else {
            $this->userLevel = 'guest';
        }
        $this->objContext = $this->getObject('dbcontext', 'context');
        $this->objConfig = $this->getObject('altconfig', 'config');
        $location = "http://" . $_SERVER['HTTP_HOST'];

        $this->sessionId="default";
        $this->sessionTitle="Default Session";
        $this->jnlpId=$this->realtimeManager->randomString(20);
        $this->objUrl = $this->getObject('url', 'strings');
    }


    public function dispatch($action) {
        if(!$this->objContext->isInContext()) {
            return "needtojoin_tpl.php";
        }

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
     *Takes us to default home pages
     * @return <type>
     */
    function __home() {
        return "home_tpl.php";
    }

    function __showdetails() {
        $id=$this->getParam('id');
        $this->setVarByRef('sessionid',$id);
        return "sessionmembers_tpl.php";

    }
    function __deleteroommember() {
        $userid=$this->getParam('userid');
        $sessionid=$this->getParam('sessionid');
        $this->objDbScheduleMembers->deleteRoomMember($userid);
        $this->setVarByRef('sessionid',$sessionid);
        return "sessionmembers_tpl.php";

    }
    function __saveroommember() {
        $sessionid=$this->getParam('sessionid');
        $userid=$this->getParam('userfield');
        $this->objDbScheduleMembers->addRoomMember($userid,$sessionid);
        $this->setVarByRef('sessionid',$sessionid);
        return "sessionmembers_tpl.php";
    }

    function __deleteschedule() {
        $sessionid=$this->getParam('sessionid');
        $this->objDbSchedules->deleteSchedule($sessionid);
        $this->nextAction(NULL);
    }

    /**
     * Ssave the newly created  session details into the database
     */
    function __saveschedule() {
        $sessionTitle=$this->getParam('title');
        $date=$this->getParam('date');
        $starttime=$this->getParam('starttime');
        $endtime=$this->getParam('endtime');
        $type=$this->getParam('typefield');
        $this->objDbSchedules->addSchedule(
                $sessionTitle,
                $date,
                $starttime,
                $endtime,
                $type
        );
        $this->nextAction(NULL);
    }
    function __updatesession() {
        $sessionTitle=$this->getParam('title');
        $date=$this->getParam('date');
        $starttime=$this->getParam('starttime');
        $endtime=$this->getParam('endtime');
        $id=$this->getParam('sessionid');
        $type=$this->getParam('typefield');

        $this->objDbSchedules->updateSchedule(
                $sessionTitle,
                $date,
                $starttime,
                $endtime,
                $id,$type
        );
        $this->nextAction(NULL);
    }
    function __deletesession() {
        $id=$this->getParam('sessionid');
        $this->objDbSchedules->deleteSchedule($id);
        $this->objDbScheduleMembers->deleteSession($id);
        $this->nextAction(NULL);
    }
    function __registerexisting() {
        $sessionid=$this->getParam('sessionid');
        $userid=$this->objUser->userid();
        $this->objDbScheduleMembers->addRoomMember($userid,$sessionid);
        return $this->nextAction('home');
    }
    function __signinagain() {
        $this->objUser->logout();
        $sessionid=$this->getParam('sessionid');
        return  $this->nextAction('registerexisting',  array ('sessionid' => $sessionid ));
    }

    function __register() {
        return $this->saveNewUser($this->getParam('sessionid'));
    }
    public function requiresLogin($action) {

        /* $required = array('registerexisting','deleteroommember', 'deletesession', 'home', 'saveschedule', 'showdetails',  'updatesesion');

        if (in_array($action, $required)) {
            return TRUE;
        } else {
            return FALSE;
        }*/
        return TRUE;
    }

    function __showregister() {
        $id=$this->getParam('sessionid');
        $this->setVarByRef('sessionid',$id);
        return "registrationhome_tpl.php";
    }
    /**
     * Method to add a new user
     */
    protected function saveNewUser($sessionid) {
        if (!$_POST) { // Check that user has submitted a page

            return $this->nextAction(NULL);
        }
        // Generate User Id
        $userId = $this->objUserAdmin->generateUserId();
        // Capture all Submitted Fields
        $captcha = $this->getParam('request_captcha');
        $username = $this->getParam('register_username');
        $password = $this->getParam('register_password');
        $repeatpassword = $this->getParam('register_confirmpassword');
        $title = $this->getParam('register_title');
        $firstname = $this->getParam('register_firstname');
        $surname = $this->getParam('register_surname');
        $email = $this->getParam('register_email');
        $repeatemail = $this->getParam('register_confirmemail');
        $sex = $this->getParam('register_sex');
        $cellnumber = $this->getParam('register_cellnum');
        $staffnumber = $this->getParam('register_staffnum');
        $country = $this->getParam('country');
        $accountstatus = 1; // Default Status Active
        // Create an array of fields that cannot be empty
        $checkFields = array(
                $captcha,
                $username,
                $firstname,
                $surname,
                $email,
                $repeatemail,
                $password,
                $repeatpassword
        );
        // Create an Array to store problems
        $problems = array();
        // Check that username is available
        if ($this->objUserAdmin->userNameAvailable($username) == FALSE) {
            $problems[] = 'usernametaken';
        }
        //check that the email address is unique
        if ($this->objUserAdmin->emailAvailable($email) == FALSE) {
            $problems[] = 'emailtaken';
        }
        // Check for any problems with password
        if ($password == '') {
            $problems[] = 'nopasswordentered';
        } else if ($repeatpassword == '') {
            $problems[] = 'norepeatpasswordentered';
        } else if ($password != $repeatpassword) {
            $problems[] = 'passwordsdontmatch';
        }
        // Check that all required field are not empty
        if (!$this->checkFields($checkFields)) {
            $problems[] = 'missingfields';
        }
        // Check that email address is valid
        if (!$this->objUrl->isValidFormedEmailAddress($email)) {
            $problems[] = 'emailnotvalid';
        }
        // Check whether user matched captcha
        if (md5(strtoupper($captcha)) != $this->getParam('captcha')) {
            $problems[] = 'captchadoesntmatch';
        }
        // If there are problems, present from to user to fix
        if (count($problems) > 0) {
            $this->setVar('mode', 'addfixup');
            $this->setVarByRef('problems', $problems);
            $this->setVarByRef('sessionid',$sessionid);

            return 'registrationhome_tpl.php';
        } else {
            // Else add to database
            $pkid = $this->objUserAdmin->addUser($userId, $username, $password, $title, $firstname, $surname, $email, $sex, $country, $cellnumber, $staffnumber, 'useradmin', $accountstatus);
            // Email Details to User
            $this->objUserAdmin->sendRegistrationMessage($pkid, $password);
            $this->setSession('id', $pkid);
            //$this->setSession('password', $password);
            $this->setSession('time', $password);
            $this->objDbScheduleMembers->addRoomMember($userId,$sessionid);

            return $this->nextAction('home');
        }
    }
    /**
     * Method to display the error messages/problems in the user registration
     * @param string $problem Problem Code
     * @return string Explanation of Problem
     */
    protected function explainProblemsInfo($problem) {
        switch ($problem) {
            case 'usernametaken':
                return 'The username you have chosen has been taken already.';
            case 'emailtaken':
                return 'The supplied email address has been taken already.';
            case 'passwordsdontmatch':
                return 'The passwords you have entered do not match.';
            //case 'missingfields': return 'Some of the required fields are missing.';

            case 'emailnotvalid':
                return 'The email address you enter is not a valid format.';
            case 'captchadoesntmatch':
                return 'The image code you entered was incorrect';
            case 'nopasswordentered':
                return 'No password was entered';
            case 'norepeatpasswordentered':
                return 'No Repeat password was entered';
        }
    }
    /**
     * Method to check that all required fields are entered
     * @param array $checkFields List of fields to check
     * @return boolean Whether all fields are entered or not
     */
    private function checkFields($checkFields) {
        $allFieldsOk = TRUE;
        $this->messages = array();
        foreach($checkFields as $field) {
            if ($field == '') {
                $allFieldsOk = FALSE;
            }
        }
        return $allFieldsOk;
    }
    /**
     * Method to inform the user that their registration has been successful
     */
    protected function detailsSent() {
        $user = $this->objUserAdmin->getUserDetails($this->getSession('id'));
        if ($user == FALSE) {
            return $this->nextAction(NULL, NULL, '_default');
        } else {
            $this->setVarByRef('user', $user);
        }
        return 'confirm_tpl.php';
    }
}
?>
