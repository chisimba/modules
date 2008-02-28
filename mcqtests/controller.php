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
class mcqtests extends controller
{
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
     * Method to construct the class.
     *
     * @access public
     * @return
     */
    public function init()
    {
        // Check if the assignment module is registered and can be linked to.
        $this->objModules = $this->newObject('modules', 'modulecatalogue');
        $this->assignment = FALSE;
        if ($this->objModules->checkIfRegistered('Assignment Management', 'assignment')) {
            $this->assignment = TRUE;
        }

        // get the user object
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

        // context
        $this->objContext = $this->newObject('dbcontext', 'context');
        $this->objContentNodes = $this->newObject('dbcontentnodes', 'context');
        $this->contextCode = $this->objContext->getContextCode();
        $this->contextUsers = $this->newObject('managegroups', 'contextgroups');
        $this->objCond = $this->newObject('contextCondition', 'contextpermissions');

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
    public function dispatch($action)
    {
        // Now the main switch for $action
        switch ($action) {
                // display template to add a new test.


             // Display template to add a new question

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
                // add the question to the database

            case 'applyaddquestion':
                $postSave = $this->getParam('save');
                $id = $this->getParam('id', '');
                if ($postSave == $this->objLanguage->languageText('word_cancel')) {
                    return $this->nextAction('view', array(
                        'id' => $id
                    ));
                }
                $this->addQuestion();
                if ($postSave == $this->objLanguage->languageText('mod_mcqtests_saveaddanotherquestion')) {
                    return $this->nextAction('addquestion', array(
                        'id' => $id,
                        'count' => $this->getParam('qOrder')
                    ));
                }
                $msg = $this->objLanguage->languageText('mod_mcqtests_confirmaddquestion', 'mcqtests');
                $this->setSession('confirm', $msg);
                return $this->nextAction('view', array(
                    'id' => $id,
                    'confirm' => 'yes'
                ));






		case 'addstep':
		return 'addstep_tpl.php';

		case 'savestep':
		$currentstep = $this->getParam('currentstep');
		if($currentstep=='1'){
		return 'addstep_tpl.php';
		}else if($currentstep=='2'){
				$StepMenuArr = array();
				$StepMenuArr['status'] = $this->getParam('status');
				$StepMenuArr['name'] = $this->getParam('name');
				$StepMenuArr['description'] = $this->getParam('description');
				$StepMenuArr['testType'] = $this->getParam('testType');
				$StepMenuArr['qSequence'] = $this->getParam('qSequence');
				$StepMenuArr['aSequence'] = $this->getParam('aSequence');
				$StepMenuArr['save'] = $this->getParam('save');
				
				$this->setSession('stepmenu1', null);
				$this->setSession('stepmenu1', $StepMenuArr);
				$StepMenuArr = null;
				return 'addstep_tpl.php';

		}else if($currentstep=='3'){


				$StepMenuArr2 = array();
				$StepMenuArr2['percent'] = $this->getParam('percent');
				$StepMenuArr2['decimal'] = $this->getParam('decimal');
				$StepMenuArr2['setequal'] = $this->getParam('setequal');
				$StepMenuArr2['start'] = $this->getParam('start');
				$StepMenuArr2['close'] = $this->getParam('close');
				$StepMenuArr2['timed'] = $this->getParam('timed');
				$StepMenuArr2['hour'] = $this->getParam('start');
				$StepMenuArr2['min'] = $this->getParam('close');
				$StepMenuArr2['save'] = $this->getParam('save');
				$this->setSession('stepmenu2', null);
				$this->setSession('stepmenu2', $StepMenuArr2);
				$StepMenuArr = null;
				return 'addstep_tpl.php';
		}else{
				$step_data1 = $this->getSession('stepmenu1');
				$step_data2 = $this->getSession('stepmenu2');
				//merge 2 arrays 	
				$fields = array();
				$fields['status'] = $step_data1['status'];
				$fields['name'] = $step_data1['name'];
				$fields['description'] = $step_data1['description'];
				$fields['testType'] =$step_data2['testType'];
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
				//saving the step data
				
                $id = $this->StepAddTest($fields);
                return $this->nextAction('view', array(
                    'id' => $id
                ));
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
                    Header("Location: ".$this->uri(array(
                        'action' => 'viewbyletter'
                    ) , $back));
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
                $answers = $this->dbAnswers->getAnswers($this->getParam('questionId'));
                $test = $this->dbTestadmin->getTests($this->contextCode, 'id,name,totalmark', $data[0]['testid']);
                $this->setVarByRef('test', $test[0]);
                $this->setVarByRef('data', $data[0]);
                $this->setVarByRef('answers', $answers);
                $this->setVar('mode', 'edit');
                return 'addquestion_tpl.php';
                // delete a question

            case 'deletequestion':
                $this->dbQuestions->deleteQuestion($this->getParam('questionId'));
                $this->dbTestadmin->setTotal($this->getParam('id') , -$this->getParam('mark'));
                return $this->nextAction('view', array(
                    'id' => $this->getParam('id')
                ));
            case 'questionup':
                $this->dbQuestions->changeOrder($this->getParam('questionId') , TRUE);
                return $this->nextAction('view', array(
                    'id' => $this->getParam('id')
                ));
            case 'questiondown':
                $this->dbQuestions->changeOrder($this->getParam('questionId') , FALSE);
                return $this->nextAction('view', array(
                    'id' => $this->getParam('id')
                ));
                // save the question to the database and call next action to add answers

            case 'addanswer':
                $questionId = $this->addQuestion();
                return $this->nextAction('addanswers', array(
                    'questionId' => $questionId,
                    'count' => $this->getParam('count')
                ));
                // display the template to add answers to a question

            case 'addanswers':
                $data = $this->dbQuestions->getQuestion($this->getParam('questionId'));
                $data[0]['count'] = $this->getParam('count');
                $this->setVarByRef('data', $data[0]);
                $this->setVar('mode', 'add');
                return 'addanswer_tpl.php';
                // save answers to the database

            case 'applyaddanswer':
echo "<pre>";
print_r($_POST);
echo "</pre>";

                $postSave = $this->getParam('save', '');
                $postTestId = $this->getParam('testId', '');
                $postQuestionId = $this->getParam('questionId', '');
                if ($postSave == $this->objLanguage->languageText('word_cancel')) {
                    return $this->nextAction('editquestion', array(
                        'questionId' => $postQuestionId
                    ));
                }
                $this->addAnswers($postTestId, $postQuestionId, $_POST);
                $msg = $this->objLanguage->languageText('mod_mcqtests_confirmaddanswer', 'mcqtests');
                $this->setSession('confirm', $msg);
                return $this->nextAction('editquestion', array(
                    'questionId' => $postQuestionId,
                    'confirm' => 'yes'
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
                $this->setVarByRef('test', $test[0]);
                $this->setVarByRef('data', $data);
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
                $fileLocation = $contentRoot.'modules/mcqtests';
                if (!is_dir($fileLocation)) {
                    $this->objMkdir->fullFilePath = $fileLocation;
                    $this->objMkdir->makedir();
                }
                $file = $fileLocation.'/'.$testId.'.csv';
                if ($exportType == 'answers') {
                    $usersResultList = $this->dbResults->getResults($testId);
                    if (isset($usersResultList) && !empty($usersResultList)) {
                        $outputFile = fopen($file, 'wb');
                        fwrite($outputFile, '"Student Number","Student name","Start time","End time","Answers selected"'."\n");
                        foreach($usersResultList as $user) {
                            $userAnswerList = $this->dbMarked->getAnswersForOutput($testId, $user['studentid']);
                            $line = $userAnswerList[0]['studentid'].",";
                            $line.= $this->objUser->fullname($userAnswerList[0]['studentid']) .",";
                            $line.= $userAnswerList[0]['starttime'].",";
                            $line.= $userAnswerList[0]['endtime'].",";
                            if (isset($userAnswerList) && !empty($userAnswerList)) {
                                foreach($userAnswerList as $answer) {
                                    $value = isset($answer['answerorder']) ? $answer['answerorder'] : 'NULL';
                                    $line.= $value.",";
                                }
                            }
                            fwrite($outputFile, $line."\n");
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
                        fwrite($outputFile, '"Student Number","Student name","Score","Percentage"'."\n");
                        foreach($usersResultList as $user) {
                            $line = $user['studentid'].",";
                            $line.= $this->objUser->fullname($user['studentid']) .",";
                            $line.= $user['mark'].",";
                            $line.= (round(($user['mark']/$testData[0]['totalmark']) , 4) *100) ."%,";
                            fwrite($outputFile, $line."\n");
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
                    $fileLocation = $contentRoot.'/modules/mcqtests/';
                    if (!is_dir($fileLocation)) {
                        $this->objMkdir->fullFilePath = $fileLocation;
                        $this->objMkdir->makedir();
                    }
                    $labFileLocation = $fileLocation.$file['comLab']['name'];
                    move_uploaded_file($file['comLab']['tmp_name'], $labFileLocation);
                    if ($mode == 'add') {
                        return $this->nextAction('addtest', array(
                            'mode' => $mode
                        ) , 'mcqtests');
                    } else {
                        return $this->nextAction('edit', array(
                            'id' => $id,
                            'mode' => $mode
                        ) , 'mcqtests');
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
            case 'continuetest':
                $this->unsetSession('taketest');
                $resultId = $this->getParam('resultId', NULL);
                $resultId = $this->saveTest($resultId);
                $testDuration = $this->getParam('testduration', NULL);
                $mode = $this->getParam('mode', 'mode');
                $this->setVarByRef('testDuration', $testDuration);
                $this->setVarByRef('resultId', $resultId);
                $this->setVarByRef('mode', $mode);
                return $this->setTest($this->getParam('id') , $this->getParam('qnum', ''));
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
            case 'showstudenttest':
                return $this->showStudentTest();
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
     * Method to display a list of tests in the test home page.
     *
     * @access private
     * @param string $testId The id of the test results were exported for
     * @return
     */
    private function home($testId = NULL)
    {
        $data = $this->dbTestadmin->getTests($this->contextCode);
        if (!empty($data)) {
            foreach($data as $key => $line) {
                $sql = "SELECT title FROM tbl_context_nodes WHERE ";
                $sql.= "id = '".$line['chapter']."'";
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
    private function addTest()
    {
        $nodesSQL = 'SELECT tbl_context_nodes.id AS chapter_id,
        tbl_context_nodes.title AS chapter_title FROM tbl_context_nodes
        INNER JOIN tbl_context_parentnodes ON ( tbl_context_parentnodes_id =
        tbl_context_parentnodes.id )
        WHERE tbl_context_parentnodes.tbl_context_parentnodes_has_tbl_context_tbl_context_contextCode
        = "'.$this->contextCode.'"'; // AND parent_Node = "" ';
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
    private function StepAddTest($data)
    {

 $id = $this->getParam('id', '');
        $fields = array();
        $fields['name'] = $data['name'];
        $fields['context'] = $this->contextCode;
        $fields['userid'] = $this->userId;
        // $fields['chapter'] = $this->getParam('chapter', '');
        $fields['status'] = $data['status'];
        $percent =$data['percent'];
        $decimal = $data['decimal'];
        $fields['percentage'] = $percent.'.'.$decimal;
        $postTimed = $data['timed'];
        if (!empty($postTimed)) {
            $fields['timed'] = 1;
        } else {
            $fields['timed'] = 0;
        }
        $fields['duration'] = ($data['hour'] *60) +$data['min'];
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
        $id = $this->dbTestadmin->addTest($fields, $id);
        // set all tests to equal percentages
        $postEqual = $this->getParam('setequal', '');
        if (isset($postEqual) && !empty($postEqual)) {
            $tests = $this->dbTestadmin->getTests($this->contextCode, 'id, percentage');
            $num = count($tests);
            $percent = round((100/$num) , 2);
            $arrField = array(
                'percentage' => $percent
            );
            foreach($tests as $item) {
                $this->dbTestadmin->addTest($arrField, $item['id']);
            }
        }
        return $id;
    }

    /**
     * Method to add a new test
     *
     * @access private
     * @return string $id The id of the new test
     */
    private function applyAddTest()
    {
        $id = $this->getParam('id', '');
        $fields = array();
        $fields['name'] = $this->getParam('name', '');
        $fields['context'] = $this->contextCode;
        $fields['userid'] = $this->userId;
        // $fields['chapter'] = $this->getParam('chapter', '');
        $fields['status'] = $this->getParam('status', '');
        $percent = $this->getParam('percent', 0);
        $decimal = $this->getParam('decimal', 0);
        $fields['percentage'] = $percent.'.'.$decimal;
        $postTimed = $this->getParam('timed', '');
        if (!empty($postTimed)) {
            $fields['timed'] = 1;
        } else {
            $fields['timed'] = 0;
        }
        $fields['duration'] = ($this->getParam('hour', 0) *60) +$this->getParam('min', 0);
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
            $percent = round((100/$num) , 2);
            $arrField = array(
                'percentage' => $percent
            );
            foreach($tests as $item) {
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
    private function viewTest()
    {
        $data = $this->dbTestadmin->getTests($this->contextCode, '*', $this->getParam('id'));
        if (!empty($data)) {
            foreach($data as $key => $line) {
                $sql = "SELECT title FROM tbl_context_nodes WHERE ";
                $sql.= "id = '".$line['chapter']."'";
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
        return 'viewtest_tpl.php';
    }

    /**
     * Method to set up test data for editing.
     *
     * @access private
     * @return
     */
    private function editTest()
    {
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
     * Method to save a question and set the correct answer.
     *
     * @access private
     * @return string $id The id of the inserted or updated question
     */
    private function addQuestion()
    {
        $qId = $this->getParam('questionId', '');
        $imgConfirm = $this->getParam('imageconfirm', '');
        $hintConfirm = $this->getParam('enablehint', '');
        $postMark = $this->getParam('mark', 0);
        $mark = 0;
        $fields = array();
        $fields['testid'] = $this->getParam('id', '');
        $fields['question'] = $this->getParam('question', '');
        $hint = $this->getParam('hint', '');
        if ($hintConfirm == 'no') {
            $hint = '';
        }
        $fields['hint'] = $hint;
        $fields['mark'] = $postMark;
        $fields['questionorder'] = $this->getParam('qOrder', '');
        if (!empty($qId)) {
            $postOrigMark = $this->getParam('total', 0);
            if ($postOrigMark != $postMark) {
                $mark = $postMark-$postOrigMark;
            }
        } else {
            $mark = $postMark;
        }
        // Add to database and set the total mark for the test
        $qId = $this->dbQuestions->addQuestion($fields, $qId);
        $id = $this->getParam('id', '');
        $this->dbTestadmin->setTotal($id, $mark);
        // set the correct answer if it has changed
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
        return $qId;
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
    private function addAnswers($testId, $questionId, $answers, $num = 4)
    {
        $answerId = $this->getParam('answerId', NULL);
        if ($answerId) {
            $fields = array();
            $fields['testid'] = $testId;
            $fields['questionid'] = $questionId;
            $fields['answer'] = $answers['answer1'];
            $fields['commenttext'] = $answers['comment1'];
            $this->dbAnswers->addAnswers($fields, $answerId);
            return TRUE;
        }
        $i = 1;
        $order = $answers['answerorder'];
        while ($i <= $num) {
            if (isset($answers['answer'.$i])) {
                if (!empty($answers['answer'.$i])) {
                    $fields = array();
                    $fields['testid'] = $testId;
                    $fields['questionid'] = $questionId;
                    $fields['answer'] = $answers['answer'.$i];
                    $fields['commenttext'] = $answers['comment'.$i];
                    $fields['answerorder'] = $order++;
                    $this->dbAnswers->addAnswers($fields);
                } // end if

            } // end if
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
    private function showTest()
    {
        $testId = $this->getParam('id');
        $studentId = $this->getParam('studentId');
        $result = $this->dbResults->getResult($studentId, $testId);
        $test = $this->dbTestadmin->getTests($this->contextCode, 'name, totalmark', $testId);
        $result = array_merge($result[0], $test[0]);
        $qNum = $this->getParam('qnum');
        if (empty($qNum)) {
            $data = $this->dbQuestions->getQuestionCorrectAnswer($testId);
        } else {
            $data = $this->dbQuestions->getQuestionCorrectAnswer($testId, $qNum);
        }
        if (!empty($data)) {
            foreach($data as $key => $line) {
                $marked = $this->dbMarked->getMarked($studentId, $line['questionid'], $testId);
                $data[$key]['studcorrect'] = $marked[0]['correct'];
                $data[$key]['studans'] = $marked[0]['answer'];
                $data[$key]['studorder'] = $marked[0]['answerorder'];
                $data[$key]['studcomment'] = $marked[0]['commenttext'];
            }
        }
        $this->setVarByRef('data', $data);
        $this->setVarByRef('result', $result);
        return 'showtest_tpl.php';
    }

    /**
     * Method to take a datetime string and reformat it as text.
     *
     * @access public
     * @param string $date The date in datetime format.
     * @return string $ret The formatted date.
     */
    public function formatDate($date)
    {
        $ret = $this->objDate->formatDate($date);
        return $ret;
    }

    /**
     * Method to return an array of computer lab names.
     *
     * @access public
     * @return array $arrComLabs The array of computer lab names
     */
    public function getLabs()
    {
        $arrComLabs = array();
        $contentRoot = $this->objConfig->getcontentBasePath();
        $fileLocation = $contentRoot.'/modules/mcqtests/';
        $fileLocation = str_replace('//','/',$fileLocation);
        if (!is_dir($fileLocation)) {
            $this->objMkdir->mkdirs($fileLocation);
        }
        $pattern = $fileLocation.'*.csv';
        foreach(glob($pattern) as $filename) {
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
    private function studentHome()
    {
        $data = $this->dbTestadmin->getTests($this->contextCode);
        if (!empty($data)) {
            foreach($data as $key => $line) {
                $sql = "SELECT title FROM tbl_context_nodes WHERE ";
                $sql.= "id = '".$line['chapter']."'";
                $nodes = $this->objContentNodes->getArray($sql);
                $data[$key]['node'] = '';
                if (!empty($nodes)) {
                    $data[$key]['node'] = $nodes[0]['title'];
                }
                $result = $this->dbResults->getResult($this->userId, $line['id']);
                if (!empty($result)) {
                    $data[$key]['mark'] = $result[0]['mark'];
                } else {
                    $data[$key]['mark'] = 'none';
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
    private function closeTest($testId)
    {
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
    private function setTest($testId, $num = 0)
    {
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
                foreach($data as $key => $line) {
                    if (isset($results) && !empty($results)) {
                        foreach($results as $item) {
                            foreach($data[$key]['answers'] as $k => $val) {
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
                    foreach($qData as $key => $line) {
                        $qData[$key]['questionorder'] = ($key+1);
                    }
                    $qData[0]['count'] = count($qData);
                    foreach($qData as $key => $line) {
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
            $data = $this->dbQuestions->getQuestions($test[0]['id'], 'questionorder > '.$num.' ORDER BY questionorder LIMIT 10');
            if (!empty($data)) {
                foreach($data as $key => $line) {
                    $answers = $this->dbAnswers->getAnswers($line['id']);
                    if (isset($results) && !empty($results)) {
                        foreach($results as $item) {
                            foreach($answers as $k => $val) {
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
        return 'answertest_tpl.php';
    }

    /**
     * Method to save the students answers to the database.
     *
     * @access private
     * @param string $resultId The id of the students result in the results table
     * @return string $resultId The id of the students result in the results table.
     */
    private function saveTest($resultId)
    {
        $total = 0;
        $postCount = $this->getParam('count', NULL);
        if ($postCount) {
            for ($i = $this->getParam('first', 0) ; $i <= $postCount ; $i++) {
                // Check if answer selected and needs updating
                $postSelected = $this->getParam('selected'.$i, NULL);
                // Save the students answers.
                $testId = $this->getParam('id', '');
                $questId = $this->getParam('questionId'.$i, '');
                if (!empty($testId) && !empty($questId)) {
                    $fields = array();
                    $fields['testid'] = $testId;
                    $fields['questionid'] = $questId;
                    $postAns = $this->getParam('ans'.$i, NULL);
                    $fields['answerid'] = $postAns;
                    $fields['studentid'] = $this->userId;
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
    private function markTest($resultId)
    {
        $total = 0;
        $testId = $this->getParam('id', '');
        if (!empty($testId)) {
            $data = $this->dbMarked->getCorrectAnswers($this->userId, $testId);
            if (!empty($data)) {
                foreach($data as $item) {
                    if ($item['correct']) {
                        $total = $total+$item['mark'];
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
    private function showStudentTest()
    {
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
            foreach($data as $key => $line) {
                $marked = $this->dbMarked->getMarked($this->userId, $line['questionid'], $testId);
                $data[$key]['studcorrect'] = $marked[0]['correct'];
                $data[$key]['studans'] = $marked[0]['answer'];
                $data[$key]['studorder'] = $marked[0]['answerorder'];
                $data[$key]['studcomment'] = $marked[0]['commenttext'];
            }
        }
        $this->setVarByRef('data', $data);
        $this->setVarByRef('result', $result);
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
    public function generateLinks($current, $total, $num = 4)
    {
        $this->loadClass('link', 'htmlelements');
        $output = '';
        if ($current == 0) {
            $current = 1;
        }
        $rem = ($current-1) %$num;
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
        for ($i = $rem+1 ; $i <= $total ; $i = $i+$num) {
            $end = $i+$num-1;
            if ($end > $total) {
                $link = $i.'&nbsp;-&nbsp;'.$total;
            } else {
                if ($i == $end) {
                    $link = $i;
                } else {
                    $link = $i.'&nbsp;-&nbsp;'.$end;
                }
            }
            if ($i == $current) {
                if ($i == 1) {
                    $output.= $link;
                } else {
                    $output.= '&nbsp;&nbsp;|&nbsp;&nbsp;'.$link;
                }
            } else {
                $j = $i-1;
                $objLink = new link("javascript:submitform('$j');");
                $objLink->link = $link;
                if ($i == 1) {
                    $output.= $objLink->show();
                } else {
                    $output.= '&nbsp;&nbsp;|&nbsp;&nbsp;'.$objLink->show();
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
    public function getIps($comLab)
    {
        $contentRoot = $this->objConfig->getcontentBasePath();
        $fileLocation = $contentRoot.'/modules/mcqtests/';
        if (!is_dir($fileLocation)) {
            $this->objMkdir->fullFilePath = $fileLocation;
            $this->objMkdir->makedir();
        }
        $file = $fileLocation.$comLab.'.csv';
        $arrIpAddresses = array();
        $fp = fopen($file, 'r');
        while ($line = fgetcsv($fp, 1024, ",")) {
            $arrIpAddresses[] = $line[0];
        }
        fclose($fp);
        return $arrIpAddresses;
    }
} // end of class
?>
