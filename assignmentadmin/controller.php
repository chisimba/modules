<?php
/**
* Assignment admin class extends controller
* @author Megan Watson
* @copyright (c) 2004 UWC
* @package assignmentadmin
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
 * @package assignmentadmin
 * @version 1
 */

class assignmentadmin extends controller
{

    public $contextCode;
    /**
    * Initialise objects used in the module.
    */
    public function init()
    {
        // Check if the module is registered and redirect if not.
        // Check if the assignment module is registered and can be linked to.
        /*$this->objModules = $this->newObject('modulesadmin','modulelist');
        if(!$this->objModules->checkIfRegistered('Assignment Management', 'assignmentadmin')){
            return $this->nextAction('notregistered',array('modname'=>'assignment'), 'redirect');
        }
	*/
        $this->test = FALSE;
        /*if($this->objModules->checkIfRegistered('Online Tests','testadmin')){
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
        $this->dbAssignment = $this->getObject('dbassignment', 'assignment');
        $this->dbSubmit = $this->getObject('dbassignmentsubmit', 'assignment');

        if($this->essay){
            $this->dbEssayTopics = $this->getObject('dbessay_topics','essay');
            $this->dbEssays = $this->getObject('dbessays','essay');
            $this->dbEssayBook = $this->getObject('dbessay_book','essay');
        }
        if($this->ws){
            $this->dbWorksheet = $this->getObject('dbworksheet','worksheet');
            $this->dbWorksheetResults = $this->getObject('dbworksheetresults','worksheet');
        }
        if($this->test){
            $this->dbTestAdmin = $this->getObject('dbtestadmin','testadmin');
            $this->dbTestResults = $this->getObject('dbresults','testadmin');
        }


		//cunstructors for needed functions and attributes.
		$this->objAssignmentAdmin = $this->getObject('functions_assignmentadmin','assignmentadmin');
        $this->objDate = $this->getObject('dateandtime','utilities');
        $this->objLanguage = $this->getObject('language','language');
        $this->objUser = $this->getObject('user','security');
        //$this->objGroups = $this->getObject('groupAdminModel','groupadmin');
        $this->objContext = $this->getObject('dbcontext','context');
        $this->objCleaner = $this->getObject('htmlcleaner', 'utilities');
		$fileUploader = $this->getObject('fileuploader', 'files');
        // Get an instance of the filestore object and change the tables to assignment specific tables
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
            // display template to add or edit an assignment
            case 'edit':
		
            case 'add':
                $data = $this->objAssignmentAdmin->addAssign($action);
				$this->setVarByRef('data', $data);
        		return 'assignment_add_tpl.php';

            // delete an assignment
            case 'delete':
                $this->objAssignmentAdmin->deleteAssign();
				return $this->nextAction('viewbyletter');
            // save new or edited assignment
            case 'saveassign':
                $postSave = $this->getParam('save');
                if($postSave == $this->objLanguage->languageText('word_cancel')){
                    return $this->nextAction('');
                }
                $this->objAssignmentAdmin->saveAssign();
                $message = $this->objLanguage->languageText('mod_assignmentadmin_saveassignconfirm','assignmentadmin');
                $this->setSession('confirm', $message);
                return $this->nextAction('viewbyletter', array('confirm'=>'yes'));

            // change the editor for the question
            case 'changeeditor':
                $editor = $this->getParam('editor');
                $id = $this->objAssignmentAdmin->saveAssign();
                return $this->nextAction('edit', array('id'=>$id, 'editor'=>$editor));

            // view the assignment
            case 'view':
                	 $data = $this->objAssignmentAdmin->viewAssign();
					 $this->setVarByRef('data', $data);
        		return 'assignment_view_tpl.php';

            // mark the submitted assignments
            case 'mark':
                $postSave = $this->getParam('save');
				 if($postSave){
                    if($postSave == $this->objLanguage->languageText('word_exit')){
                        return $this->nextAction('');
                    }
                }
                $data_assign = $this->objAssignmentAdmin->listAssign();
				$data = $data_assign[0];
				$assign = $data_assign[1];
				$this->setVarByRef('assign', $assign[0]);
				$this->setVarByRef('data', $data);
			   	return 'assignment_list_tpl.php';
           

            //submit the assignment with admin logged in
			case 'submitupload':
                $data = $this->objAssignmentAdmin->markAssign();
				$confirm = $this->getParam('confirm');
				if(!empty($confirm)){
				    $this->setVarByRef('msg',$confirm);
				}
				$this->setVarByRef('data',$data);
				$this->setVar('online',FALSE);
				return 'assignment_mark_tpl.php';

            // display template to mark an uploaded assignment
            
			case 'upload':
                $data = $this->objAssignmentAdmin->markAssign();
				$confirm = $this->getParam('confirm');
				if(!empty($confirm)){
				    $this->setVarByRef('msg',$confirm);
				}
				$this->setVarByRef('data',$data);
				$this->setVar('online',FALSE);
				return 'assignment_mark_tpl.php';

			// display template to mark an online assignment
            case 'mark_online':
                $data =  $this->objAssignmentAdmin->markOnlineAssign();
				$this->setVarByRef('data',$data);
				$this->setVar('online',TRUE);
				return 'assignment_mark_tpl.php';
            // display template to mark an online assignment
            case 'online':
                $data =  $this->objAssignmentAdmin->markOnlineAssign();
				$this->setVarByRef('data',$data);
				$this->setVar('online',TRUE);
				return 'assignment_mark_tpl.php';

          
            case 'uploadsubmit':
				$postSave = $this->getParam('save');
                if($postSave == $this->objLanguage->languageText('word_exit')){
                    return $this->nextAction('mark',array('id'=>$this->getParam('id')));
                }
                return $this->objAssignmentAdmin->saveMark();

            // Display search results
            case 'viewbyletter':
                $data_title =  $this->objAssignmentAdmin->showResults();
	
				$data = $data_title[0];
				$title = $data_title[1];
				$this->setVarByRef('data', $data);
				$this->setVarByRef('title', $title);

				// Check for a confirmation message
				$confirm = $this->getParam('confirm');

				if($confirm == 'yes'){
				    $message = $this->getSession('confirm');
				    $this->unsetSession('confirm');
				    $this->setVarByRef('message', $message);
				}

				return 'assignment_tpl.php';


            default:
	        return $this->objAssignmentAdmin->assignHome();
        }
    }


}// end class assignment
php?>
