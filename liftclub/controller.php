<?php
/* -------------------- liftclub class extends controller ----------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check
class liftclub extends controller
{
    /**
     * @var object $objConfig Config Object
     */
    public $objConfig;

    /**
     * @var object $objLanguage Language Object
     */
    public $objLanguage;

    /**
     * @var object $objUserAdmin User Administration \ Object
     */
    public $objUserAdmin;

    /**
     * @var object $objUser User Object Object
     */
    public $objUser;

    /**
     * Constructor
     */
    public function init() 
    {
        $this->objConfig = $this->getObject('altconfig', 'config');
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objUserAdmin = $this->getObject('useradmin_model2', 'security');
        $this->objUser = $this->getObject('user', 'security'); 
        $this->objUrl = $this->getObject('url', 'strings');
        $this->objDBCities = $this->getObject('dbliftclub_cities', 'liftclub');
        $this->objDBOrigin = $this->getObject('dbliftclub_origin', 'liftclub');
        $this->objDBDestiny = $this->getObject('dbliftclub_destiny', 'liftclub');
        $this->objDBDetails = $this->getObject('dbliftclub_details', 'liftclub');
    }
    /**
     * Method to turn off login requirement for all actions in this module
     */
    public function requiresLogin() 
    {
        return FALSE;
    }
    /**
     * Dispatch Method
     * @param string $action Action to be taken
     */
    public function dispatch($action) 
    {
        // Add login layout if page is displayed outside facebox.
        if (!$this->getParam('facebox')) {
            $this->setLayoutTemplate('login_layout_tpl.php');
        }

        $canRegister = ($this->objConfig->getItem('KEWL_ALLOW_SELFREGISTER') != strtoupper('FALSE'));
        if (!$canRegister) {
            //Disabling Registration
            return $this->showDisabledMessage();
        } else {
            switch ($action) {
                case 'showregister':
                default:
                    return $this->registrationHome();
                case 'confirm':
                    $id = $this->getParam('newId');
                    if (!empty($id)) {
                        $this->setSession('id', $id);
                        return $this->nextAction('detailssent');
                    }
                    return $this->nextAction('');
                case 'register':
                    return $this->saveNewUser();
                case 'detailssent':
                    return $this->detailsSent();
                case 'invitefriend':
                    $this->setLayoutTemplate(NULL);
                    return 'invite_tpl.php';
                    break;

                case 'sendinvite':
                    $fn = ucwords($this->getParam('friend_firstname'));
                    $sn = ucwords($this->getParam('friend_surname'));
                    $msg = $this->getParam('friend_msg');
                    $fe = $this->getParam('friend_email');
                    $userthing = $fn . "," . $sn . "," . $fe;
                    $code = base64_encode($userthing);
                    // construct the url
                    $url = $this->objConfig->getSiteRoot() . "index.php?module=userregistration&user=" . $code;
                    $msg = $msg . " <br />" . $url;
                    // bang off the email now
                    $objMailer = $this->getObject('email', 'mail');
                    $objMailer->setValue('to', array(
                        $fe
                    ));
                    $objMailer->setValue('from', $this->objConfig->getsiteEmail());
                    $objMailer->setValue('fromName', $this->objConfig->getSiteName() . " " . $this->objLanguage->languageText("mod_userregistration_emailfromname", "userregistration"));
                    $objMailer->setValue('subject', $this->objLanguage->languageText("mod_userregistration_emailsub", "userregistration") . " " . $this->objConfig->getSiteName());
                    $objMailer->setValue('body', strip_tags($msg));
                    $objMailer->send();
                    $this->nextAction('', array() , '_default');
                case 'jsongetcities':
                    //query coming from the ext lib. combobox auto complete. The post var is called query.
                    if (isset($_GET['query'])){
                     $city = $_GET['query'];
                     $start =  $_GET['start'];
                     $limit =  $_GET['limit'];
                    }else{
                     $city = $this->getParam('query');
                     $start = $this->getParam('start');
                     $limit = $this->getParam('limit');
                    }
                    $this->setLayoutTemplate(NULL);
                    $this->setVar('pageSuppressToolbar', TRUE);
                    $this->setVar('pageSuppressBanner', TRUE);
                    $this->setVar('pageSuppressSearch', TRUE);
                    $this->setVar('suppressFooter', TRUE);
                    //Get journal, journcatid
                    //$journalcat = $this->getParam('journalcat');
                    $myCities= $this->objDBCities->jsongetCities($city, $start, $limit);
                    echo $myCities;
                    exit(0);
                    break; 
            }
        }
    }
    /**
     * Method to show the Disabled Reg Message
     */
    protected function showDisabledMessage() 
    {
        $this->setVar('mode', 'add');
        return 'disabled_tpl.php';
    }
    /**
     * Method to show the registration page
     */
    protected function registrationHome() 
    {
        $userstring = $this->getParam('user');
        $this->setVar('userstring', $userstring);
        $this->setVar('mode', 'add');
        return 'registrationhome_tpl.php';
    }
    /**
     * Method to add a new user
     */
    protected function saveNewUser() 
    {
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
        //From (Home or Trip Origin)
        $streetname = $this->getParam('street_name');
        $suburb = $this->getParam('suburb');
        $citytown = $this->getParam('citytown');
        $province = $this->getParam('province'); 
        $neighbour = $this->getParam('neighbour');                       
        //To (Home or Trip Destination)  
        $institution = $this->getParam('institution');                       
        $streetname2 = $this->getParam('street_name2');
        $suburb2 = $this->getParam('suburb2');
        $citytown2 = $this->getParam('citytown2');
        $province2 = $this->getParam('province2'); 
        $neighbour2 = $this->getParam('neighbour2');                       
        //Trip Details
        $traveltimes = $this->getParam('traveltimes');
        $monday = $this->getParam('monday');
        $tuesday = $this->getParam('tuesday');
        $wednesday = $this->getParam('wednesday');
        $thursday = $this->getParam('thursday');
        $friday = $this->getParam('friday');
        $saturday = $this->getParam('saturday');
        $sunday = $this->getParam('sunday');
        $daysvary = $this->getParam('daysvary');
        $smoke = $this->getParam('smoke');
        //Additional Information
        $additionalinfo = $this->getParam('additionalinfo');
        $acceptoffers = $this->getParam('acceptoffers');
        //Account Settings
        $notifications = $this->getParam('notifications');
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
            $repeatpassword,
            $streetname,
            $suburb,
            $citytown,
            $streetname2,
            $suburb2,
            $citytown2,
            $traveltimes
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
        // Check for any problems with streetname
        if ($streetname == '') {
            $problems[] = 'nostreetnameentered';
        }
        // Check for any problems with suburb
        if ($suburb == '') {
            $problems[] = 'nosuburbentered';
        }
        // Check for any problems with citytown
        if ($citytown == '') {
            $problems[] = 'nocitytownentered';
        }
        // Check for any problems with streetname
        if ($streetname2 == '') {
            $problems[] = 'nostreetnameentered2';
        }
        // Check for any problems with suburb
        if ($suburb2 == '') {
            $problems[] = 'nosuburbentered2';
        }
        // Check for any problems with citytown
        if ($citytown2 == '') {
            $problems[] = 'nocitytownentered2';
        }
        // Check for any problems with travel times
        if ($traveltimes == '') {
            $problems[] = 'notraveltimesentered';
        }
        // Check for any problems with lift days
        if ($this->getParam('monday')=='' && $this->getParam('tuesday')=='' && $this->getParam('wednesday')=='' && $this->getParam('thursday')=="" && $this->getParam('friday')=="" && $this->getParam('saturday')=="" && $this->getParam('sunday')=="") {
            $problems[] = 'noliftdaysentered';
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
            return 'registrationhome_tpl.php';
        } else {

            // Else add to database
            $pkid = $this->objUserAdmin->addUser($userId, $username, $password, $title, $firstname, $surname, $email, $sex, $country, $cellnumber, $staffnumber, 'useradmin', $accountstatus);
            // Email Details to User
            $this->objUserAdmin->sendRegistrationMessage($pkid, $password);
            $userId = $this->objUser->getItemFromPkId($pkid,$field='userid');            
            $origin = $this->objDBOrigin->insertSingle($userId, $streetname, $suburb, $citytown, $province, $neighbour);
            $destiny = $this->objDBDestiny->insertSingle($userId, $institution, $streetname2, $suburb2, $citytown2, $province2, $neighbour2);
            $details = $this->objDBDetails->insertSingle($userId, $times, $additionalinfo, $acceptoffers, $notifications, $daysvary, $smoke, $monday, $tuesday, $wednesday, $thursday, $friday, $saturday, $sunday);
            $this->setSession('id', $pkid);
            //$this->setSession('password', $password);
            $this->setSession('time', $password);
            return $this->nextAction('detailssent');
        }
    }
    /**
     * Method to display the error messages/problems in the user registration
     * @param string $problem Problem Code
     * @return string Explanation of Problem
     */
    protected function explainProblemsInfo($problem) 
    {
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
            case 'nostreetnameentered':
                return 'No Street name was entered for (Home or Trip Origin)';            
            case 'nosuburbentered':
                return 'No Suburb was entered for (Home or Trip Origin)';            
            case 'nocitytownentered':
                return 'No City/Town was entered for (Home or Trip Origin)';            
            case 'nostreetnameentered2':
                return 'No Street name was entered for (Home or Trip Destination)';            
            case 'nosuburbentered2':
                return 'No Suburb was entered for (Home or Trip Destination)';            
            case 'nocitytownentered2':
                return 'No City/Town was entered for (Home or Trip Destination)';            
            case 'notraveltimesentered':
                return 'No Travel Time was entered';
            case 'noliftdaysentered':
                return 'No Day was selected for (Trip Details)';            
        }
    }
    /**
     * Method to check that all required fields are entered
     * @param array $checkFields List of fields to check
     * @return boolean Whether all fields are entered or not
     */
    private function checkFields($checkFields) 
    {
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
    protected function detailsSent() 
    {
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
