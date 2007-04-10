<?php
/**
* dbresponse class extends dbtable
* @package poll
* @filesource
*/

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* dbresponse class
* @author Megan Watson
* @copyright (c) 2007 UWC
* @version 0.1
*/

class dbresponse extends dbtable
{    
    /**
    * Constructor method
    */
    public function init()
    {
        try{
            parent::init('tbl_poll_responses');
            $this->table = 'tbl_poll_responses';
            
            $this->objUser = $this->getObject('user', 'security');
            $this->objLanguage = $this->getObject('language', 'language');
            
            $this->userId = $this->objUser->userId();
        } catch (Exception $e) {
            throw customException($e->getMessage());
            exit();
        }
    }
    
    /**
    * Method to save a response to a poll question
    *
    * @access public
    * @return void
    */
    public function addResponse()
    {
        $type = $this->getParam('questionType');
        
        $fields = array();
        $fields['question_id'] = $this->getParam('questionId');
        $fields['date_created'] = $this->now();
        
        if($type == 'bool' || $type == 'yes'){
            $fields['answer_id'] = $this->getParam('answer');
        }
        
        $id = $this->insert($fields);
        return $id;
    }
}
?>