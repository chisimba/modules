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
		
		//check if the modules related are registered	
        $this->test = FALSE;
        $this->essay = FALSE;
        $this->ws = FALSE;
        $this->rubric = FALSE;

		//database objects , provides access to the related tables.
        $this->dbAssignment = $this->getObject('dbassignment','assignment');
        $this->dbSubmit = $this->getObject('dbassignmentsubmit','assignment');

		//functions for actions in the controller
		$this->Assignment = $this->getObject('functions_assignment','assignment');

        if($this->ws){
            $this->dbWorksheet = $this->getObject('dbworksheet','worksheet');
            $this->dbWorksheetResults = $this->getObject('dbworksheetresults','worksheet');
        }

        $this->objDate = $this->getObject('dateandtime','utilities');
        $this->objLanguage = $this->getObject('language','language');
        $this->objUser = $this->getObject('user','security');
        $this->objContext = $this->getObject('dbcontext','context');
		$this->dbassignmentsubmit = $this->getObject('dbassignmentsubmit');
        
		// Get an instance of the filestore object and change the tables to essay specific tables
        $this->objFile= $this->getObject('upload','filemanager');
		$objSelectFile = $this->newObject('selectfile','filemanager');
        $this->objFileRegister = $this->getObject('registerfileusage', 'filemanager');
		$this->objhtmlcleaner= $this->getObject('htmlcleaner', 'utilities');
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
                $data = $this->Assignment->viewAssign($var);
				$this->setVarByRef('data', $data);
       		 	return 'assignment_view_tpl.php';


           // online submit insert/submitted assignment in the database
            case 'onlinesubmit':
                $postSave = $this->getParam('save', '');
                if($postSave == $this->objLanguage->languageText('word_exit')){

                    return $this->nextAction('');
                }

                return $this->Assignment->submitAssign();
            // insert the submitted assignment in the database
            case 'submit':
                $postSave = $this->getParam('save', '');
                if($postSave == $this->objLanguage->languageText('word_exit')){
                    return $this->nextAction('');
                }
                return $this->Assignment->submitAssign();


            // change the editor for the assignment
            case 'changeeditor':
                $id = $this->getParam('id', '');
                $editor = $this->getParam('editor');
                $this->Assignment->saveAssign();
                return $this->nextAction('view', array('id'=>$id, 'editor'=>$editor, 'var'=>TRUE));

            // download an assignment
            case 'download':
                $this->setPageTemplate('download_page_tpl.php');
		   return 'download_tpl.php';

            case 'showcomment':
                $comment = $this->Assignment->showComment();
        		$this->setVarByRef('data',$comment);
        		return 'assignment_comment_tpl.php';
                
        	case 'upload':
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
    				$fileId = $this->getParam('file');
                    // upload file to database
					
                    	// save file id and submit date to database
                    	$fields=array(
                    		'userid'=>$studentid,
                    		'assignmentid'=>$id,
                    		'updated'=>date('Y-m-d H:i:s'),
                    		'studentfileid'=>$fileId,
                    		'datesubmitted'=>date('Y-m-d H:i:s')
                        );
                $this->dbassignmentsubmit->addSubmit($fields);
				$this->objFileRegister->registerUse($fileId, 'assignment', 'tbl_assignment_submit', $id, 'studentfileid', $this->contextcode, '', TRUE);	
                // display success message
                $msg = $this->objLanguage->languageText('mod_assignment_confirmupload','assignment');
                $this->setVarByRef('msg',$msg);
}
                $mixed_arr = $this->Assignment->studentHome();
		        $this->setVarByRef('essayData', $mixed_arr[0]);
		        $this->setVarByRef('wsData', $mixed_arr[1]);
		        $this->setVarByRef('testData', $mixed_arr[2]);
		        $this->setVarByRef('assignData', $mixed_arr[3]);
		        return 'assignment_student_tpl.php';

            break;

            default:
               	$mixed_arr = $this->Assignment->studentHome();

		        $this->setVarByRef('essayData', $mixed_arr[0]);
		        $this->setVarByRef('wsData', $mixed_arr[1]);
		        $this->setVarByRef('testData', $mixed_arr[2]);
		        $this->setVarByRef('assignData', $mixed_arr[3]);
		        return 'assignment_student_tpl.php';

        }//end of switch 
    }//end of dispatch


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

php?>
