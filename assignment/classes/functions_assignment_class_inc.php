<?php
/**
* @package assignmentadmin
*/

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
// end security check

/**
* The assignment admin block class displays a block with an alert if students have handed in.
* @author Jameel Adam
*/

class functions_assignment extends object
{
    /**
    * Constructors
    */
    public function init()
    {
        $this->objLanguage = $this->getObject('language', 'language');
        $objDbContext = $this->getObject('dbcontext', 'context');
 		$this->contextCode = $objDbContext->getContextCode();
		$this->objDate = $this->getObject('dateandtime','utilities');
       	$this->dbAssignment = $this->getObject('dbassignment','assignment');
        $this->dbSubmit = $this->getObject('dbassignmentsubmit','assignment');
 		$this->objCleaner = $this->getObject('htmlcleaner', 'utilities');
		$this->objContext = $this->getObject('dbcontext','context');
		$this->objUser = $this->getObject('user','security');
		$this->userId = $this->objUser->userId();
		if($this->objContext->isInContext()){
            $this->contextCode=$this->objContext->getContextCode();
            $this->context=$this->objContext->getTitle();
        }
        $this->test = FALSE;
		$this->essay = FALSE;
		$this->ws = FALSE;
		$this->rubric = FALSE;       


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
		return $comment;

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
            $cleanHtmltext = $this->objhtmlcleaner->cleanHtml($text);
            $fields['online'] = $cleanHtmltext;
        }

        $postSubmitId = $this->getParam('submitid', NULL);
        $id = $this->dbSubmit->addSubmit($fields, $postSubmitId);
        return $id;
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
            .$this->objUser->userId()."'", 'id, online, studentfileid');
            if(!empty($submit)){
                $data[0]['online'] = $submit[0]['online'];
                $data[0]['fileid'] = $submit[0]['fileid'];
                $data[0]['submitid'] = $submit[0]['id'];
            }
        }
		return $data;
        
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
		$mixed_arr = array();
		$mixed_arr[0] = $essayData;
		$mixed_arr[1] = $wsData;
		$mixed_arr[2] = $testData;
		$mixed_arr[3] = $assignData;
		return $mixed_arr;
    }

 }//end of class
?>
