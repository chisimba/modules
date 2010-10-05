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

       // Instantiate the class
        $this->dbAddDistances = $this->getObject('editform', 'libraryforms');
        $this->dbAddBookthesis =$this->getObject('bookthesis','libraryforms');
        $this->dbAddillperiodical=$this->getObject('illperiodical','libraryforms');
        $this->dbfeedback=$this->getObject('feedbk','libraryforms');
        $this->objUser=$this->getObject('User','security');
        // Get a local reference to the mail
        $this->objMail = $this->getObject('email', 'mail');

       }//end of function

   public function dispatch($action) {
     
      $action =$this ->getParam('action');
      $this->setLayoutTemplate('editadd_tpl.php');
       //if form is submitted
 	
  if($_POST){

	switch($action){

        case 'addeditform':
          return $this->saveRecord();
          return $this->sendEmailNotification();

        case 'addthesis':
         return $this->saveBookthesisRecord();
         return $this->sendEmailNotification();

        case 'addperiodical':
         return $this->saveperiodicalRecord();
         return $this->sendEmailNotification();

        case 'addfeedbk':
         return $this->submitmsg();
         return $this->sendEmailNotification();
      
	}// end if post

	}// end switch function

 
       else{	//Display Landing page and forms
            switch($action)
            {
 	 default: 
     return "editadd_tpl.php";
       
        }
	} //end switch

	}//end of function dispatch
 	/*
 	   *Public Method that checks if all required fields are filled
 	   *If fields are filled, and inserts data into db table, else returns error
 	   */

 public function saveRecord() {
           if (!$_POST) { // Check that user has submitted a page
           return $this->nextAction(NULL);
           }
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
        $this->objConfirm=$this->getObject('confirm','libraryforms');
        $captcha = $this->getParam('editformrequest_captcha');

         // Check whether user matched captcha
        if (md5(strtoupper($captcha)) != $this->getParam('editformrequest_captcha') || empty($captcha)) {
           $msg = 'badcaptcha';
           }

          //if form entry is in corect or invavalid
            if (count($msg) > 0) {
            $this->setVarByRef('msg', $msg);
            $this->setVarByRef('insarr', $insarr);
            return 'editadd_tpl.php';
             }

        else {
            $this->nextAction('confirm');
            return $this->objConfirm->addStudentDetails();
        }
         // insert into database
	$pid = $this->dbAddDistances->insertRecord($surname, $initials, $title, $studentno, $postaladdress,
						   $physicaladdress, $postalcode, $postalcode2, $telnoh, 
                                                   $telnow, $cell, $fax,$emailaddress, $course, $department, $supervisor);
 
           
        // send email alert 
 	$this->sendEmailNotification($title="email notification for distance user",$subject="distance user email",
				     $message= $surname.' '.$initials.' '. $title.' '. $studentno.' '. $postaladdress.' '. 
                                     $physicaladdress.' '. $postalcode.' '. $postalcode2.' '.$telnoh.' '. $telnow.' '.
                                     $cell.' '. $fax.' '.$emailaddress.' ' .$course.' '. $department.' '. $supervisor);
    return 'confirm_tpl.php';

	 	         
		
    }// end of Save Records */

 function saveBookthesisRecord() {
        if (!$_POST) { // Check that user has submitted a page
	
            return $this->nextAction(NULL);
        }    

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

         // Check whether user matched captcha
       if (md5(strtoupper($captcha)) != $this->getParam('thesis_captcha') || empty($captcha)) {
            $msg = 'badcaptcha';
           }
             //if form entry is in corect or invavalid
            if (count($msg) > 0) {
            $this->setVarByRef('msg', $msg);
            $this->setVarByRef('insarr', $insarr);
            return 'editadd_tpl.php';
             }

        else {
            $this->nextAction('confirm');
            return $this->objConfirm->addStudentDetails();
        }
        //insert into DB
	$id= $this->dbAddBookthesis->insertBookthesisRecord($bprint,$bauthor,$btitle,$bplace,$bpublisher,$bdate,
                                                            $bedition,$bisbn,$bseries,$bcopy,$btitlepages,$bpages,$bthesis,
                                                            $bname,$baddress,$bcell,$bfax,$btel,$btelw,$bemailaddress,
                                                            $bentitynum,$bstudentno, $bcourse);

// after inserting into db send email alert
	$this->sendEmailNotification($title="email notification for thesis books",$subject="book thesis mail",
                                     $message= $bprint.' '. $bauthor.' '.$btitle.' '.$bplace.' '.$bpublisher.' '.
				     $bdate.' '.$bedition.' '.$bisbn.' '.$bseries.' '.$bcopy.' '. $btitlepages.' '.
				     $bpages.' '.$bthesis.' '.$bname.' '.$baddress.' '.$bcell.' '.$bfax.' '.
				     $btel.' '.$btelw.' '.$bemailaddress.' '.$bentitynum.' '.$bstudentno.' '.$bcourse);


  } // end of bookthesisrecord

 public function saveperiodicalRecord() {
        if (!$_POST) { // Check that user has submitted a page
        
            return $this->nextAction(NULL);
        }

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

  	 // Check whether user matched captcha
	if (md5(strtoupper($captcha)) != $this->getParam('periodical_captcha') || empty($captcha)) {
               $msg = 'badcaptcha';
            }
 	//if form entry is in corect or invavalid
            if (count($msg) > 0) {
            $this->setVarByRef('msg', $msg);
            $this->setVarByRef('insarr', $insarr);
            return 'editadd_tpl.php';
             }

        else {
            $this->nextAction('confirm');
            return $this->objConfirm->addStudentDetails();
           }

       //insert the data into DB
 	$id=$this->dbAddillperiodical->insertperiodicalRecord($titleperiodical, $volume, $part, $year, $pages, $author, 							      $titlearticle, $prof,$address, $cell,$tell,$tellw, 
							      $emailaddress,$entitynum,$studentno,$course);

	$this->sendEmailNotification($title="email for periodical books",$subject="periodical thesis mail",
				     $message= $titleperiodical.''. $volume.''.$part.''.$year.''.$pages.''.
				     $author.''.$titlearticle.''.$prof.''.$address.''.$cell.''.$tell.''.
				     $tellw.''.$emailaddress.''.$entitynum.''.$studentno.''.$course);


} //  end saveperiodicalRecord


public function submitmsg() {

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
           }

  	 //if form entry is in corect or invavalid
            if (count($msg) > 0) {
            $this->setVarByRef('msg', $msg);
            $this->setVarByRef('insarr', $insarr);
            return 'editadd_tpl.php';
             }

        else {
            $this->nextAction('confirm');
            return $this->objConfirm->addStudentDetails();
           }

         //insert the data into DB
         $id=$this->dbfeedback->insertmsgRecord($name,$emaill,$msg);
          // send email alert
         $this->sendEmailNotification($title="feed back email",$subject="channel your feed back",$message= $msg.'');

}// end of Submitmsg


 public function sendEmailNotification($title,$subject,$message){
        //Get local refence to mail object
        $objMail = $this->getObject('email', 'mail');
        //send to multiple addressed   
        $list=array("library@uwc.ac.za","arieluwc@uwc.ac.za");
        $objMail->to=$list;
	// specify whom the email is coming from
        $objMail->from= "no-reply@uwc.ac.za";
        $objMail->from= "no-reply";
        //Give email subject and body
        //$objMail->subject=$emaill;
        $objMail->subject=$title;
        $objMail->body=$message;
        $objMail->AltBody=$message;
        // send email
        $this->$objMail->send();

     }  // end of notification email


      
}// end of all
   

