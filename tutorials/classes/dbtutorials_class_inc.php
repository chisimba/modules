<?php
/* ------ data class extends dbTable for all tutorials database tables ------*/

// security check - must be included in all scripts
if(!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* Model class for the tutorials database tables
* @author Kevin Cyster
*/

class dbtutorials extends dbTable
{
    /**
    * @var object $objLanguage: The language class in the language module
    * @access private
    */
    private $objLanguage;
    /**
    * @var object $objUser: The user class in the security module
    * @access private
    */
    private $objUser;

    /**
    * @var string $userId: The user id of the current user
    * @access private
    */
    private $userId;

    /**
    * @var object $objContext: The dbcontext class in the context module
    * @access public
    */
    public $objContext;

    /**
    * @var string $contextCode: The active context of the current logged in user
    * @access public
    */
    public $contextCode;
        
    /**
    * @var object $objGroups: The managegroups class in the contextgroups module
    * @access public
    */
    public $objGroups;

    /**
    * @var string $table: The the name of the current table
    * @access private
    */
    private $table;

    /**
    * Method to construct the class
    *
    * @access public
    * @return
    */
    public function init()
    {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objUser = $this->getObject('user', 'security');
        $this->userId = $this->objUser->userId();
        $this->objContext = $this->getObject('dbcontext', 'context');
        $this->contextCode = $this->objContext->getContextCode();
        $this->objGroups = $this->getObject('managegroups', 'contextgroups');
    }
    
/* ----- Functions for changeing tables ----- */

	/**
	* Method to dynamically switch tables
	*
	* @access private
	* @param string $table: The name of the table
	* @return boolean: TRUE on success FALSE on failure
	*/
	private function _changeTable($table)
	{
		try{
		 	$this->table = $table;
			parent::init($table);
			return TRUE;
		}catch(customException $e){
			customException::cleanUp();
			return FALSE;
		}
	}
	
	/**
	* Method to set the tutorials table
	*
	* @access private
	* @return boolean: TRUE on success FALSE on failure
	*/
	private function _setTutorials()
	{
        return $this->_changeTable('tbl_tutorials');
    }

	/**
	* Method to set the tutorials questions table
	*
	* @access private
	* @return boolean: TRUE on success FALSE on failure
	*/
	private function _setQuestions()
	{
        return $this->_changeTable('tbl_tutorials_questions');
    }

	/**
	* Method to set the tutorials answers table
	*
	* @access private
	* @return boolean: TRUE on success FALSE on failure
	*/
	private function _setAnswers()
	{
        return $this->_changeTable('tbl_tutorials_answers');
    }

	/**
	* Method to set the tutorials results table
	*
	* @access private
	* @return boolean: TRUE on success FALSE on failure
	*/
	private function _setResults()
	{
        return $this->_changeTable('tbl_tutorials_results');
    }

	/**
	* Method to set the tutorials instructions table
	*
	* @access private
	* @return boolean: TRUE on success FALSE on failure
	*/
	private function _setInstructions()
	{
        return $this->_changeTable('tbl_tutorials_instructions');
    }

	/**
	* Method to set the tutorials late submissions table
	*
	* @access private
	* @return boolean: TRUE on success FALSE on failure
	*/
	private function _setLate()
	{
        return $this->_changeTable('tbl_tutorials_late');
    }

	/**
	* Method to set the tutorials audit table
	*
	* @access private
	* @return boolean: TRUE on success FALSE on failure
	*/
	private function _setAudit()
	{
        return $this->_changeTable('tbl_tutorials_audit');
    }

/* ----- Functions for name spacing ----- */

	/**
	* Method to define the name space for tutorials
	*
	* @access private
	* @return string $nameSpace: The name space
	*/
	private function _defineNameSpace()
	{
		$nameSpace = rand(1, 9999).'_'.$this->contextCode.'_'.rand(1, 9999);

		$sql = "SELECT * FROM ".$this->table;
		$sql .= " WHERE name_space='".$nameSpace."'";
		$data = $this->getArray($sql);
		if($data != FALSE){
			$this->_defineNameSpace($tableName);
		}
		return $nameSpace;
	}

	/**
	* Method to determine the name space order (number) of the tutorials
	*
	* @access private
	* @param string $nameSpace: The name space of the tutorial
* @param string $tableName: The name of the table to create a name space in
	* @return integer $nOrder: The order (number) of the name space being added
	*/
	private function _getNameSpaceOrder($nameSpace)
	{
		$sql = "SELECT * FROM ".$this->table;
		$sql .= " WHERE name_space = '".$nameSpace."'";
		$data = $this->getArray($sql);
		if($data != FALSE){
			$nOrder = (count($data) + 1);
		}else{
			$nOrder = 1;
		}
		return $nOrder;
	}
	
/* ----- Functions for tbl_tutorials ----- */

    /**
    * Method to add a tutorial
    *
    * @access public
    * @param string $name: The name of the tutorial
    * @param string $type: The type of the tutorial (normal/interactive)
    * @param string $description: The description of the tutorial
    * @param string $percentage: The % that this tutorial counts towards the year mark
    * @param string $answerOpen: The date the tutorial is open for answers
    * @param string $answerClose: The date the tutorial is closed for answers
    * @param string $markingClose: The date the tutorial is closed for marking
    * @param string $moderationClosed: The date the tutorial is closed for moderation
    * @return string|bool $tutorialId: The tutorial id |False on failure
    */
    public function addTutorial($name, $type, $description, $percentage, $answerOpen, $answerClose, $markingClose = NULL, $moderationClose = NULL)
    {
        $this->_setTutorials();
        
        $date = date('Y-m-d H:i:s');
        $tNameSpace = $this->_defineNameSpace();
        
        $fields = array();
        $fields['name_space'] = $tNameSpace;
        $fields['name_space_order'] = 1;
        $fields['contextcode'] = $this->contextCode;
        $fields['name'] = $name;
        $fields['type'] = $type;
        $fields['description'] = $description;
        $fields['percentage'] = $percentage;
        $fields['answer_open_date'] = $answerOpen;
        $fields['answer_close_date'] = $answerClose;
        $fields['marking_close_date'] = $markingClose;
        $fields['moderating_close_date'] = $moderationClose;
        $fields['deleted'] = 0;
        $fields['updated'] = $date;
        $tutorialId = $this->insert($fields);
        if($tutorialId != FALSE){
         	$comment = $this->objLanguage->languageText('word_add');
			$this->_addAuditTrail($this->table, $tNameSpace, $date, $comment);
	        return $tNameSpace;
		}
		return FALSE;
    }
    
    /**
    * Method to edit a tutorial
    *
    * @access public
    * @param string $tNameSpace: The name space of the tutorial
    * @param string $name: The name of the tutorial
    * @param string $type: The type of the tutorial (normal/interactive)
    * @param string $description: The description of the tutorial
    * @param string $percentage: The % that this tutorial counts towards the year mark
    * @param string $answerOpen: The date the tutorial is open for answers
    * @param string $answerClose: The date the tutorial is closed for answers
    * @param string $markingClose: The date the tutorial is closed for marking
    * @param string $moderationClosed: The date the tutorial is closed for moderation
    * @param string $deleted: Indicator if the record is deleted
    * @param string $comment: Audit comment
    * @return void
    */
    public function editTutorial($tNameSpace, $name, $type, $description, $percentage, $answerOpen, $answerClose, $markingClose, $moderationClose, $mark, $deleted = 0, $comment = NULL)
    {
        $this->_setTutorials();
        $nOrder = $this->_getNameSpaceOrder($tNameSpace);
        
        $date = date('Y-m-d H:i:s');
        $fields = array();
		$fields['name_space'] = $tNameSpace;
		$fields['name_space_order'] = $nOrder;
		$fields['contextcode'] = $this->contextCode;       
        $fields['name'] = $name;
        $fields['type'] = $type;
        $fields['description'] = $description;
        $fields['percentage'] = $percentage;
        $fields['total_mark'] = $mark;
        $fields['answer_open_date'] = $answerOpen;
        $fields['answer_close_date'] = $answerClose;
        $fields['marking_close_date'] = $markingClose;
        $fields['moderating_close_date'] = $moderationClose;
        $fields['deleted'] = $deleted;
        $fields['updated'] = $date;
		$tutorialId = $this->insert($fields);
		if($tutorialId != FALSE){
		 	if(!isset($comment)){
        		$comment = $this->objLanguage->languageText('word_edit');
        	}
			$this->_addAuditTrail($this->table, $tNameSpace, $date, $comment);
			return $tNameSpace;
		}
		return FALSE;
    }

    /**
    * Method to delete a tutorial
    *
    * @access public
    * @param string $tNameSpace: The name space of the tutorial being deleted
    * @return void
    */
    public function deleteTutorial($tNameSpace)
    {
        $data = $this->getTutorial($tNameSpace);
        if($data != FALSE){
			$tNameSpace = $data['name_space'];
        	$name = $data['name'];
        	$type = $data['type'];
        	$description = $data['description'];
        	$percentage = $data['percentage'];
        	$answerOpen = $data['answer_open_date'];
        	$answerClose = $data['answer_close_date'];
        	$markingClose = $data['marking_close_date'];
        	$moderationClose = $data['moderating_close_date'];
        	$mark = $data['total_mark'];
			$comment = $this->objLanguage->languageText('word_delete');
        	
			return $this->editTutorial($tNameSpace, $name, $type, $description, $percentage, $answerOpen, $answerClose, $markingClose, $moderationClose, $mark, 1, $comment);
		}
		return FALSE;
	}
	
	/**
	* Method to list all tutorials in a context
	*
	* @access public
	* @return array|bool $data: The tutorial list on success | FALSE on failure
	*/
	public function listTutorials()
	{
		$this->_setTutorials();
		$sql = "SELECT * FROM ".$this->table;
		$sql .= " WHERE (name_space, name_space_order)";
		$sql .= "  IN (SELECT name_space, MAX(name_space_order)";
		$sql .= "      FROM ".$this->table." GROUP BY name_space)";
		$sql .= " AND contextcode='".$this->contextCode."'";
		$sql .= " AND deleted='0'";
		$sql .= " ORDER BY name ASC";
		$data = $this->getArray($sql);
		if($data != FALSE){
			return $data;
		}
		return FALSE;
	}
	
	/**
	* Method to get a tutorial
	*
	* @access public
	* @param string $tNameSpace: The name space of the tutorial to retrieve
	* @return array|bool $data: The tutorial data on success | FALSE on failure
	*/
	public function getTutorial($tNameSpace)
	{
		$this->_setTutorials();
		$sql = " SELECT * FROM ".$this->table;
		$sql .= " WHERE (name_space, name_space_order)";
		$sql .= "  IN (SELECT name_space, MAX(name_space_order)";
		$sql .= "      FROM ".$this->table." GROUP BY name_space)";
		$sql .= " AND name_space='".$tNameSpace."'";
		$sql .= " AND deleted='0'";
		$data = $this->getArray($sql);
		if($data != FALSE){
			return $data[0];
		}
		return FALSE;
	}
	
	/**
	* Method to update the total marks for the tutorial
	*
	* @access private
	* @param string $tNameSpace: The name space of the tutorial
	* @param integer $value: The value of the change to the total marks
	* @return void
	*/
	private function _updateMarks($tNameSpace, $value)
	{
		$data = $this->getTutorial($tNameSpace);
		$data['total_mark'] = ($data['total_mark'] + $value);
        $name = $data['name'];
        $type = $data['type'];
        $description = $data['description'];
        $percentage = $data['percentage'];
        $mark = $data['total_mark'];
        $answerOpen = $data['answer_open_date'];
        $answerClose = $data['answer_close_date'];
        $markingClose = $data['marking_close_date'];
        $moderationClose = $data['moderating_close_date'];
        
		$this->editTutorial($tNameSpace, $name, $type, $description, $percentage, $answerOpen, $answerClose, $markingClose, $moderationClose, $mark);			
	}

/* ----- Functions for tbl_tutorials_questions ----- */

    /**
    * Method to add a question to the tutorial
    *
    * @access public
    * @param string $tNameSpace: The name space of the tutorial the question is added to
    * @param string $question: The question text
    * @param string $answer: The model answer
    * @param string $worth: The question worth
    * @return string|bool $questionId: The tutorial id |False on failure
    */
    public function addQuestion($tNameSpace, $question, $answer, $worth)
    {
        $this->_setQuestions();
        $qNameSpace = $this->_defineNameSpace();
        
        $date = date('Y-m-d H:i:s');
        $qOrder = $this->_getQuestionOrder($tNameSpace);
        
        $fields = array();
        $fields['tutorial_name_space'] = $tNameSpace;
        $fields['name_space'] = $qNameSpace;
        $fields['name_space_order'] = 1;
        $fields['question'] = $question;
        $fields['model_answer'] = $answer;
        $fields['question_value'] = $worth;
        $fields['question_order'] = $qOrder;
        $fields['deleted'] = 0;
        $fields['updated'] = $date;
        $questionId = $this->insert($fields);
        if($questionId != FALSE){
         	$comment = $this->objLanguage->languageText('word_add');
			$this->_addAuditTrail($this->table, $qNameSpace, $date, $comment);
			$this->_updateMarks($tNameSpace, $worth);
		}
        return $questionId;
    }
    
    /**
    * Method to edit a question on the tutorial
    *
    * @access public
    * @param string $tNameSpace: The name space of the tutorial
    * @param string $qNameSpace: The name space of the question being edited
    * @param string $question: The question text
    * @param string $answer: The model answer
    * @param string $newValue: The new value of the question
    * @param string $oldValue: The old value of the question
    * @return string|bool $questionId: The question id on success |FALSE on failure
    */
    public function editQuestion($tNameSpace, $qNameSpace, $question, $answer, $qOrder, $newValue, $oldValue, $deleted, $comment = NULL)
    {
        $this->_setQuestions();
        $nOrder = $this->_getNameSpaceOrder($qNameSpace);
        
        $date = date('Y-m-d H:i:s');
        $fields = array();
        $fields['tutorial_name_space'] = $tNameSpace;
        $fields['name_space'] = $qNameSpace;
        $fields['name_space_order'] = $nOrder;
        $fields['question'] = $question;
        $fields['model_answer'] = $answer;
        $fields['question_value'] = $newValue;
        $fields['question_order'] = $qOrder;
        $fields['deleted'] = $deleted;
        $fields['updated'] = $date;
        $questionId = $this->insert($fields);
        if($questionId != FALSE){
         	if(!isset($comment)){
        		$comment = $this->objLanguage->languageText('word_edit');
        	}
			$this->_addAuditTrail($this->table, $qNameSpace, $date, $comment);
         	$worth = ($newValue - $oldValue);
         	if($worth != 0){
				$this->_updateMarks($tNameSpace, $worth);
			}
		}
    }

    /**
    * Method to delete a question
    *
    * @access public
    * @param string $qNameSpace: The name space of the question being deleted
    * @return void
    */
    public function deleteQuestion($qNameSpace)
    {
        $data = $this->getQuestion($qNameSpace);
		if($data != FALSE){
        	$tNameSpace = $data['tutorial_name_space'];
        	$qNameSpace = $data['name_space'];
        	$question = $data['question'];
        	$answer = $data['model_answer'];
        	$qOrder = $data['question_order'];
        	$oldValue = $data['question_value'];
        	$deleted = $data['deleted'];
		 
	        $comment = $this->objLanguage->languageText('word_delete');
			$this->editQuestion($tNameSpace, $qNameSpace, $question, $answer, $qOrder, 0, $oldValue, 1, $comment);
			$qAllData = $this->listQuestions($tNameSpace);
			foreach($qAllData as $line){
				$qData[] = $line['name_space'];
			}
			$this->reorderQuestions($qData);
		}
		return FALSE;
	}
	
	/**
	* Method to list all question on a tutorial
	*
	* @access public
	* @param string $tNameSpace: The name space of the tutorial
	* @return array|bool $data: The question list on success | FALSE on failure
	*/
	public function listQuestions($tNameSpace)
	{
		$this->_setQuestions();
		$sql = "SELECT * FROM ".$this->table;
		$sql .= " WHERE (name_space, name_space_order)";
		$sql .= "  IN (SELECT name_space, MAX(name_space_order)";
		$sql .= "      FROM ".$this->table." GROUP BY name_space)";
		$sql .= " AND tutorial_name_space='".$tNameSpace."'";
		$sql .= " AND deleted='0'";
		$sql .= " ORDER BY question_order ASC";
		$data = $this->getArray($sql);
		if($data != FALSE){
			return $data;
		}
		return FALSE;
	}
	
	/**
	* Method to list 4 questions on a tutorial for answering
	*
	* @access public
	* @param string $tNameSpace: The name space of the tutorial
	* @param string $qNum: The question order from which to get questions for answering
	* @return array|bool $data: The question list on success | FALSE on failure
	*/
	public function listQuestionsForAnswering($tNameSpace, $qNum)
	{
		$questions = $this->listQuestions($tNameSpace);
		
		$this->_setQuestions();
		$sql = "SELECT * FROM ".$this->table;
		$sql .= " WHERE (name_space, name_space_order)";
		$sql .= "  IN (SELECT name_space, MAX(name_space_order)";
		$sql .= "      FROM ".$this->table." GROUP BY name_space)";
		$sql .= " AND tutorial_name_space='".$tNameSpace."'";
		$sql .= " AND deleted='0'";
		$sql .= " AND question_order >= '".$qNum."'";
 		$sql .= " ORDER BY question_order ASC";
 		$sql .= " LIMIT 0, 4";
		$data = $this->getArray($sql);
		if($data != FALSE){
		 	$data[0]['total'] = count($questions);
			return $data;
		}
		return FALSE;
	}
	
	/**
	* Method to get a question
	*
	* @access public
	* @param string $qNameSpace: The name space of the tutorial to retrieve
	* @return array|bool $data: The tutorial data on success | FALSE on failure
	*/
	public function getQuestion($qNameSpace)
	{
		$this->_setQuestions();
		$sql = " SELECT * FROM ".$this->table;
		$sql .= " WHERE (name_space, name_space_order)";
		$sql .= "  IN (SELECT name_space, MAX(name_space_order)";
		$sql .= "      FROM ".$this->table." GROUP BY name_space)";
		$sql .= " AND name_space='".$qNameSpace."'";
		$sql .= " AND deleted='0'";
		$data = $this->getArray($sql);
		if($data != FALSE){
			return $data[0];
		}
		return FALSE;
	}
	
	/**
	* Method to determine the question order (number) of the question being added
	*
	* @access private
	* @param string $tNameSpace: The name space of the tutorial the question is on
	* @return integer $qOrder: The order (number)of the question being added
	*/
	private function _getQuestionOrder($tNameSpace)
	{
		$this->_setQuestions();
		$sql = "SELECT * FROM ".$this->table;
		$sql .= " WHERE (name_space, name_space_order)";
		$sql .= "  IN (SELECT name_space, MAX(name_space_order)";
		$sql .= "      FROM ".$this->table." GROUP BY name_space)";
		$sql .= " AND tutorial_name_space='".$tNameSpace."'";
		$sql .= " AND deleted='0'";
		$sql .= " ORDER BY question_order ASC";
		$data = $this->getArray($sql);
		if($data != FALSE){
			$qOrder = (count($data) + 1);
		}else{
			$qOrder = 1;
		}
		return $qOrder;
	}
	
	/**
	* Method to import questions
	*
	* @access public
	* @param array $file: The uploaded file details
	* @param string $tNameSpace: The name space of the tutorial the questions are imported to
	* @param integer $overwrite: An indicator to see if the questions are to be deleted
	* @return void
	*/
	public function importQuestions($file, $tNameSpace, $overwrite)
	{
		if(is_uploaded_file($file['import']['tmp_name'])){
			if($overwrite == 1){
				$data = $this->listQuestions($tNameSpace);
				if($data != FALSE){
					foreach($data as $line){
						$this->deleteQuestion($line['name_space']);
					}
				}
			}
			$handle = fopen($file['import']['tmp_name'], "r");
			while(($data = fgetcsv($handle, 0, ",")) !== FALSE){
				$this->addQuestion($tNameSpace, $data[0], $data[1], $data[2]);			
			}
			fclose($handle);
		}
	}
	
	/**
	* Method to reorder the question list
	*
	* @access public
	* @param array $list: The list of guestions
	* @return bool $result: TRUE on success | FALSE on failure
	*/
	public function reorderQuestions($list)
	{
		$this->_setQuestions();
		if(is_array($list)){
		 	$order = 1;
			foreach($list as $qNameSpace){
				$data = $this->getQuestion($qNameSpace);
				if($data != FALSE){
					$this->editQuestion($data['tutorial_name_space'], $qNameSpace, $data['question'], $data['model_answer'], $order, $data['question_value'], $data['question_value'], 0);
					$order++;					
				}				
			}
			return TRUE;	
		}
		return FALSE;
	}

	/**
	* Method to get a question on the tutorial for marking
	*
	* @access public
	* @param string $tNameSpace: The name space of the tutorial
	* @param string $qNum: The question order to get the question for marking
	* @return array|bool $data: The question list on success | FALSE on failure
	*/
	public function getQuestionForMarking($tNameSpace, $qNum)
	{
		$questions = $this->listQuestions($tNameSpace);
		
		$this->_setQuestions();
		$sql = "SELECT * FROM ".$this->table;
		$sql .= " WHERE (name_space, name_space_order)";
		$sql .= "  IN (SELECT name_space, MAX(name_space_order)";
		$sql .= "      FROM ".$this->table." GROUP BY name_space)";
		$sql .= " AND tutorial_name_space='".$tNameSpace."'";
		$sql .= " AND deleted='0'";
		$sql .= " AND question_order='".$qNum."'";
		$data = $this->getArray($sql);
		if($data != FALSE){
		 	$data[0]['total'] = count($questions);
			return $data[0];
		}
		return FALSE;
	}

/* ----- Functions for tbl_tutorial_answers ----- */

	/**
	* Method to add an answer record
	*
	* @access public
    * @param string $tNameSpace: The name space of the tutorial
    * @param string $qNameSpace: The name space of the question
    * @param string $answer: The student answer
    * @param string $aNameSpace: The answer name space if any
    * @return string|bool $answerId: The answer id |False on failure
    */
    public function addAnswer($tNameSpace, $qNameSpace, $answer, $aNameSpace = NULL)
    {
        $this->_setAnswers();
        if($aNameSpace == NULL){
        	$nameSpace = $this->_defineNameSpace();
        	$order = 1;
			$comment = $this->objLanguage->languageText('word_add');
        }else{
			$nameSpace = $aNameSpace;
			$order = $this->_getNameSpaceOrder($nameSpace);
			$comment = $this->objLanguage->languageText('word_edit');
		}
        
        $date = date('Y-m-d H:i:s');

        $fields = array();
        $fields['tutorial_name_space'] = $tNameSpace;
        $fields['question_name_space'] = $qNameSpace;
        $fields['name_space'] = $nameSpace;
        $fields['name_space_order'] = $order;
        $fields['answer'] = $answer;
        $fields['student_id'] = $this->userId;
        $fields['request_moderation'] = 0;
        $fields['moderation_complete'] = 0;
        $fields['deleted'] = 0;
        $fields['updated'] = $date;
        $answerId = $this->insert($fields);
        if($answerId != FALSE){         	
			$this->_addAuditTrail($this->table, $nameSpace, $date, $comment);
		}
        return $answerId;
    }
    
	/**
	* Method to update an answer record
	*
	* @access public
    * @param array $aData: The answer data
    * @return string|bool $answerId: The answer id |False on failure
    */
    public function updateAnswer($aData)
    {
        $this->_setAnswers();
		$nameSpace = $aData['name_space'];
		$aData['name_space_order'] = $this->_getNameSpaceOrder($nameSpace);
		$comment = $this->objLanguage->languageText('word_edit');
        
        $date = date('Y-m-d H:i;s');
		$aData['updated'] =$date;

        $answerId = $this->insert($aData);
        if($answerId != FALSE){         	
			$this->_addAuditTrail($this->table, $nameSpace, $date, $comment);
		}
        return $answerId;
    }
    
	/**
	* Method to get an answer for a question
	*
	* @access public
	* @param string $qNameSpace: The name space of the question to retrieve an answer for
	* @return array|bool $data: The answer data on success | FALSE on failure
	*/
	public function getQuestionAnswer($qNameSpace, $studentId = NULL)
	{
		if(!isset($studentId) || empty($studentId)){
			$studentId = $this->userId;
		}
		
		$this->_setAnswers();
		$sql = " SELECT * FROM ".$this->table;
		$sql .= " WHERE (name_space, name_space_order)";
		$sql .= "  IN (SELECT name_space, MAX(name_space_order)";
		$sql .= "      FROM ".$this->table." GROUP BY name_space)";
		$sql .= " AND question_name_space='".$qNameSpace."'";
		$sql .= " AND student_id='".$studentId."'";
		$sql .= " AND deleted='0'";
		$data = $this->getArray($sql);
		if($data != FALSE){
			return $data[0];
		}
		return FALSE;
	}
	
	/**
	* Method to get an answer
	*
	* @access public
	* @param string $aNameSpace: The name space of the answer
	* @return array|bool $data: The answer data on success | FALSE on failure
	*/
	public function getAnswer($aNameSpace)
	{
		$this->_setAnswers();
		$sql = " SELECT * FROM ".$this->table;
		$sql .= " WHERE (name_space, name_space_order)";
		$sql .= "  IN (SELECT name_space, MAX(name_space_order)";
		$sql .= "      FROM ".$this->table." GROUP BY name_space)";
		$sql .= " AND name_space='".$aNameSpace."'";
		$sql .= " AND deleted='0'";
		$data = $this->getArray($sql);
		if($data != FALSE){
			return $data[0];
		}
		return FALSE;
	}
	
	/**
	* Method to get a student to be marked
	*
	* @access public
	* @param string $qNameSpace: The name space of the question to retrieve an answer for
	* @param bool $checkOnly: TRUE if for checking only | FALSE if not
	* @return string|bool $data: The student id
	*/
	public function getStudentToMark($tNameSpace, $checkOnly = TRUE)
	{
		$this->_setAnswers();
		$sql = " SELECT * FROM ".$this->table;
		$sql .= " WHERE tutorial_name_space='".$tNameSpace."'";
		$sql .= " AND peer_id='".$this->userId."'";
		$sql .= " AND deleted='0'";
		$sql .= " ORDER BY name_space_order DESC";
		$data = $this->getArray($sql);
		if($data != FALSE){
			return $data[0]['student_id'];
		}
		
		$sql = " SELECT * FROM ".$this->table;
		$sql .= " WHERE tutorial_name_space='".$tNameSpace."'";
		$sql .= " AND student_id != '".$this->userId."'";
		$sql .= " AND deleted='0'";
		$sql .= " ORDER BY name_space_order DESC";
		$data = $this->getArray($sql);
		if($data != FALSE){
		 	$studentId = $data[0]['student_id'];
		 	$hasSubmitted = $this->hasSubmitted($tNameSpace, $studentId);
		 	if(!$hasSubmitted){
				return $this->getStudentToMark($tNameSpace, $checkOnly);
			}
		 	if(!$checkOnly){
			  	$aData = $this->listStudentAnswers($tNameSpace, $studentId);
		 		$this->setMarker($tNameSpace, $studentId);
		 		$this->setStudent($tNameSpace, $studentId);
		 		foreach($aData as $line){
		 		 	unset($line['id']);
		 		 	unset($line['puid']);
		 		 	$line['peer_id'] = $this->userId;
		 			$this->updateAnswer($line);
		 		}
		 	}
			return $studentId;
		}
		return FALSE;
	}
	
	/**
	* Method to check if a student has marked the tutorial
	*
	* @access public
	* @param string $tNameSpace: The name space of the tutorial
	* @param string $studentId: The id of the student
	* @return bool $hasMarked: TURE if the student has marked | FALSE if not
	*/
	public function checkHasMarked($tNameSpace, $studentId = NULL)
	{
		$studentId = isset($studentId) ? $studentId : $this->userId;
		
		$this->_setAnswers();
		$sql = "SELECT * FROM ".$this->table;
		$sql .= " WHERE tutorial_name_space='".$tNameSpace."'";
		$sql .= " AND peer_id='".$studentId."'";
		$sql .= " AND deleted='0'";
		$data = $this->getArray($sql);
		if($data != FALSE){
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	* Method to list all the answers for a student
	*
	* @access public
	* @param string $tNameSpace: The name space of the tutorial
	* @param string $studentId: The id of the student
	* @return array|bool $data: The students answers on success | FALSE on failure
	*/
	public function listStudentAnswers($tNameSpace, $studentId)
	{
		$this->_setAnswers();
		$sql = " SELECT * FROM ".$this->table;
		$sql .= " WHERE (name_space, name_space_order)";
		$sql .= "  IN (SELECT name_space, MAX(name_space_order)";
		$sql .= "      FROM ".$this->table." GROUP BY name_space)";
		$sql .= " AND tutorial_name_space='".$tNameSpace."'";
		$sql .= " AND student_id='".$studentId."'";
		$sql .= " AND deleted='0'";
		$data = $this->getArray($sql);
		if($data != FALSE){
			return $data;
		}
		return FALSE;
	}
	
	/**
	* Method to add peer marks and comments
	* 
	* @access public
	* @param string $aNameSpace: The name space of the answer
	* @param string $mark: The peer mark given
	* @param string $comment: The peer comment made
	* @return string|bool $aNameSpace: The name space of the answer on success | FALSE if not
	*/
	public function addPeerMark($aNameSpace, $mark, $comment)
	{
		$aData = $this->getAnswer($aNameSpace);
		unset($aData['id']);
		unset($aData['puid']);
		$aData['peer_id'] = $this->userId;
		$aData['peer_mark'] = $mark;
		$aData['peer_comment'] = $comment;
		$aData['moderator_id'] = $this->userId;
		$aData['moderator_mark'] = $mark;
		$aData['moderator_comment'] = $comment;
		return $this->updateAnswer($aData);
	}
	
	/**
	* Method to add moderator marks and comments
	* 
	* @access public
	* @param string $aNameSpace: The name space of the answer
	* @param string $mark: The peer mark given
	* @param string $comment: The peer comment made
	* @param bool $completed: TRUE if the moderator submitted | FALSE if not 
	* @return string|bool $aNameSpace: The name space of the answer on success | FALSE if not
	*/
	public function addModeratorMark($aNameSpace, $mark, $comment, $completed)
	{
		$aData = $this->getAnswer($aNameSpace);
		unset($aData['id']);
		unset($aData['puid']);
		$aData['moderator_id'] = $this->userId;
		$aData['moderator_mark'] = $mark;
		$aData['moderator_comment'] = $comment;
		if($completed){
			$aData['moderation_complete'] = 1;
		}
		return $this->updateAnswer($aData);
	}
	
	/**
	* Method to update the answer record with the moderation request
	*
	* @access public
	* @param string $aNameSpace: The name space of the answer
	* @param string $reason: The reason for moderation
	* @return string|bool $aNameSpace: The name space of the answer on success | FALSE if not
	*/
	public function submitModerationRequest($aNameSpace, $reason)
	{
		$aData = $this->getAnswer($aNameSpace);
		unset($aData['id']);
		unset($aData['puid']);
		$aData['request_moderation'] = 1;
		$aData['moderation_reason'] = $reason;
		return $this->updateAnswer($aData);
	}
	
	/**
	* Method to return an answer for moderation
	*
	* @access public
	* @param string $tNameSpace: The tutorial name space
	* @return array|bool $data: The answer data on success | FALSE on failure
	*/
	public function getAnswerToModerate($tNameSpace)
	{
		$this->_setAnswers();
		$sql = " SELECT * FROM ".$this->table;
		$sql .= " WHERE (name_space, name_space_order)";
		$sql .= "  IN (SELECT name_space, MAX(name_space_order)";
		$sql .= "      FROM ".$this->table." GROUP BY name_space)";
		$sql .= " AND tutorial_name_space='".$tNameSpace."'";
		$sql .= " AND request_moderation='1'";
		$sql .= " AND moderation_complete!='1'";
		$sql .= " AND deleted='0'";
		$data = $this->getArray($sql);
		if($data != FALSE){
		 	$data[0]['total'] = count($data);
			return $data[0];
		}
		return FALSE;
	}
	
	/**
	* Method to list all tutorial answers
	*
	* @access public
	* @param string $qNum: The question order number
	* @param string $aNum: The answer count from which to list
	* @return array|bool $data: The answer data on success | FALSE on failure
	*/
	public function listAnswers($qNameSpace, $aNum)
	{
		$this->_setAnswers();
		$sql = "SELECT * FROM ".$this->table;
		$sql .= " WHERE (name_space, name_space_order)";
		$sql .= "  IN (SELECT name_space, MAX(name_space_order)";
		$sql .= "      FROM ".$this->table." GROUP BY name_space)";
		$sql .= " AND question_name_space='".$qNameSpace."'";
		$sql .= " AND deleted='0'";
 		$sql .= " ORDER BY student_id ASC";
 		$sql .= " LIMIT ".($aNum - 1).", 20";
		$data = $this->getArray($sql);
		if($data != FALSE){
		 	$count = $this->countAnswers($qNameSpace);
		 	$data[0]['total'] = $count;
			return $data;
		}
		return FALSE;
	}
	
	/**
	* Method to count all tutorial answers
	*
	* @access public
	* @param string $qNameSpace: The question name space
	* @return array|bool $data: The answer data on success | FALSE on failure
	*/
	public function countAnswers($qNameSpace)
	{
		$this->_setAnswers();
		$sql = "SELECT * FROM ".$this->table;
		$sql .= " WHERE (name_space, name_space_order)";
		$sql .= "  IN (SELECT name_space, MAX(name_space_order)";
		$sql .= "      FROM ".$this->table." GROUP BY name_space)";
		$sql .= " AND question_name_space='".$qNameSpace."'";
		$sql .= " AND deleted='0'";
		$data = $this->getArray($sql);
		if($data != FALSE){
			return count($data);
		}
		return 0;
	}
/* ----- Functions for tbl_tutorial_results ----- */

	/**
	* Method to check if the user has a result record
	*
	* @access public
    * @param string $tNameSpace: The name space of the tutorial
    * @return string|bool $resultId: The result id |False on failure
    */
    public function checkResult($tNameSpace)
    {
		$this->_setResults();
		$sql = "SELECT * FROM ".$this->table;
		$sql .= " WHERE tutorial_name_space='".$tNameSpace."'";
		$sql .= " AND student_id='".$this->userId."'";
		$sql .= " AND deleted='0'";
		$data = $this->getArray($sql);
		if($data == FALSE){
			$this->addResult($tNameSpace);
		}
		return TRUE;
    }
    
	/**
	* Method to add an results record
	*
	* @access public
    * @param string $tNameSpace: The name space of the tutorial
    * @return string|bool $resultId: The result id |False on failure
    */
    public function addResult($tNameSpace)
    {
        $this->_setResults();
        $rNameSpace = $this->_defineNameSpace();
        
        $date = date('Y-m-d H:i:s');

        $fields = array();
        $fields['tutorial_name_space'] = $tNameSpace;
        $fields['name_space'] = $rNameSpace;
        $fields['name_space_order'] = 1;
        $fields['student_id'] = $this->userId;
        $fields['completed'] = 0;
        $fields['marked'] = 0;
        $fields['mark_obtained'] = 0;
        $fields['deleted'] = 0;
        $fields['updated'] = $date;
        $resultId = $this->insert($fields);
        if($resultId != FALSE){
         	$comment = $this->objLanguage->languageText('word_add');
			$this->_addAuditTrail($this->table, $rNameSpace, $date, $comment);
		}
        return $resultId;
    }
    
    /**
    * Method to set a student as having submitted a tutorial
    *
    * @access public
    * @param string $tNameSpace: The tutorial name space
    * @return string|bool $resultId: The result id |False on failure
    */
    public function setAnswered($tNameSpace)
    {
		$data = $this->getResult($tNameSpace);
		if($data != FALSE){
		 	$this->_setResults();
			unset($data['id']);
			unset($data['puid']);
			$order = $this->_getNameSpaceOrder($data['name_space']);
			$data['name_space_order'] = $order;
			$data['completed'] = 1;
      		$date = date('Y-m-d H:i:s');
      		$data['updated'] = $date;
	        $resultId = $this->insert($data);
    	    if($resultId != FALSE){
        	 	$comment = $this->objLanguage->languageText('word_edit');
				$this->_addAuditTrail($this->table, $data['name_space'], $date, $comment);
			}
        	return $resultId;
		}
		return FALSE;
	}
    
    /**
    * Method to set a student as having marked a tutorial
    *
    * @access public
    * @param string $tNameSpace: The tutorial name space
    * @return string|bool $resultId: The result id |False on failure
    */
    public function setMarked($tNameSpace)
    {
		$data = $this->getResult($tNameSpace);
		if($data != FALSE){
		 	$this->_setResults();
			unset($data['id']);
			unset($data['puid']);
			$order = $this->_getNameSpaceOrder($data['name_space']);
			$data['name_space_order'] = $order;
			$data['marked'] = 1;
      		$date = date('Y-m-d H:i:s');
      		$data['updated'] = $date;
	        $resultId = $this->insert($data);
    	    if($resultId != FALSE){
        	 	$comment = $this->objLanguage->languageText('word_edit');
				$this->_addAuditTrail($this->table, $data['name_space'], $date, $comment);
			}
        	return $resultId;
		}
		return FALSE;
	}
    
    /**
    * Method to set the id of the student who is being marked
    *
    * @access public
    * @param string $tNameSpace: The tutorial name space
    * @params string $studentId: The id of the student who is being marked
    * @return string|bool $resultId: The result id |False on failure
    */
    public function setStudent($tNameSpace, $studentId)
    {
		$data = $this->getResult($tNameSpace);
		if($data != FALSE){
		 	$this->_setResults();
			unset($data['id']);
			unset($data['puid']);
			$order = $this->_getNameSpaceOrder($data['name_space']);
			$data['name_space_order'] = $order;
			$data['student_marked'] = $studentId;
      		$date = date('Y-m-d H:i:s');
      		$data['updated'] = $date;
	        $resultId = $this->insert($data);
    	    if($resultId != FALSE){
        	 	$comment = $this->objLanguage->languageText('word_edit');
				$this->_addAuditTrail($this->table, $data['name_space'], $date, $comment);
			}
        	return $resultId;
		}
		return FALSE;
	}
    
	/**
	* Method to get a students result
	*
	* @access public
	* @param string $tNameSpace: The name space of the tutorial result retrieve
	* @param string $studentId: The id of the student
	* @return array|bool $data: The result data on success | FALSE on failure
	*/
	public function getResult($tNameSpace, $studentId = NULL)
	{
		$studentId = isset($studentId) ? $studentId : $this->userId;
		
		$this->_setResults();
		$sql = " SELECT * FROM ".$this->table;
		$sql .= " WHERE (name_space, name_space_order)";
		$sql .= "  IN (SELECT name_space, MAX(name_space_order)";
		$sql .= "      FROM ".$this->table." GROUP BY name_space)";
		$sql .= " AND tutorial_name_space='".$tNameSpace."'";
		$sql .= " AND student_id='".$studentId."'";
		$sql .= " AND deleted='0'";
		$data = $this->getArray($sql);
		if($data != FALSE){
			return $data[0];
		}
		return FALSE;
	}
	
	/**
	* Method to check if a student has submitted a tutorial
	*
	* @access public
	* @param string $tNameSpace: The name space of the tutorial result retrieve
	* @return bool $data: TRUE if yes | FALSE if no
	*/
	public function hasSubmitted($tNameSpace, $studentId = NULL)
	{
		$studentId = isset($studentId) ? $studentId : $this->userId;
		$this->_setResults();
		$sql = " SELECT * FROM ".$this->table;
		$sql .= " WHERE tutorial_name_space='".$tNameSpace."'";
		$sql .= " AND student_id='".$studentId."'";
		$sql .= " AND deleted='0'";
		$sql .= " ORDER BY name_space_order DESC";
		$data = $this->getArray($sql);
		if($data != FALSE){
		 	if($data[0]['completed'] == 1){
				return TRUE;
			}
		}
		return FALSE;
	}
	
	/**
	* Method to check if a student has marked a tutorial
	*
	* @access public
	* @param string $tNameSpace: The name space of the tutorial result retrieve
	* @return bool $data: TRUE if yes | FALSE if no
	*/
	public function hasMarked($tNameSpace, $studentId = NULL)
	{
		$studentId = isset($studentId) ? $studentId : $this->userId;
		$this->_setResults();
		$sql = " SELECT * FROM ".$this->table;
		$sql .= " WHERE tutorial_name_space='".$tNameSpace."'";
		$sql .= " AND student_id='".$studentId."'";
		$sql .= " AND deleted='0'";
		$sql .= " ORDER BY name_space_order DESC";
		$data = $this->getArray($sql);
		if($data != FALSE){
		 	if($data[0]['marked'] == 1){
				return TRUE;
			}
		}
		return FALSE;
	}
	
	/**
	* Method to check if a student has submitted a tutorial
	*
	* @access public
	* @param string $tNameSpace: The name space of the tutorial result retrieve
	* @param string $studentId: The id of the student to update
	* @return string|bool $data: TRUE if yes | FALSE if no
	*/
	public function updateResults($tNameSpace, $moderator = FALSE, $studentId = NULL)
	{
		if(!$moderator){
			$studentId = $this->getStudentToMark($tNameSpace);
		}
		$aData = $this->listStudentAnswers($tNameSpace, $studentId);
		$total = 0;
		foreach($aData as $line){
			$total = $total + $line['moderator_mark'];
		}
		$rData = $this->getResult($tNameSpace, $studentId);
		$rNameSpace = $rData['name_space'];
		$order = $this->_getNameSpaceOrder($rNameSpace);
		$date = date('Y-m-d H:i:s');
		
		unset($rData['id']);
		unset($rData['puid']);
		$rData['mark_obtained'] = $total;
		$rData['name_space_order'] = $order;
	   	$resultId = $this->insert($rData);
    	if($resultId != FALSE){
        	$comment = $this->objLanguage->languageText('word_edit');
			$this->_addAuditTrail($this->table, $rNameSpace, $date, $comment);
		}
        return $resultId;
	}
	
	/**
	* Method to set the marker on the results record
	*
	* @access public
	* @param string $tNameSpace: The tutorial name space
	* @param string $studentId: The id of the student
	* @param bool $moderator: TRUE if the current user is a lecturer | FALSE if not
	* @return string|bool $resultId: The result id on success | FALSE if not
	*/
	public function setMarker($tNameSpace, $studentId, $moderator = FALSE)
	{
		$data = $this->getResult($tNameSpace, $studentId);
		if($data != FALSE){
			unset($data['id']);
			unset($data['puid']);
      		$date = date('Y-m-d H:i:s');
      		$data['updated'] = $date;
			$order = $this->_getNameSpaceOrder($data['name_space']);
			$data['name_space_order'] = $order;
			if($moderator){
				$data['moderator_id'] = $this->userId;
			}else{
				$data['peer_id'] = $this->userId;
			}

		 	$this->_setResults();
	        $resultId = $this->insert($data);
    	    if($resultId != FALSE){
        	 	$comment = $this->objLanguage->languageText('word_edit');
				$this->_addAuditTrail($this->table, $data['name_space'], $date, $comment);
			}
        	return $resultId;
		
		}
		return FALSE;
	}

	/** 
	* Methid to delete a students results
	*
	* @access public
	* @param string $tNameSpace: The name space	of the tutorial
	* @param string $studentId: The id of the student
	* @return string|bool $id: The id on success | FALSE on failure
	*/
	public function deleteResults($tNameSpace, $studentId)
	{
		$rData = $this->getResult($tNameSpace, $studentId);
		if($rData != FALSE){
			$order = $this->_getNameSpaceOrder($rData['name_space']);
			$date = date('Y-m-d H:i:s');
			unset($rData['id']);
			unset($rData['puit']);
			$rData['name_space_order'] = $order;
			$rData['deleted'] = 1;
			$rData['updated'] = $date;
		 	$this->_setResults();
	        $id = $this->insert($data);
    	    if($id != FALSE){
        	 	$comment = $this->objLanguage->languageText('word_delete');
				$this->_addAuditTrail($this->table, $data['name_space'], $date, $comment);
			}
        	return $id;
		}
		return FALSE;
	}

	
/* ----- Functions for tbl_tutorial_instructions ----- */

	/**
	* MEthod to add instructions for the tutorial
	* 
	* @access public
	* @param string $instructions: The instructions for the tutorial
	* @return string|bool $id: The instruction id on success | FALSE on failure
	*/
	public function addInstructions($instructions)
	{
		$this->_setInstructions();
		$nameSpace = $this->_defineNameSpace();		
		$date = date('Y-m-d H:i:s');
		
		$fields = array();
		$fields['contextcode'] = $this->contextCode;
		$fields['name_space'] = $nameSpace;
		$fields['name_space_order'] = 1;
		$fields['instructions'] = $instructions;
		$fields['deleted'] = 0;
		$fields['updated'] = $date;
		$id = $this->insert($fields);
		if($id != FALSE){
        	$comment = $this->objLanguage->languageText('word_add');
			$this->_addAuditTrail($this->table, $nameSpace, $date, $comment);
		}
	}
	
	/**
	* Method to get the instructiosn for a tutorial
	*
	* @access public
	* @return array|bool $data: The instructions data on success | FALSE on failure
	*/
	public function getInstructions()
	{
		$this->_setInstructions();
		$sql = " SELECT * FROM ".$this->table;
		$sql .= " WHERE (name_space, name_space_order)";
		$sql .= "  IN (SELECT name_space, MAX(name_space_order)";
		$sql .= "      FROM ".$this->table." GROUP BY name_space)";
		$sql .= " AND contextcode='".$this->contextCode."'";
		$sql .= " AND deleted='0'";
		$data = $this->getArray($sql);
		if($data != FALSE){
			return $data[0];
		}
		return FALSE;
	}
	
	/**
	* Method to edit the instructions
	*
	* @access public
	* @param string $instructions: The instructions for the tutorial
	* @return string|bool $id: The instruction id on success | FALSE on failure
	*/
	public function updateInstructions($instructions)
	{
		$iData = $this->getInstructions();
		if($iData != FALSE){
			$order = $this->_getNameSpaceOrder($iData['name_space']);
			$date = date('Y-m-d H:i:s');
			unset($iData['id']);
			unset($iData['puid']);
			$iData['name_space_order'] = $order;
			$iData['instructions'] = $instructions;
			$iData['updated'] = $date;

			$this->_setInstructions();
			$id = $this->insert($iData);
			if($id != FALSE){
        		$comment = $this->objLanguage->languageText('word_edit');
				$this->_addAuditTrail($this->table, $iData['name_space'], $date, $comment);
			}
			return $id;
		}
		return FALSE;
	}
	
	/**
	* Method to delete instructions
	*
	* @access public
	* @return string|bool $id: The instruction id on success | FALSE on failure
	*/
	public function deleteInstructions()
	{
		$iData = $this->getInstructions();
		if($iData != FALSE){
			$order = $this->_getNameSpaceOrder($iData['name_space']);
			$date = date('Y-m-d H:i:s');
			unset($iData['id']);
			unset($iData['puid']);
			$iData['name_space_order'] = $order;
			$iData['deleted'] = 1;
			$iData['updated'] = $date;

			$this->_setInstructions();
			$id = $this->insert($iData);
			if($id != FALSE){
        		$comment = $this->objLanguage->languageText('word_delete');
				$this->_addAuditTrail($this->table, $iData['name_space'], $date, $comment);
			}
			return $id;
		}
		return FALSE;
	}
	
/* ----- Functions for tbl_tutorial_late ----- */

	/**
	* Method to get the late submissions for a tutorial
	*
	* @access public
	* @param string $tNameSpace: The name spacel of the tutorial
	* @param string $studentId: The id of the student
	* @return array|bool $data: The late submission data on success | FALSE on failure
	*/
	public function getLateSubmissions($tNameSpace, $studentId)
	{
		$this->_setLate();
		$sql = " SELECT * FROM ".$this->table;
		$sql .= " WHERE (name_space, name_space_order)";
		$sql .= "  IN (SELECT name_space, MAX(name_space_order)";
		$sql .= "      FROM ".$this->table." GROUP BY name_space)";
		$sql .= " AND tutorial_name_space='".$tNameSpace."'";
		$sql .= " AND student_id='".$studentId."'";
		$sql .= " AND deleted='0'";
		$data = $this->getArray($sql);
		if($data != FALSE){
			return $data[0];
		}
		return FALSE;
	}
	
	/**
	* Method to add late submissions
	* 
	* @access public
	* @param string $tNameSpace: The name space of the tutorial
	* @param string $studentId: The id of the student
	* @param string $answer: The answer closing date
	* @param string $mark: The marking closing date
	* @param string $moderate: The moderating closing date
	* @return string|bool $id: The id on success | FALSE on failure
	*/
	public function addLateSubmission($tNameSpace, $studentId, $answer, $mark, $moderate)
	{
		$lData = $this->getLateSubmissions($tNameSpace, $studentId);
		$date = date('Y-m-d H:i:s');
		if($lData != FALSE){
			$order = $this->_getNameSpaceOrder($lData['name_space']);
			unset($lData['id']);
			unset($lData['puid']);
			$lData['name_space_order'] = $order;
			$lData['answer_close_date'] = $answer;
			$lData['marking_close_date'] = $mark;
			$lData['moderating_close_date'] = $moderate;
			$comment = $this->objLanguage->languageText('word_edit');
		}else{
		 	$nameSpace = $this->_defineNameSpace();
			$lData = array();
			$lData['tutorial_name_space'] = $tNameSpace;
			$lData['name_space'] = $nameSpace;			
			$lData['name_space_order'] = 1;
			$lData['answer_close_date'] = $answer;
			$lData['marking_close_date'] = $mark;
			$lData['moderating_close_date'] = $moderate;
			$comment = $this->objLanguage->languageText('word_add');
		}
		$lData['student_id'] = $studentId;
		$lData['updated'] = $date;
		$lData['deleted'] = 0;
		$this->_setLate();
		
		$id = $this->insert($lData);
		if($id != FALSE){
			$this->_addAuditTrail($this->table, $lData['name_space'], $date, $comment);
		}
		return $id;
	}
	
	/**
	* Method to delete a late submission
	*
	* @access public
	* @param string $tNameSpace: The tutorial name space
	* @param string $studentId: The id of the student
	* @return string|bool $id: The id on success | FALSE on failure
	*/
	public function deleteLateSubmissions($tNameSpace, $studentId)
	{
		$lData = $this->getLateSubmissions($tNameSpace, $studentId);
		if($lData != FALSE){
		 	$date = date('Y-m-d H:i:s');
			$order = $this->_getNameSpaceOrder($lData['name_space']);
			unset($lData['id']);
			unset($lData['puid']);
			$lData['name_space_order'] = $order;
			$lData['deleted'] = 1;
			$lData['updated'] = $date;
			$comment = $this->objLanguage->languageText('word_delete');
			$id = $this->insert($lData);
			if($id != FALSE){
				$this->_addAuditTrail($this->table, $lData['name_space'], $date, $comment);
			}
			return $id;
		}
		return FALSE;
	}
	
/* ----- Functions for tbl_tutorial_audit ----- */

    /**
    * Method to add a tutorial audit trail record
    * @access private
    * @param string $table: The table that was affected
    * @param string $nameSpace: The name space of the records on the affected table
    * @param string $recordDate: The date the record was added
    * @param string $comment: A comment on the audit record
    * @return string|bool $auditId: The audit trail id |False on failure
    */
    private function _addAuditTrail($table, $nameSpace, $recordDate, $comment)
    {
        $this->_setAudit();
        $auditDate = date('Y-m-d H:i:s');
        $fields = array();
        $fields['table_affected'] = $table;
        $fields['name_space'] = $nameSpace;
        $fields['user_id'] = $this->userId;
        $fields['date_record_updated'] = $recordDate;
        $fields['audit_comment'] = $comment;
        $fields['updated'] = $auditDate;
        $auditId = $this->insert($fields);
        return $auditId;
    }
}
?>