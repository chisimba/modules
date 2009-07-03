<?php

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
// end security check

class ads extends controller
{

   /**
    * var that holds reference to course proposals db object
    */
    public  $objCourseProposals;
    public  $unit_name;
    public  $id;
    public  $objOverview;
    function init()
    {
        //Get language class
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objLog = $this->getObject('logactivity', 'logger');
        $this->objCourseProposals = $this->getObject('dbcourseproposals');
        $this->objUser = $this->getObject ( 'user', 'security' );
        $this->courseid = $this->getParam('courseid');
        $this->objOverview = $this->getObject('dboverview');
        $this->objRules = $this->getObject('dbrules');
        $this->formError = $this->getObject("formerror");
        $this->formValue = $this->getObject("formvalue");
        $this->objContact = $this->getObject("contactinfo");
        $this->objDbClass = $this->getObject("dbcontacttable");
        $this->objTask2 = $this->getObject('dbtask2');
        $this->dispFormE = $this->getObject("dispforme");
        $this->dispFormF = $this->getObject("dispformf");
        //Log this module call
        $this->objLog->log();

    }
       /**
         * Method to override login for certain actions
         * @param <type> $action
         * @return <type>
         */
    public function requiresLogin($action)
    {
        $required = array('overview','addcourseproposal');


        if (in_array($action, $required)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }


    function __submiteditForm($form) {
        $formnumber = $this->getParam('formnumber');
        if (!$this->admin) {
            return "error_tpl.php";
        }
        $userid = $this->getParam("userid");
        $textquestions = $this->getTextQuestions($form);
        $numericalquestions = $this->getNumericalQuestions($form);
        $otherquestions = $this->getOtherQuestions($form);
        $this->errorCheckText($textquestions);
        $this->errorCheckNumeric($numericalquestions);
        $allquestions = array_merge($textquestions, $numericalquestions);
        $allquestions  = array_merge($allquestions, $otherquestions);
        if ($this->formError->numErrors() == 0) {
            $this->updateDatabase($form, $allquestions, $userid);
            return "success_tpl.php";
        }
        else {
            $this->formValue->setAllValues($_POST);
            $this->submitAction = $this->uri(array("action"=>"submiteditform", "formnumber"=>$form, "courseid"=>$this->courseid, "userid"=>$userid));
            return "form$form" . "_tpl.php";
        }
    }

    function __editform() {
        $form = $this->getParam('formnumber');
/*
        if (!$this->admin) {
            return "error_tpl.php";
        }*/
        $userid = $this->getParam("userid");
        $dbname = strtolower($form);
        $database = $this->getObject("section$dbname");
        $values = $database->getValues($userid);
        /*if (count($values) == 0) {
            return "error_tpl.php";
        }*/
        foreach ($values as $fieldvalue) {
            $this->formValue->setValue($fieldvalue['question'], $fieldvalue['value']);
        }
        $this->submitAction = $this->uri(array("action"=>"submiteditform", "formnumber"=>$form, "courseid"=>$this->courseid, "userid"=>$userid));
        return "form$form" . "_tpl.php";
    }
    function __viewform() {
        $form = $this->getParam('formnumber');
        $this->submitAction = $this->uri(array("action"=>"submitform", "formnumber"=>$form, "courseid"=>$this->courseid));
        return "form$form" . "_tpl.php";
    }

    function __submitForm() {
        $form = $this->getParam('formnumber');
        $textquestions = $this->getTextQuestions($form);
        $numericalquestions = $this->getNumericalQuestions($form);
        $otherquestions = $this->getOtherQuestions($form);
        $this->errorCheckText($textquestions);
        $this->errorCheckNumeric($numericalquestions);
        $allquestions = array_merge($textquestions, $numericalquestions);
        $allquestions  = array_merge($allquestions, $otherquestions);
        if ($this->formError->numErrors() == 0) {
            $error = $this->writeToDatabase($form, $allquestions); //currently ignore error and assume user double clicked submit button
            return $this->nextAction('overview', array('id'=>$courseProposalId));
        }
        else {
            $this->formValue->setAllValues($_POST);
            $this->submitAction = $this->uri(array("action"=>"submitform", "formnumber"=>$form, "courseid"=>$this->courseid));
            return "form$form" . "_tpl.php";
        }
    }

           /**
        * Standard Dispatch Function for Controller
        * @param <type> $action
        * @return <type>
        */
    public function dispatch($action)
    {

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
    function getMethod(& $action)
    {
        if ($this->validAction($action)) {
            return '__'.$action;
        } else {
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
    function validAction(& $action)
    {
        if (method_exists($this, '__'.$action)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    function writeToDatabase($form, $questionnumbers) {
        $dbname = strtolower($form);
        $database = $this->getObject("section$dbname");
        $error = "";
        if ($database->checkUser($this->userid) == 0) {
            $error = "Already submitted";
            return $error;
        }
        foreach ($questionnumbers as $question) {
            $database->addRecord($this->courseid, $this->userid, $question, $_POST[$question]);
        }
    }

    function updateDatabase($form, $questionnumbers, $userid) {
        $dbname = strtolower($form);
        $database = $this->getObject("section$dbname");
        foreach ($questionnumbers as $question) {
            $database->updateRecord($this->courseid, $userid, $question, $_POST[$question]);
        }
    }

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
        return $textquestions;
    }

    function getOtherQuestions($form) { //questions with no error checking on them such as checkboxes
        $otherquestions = array();
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
        return $otherquestions;
    }

    function getNumericalQuestions($form) {
        $numericalquestions = array();
        return $numericalquestions;
    }

          /**
       * Method to show the Home Page of the Module
       */
    function __home()
    {
        return "courseproposallist_tpl.php";
    }

    function __overview(){
        $this->unit_name=$this->getParam('unit_name');
        $this->id=$this->getParam('id');
        if ($this->objOverview->exists($this->unit_name,$this->objUser->userId())==0){
            $data=array();
            $prop=$this->objCourseProposals->getCourseProposal($this->id);
            $data['unit_name']= $prop[0]['title'];
            $data['unit_type']='';
            $data['motiv']='';
            $data['qual']='';
            $data['unit_type2']='';
            $this->setVarByRef('data', $data);
            $errors =0;
            $this->setVarByRef('errors', $errors);
            return "overview_tpl.php";
        }
        else
        {
            $data=array();
            $data=$this->objOverview->getOverview($this->unit_name, $this->objUser->userId());
            $this->setVarByRef('data', $data);
            $errors =0;
            $this->setVarByRef('errors', $errors);
            return "overview_tpl.php";
        }
    }
    function __saveoverview(){
        $id=$this->getParam('id');
        $data=array();
        $data['userid']=$this->objUser->userId();
        $data['unit_name']=$this->getParam('unit_name');
        $data['unit_type']=$this->getParam('unit_type');
        $data['motiv']=$this->getParam('motiv');
        $data['qual']=$this->getParam('qual');
        $data['unit_type2']=$this->getParam('unit_type2');
        $error=0;
        foreach($data as $key=>$value){
            if($value==''||strlen($value)>255){
                $error=1;
            }
        }
        if ($error==0){
            $this->objCourseProposals->changeTitle($id,$data['unit_name']);
            $this->objOverview->addOverview($this->getParam('unit_nameorg'),$data);
            return $this->__rulesandsyllabus();
        }
        else
        {
            $this->setVarByRef('data', $data);
            $errors =1;
            $this->setVarByRef('errors', $errors);
            return "overview_tpl.php";
        }
    }

    function __rulesandsyllabus(){
        $this->unit_name=$this->getParam('unit_name');
        $this->id=$this->getParam('id');
        if ($this->objRules->exists($this->unit_name,$this->objUser->userId())==0){
            $data=array();
            $data['unit_name']= $this->unit_name;
            $data['b1']='';
            $data['b2']='';
            $data['b3a']='';
            $data['b3b']='';
            $data['b4a']='';
            $data['b4b']='';
            $data['b4c']='';
            $data['b5a']='';
            $data['b5b']='';
            $this->setVarByRef('data', $data);
            $mode ='';
            $this->setVarByRef('mode', $mode);
            return "rulesandsyllabusbook_tpl.php";
        }
        else
        {
            $data=array();
            $data=$this->objRules->getRules($this->unit_name, $this->objUser->userId());
            $this->setVarByRef('data', $data);
            $mode ='';
            $this->setVarByRef('mode', $mode);
            return "rulesandsyllabusbook_tpl.php";
        }
    }

    function __saverules(){
        $id=$this->getParam('id');
        $data=array();
        $data['userid']=$this->objUser->userId();
        $data['unit_name']=$this->getParam('unit_name');
        $data['b1']=$this->getParam('b1');
        $data['b2']=$this->getParam('b2');
        $data['b3a']=$this->getParam('b3a');
        $data['b3b']=$this->getParam('b3b');
        $data['b4a']=$this->getParam('b4a');
        $data['b4b']=$this->getParam('b4b');
        $data['b4c']=$this->getParam('b4c');
        $data['b5a']=$this->getParam('b5a');
        $data['b5b']=$this->getParam('b5b');
        $mode ='';
        foreach($data as $key=>$value){
            if(strlen($value)>255){
                $mode ='addfixup';
            }
        }
        if($data['b1']=='')
        $mode ='addfixup';
        if($data['b2']=='')
        $mode ='addfixup';
        if($data['b4a']==''||strlen($data['4a'])>32)
        $mode ='addfixup';
        if($data['b5a']==''||strlen($data['4a'])>32)
        $mode ='addfixup';
        if ($mode==''){
            $this->objRules->addRules($data);
            return $this->__home();
        }
        else
        {
            $this->setVarByRef('data', $data);
            $mode ='addfixup';
            $this->setVarByRef('mode', $mode);
            return "rulesandsyllabusbook_tpl.php";
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

    function __sectionE(){
        return "sectionE_tpl.php";
    }
    function __sectionF(){
        return "sectionF_tpl.php";

    }
    function __viewcontacts(){
        $coursenum = $this->getParam("coursenum");
        $this->setVarByRef("coursenum",$coursenum);
        return "viewcontacts_tpl.php";
    }
    function __submitcontacts(){
        $coursenumber=$this->getParam("coursenumber");
        //--Defining parameters from form
        $h1Input = $this->getParam("h1ans");
        //$this->setVarByRef("h1Input",$h1Input);
        //echo $h1Input;
        //die();
        $h2aInput = $this->getParam("h2aans");
        //$this->setVarByRef("h2aInput",$h2aInput);

        $h2bInput = $this->getParam("h2bans");
        //$this->setVarByRef("h2bInput",$h2bInput);

        $h3aInput = $this->getParam("h3aans");
        //$this->setVarByRef("h3aInput",$h3aInput);

        $h3bInput = $this->getParam("h3bans");
        //$this->setVarByRef("h3bInput",$h3bInput);

        //-----Writing to the table/DB...
        $this->objDbClass->addInfo($h1Input,$h2aInput,$h2bInput,$h3aInput,$h3bInput,$coursenumber);

        $this->setVarByRef("coursenum",$coursenumber);
        $this->setVarByRef("title","Submitted Details");
        return "submitcontacts_tpl.php";
    }

    public function  __updatecontacts(){
        $coursenumber=$this->getParam("coursenumber");
        //--Defining parameters from form
        $h1Input = $this->getParam("h1ans");
        //$this->setVarByRef("h1Input",$h1Input);
        //echo $h1Input;
        //die();
        $h2aInput = $this->getParam("h2aans");
        //$this->setVarByRef("h2aInput",$h2aInput);

        $h2bInput = $this->getParam("h2bans");
        //$this->setVarByRef("h2bInput",$h2bInput);

        $h3aInput = $this->getParam("h3aans");
        //$this->setVarByRef("h3aInput",$h3aInput);

        $h3bInput = $this->getParam("h3bans");
        //$this->setVarByRef("h3bInput",$h3bInput);

        //-----Writing to the table/DB...
        $this->objDbClass->updateInfo($h1Input,$h2aInput,$h2bInput,$h3aInput,$h3bInput,$coursenumber);

        $this->setVarByRef("coursenum",$coursenumber);
        $this->setVarByRef("title","Updated Details");
        return "updatecontacts_tpl.php";
    }
}
?>
