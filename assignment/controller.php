<?php
/**
 *
 * Assignments
 *
 * Assignments enable students to view a list of booked assignments. The status is displayed indicating whether it is open, closed or if the student has submitted. The mark is shown once it has been marked.A new assignment can be opened for answering. Students can complete the assignment if its online and submit it. An uploadable or offline assignment can be completed and then loaded into the database. A marked assignment can be opened and the lecturer's comment can be viewed.
 *
 * PHP version 5
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the
 * Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 * @category  Chisimba
 * @package   helloforms
 * @author    Tohir Solomons tsolomons@uwc.ac.za
 * @copyright 2007 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 */

// security check - must be included in all scripts
if (!
/**
 * The $GLOBALS is an array used to control access to certain constants.
 * Here it is used to check if the file is opening in engine, if not it
 * stops the file from running.
 *
 * @global entry point $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 *
 */
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
 *
 * Controller class for Chisimba for the module assignment2
 *
 * @author Tohir Solomons
 * @package assignment2
 *
 */
class assignment extends controller {

    /**
     *
     * @var string $objConfig String object property for holding the
     * configuration object
     * @access public;
     *
     */
    public $objConfig;

    /**
     *
     * @var string $objLanguage String object property for holding the
     * language object
     * @access public
     *
     */
    public $objLanguage;
    /**
     *
     * @var string $objLog String object property for holding the
     * logger object for logging user activity
     * @access public
     *
     */
    public $objLog;

    public $contextCode;

    /**
     *
     * Intialiser for the assignment2 controller
     * @access public
     *
     */
    public function init() {
        $this->objUser = $this->getObject('user', 'security');
        $this->objLanguage = $this->getObject('language', 'language');
        // Create the configuration object
        $this->objConfig = $this->getObject('altconfig', 'config');
        // Create an instance of the database class

        //database objects , provides access to the related tables.
        $this->objAssignment = $this->getObject('dbassignment');
        $this->objAssignmentSubmit = $this->getObject('dbassignmentsubmit');


        $this->objAssignmentFunctions = $this->getObject('functions_assignment', 'assignment');
        $this->objDate = $this->getObject('dateandtime','utilities');
        $this->objLanguage = $this->getObject('language','language');
        $this->objUser = $this->getObject('user','security');
        $this->objContext = $this->getObject('dbcontext','context');
        if($this->objContext->isInContext()) {
            $this->contextCode = $this->objContext->getContextCode();
            $this->context = $this->objContext->getTitle();
        }
        $this->objIcon= $this->newObject('geticon','htmlelements');
        $this->loadclass('link','htmlelements');
        $this->objLink=new link();
        //Get the activity logger class
        $this->objLog=$this->newObject('logactivity', 'logger');
        //Log this module call
        $this->objLog->log();
        //Load Module Catalogue Class
        $this->objModuleCatalogue = $this->getObject('modules', 'modulecatalogue');

        $this->objContextGroups = $this->getObject('managegroups', 'contextgroups');

        if($this->objModuleCatalogue->checkIfRegistered('activitystreamer')) {
            $this->objActivityStreamer = $this->getObject('activityops', 'activitystreamer');
            $this->eventDispatcher->addObserver ( array ($this->objActivityStreamer, 'postmade' ) );
            $this->eventsEnabled = TRUE;
        } else {
            $this->eventsEnabled = FALSE;
        }
    }


    public function isValid($action) {
        $restrictedActions = array ('add', 'edit', 'saveassignment', 'updateassignment', 'delete', 'markassignments', 'saveuploadmark', 'saveonlinemark');

        if (in_array($action, $restrictedActions)) {
            $valid = $this->objUser->isCourseAdmin($this->contextCode);
        } else {
            $valid = TRUE;
        }

        return $valid;
    }

    /**
     *
     * The standard dispatch method for the assignment2 module.
     * The dispatch method uses methods determined from the action
     * parameter of the  querystring and executes the appropriate method,
     * returning its appropriate template. This template contains the code
     * which renders the module output.
     *
     */
    public function dispatch($action) {
        /*else {
            return $this->nextAction(NULL, array('error'=>'notincontext'), '_default');
        }
        */
        if (!$this->isValid($action)) {
            return $this->nextAction(NULL, array('error'=>'nopermission'));
        }

        $this->setLayoutTemplate('assignment_layout_tpl.php');


        /*
        * Convert the action into a method (alternative to
        * using case selections)
        */
        $method = $this->__getMethod($action);
        /*
        * Return the template determined by the method resulting
        * from action
        */
        return $this->$method();
    }

    /**
     *
     * Method to check if a given action is a valid method
     * of this class preceded by double underscore (__). If it __action
     * is not a valid method it returns FALSE, if it is a valid method
     * of this class it returns TRUE.
     *
     * @access private
     * @param string $action The action parameter passed byref
     * @return boolean TRUE|FALSE
     *
     */
    function __validAction(& $action) {
        if (method_exists($this, "__".$action)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     *
     * Method to convert the action parameter into the name of
     * a method of this class.
     *
     * @access private
     * @param string $action The action parameter passed byref
     * @return stromg the name of the method
     *
     */
    function __getMethod(& $action) {
        if ($this->__validAction($action)) {
            return "__" . $action;
        } else {
            return "__home";
        }
    }


    /*------------- BEGIN: Set of methods to replace case selection ------------*/

    /**
     *
     * Method corresponding to the view action. It fetches the stories
     * into an array and passes it to a main_tpl content template.
     * @access private
     *
     */
    private function __home() {

        $assignments = $this->objAssignment->getAssignments($this->contextCode);
        $this->setVarByRef('assignments', $assignments);

        return 'assignment_home_tpl.php';
    }
    private function __displaylist() {

        $assignments = $this->objAssignment->getAssignments($this->contextCode);
        $this->setVarByRef('assignments', $assignments);

        return 'assignment_list_tpl.php';
    }


    private function __add() {
        $this->setVar('mode', 'add');

        return 'addedit_assignment_tpl.php';
    }

    private function __saveassignment() {
        $name = $this->getParam('name');
        $type = $this->getParam('type');
        $resubmit = $this->getParam('resubmit');
        $mark = $this->getParam('mark');
        $yearmark = $this->getParam('yearmark');
        $openingDate = $this->getParam('openingdate').' '.$this->getParam('openingtime');
        $closingDate = $this->getParam('closingdate').' '.$this->getParam('closingtime');
        $description = $this->getParam('description');
        $assesment_type = $this->getParam('assesment_type');
        $emailAlert=$this->getParam('emailalert');

        $result = $this->objAssignment->addAssignment($name, $this->contextCode, $description, $resubmit, $type, $mark, $yearmark, $openingDate, $closingDate, $assesment_type,$emailAlert);

        if ($result == FALSE) {
            return $this->nextAction(NULL, array('error'=>'unabletosaveassignment'));
        } else {
            //add to activity streamer
            if($this->eventsEnabled) {
                $message = $this->objUser->getsurname()." ".$this->objLanguage->languageText('mod_assignment_addedassignment', 'assignment')." ".$this->contextCode;
                $this->eventDispatcher->post($this->objActivityStreamer, "context", array('title'=> $message,
                        'link'=> $this->uri(array()),
                        'contextcode' => $this->contextCode,
                        'author' => $this->objUser->fullname(),
                        'description'=>$message));

                $this->eventDispatcher->post($this->objActivityStreamer, "context", array('title'=> $message,
                        'link'=> $this->uri(array()),
                        'contextcode' => null,
                        'author' => $this->objUser->fullname(),
                        'description'=>$message));
            }
            return $this->nextAction('view', array('id'=>$result));
        }
    }
    // display notes in a pop up window
    private function __showcomment() {
        $this->setLayoutTemplate('');
        $id = $this->getParam('id');
        $submissionId=$this->getParam('submissionid');
        $assignment = $this->objAssignment->getAssignment($id);
        $submission = $this->objAssignmentSubmit->getSubmission($submissionId);
        $this->setVarByRef('assignment',$assignment);
        $this->setVarByRef('submission', $submission);
        return 'onlineview_tpl.php';
    }

    private function __view() {
        $id = $this->getParam('id');

        $assignment = $this->objAssignment->getAssignment($id);

        if ($assignment == FALSE) {
            return $this->nextAction(NULL, array('error'=>'noassignment'));
        }

        if ($assignment['context'] != $this->contextCode) {
            return $this->nextAction(NULL, array('error'=>'wrongcontext'));
        }

        $this->setVarByRef('assignment', $assignment);

        return 'viewassignment_tpl.php';
    }

    function __edit() {
        $id = $this->getParam('id');

        $assignment = $this->objAssignment->getAssignment($id);

        if ($assignment == FALSE) {
            return $this->nextAction(NULL, array('error'=>'noassignment'));
        }

        if ($assignment['context'] != $this->contextCode) {
            return $this->nextAction(NULL, array('error'=>'wrongcontext'));
        }

        $this->setVarByRef('assignment', $assignment);
        $this->setVar('mode', 'edit');

        return 'addedit_assignment_tpl.php';
    }


    function __updateassignment() {
        $id = $this->getParam('id');
        $name = $this->getParam('name');

        $resubmit = $this->getParam('resubmit');
        $type = $this->getParam('type');
        $mark = $this->getParam('mark');
        $yearmark = $this->getParam('yearmark');

        $openingDate = $this->getParam('openingdate').' '.$this->getParam('openingtime');
        $closingDate = $this->getParam('closingdate').' '.$this->getParam('closingtime');

        $description = $this->getParam('description');
        $assesment_type = $this->getParam('assesment_type');
        $emailAlert=$this->getParam('emailalert');
        
        $result = $this->objAssignment->updateAssignment($id, $name, $description, $resubmit, $type, $mark, $yearmark, $openingDate, $closingDate, $assesment_type,$emailAlert);

        $result = $result ? 'Y' : 'N';

        return $this->nextAction('view', array('id'=>$id, 'update'=>$result));
    }

    function __uploadassignment() {
        $objFileUpload = $this->getObject('uploadinput', 'filemanager');
        $objFileUpload->enableOverwriteIncrement = TRUE;
        $results = $objFileUpload->handleUpload('fileupload');

        // Technically, FALSE can never be returned, this is just a precaution
        // FALSE means there is no fileinput with that name
        if ($results == FALSE) {
            return $this->nextAction('view', array('id'=>$this->getParam('id'), 'error'=>'unabletoupload'));
        } else {
            // If successfully Uploaded
            if ($results['success']) {

                return $this->__submitassignment($results['fileid']);

            } else {
                // If not successfully uploaded
                return $this->nextAction('view', array('id'=>$this->getParam('id'),'error'=>$results['reason']));
            }
        }
    }


    function __submitassignment($fileId=null) {
        if ($fileId == NULL) {
            $fileId = $this->getParam('assignment');
        }

        $result = $this->objAssignmentSubmit->submitAssignmentUpload($this->getParam('id'), $this->objUser->userId(), $fileId);

        return $this->nextAction('view', array('id'=>$this->getParam('id'), 'message'=>'assignmentsubmitted'));
    }

    function __submitonlineassignment() {
        $result = $this->objAssignmentSubmit->submitAssignmentOnline($this->getParam('id'), $this->objUser->userId(), $this->getParam('text'));

        return $this->nextAction('view', array('id'=>$this->getParam('id'), 'message'=>'assignmentsubmitted'));
    }


    function __viewsubmission() {
        $id = $this->getParam('id');

        $submission = $this->objAssignmentSubmit->getSubmission($id);

        if ($submission == FALSE) {
            return $this->nextAction(NULL, array('error'=>'unknownsubmission'));
        }

        $assignment = $this->objAssignment->getAssignment($submission['assignmentid']);

        if ($assignment == FALSE) {
            return $this->nextAction(NULL, array('error'=>'unknownassignment'));
        }

        if ($assignment['context'] != $this->contextCode) {
            return $this->nextAction(NULL, array('error'=>'wrongcontext'));
        }

        $this->setVarByRef('assignment', $assignment);
        $this->setVarByRef('submission', $submission);

        return 'viewsubmission_tpl.php';
    }

    function __downloadfile() {
        $id = $this->getParam('id');
        //$mode = $this->getParam('mode');
        $fileId = $this->getParam('fileid');

        $submission = $this->objAssignmentSubmit->getSubmission($id);

        if ($submission == FALSE) {
            return $this->nextAction(NULL, array('error'=>'unknownsubmission'));
        }

        $assignment = $this->objAssignment->getAssignment($submission['assignmentid']);

        if ($assignment == FALSE) {
            return $this->nextAction(NULL, array('error'=>'unknownassignment'));
        }

//        switch($mode){
//            case 'submitted':
//                $fileid = $submission['studentfileid'];
//                break;
//            case 'marked':
//                $fileid = $submission['lecturerfileid'];
//                break;
//            default:
//                return $this->nextAction(NULL, array('error'=>'unknownmode'));
//        }

        $filePath = $this->objAssignmentSubmit->getAssignmentFilename($submission['id'], $fileId);

        $objDateTime = $this->getObject('dateandtime', 'utilities');

        $objFile = $this->getObject('dbfile', 'filemanager');

        $file = $objFile->getFile($fileId);

        $extension = $file['datatype'];

        $filename = $this->objUser->fullName($submission['userid']).' '.$objDateTime->formatDate($submission['datesubmitted']);
        $filename = str_replace(' ', '_', $filename);
        $filename = str_replace(':', '_', $filename);

        if (file_exists($filePath)) {
            // Set Mimetype
            header('Content-type: '.$file['mimetype']);
            // Set filename and as download
            header('Content-Disposition: attachment; filename="'.$filename.'.'.$extension.'"');
            // Load file
            readfile($filePath);
            exit;
        }


    }

    function __saveuploadmark() {
        $id = $this->getParam('id');
        $mark = $this->getParam('mark');
        $comment = $this->getParam('commentinfo');

        $this->objAssignmentSubmit->markAssignment($id, $mark, $comment);

        $submission = $this->objAssignmentSubmit->getSubmission($id);

        ///
        $filePath = $this->objConfig->getcontentPath().'/assignment/submissions/'.$id;

        $objCleanUrl = $this->getObject('cleanurl', 'filemanager');
        $folderPath = $objCleanUrl->cleanUpUrl($filePath);

        $objFolder = $this->getObject('dbfolder', 'filemanager');

        $folderId = $objFolder->indexFolder($folderPath, FALSE);

        $objUpload = $this->getObject('upload', 'filemanager');
        $objUpload->setUploadFolder('/assignment/submissions/'.$id);
        $objUpload->enableOverwriteIncrement = TRUE;

        $restrictions = NULL;

        $fileUploadResultsArray = array();

        $fileResults = $objUpload->uploadFile('lectfile', $restrictions, $fileUploadResultsArray);

        if ($fileResults['success'] == TRUE) {
            $this->objAssignmentSubmit->setLecturerMarkFile($id, $fileResults['fileid']);
        }

        return $this->nextAction('view', array('id'=>$submission['assignmentid'], 'message'=>'assignmentmarked', 'assignment'=>$id));
    }


    function __saveonlinemark() {
        $id = $this->getParam('id');
        $mark = $this->getParam('mark');
        $comment = $this->getParam('commentinfo');

        $this->objAssignmentSubmit->markAssignment($id, $mark, $comment);

        $submission = $this->objAssignmentSubmit->getSubmission($id);

        return $this->nextAction('view', array('id'=>$submission['assignmentid'], 'message'=>'assignmentmarked', 'assignment'=>$id));
    }

    function __viewhtmlsubmission() {
        //die;
//        echo '<pre>';
//        var_dump($_GET);
//        echo '</pre>';
        $id = $this->getParam('id');
        $fileId = $this->getParam('fileid');
//        echo "Id==$id";
//        echo "FileId==$fileId";
//        die;
        $filePath = $this->objAssignmentSubmit->getAssignmentFilename($id, $fileId).'.php';

        //$filePath = $this->objConfig->getcontentBasePath().'/assignment/submissions/'.$id.'/'.$filename;

        $objCleanUrl = $this->getObject('cleanurl', 'filemanager');
        $filePath = $objCleanUrl->cleanUpUrl($filePath);

        $permission = TRUE;

        include($filePath);
    }

    function __delete() {
        $id = $this->getParam('id');

        $assignment = $this->objAssignment->getAssignment($id);

        if ($assignment == FALSE) {
            return $this->nextAction(NULL, array('error'=>'noassignment'));
        }

        if ($assignment['context'] != $this->contextCode) {
            return $this->nextAction(NULL, array('error'=>'wrongcontext'));
        }

        $this->setVarByRef('assignment', $assignment);

        // Generate Random Number required for delete
        // This prevents delete by URL
        $randomNumber = rand(0, 1000);
        $this->setVar('randNumber', $randomNumber);
        $this->setSession($id, $randomNumber);

        return 'deleteassignment_tpl.php';
    }

    function __deleteconfirm() {
        $id = $this->getParam('id');

        $assignment = $this->objAssignment->getAssignment($id);

        if ($assignment == FALSE) {
            return $this->nextAction(NULL, array('error'=>'noassignment'));
        }

        if ($assignment['context'] != $this->contextCode) {
            return $this->nextAction(NULL, array('error'=>'wrongcontext'));
        }

        if ($this->getParam('confirm') == 'Y') {
            if ($this->getSession($id) == $this->getParam('randNumber')) {
                $result = $this->objAssignment->deleteAssignment($id);

                return $this->nextAction(NULL, array('id'=>$id, 'message'=>'assignmentdeleted'));
            } else {
                return $this->nextAction('delete', array('id'=>$id, 'error'=>'invaliddeletesession'));
            }
        } else {
            return $this->nextAction($this->getParam('return'), array('id'=>$id, 'message'=>'deletecancelled'));
        }
    }
    function __exportospreadsheet() {
        $objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $downloadfolder=$objSysConfig->getValue('DOWNLOAD_FOLDER', 'assignment');
        $this->objAltConfig = $this->getObject('altconfig','config');
        $siteRoot=$this->objAltConfig->getsiteRoot();

        $assignmentid=$this->getParam('assignmentid');
        $assignment = $this->objAssignment->getAssignment($assignmentid);
        $submissions = $this->objAssignmentSubmit->getStudentSubmissions($assignmentid);
        $id = mktime().rand();
        $filename=$assignmentid;//.'-'.$id;

        $objMkDir = $this->getObject('mkdir', 'files');
        $destinationDir = $this->objAltConfig->getcontentBasePath().'/assignment/submissions/export';
        $objMkDir->mkdirs($destinationDir);
        $exportfile=$destinationDir."/". $filename.".xls";

        if(file_exists($exportfile)) {
            unlink($exportfile);
        }
        $file = fopen($exportfile, "a");
        $objDateTime = $this->getObject('dateandtime', 'utilities');
        $objWashout = $this->getObject('washout', 'utilities');
        $type=$submission['online'] == 0?"Online":"Upload";
        $name=$assignment['name'];
        $desc=strip_tags($assignment['description']);
        $percOfYear=$assignment['percentage'].'%';

        $openingDate=$objDateTime->formatDate($assignment['opening_date']);
        $closingDate=$objDateTime->formatDate($assignment['closing_date']);
        fputs($file, "Title, $name\r\n");
        fputs($file, "Description, $desc\r\n");
        fputs($file, "Percentage of year mark, $percOfYear\r\n");
        fputs($file, "Opening Date, $openingDate\r\n");
        fputs($file, "Closing Date, $closingDate\r\n");
        fputs($file, "Type: $type\r\n");
        fputs($file, "\r\n");

        fputs($file, "\r\n");

        fputs($file,'Student No,Name,Submision Date,Mark, Comment');
        fputs($file, "\r\n");
        foreach ($submissions as $submission) {

            $date=$objDateTime->formatDate($submission['datesubmitted']);
            $mark="";
            $comment="";
            $student=$this->objUser->fullname($submission['userid']);
            $username=$this->objUser->username($submission['userid']);
            if ($submission['mark'] == NULL) {
                $mark=$this->objLanguage->languageText('mod_assignment_notmarked', 'assignment', 'Not Marked');

            } else {
                $mark=$submission['mark'].'%';
                $comment=$submission['commentinfo'];
            }

            $content=$username.','.$student.','. $date.','.$mark.', '.$comment."\r\n";

            fputs($file,$content);
        }

        fclose($file);


        $this->nextAction('downloadassignmentfile',array("filename"=>$filename));
    }

    function __downloadassignmentfile() {
        $filename=$this->getParam('filename');
        $this->setVarbyRef("filename",$filename);
        return "downloadsubmissionsfile_tpl.php";
    }
    /*------------- END: Set of methods to replace case selection ------------*/

}
?>
