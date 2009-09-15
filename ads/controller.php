<?php

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
// end security check

class ads extends controller {
    var $submitAction; //stores the action that must be taken when a user clicks the 'next' button
    var $addCommentMessage = false;//stores messages

    function init() {
        $this->loadclass('link','htmlelements');

        //These two store error text and form values
        $this->formError = $this->getObject("formerror");
        $this->formValue = $this->getObject("formvalue");

        $this->objDocumentStore = $this->getObject('dbdocument');
        $this->objGetData = $this->getObject('getdata');
        $this->objCourseProposals = $this->getObject('dbcourseproposals');
        $this->objComment = $this->getObject('dbcoursecomments');
        $this->objQuestionComment = $this->getObject('dbquestioncomments');
        $this->objFaculty = $this->getObject('dbfaculty');
        $this->objProposalMembers=$this->getObject('dbproposalmembers');
        $this->objCommentAdmin=$this->getObject('dbcommentsadmin');
        //review
        $this->objCourseReviews = $this->getObject('dbcoursereviews');
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

    function __addquestioncomment(){
        $comment=$this->getParam("comment");
        $courseid=$this->getParam("courseid");
        $formnumber=$this->getParam("formnumber");
        $question=$this->getParam("question");
        if($comment != ''){
        $this->objQuestionComment->addComment($courseid, $formnumber,$question,$comment);
        }
        echo $this->objQuestionComment->getComments($courseid, $formnumber,$question);
    }
    function __addcomment(){
        $this->setVarByRef('tmpcourseid',$this->getParam('courseid'));
        return "addcomment_tpl.php";
    }

   function __commentadmin(){
       return "commentadmin_tpl.php";
   }
    function __updatecomment(){
           $objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $toemail=$this->objCourseProposals($this->getParam('courseid'));
        $subject=$objSysConfig->getValue('EMAIL_COMMENT_SUBJECT', 'ads');
        $body=$objSysConfig->getValue('EMAIL_COMMENT_BODY', 'ads');
        $linkUrl = $this->uri(array('action'=>'showcourseprophist','courseid'=>$this->getParam('courseid'),'selectedtab'=>'0'));
        $body.=' '. str_replace("amp;", "", $linkUrl);

        $objMailer = $this->getObject('email', 'mail');
        $objMailer->setValue('to', array($toemail));
        $objMailer->setValue('from', $this->objUser->email());
        $objMailer->setValue('fromName', $this->objUser->fullnames);
        $objMailer->setValue('subject', $subject);
        $objMailer->setValue('body', $body);
        $objMailer->send();
       $this->objDocumentStore->updateComment($this->getParam('id'),$this->getParam('admComment'));
        $this->nextAction('showcourseprophist',array('courseid'=>$this->getParam('courseid'),'selectedtab'=>'0'));
    }

  function __facultylist() {
    return "facultylist_tpl.php";
  }

    function __savecomment() {
        $objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $toemail=$this->objCourseProposals->getOwnerEmail($this->getParam('courseid'));
        $subject=$objSysConfig->getValue('EMAIL_ADDCOMMENT_SUBJECT', 'ads');
        $body=$objSysConfig->getValue('EMAIL_ADDCOMMENT_BODY', 'ads');
        $linkUrl = $this->uri(array('action'=>'showcourseprophist','courseid'=>$this->getParam('courseid'),'selectedtab'=>'0'));

        $body.=' '. str_replace("amp;", "", $linkUrl);
        $body=' '. str_replace("{from_names}", $this->objUser->fullname(), $body);
        $body=' '. str_replace("{proposal_status}", $this->objCourseProposals->getStatus($this->getParam('courseid')), $body);
        $body=' '. str_replace("{proposal}", $this->objCourseProposals->getTitle($this->getParam('courseid')), $body);
        $body=' '. str_replace("{comment}", $this->getParam('commentField'), $body);

        
        $recepients=array($toemail);
        $proposalMembersData=$this->objProposalMembers->getMembers($this->getParam('courseid'));
        $membercount=count($proposalMembersData);
        if($membercount > 0){
        foreach($proposalMembersData as $row){
        $recepients[]=$this->objUser->email($row['userid']);
        }  
        }

        $objMailer = $this->getObject('email', 'mail');
        $objMailer->setValue('to',$recepients);
        $objMailer->setValue('from', $this->objUser->email());
        $objMailer->setValue('fromName', $this->objUser->fullnames);
        $objMailer->setValue('subject', $subject);
        $objMailer->setValue('body', $body);
        $objMailer->send();

        $this->objComment->addComment($this->getParam('courseid'),$this->getParam('commentField'),$this->getParam('status'));
        $this->nextAction('showcourseprophist',array('courseid'=>$this->getParam('courseid'),'selectedtab'=>'0'));
    }

    function __viewcomments() {
        $this->id = $this->getParam('courseid');
        $this->setVarByRef('version', $this->getParam('version'));

        return "viewcomment_tpl.php";
    }

    function __searchusers(){
        $val= $this->getParam('query');
        $start= $this->getParam('start');
        $limit= $this->getParam('limit');
        return $this->objDocumentStore->getUsers($val,$start,$limit);
    }



    function __reviewcourseproposal(){
        return "reviewcourseproposal_tpl.php";
    }

    function __addcourseproposal(){
        return "addcourseproposal_tpl.php";
    }

    function __editcourseproposal() {
        $this->id = $this->getParam('id');

        return "editcourseproposal_tpl.php";
    }

    function __savecourseproposal(){
        $faculty = $this->getParam('faculty');
        $courseTitle= $this->getParam('title');

        if($this->getParam('edit')) {
            $this->id = $this->getParam('id');
            $courseProposalId=$this->objCourseProposals->editProposal($this->id, $faculty, $courseTitle);
        }
        else {
            $courseProposalId=$this->objCourseProposals->addCourseProposal($faculty, $courseTitle);
        }
        $this->objDocumentStore->addRecord($courseProposalId, "Comment", "", "", "", "0", $this->objUser->email($this->objUser->userId()));
        return $this->nextAction('overview', array('id'=>$courseProposalId));
    }

    function __savecoursereview(){
        $courseReview = $this->getParam('title');
        $courseID = $this->getParam('id');
        $courseReviewlId=$this->objCourseReviews->addCourseReview($courseReview,$courseID);
        return $this->nextAction('overview', array('id'=>$courseReviewId));
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
            $this->objDocumentStore->increaseVersion($courseid, $this->objUser->email($userid), ($verarray['version'] + 1));
        }
        else {
            if(!$this->objUser->isAdmin()){
                if (!strcmp($verarray['currentuser'], $this->objUser->email($userid)) == 0) { //current user is trying to edit a locked document
                    $this->formError->setError("general", "This document is currently locked, please wait until the user editing it is finished.");
                    return "error_tpl.php";
                }}
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
        //$this->setVarByRef("courseid",$courseid);
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
            return $this->getNext($form,$courseid);
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
        if (strcmp($verarray['currentuser'], $this->objUser->email(userid)) == 0) {
            $this->formError->setError("general", "This document is currently locked, please wait until the user editing it is finished.");
            return "error_tpl.php";
        }
        if ($verarray['status'] == 'submitted') {
            return __home();
        }
        $proposal = $this->objDocumentStore->getProposal($courseid, $verarray['version']);
        //if ($this->errorFree($proposal,$courseid)) {
            $verarray = $this->objDocumentStore->submitProposal($courseid, $verarray['version']);
            return $this->__home();
        //}
        /*else {
            return "error_tpl.php";
        }*/
    }

    function errorFree($proposal,$courseid) {

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

                $formLink = new link ($this->uri(array('action'=>'viewform', 'formnumber'=>$key, 'courseid'=>$courseid),"ads"));
                $formLink->link=   '<h4>Empty fields in form '.$key.'<h4>';
                $errorstring .=$formLink->show();
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

    function getNext($form,$courseid) {
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
            return $this->nextAction('showcourseprophist',array("courseid"=>$courseid,'selectedtab'=>'0'));
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
                $this->objDocumentStore->addRecord($courseid, $form, $question, "", "unsubmitted", "1", $this->objUser->email($this->objUser->userId()));
            }
        }
    }

    function updateDatabase($userid, $courseid, $form, $questionnumbers) {
        foreach ($questionnumbers as $question) {
            if (!isset($_POST[$question])) {
                $_POST[$question] = "";
            }
            if ($this->objDocumentStore->updateRecord($courseid, $form , $question, $_POST[$question], $this->objUser->email($this->objUser->userId())) == false) {
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
            elseif (strlen($_POST[$value]) > 4000) {
                $this->formError->setError($value, "Field value too long.");
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
            $textquestions[]  = "F3a";
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
            $otherquestions[]  = "F1b";
            $otherquestions[]  = "F2b";
            $otherquestions[]  = "F3b";
        }
        return $otherquestions;
    }

    function getNumericalQuestions($form) {
        $numericalquestions = array();
        if ($form == "D") {
            $numericalquestions[] = "D5_1";
            $numericalquestions[] = "D5_2";
            $numericalquestions[] = "D5_3";
            $numericalquestions[] = "D5_4";
            $numericalquestions[] = "D5_5";
            $numericalquestions[] = "D5_6";
            $numericalquestions[] = "D5_7";
            $numericalquestions[] = "D5_8";
            $numericalquestions[] = "D5_9";
        }
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
        $version= $this->getParam('version');


        // save status in the database
        $submitted = $this->objCourseProposals->updateProposalStatus($this->id, $status);

        if($submitted) {
        $objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $toemail=$this->objCourseProposals->getOwnerEmail($this->getParam('id'));
        $subject=$objSysConfig->getValue('EMAIL_STATUS_SUBJECT', 'ads');
        $body=$objSysConfig->getValue('EMAIL_STATUS_BODY', 'ads');
        $linkUrl = $this->uri(array('action'=>'showcourseprophist','courseid'=>$this->getParam('id'),'selectedtab'=>'0'));

        $body.=' '. str_replace("amp;", "", $linkUrl);
        $body=' '. str_replace("{from_names}", $this->objUser->fullname(), $body);
        $body=' '. str_replace("{proposal_status}", $this->objCourseProposals->getStatus($this->getParam('id')), $body);
        $body=' '. str_replace("{proposal}", $this->objCourseProposals->getTitle($this->getParam('id')), $body);
        $body=' '. str_replace("{comment}", $this->getParam('commentField'), $body);

        $objMailer = $this->getObject('email', 'mail');
        $objMailer->setValue('to', array($toemail));
        $objMailer->setValue('from', $this->objUser->email());
        $objMailer->setValue('fromName', $this->objUser->fullnames);
        $objMailer->setValue('subject', $subject);
        $objMailer->setValue('body', $body);
        $objMailer->send();

        $this->nextAction('showcourseprophist',array('courseid'=>$this->id,'selectedtab'=>'0'));
        }
        else {
            $message = "There was an error submitting your information";
            $this->setVarByRef("message", $message);
            return "viewcourseproposalstatus_tpl.php";
        }
    }

    public function __deletecourseproposal() {
        $this->id=$this->getParam('id');
        $this->objCourseProposals->deleteProposal($this->id);

        return $this->__home();
    }

    public function __showcourseprophist() {

        $this->id = $this->getParam('courseid');
        $data = $this->objDocumentStore->getVersion($this->id, $this->objUser->userId());
        $selectedtab= $this->getParam('selectedtab');
        if(!$selectedtab){
            $selectedtab="0";
        }
        if($data['version'] == 0) {
            return $this->nextAction('viewForm', array('courseid'=>$this->id, 'formnumber'=>$this->allForms[0]));
        }
        else {
            $message=$this->getParam("message");
            $this->setVarByRef("alert",$message);
            $this->setVarByRef("selectedtab",$selectedtab);
            return "proposaldetails_tpl.php";
        }
    }

    public function __sendproposaltomoderator(){
        /*if(strlen(trim($this->getParam('faculty'))) != 0) {
            $this->id = $this->objCourseProposals->getID($this->getParam('faculty'));
        }*/
        $this->id = $this->getParam('courseid');
        
        $phone = 'xxxx';
        $lname="x";
        $fname="y";
        $objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');

        $modemail=$this->objFaculty->getModeratorEmail(getParam('faculty'));

        $subject=$objSysConfig->getValue('EMAIL_MODERATOR_SUBJECT', 'ads');
        $body=$objSysConfig->getValue('EMAIL_MODERATOR_BODY', 'ads');
        $linkUrl = $this->uri(array('action'=>'showcourseprophist','courseid'=>$this->id,'selectedtab'=>'0'));

        $body.=' '. str_replace("amp;", "", $linkUrl);
        $body=' '. str_replace("{from_names}", $this->objUser->fullname(), $body);
        $body=' '. str_replace("{proposal_status}", $this->objCourseProposals->getStatus($this->getParam('courseid')), $body);
        $body=' '. str_replace("{proposal}", $this->objCourseProposals->getTitle($this->getParam('courseid')), $body);
        $body=' '. str_replace("{comment}", $this->getParam('commentField'), $body);


        $objMailer = $this->getObject('email', 'mail');
        $objMailer->setValue('to', array($modemail));
        $objMailer->setValue('from', $this->objUser->email());
        $objMailer->setValue('fromName', $this->objUser->fullnames);
        $objMailer->setValue('subject', $subject);
        $objMailer->setValue('body', $body);
        $objMailer->send();

        $this->objDocumentStore->sendProposal($lname, $fname, $modemail, $phone, $this->id,$this->objUser->email(),false);
        $this->nextAction('showcourseprophist',array("message"=>"Proposal send successfully to moderator","courseid"=>$this->id,'selectedtab'=>'0'));
    }
   public function __deleteproposalmember(){
        $memberid=$this->getParam('id');
        $this->id = $this->getParam('courseid');
        $this->objProposalMembers->deleteMember($memberid,$this->id);
        $this->nextAction('showcourseprophist',array("courseid"=>$this->id,'selectedtab'=>'2'));
   }
   public function __addproposalmember(){
        $this->id = $this->getParam('courseid');
        $email=$this->getParam('email');
        $userid=$this->getParam('userid');
        $objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $subject=$objSysConfig->getValue('EMAIL_ADDMEMBER_SUBJECT', 'ads');
        $body=$objSysConfig->getValue('EMAIL_ADDMEMBER_BODY', 'ads');
        $linkUrl = $this->uri(array('action'=>'showcourseprophist','courseid'=>$this->id,'selectedtab'=>'0'));
        $body.=' '. str_replace("amp;", "", $linkUrl);

        $body=' '. str_replace("{from_names}", $this->objUser->fullname(), $body);
        $body=' '. str_replace("{proposal_status}", $this->objCourseProposals->getStatus($this->getParam('courseid')), $body);
        $body=' '. str_replace("{proposal}", $this->objCourseProposals->getTitle($this->getParam('courseid')), $body);
        $body=' '. str_replace("{comment}", $this->getParam('commentField'), $body);


        $objMailer = $this->getObject('email', 'mail');
        $objMailer->setValue('to', array($email));
        $objMailer->setValue('from', $this->objUser->email());
        $objMailer->setValue('fromName', $this->objUser->fullnames);
        $objMailer->setValue('subject', $subject);
        $objMailer->setValue('body', $body);

        $objMailer->send();
        $this->objProposalMembers->saveMember($userid, $this->id);
        $this->nextAction('showcourseprophist',array("courseid"=>$this->id,'selectedtab'=>'2'));

    }
    public function __sendproposal() {

        $email = $this->getParam("email");
        $phone = 'xxxx';
        $this->id = $this->getParam('courseid');
        $lname="x";
        $fname="y";
        $submission = $this->objDocumentStore->sendProposal($lname, $fname, $email, $phone, $this->id,$this->objUser->email());
        $this->nextAction('showcourseprophist',array("message"=>"Proposal send successfully","courseid"=>$this->id,'selectedtab'=>'0'));
    }

    public function __showcourseprophisttest() {
        $this->id = $this->getParam('courseid');
        return "showcourseprophisttest_tpl.php";
    }

    public function __gethistorydata() {
        $objGetData = $this->getObject("getdata");
        $data = $this->getParam('data');
        $version = $this->getParam('version');
        $response = $objGetData->getcoursehistory($this->getParam('courseid'), $this->getParam('formnumber'), $data, $version);
        echo $response;
        die(); // break does not work. throws an error.
    }

    public function __viewcoursedetails() {
        $date = $this->getParam('date');
        $courseid = $this->getParam('courseid');
        $version = $this->getParam('version');

        $this->setVarByRef("date", $date);
        $this->setVarByRef("courseid", $courseid);
        $this->setVarByRef("version", $version);

        return "viewcoursedetails_tpl.php";
    }

    public function __savemoderator() {
        $moderator = $this->getParam('moderator');
        $faculty  = $this->getParam('faculty');
        $this->objFaculty->saveModerator($faculty, $moderator);
        $this->nextAction('facultylist');
    }

    public function __savefaculty() {
        $faculty = $this->getParam('addfaculty');
        $this->objFaculty->saveFaculty($faculty);
        $this->nextAction('facultylist');
    }
    function getValValue($val){
        if($val =='{names}'){
            return $this->objUser->fullname();
        }
        if($val == '{proposal}'){
            return $this->objCourseProposals->getTitle($this->id);
        }
        if($val == '{proposal_status}'){
            return $this->objCourseProposals->getTitle($this->id);
        }
    }
    function parseVar($txt){
        $vals=array(
        "{names}",
        "{proposal_status}",
        "{proposal}",
        "{comment}"
          );
          foreach($vals as $val){
             $txt= str_replace($val, "", $txt);
          }
    }

    public function __savestatus() {
        // save data into the commentadmin table
        $this->objCommentAdmin->saveStatus($this->getParam('title'), $this->getParam('moderator'));
        $this->nextAction('commentadmin');
    }
    public function __updatestatus() {
        // save data into the commentadmin table
        $this->objCommentAdmin->updateStatus($this->getParam('title'), $this->getParam('moderator'), $this->getParam('id'));
        $this->nextAction('commentadmin');
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
