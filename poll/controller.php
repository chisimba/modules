<?php
/**
* poll class extends controller
* @package poll
* @filesource
*/

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* Controller class for poll module
* @author Megan Watson
* @copyright (c) 2007 UWC
* @version 0.1
*/

class poll extends controller
{
    /**
    * @var string $contextCode The current context .. to do.
    * @access private
    */
    private $contextCode = 'root';
    
    /**
    * Method to construct the class.
    */
    public function init()
    {
        try{
            $this->pollTools = $this->getObject('polltools', 'poll');
            $this->dbPoll = $this->getObject('dbpoll', 'poll');
            $this->dbQuestions = $this->getObject('dbquestions', 'poll');
            $this->dbResponse = $this->getObject('dbresponse', 'poll');
            
            $this->objUser = $this->getObject('user', 'security');
            
            //Get the activity logger class and log this module call
            $objLog = $this->getObject('logactivity', 'logger');
            $objLog->log();
            
            $this->setPollId();
        }catch(Exception $e){
            throw customException($e->message());
            exit();
        }
    }

    /**
    * Standard dispatch function
    *
    * @access public
    * @param string $action The action to be performed
    * @return string Template to be displayed
    */
    public function dispatch($action)
    {
        switch($action){
            case 'showadd':
                $display = $this->pollTools->showAdd('');
                $this->setVarByRef('display', $display);
                return 'home_tpl.php';
                
            case 'showedit':
                $id= $this->getParam('id');
                $data = $this->dbQuestions->getQuestion($id);
                $display = $this->pollTools->showAdd($data);
                $this->setVarByRef('display', $display);
                return 'home_tpl.php';
                
            case 'deletequestion':
                $id= $this->getParam('id');
                $this->dbQuestions->deleteQuestion($id);
                return $this->nextAction('');
                
            case 'savequestion':
                $pollId = $this->getSession('pollId');
                $id = $this->getParam('id');
                $this->dbQuestions->saveQuestion($pollId, $id);
                return $this->nextAction('');
                
            case 'saveresponse':
                $this->dbResponse->addResponse();
                break;
            
            case 'saveconfig':
                $pollId = $this->getSession('pollId');
                $this->dbPoll->saveConfig($pollId);
                return $this->nextAction('');
                break;
            
            default:
                $pollId = $this->getSession('pollId');
                $pollData = $this->dbQuestions->getQuestions($pollId);
                $display = $this->pollTools->showPolls($pollData);
                $this->setVarByRef('display', $display);
                return 'home_tpl.php';
        }
    }
    
    /**
    * Method sets the poll id in session - checks if the user is in a different context to reset the id
    *
    * @access private
    */
    private function setPollId()
    {
        $context = $this->getSession('poll_context');
        if(empty($context) || ($context != $this->contextCode)){
            $pollId = $this->dbPoll->getPoll($this->contextCode);
            
            $this->setSession('pollId', $pollId);
            $this->setSession('poll_context', $this->contextCode);
        }
    }
    
    /**
    * Method to allow user to view the poll without being logged in
    *
    * @access public
    */
    public function requiresLogin()
    {
        return FALSE;
    }
} // end of controller class
?>