<?php
/* -------------------- sis class extends controller ----------------*/

// security check - must be included in all scripts
if (! $GLOBALS ['kewl_entry_point_run']) {
    die ( "You cannot view this page directly" );
}
// end security check


class sis extends controller {
    public $objConfig;
    public $objLanguage;
    public $objSISforms;

    /**
     * ye olde standard init()
     */
    public function init() {
        $this->objConfig = $this->getObject ( 'altconfig', 'config' );
        $this->objLanguage = $this->getObject ( 'language', 'language' );
        $this->objSISforms = $this->getObject ( 'studentforms', 'sis' );
        $this->objFMPro = $this->getObject ( 'fmpro', 'filemakerpro' );
        $this->objUser = $this->getObject('user', 'security');
    }

    /**
     *
     *
     */
    public function dispatch($action) {
        switch ($action) {
            case "showprofile" :
                return $this->myProfile ();
            case "updateprofile" :
                $ln = $this->getParam('lastname', '');
                $fn = $this->getParam('firstname', '');
                $mn = $this->getParam('midname', '');
                $oc = $this->getParam('occupation', '');
                $em = $this->getParam('employer', '');
                $un = $this->getParam('username');
                // address details
                $street = $this->getParam('street', '');
                $city = $this->getParam('city');
                $state = $this->getParam('street');
                $zip = $this->getParam('zip', '');
                // email details
                $email = $this->getParam('email');
                $emailpriv = $this->getParam('emailpriv');
                if($emailpriv == 'on') {
                    $emailpriv = 1;
                }
                else {
                    $emailpriv = 0;
                }
                // phone details
                $hphone = $this->getParam('hphone');
                $cphone = $this->getParam('cphone');
                $cellpriv = $this->getParam('cellpriv');
                if($cellpriv == 'on') {
                    $cellpriv = 1;
                }
                else {
                    $cellpriv = 0;
                }
                $wphone = $this->getParam('wphone');
                // record id
                $recid = $this->getParam('recid');
                // check that the user doing the update has rights to do so
                // First lets get the correct users info
                $formid = $this->objFMPro->getUsersIdByUsername($un);
                $userid =  $this->objUser->userId();
                if($formid === $userid) {
                    // build up an array of data to update with
                    $values = array('UserName'=> $un,
                                    'LastName' => $ln,
                                    'FirstName' => $fn,
                                    'Email' => $email,
                                    'IsEmailPrivate' => $emailpriv,
                                    'Address' => $street,
                                    'Cell' => $cphone,
                                    'City' => $city,
                                    'Employer' => $em,
                                    'Occupation' => $oc,
                                    'State' => $state,
                                    'Zip' => $zip,
                                    'HomePhone' => $hphone,
                                    'WorkPhone' => $wphone,
                                    'IsCellPrivate' => $cellpriv
                    );

                    $layoutName = 'Form: Person';
                    $res = $this->objFMPro->editRecord($layoutName, $recid, $values);
                    // check the results and take action
                    if($res === TRUE) {
                        // Create a message for the user.
                        $message = $this->objLanguage->languageText("mod_sis_update_success", "sis");
                        $userdetails = array('firstname' => $fn,  'lastname' => $ln);
                        // We need to send a mail or other notification to "the staff" to tell them something is new...
                        $mailres = $this->objFMPro->sendStaffMail($userdetails);

                        // return to the main screen
                        $this->nextAction(NULL, array('message' => $message));
                    }
                    else {
                        // TODO: add in a template here
                        echo "You have a mess on your hands...";
                    }

                }
                else {
                    echo "booboo!";

                }
                die();
                //var_dump($this->objFMPro->getUserProfile ()); die();


                return $this->updateProfile ();
            case 'newstudent' :
                return $this->showStudent ();
            case 'savestudent' :
                return $this->saveStudent ();
            default :
                $message = $this->getParam('message');
                if($message == '') {
                    $message = NULL;
                }
                $this->setVarByRef('message', $message);
                return "default_tpl.php";
        }
    }

    /**

     **
     * Calls classes to get form info
     * @returns string $template
     */
    public function showStudent() {
        $this->string = $this->objSISforms->studentInput ();
        return "default_tpl.php";
    }

    /**
     * Calls classes to save form info
     * @returns string $template
     */
    public function saveStudent() {
        $paramNames = array ();
        $paramvals = array ();
        foreach ( $paramNames as $line ) {
            $paramvals [$line] = $this->getParam ( $line );
        }

        return "default_tpl.php";
    }

    private function myProfile() {
        $profile = $this->objFMPro->getUserProfile ();
        //var_dump($profile[0]->_impl->_relatedSets);
        $this->setVarByRef ( 'profile', $profile );
        //var_dump ( $profile );
        return "profile_tpl.php";
    }

    private function updateprofile() {
        // update the profile by catching all the details then sending to FMP in this case

    }

}

?>
