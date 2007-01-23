<?php
/**
* @package worksheet
* @filesource
*/

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}


/**
* Controller class for the worksheet module.
*
* Worksheet provides functionality for lectures to create, edit and delete worksheets and mark
* answered worksheets submitted by the students in the context.
*
* Functionality is provided for students to answer the worksheet and submit it for marking, and
* view the marked worksheet.
*
* @author Megan Watson
* @author Tohir Solomons
* @copyright (c) 2004 UWC
* @package worksheet
* @version 0.2
*/
class worksheet extends controller
{
    /**
    * @var string $action The action parameter from the querystring
    */
   public $action;

    /**
    * Standard constructor method
    */
    public function init()
    {
        // Check if the module is registered and redirect if not.
        // Check if the assignment module is registered and can be linked to.
        $this->objModules =& $this->newObject('modules','modulecatalogue');
        if(!$this->objModules->checkIfRegistered('worksheet')){
            return $this->nextAction('notregistered',array(),'redirect');
        }
        $this->assignment = FALSE;
        //if($this->objModules->checkIfRegistered('Assignment Management', 'assignment')){
        //    $this->assignment = TRUE;
        //}
				
        $this->action = $this->getParam('action', NULL);
        $this->objLanguage = &$this->getObject('language', 'language');
        $this->objWorksheet =& $this->getObject('dbworksheet','worksheet');
        $this->objWorksheetQuestions =& $this->getObject('dbworksheetquestions','worksheet');
        $this->objWorksheetAnswers =& $this->getObject('dbworksheetanswers','worksheet');
        $this->objWorksheetResults =& $this->getObject('dbworksheetresults','worksheet');
        $this->objDate =& $this->newObject('datetime','utilities');
			
        // User
        $this->objUser =& $this->getObject('user', 'security');
        $this->user = $this->objUser->fullname();
        $this->userId = $this->objUser->userId();

        $this->objFile =& $this->getObject('upload','filemanager');
       // $this->objFile->changeTables('tbl_worksheet_filestore','tbl_worksheet_blob');

        // Context Code
        $this->contextObject =& $this->getObject('dbcontext', 'context');
        $this->objContentNodes =& $this->getObject('dbcontentnodes', 'context');
        $this->contextCode = $this->contextObject->getContextCode();
        $this->contextTitle = $this->contextObject->getTitle();

        if ($this->contextCode == ''){
            $this->contextCode = 'root';
            $this->contextTitle = $this->objLanguage->languageText('word_inLobby');
        }

        $this->setVarByRef('contextCode', $this->contextCode);
        $this->setVarByRef('contextTitle', $this->contextTitle);

        // Multi Lingualisation
        $this->setVarByRef('objLanguage', $this->objLanguage);
			
        // Log this call if registered
        if(!$this->objModules->checkIfRegistered('logger', 'logger')){
            //Get the activity logger class
            $this->objLog=$this->newObject('logactivity', 'logger');
            //Log this module call
            $this->objLog->log();
        }
    }


    /**
    * Standard dispatch method
    */
    public function dispatch()
    {
        switch ($this->action) {
            // Display the start of a worksheet for answering
            case 'selectforanswer':
                $qNum=$this->objWorksheetAnswers->countAnswers($this->getParam('id'),$this->userId);
                $count=$this->objWorksheetQuestions->getNumQuestions($this->getParam('id'));
                if($count == $qNum){
                    $qNum = 0;
                }
                return $this->answerWorksheet($this->getParam('id'), $qNum);

            // Display the rest of the worksheet for answering
            case 'question':
                return $this->answerWorksheet($this->getParam('id'), $this->getParam('qNum'));

            // Save an answer
            case 'saveanswer':
                return $this->saveAnswer();

            // change the editor for the question
            case 'changeeditor':
                $editor = $this->getParam('neweditor');
                $worksheet_id = $this->getParam('worksheet_id', '');
                $qNum = $this->getParam('qNum', '')-4;
                if($qNum < 0){
                    $qNum = 0;
                }
                $this->addAnswers($worksheet_id);
                return $this->nextAction('question', array('id'=>$worksheet_id, 'qNum'=>$qNum, 'editor'=>$editor));

            // Display marked worksheet for viewing by the student
            case 'viewmarked':
                $action = $this->getParam('num');
                if($action == 'exit'){
                    return $this->nextAction('');
                }
                return $this->viewMarked();

            // Display image
            // Option: use filepreview,filemanager 
            case 'viewimage':
                $fileId = $this->getParam('fileid', NULL);
                return $this->objFile->outputFile2($fileId);

            default:
                return $this->worksheetHome();
        }//switch
    } // dispatch

    /**
    * Method to display a list of worksheets in context to the user.
    * @return The template for the worksheet home page.
    */
    public function worksheetHome()
    {
        $ar = $this->objWorksheet->getWorksheetsInContext($this->contextCode);
		//echo '<pre />';		
		//print_r($ar);
		//die ();
        if(!empty($ar)){
            foreach($ar as $key=>$row){
                $sql = "SELECT title FROM tbl_context_nodes WHERE ";
                $sql .= "id = '".$row['chapter']."'";
                $nodes = $this->objContentNodes->getArray($sql);

                if(!empty($nodes)){
                    $ar[$key]['node'] = $nodes[0]['title'];
                }else{
                    $ar[$key]['node'] = '';
                }
                $ar[$key]['date'] = $this->formatDate($row['closing_date']);
            }

            $data=$this->objWorksheetResults->checkSubmit($this->userId);
            if(!empty($data)){
                foreach($data as $line){
                    foreach($ar as $key=>$row){
                        if($row['id']==$line['worksheet_id']){
                            $ar[$key]['completed']=TRUE;
                        }else{
                            $ar[$key]['completed']=FALSE;
                        }
                    }
                }
            }
        }
        $this->setVarByRef('ar', $ar);
        return 'worksheet_student_tpl.php';
    }

    /**
    * Method to display worksheet questions for answering.
    * $id The id of the current worksheet.
    * $qNum The number of the current question in the worksheet.
    * @return The template to answer a worksheet.
    */
    public function answerWorksheet($id,$qNum)
    {
        if(empty($qNum)){
            $qNum=0;
        }
        $worksheet =& $this->objWorksheet->getRow('id', $id);
        $worksheet['qNum']=$qNum;
        if ($worksheet == '') {
            return $this->nextAction(NULL);
        }
        $this->setVarByRef('worksheet',$worksheet);

        $question = $this->objWorksheetQuestions->getQuestions($id,$qNum);

        $count = $this->objWorksheetQuestions->getNumQuestions($worksheet['id']);
        $this->setVarByRef('count',$count);

        // Get answered questions
        foreach($question as $key=>$val){
            if($this->objWorksheetAnswers->answerExists($val['id'],$this->userId)){
                $result=$this->objWorksheetAnswers->getAnswer($val['id'],$this->userId);
                $question[$key]['answer']=$result[0]['answer'];
            }else{
                $question[$key]['answer']='';
            }
        }
        $this->setVarByRef('question', $question);
        return 'answer_worksheet_tpl.php';
    }

    /**
    * Method to insert Students answer(s) in database.
    * @param string $worksheet_id The id of the current worksheet.
    * @return
    */
    public function addAnswers($worksheet_id)
    {
        // Insert answers
        $fields = array(); $last = FALSE;
        $num = $this->getParam('num', '');

        // Count back from the last entered answer till find a non empty answer
        for($i = $num-1; $i >= 0; $i--){
            $postAnswer = $this->getParam('answer'.$i, '');
            $fields[$i]['question_id'] = $this->getParam('question'.$i, '');
            $fields[$i]['student_id'] = $this->userId;
            $fields[$i]['dateAnswered'] = date('Y-m-d H:i:s');
            $fields[$i]['answer'] = $postAnswer;
        }
        $this->objWorksheetAnswers->insertAnswer($fields);

        // Save the date updated to the results table. $result_id = FALSE if it doesn't exist.
        $result_id = $this->objWorksheetResults->getId($this->userId, $worksheet_id);
        $fields = array();
        $fields['worksheet_id'] = $worksheet_id;
        $fields['completed'] = 'N';
        $fields['userId'] = $this->userId;
        $fields['last_modified'] = date('Y-m-d H:i:s');

        // Set worksheet as completed if student has submitted
        $postSave = $this->getParam('save', NULL);
        $postSubmit = $this->getParam('submitAns', NULL);
        if(isset($postSubmit)){
            $fields['completed']='Y';
        }
        $this->objWorksheetResults->addResult($fields,$result_id);
    }

    /**
    * Method to insert Students answer(s) in database.
    * @return The next action: question or default.
    */
    public function saveAnswer()
    {
        $qNum = $this->getParam('qNum', '');
        $editor = $this->getParam('editor', '');
        $worksheet_id = $this->getParam('worksheet_id', '');
        $postSave = $this->getParam('save', NULL);
        $postSubmit = $this->getParam('submitAns', NULL);

        $this->addAnswers($worksheet_id);

        // If student used navigation at bottom of page
        if(!isset($postSave) && !isset($postSubmit)){
            return $this->nextAction('question', array('id'=>$worksheet_id, 'qNum'=>$qNum, 'editor'=>$editor));
        }

        // If student clicked save and continue button
        $continue = $this->objLanguage->languageText('mod_worksheet_continue','worksheet');
        if(isset($postSave) && !(strpos($postSave, $continue) === FALSE)){
            return $this->nextAction('question', array('id'=>$worksheet_id, 'qNum'=>$qNum, 'editor'=>$editor));
        }
        return $this->nextAction('');
    }

    /**
    * Method to display a list of links to other questions in the worksheet.
    * @param string $current The current question.
    * @param string $total The total number of questions in the worksheet.
    * @param string $worksheet_id The id of the worksheet being answered.
    * @return The links.
    */
    public function generateLinks($current, $total, $num=4)
    {
        $this->loadClass('link', 'htmlelements');
        $output='';
        if($current==0){
            $current=1;
        }
        $rem=($current-1)%$num;

        if($rem!=0){
            if($rem==1){
                $link = '1';
            }else{
                $link = "1 - $rem";
            }
            $objLink = new link("javascript:submitform('0');");
            $objLink->link=$link;
            $output .= $objLink->show();
        }

        for($i=$rem+1; $i<=$total; $i=$i+$num){
            $end=$i+$num-1;
            if($end > $total){
                $link = $i.'&nbsp;-&nbsp;'.$total;
            }else{
                if($i==$end){
                    $link = $i;
                }else{
                    $link = $i.'&nbsp;-&nbsp;'.$end;
                }
            }
            if($i==$current){
                if($i==1){
                    $output .=  $link;
                }else{
                    $output .= '&nbsp;&nbsp;|&nbsp;&nbsp;'.$link;
                }
            }else{
                $j=$i-1;
                $objLink = new link("javascript:submitform('$j');");
                $objLink->link=$link;
                if($i==1){
                    $output .= $objLink->show();
                }else{
                    $output .= '&nbsp;&nbsp;|&nbsp;&nbsp;'.$objLink->show();
                }
            }
        }
        return $output;
    }

    /**
    * Method to display the marked worksheet to the student.
    * @return The template to view a marked worksheet.
    */
    public function viewMarked()
    {
        $worksheet_id = $this->getParam('worksheet_id');
        $num = $this->getParam('num');
        $student_id = $this->userId;

        if(empty($num)){
            $num=0;
        }
        $worksheet = $this->objWorksheet->getWorksheet($worksheet_id,
        'id, name, chapter, percentage, total_mark');

        $sql = "SELECT title FROM tbl_context_nodes WHERE ";
        $sql .= "id = '$worksheet_id'";
        $nodes = $this->objContentNodes->getArray($sql);

			if(isset($nodes[0]['title'])){
        		$worksheet[0]['node'] = $nodes[0]['title'];
        }else{
        		$worksheet[0]['node'] = '';
        }

        $count = $this->objWorksheetQuestions->getNumQuestions($worksheet_id);
        $worksheet[0]['count']=$count;

        $data = $this->objWorksheetAnswers->getAnswers($worksheet_id, $student_id,
        $num, 5);

        $this->setVarByRef('worksheet',$worksheet[0]);
        $this->setVarByRef('data',$data);

        return 'view_marked_tpl.php';
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

} // end of class
?>