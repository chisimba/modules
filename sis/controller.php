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
                // Just shows the users profile with an update form
                $profile = $this->objFMPro->getUserProfile ();
                $this->setVarByRef ( 'profile', $profile );

                // check for a message
                $message = $this->getParam('message');
                if($message == '' ) {
                    $message = NULL;
                }
                $this->setVarByRef('message', $message);

                return "profile_tpl.php";
                break;

            case "updateprofile" :
                // Action to catch and process updates
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

                // Some validation to check all required fields exist
                if ($ln == '' || $fn == '' || $un == '' || $street == '' || $city == '' || $state == '' || $zip == '' || $email == '' || $hphone == '' || $wphone == '' ) {
                    $message = $this->objLanguage->languageText("mod_sis_reqfieldsmissing", "sis");
                    $this->nextAction('showprofile', array('message' => $message));
                    break;
                }
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
                        return "error_tpl.php";
                        break;
                    }

                }
                else {
                    return "error_tpl.php";
                    break;
                }
                break;

            case 'newstudent' :
                return $this->showStudent ();
                break;

            case 'savestudent' :
                return $this->saveStudent ();
                break;

            default :
                $message = $this->getParam('message');
                if($message == '') {
                    $message = NULL;
                }
                $this->setVarByRef('message', $message);
                return "default_tpl.php";
                break;
        }
    }
}

?>