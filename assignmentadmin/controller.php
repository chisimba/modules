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
        $this->objDate = $this->getObject('dateandtime','utilities');
        $this->objLanguage = $this->getObject('language','language');
        $this->objUser = $this->getObject('user','security');
        //$this->objGroups = $this->getObject('groupAdminModel','groupadmin');
        $this->objContext = $this->getObject('dbcontext','context');
        $this->objCleaner = $this->getObject('htmlcleaner', 'utilities');

        // Get an instance of the filestore object and change the tables to assignment specific tables
        /*$this->objFile= $this->getObject('fileupload','filestore');
        $this->objFile->changeTables('tbl_assignment_filestore','tbl_assignment_blob');
	*/
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
                return $this->addAssign($action);

            // delete an assignment
            case 'delete':
                return $this->deleteAssign();

            // save new or edited assignment
            case 'saveassign':
                $postSave = $this->getParam('save');
                if($postSave == $this->objLanguage->languageText('word_cancel')){
                    return $this->nextAction('');
                }
                $this->saveAssign();
                $message = $this->objLanguage->languageText('mod_assignmentadmin_saveassignconfirm','assignmentadmin');
                $this->setSession('confirm', $message);
                return $this->nextAction('viewbyletter', array('confirm'=>'yes'));

            // change the editor for the question
            case 'changeeditor':
                $editor = $this->getParam('editor');
                $id = $this->saveAssign();
                return $this->nextAction('edit', array('id'=>$id, 'editor'=>$editor));

            // view the assignment
            case 'view':
                return $this->viewAssign();

            // mark the submitted assignments
            case 'mark':
                $postSave = $this->getParam('save');
                if($postSave){
                    if($postSave == $this->objLanguage->languageText('word_exit')){
                        return $this->nextAction('');
                    }
                }
                return $this->listAssign();

            // download an assignment
            case 'download':
                $this->setPageTemplate('download_page_tpl.php');
                return 'download_tpl.php';

            // display template to mark an uploaded assignment
            case 'upload':
                return $this->markAssign();

            // display template to mark an online assignment
            case 'online':
                return $this->markOnlineAssign();

            case 'uploadsubmit':
                $postSave = $this->getParam('save');
                if($postSave == $this->objLanguage->languageText('word_exit')){
                    return $this->nextAction('mark',array('id'=>$this->getParam('id')));
                }
                return $this->saveMark();

            // Display search results
            case 'viewbyletter':
                return $this->showResults();

            default:


                return $this->assignHome();
        }
    }

    /**
    * Method to display the lecturers home page for assignments.
    * @return The template for the lecturers home page.
    */
    public function assignHome()
    {
        return 'assignment_tpl.php';
    }

    /**
    * Method to display the template for adding or editing an assignment.
    * @param string $mode Add or Edit.
    * @return The template for adding or editing an assignment.
    */
    public function addAssign($mode)
    {
        $data = array();
        if($mode == 'edit'){
            $id = $this->getParam('id');
            $data = $this->dbAssignment->getAssignment($this->contextCode, "id='$id'");
        }
        $this->setVarByRef('data', $data);
        return 'assignment_add_tpl.php';
    }

    /**
    * Method to delete an assignment.
    * @return The next action: default.
    */
    public function deleteAssign()
    {
        $this->dbAssignment->deleteAssignment($this->getParam('id'));
        return $this->nextAction('viewbyletter');
    }

    /**
    * Method to save a new assignment or update an existing assignment.
    * @return The next action: default.
    */
    public function saveAssign()
    {
        $id = $this->getParam('id', NULL);
        $description = $this->getParam('description', '');
        $description = $this->objCleaner->cleanHtml($description);

        $fields = array();
        $fields['name'] = $this->getParam('name', '');
        $fields['format'] = $this->getParam('format', '');
        $fields['resubmit'] = $this->getParam('resubmit', '');
        $fields['mark'] = $this->getParam('mark', '');
        $fields['percentage'] = $this->getParam('percentage', '');
        $fields['closing_date'] = $this->getParam('date', '');
        $fields['description'] = $description;

        $fields['userId'] = $this->userId;
        $fields['context'] = $this->contextCode;
        $fields['last_modified'] = date('Y-m-d H:i',time());
	
        $id = $this->dbAssignment->addAssignment($fields, $id);
	
        return $id;
    }

    /**
    * Method to display the assignment.
    * @return The template for displaying the assignment.
    */
    public function viewAssign()
    {
        $id = $this->getParam('id');
        $data = $this->dbAssignment->getAssignment($this->contextCode, "id='$id'");

        if($data[0]['resubmit']){
            $submit = $this->dbSubmit->getSubmit("assignmentId='$id' AND userId='"
            .$this->objUser->userId()."'", 'id, online, studentfileId');
            if(!empty($submit)){
                $data[0]['online'] = $submit[0]['online'];
                $data[0]['studentfileId'] = $submit[0]['studentfileId'];
                $data[0]['submitId'] = $submit[0]['id'];
            }
        }

        $this->setVarByRef('data', $data);
        return 'assignment_view_tpl.php';
    }

    /**
    * Method to list the assignments submitted by students.
    * @return The template for displaying the list of submissions.
    */
    public function listAssign()
    {
        $id = $this->getParam('id');
        $assign = $this->dbAssignment->getAssignment($this->contextCode,"id='$id'");
        $data = $this->dbSubmit->getSubmit("assignmentId = '$id'");

        $this->setVarByRef('assign', $assign[0]);
        $this->setVarByRef('data', $data);
        return 'assignment_list_tpl.php';
    }

    /**
    * Method to display the template for marking an assignment.
    * Lecturers can upload the marked assignment and enter a mark and comment for it.
    * @return The template for marking assignments.
    */
    public function markAssign()
    {
        $data = $this->dbSubmit->getSubmit("id='".$this->getParam('submitId')."'");
        //$file = $this->dbSubmit->getFileName($data[0]['userid'],$data[0]['studentfileid']);
        //$data[0]['filename'] = $file;
        $data[0]['assignmentid'] = $this->getParam('id');
        $data[0]['assignment'] = $this->getParam('assignment');

        $confirm = $this->getParam('confirm');
        if(!empty($confirm)){
            $this->setVarByRef('msg',$confirm);
        }
        $this->setVarByRef('data',$data);
        $this->setVar('online',FALSE);
        return 'assignment_mark_tpl.php';
    }

    /**
    * Method to display the template for marking an online assignment.
    * Lecturers can mark the assignment and enter a mark and comment for it.
    * @return The template for marking assignments.
    */
    public function markOnlineAssign()
    {
        $data = $this->dbSubmit->getSubmit("id='".$this->getParam('submitId')."'");
        $data[0]['assignmentId'] = $this->getParam('id');
        $data[0]['assignment'] = $this->getParam('assignment');
        $this->setVarByRef('data',$data);
        $this->setVar('online',TRUE);
        return 'assignment_mark_tpl.php';
    }

    /**
    * Method to save the marked assignment.
    * The lecturers mark and comment are saved to the relevant assignment
    * @return The next action: mark
    */
    public function saveMark()
    {
        $id = $this->getParam('id', '');
        $fields = array();

        // save mark and comment
        $fields['mark'] = $this->getParam('mark', '');
        $fields['commentinfo'] = $this->getParam('comment', '');
        
        $this->dbSubmit->updateSubmit($this->getParam('submitId', ''), $fields);
        $action = 'mark';
        $params = array('id'=>$this->getParam('id'));

        $postSave = $this->getParam('save');
        if($postSave ==$this->objLanguage->languageText('mod_assignmentadmin_upload','assignmentadmin')){
            // upload marked assignment - overwrite students submission
            $fileId = $this->getParam('fileId', '');
            $fileid = $this->objFile->uploadFile($_FILES['file'],'file',$fileId);
            $action = 'upload';
            $params['assignment'] = $this->getParam('assignment', '');
            $params['submitId'] = $this->getParam('submitId', '');
            if(!empty($fileid)){
                $msg = $this->objLanguage->languageText('mod_assignmentadmin_confirmupload','assignmentadmin');
                $params['confirm'] = $msg;
            }
        }
        return $this->nextAction($action,$params);
    }

    /**
    * Method to display the results from a search.
    * @return The template for the lecturers home page.
    */
    public function showResults()
    {
        $title = $this->objLanguage->languageText('mod_assignmentadmin_listallassignments','assignmentadmin');
        $start = $this->objLanguage->languageText('mod_assignmentadmin_startingwith','assignmentadmin');
        $contain = $this->objLanguage->languageText('mod_assignmentadmin_containing','assignmentadmin');

        $letter = $this->getParam('letter');
        $searchby = 'all';
        $searchField = $this->getParam('searchField', '');

        if (!empty($searchField)) {
            $searchby = $this->getParam('searchby', '');
            $index = '%'.$searchField;
            $title .= ' '.$contain.' "'.$searchField.'"';
        } else {
            if ($letter == 'listall' or $letter == '') {
                $index = '';
            } else {
                $index = $letter;
                $title .= ' '.$start.' "'.$letter.'"';
            }
        }
        $data=array();
        if($searchby == 'assignment' || $searchby == 'all'){
            $assign = $this->dbAssignment->search('name', $index,$this->contextCode);
            if(!empty($assign)){
                $assign[0]['type']='assignmentadmin';
                $data=$assign;
            }
        }
        if($this->test){
            if($searchby == 'test' || $searchby == 'all'){
                $tests = $this->dbTestAdmin->search('name', $index, $this->contextCode);
                if(!empty($tests)){
                    $tests[0]['type']='testadmin';
                    $data = array_merge($data, $tests);
                }
            }
        }
        if($this->essay){
            if($searchby == 'essay' || $searchby == 'all'){
                $filter = "name LIKE '$index%' AND context='".$this->contextCode."'";
                $essays = $this->dbEssayTopics->getTopic(NULL, NULL, $filter);
                if(!empty($essays)){
                    $essays[0]['type']='essayadmin';
                    $data = array_merge($data, $essays);
                }
            }
        }
        if($this->ws){
            if($searchby == 'worksheet' || $searchby == 'all'){
                $worksheets = $this->dbWorksheet->search('name',$index, $this->contextCode);
                if(!empty($worksheets)){
                    $worksheets[0]['type']='worksheetadmin';
                    $data = array_merge($data, $worksheets);
                }
            }
        }

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
    }

    /**
    * Method to search all assignments in the database.
    * @return The interface for searching assignments.
    */
    public function putSearch()
    {
        // Set up html elements
        $this->loadClass('radio','htmlelements');
        $this->loadClass('button','htmlelements');
        $this->loadClass('form','htmlelements');
        $objAlphabet = $this->newObject('alphabet', 'navigation');
        //$objText = $this->newObject('textinput','htmlelements');
        $this->loadClass('textinput','htmlelements');

        // Set up language items
        $searchLabel=$this->objLanguage->languageText('word_search');
        $listLabel=$this->objLanguage->languageText('mod_assignmentadmin_listall','assignmentadmin');
        $allLabel=$this->objLanguage->languageText('mod_assignmentadmin_all','assignmentadmin');
        $testLabel=$this->objLanguage->languageText('mod_testadmin_tests','assignmentadmin');
        $essayLabel=$this->objLanguage->languageText('mod_essayadmin_essays','assignmentadmin');
        $wsLabel=$this->objLanguage->languageText('mod_worksheetadmin_worksheets','assignmentadmin');
        $assignLabel=$this->objLanguage->languageText('mod_assignmentadmin_assignments','assignmentadmin');
        $otherLabel = $this->objLanguage->languageText('mod_assignmentadmin_other','assignmentadmin');

        // search button and input
        $objButton = new button('search', $searchLabel);
        $objButton->setToSubmit();
        $searchbtn = $objButton->show();

        $objText =  new textinput('searchField');
        $objText->size = "20";
        $search = $objText->show() . '&nbsp;&nbsp;&nbsp;' . $searchbtn;

        // set up search in radio buttons
        $objRadio = new radio('searchby');
        if($this->test){
            $objRadio->addOption('test', $testLabel);
        }
        if($this->ws){
            $objRadio->addOption('worksheet', $wsLabel);
        }
        if($this->essay){
            $objRadio->addOption('essay', $essayLabel);
        }
        $objRadio->addOption('assignment', $otherLabel);
        $objRadio->addOption('all',$allLabel);
        $objRadio->setSelected('all');
        $objRadio->setBreakSpace('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
        $search .= '<p>'.$objRadio->show().'</p>';

        $objForm = new form('search', $this->uri(array('action' => 'viewbyletter')));
        $objForm->addToForm($search);
        $str = $objForm->show();

        // set up linked alphabet
        $linkarray = array('action' => 'viewbyletter', 'letter' => 'LETTER');
        $url = $this->uri($linkarray, 'assignmentadmin');

        $alpha = $objAlphabet->putAlpha($url, TRUE, $listLabel);
        $str .= '<p>'.$alpha.'</p>';

        return $str;
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