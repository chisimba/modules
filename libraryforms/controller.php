<?php

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check
// end security check

/**
 *
 * libraryforms
 *
 * libraryforms allows students or distant user to request books online
 *
 * @category  Chisimba
 * @package   libraryforms
 * @author    Brenda Mayinga brendamayinga@ymail.com
 * */
class libraryforms extends controller {

    public $objLanguage;
    protected $objMail;

    public function init() {

        //Instantiate the language object
        $this->objLanguage = $this->getObject('language', 'language');

        // Instantiate the class
        $this->dbAddDistances = $this->getObject('editform', 'libraryforms');
        $this->dbAddBookthesis = $this->getObject('bookthesis', 'libraryforms');
        $this->dbAddillperiodical = $this->getObject('illperiodical', 'libraryforms');
        $this->dbfeedback = $this->getObject('feedbk', 'libraryforms');
        $this->objUser = $this->getObject('User', 'security');
        // Get a local reference to the mail
        $this->objMail = $this->getObject('email', 'mail');
    }

//end of function

    public function dispatch($action) {

        //$action = $this->getParam('action');
        // $this->setLayoutTemplate('editadd_tpl.php');

        switch ($action) {

            default:
                return "editadd_tpl.php";

            case 'addeditform':
                return $this->saveRecord();


            case 'addthesis':
                return $this->saveBookthesisRecord();


            case 'addperiodical':
                return $this->saveperiodicalRecord();


            case 'addfeedbk':
                return $this->submitmsg();

            case 'save_addedit':
                $this->saveRecord();
                return 'confirm_tpl.php';

            case 'save_book':
                $this->saveBookthesisRecord();
                return 'confirm_tpl.php';


            case 'save_periodical':
                $this->saveperiodicalRecord();
                return 'confirm_tpl.php';

            case 'save_fdbk':

                $this->submitmsg();
                return 'fdbkconfirm_tpl.php';

            case 'Back to Forms':
                return 'editadd_tpl.php';
        }// close for switch
    }

//end of function dispatch



    /*
     * Public Method that checks if all required fields are filled
     * If fields are filled, and inserts data into db table, else returns error
     */

    public function saveRecord() {
        if (!$_POST) { // Check that user has submitted a page
            return $this->nextAction(NULL);
        }
        $surname = $this->getParam('surname');
        $initials = $this->getParam('initials');
        $title = $this->getParam('title');
        $studentno = $this->getParam('studentno');
        $postaladdress = $this->getParam('postaladdress');
        $physicaladdress = $this->getParam('physicaladdress');
        $postalcode = $this->getParam('postalcode');
        $postalcode2 = $this->getParam('postalcode2');
        $telnoh = $this->getParam('telnoh');
        $telnow = $this->getParam('telnow');
        $cell = $this->getParam('cell');
        $fax = $this->getParam('fax');
        $emailaddress = $this->getParam('register_email');
        $course = $this->getParam('course');
        $department = $this->getParam('department');
        $supervisor = $this->getParam('supervisor');
        $captcha = $this->getParam('editformrequest_captcha');

        // Check whether user matched captcha
        if (md5(strtoupper($captcha)) != $this->getParam('captcha') || empty($captcha)) {
            $msg[] = 'badcaptcha';
        }

        //if form entry is in corect or invavalid
        if (count($msg) > 0) {
            $this->setVarByRef('msg', $msg);
            $this->setVarByRef('insarr', $insarr);
            return 'editadd_tpl.php';
        }
        // insert into database
        $pid = $this->dbAddDistances->insertRecord($surname, $initials, $title, $studentno, $postaladdress,
                        $physicaladdress, $postalcode, $postalcode2, $telnoh,
                        $telnow, $cell, $fax, $emailaddress, $course, $department, $supervisor);


        // send email alert
        $subject = "New user registered";

        $this->sendEmailNotification($subject,
                $message = $surname . ' ' . $initials . ' ' . $title . ' ' . $studentno . ' ' . $postaladdress . ' ' .
                $physicaladdress . ' ' . $postalcode . ' ' . $postalcode2 . ' ' . $telnoh . ' ' . $telnow . ' ' .
                $cell . ' ' . $fax . ' ' . $emailaddress . ' ' . $course . ' ' . $department . ' ' . $supervisor);
    }

// end of Save Records */

    function saveBookthesisRecord() {

        $author = $this->getParam('aut');
        $title = $this->getParam('thesis_titles');
        $place = $this->getParam('thesis_place');
        $publisher = $this->getParam('thesis_publisher');
        $date = $this->getParam('year');
        $edition = $this->getParam('edition');
        $isbn = $this->getParam('ISBN');
        $series = $this->getParam('series');
        $copy = $this->getParam('photocopy');
        $titlepages = $this->getParam('titles');
        $pages = $this->getParam('pages');
        $thesis = $this->getParam('thesis');
        $name = $this->getParam('thesis_prof');
        $address = $this->getParam('thesis_address');
        $cell = $this->getParam('thesis_cell');
        $fax = $this->getParam('fax');
        $tel = $this->getParam('thesis_tel');
        $telw = $this->getParam('thesis_w');
        $emailaddress = $this->getParam('thesis_email');
        $entitynum = $this->getParam('entity');
        $studentno = $this->getParam('thesis_studentno');
        $course = $this->getParam('thesis_course');
        $captcha = $this->getParam('thesis_captcha');

        // Check whether user matched captcha
        if (md5(strtoupper($captcha)) != $this->getParam('captcha') || empty($captcha)) {
            $erormsg [ ] = 'badcaptcha';
        }
        //if form entry is in corect or invavalid
        if (count($erormsg) > 0) {
            $this->setVarByRef('erormsg', $erormsg);
            $this->setVarByRef('insarr', $insarr);
            return 'editadd_tpl.php';
        }

        //insert into DB
        $id = $this->dbAddBookthesis->insertBookthesisRecord($author, $title, $place, $publisher, $date,
                        $edition, $isbn, $series, $copy, $titlepages, $pages, $thesis,
                        $name, $address, $cell, $fax, $tel, $telw, $emailaddress,
                        $entitynum, $studentno, $course);

// after inserting into db send email alert
        $subject = "Book thesis record";
        $this->sendEmailNotification($subject,
                $message = $author . ' ' . $title . ' ' . $place . ' ' . $publisher . ' ' .
                $date . ' ' . $edition . ' ' . $isbn . ' ' . $series . ' ' . $copy . ' ' . $titlepages . ' ' .
                $pages . ' ' . $thesis . ' ' . $name . ' ' . $address . ' ' . $cell . ' ' . $fax . ' ' .
                $tel . ' ' . $telw . ' ' . $emailaddress . ' ' . $entitynum . ' ' . $studentno . ' ' . $course);
    }

// end of bookthesisrecord

    public function saveperiodicalRecord() {

        $titleperiodical = $this->getParam('titleperiodical');
        $volume = $this->getParam('period_volume');
        $part = $this->getParam('period_part');
        $year = $this->getParam('period_year');
        $pages = $this->getParam('period_pages');
        $author = $this->getParam('period_author');
        $titlearticle = $this->getParam('periodical_titlearticle');
        $prof = $this->getParam('periodical_prof');
        $address = $this->getParam('periodical_address');
        $cell = $this->getParam('period_cell');
        $tell = $this->getParam('periodical_tell');
        $tellw = $this->getParam('periodical_w');
        $emailaddress = $this->getParam('periodicalemail');
        $bentitynum = $this->getParam('periodical_entity');
        $bstudentno = $this->getParam('periodical_student');
        $bcourse = $this->getParam('periodical_course');
        $captcha = $this->getParam('periodical_captcha');

        // Check whether user matched captcha
        if (md5(strtoupper($captcha)) != $this->getParam('captcha') || empty($captcha)) {
            $errormsg [ ] = 'badcaptcha';
        }
        //if form entry is in corect or invavalid
        if (count($errormsg) > 0) {
            $this->setVarByRef('$errormsg', $errormsg);
            $this->setVarByRef('insarr', $insarr);
            return 'editadd_tpl.php';
        }

        //insert the data into DB
        $id = $this->dbAddillperiodical->insertperiodicalRecord($titleperiodical, $volume, $part, $year, $pages,
                        $author, $titlearticle, $prof, $address, $cell, $tell,
                        $tellw, $emailaddress, $entitynum, $studentno, $course);

        $subject = "Periodical Book Record";
        $this->sendEmailNotification($subject,
                $message = $titleperiodical . ' ' . $volume . ' ' . $part . ' ' . $year . ' ' . $pages . '' .
                $author . ' ' . $titlearticle . ' ' . $prof . ' ' . $address . ' ' . $cell . ' ' . $tell . ' ' .
                $tellw . ' ' . $emailaddress . ' ' . $entitynum . ' ' . $studentno . ' ' . $course);
    }

//  end saveperiodicalRecord

    public function submitmsg() {

        //get parametters
        $name = $this->getParam('feedback_name');
        $email = $this->getParam('fbkemail');
        $msg = $this->getParam('msgbox');
        $captcha = $this->getParam('feedback_captcha');

        if (md5(strtoupper($captcha)) != $this->getParam('captcha') || empty($captcha)) {
            $errormsg[] = 'badcaptcha';
        }

        //if form entry is in corect or invavalid
        if (count($errormsg) > 0) {
            $this->setVarByRef('errormsg', $errormsg);
            $this->setVarByRef('insarr', $insarr);
            return 'editadd_tpl.php';
        }



        //insert the data into DB
        $id = $this->dbfeedback->insertmsgRecord($name, $email, $msg);

        // send email alert
        $subject = "Feed Back";

        $this->sendEmailNotification($subject, $message = $name . ' ' . $email . ' ' . $msg);
    }

// end of Submitmsg

    public function sendEmailNotification($subject, $message) {
        
        $objMail = $this->getObject('email', 'mail');
        //send to multiple addressed   
        $list = array("pmalinga@uwc.ac.za", "afakier@uwc.ac.za", "library@uwc.ac.za");
        $objMail->to = ($list);
        // specify whom the email is coming from
        $objMail->from = "no-reply@uwc.ac.za";
        $objMail->from = "no-reply";
        //Give email subject and body
        //$objMail->subject=$emaill;
        $objMail->subject = $subject;
        $objMail->body = $message;
        $objMail->AltBody = $message;
        // send email
        $objMail->send();
    }

// end of notification email
}

// end of all



