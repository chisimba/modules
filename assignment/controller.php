<?php
/**
* Assignment class extends controller
* @author Megan Watson
* @copyright (c) 2004 UWC
* @package assignment
* @version 1
* @filesource
*/

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
} // end security check

 /**
 * Module class to handle the management of assignments.
 * Forms part of formative assessment
 * @author Megan Watson
 * @copyright (c) 2004 UWC
 * @package assignment
 * @version 1
 */

class assignment extends controller
{
    public $contextcode;

    /**
    * Initialise objects used in the module.
    */
    public function init()
    {
        // Check if the module is registered and redirect if not.
        // Check if the assignment module is registered and can be linked to.
        /*$this->objModules = $this->getObject('modules','modulecatalogue');
        if(!$this->objModules->checkIfRegistered('Assignments', 'assignment')){
            return $this->nextAction('notregistered',array('modname'=>'assignment'), 'redirect');
        }*/
	
        $this->test = FALSE;
	/*if($this->objModules->checkIfRegistered('Online Tests','test')){
            $this->test = TRUE;
        }
	*/
        $this->essay = FALSE;
        /*if($this->objModules->checkIfRegistered('Essay Management','essay')){
            $this->essay = TRUE;
        }
	*/
        $this->ws = FALSE;
        /*if($this->objModules->checkIfRegistered('Worksheets','worksheet')){
            $this->ws = TRUE;
        }
	*/
        $this->rubric = FALSE;
        /*if($this->objModules->checkIfRegistered('Rubrics','rubric')){
            $this->rubric = TRUE;
        }
	*/
        $this->dbAssignment = $this->getObject('dbassignment','assignment');
        $this->dbSubmit = $this->getObject('dbassignmentsubmit','assignment');

        /*if($this->essay){
            $this->dbEssayTopics = $this->getObject('dbessay_topics','essay');
            $this->dbEssays = $this->getObject('dbessays','essay');
            $this->dbEssayBook = $this->getObject('dbessay_book','essay');
        }
	*/
        if($this->ws){
            $this->dbWorksheet = $this->getObject('dbworksheet','worksheet');
            $this->dbWorksheetResults = $this->getObject('dbworksheetresults','worksheet');
        }
        /*if($this->test){
            $this->dbTestAdmin = $this->getObject('dbtestadmin','testadmin');
            $this->dbTestResults = $this->getObject('dbresults','testadmin');
        }
	*/
        $this->objDate = $this->getObject('dateandtime','utilities');
        $this->objLanguage = $this->getObject('language','language');
        $this->objUser = $this->getObject('user','security');
        //$this->objGroups = $this->getObject('groupAdminModel','groupadmin');
        $this->objContext = $this->getObject('dbcontext','context');
		$this->dbassignmentsubmit = $this->getObject('dbassignmentsubmit');
        // Get an instance of the filestore object and change the tables to essay specific tables
       $this->objFile= $this->getObject('upload','filemanager');
       // $this->objFile->changeTables('tbl_assignment_filestore','tbl_assignment_blob');
		$this->objFileRegister = $this->getObject('registerfileusage', 'filemanager');
		$this->objCleaner = $this->getObject('htmlcleaner', 'utilities');

        $this->userId = $this->objUser->userId();

        if($this->objContext->isInContext()){
            $this->contextCode=$this->objContext->getContextCode();
            $this->context=$this->objContext->getTitle();
        }

        // Log this call if registered
        /*if(!$this->objModules->checkIfRegistered('logger', 'logger')){
            //Get the activity logger class
            $this->objLog=$this->getObject('logactivity', 'logger');
            //Log this module call
            $this->objLog->log();
        }*/
    }

    /**
    * The standard dispatch method for the module.
    * The dispatch() method must return the name of a page body template which will
    * render the module output (for more details see Modules and templating)
    * @return The template to display
    */
    public function dispatch($action)
    {
        switch($action){
            // view the assignment
            case 'view':
                $var = $this->getParam('var', FALSE);
                return $this->viewAssign($var);

            // insert the submitted assignment in the database
            case 'submit':
                $postSave = $this->getParam('save', '');
                if($postSave == $this->objLanguage->languageText('word_exit')){
                    return $this->nextAction('');
                }
                return $this->submitAssign();

            // change the editor for the assignment
            case 'changeeditor':
                $id = $this->getParam('id', '');
                $editor = $this->getParam('editor');
                $this->saveAssign();
                return $this->nextAction('view', array('id'=>$id, 'editor'=>$editor, 'var'=>TRUE));

            // download an assignment
            case 'download':
                $this->setPageTemplate('download_page_tpl.php');
                return 'download_tpl.php';

            case 'showcomment':
                return $this->showComment();
                
         	case 'upload';
         		$id = $this->getParam('id');
         		$this->setVarByRef('id',$id);
         		return 'upload_tpl.php';
         	break;
         	
         	case 'uploadsubmit':
                // get topic id
                $id=$this->getParam('id');
                // get booking id
                //$book=$this->getParam('book');
                $msg = '';
                $postSubmit = $this->getParam('submit');
				
                // exit upload form
                if($postSubmit==$this->objLanguage->languageText('word_exit')){
                    return $this->nextAction('');
                }

                // upload essay and return to form
                if($postSubmit==$this->objLanguage->languageText('mod_assignment_upload', 'assignment')){

                    // change the file name to fullname_studentId
                    $studentid = $this->userId;
                    //$name = $this->user;
    
                    // upload file to database
					$arrayfiledetails = $this->objFile->uploadFile('file');
		
					if ($arrayfiledetails['success']){				
                    	// save file id and submit date to database
                    	$fields=array(
                    		'userid'=>$studentid,
                    		'assignmentid'=>$id,
                    		'updated'=>date('Y-m-d H:i:s'),
                    		'studentfileid'=>$arrayfiledetails['fileid'],
                    		'datesubmitted'=>date('Y-m-d H:i:s')
                        );
                    	$this->dbassignmentsubmit->addSubmit($fields);
						$this->objFileRegister->registerUse($arrayfiledetails['fileid'], 'assignment', 'tbl_assignment_submit', $id, 'studentfileid', $this->contextcode, '', TRUE);	
                    	// display success message
                    	$msg = $this->objLanguage->languageText('mod_assignment_confirmupload','assignment');
                    	$this->setVarByRef('msg',$msg);
                    }
                }
                return $this->studentHome();
            break;

            default:
                return $this->studentHome();
        }
    }

    /**
    * Method to display the Students home page.
    * @return The template for the students home page.
    */
    public function studentHome()
    {
        // Get students assignments: worksheets, booked essays
        $wsData = array(); $essay = array(); $topic = array(); $essayData = array();
        $assignData = array(); $testData = array();

        if($this->ws){
            $wsData = $this->dbWorksheet->getWorksheetsInContext($this->contextCode);
            if(!empty($wsData)){
                foreach($wsData as $key=>$line){
                    $result = $this->dbWorksheetResults->getResults(NULL, "worksheet_id='"
                            .$line['id']."' AND userid='".$this->userId."'");
                    $wsData[$key]['mark'] = $result[0]['mark'];
                    $wsData[$key]['completed'] = $result[0]['completed'];
                }
            }
        }
        if($this->essay){
            // get topic list for the context
            $topicFilter = "context='".$this->contextCode."'";
            $topicFields = 'id, name, closing_date, userid';
            $topics = $this->dbEssayTopics->getTopic(NULL, $topicFields, $topicFilter);

            // check booked topics and get booked essays
            if(!empty($topics)){
                $i = 0;
                foreach($topics as $item){
                    $bookFilter = "where studentid='".$this->userId."' and topicid='".$item['id']."'";
                    $booking = $this->dbEssayBook->getBooking($bookFilter);
                    if(!empty($booking)){
                        $essay = $this->dbEssays->getEssay($booking[0]['essayid'], 'topic');
                        $booking[0]['essayName'] = $essay[0]['topic'];
                        $booking[0]['topicName'] = $item['name'];
                        $booking[0]['closing_date'] = $item['closing_date'];
                        $booking[0]['lecturer'] = $item['userid'];
                        $essayData[] = $booking[0];
                    }else{
                        $i++;
                    }
                }
                if($i > 0){
                    $essayData[]['unassigned'] = $i;
                }
            }
        }
        if($this->test){
            $filter =
            $testData = $this->dbTestAdmin->getTests($this->contextCode);
            if(!empty($testData)){
                foreach($testData as $key=>$line){
                    $result = $this->dbTestResults->getResult($this->userId, $line['id']);
                    if(!empty($result)){
                        $testData[$key]['mark'] = $result[0]['mark'];
                    }else{
                        $testData[$key]['mark'] = 'none';
                    }
                }
            }
        }
        $assignData = $this->dbAssignment->getAssignment($this->contextCode);

 
        if(!empty($assignData)){
            foreach($assignData as $key=>$val){
                $submitData = $this->dbSubmit->getSubmit("assignmentid='".$val['id']."' AND
                userid='".$this->objUser->userId()."'", 'id AS submitid, mark AS studentmark, datesubmitted, studentfileid');

		if(!($submitData === FALSE)){
                	$assignData[$key] = array_merge($val, $submitData[0]);
		}
            }
        }
        $msg = $this->getParam('confirm');
        if(!empty($msg)){
            $this->setVarByRef('msg',$msg);
        }

        $this->setVarByRef('essayData', $essayData);
        $this->setVarByRef('wsData', $wsData);
        $this->setVarByRef('testData', $testData);
        $this->setVarByRef('assignData', $assignData);
        return 'assignment_student_tpl.php';
    }

    /**
    * Method to display the assignment.
    * @param bool $var Allows the assignment to be resubmitted.
    * @return The template for displaying the assignment.
    */
    public function viewAssign($var = FALSE)
    {
        $id = $this->getParam('id');
        $data = $this->dbAssignment->getAssignment($this->contextCode, "id='$id'");

        if($data[0]['resubmit'] || $var){
            $submit = $this->dbSubmit->getSubmit("assignmentid='$id' AND userid='"
            .$this->objUser->userId()."'", 'id, online, fileId');
            if(!empty($submit)){
                $data[0]['online'] = $submit[0]['online'];
                $data[0]['fileid'] = $submit[0]['fileid'];
                $data[0]['submitid'] = $submit[0]['id'];
            }
        }

        $this->setVarByRef('data', $data);
        return 'assignment_view_tpl.php';
    }

    /**
    * Method to display a lecturers comment in a pop-up window
    */
    public function showComment()
    {
        $id = $this->getParam('id');
        $name = $this->getParam('name');
        $comment = $this->dbSubmit->getSubmit("id='$id'",'comment');
        $comment[0]['name'] = $name;
        $this->setVarByRef('data',$comment);
        return 'assignment_comment_tpl.php';
    }

    /**
    * Method to save a students submitted assignment in the database
    */
    public function saveAssign()
    {
        $fields = array();
        $fields['assignmentid'] = $this->getParam('id', '');
        $fields['userid'] = $this->userId;
        $fields['datesubmitted'] = date('Y-m-d H:i', time());

        $postFormat = $this->getParam('format');
        if($postFormat && isset($_FILES['file'])){
            $fileId = $this->getParam('fileid', NULL);
	    	$fileId = $this->objFile->uploadFile($_FILES['file'],'file',$fileId);
            $fields['fileid'] = $fileId;
        }else{
            $text = $this->getParam('text', '');
            $text = $this->objCleaner->cleanHtml($text);
            $fields['online'] = $text;
        }

        $postSubmitId = $this->getParam('submitid', NULL);
        $id = $this->dbSubmit->addSubmit($fields, $postSubmitId);
        return $id;
    }

    /**
    * Method to save a students submitted assignment in the database
    */
    public function submitAssign()
    {
        $id = $this->saveAssign();
        if(!($id === FALSE)){
            $msg = $this->objLanguage->languageText('mod_assignment_confirmsubmit','assignment');
        }
        return $this->nextAction('',array('confirm'=>$msg));
    }

    /**
    * Method to take a datetime string and reformat it as text.
    * @param string $date The date in datetime format.
    * @return string $ret The formatted date.
    */
    public function formatDate($date)
    {
        $ret = $this->objDate->formatDate($date);
        return $ret;
	}
}// end class assignment
?>