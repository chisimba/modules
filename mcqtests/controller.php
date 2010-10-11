<?php

/**
 * testadmin class extends controller
 * @package testadmin
 * @filesource
 */
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
 * Module class to handle internal test admin
 *
 * @author Megan Watson, Kevin Cyster
 *
 * @copyright (c) 2004 UWC
 * @package mcqtests
 * @version 0.1
 */
class mcqtests extends controller {

    /**
     * @var string $user The full name of the current logged in user
     */
    protected $user;
    /**
     * @var string $userId The userId of the current logged in user
     */
    protected $userId;
    /**
     * @var string $email The email address of the current logged in user
     */
    protected $email;
    /**
     * @var array $arrComLabs Array containg all computer laboratory files
     */
    protected $arrComLabs;
    /**
     * @var array $assignment A boolean value indicating if the Assignment module is registered
     */
    protected $assignment;
    /**
     *
     * @var object to hold tbl description class
     */
    public $dbDescription;
    /**
     *
     * @var object to hold tbl category class
     */
    public $dbCategory;
    /**
     *
     * @var object to hold formmanager class
     */
    public $objFormManager;

    /**
     * Method to construct the class.
     *
     * @access public
     * @return
     */
    public function init() {
        // Check if the assignment module is registered and can be linked to.
        $this->objModules = $this->newObject('modules', 'modulecatalogue');
        $this->assignment = FALSE;
        if ($this->objModules->checkIfRegistered('Assignment Management', 'assignment')) {
            $this->assignment = TRUE;
        }

        // get the user object
        $this->objFormManager = $this->getObject('formmanager');
        $this->dbDescription = $this->newObject('dbdescription');
        $this->dbCategory = $this->newObject('dbcategory');
        $this->dbTestadmin = $this->newObject('dbtestadmin');
        $this->dbQuestions = $this->newObject('dbquestions');
        $this->dbAnswers = $this->newObject('dbanswers');
        $this->dbMarked = $this->newObject('dbmarked');
        $this->dbResults = $this->newObject('dbresults');
        $this->objUser = $this->newObject('user', 'security');
        $this->objLanguage = $this->newObject('language', 'language');
        $this->objDate = $this->newObject('dateandtime', 'utilities');
        $this->objConfig = $this->newObject('altconfig', 'config');
        $this->objMkdir = $this->newObject('mkdir', 'files');
        $this->user = $this->objUser->fullname();
        $this->userId = $this->objUser->userId();
        $this->email = $this->objUser->email($this->userId);
        $this->objMail = $this->newObject('dbemail', 'internalmail');
        $this->objEmailFiles = $this->newObject('emailfiles', 'internalmail');
        $this->objWashout = $this->getObject('washout', 'utilities');
        $this->objContextGroups = $this->getObject('managegroups', 'contextgroups');

        // context
        $this->objContext = $this->newObject('dbcontext', 'context');
        $this->objContentNodes = $this->newObject('dbcontentnodes', 'context');
        $this->contextCode = $this->objContext->getContextCode();
        $this->contextUsers = $this->newObject('managegroups', 'contextgroups');
        $this->objCond = $this->newObject('contextCondition', 'contextpermissions');

        //Load Module Catalogue Class
        $this->objModuleCatalogue = $this->getObject('modules', 'modulecatalogue');

        $this->objContextGroups = $this->getObject('managegroups', 'contextgroups');

        if ($this->objModuleCatalogue->checkIfRegistered('activitystreamer')) {
            $this->objActivityStreamer = $this->getObject('activityops', 'activitystreamer');
            $this->eventDispatcher->addObserver(array($this->objActivityStreamer, 'postmade'));
            $this->eventsEnabled = TRUE;
        } else {
            $this->eventsEnabled = FALSE;
        }


        // Log this call if registered
        if (!$this->objModules->checkIfRegistered('logger', 'logger')) {
            //Get the activity logger class
            $this->objLog = $this->newObject('logactivity', 'logger');
            //Log this module call
            $this->objLog->log();
        }
        $this->arrComLabs = $this->getLabs();
    }

    /**
     * Standard dispatch method
     *
     * @access public
     * @param string $action The action to be performed
     * @return
     */
    public function dispatch($action) {
        if ($this->contextCode == '') {
            return $this->nextAction(NULL, NULL, '_default');
        }
        // Now the main switch for $action
        switch ($action) {
            case 'addcategory':
                return 'addcategory_tpl.php';
                break;
            case 'addcategoryconfirm':
                //Get the cat id
                $id = $this->getParam('id', Null);
                //Fetch the form data into an array for insertion/update
                $fields = array();
                $fields['parentcategoryid'] = $this->getParam('parentId', Null);
                $fields['name'] = $this->getParam('categoryname', Null);
                $fields['categoryinfo'] = $this->getParam('desc', Null);
                //Insert/Update
                $id = $this->dbCategory->addCategory($fields, $id);
                return $this->nextAction('view', array('id' => $id));
            case 'addeditdesc':
                $this->setLayoutTemplate("mcqtests_layout_tpl.php");
                //Get the test id
                $id = $this->getParam('id', Null);
                //Get desc id if its an edit
                $descId = $this->getParam('descid', Null);
                $test = $this->getParam('test', Null);
                $this->setVarByRef('id', $id);
                $this->setVarByRef('descId', $descId);
                $this->setVarByRef('test', $test);
                return 'description_tpl.php';
            case 'adddescconfirm':
                //Get the test id
                $id = $this->getParam('id', Null);
                //Get desc id if its an edit
                $descId = $this->getParam('descid', Null);
                //Fetch the form data into an array for insertion/update
                $fields = array();
                $fields['categoryid'] = $this->getParam('desccategoryid', Null);
                $fields['questionname'] = $this->getParam('descqnname', Null);
                $fields['questiontext'] = $this->getParam('descqntext', Null);
                $fields['feedback'] = $this->getParam('descgenfeedback', Null);
                $fields['tags'] = $this->getParam('descofficialtags', Null);
                $fields['othertags'] = $this->getParam('descothertags', Null);
                $id = $this->dbDescription->addDescription($fields, $descId);
                return $this->nextAction('addeditdesc', array('id' => $id));
            case 'activatetest':
                $id = $this->getParam('id', '');
                $this->applyChangeStatus();
                return $this->nextAction('view', array(
                    'id' => $id
                ));
            case 'addnewquestion': {
                    $option = $this->getParam('qnoption');
                    if ($option == 'mcq') {
                        return $this->addMcqQuestion();
                    }
                    if ($option == 'freeform') {
                        return $this->addFreeForm();
                    }
                }
            // create an interface to choose a questiontype
            case 'choosequestiontype':
                $this->viewtest();
                $id = $this->getParam('id');
                $count = $this->getParam('count');
                $this->setVarByRef('testid', $id);
                $this->setVarByRef('count', $count);
                $test = $this->dbTestadmin->getTests($this->contextCode, 'id,name,totalmark', $id);
                $oldQuestions = $this->dbTestadmin->getContextQuestions($this->contextCode, $id);

                // Get the total number of questions if this isn't the first
                if ($count > 1) {
                    $count = $this->dbQuestions->countQuestions($id);
                }
                $test[0]['count'] = $count;
                $this->setVarByRef('test', $test[0]);
                $this->setVar('mode', 'add');
                $this->setVar('oldQuestions', $oldQuestions);

                return 'choosequestiontype_tpl.php';
                break;
            // create an interface to choose a questiontype
            case 'choosequestiontype2':
                $this->viewtest();
                $id = $this->getParam('id');
                $count = $this->getParam('count');
                $this->setVarByRef('testid', $id);
                $this->setVarByRef('count', $count);
                $test = $this->dbTestadmin->getTests($this->contextCode, 'id,name,totalmark', $id);
                $oldQuestions = $this->dbTestadmin->getContextQuestions($this->contextCode, $id);

                // Get the total number of questions if this isn't the first
                if ($count > 1) {
                    $count = $this->dbQuestions->countQuestions($id);
                }
                $test[0]['count'] = $count;
                $this->setVarByRef('test', $test[0]);
                $this->setVar('mode', 'add');
                $this->setVar('oldQuestions', $oldQuestions);

                return 'choosequestiontype2_tpl.php';
                break;

            case 'addquestion':
                $id = $this->getParam('id', NULL);
                $count = $this->getParam('count');

                $test = $this->dbTestadmin->getTests($this->contextCode, 'id,name,totalmark', $id);

                // Get the total number of questions if this isn't the first
                if ($count > 1) {
                    $count = $this->dbQuestions->countQuestions($id);
                }
                $test[0]['count'] = $count;
                $this->setVarByRef('test', $test[0]);
                $this->setVar('mode', 'add');
                return 'addquestion_tpl.php';
                break;
            // add the question to the database
            case 'applyaddquestion':
                $postSave = $this->getParam('save');
                $id = $this->getParam('testId', '');
                if ($postSave == $this->objLanguage->languageText('word_cancel')) {
                    return $this->nextAction('view', array(
                        'id' => $id
                    ));
                }
                if ($postSave == $this->objLanguage->languageText('word_save')) {
                    $imgConfirm = $this->getParam('imageconfirm', '');
                    $hintConfirm = $this->getParam('enablehint', '');
                    $postMark = $this->getParam('mark', 0);

                    $qType = $this->getParam('type');
                    $fields = array();
                    $fields['testid'] = $id;
                    if ($this->getParam('formtype') == 'freeform')
                        $fields['question'] = $this->getParam('freeformquestion', '');
                    else
                        $fields['question'] = $this->getParam('question', '');
                    $hint = $this->getParam('hint', '');
                    if ($hintConfirm == 'no') {
                        $hint = '';
                    }
                    $fields['hint'] = $hint;
                    $fields['mark'] = $postMark;
                    $fields['questionorder'] = $this->getParam('qOrder', '');
                    $fields['questiontype'] = $qType;
                    $qId = $this->getParam('questionId', '');

                    // Add to database and set the total mark for the test
                    $qId = $this->dbQuestions->addQuestion($fields, $qId);
                    $this->dbTestadmin->setTotal($id, $this->dbQuestions->getTotalMarks($id));

                    if ($qType == 'mcq') {
                        return $this->nextAction('addanswers', array(
                            'questionId' => $qId,
                            'testId' => $id,
                            'count' => $this->getParam('qOrder'),
                            'qNum' => $this->getParam('options')
                        ));
                    }
                    if ($qType == 'tf') {
                        return $this->nextAction('addanswers', array(
                            'questionId' => $qId,
                            'testId' => $id,
                            'count' => $this->getParam('qOrder'),
                            'qNum' => 2,
                            'truefalse' => true
                        ));
                    }
                    if ($qType == 'freeform') {
                        return $this->nextAction('addfreeformanswers', array(
                            'questionId' => $qId,
                            'testId' => $id,
                            'count' => $this->getParam('qOrder')
                        ));
                    }
                }
                break;
            case 'addfreeform':
                $id = $this->getParam('id', NULL);
                $count = $this->getParam('count');
                $test = $this->dbTestadmin->getTests($this->contextCode, 'id,name,totalmark', $id);

                // Get the total number of questions if this isn't the first
                if ($count > 1) {
                    $count = $this->dbQuestions->countQuestions($id);
                }
                $test[0]['count'] = $count;
                $this->setVarByRef('test', $test[0]);
                $this->setVar('mode', 'add');

                return 'addfreeform_tpl.php';
                break;


            case 'addstep':
                $this->setVar('mode', 'add');
                return 'addstep_tpl.php';

            case 'savestep':
                $currentstep = $this->getParam('currentstep');

                switch ($currentstep) {
                    case '1':
                        $this->setVar('mode', 'edit');
                        return 'addstep_tpl.php';
                        break;
                    case '2':
                        $StepMenuArr = array();
                        $StepMenuArr['status'] = $this->getParam('status');
                        $StepMenuArr['name'] = $this->getParam('name');
                        $StepMenuArr['description'] = $this->getParam('description');
                        $StepMenuArr['testType'] = $this->getParam('testType');
                        $StepMenuArr['qSequence'] = $this->getParam('qSequence');
                        $StepMenuArr['aSequence'] = $this->getParam('aSequence');
                        $StepMenuArr['coursePermissions'] = $this->getParam('coursePermissions');
                        $StepMenuArr['save'] = $this->getParam('save');

                        $this->setSession('stepmenu1', null);
                        $this->setSession('stepmenu1', $StepMenuArr);
                        $StepMenuArr = null;

                        return $this->nextAction('savestep', array('currentstep' => '2a'));

                    case '2a':
                        return 'addstep_tpl.php';
                        break;
                    case '3':
                        $StepMenuArr2 = array();
                        $StepMenuArr2['percent'] = $this->getParam('mark');
                        $StepMenuArr2['decimal'] = $this->getParam('decimal');
                        $StepMenuArr2['setequal'] = $this->getParam('setequal');
                        $StepMenuArr2['start'] = $this->getParam('start');
                        $StepMenuArr2['close'] = $this->getParam('close');
                        $StepMenuArr2['timed'] = $this->getParam('timed');
                        $StepMenuArr2['hour'] = $this->getParam('hour');
                        $StepMenuArr2['min'] = $this->getParam('min');
                        $StepMenuArr2['save'] = $this->getParam('save');
                        $this->setSession('stepmenu2', null);
                        $this->setSession('stepmenu2', $StepMenuArr2);
                        $StepMenuArr = null;
                        //add to activity log
                        if ($this->eventsEnabled) {
                            $message = $this->objUser->getsurname() . " " . $this->objLanguage->languageText('mod_mcqtests_newmcq', 'mcqtests') . " " . $this->objContext->getContextCode();
                            $this->eventDispatcher->post($this->objActivityStreamer, "context", array('title' => $message,
                                'link' => $this->uri(array()),
                                'contextcode' => $this->objContext->getContextCode(),
                                'author' => $this->objUser->fullname(),
                                'description' => $message));
                        }

                        return $this->nextAction('savestep', array('currentstep' => '3a'));
                        break;
                    case '3a':
                        return 'addstep_tpl.php';
                        break;
                    default:
                        $step_data1 = $this->getSession('stepmenu1');
                        $step_data2 = $this->getSession('stepmenu2');
                        //merge 2 arrays
                        $fields = array();
                        $fields['status'] = $step_data1['status'];
                        $fields['name'] = $step_data1['name'];
                        $fields['description'] = $step_data1['description'];
                        $fields['testType'] = $step_data1['testType'];
                        $fields['qSequence'] = $step_data1['qSequence'];
                        $fields['aSequence'] = $step_data1['aSequence'];
                        $fields['percent'] = $step_data2['percent'];
                        $fields['decimal'] = $step_data2['decimal'];
                        $fields['setequal'] = $step_data2['setequal'];
                        $fields['start'] = $step_data2['start'];
                        $fields['close'] = $step_data2['close'];
                        $fields['timed'] = $step_data2['timed'];
                        $fields['hour'] = $step_data2['hour'];
                        $fields['min'] = $step_data2['min'];
                        $fields['comLab'] = $this->getParam('comLab');
                        $fields['coursePermissions'] = $step_data1['coursePermissions'];
                        //saving the step data

                        $id = $this->StepAddTest($fields);
                        return $this->nextAction('view', array('id' => $id));
                        break;
                }

            case 'addtest':
                return $this->addTest();
            // add a test to the database

            case 'applyaddtest':
                $postSave = $this->getParam('save');
                if ($postSave == $this->objLanguage->languageText('word_cancel')) {
                    return $this->nextAction('');
                }
                $id = $this->applyAddTest();
                return $this->nextAction('view', array(
                    'id' => $id
                ));
            // display template to edit a test

            case 'edit':
                return $this->editTest();

            // delete a test
            case 'delete':
                $this->dbTestadmin->deleteTest($this->getParam('id'));
                $back = $this->getParam('back');
                if (!empty($back)) {
                    Header("Location: " . $this->uri(array(
                                'action' => 'viewbyletter'
                                    ), $back));
                    break;
                }
                return $this->nextAction('');
            // display template showing the test and questions


            case 'viewteststep':
                return 'viewteststep_tpl.php';

            case 'view':
                return $this->viewTest();
            // Display template to edit a question
            case 'editquestion':
                $data = $this->dbQuestions->getQuestion($this->getParam('questionId'));
                $test = $this->dbTestadmin->getTests($this->contextCode, 'id,name,totalmark', $data[0]['testid']);
                $this->setVarByRef('test', $test[0]);
                $this->setVarByRef('data', $data[0]);

                $numAnswers = $this->dbAnswers->countAnswers($this->getParam('questionId'));
                $this->setVarByRef('numAnswers', $numAnswers);
                $this->setVar('mode', 'edit');
                $type = $this->getParam('type');
                $questionid = $this->getParam('questionId');
                $this->setVarByRef('questionid', $questionid);
                if ($type == 'numerical') {
                    return 'editnumericalquestion_tpl.php';
                } else if ($type == 'matching') {
                    return 'editmatchingquestion_tpl.php';
                } else if ($data[0]['questiontype'] == 'freeform') {
                    return 'addfreeform_tpl.php';
                } else {
                    return 'addquestion_tpl.php';
                }
                break;

            // delete a question
            case 'deletequestion':
                $this->dbQuestions->deleteQuestion($this->getParam('questionId'));
                //$this->dbTestadmin->setTotal($this->getParam('id') , -$this->getParam('mark'));
                $this->dbTestadmin->setTotal($this->getParam('id'), $this->dbQuestions->getTotalMarks($this->getParam('id')));
                return $this->nextAction('view', array(
                    'id' => $this->getParam('id')
                ));
            case 'questionup':
                $this->dbQuestions->changeOrder($this->getParam('questionId'), TRUE);
                return $this->nextAction('view', array(
                    'id' => $this->getParam('id')
                ));
            case 'questiondown':
                $this->dbQuestions->changeOrder($this->getParam('questionId'), FALSE);
                return $this->nextAction('view', array(
                    'id' => $this->getParam('id')
                ));
            // save the question to the database and call next action to add answers
            case 'addanswers':
                $questionId = $this->getParam('questionId');
                $testId = $this->getParam('testId');
                $qNum = $this->getParam('qNum');
                $data[0]['count'] = $this->getParam('count');
                $truefalse = $this->getParam('truefalse');

                $data = $this->dbQuestions->getQuestion($questionId);
                $answers = $this->dbAnswers->getAll("WHERE testid = '$testId' AND questionid = '$questionId'");

                $this->setVarByRef('data', $data[0]);
                $this->setVarByRef('qNum', $qNum);
                $this->setVarByRef('answers', $answers);
                $this->setVarByRef('truefalse', $truefalse);

                $correctAnswerNum = $this->dbAnswers->getCorrectAnswer($questionId);
                $this->setVarByRef('correctAnswerNum', $correctAnswerNum);

                if ($answers == null) {
                    $this->setVar('mode', 'add');
                } else {
                    $this->setVar('mode', 'edit');
                }
                return 'addanswer_tpl.php';
            // save answers to the database

            case 'addfreeformanswers':

                $questionId = $this->getParam('questionId');
                $testId = $this->getParam('testId');
                $qNum = $this->getParam('qNum');
                $data[0]['count'] = $this->getParam('count');

                $data = $this->dbQuestions->getQuestion($questionId);
                $answers = $this->dbAnswers->getAll("WHERE testid = '$testId' AND questionid = '$questionId'");

                $this->setVarByRef('data', $data[0]);
                $this->setVarByRef('qNum', $qNum);
                $this->setVarByRef('answers', $answers);


                if ($answers == null) {
                    $this->setVar('mode', 'add');
                } else {
                    $this->setVar('mode', 'edit');
                }
                return 'addfreeformanswer_tpl.php';
            // save answers to the database


            case 'saveanswer':
                $postAns = $this->getParam('correctans', NULL);
                $postCorId = $this->getParam('correctId', NULL);
                if (!$postAns) {
                    $postAns = $this->getParam('firstans', '');
                }
                if (!$postCorId) {
                    $this->dbAnswers->setCorrect($postAns, 1);
                } else if ($postAns != $postCorId) {
                    $this->dbAnswers->setCorrect($postAns, 1);
                    $this->dbAnswers->setCorrect($postCorId, 0);
                }
            // var_dump($postAns);
            // var_dump($postCorId);

            case 'applyaddanswer':
                $postSave = $this->getParam('save', '');
                $postTestId = $this->getParam('testId', '');
                $postQuestionId = $this->getParam('questionId', '');
                $qNum = $this->getParam('qNum', '');
                if ($postSave == $this->objLanguage->languageText('word_cancel')) {
                    return $this->nextAction('editquestion', array(
                        'questionId' => $postQuestionId
                    ));
                }

                $this->addAnswers($postTestId, $postQuestionId, $_POST, $qNum);
                $msg = $this->objLanguage->languageText('mod_mcqtests_confirmaddanswer', 'mcqtests');
                $this->setSession('confirm', $msg);
                return $this->nextAction('view', array(
                    'id' => $postTestId,
                    'qNum' => $qNum
                ));

            case 'applyfreeformanswer':
                $postSave = $this->getParam('save', '');
                $postTestId = $this->getParam('testId', '');
                $postQuestionId = $this->getParam('questionId', '');

                if ($postSave == $this->objLanguage->languageText('word_cancel')) {
                    return $this->nextAction('editquestion', array(
                        'questionId' => $postQuestionId
                    ));
                }
                $this->addAnswers($postTestId, $postQuestionId, $_POST, 4);
                $msg = $this->objLanguage->languageText('mod_mcqtests_confirmaddanswer', 'mcqtests');
                $this->setSession('confirm', $msg);

                return $this->nextAction('view', array(
                    'id' => $postTestId,
                    'qNum' => $qNum
                ));
            // display template to edit a specified answer

            case 'editanswer':
                $answer = $this->dbAnswers->getAnswer($this->getParam('answerId'));
                $data = $this->dbQuestions->getQuestion($this->getParam('questionId'));
                $this->setVarByRef('answer', $answer[0]);
                $this->setVarByRef('data', $data[0]);
                $this->setVar('mode', 'edit');
                return 'addanswer_tpl.php';
            // delete an answer

            case 'deleteanswer':
                $this->dbAnswers->deleteAnswer($this->getParam('answerId'));
                return $this->nextAction('editquestion', array(
                    'questionId' => $this->getParam('questionId')
                ));
            case 'mark':
            case 'liststudents':
                $test = $this->dbTestadmin->getTests($this->contextCode, 'id, name, totalmark', $this->getParam('id'));
                $data = $this->dbResults->getResults($this->getParam('id'));
                $totalmark = $this->dbQuestions->sumTotalmark($this->getParam('id'));
                $this->setVarByRef('test', $test[0]);
                $this->setVarByRef('data', $data);
                $this->setVarByRef('totalmark', $totalmark);
                return 'list_test_tpl.php';
            case 'showtest':
                return $this->showTest();
            case 'reopen':
                $testId = $this->getParam('id');
                $studentId = $this->getParam('studentId');
                $this->dbMarked->deleteMarked($studentId, $testId);
                $this->dbResults->deleteResult($testId, $studentId);
                return $this->nextAction('liststudents', array(
                    'id' => $testId
                ));
            case 'export':
                $testId = $this->getParam('testId');
                $this->setVarByRef('testId', $testId);
                return 'export_tpl.php';
                break;

            case 'doexport':
                $testId = $this->getParam('testId');
                $testData = $this->dbTestadmin->getTests('', 'totalmark', $testId);
                $exportType = $this->getParam('exporttype');
                $contentRoot = $this->objConfig->getcontentBasePath();
                $fileLocation = $contentRoot . 'modules/mcqtests';
                if (!is_dir($fileLocation)) {
                    $this->objMkdir->fullFilePath = $fileLocation;
                    $this->objMkdir->makedir();
                }
                $file = $fileLocation . '/' . $testId . '.csv';
                if ($exportType == 'answers') {
                    $usersResultList = $this->dbResults->getResults($testId);
                    if (isset($usersResultList) && !empty($usersResultList)) {
                        $outputFile = fopen($file, 'wb');
                        fwrite($outputFile, '"Student Number","Student name","Start time","End time","Answers selected"' . "\n");
                        foreach ($usersResultList as $user) {
                            $userAnswerList = $this->dbMarked->getAnswersForOutput($testId, $user['studentid']);
                            $line = $userAnswerList[0]['studentid'] . ",";
                            $line.= $this->objUser->fullname($userAnswerList[0]['studentid']) . ",";
                            $line.= $userAnswerList[0]['starttime'] . ",";
                            $line.= $userAnswerList[0]['endtime'] . ",";
                            if (isset($userAnswerList) && !empty($userAnswerList)) {
                                foreach ($userAnswerList as $answer) {
                                    $value = isset($answer['answerorder']) ? $answer['answerorder'] : 'NULL';
                                    $line.= $value . ",";
                                }
                            }
                            fwrite($outputFile, $line . "\n");
                        }
                        fclose($outputFile);
                        return $this->nextAction('emailresults', array(
                            'file' => $file,
                            'testId' => $testId
                        ));
                    } else {
                        return $this->nextAction('');
                    }
                } else {
                    $usersResultList = $this->dbResults->getResults($testId);
                    if (isset($usersResultList) && !empty($usersResultList)) {
                        $outputFile = fopen($file, 'wb');
                        fwrite($outputFile, '"Student Number","Student name","Score","Percentage"' . "\n");
                        foreach ($usersResultList as $user) {
                            $line = $user['studentid'] . ",";
                            $line.= $this->objUser->fullname($user['studentid']) . ",";
                            @ $line.= $user['mark'] . ",";
                            $line.= ( round(($user['mark'] / $testData[0]['totalmark']), 4) * 100) . "%,";
                            fwrite($outputFile, $line . "\n");
                        }
                        fclose($outputFile);
                        return $this->nextAction('emailresults', array(
                            'file' => $file,
                            'testId' => $testId
                        ));
                    }
                }
            case 'emailresults':
                $testId = $this->getParam('testId');
                $file = $this->getParam('file');
                $testData = $this->dbTestadmin->getTests('', 'name', $testId);
                $emailSubject = $this->objLanguage->languageText('mod_mcqtests_emailsubject', 'mcqtests');
                $array = array(
                    'filename' => 'results.csv',
                    'item' => $testData[0]['name']
                );
                $emailBody = $this->objLanguage->code2Txt('mod_mcqtests_emailbody', 'mcqtests', $array);
                $this->objEmailFiles->prepareAttachment($file, 'results.csv', 'text/x-comma-separated-values');
                $emailId = $this->objMail->sendMail($this->userId, $emailSubject, $emailBody, 0);
                return $this->home($testId);
            case 'addlab':
                $id = $this->getParam('id');
                $mode = $this->getParam('mode');
                $error = $this->getParam('error');
                $this->setVarByRef('id', $id);
                $this->setVarByRef('mode', $mode);
                $this->setVar('error', $error);
                return 'addlab_tpl.php';
                break;

            case 'applyaddlab':
                $id = $this->getParam('id');
                $mode = $this->getParam('mode');
                $file = $_FILES;
                if ($file['comLab']['type'] != 'text/x-comma-separated-values') {
                    return $this->nextAction('addlab', array(
                        'id' => $id,
                        'mode' => $mode,
                        'error' => TRUE
                    ));
                } else {
                    $contentRoot = $this->objConfig->getcontentBasePath();
                    $fileLocation = $contentRoot . '/modules/mcqtests/';
                    if (!is_dir($fileLocation)) {
                        $this->objMkdir->fullFilePath = $fileLocation;
                        $this->objMkdir->makedir();
                    }
                    $labFileLocation = $fileLocation . $file['comLab']['name'];
                    move_uploaded_file($file['comLab']['tmp_name'], $labFileLocation);
                    if ($mode == 'add') {
                        return $this->nextAction('addtest', array(
                            'mode' => $mode
                                ), 'mcqtests');
                    } else {
                        return $this->nextAction('edit', array(
                            'id' => $id,
                            'mode' => $mode
                                ), 'mcqtests');
                    }
                }
                break;

            case 'answertest':
                $testId = $this->getParam('id');
                $check = $this->getSession('taketest', NULL);


                if ($check != 'open') {
                    $this->unsetSession('qData');
                    $resultId = $this->closeTest($testId);
                    $this->setSession('taketest', 'open');
                }
                $this->setVarByRef('check', $check);
                $this->setVarByRef('resultId', $resultId);
                return $this->setTest($testId);
            case 'previewtest':
                $testId = $this->getParam('id');
                $num = $this->getParam('num');
                if ($num <= 0) {
                    $num = 0;
                }
                return $this->previewTest($testId, $num);
            case 'continuetest':
                $this->unsetSession('taketest');
                $resultId = $this->getParam('resultId', NULL);
                $resultId = $this->saveTest($resultId);
                $testDuration = $this->getParam('testduration', NULL);
                $mode = $this->getParam('mode', 'mode');
                $this->setVarByRef('testDuration', $testDuration);
                $this->setVarByRef('resultId', $resultId);
                $this->setVarByRef('mode', $mode);
                return $this->setTest($this->getParam('id'), $this->getParam('qnum', ''));
            case 'marktest':
                $this->unsetSession('qData');
                $this->unsetSession('taketest');
                $resultId = $this->getParam('resultId', NULL);
                $this->saveTest($resultId);
                $this->markTest($resultId);
                $this->setVar('closeWin', TRUE);
                $this->setVar('qnum', NULL);
                $this->setVar('resultId', NULL);
                $this->setVar('test', NULL);
                $this->setVar('data', NULL);
                return 'answertest_tpl.php';
            case 'marktest2':
                echo $this->markTest($this->getParam('resultId', NULL));

                break;
            case 'showstudenttest':
                return $this->showStudentTest();
            case 'submitdbquestions':
                $status = $this->submitDBQuestions($this->getParam('ids'));
                return $status; //$this->nextAction('view', array('id' => $id) , 'mcqtests');
            case 'formattedquestions':
                $myParams = explode("&", $this->getParam('myParams'));
                $type = explode("=", $myParams[0]);
                $type = $type[1];
                $courses = explode("=", $myParams[1]);
                $courses = $courses[1];
                $start = $this->getParam('start');
                $limit = $this->getParam('limit');
                return $this->getGridData($type, $courses, $start, $limit);

            case 'previewquestion':
                $id = $this->getParam('id');
                return $this->previewQuestion($id);
            case 'calcqform':
                $id = $this->getParam('id');
                return $this->calcqForm();
            case 'addmatchingquestion':
                $qtype = $this->objLanguage->languageText('mod_mcqtests_matching', 'mcqtests');
                if ($this->getParam('edit')) {
                    if ($this->getParam('edit') == 'true') {
                        $edit = true;
                    }
                    $id = $this->addGeneralFormQuestions($qtype, $edit);
                    $this->addMatchingQuestions($id, $edit);
                } else {
                    $id = $this->addGeneralFormQuestions($qtype);
                    $this->addMatchingQuestions($id);
                }

                return $this->nextAction('view', array('id' => $this->getParam('id')));
            case 'addnumericalquestion':
                $qtype = $this->objLanguage->languageText('mod_mcqtests_numerical', 'mcqtests');
                if ($this->getParam('edit')) {
                    if ($this->getParam('edit') == 'true') {
                        $edit = true;
                    }
                    $id = $this->addGeneralFormQuestions($qtype, $edit);
                    $this->addNumericalQuestions($id, $edit);
                } else {
                    $id = $this->addGeneralFormQuestions($qtype);
                    $this->addNumericalQuestions($id);
                }
                return $this->nextAction('view', array('id' => $this->getParam('id')));
            default:
                if ($this->objCond->isContextMember('Students')) {
                    $this->unsetSession('taketest');
                    return $this->studentHome();
                } else {
                    return $this->home();
                }
        }
    }

    /**
     * Method to override isValid to enable administrators to perform certain action
     *
     * @param $action Action to be taken
     * @return boolean
     */
    public function isValid($action) {
        if ($this->objUser->isAdmin() || $this->objContextGroups->isContextLecturer()) {
            return TRUE;
        } else {
            return FALSE; //parent::isValid ( $action );
        }
    }

    /**
     * Method to display a list of tests in the test home page.
     *
     * @access private
     * @param string $testId The id of the test results were exported for
     * @return
     */
    private function home($testId = NULL) {
        $data = $this->dbTestadmin->getTests($this->contextCode);
        if (!empty($data)) {
            foreach ($data as $key => $line) {
                $sql = "SELECT title FROM tbl_context_nodes WHERE ";
                $sql.= "id = '" . $line['chapter'] . "'";
                $nodes = $this->objContentNodes->getArray($sql);
                if (!empty($nodes)) {
                    $data[$key]['node'] = $nodes[0]['title'];
                } else {
                    $data[$key]['node'] = '';
                }
            }
        }
        $this->setVarByRef('testId', $testId);
        $this->setVarByRef('data', $data);
        return 'index_tpl.php';
    }

    /**
     * Method to get the context child nodes and display form to add a new test.
     *
     * @access private
     * @return
     */
    private function addTest() {
        $nodesSQL = 'SELECT tbl_context_nodes.id AS chapter_id,
        tbl_context_nodes.title AS chapter_title FROM tbl_context_nodes
        INNER JOIN tbl_context_parentnodes ON ( tbl_context_parentnodes_id =
        tbl_context_parentnodes.id )
        WHERE tbl_context_parentnodes.tbl_context_parentnodes_has_tbl_context_tbl_context_contextCode
        = "' . $this->contextCode . '"'; // AND parent_Node = "" ';
        $nodes = $this->objContentNodes->getArray($nodesSQL);
        $allPercent = $this->dbTestadmin->getPercentage($this->contextCode);
        $this->setVarByRef('nodes', $nodes);
        $this->setVarByRef('allPercent', $allPercent);
        $this->setVar('mode', 'add');
        return 'addtest_tpl.php';
    }

    /**
     * Method to add a new test
     *
     * @access private
     * @return string $id The id of the new test
     */
    private function StepAddTest($data) {
        $id = $this->getParam('id', '');
        $fields = array();
        $fields['name'] = $data['name'];
        $fields['context'] = $this->contextCode;
        $fields['userid'] = $this->userId;
        // $fields['chapter'] = $this->getParam('chapter', '');
        $fields['status'] = $data['status'];
        $percent = $data['percent'];
        $decimal = $data['decimal'];
        $fields['percentage'] = $percent . '.' . $decimal;
        $postTimed = $data['timed'];
        if (!empty($postTimed)) {
            $fields['timed'] = 1;
        } else {
            $fields['timed'] = 0;
        }
        $fields['duration'] = ($data['hour'] * 60) + $data['min'];
        $startDate = $data['start'];
        $closeDate = $data['close'];
        $fields['startdate'] = $startDate;
        $fields['closingdate'] = $closeDate;
        $fields['testtype'] = $data['testType'];
        $fields['qsequence'] = $data['qSequence'];
        $fields['asequence'] = $data['aSequence'];
        $fields['comlab'] = $data['comLab'];
        $fields['description'] = $data['description'];
        $fields['updated'] = date('Y-m-d H:i:s');
        $fields['coursePermissions'] = $data['coursePermissions'];
        $id = $this->dbTestadmin->addTest($fields, $id);
        // set all tests to equal percentages
        $postEqual = $this->getParam('setequal', '');
        if (isset($postEqual) && !empty($postEqual)) {
            $tests = $this->dbTestadmin->getTests($this->contextCode, 'id, percentage');
            $num = count($tests);
            $percent = round((100 / $num), 2);
            $arrField = array(
                'percentage' => $percent
            );
            foreach ($tests as $item) {
                $this->dbTestadmin->addTest($arrField, $item['id']);
            }
        }
        return $id;
    }

    /**
     * Method to add change status of test
     *
     * @access private
     * @return void
     */
    private function applyChangeStatus() {
        $id = $this->getParam('id', '');
        $fields = array();
        $fields['status'] = $this->getParam('status', '');
        $id = $this->dbTestadmin->addTest($fields, $id);
        return;
    }

    /**
     * Method to add a new test
     *
     * @access private
     * @return string $id The id of the new test
     */
    private function applyAddTest() {

        $id = $this->getParam('id', '');
        $fields = array();
        $fields['name'] = $this->getParam('name', '');
        $fields['context'] = $this->contextCode;
        $fields['userid'] = $this->userId;
        // $fields['chapter'] = $this->getParam('chapter', '');
        $fields['status'] = $this->getParam('status', '');
        $percent = $this->getParam('percent', 0);
        $decimal = $this->getParam('decimal', 0);
        $fields['percentage'] = $percent . '.' . $decimal;
        $postTimed = $this->getParam('timed', '');
        if (!empty($postTimed)) {
            $fields['timed'] = 1;
        } else {
            $fields['timed'] = 0;
        }
        $fields['duration'] = ($this->getParam('hour', 0) * 60) + $this->getParam('min', 0);
        $startDate = $this->getParam('start', '');
        $closeDate = $this->getParam('close', '');
        $fields['startdate'] = $startDate;
        $fields['closingdate'] = $closeDate;
        $fields['testtype'] = $this->getParam('testType');
        $fields['qsequence'] = $this->getParam('qSequence');
        $fields['asequence'] = $this->getParam('aSequence');
        $fields['comlab'] = $this->getParam('comLab');
        $fields['description'] = $this->getParam('description', '');
        $fields['updated'] = date('Y-m-d H:i:s');
        $id = $this->dbTestadmin->addTest($fields, $id);
        // set all tests to equal percentages
        $postEqual = $this->getParam('setequal', '');
        if (isset($postEqual) && !empty($postEqual)) {
            $tests = $this->dbTestadmin->getTests($this->contextCode, 'id, percentage');
            $num = count($tests);
            $percent = round((100 / $num), 2);
            $arrField = array(
                'percentage' => $percent
            );
            foreach ($tests as $item) {
                $this->dbTestadmin->addTest($arrField, $item['id']);
            }
        }
        return $id;
    }

    /**
     * Method to display a test for viewing.
     *
     * @access private
     * @return
     */
    private function viewTest() {
        $data = $this->dbTestadmin->getTests($this->contextCode, '*', $this->getParam('id'));
        if (!empty($data)) {
            foreach ($data as $key => $line) {
                $sql = "SELECT title FROM tbl_context_nodes WHERE ";
                $sql.= "id = '" . $line['chapter'] . "'";
                $nodes = $this->objContentNodes->getArray($sql);
                if (!empty($nodes)) {
                    $data[$key]['node'] = $nodes[0]['title'];
                } else {
                    $data[$key]['node'] = '';
                }
            }
        }
        $questions = $this->dbQuestions->getQuestions($this->getParam('id'));
        $this->setVarByRef('data', $data[0]);
        $this->setVarByRef('questions', $questions);
        $this->setVarByRef('qNum', $this->getParam('qNum'));
        return 'viewtest_tpl.php';
    }

    /**
     * this brings the default add mcq questions
     * @return <type>
     */
    private function addMcqQuestion() {
        $id = $this->getParam('id', NULL);
        $count = $this->getParam('count');
        $test = $this->dbTestadmin->getTests($this->contextCode, 'id,name,totalmark', $id);

        // Get the total number of questions if this isn't the first
        if ($count > 1) {
            $count = $this->dbQuestions->countQuestions($id);
        }
        $test[0]['count'] = $count;
        $this->setVarByRef('test', $test[0]);
        $this->setVar('mode', 'add');
        return 'addquestion_tpl.php';
    }

    /**
     * this returns a form for freefomrm questions
     * @return template
     */
    private function addFreeForm() {
        $id = $this->getParam('id', NULL);
        $count = $this->getParam('count');
        $test = $this->dbTestadmin->getTests($this->contextCode, 'id,name,totalmark', $id);

        // Get the total number of questions if this isn't the first
        if ($count > 1) {
            $count = $this->dbQuestions->countQuestions($id);
        }
        $test[0]['count'] = $count;
        $this->setVarByRef('test', $test[0]);
        $this->setVar('mode', 'add');

        return 'addfreeform_tpl.php';
    }

    /**
     * Method to set up test data for editing.
     *
     * @access private
     * @return
     */
    private function editTest() {
        $testId = $this->getParam('id');
        $data = $this->dbTestadmin->getTests($this->contextCode, '*', $testId);
        /* $nodesSQL = 'SELECT tbl_context_nodes.id AS chapter_id,
          tbl_context_nodes.title AS chapter_title FROM tbl_context_nodes
          INNER JOIN tbl_context_parentnodes ON ( tbl_context_parentnodes_id =
          tbl_context_parentnodes.id )
          WHERE tbl_context_parentnodes.tbl_context_parentnodes_has_tbl_context_tbl_context_contextCode
          = "'.$this->contextCode.'"'; // AND parent_Node = "" ';
         */
        $allPercent = $this->dbTestadmin->getPercentage($this->contextCode, $testId);
        $this->setVarByRef('allPercent', $allPercent);
        //$this->setVarByRef('nodes', $nodes);
        $this->setVarByRef('data', $data);
        $this->setVar('mode', 'edit');
        return 'addtest_tpl.php';
    }

    /**
     * Method to add a set of answers to a question.
     * The method checks for an id and then updates the specified answer.
     * If there is no id then new answers are created.
     *
     * @access private
     * @param string $testId The id of the test.
     * @param string $questionId The id of the question.
     * @param array $answers Array of the answers.
     * @return bool
     */
    private function addAnswers($testId, $questionId, $answers, $num = 4) {
        $answerId = $this->getParam('answerId');
        $questiontype = $this->getParam('qtype');
        /* if ($answerId) {
          $fields = array();
          $fields['testid'] = $testId;
          $fields['questionid'] = $questionId;
          $fields['answer'] = $answers['answer1'];
          $fields['commenttext'] = $answers['comment1'];
          $this->dbAnswers->addAnswers($fields, $answerId);
          return TRUE;
          } */

        $i = 1;
        $order = 1;
        $f = 1;

        // Remove Existing Answers
        $this->dbAnswers->removeAnswers($questionId);

        while ($i <= $num) {
            $fields = array();
            $fields['testid'] = $testId;
            $fields['questionid'] = $questionId;
            $fields['answer'] = $answers['answer' . $i];
            $fields['commenttext'] = $answers['comment' . $i];
            $fields['answerorder'] = $order++;
            // var_dump($answers['correctans']);
            if ($questiontype == 'freeform') {
                $fields['correct'] = 1;
                $f++;
            } else
            if ($answers['correctans'] == $i) {
                $fields['correct'] = 1;
            } else {
                $fields['correct'] = 0;
            }
            //var_dump($fields['correct']);
            if ($answerId == '') {
                $this->dbAnswers->addAnswers($fields);
            } else {
                $this->dbAnswers->addAnswers($fields, $answerId);
            }

            $i++;
        } // end while
    }

    /**
     * Method to display a completed test to a lecturer.
     * The method displays the test information, the students mark and a list
     * of the questions in the test with the correct answer, the students answer
     * and the lecturers comment on the students answer.
     *
     * @access private
     * @return
     */
    private function showTest() {
        $testId = $this->getParam('id');
        $studentId = $this->getParam('studentId');
        $result = $this->dbResults->getResult($studentId, $testId);
        $test = $this->dbTestadmin->getTests($this->contextCode, 'name, totalmark', $testId);
        $result = array_merge($result[0], $test[0]);
        $totalmark = $this->dbQuestions->sumTotalmark($testId);
        $qNum = $this->getParam('qnum');
        if (empty($qNum)) {
            $tempData = $this->dbQuestions->getQuestionCorrectAnswer($testId);
        } else {
            $tempData = $this->dbQuestions->getQuestionCorrectAnswer($testId, $qNum);
        }
        // Remove alternative answers for free form questions.
        $data = array();
        if (!empty($tempData)) {
            foreach ($tempData as $key => $line) {
                if ($line['questiontype'] == 'freeform' && $line['answerorder'] != 1) {
                    continue;
                } else {
                    $data[$key] = $line;
                }
            }
        }
        if (!empty($data)) {
            foreach ($data as $key => $line) {
                $_questiontype = $line['questiontype'];
                switch ($_questiontype) {
                    case 'mcq':
                    case 'tf':
                        $marked = $this->dbMarked->getMarked($studentId, $line['questionid'], $testId);
                        $data[$key]['studcorrect'] = $marked[0]['correct'];
                        $data[$key]['studans'] = $marked[0]['answer'];
                        $data[$key]['studorder'] = $marked[0]['answerorder'];
                        $data[$key]['studcomment'] = $marked[0]['commenttext'];
                        $data[$key]['visible'] = true;
                        break;
                    case 'freeform':
                        //                        if ($line['answerorder'] != 1) {
                        //                            unset($data[$key]);
                        //                            continue;
                        //                        }
                        $data[$key]['alternativeanswers'] = $this->dbAnswers->getAlternativeAnswers($testId, $line['questionid']);
                        $marked = $this->dbMarked->getMarkedFreeForm($studentId, $line['questionid'], $testId);
                        $data[$key]['studcorrect'] = $marked[0]['correct'];
                        $data[$key]['studans'] = $marked[0]['answer'];
                        $data[$key]['studorder'] = $marked[0]['answerorder'];
                        $data[$key]['studcomment'] = $marked[0]['commenttext'];
                        $markedAnswer = $this->dbMarked->getMarkedFreeFormAnswer($studentId, $line['questionid'], $testId);
                        if ($markedAnswer) {
                            $data[$key]['answered'] = $markedAnswer[0]['answered'];
                        } else {
                            $data[$key]['answered'] = NULL;
                        }
                        break;
                    default:
                        trigger_error("Unknown question type", E_USER_ERROR);
                        exit(0);
                }
            }
        }

        $this->setVarByRef('data', $data);
        $this->setVarByRef('result', $result);
        $this->setVarByRef('totalmark', $totalmark);
        return 'showtest_tpl.php';
    }

    /**
     * Method to take a datetime string and reformat it as text.
     *
     * @access public
     * @param string $date The date in datetime format.
     * @return string $ret The formatted date.
     */
    public function formatDate($date) {
        $ret = $this->objDate->formatDate($date);
        return $ret;
    }

    /**
     * Method to return an array of computer lab names.
     *
     * @access public
     * @return array $arrComLabs The array of computer lab names
     */
    public function getLabs() {
        $arrComLabs = array();
        $contentRoot = $this->objConfig->getcontentBasePath();
        $fileLocation = $contentRoot . '/modules/mcqtests/';
        $fileLocation = str_replace('//', '/', $fileLocation);
        if (!is_dir($fileLocation)) {
            $this->objMkdir->mkdirs($fileLocation);
        }
        $pattern = $fileLocation . '*.csv';
        foreach (glob($pattern) as $filename) {
            $file = basename($filename, ".csv");
            $arrComLabs[] = $file;
        }
        return $arrComLabs;
    }

    /**
     * Method to display a list of open tests to a student
     *
     * @access private
     * @return
     */
    private function studentHome() {
        $data = $this->dbTestadmin->getTests($this->contextCode);
        if (!empty($data)) {
            foreach ($data as $key => $line) {
                $sql = "SELECT title FROM tbl_context_nodes WHERE ";
                $sql.= "id = '" . $line['chapter'] . "'";
                $nodes = $this->objContentNodes->getArray($sql);
                $data[$key]['node'] = '';
                if (!empty($nodes)) {
                    $data[$key]['node'] = $nodes[0]['title'];
                }
                $result = $this->dbResults->getResult($this->userId, $line['id']);
                //Get Total Mark
                $totalMark = $this->dbQuestions->sumTotalmark($line['id']);
                if (!empty($result)) {
                    $data[$key]['mark'] = $result[0]['mark'];
                } else {
                    $data[$key]['mark'] = 'none';
                }
                if (!empty($totalMark)) {
                    $data[$key]['totalmark'] = $totalMark;
                } else {
                    $data[$key]['totalmark'] = 'none';
                }
                if ($line['comlab'] != NULL) {
                    $arrLabIps = $this->getIps($line['comlab']);
                    $ipAddress = $_SERVER['REMOTE_ADDR'];
                    if (in_array($ipAddress, $arrLabIps)) {
                        $data[$key]['comlab'] = TRUE;
                        $data[$key]['labname'] = '';
                    } else {
                        $data[$key]['comlab'] = FALSE;
                        $data[$key]['labname'] = $line['comlab'];
                    }
                } else {
                    $data[$key]['comlab'] = TRUE;
                    $data[$key]['labname'] = '';
                }
            }
        }
        $this->setVarByRef('data', $data);
        return 'student_home_tpl.php';
    }

    /**
     * Method to save the result with mark=0, to prevent reopening the test.
     *
     * @access private
     * @param string $testId The id of the testadmin
     * @return
     */
    private function closeTest($testId) {
        // Check if result exists, if not return to main page
        $result = $this->dbResults->getResult($this->userId, $testId);
        if ($result === FALSE) {
            $fields = array();
            $fields['testid'] = $testId;
            $fields['studentid'] = $this->userId;
            $fields['mark'] = 0;
            $id = $this->dbResults->addResult($fields);
            return $id;
        }
        return $this->nextAction('');
    }

    /**
     * Method to set up a test for answering.
     *
     * @access private
     * @param string $testId The id of the test to be answered.
     * @return The template displaying the test.
     */
    private function setTest($testId, $num = 0) {
        $data = array();
        $fieldlist = 'id,name,totalmark,timed,duration,description,testtype,qsequence,asequence';
        $test = $this->dbTestadmin->getTests('', $fieldlist, $this->getParam('id'));
        $results = $this->dbMarked->getSelectedAnswers($this->userId, $testId);
        // new code for scrambling tests
        if ($test[0]['qsequence'] == 'Scrambled' || $test[0]['asequence'] == 'Scrambled') {
            $qData = $this->getSession('qData');
            if (isset($qData) && !empty($qData)) {
                $data = array_slice($qData, $num, 10);
                $data[0]['count'] = count($qData);
                $data[0]['qnum'] = $num;
                foreach ($data as $key => $line) {
                    if (isset($results) && !empty($results)) {
                        foreach ($results as $item) {
                            foreach ($data[$key]['answers'] as $k => $val) {
                                if (($item['questionid'] == $line['id']) && ($item['answerid'] == $val['id'])) {
                                    $data[$key]['answers'][$k]['selected'] = $item['id'];
                                }
                            }
                        }
                    }
                }
            } else {
                $qData = $this->dbQuestions->getQuestions($test[0]['id']);
                if (!empty($qData)) {
                    if ($test[0]['qsequence'] == 'Scrambled') {
                        shuffle($qData);
                    }
                    foreach ($qData as $key => $line) {
                        $qData[$key]['questionorder'] = ($key + 1);
                    }
                    $qData[0]['count'] = count($qData);
                    foreach ($qData as $key => $line) {
                        $answers = $this->dbAnswers->getAnswers($line['id']);
                        if ($test[0]['asequence'] == 'Scrambled') {
                            shuffle($answers);
                        }
                        $qData[$key]['answers'] = $answers;
                    }
                    $this->setSession('qData', $qData);
                    $data = array_slice($qData, $num, 10);
                    $data[0]['count'] = count($qData);
                    $data[0]['qnum'] = $num;
                }
            }
        } else {
            // original code
            $data = $this->dbQuestions->getQuestions($test[0]['id'], 'questionorder > ' . $num . ' ORDER BY questionorder LIMIT 10');
            if (!empty($data)) {
                foreach ($data as $key => $line) {
                    $answers = $this->dbAnswers->getAnswers($line['id']);
                    if (isset($results) && !empty($results)) {
                        foreach ($results as $item) {
                            foreach ($answers as $k => $val) {
                                if (($item['questionid'] == $line['id']) && ($item['answerid'] == $val['id'])) {
                                    $answers[$k]['selected'] = $item['id'];
                                }
                            }
                        }
                    }
                    $data[$key]['answers'] = $answers;
                }
                $data[0]['qnum'] = $num;
            }
        }
        $this->setVarByRef('test', $test[0]);
        $this->setVarByRef('data', $data);

        $this->setVar('suppressFooter', TRUE);

        return 'answertest_tpl.php';
    }

    /**
     * Method to set up a test for answering.
     *
     * @access private
     * @param string $testId The id of the test to be answered.
     * @return The template displaying the test.
     */
    private function previewTest($testId, $num = 0) {
        $data = array();
        $fieldlist = 'id,name,totalmark,timed,duration,description,testtype,qsequence,asequence';
        $test = $this->dbTestadmin->getTests('', $fieldlist, $this->getParam('id'));
        $results = $this->dbMarked->getSelectedAnswers($this->userId, $testId);
        // new code for scrambling tests
        if ($test[0]['qsequence'] == 'Scrambled' || $test[0]['asequence'] == 'Scrambled') {
            $qData = $this->getSession('qData');
            if (isset($qData) && !empty($qData)) {
                $data = array_slice($qData, $num, 10);
                $data[0]['count'] = count($qData);
                $data[0]['qnum'] = $num;
                foreach ($data as $key => $line) {
                    if (isset($results) && !empty($results)) {
                        foreach ($results as $item) {
                            foreach ($data[$key]['answers'] as $k => $val) {
                                if (($item['questionid'] == $line['id']) && ($item['answerid'] == $val['id'])) {
                                    $data[$key]['answers'][$k]['selected'] = $item['id'];
                                }
                            }
                        }
                    }
                }
            } else {
                $qData = $this->dbQuestions->getQuestions($test[0]['id']);
                if (!empty($qData)) {
                    if ($test[0]['qsequence'] == 'Scrambled') {
                        shuffle($qData);
                    }
                    foreach ($qData as $key => $line) {
                        $qData[$key]['questionorder'] = ($key + 1);
                    }
                    $qData[0]['count'] = count($qData);
                    foreach ($qData as $key => $line) {
                        $answers = $this->dbAnswers->getAnswers($line['id']);
                        if ($test[0]['asequence'] == 'Scrambled') {
                            shuffle($answers);
                        }
                        $qData[$key]['answers'] = $answers;
                    }
                    $this->setSession('qData', $qData);
                    $data = array_slice($qData, $num, 10);
                    $data[0]['count'] = count($qData);
                    $data[0]['qnum'] = $num;
                }
            }
        } else {
            // original code
            $data = $this->dbQuestions->getQuestions($test[0]['id'], 'questionorder > ' . $num . ' ORDER BY questionorder LIMIT 10');
            if (!empty($data)) {
                foreach ($data as $key => $line) {
                    $answers = $this->dbAnswers->getAnswers($line['id']);
                    if (isset($results) && !empty($results)) {
                        foreach ($results as $item) {
                            foreach ($answers as $k => $val) {
                                if (($item['questionid'] == $line['id']) && ($item['answerid'] == $val['id'])) {
                                    $answers[$k]['selected'] = $item['id'];
                                }
                            }
                        }
                    }
                    $data[$key]['answers'] = $answers;
                }
                $data[0]['qnum'] = $num;
            }
        }

        $this->setVarByRef('test', $test[0]);
        $this->setVarByRef('data', $data);

        $this->setVar('suppressFooter', TRUE);

        return 'previewtest_tpl.php';
    }

    /**
     * Method to save the students answers to the database.
     *
     * @access private
     * @param string $resultId The id of the students result in the results table
     * @return string $resultId The id of the students result in the results table.
     */
    private function saveTest($resultId) {
        $total = 0;
        $postCount = $this->getParam('count', NULL);
        if ($postCount) {
            for ($i = $this->getParam('first', 0); $i <= $postCount; $i++) {
                // Check if answer selected and needs updating
                $postSelected = $this->getParam('selected' . $i, NULL);
                // Save the students answers.
                $testId = $this->getParam('id');
                $questType = $this->getParam('qtype' . $i);
                $questId = $this->getParam('questionId' . $i);
                if (!empty($testId) && !empty($questId)) {
                    $fields = array();
                    $fields['testid'] = $testId;
                    $fields['questionid'] = $questId;
                    $postAns = $this->getParam('ans' . $i);
                    $fields['studentid'] = $this->userId;
                    if ($questType == 'freeform') {
                        $fields['answerid'] = '';
                        $fields['answered'] = $postAns;
                    } else {
                        $fields['answerid'] = $postAns;
                        $fields['answered'] = '';
                    }

                    $this->dbMarked->addMarked($fields, $postSelected);
                }
            }
        }
        return $resultId;
    }

    /**
     * Method to add up the marks and submit them to the database.
     *
     * @access private
     * @param string $resultId The id of the students result in the results table.
     * @return string $resultId The id of the students result in the results table.
     */
    private function markTest($resultId) {
        $total = 0;
        $testId = $this->getParam('id', '');
        //$j = $this->getParam('first', 0);
        // $questType = $this->getParam('qtype'.$j,'');
        if (!empty($testId)) {
            $data = $this->dbMarked->getfreeformAnswers($this->userId, $testId);

            if (!empty($data)) {
                foreach ($data as $val) {

                    if ($val['answered'] != NULL) {
                        $total = $total + $val['mark'];
                        //$total = $p;
                    }
                }
            }
        }   //  $j++;


        if (!empty($testId)) {
            $data = $this->dbMarked->getCorrectAnswers($this->userId, $testId);
            if (!empty($data)) {
                foreach ($data as $item) { //$b++;
                    if ($item['correct']) {
                        $total = $total + $item['mark'];
                        //$total = $b;
                    }
                }
            }
        }
        $this->dbResults->addMark($resultId, $total);
    }

    /**
     * Method to display a completed test to a student.
     * The method displays the test information, the students mark and a list
     * of the questions in the test with the correct answer, the students answer
     * and the lecturers comment on the students answer.
     *
     * @access private
     * @return
     */
    private function showStudentTest() {
        $testId = $this->getParam('id');
        $result = $this->dbResults->getResult($this->userId, $testId);
        $test = $this->dbTestadmin->getTests($this->contextCode, 'name, totalmark', $testId);
        $result = array_merge($result[0], $test[0]);
        $qNum = $this->getParam('qnum');

        if (empty($qNum)) {
            $data = $this->dbQuestions->getQuestionCorrectAnswer($testId);
        } else {
            $data = $this->dbQuestions->getQuestionCorrectAnswer($testId, $qNum);
        }
        if (!empty($data)) {
            foreach ($data as $key => $line) {
                $marked = $this->dbMarked->getMarked($this->userId, $line['questionid'], $testId);
                $ffmarked = $this->dbMarked->getAllMarked($this->userId, $line['questionid'], $testId);
                $correctans = $this->dbAnswers->getAnswers($line['questionid']);
                if ($line['questiontype'] == 'freeform') {
                    $simple = array();
                    foreach ($correctans as $base) {
                        $simple[] = $base['answer'];
                    }
                    $stringOut = implode(',', $simple);
                }
                $data[$key]['studcorrect'] = $marked[0]['correct'];
                $data[$key]['studfreeans'] = $ffmarked[0]['answered'];
                $data[$key]['studans'] = $marked[0]['answer'];
                $data[$key]['studorder'] = $marked[0]['answerorder'];
                $data[$key]['studcomment'] = $marked[0]['commenttext'];
                $data[$key]['studfreecorrect'] = $stringOut;
            }
        }
        // $this->setVarByRef('stringOut', $stringOut);
        $this->setVarByRef('data', $data);
        $this->setVarByRef('result', $result);
        //$this->setVarByRef('datanew',$datanew);
        //return 'showtest_tpl.php';
        return 'show_test_tpl.php';
    }

    /**
     * Method to display a list of links to other questions in the worksheet.
     *
     * @access private
     * @param string $current The current question.
     * @param string $total The total number of questions in the worksheet.
     * @param string $worksheetid The id of the worksheet being answered.
     * @return The links.
     */
    public function generateLinks($current, $total, $num = 4) {
        $this->loadClass('link', 'htmlelements');
        $output = '';
        if ($current == 0) {
            $current = 1;
        }
        $rem = ($current - 1) % $num;
        if ($rem != 0) {
            if ($rem == 1) {
                $link = '1';
            } else {
                $link = "1 - $rem";
            }
            $objLink = new link("javascript:submitform('0');");
            $objLink->link = $link;
            $output.= $objLink->show();
        }
        for ($i = $rem + 1; $i <= $total; $i = $i + $num) {
            $end = $i + $num - 1;
            if ($end > $total) {
                $link = $i . '&nbsp;-&nbsp;' . $total;
            } else {
                if ($i == $end) {
                    $link = $i;
                } else {
                    $link = $i . '&nbsp;-&nbsp;' . $end;
                }
            }
            if ($i == $current) {
                if ($i == 1) {
                    $output.= $link;
                } else {
                    $output.= '&nbsp;&nbsp;|&nbsp;&nbsp;' . $link;
                }
            } else {
                $j = $i - 1;
                $objLink = new link("javascript:submitform('$j');");
                $objLink->link = $link;
                if ($i == 1) {
                    $output.= $objLink->show();
                } else {
                    $output.= '&nbsp;&nbsp;|&nbsp;&nbsp;' . $objLink->show();
                }
            }
        }
        return $output;
    }

    /**
     * Method to return an array of ip addresses for a computer lab
     *
     * @access public
     * @param string $comLab The computer lab name
     * @return array $arrIpAddresses The array of ip addresses
     */
    public function getIps($comLab) {
        $contentRoot = $this->objConfig->getcontentBasePath();
        $fileLocation = $contentRoot . '/modules/mcqtests/';
        if (!is_dir($fileLocation)) {
            $this->objMkdir->fullFilePath = $fileLocation;
            $this->objMkdir->makedir();
        }
        $file = $fileLocation . $comLab . '.csv';
        $arrIpAddresses = array();
        $fp = fopen($file, 'r');
        while ($line = fgetcsv($fp, 1024, ",")) {
            $arrIpAddresses[] = $line[0];
        }
        fclose($fp);
        return $arrIpAddresses;
    }

    /**
     * Method to submit new questions based on existing questions in the database.
     *
     * @access public
     * @param none
     * @return status of data submission
     */
    public function submitDBQuestions() {
        // submit the questions into this context.
        $this->dbQuestions->submitDBQuestions($this->contextCode, $this->getParam(idData), $this->getParam(courseID));
    }

    /**
     * Method to retrieve data based on existing questions in the database.
     *
     * @access public
     * @param none
     * @return status of data submission
     */
    public function getGridData($type=null, $courses=null, $start = null, $limit = null) {
        $id = $this->getParam('id');
        echo $this->dbTestadmin->getContextQuestions($this->contextCode, $id, $type, $courses, $start, $limit);
    }

    /**
     * Method to retrieve data based on existing questions in the database.
     *
     * @access public
     * @param none
     * @return status of data submission
     */
    public function previewQuestion($id) {
        $this->setVarByRef('id', $id);
        return 'previewquestion_tpl.php';
    }

    public function calcqForm() {
        $objCalcQFormmanager = $this->newObject('question_calculated_formmanager');
        $id = $this->getParam('id');

        echo $objCalcQFormmanager->calcQForm($id);
    }

    private function addGeneralFormQuestions($qtype, $edit=null) {
        $objQuestions = $this->getObject('dbquestions', 'mcqtests');
        //insert into questions table
        $questiondata = array();
        $questiondata['testid'] = $this->getParam('id');
        $questiondata['question'] = $this->getParam('qText');
        $questiondata['name'] = $this->getParam('qName');
        $questiondata['hint'] = $this->getParam('hint');
        $questiondata['questiontext'] = $this->getParam('qText');
        $questiondata['mark'] = $this->getParam('qMark');
        $questiondata['generalfeedback'] = $this->getParam('calcqgenfeedback'); //('generalfeedback');
        $questiondata['penalty'] = $this->getParam('qPenalty');
        $questiondata['questiontype'] = $qtype;

        if ($edit) {
            $id = $this->getParam('id');
            $objQuestions->addQuestion($questiondata, $id);
        } else {
            $id = $objQuestions->addQuestion($questiondata);
        }
        return $id;
    }

    public function addMatchingQuestions($id, $edit=false) {
        $objQuestionMatching = $this->newObject('dbquestion_matching');

        $matchingQuestionData = array();
        $matchingQuestionData['subquestions'] = array('q1' => trim($this->getParam('qmatching1')), 'q2' => trim($this->getParam('qmatching2')), 'q3' => trim($this->getParam('qmatching3')));
        $matchingQuestionData['subanswers'] = array('a1' => $this->getParam('aMatching1'), 'a2' => $this->getParam('aMatching2'), 'a3' => $this->getParam('aMatching3'));

        if ($edit) {
            $objQuestionMatching->updateMatchingQuestions($id, $matchingQuestionData);
        } else {
            $objQuestionMatching->addMatchingQuestions($id, $matchingQuestionData);
        }
    }

    public function addNumericalQuestions($id, $edit=false) {
        $objQuestionNumerical = $this->newObject('dbquestion_numerical');
        $objQuestionUnit = $this->newObject('dbnumericalunits');

        $questionid = $id;
        $numericalQuestionData = array();
        // get info for unit marked
        if (strlen($this->getParam('unitmarked')) > 0) {
            $unitmarked = 'yes';
        } else if ($this->getParam('dispUnit')) {
            $dispUnit = 'yes';
        }
        $numericalQuestionData['answer'] = array('a1' => $this->getParam('aNumerical1'), 'a2' => $this->getParam('aNumerical2'), 'a3' => $this->getParam('aNumerical3'));
        $numericalQuestionData['mark'] = array('mark1' => $this->getParam('mark_1'), 'mark2' => $this->getParam('mark_2'), 'mark3' => $this->getParam('mark_3'));
        if ($edit) {
            $objQuestionNumerical->updateNumericalQuestions($questionid, $numericalQuestionData);
        } else {
            $objQuestionNumerical->addNumericalQuestions($questionid, $numericalQuestionData);
        }
        //insert unit data
        $unitData = array();
        $unitData['unit'] = $this->getParam('aUnit');
        $unitData['questionid'] = $questionid;
        if ($edit) {
            $objQuestionUnit->updateNumericalUnits($unitData, $id);
        } else {
            $objQuestionUnit->addNumericalUnits($unitData);
        }
    }

}

// end of class
?>