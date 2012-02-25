<?php
/**
 *
 * Assignments
 *
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
 * @package   assignment2
 * @copyright 2007 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: controller.php 11340 2008-11-05 17:47:06Z davidwaf $
 * @link      http://avoir.uwc.ac.za
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
            $this->test = FALSE;
            $this->essay = FALSE;
            $this->ws = FALSE;
            $this->rubric = FALSE;
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
                $id = $this->getParam('id');
                $mark = $this->getParam('mark');
                $comment = $this->getParam('comment');
                $submitId = $this->getParam('submitId');
                $online = $this->getParam('online');
                $fileId = $this->getParam('fileId');
                $assignment = $this->getParam('assignment');
                if($postSave == $this->objLanguage->languageText('word_exit')){
                    return $this->nextAction('mark',array('id'=>$this->getParam('id')));
                }
                $next = $this->objAssignmentAdmin->saveMark($postSave,$id,$mark,$comment,$submitId,$online,$fileId,$assignment);
                return $this->nextAction($next[0], $next[1]);

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

?>