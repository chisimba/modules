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
 * @version   $Id: functions_assignmentadmin_class_inc.php 13394 2009-05-08 15:47:19Z pwando $
 * @link      http://avoir.uwc.ac.za
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

class functions_assignmentadmin extends object
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
        $data_title = array();
        $data_title[0] = $data;
        $data_title[1] =$title ;
        return $data_title;
    }
    /**
    * Method to save the marked assignment.
    * The lecturers mark and comment are saved to the relevant assignment
    * @return The next action: mark
    */
    public function saveMark($postSave,$id,$mark,$comment,$submitId,$online,$fileId,$assignment)
    {
        //$id = $this->getParam('id', '');
        $fields = array();
        // save mark and comment
        $fields['mark'] = $mark;
        $fields['commentinfo'] = $comment;
        $fields['online'] = $online;
        //update to the tbl_assignments_submit
        $this->dbSubmit->updateSubmit($submitId, $fields);
        //redirecting to the assignment view page.
        $action = 'mark';
        $params = array('id'=>$id);
        //condition if the save or exit was click on the page.
        if($postSave ==$this->objLanguage->languageText('mod_assignmentadmin_upload','assignmentadmin')){
            // upload marked assignment - overwrite students submission
            $action = 'upload';
            $params['assignment'] = $assignment;
            $params['submitId'] = $submitId;

            if(!empty($fileid)){
                $msg = $this->objLanguage->languageText('mod_assignmentadmin_confirmupload','assignmentadmin');
                $params['confirm'] = $msg;
            }
        }
        $next = array('action'=>$action, 'params'=>$params);
        return $next;
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
        return $data;

    }

    /**
    * Method to display the template for marking an assignment.
    * Lecturers can upload the marked assignment and enter a mark and comment for it.
    * @return The template for marking assignments.
    */
    public function markAssign()
    {
        $data = $this->dbSubmit->getSubmit("id='".$this->getParam('submitId')."'");
        //------
        //$file = $this->dbSubmit->getFileName($data[0]['userid'],$data[0]['studentfileid']);
        //------
        $data[0]['filepath'] = $filepath;
        $data[0]['filename'] = $filename;
        $data[0]['assignmentid'] = $this->getParam('id');
        $data[0]['assignment'] = $this->getParam('assignment');
        return $data;
    }

    /**
    * Method to list the assignments submitted by students.
    * @return The template for displaying the list of submissions.
    */
    public function listAssign()
    {
        $id = $this->getParam('id','');
        $assign = $this->dbAssignment->getAssignments($this->contextCode,"id='$id'");
        $data = $this->dbSubmit->getSubmit("assignmentid = '$id'");
        $data_assign = array();
        $data_assign[0] = $data;
        $data_assign[1] = $assign;
        return $data_assign;
    }

    /**
    * Method to display the assignment.
    * @return The template for displaying the assignment.
    */
    public function viewAssign()
    {
        $id = $this->getParam('id');
        $data = $this->dbAssignment->getAssignments($this->contextCode, "id='$id'");

        if($data[0]['resubmit']){
            $submit = $this->dbSubmit->getSubmit("assignmentId='$id' AND userId='"
                .$this->objUser->userId()."'", 'id, online, studentfileId');
            if(!empty($submit)){
                $data[0]['online'] = $submit[0]['online'];
                $data[0]['studentfileId'] = $submit[0]['studentfileId'];
                $data[0]['submitId'] = $submit[0]['id'];
            }
        }

        return $data;
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
            $data = $this->dbAssignment->getAssignments($this->contextCode, "id='$id'");
        }
        return $data;
    }


    /**
    * Method to delete an assignment.
    * @return The next action: default.
    */
    public function deleteAssign()
    {
        $this->dbAssignment->deleteAssignment($this->getParam('id'));

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
        $clean_text = str_replace('<p>','',$description);
        $clean_text = str_replace('</p>','', $clean_text);
        $name= $this->getParam('name', '');
        $format=$this->getParam('format', '');
        $resubmit=$this->getParam('resubmit', '');
        $mark=$this->getParam('mark', '');;
        $percentage= $this->getParam('percentage', '');
        $closing_date=$this->getParam('date', '');
        $last_modified= date('Y-m-d H:i:s',time());
        $assesment_type=$this->getParam('assesment_type','');
        $fields = array();
        $fields['name'] =$name;
        $fields['format'] = $format;
        $fields['resubmit'] = $resubmit;
        $fields['mark'] = $mark;
        $fields['percentage'] =$percentage;
        $fields['closing_date'] = $closing_date;

        $fields['description'] = $clean_text;
        $fields['userId'] = $this->userId;
        $fields['context'] = $this->contextCode;
        $fields['last_modified'] =$last_modified;
        $fields['assesment_type'] =$assesment_type;
        if($id==NULL)
        $id = $this->dbAssignment->addAssignment($name, $this->contextCode, $clean_text, $resubmit, $format, $mark, $percentage, $last_modified, $closing_date,$assesment_type);
        else
        $this->dbAssignment->updateAssignment($id,$name, $clean_text, $resubmit, $format, $mark, $percentage, $last_modified, $closing_date,$assesment_type);
        return $id;
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

}//end of class
?>
