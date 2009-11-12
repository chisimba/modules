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
        $this->objLiftSearch = $this->getObject('search_liftclub', 'liftclub');
        $this->objFavourites = $this->getObject('dbliftclub_favourites', 'liftclub');
        $this->objMessages = $this->getObject('dbliftclub_messages', 'liftclub');
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
                case 'liftclubhome':
                default:
                    return $this->liftclubHome();
                case 'startregister':
                    return 'registrationstart_tpl.php';
                case 'findlift':
                    return 'liftclubfind_tpl.php';
                case 'offeredlifts':
                    return 'liftcluboffer_tpl.php'; 
                case 'messages':
                    return 'messages_tpl.php'; 
                case 'showregister':
                    return $this->registrationHome();
                case 'modifydetails':
                    return $this->modifyRegistration();
                case 'viewlift':
                    return $this->viewMembership();
                case 'confirm':
                    $id = $this->getParam('newId');
                    if (!empty($id)) {
                        $this->setSession('id', $id);
                        return $this->nextAction('detailssent');
                    }
                    return $this->nextAction('');
                case 'register':
                    return $this->saveNewUser();
                case 'updateregister':
                    return $this->updateUser();
                case 'sendmessage':
                    return $this->sendMessage();
                case 'addfavourite':
                    return $this->addFavourite();
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
                case 'jsongetlifts':
                    $userneed = $this->getParam('userneed');
                    $start = $this->getParam('start');
                    $limit = $this->getParam('limit');
                    
                    $this->setLayoutTemplate(NULL);
                    $this->setVar('pageSuppressToolbar', TRUE);
                    $this->setVar('pageSuppressBanner', TRUE);
                    $this->setVar('pageSuppressSearch', TRUE);
                    $this->setVar('suppressFooter', TRUE);
                    $lifts= $this->objLiftSearch->jsonLiftSearch($userneed, $start, $limit);
                    echo $lifts;
                    exit(0);
                    break;
                case 'json_getallmessages':
                    $userneed = $this->getParam('userneed');
                    $start = $this->getParam('start');
                    $limit = $this->getParam('limit');
                    
                    $this->setLayoutTemplate(NULL);
                    $this->setVar('pageSuppressToolbar', TRUE);
                    $this->setVar('pageSuppressBanner', TRUE);
                    $this->setVar('pageSuppressSearch', TRUE);
                    $this->setVar('suppressFooter', TRUE);
                    $lifts= $this->objLiftSearch->jsonGetMessages($start, $limit, $read=NULL, $trash=NULL);
                    echo $lifts;
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
        $userneed = $this->getParam('userneed');
        $this->setSession('userneed', $userneed);
        $needtype = $this->getParam('needtype');
        $this->setSession('needtype', $needtype);
        $this->setVar('userstring', $userstring);
        $this->setVar('mode', 'add');
        $this->setVar('userneed', $userneed);
        $this->setVar('needtype', $needtype);       
        return 'registrationhome_tpl.php';
    }
    /**
     * Method to show the registration page
     **/
    protected function modifyRegistration() 
    {
        $userInfo = $this->objUserAdmin->getUserDetails($this->objUser->PKId($this->objUser->userId()));
        $userOrigin = $this->objDBOrigin->userOrigin($this->objUser->userId());
        $userDestiny = $this->objDBDestiny->userDestiny($this->objUser->userId());
        $userDetails = $this->objDBDetails->userDetails($this->objUser->userId());
        /*var_dump($userInfo);
        echo '<br />'; 
        var_dump($userOrigin);
        echo '<br />';
        var_dump($userDestiny);
        echo '<br />';
        var_dump($userDetails);
        exit;*/        
        $userstring = $this->getParam('user');
        $userneed = $userDetails[0]["needtype"];
        $this->setSession('userneed', $userneed);
        $needtype = $userDetails[0]["needtype"];
        $this->setSession('needtype', $needtype);
        $this->setVar('userstring', $userstring);
        $this->setVar('mode', 'add');
        $this->setVar('userneed', $userneed);
        $this->setVar('needtype', $needtype);
        $this->setVar('id',$userInfo['id']);
        $this->setVar('register_username',$userInfo['username']);
        $this->setVar('register_title',$userInfo['title']);
        $this->setVar('register_firstname',$userInfo['firstname']);
        $this->setVar('register_surname',$userInfo['surname']);
        $this->setVar('register_staffnum',$userInfo['staffnumber']);
        $this->setVar('register_cellnum',$userInfo['cellnumber']);
        $this->setVar('register_sex',$userInfo['sex']);
        $this->setVar('country',$userInfo['country']);
        $this->setVar('register_email',$userInfo['emailaddress']);
        $this->setVar('originid',$userOrigin[0]['id']);
        $this->setVar('street_name',$userOrigin[0]['street']);
        $this->setVar('suburborigin',$userOrigin[0]['suburb']);
        $this->setVar('citytownorigin',$userOrigin[0]['city']);
        $this->setVar('province',$userOrigin[0]['province']);
        $this->setVar('neighbourorigin',$userOrigin[0]['neighbour']);
        $this->setVar('destinyid',$userDestiny[0]['id']);
        $this->setVar('destinstitution',$userDestiny[0]['institution']);
        $this->setVar('deststreetname',$userDestiny[0]['street']);
        $this->setVar('destsuburb',$userDestiny[0]['suburb']);
        $this->setVar('destcity',$userDestiny[0]['city']);
        $this->setVar('destprovince',$userDestiny[0]['province']);
        $this->setVar('destneighbour',$userDestiny[0]['neighbour']);
        $this->setVar('detailsid',$userDetails[0]['id']);
        $this->setVar('tripdaterequired',$userDetails[0]['daterequired']);
        $this->setVar('triptimes',$userDetails[0]['times']);
        $this->setVar('tripsmoke',$userDetails[0]['smoke']);
        $this->setVar('tripadditionalinfo',$userDetails[0]['additionalinfo']);
        $this->setVar('tripacceptoffers',$userDetails[0]['specialoffer']);
        $this->setVar('tripemailnotifications',$userDetails[0]['emailnotifications']);
        $this->setVar('daymon',$userDetails[0]['monday']);
        $this->setVar('daytues',$userDetails[0]['tuesday']);
        $this->setVar('daywednes',$userDetails[0]['wednesday']);
        $this->setVar('daythurs',$userDetails[0]['thursday']);
        $this->setVar('dayfri',$userDetails[0]['friday']);
        $this->setVar('daysatur',$userDetails[0]['saturday']);
        $this->setVar('daysun',$userDetails[0]['sunday']);
        $this->setVar('varydays',$userDetails[0]['daysvary']);
        return 'modifyregistration_tpl.php';
    }
    /**
     * Method to show the registration page
     **/
    protected function viewMembership() 
    {
        $userid = $this->getParam('liftuserid');
        $userInfo = $this->objUserAdmin->getUserDetails($this->objUser->PKId($userid));
        $userOrigin = $this->objDBOrigin->userOrigin($userid);
        $userDestiny = $this->objDBDestiny->userDestiny($userid);
        $userDetails = $this->objDBDetails->userDetails($userid);
        /*var_dump($userInfo);
        echo '<br />'; 
        var_dump($userOrigin);
        echo '<br />';
        var_dump($userDestiny);
        echo '<br />';
        var_dump($userDetails);
        exit;*/        
        $userstring = $this->getParam('user');
        $userneed = $userDetails[0]["userneed"];
        $this->setSession('userneed', $userneed);
        $needtype = $userDetails[0]["needtype"];
        $this->setSession('needtype', $needtype);
        $this->setVar('userstring', $userstring);
        $this->setVar('mode', 'add');
        $this->setVar('userneed', $userneed);
        $this->setVar('needtype', $needtype);
        $this->setVar('id',$userInfo['id']);
        $this->setVar('register_username',$userInfo['username']);
        $this->setVar('register_title',$userInfo['title']);
        $this->setVar('register_firstname',$userInfo['firstname']);
        $this->setVar('register_surname',$userInfo['surname']);
        $this->setVar('register_staffnum',$userInfo['staffnumber']);
        $this->setVar('register_cellnum',$userInfo['cellnumber']);
        $this->setVar('register_sex',$userInfo['sex']);
        $this->setVar('country',$userInfo['country']);
        $this->setVar('register_email',$userInfo['emailaddress']);
        $this->setVar('originid',$userOrigin[0]['id']);
        $this->setVar('street_name',$userOrigin[0]['street']);
        $this->setVar('suburborigin',$userOrigin[0]['suburb']);
        $this->setVar('citytownorigin',$userOrigin[0]['city']);
        $this->setVar('province',$userOrigin[0]['province']);
        $this->setVar('neighbourorigin',$userOrigin[0]['neighbour']);
        $this->setVar('destinyid',$userDestiny[0]['id']);
        $this->setVar('destinstitution',$userDestiny[0]['institution']);
        $this->setVar('deststreetname',$userDestiny[0]['street']);
        $this->setVar('destsuburb',$userDestiny[0]['suburb']);
        $this->setVar('destcity',$userDestiny[0]['city']);
        $this->setVar('destprovince',$userDestiny[0]['province']);
        $this->setVar('destneighbour',$userDestiny[0]['neighbour']);
        $this->setVar('detailsid',$userDetails[0]['id']);
        $this->setVar('tripdaterequired',$userDetails[0]['daterequired']);
        $this->setVar('triptimes',$userDetails[0]['times']);
        $this->setVar('tripsmoke',$userDetails[0]['smoke']);
        $this->setVar('tripadditionalinfo',$userDetails[0]['additionalinfo']);
        $this->setVar('tripacceptoffers',$userDetails[0]['specialoffer']);
        $this->setVar('tripemailnotifications',$userDetails[0]['emailnotifications']);
        $this->setVar('daymon',$userDetails[0]['monday']);
        $this->setVar('daytues',$userDetails[0]['tuesday']);
        $this->setVar('daywednes',$userDetails[0]['wednesday']);
        $this->setVar('daythurs',$userDetails[0]['thursday']);
        $this->setVar('dayfri',$userDetails[0]['friday']);
        $this->setVar('daysatur',$userDetails[0]['saturday']);
        $this->setVar('daysun',$userDetails[0]['sunday']);
        $this->setVar('varydays',$userDetails[0]['daysvary']);
        return 'viewmembership_tpl.php';
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
        $needtype = $this->getSession('needtype');
        $userneed = $this->getSession('userneed');     

        if($this->getSession('needtype')!=='Trip'){
         $daterequired = null;
		       $traveltimes = $this->getParam('traveltimes');
		       $monday = $this->getParam('monday');
		       $tuesday = $this->getParam('tuesday');
		       $wednesday = $this->getParam('wednesday');
		       $thursday = $this->getParam('thursday');
		       $friday = $this->getParam('friday');
		       $saturday = $this->getParam('saturday');
		       $sunday = $this->getParam('sunday');
		       $daysvary = $this->getParam('daysvary');
        }else{
         $daterequired =$this->getParam('daterequired');
		       $traveltimes = null;
		       $monday = null;
		       $tuesday = null;
		       $wednesday = null;
		       $thursday = null;
		       $friday = null;
		       $saturday = null;
		       $sunday = null;
		       $daysvary = null;
        }
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
        if($this->getSession('needtype')!=='Trip'){
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
            $details = $this->objDBDetails->insertSingle($userId, $traveltimes, $additionalinfo, $acceptoffers, $notifications, $daysvary, $smoke, $userneed, $needtype, $daterequired, $monday, $tuesday, $wednesday, $thursday, $friday, $saturday, $sunday);
            $this->setSession('id', $pkid);
            //$this->setSession('password', $password);
            $this->setSession('time', $password);
            return $this->nextAction('detailssent');
        }
    }
    /**
     * Method to add favourites
     */
    protected function addFavourite(){
        $this->setPageTemplate(NULL);
        $this->setLayoutTemplate(NULL);
        // Capture all Submitted Fields
        $userid = $this->objUser->userId();
        $favusrid = $this->getParam('favusrid');
        if(!empty($userid) && !empty($favusrid)){
         $recexists = $this->objFavourites->checkIfExists($userid, $favusrid);
         if($recexists==TRUE){
          echo 'exists';
         }else{
          $id = $this->objFavourites->insertSingle($userid, $favusrid);
          echo 'ok';
         }
        }elseif(!empty($userid)){
         echo 'notok';
        }else{
         echo 'notlogged';
        }
    }
    /**
     * Method to send a message
     */
    protected function sendMessage(){
        $this->setPageTemplate(NULL);
        $this->setLayoutTemplate(NULL);
        // Capture all Submitted Fields
        $userid = $this->objUser->userId();
        $favusrid = $this->getParam('favusrid');
        $msgtitle = $this->getParam('msgtitle');
        $msgbody = $this->getParam('msgbody');
        if(!empty($userid) && !empty($favusrid) && !empty($msgtitle) && !empty($msgbody)){
         $sendmsg = $this->objMessages->insertSingle($userid, $favusrid, $msgtitle, $msgbody);
         echo 'ok';
        }elseif(empty($userid)){
         echo 'notlogged';
        }else{
         echo 'notok';
        }
    }
    /**
     * Method to update user information
     */
    protected function updateUser() 
    {
        if (!$_POST) { // Check that user has submitted a page
            return $this->nextAction(NULL);
        }
        // Generate User Id
        $userId = $this->objUserAdmin->generateUserId();
        // Capture all Submitted Fields
        $id = $this->getParam('id');
        $originid = $this->getParam('originid');
        $destinyid = $this->getParam('destinyid');
        $detailsid = $this->getParam('detailsid');
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
        $needtype = $this->getSession('needtype');
        $userneed = $this->getSession('userneed');     

        if($this->getSession('needtype')!=='Trip'){
         $daterequired = null;
		       $traveltimes = $this->getParam('traveltimes');
		       $monday = $this->getParam('monday');
		       $tuesday = $this->getParam('tuesday');
		       $wednesday = $this->getParam('wednesday');
		       $thursday = $this->getParam('thursday');
		       $friday = $this->getParam('friday');
		       $saturday = $this->getParam('saturday');
		       $sunday = $this->getParam('sunday');
		       $daysvary = $this->getParam('daysvary');
        }else{
         $daterequired =$this->getParam('daterequired');
		       $traveltimes = null;
		       $monday = null;
		       $tuesday = null;
		       $wednesday = null;
		       $thursday = null;
		       $friday = null;
		       $saturday = null;
		       $sunday = null;
		       $daysvary = null;
        }
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
        // Check for any problems with password
        if ($password !== '') {
         if ($password != $repeatpassword)
            $problems[] = 'passwordsdontmatch';
        }        
        // Check for any problems with travel times
        if($this->getSession('needtype')!=='Trip'){
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
            $pkid = $this->objUserAdmin->updateUserDetails($id,$username, $firstname, $surname, $title, $email, $sex, $country, $cellnumber, $staffnumber, $password, $accountType='', $accountstatus); 
            $origin = $this->objDBOrigin->updateSingle($originid, $streetname, $suburb, $citytown, $province, $neighbour);
            $destiny = $this->objDBDestiny->updateSingle($destinyid, $institution, $streetname2, $suburb2, $citytown2, $province2, $neighbour2);
            $details = $this->objDBDetails->updateSingle($detailsid, $traveltimes, $additionalinfo, $acceptoffers, $notifications, $daysvary, $smoke, $userneed, $needtype, $daterequired, $monday, $tuesday, $wednesday, $thursday, $friday, $saturday, $sunday);
            $this->setSession('id', $pkid);
            //$this->setSession('password', $password);
            $this->setSession('time', $password);
            return $this->nextAction('liftclubhome');
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
    /**
     * Method to inform the user that their registration has been successful
     */
    protected function liftclubHome() 
    {
        $user = $this->objUserAdmin->getUserDetails($this->objUser->userId());
        if ($user !== FALSE) {
            $this->setVarByRef('user', $user);
        }
        return 'liftclubhome_tpl.php';
    }

}
?>
