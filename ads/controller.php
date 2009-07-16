<?php

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
// end security check

class ads extends controller {
  var $submitAction; //stores the action that must be taken when a user clicks the 'next' button
  
  function init() {
    //These two store error text and form values
    $this->formError = $this->getObject("formerror");
    $this->formValue = $this->getObject("formvalue");
    
    $this->objDocumentStore = $this->getObject('dbdocument');
    $this->objCourseProposals = $this->getObject('dbcourseproposals');
    $this->objUser = $this->getObject ( 'user', 'security' );
    
    $this->objLanguage = $this->getObject('language', 'language');
    $this->objLog = $this->getObject('logactivity', 'logger');
    $this->allForms = array("A", "B", "C", "D", "E", "F", "G", "H");
    $this->objLog->log();
  }
  
  public function dispatch($action) {
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
  
  function __addcourseproposal(){
    return "addcourseproposal_tpl.php";
  }

  function __savecourseproposal(){
    $courseTitle= $this->getParam('title');
    $courseProposalId=$this->objCourseProposals->addCourseProposal($courseTitle);
    return $this->nextAction('overview', array('id'=>$courseProposalId));
  }
  
  function formExists($form) {
    foreach ($this->allForms as $f) {
      if ($form == $f) {
        return true;
      }
    }
    return false;
  }
  
  function __viewform($form = "") {
    $courseid = $this->getParam('courseid');
    $userid = $this->objUser->userId();
    if (!$this->objCourseProposals->courseExists($courseid)) {
      $this->formError->setError("general", "Invalid course number.");
      return "error_tpl.php";
    }
    if ($form == "") {
      $form = $this->getParam('formnumber');
      if (!$this->formExists($form)) {
        $this->formError->setError("general", "Invalid form number.");
        return "error_tpl.php";
      }
    }
    $verarray = $this->objDocumentStore->getVersion($courseid, $userid);
    if ($verarray['version'] == 0) {
      $this->createDocument($userid, $courseid);
    }
    else if ($verarray['status'] == 'submitted') {
      $this->objDocumentStore->increaseVersion($courseid, $userid, ($verarray['version'] + 1));
    }
    else {
      if ($verarray['currentuser'] != $userid) { //current user is trying to edit a locked document
        $this->formError->setError("general", "This document is currently locked, please wait until the user editing it is finished.");
        return "error_tpl.php";
      }
    }

    $values = $this->objDocumentStore->getValues($courseid, $form);
    if (count($values) == 0) {
      $this->formError->setError("general", "This document is currently locked, please wait until the user editing it is finished.");
      return "error_tpl.php";
    }
    foreach ($values as $fieldvalue) {
      $this->formValue->setValue($fieldvalue['question'], $fieldvalue['value']);
    }
    $this->submitAction = $this->uri(array("action"=>"submitform", "formnumber"=>$form, "courseid"=>$courseid));
    return "form$form" . "_tpl.php";
  }

  function __submitform() {
    $form = $this->getParam('formnumber');
    if (!$this->formExists($form)) {
      $this->formError->setError("general", "Invalid form number.");
      return "error_tpl.php";
    }
    $courseid = $this->getParam('courseid');
    if (!$this->objCourseProposals->courseExists($courseid)) {
      $this->formError->setError("general", "Invalid course number.");
      return "error_tpl.php";
    }
    $userid = $this->objUser->userId();
    $textquestions = $this->getTextQuestions($form);
    $numericalquestions = $this->getNumericalQuestions($form);
    $otherquestions = $this->getOtherQuestions($form);
    $this->errorCheckText($textquestions);
    $this->errorCheckNumeric($numericalquestions);
    $allquestions = array_merge($textquestions, $numericalquestions);
    $allquestions  = array_merge($allquestions, $otherquestions);
    if ($this->formError->numErrors() == 0) {
      $this->updateDatabase($userid, $courseid, $form, $allquestions);
      return $this->getNext($form);
    }
    else {
      $this->formValue->setAllValues($_POST);
      $this->submitAction = $this->uri(array("action"=>"submitform", "formnumber"=>$form, "courseid"=>$courseid));
      return "form$form" . "_tpl.php";
    }
  }
  function __home() {
    return "courseproposallist_tpl.php";
  }
  
  function __submitproposal() {
    $courseid = $this->getParam('courseid');
    if (!$this->objCourseProposals->courseExists($courseid)) {
      $this->formError->setError("general", "Invalid course number.");
      return "error_tpl.php";
    }
    //$proposal = $this->objCourseProposals->getProposal($courseid);
    $userid = $this->objUser->userID();
    $verarray = $this->objDocumentStore->getVersion($courseid, $userid);
    if ($verarray['currentuser'] != $userid) {
      $this->formError->setError("general", "This document is currently locked, please wait until the user editing it is finished.");
      return "error_tpl.php";
    }
    if ($verarray['status'] == 'submitted') {
      return __home();
    }
    $proposal = $this->objDocumentStore->getProposal($courseid, $verarray['version']);
    if ($this->errorFree($proposal)) {
      $verarray = $this->objDocumentStore->submitProposal($courseid, $verarray['version']);
      return $this->__home();
    }
    else {
      return "error_tpl.php";
    }
  }
  
  function errorFree($proposal) {
    $errorstring = "";
    $forms = array();
    foreach ($proposal as $question) {
      $forms[$question['formnumber']][$question['question']] = $question['value'];
    }
    foreach ($forms as $key=>$form) {
      $_POST = $form;
      $textquestions = $this->getTextQuestions($key);
      $numericalquestions = $this->getNumericalQuestions($key);
      $otherquestions = $this->getOtherQuestions($key);
      $this->errorCheckText($textquestions);
      $this->errorCheckNumeric($numericalquestions);
      $allquestions = array_merge($textquestions, $numericalquestions);
      $allquestions  = array_merge($allquestions, $otherquestions);
      if ($this->formError->numErrors() > 0) {
        $errorstring .= "There is an error on form $key<br />";
      }
      $this->formError->errorarray = array();
    }
    if ($errorstring == "") {
      return true;
    }
    else {
      $this->formError->setError("general", $errorstring);
      return false;
    }
  }
  
  function getNext($form) {
    $count = 0;
    foreach ($this->allForms as $f) {
      $count++;
      if ($f == $form) {
        break;
      }
    }
    if ($count == count($this->allForms)) {
      //no more forms, go back to initial page
      $this->formError->setError("general", "Document complete, you may now submit it by clicking the submit link next to it.");
      return $this->__home();
    }
    else {
      //go to $this->allForms[$count]
      return $this->__viewform($this->allForms[$count]);
    }
  }
 
  
  function createDocument($userid, $courseid) {
    foreach ($this->allForms as $form) {
      $text = $this->getTextQuestions($form);
      $num = $this->getNumericalQuestions($form);
      $other = $this->getOtherQuestions($form);
      $allquestions = array_merge($text, $num);
      $allquestions = array_merge($allquestions, $other);
      foreach ($allquestions as $question) {
        $this->objDocumentStore->addRecord($courseid, $form, $question, "", "unsubmitted", "1", $userid);
      }
    }
  }

  function updateDatabase($userid, $courseid, $form, $questionnumbers) {
    foreach ($questionnumbers as $question) {
      if (!isset($_POST[$question])) {
        $_POST[$question] = "";
      }
      if ($this->objDocumentStore->updateRecord($courseid, $form , $question, $_POST[$question], $this->objUser->userId()) == false) {
        $this->formError->setError("general", "This document is currently locked, please wait until the user editing it is finished.");
        return $this->__home();
      }
    }
  }
    
  //======================================Error Checking=======================================
    function errorCheckText($array) {
        foreach ($array as $value) {
            if (!isset($_POST[$value])) {
                $this->formError->setError($value, "You must specify a value for this field.");
            }
            elseif (strlen($_POST[$value] = trim($_POST[$value])) == 0) {
                $this->formError->setError($value, "You must specify a value for this field.");
            }
        }
    }

    function errorCheckNumeric($array) {
        //this will check if the numbers are actually numbers
        foreach ($array as $value) {
            if (!isset($_POST[$value])) {
                $this->formError->setError($value, "You must specify a numeric value for this field.");
            }
            elseif (strlen($_POST[$value] = trim($_POST[$value])) == 0) {
                $this->formError->setError($value, "You must specify a numeric value for this field.");
            }
            elseif (!preg_match ("/[^0-9]/", $_POST[$value]) == false) { //checks if it consists of digits only
                $this->formError->setError($value, "Value must be a number.");
            }
        }
    }


    function getTextQuestions($form) {
        $textquestions = array();
        if ($form == "A") {
          $textquestions[] = "A1";
          $textquestions[] = "A3";
          $textquestions[] = "A4";
        }
        if ($form == "B") {
          $textquestions[] = "B1";
          $textquestions[] = "B2";
          $textquestions[] = "B3a";
          $textquestions[] = "B3b";
          $textquestions[] = "B4b";
          $textquestions[] = "B4c";
          $textquestions[] = "B5b";
          $textquestions[] = "B6b";
        }
        if ($form == "C") {
            $textquestions[] = "C1";
            $textquestions[] = "C2b";
            $textquestions[] = "C3";
            $textquestions[] = "C4b_1";
            //$textquestions[] = "C4b_2";
        }
        if ($form == "D") {
            $textquestions[]  = "D1";
            $textquestions[]  = "D2_1";
            $textquestions[]  = "D2_2";
            $textquestions[]  = "D2_3";
            $textquestions[]  = "D3";
            $textquestions[]  = "D5_1";
            $textquestions[]  = "D5_2";
            $textquestions[]  = "D5_3";
            $textquestions[]  = "D5_4";
            $textquestions[]  = "D5_5";
            $textquestions[]  = "D5_6";
            $textquestions[]  = "D5_7";
            $textquestions[]  = "D5_8";
            $textquestions[]  = "D5_9";
            $textquestions[]  = "D6";
            $textquestions[]  = "D7";
        }
        if ($form == "E") {
          $textquestions[]  = "E1a";
          $textquestions[]  = "E1b";
          $textquestions[]  = "E2a";
          $textquestions[]  = "E2b";
          $textquestions[]  = "E2c";
          $textquestions[]  = "E3a";
          $textquestions[]  = "E3b";
          $textquestions[]  = "E3c";
          $textquestions[]  = "E4";
          $textquestions[]  = "E5a";
          $textquestions[]  = "E5b";
        }
        if ($form == "F") {
          $textquestions[]  = "F1b";
          $textquestions[]  = "F2b";
          $textquestions[]  = "F3a";
          $textquestions[]  = "F3b";
          $textquestions[]  = "F4";
        }
        if ($form == "G") {
          $textquestions[]  = "G1a";
          $textquestions[]  = "G1b";
          $textquestions[]  = "G2a";
          $textquestions[]  = "G2b";
          $textquestions[]  = "G3a";
          $textquestions[]  = "G3b";
          $textquestions[]  = "G4a";
          $textquestions[]  = "G4b";
        }
        if ($form == "H") {
          $textquestions[]  = "H1";
          $textquestions[]  = "H2a";
          $textquestions[]  = "H2b";
          $textquestions[]  = "H3a";
          $textquestions[]  = "H3b";
        }
        return $textquestions;
    }

    function getOtherQuestions($form) { //questions with no error checking on them such as checkboxes
        $otherquestions = array();
        if ($form == "A") {
           $otherquestions[] = "A2";
           $otherquestions[] = "A5";
        }
        if ($form == "B") {
          $otherquestions[] = "B4a";
          $otherquestions[] = "B5a";
          $otherquestions[] = "B6a";
        }
        if ($form == "C") {
           $otherquestions[] = "C2a";//radio button
           $otherquestions[] = "C4a";//radio button
        }
        if ($form == "D") {
          $otherquestions[]  = "D4_1";//D4_1-8 are checkboxes
          $otherquestions[]  = "D4_2";
          $otherquestions[]  = "D4_3";
          $otherquestions[]  = "D4_4";
          $otherquestions[]  = "D4_5";
          $otherquestions[]  = "D4_6";
          $otherquestions[]  = "D4_7";
          $otherquestions[]  = "D4_8";
        }
        if ($form == "F") {
          $otherquestions[]  = "F1a";
          $otherquestions[]  = "F2a";
        }
        return $otherquestions;
    }

    function getNumericalQuestions($form) {
        $numericalquestions = array();
        return $numericalquestions;
    }

    public function __viewcourseproposalstatus() {
        $this->unit_name=$this->getParam('unit_name');
        $this->id=$this->getParam('id');

        return "viewcourseproposalstatus_tpl.php";
    }

    public function __submitproposalstatus() {
        $this->id=$this->getParam('id');
        $status = $this->getParam('proposalstatus');

        // save status in the database
        $submitted = $this->objCourseProposals->updateProposalStatus($this->id, $status);

        if($submitted) {
            return $this->__home();
        }
        else {
            $message = "There was an error submitting your information";
            $this->setVarByRef("message", $message);
            return "viewcourseproposalstatus_tpl.php";
        }
    }
}


/*
$this->objUser = $this->newObject('user', 'security');
$this->userId = $this->objUser->userId();
$this->userName = $this->objUser->username($this->userId);
if ($this->objUser->isAdmin())
{
$this->userLevel = 'admin';
}
elseif ($this->objUser->isLecturer())
{
$this->userLevel = 'lecturer';
}
elseif ($this->objUser->isStudent())
{
$this->userLevel = 'student';
} else
{
$this->userLevel = 'guest';
}
*/