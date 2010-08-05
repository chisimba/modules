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
    protected $objMail;

    public function init() {

    //Instantiate the language object
        $this->objLanguage = $this->getObject('language', 'language');

       // Instantiate the class\\
        $this->dbAddDistances = $this->getObject('editform', 'libraryforms');
        $this->dbAddBookthesis =$this->getObject('bookthesis','libraryforms');
        $this->dbAddillperiodical=$this->getObject('illperiodical','libraryforms');
        $this->dbfeedback=$this->getObject('feedbk','libraryforms');
        $this->objUser=$this->getObject('User','security');
        $this->objMail = $this->getObject('email', 'mail');


    }//end of function

   public function dispatch($action) {
     
//var_dump($action);die;
        if($action=='addeditform') {
            $this->saveRecord();
	 }
else 
	if($action=='addthesis')
        {
            $this-> saveBookthesisRecord(); 
	}
else 
        if($action=='addperiodical')
        {
          $this->saveperiodicalRecord();
        }
   else 
        if ($action=='addfeedbk') 
	{          
	    $this->submitmsg(); 
        }

        return "editadd_tpl.php";
    }


    function saveRecord() {
        if (!$_POST) { // Check that user has submitted a page
           return $this->nextAction(NULL);

        }
        //get parametters for the distance form
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
	$captcha = $this->getParam('editformrequest_captcha');

       if (md5(strtoupper($captcha)) != $this->getParam('editformrequest_captcha') || empty($captcha)) {
            $msg = 'badcaptcha';
            $this->setVarByRef('msg', $msg);
            $this->setVarByRef('insarr', $insarr);
            return 'confirm_tpl.php';
        }

        else{
        // add info into database
  $pid = $this->dbAddDistances->insertRecord($surname, $initials, $title, $studentno, $postaladdress, $physicaladdress, $postalcode, $postalcode2, $telnoh, $telnow, $cell, $fax,$emailaddress, $course, $department, $supervisor);
   // send email notification

$this->sendEmailNotification($title="email notification for distance user",$subject="distance user email",$message= $surname.' '.$initials.' '. $title.' '. $studentno.' '. $postaladdress.' '. $physicaladdress.' '. $postalcode.' '. $postalcode2.' '.$telnoh.' '. $telnow.' '.$cell.' '. $fax.' '.$emailaddress.' ' .$course.' '. $department.' '. $supervisor);
 }

        if($pid!=null) {
            var_dump('Saved Successfully');
            die;
        }
        else {
            var_dump('Sorry Saved Successfully');
            die;
        }

    }// end of function

    function saveBookthesisRecord() {
        if (!$_POST) { // Check that user has submitted a page
	
            return $this->nextAction(NULL);
        }
        //get parametters
       
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
 	$captcha = $this->getParam('thesis_captcha');

        if (md5(strtoupper($captcha)) != $this->getParam('thesis_captcha') || empty($captcha)) {
            $msg = 'badcaptcha';
            $this->setVarByRef('msg', $msg);
            $this->setVarByRef('insarr', $insarr);
            //return 'form_tpl.php';
            return 'confirm_tpl.php';
        }
        else {
        //insert into DB
  $id= $this->dbAddBookthesis->insertBookthesisRecord($bprint,$bauthor,$btitle,$bplace,$bpublisher,$bdate,$bedition,$bisbn,$bseries,$bcopy,$btitlepages,$bpages,$bthesis,$bname,$baddress,$bcell,$bfax,$btel,$btelw,$bemailaddress,$bentitynum,$bstudentno, $bcourse);
  
 // after inserting into db send email notification
$this->sendEmailNotification($title="email notification for thesis books",$subject="book thesis mail",$message= $bprint.' '.
	$bauthor.' '.$btitle.' '.$bplace.' '.$bpublisher.' '.$bdate.' '.$bedition.' '.$bisbn.' '.$bseries.' '.$bcopy.' '.
	$btitlepages.' '.$bpages.' '.$bthesis.' '.$bname.' '.$baddress.' '.$bcell.' '.$bfax.' '.$btel.' '.$btelw.' '.
	$bemailaddress.' '.$bentitynum.' '.$bstudentno.' '.$bcourse);
	
}

        if($pid!=null) {
            var_dump('Saved Successfully');
            die;
        }
        else {
            var_dump('Sorry Saved Successfully');
            die;
        }
        
}// end of function
   


    function saveperiodicalRecord() {
        if (!$_POST) { // Check that user has submitted a page
        
            return $this->nextAction(NULL);
        }
       //get parametters
      
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
        $tell = $this->getParam('pages');
        $tellw = $this->getParam('thesis');
        $emailaddress = $this->getParam('email');
        $bentitynum = $this->getParam('entitynum');
        $bstudentno = $this->getParam('studentno');
        $bcourse = $this->getParam('course');
        $captcha = $this->getParam('periodical_captcha');

        if (md5(strtoupper($captcha)) != $this->getParam('periodical_captcha') || empty($captcha)) {
            $msg = 'badcaptcha';
            $this->setVarByRef('msg', $msg);
            $this->setVarByRef('insarr', $insarr);
            //return 'form_tpl.php';
            return 'confirm_tpl.php';
        }

        else{
        //insert the data into DB
 $id=$this->dbAddillperiodical->insertperiodicalRecord($titleperiodical, $volume, $part, $year, $pages, $author, $titlearticle, $prof,$address, $cell,$tell,$tellw, $emailaddress,$entitynum,$studentno,$course);

$this->sendEmailNotification($title="email for periodical books",$subject="periodical thesis mail",$message= $titleperiodical.''. $volume.''.$part.''.$year.''.$pages.''.$author.''.$titlearticle.''.$prof.''.$address.''.$cell.''.$tell.''.$tellw.''.$emailaddress.''.$entitynum.''.$studentno.''.$course);
}
             if($pid!=null) {
            	var_dump('Saved Successfully');
            	die;
        }
        else {
            	var_dump('Sorry Saved Successfully');
           	 die;
        }
    }// end if function

    function submitmsg() {

        if (!$_POST) { // Check that user has submitted a page
            return $this->nextAction(NULL);
        }
        //get parametters
       
        $name = $this->getParam('name');
        $emaill = $this->getParam('emaill');
        $msg = $this->getParam('msg');
         $captcha = $this->getParam('feedback_captcha');

        if (md5(strtoupper($captcha)) != $this->getParam('feedback_captcha') || empty($captcha)) {
            $msg = 'badcaptcha';
            $this->setVarByRef('msg', $msg);
            $this->setVarByRef('insarr', $insarr);
            return 'confirm_tpl.php';
        }
     else{
        //insert the data into DB
            $id=$this->dbfeedback->insertmsgRecord($name,$emaill,$msg);
	    $this->sendEmailNotification($title="feeb back email",$subject="channel your feed back",$message=$msg);
	}
   }
     function sendEmailNotification($title,$subject,$message){
        $objMail = $this->getObject('email', 'mail');
        $list=array("library@uwc.ac.za","arieluwc@uwc.ac.za","pmalinga@uwc.ac.za");
     	$objMail->setValue('to',$list);
        $objMail->setValue('from', "noreply@uwc.ac.za");
        $objMail->setValue('fromName', "");
        $objMail->setValue('subject', $title);
        $objMail->setValue('body', $message);
        $objMail->setValue('AltBody', $message);
        $objMail->send();

    }
       
}
   

