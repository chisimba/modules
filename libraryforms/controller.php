<?php

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

// end security check

/**
 *
 *libraryforms
 *
 * libraryforms allows students or distant user to request books online
 *
 * @category  Chisimba
 * @package   libraryforms
 * @author    Brenda Mayinga brendamayinga@ymail.com
 **/



class libraryforms extends controller {


    public $objLanguage;
   // public $objUser;
    protected $objMail;

    public function init() {
    //Instantiate the language object
        $this->objLanguage = $this->getObject('language', 'language');
        $this->dbAddDistances = $this->getObject('editform', 'libraryforms');
        $this->dbAddBookthesis =$this->getObject('bookthesis','libraryforms');
        $this->dbAddillperiodical=$this->getObject('illperiodical','libraryforms');
        $this->dbfeedback=$this->getObject('feedbk','libraryforms');
        $this->objUser=$this->getObject('User','security');
        $this->objMail = $this->getObject('email', 'mail');


    }//end of function

    public function dispatch($action) {
    //var_dump($action);die;
        if($action=='add') {
            $this->saveRecord();
        }

        return "editadd_tpl.php";
    }


    function saveRecord() {
        if (!$_POST) { // Check that user has submitted a page
            return $this->nextAction(NULL);
        }
        $captcha = $this->getParam('request_captcha');
        $surname = $this->getParam('surname');
        $initials = $this->getParam('initials');
        $title= $this->getParam('title');
        $studentno = $this->getParam('studentno');
        $postaladdress= $this->getParam('postaladdress');
        $physicaladdress = $this->getParam('physicaladdress');
        $postalcode = $this->getParam('postalcode');
        $postalcode2 = $this->getParam('postalcode2');
        $telnoh= $this->getParam('telnoh');
        $telnow = $this->getParam('telnow');
        $cell = $this->getParam('cell');
        $fax= $this->getParam('fax');
        $emailaddress= $this->getParam('register_email');
        $course= $this->getParam('course');
        $department = $this->getParam('department');
        $supervisor = $this->getParam('supervisor');

        if (md5(strtoupper($captcha)) != $this->getParam('captcha') || empty($captcha)) {
            $msg = 'badcaptcha';
            $this->setVarByRef('msg', $msg);
            $this->setVarByRef('insarr', $insarr);
            //return 'form_tpl.php';
            return 'editadd_tpl.php';
        }

        else
        // add info into database
  $pid = $this->dbAddDistances->insertRecord($surname, $initials, $title, $studentno, $postaladdress, $physicaladdress, $postalcode, $postalcode2, $telnoh, $telnow, $cell, $fax,$emailaddress, $course, $department, $supervisor);
   
        if($pid!=null) {
            var_dump('Saved Successfully');
            die;
        }
        else {
            var_dump('Not Saved Successfully');
            die;
        }

    }

    function saveBookthesisRecord() {
        if (!$_POST) { // Check that user has submitted a page
            return $this->nextAction(NULL);
        }
        //  getting parametters to save into database
        $captcha = $this->getParam('request_captcha');
        $bprint = $this->getParam('print');
        $bauthor= $this->getParam('author');
        $btitle = $this->getParam('title');
        $bplace = $this->getParam('place');
        $bpublisher = $this->getParam('publisher');
        $bdate = $this->getParam('date');
        $bedition = $this->getParam('edition');
        $bisbn= $this->getParam('isbn');
        $bseries = $this->getParam('series');
        $bcopy = $this->getParam('copy');
        $btitlepages = $this->getParam('titlepages');
        $bpages = $this->getParam('pages');
        $bthesis = $this->getParam('thesis');
        $bname = $this->getParam('name');
        $baddress = $this->getParam('address');
        $bcell= $this->getParam('cell');
        $bfax = $this->getParam('fax');
        $btel = $this->getParam('tel');
        $btelw = $this->getParam('telw');
        $bemailaddress = $this->getParam('emailaddress');
        $bentitynum = $this->getParam('entitynum');
        $bstudentno = $this->getParam('studentno');
        $bcourse = $this->getParam('course');

        if (md5(strtoupper($captcha)) != $this->getParam('captcha') || empty($captcha)) {
            $msg = 'badcaptcha';
            $this->setVarByRef('msg', $msg);
            $this->setVarByRef('insarr', $insarr);
            //return 'form_tpl.php';
            return 'editadd_tpl.php';
        }
        else {
        //insert into DB
  $id2= $this->dbAddBookthesis->insertBookthesisRecord($bprint,$bauthor,$btitle,$bplace,$bpublisher,$bdate,$bedition,$bisbn,$bseries,$bcopy,$btitlepages,$bpages,$bthesis,$bname,$baddress,$bcell,$bfax,$btel,$btelw,$bemailaddress,$bentitynum,$bstudentno, $bcourse);
           

        if($pid2!=null) {
            var_dump('Saved Successfully');
            die;
        }
        else {
            var_dump('Not Saved Successfully');
            die;
        }
            }
    }


    function saveperiodicalRecord() {
        if (!$_POST) { // Check that user has submitted a page
            return $this->nextAction(NULL);
        }
        //for the book periodicalform
        $captcha = $this->getParam('request_captcha');
        $titleperiodical = $this->getParam('print');
        $volume= $this->getParam('author');
        $part= $this->getParam('title');
        $year = $this->getParam('place');
        $pages = $this->getParam('publisher');
        $author = $this->getParam('date');
        $titlearticle= $this->getParam('edition');
        $prof= $this->getParam('isbn');
        $address = $this->getParam('series');
        $cell = $this->getParam('copy');
        // $fax = $this->getParam('titlepages');
        $tell = $this->getParam('pages');
        $tellw = $this->getParam('thesis');
        $emailaddress = $this->getParam('email');
        $bentitynum = $this->getParam('entitynum');
        $bstudentno = $this->getParam('studentno');
        $bcourse = $this->getParam('course');

        if (md5(strtoupper($captcha)) != $this->getParam('captcha') || empty($captcha)) {
            $msg = 'badcaptcha';
            $this->setVarByRef('msg', $msg);
            $this->setVarByRef('insarr', $insarr);
            //return 'form_tpl.php';
            return 'editadd_tpl.php';
        }

        else
        //insert the data into DB
            $id3=$this->dbAddillperiodical->insertperiodicalRecord($titleperiodical, $volume, $part, $year, $pages, $author, $titlearticle, $prof,$address, $cell,$tell,$tellw, $emailaddress,$entitynum,$studentno,$course);

        if($pid3!=null) {
            var_dump('Saved Successfully');
            die;
        }
        else {
            var_dump('Not Saved Successfully');
            die;
        }
    }

    function submitmsg() {

        if (!$_POST) { // Check that user has submitted a page
            return $this->nextAction(NULL);
        }
        //getting pararameters for the feed back form
        $captcha = $this->getParam('request_captcha');
        $name = $this->getParam('name');
        $emaill = $this->getParam('emaill');
        $msg = $this->getParam('mag');

        if (md5(strtoupper($captcha)) != $this->getParam('captcha') || empty($captcha)) {
            $msg = 'badcaptcha';
            $this->setVarByRef('msg', $msg);
            $this->setVarByRef('insarr', $insarr);
            //return 'form_tpl.php';
            return 'editadd_tpl.php';
        }
        else
        //insert the data into DB
            $id4=$this->dbfeedback->insertmsgRecord($name,$emaill,$msg);

 //send email notification
     // Specify who the mail is coming from.
        $this->objMail->from = $emaill;
        $this->objMail->fromName = $name;
         // Give the mail a subject and a body.
        $this->objMail->subject = 'Feedback Message';
        $this->objMail->body = $msg;

        // Send to a single address.
        $this->objMail->to = 'library@uwc.ac.za';

        // Send to multiple addresses.
        $this->objMail->to = array('library@uwc.ac.za', 'arieluwc@uwc.ac.za','shiluvam@gmail.com');

        // Send the mail.
        $this->objMail->send();
    }
}
//}

