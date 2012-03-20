<?php
/**
 *
 * The grades operations class
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
 * @version    0.001
 * @package    grades
 * @author     Kevin Cyster kcyster@gmail.com
 * @copyright  2011 AVOIR
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @link       http://www.chisimba.com
 * 
 */
// security check - must be included in all scripts
if (!
/**
 * Description for $GLOBALS
 * @global unknown $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
 * 
 * The grades operations class.
 *
 *
 * @category  Chisimba
 * @author    Kevin Cyster kcyster@gmail.com
 * @version   0.001
 * @copyright 2011 AVOIR
 *
 */
class gradesops extends object
{
    /**
     * Standard init function
     *
     * Create title
     *
     * @return NULL
     */
    public function init() 
    {
        try {
            // Load core system objects.
            $this->objLanguage = $this->getObject('language', 'language');
            $this->objUser = $this->getObject('user', 'security');
            $this->objUserAdmin = $this->getObject('useradmin_model2', 'security');
            $this->objSvars = $this->getObject('serializevars', 'utilities');
            $this->objGroups = $this->getObject('groupadminmodel', 'groupadmin');
            $this->objConfirm = $this->newObject('confirm', 'utilities');

            // Load html elements.
            $this->objIcon = $this->newObject('geticon', 'htmlelements');
            $this->objTable = $this->loadClass('htmltable', 'htmlelements');
            $this->objLink = $this->loadClass('link', 'htmlelements');
            $this->objInput = $this->loadClass('textinput', 'htmlelements');
            $this->objFieldset = $this->loadClass('fieldset', 'htmlelements');
            $this->objDropdown = $this->loadClass('dropdown', 'htmlelements');
            $this->objForm = $this->loadClass('form', 'htmlelements');
            $this->objLayer = $this->loadClass('layer', 'htmlelements');
            $this->objRadio = $this->loadClass('radio', 'htmlelements');
            $this->objText = $this->loadClass('textarea', 'htmlelements');
            $this->objTab = $this->newObject('tabber', 'htmlelements');

            $this->objDBgrades = $this->getObject('dbgrades', 'grades');
            $this->objDBsubjects = $this->getObject('dbsubjects', 'grades');
            $this->objDBclasses = $this->getObject('dbclasses', 'grades');
            $this->objDBgradesubject = $this->getObject('dbgradesubject', 'grades');
            $this->objDBsubjectclass = $this->getObject('dbsubjectclass', 'grades');
            $this->objDBsubjectcontext = $this->getObject('dbsubjectcontext', 'grades');
        }
        catch(customException $e) {
            echo customException::cleanUp();
            die();
        }
    }
    
    /**
     *
     * Method to generate an error string for display
     * 
     * @access private
     * @param string $errorText The error string
     * @return string $string The formated error string
     */
    private function error($errorText)
    {
        $error = $this->objLanguage->languageText('word_error', 'system', 'WORD: word_error, not found');
        
        $this->objIcon->title = $error;
        $this->objIcon->alt = $error;
        $this->objIcon->setIcon('exclamation', 'png');
        $errorIcon = $this->objIcon->show();
        
        $string = '<span style="color: red">' . $errorIcon . '&nbsp;<b>' . $errorText . '</b></span>';
        return $string;
    }

    /**
     * Method to show the content on the default main block.
     *
     * @return string $string The display string
     */
    public function showMain() 
    {
        $descriptionLabel = $this->objLanguage->code2Txt('mod_grades_description', 'grades', NULL, 'ERROR: mod_grades_description');

        $objFieldset = new fieldset();
        $objFieldset->contents = $descriptionLabel;
        $mainFieldset = $objFieldset->show();
        
        $string = $mainFieldset;
        
        return $string;
    }

    /**
     *
     * Method to show the content of the left manage block
     * 
     * @return string $string The display string
     */
    public function showManage()
    {
        $gradesLabel = $this->objLanguage->code2Txt('mod_grades_managegrades', 'grades', NULL, 'ERROR: mod_grades_managegrades');
        $subjectsLabel = $this->objLanguage->code2Txt('mod_grades_managesubjects', 'grades', NULL, 'ERROR: mod_grades_managesubjects');
        $classesLabel = $this->objLanguage->code2Txt('mod_grades_manageclasses', 'grades', NULL, 'ERROR: mod_grades_manageclasses');

        $this->objIcon->title = $gradesLabel;
        $this->objIcon->alt = $gradesLabel;
        $this->objIcon->setIcon('brick', 'png');
        $manageIcon = $this->objIcon->show();
        
        $objLink = new link($this->uri(array('action' => 'list', 'type' => 'g')));
        $objLink->link = $manageIcon . '&nbsp' . $gradesLabel;
        $gradesLink = $objLink->show();

        $this->objIcon->title = $subjectsLabel;
        $this->objIcon->alt = $subjectsLabel;
        $this->objIcon->setIcon('book', 'png');
        $manageIcon = $this->objIcon->show();
        
        $objLink = new link($this->uri(array('action' => 'list', 'type' => 's')));
        $objLink->link = $manageIcon . '&nbsp' . $subjectsLabel;
        $subjectsLink = $objLink->show();
    
        $this->objIcon->title = $classesLabel;
        $this->objIcon->alt = $classesLabel;
        $this->objIcon->setIcon('group', 'png');
        $manageIcon = $this->objIcon->show();
        
        $objLink = new link($this->uri(array('action' => 'list', 'type' => 'c')));
        $objLink->link = $manageIcon . '&nbsp' . $classesLabel;
        $classesLink = $objLink->show();
    
        $string = $gradesLink . '<br />' . '<br />' . $subjectsLink . '<br />' . '<br />' . $classesLink;
                
        return $string;
    }
    
    /**
     *
     * Method to show the list of grades
     * 
     * @access public
     * @return string $string The display string 
     */
    public function showList()
    {
        $type = $this->getParam('type');
        
        $addLabel = $this->objLanguage->languageText('word_add', 'system', 'ERROR: word_add');
        $editLabel = $this->objLanguage->languageText('word_edit', 'system', 'ERROR: word_edit');
        $deleteLabel = $this->objLanguage->languageText('word_delete', 'system', 'ERROR: word_delete');
        $linkLabel = $this->objLanguage->languageText('word_link', 'system', 'ERROR: word_link');
        $descriptionLabel = $this->objLanguage->languageText('word_description', 'system', 'ERROR: word_description');

        $gradeLabel = $this->objLanguage->code2Txt('mod_grades_grade', 'grades', NULL, 'ERROR: mod_grades_grade');
        $gradesLabel = $this->objLanguage->code2Txt('mod_grades_grades', 'grades', NULL, 'ERROR: mod_grades_grades');
        $addGradeLabel = $this->objLanguage->code2Txt('mod_grades_addgrade', 'grades', NULL, 'ERROR: mod_grades_addgrade');
        $editGradeLabel = $this->objLanguage->code2Txt('mod_grades_editgrade', 'grades', NULL, 'ERROR: mod_grades_editgrade');
        $deleteGradeLabel = $this->objLanguage->code2Txt('mod_grades_deletegrade', 'grades', NULL, 'ERROR: mod_grades_deletegrade');
        $noGradesLabel = $this->objLanguage->code2Txt('mod_grades_nogrades', 'grades', NULL, 'ERROR: mod_grades_nogrades');
        $linkGradeLabel = $this->objLanguage->code2Txt('mod_grades_linkgrade', 'grades', NULL, 'ERROR: mod_grades_linkgrade');
        
        $subjectLabel = $this->objLanguage->code2Txt('mod_grades_subject', 'grades', NULL, 'ERROR: mod_grades_subject');
        $subjectsLabel = $this->objLanguage->code2Txt('mod_grades_subjects', 'grades', NULL, 'ERROR: mod_grades_subjects');
        $addSubjectLabel = $this->objLanguage->code2Txt('mod_grades_addsubject', 'grades', NULL, 'ERROR: mod_grades_addsubject');
        $editSubjectLabel = $this->objLanguage->code2Txt('mod_grades_editsubject', 'grades', NULL, 'ERROR: mod_grades_editsubject');
        $deleteSubjectLabel = $this->objLanguage->code2Txt('mod_grades_deletesubject', 'grades', NULL, 'ERROR: mod_grades_deletesubject');
        $noSubjectsLabel = $this->objLanguage->code2Txt('mod_grades_nosubjects', 'grades', NULL, 'ERROR: mod_grades_nosubjects');
        $linkSubjectLabel = $this->objLanguage->code2Txt('mod_grades_linksubject', 'grades', NULL, 'ERROR: mod_grades_linksubject');
        
        $classLabel = $this->objLanguage->code2Txt('mod_grades_class', 'grades', NULL, 'ERROR: mod_grades_class');
        $classesLabel = $this->objLanguage->code2Txt('mod_grades_classes', 'grades', NULL, 'ERROR: mod_grades_classes');
        $addClassLabel = $this->objLanguage->code2Txt('mod_grades_addclass', 'grades', NULL, 'ERROR: mod_grades_addclass');
        $editClassLabel = $this->objLanguage->code2Txt('mod_grades_editclass', 'grades', NULL, 'ERROR: mod_grades_editclass');
        $deleteClassLabel = $this->objLanguage->code2Txt('mod_grades_deleteclass', 'grades', NULL, 'ERROR: mod_grades_deleteclass');
        $noClassesLabel = $this->objLanguage->code2Txt('mod_grades_noclasses', 'grades', NULL, 'ERROR: mod_grades_noclasses');
        $linkClassLabel = $this->objLanguage->code2Txt('mod_grades_linkclass', 'grades', NULL, 'ERROR: mod_grades_linkclass');

        switch ($type)
        {
            case 'g':
                $addImage = 'brick_add';
                $editImage = 'brick_edit';
                $deleteImage = 'brick_delete';
                $linkImage = 'brick_link';
                $componentLabel = ucfirst(strtolower($gradeLabel));
                $componentsLabel = ucfirst(strtolower($gradesLabel));
                $addComponentLabel = $addGradeLabel;
                $editComponentLabel = $editGradeLabel;
                $deleteComponentLabel = $deleteGradeLabel;
                $noComponentsLabel = $noGradesLabel;
                $linkComponentLabel = $linkGradeLabel;
                $dataArray = $this->objDBgrades->getAll();
                break;
            case 's';
                $addImage = 'book_add';
                $editImage = 'book_edit';
                $deleteImage = 'book_delete';
                $linkImage = 'book_link';
                $componentLabel = ucfirst(strtolower($subjectLabel));
                $componentsLabel = ucfirst(strtolower($subjectsLabel));
                $addComponentLabel = $addSubjectLabel;
                $editComponentLabel = $editSubjectLabel;
                $deleteComponentLabel = $deleteSubjectLabel;
                $noComponentsLabel = $noSubjectsLabel;
                $linkComponentLabel = $linkSubjectLabel;
                $dataArray = $this->objDBsubjects->getAll();
                break;
            case 'c';
                $addImage = 'group_add';
                $editImage = 'group_edit';
                $deleteImage = 'group_delete';
                $linkImage = 'group_link';
                $componentLabel = ucfirst(strtolower($classLabel));
                $componentsLabel = ucfirst(strtolower($classesLabel));
                $addComponentLabel = $addClassLabel;
                $editComponentLabel = $editClassLabel;
                $deleteComponentLabel = $deleteClassLabel;
                $noComponentsLabel = $noClassesLabel;
                $linkComponentLabel = $linkClassLabel;
                $dataArray = $this->objDBclasses->getAll();
                break;
        }
        $array = array('item' => $componentLabel);
        $deleteConfirmLabel = ucfirst(strtolower($this->objLanguage->code2Txt('mod_grades_deleteconfirm', 'grades', $array, 'ERROR: mod_grades_deleteconfirm'))) . '?';

        $this->objIcon->title = $addLabel;
        $this->objIcon->alt = $addLabel;
        $this->objIcon->setIcon($addImage, 'png');
        $addIcon = $this->objIcon->show();

        $objLink = new link($this->uri(array('action' => 'form', 'type' => $type)));
        $objLink->link = $addIcon . '&nbsp;' . $addComponentLabel;
        $addLink = $objLink->show();
            
        if (empty($dataArray))
        {
            $str = $this->error($noComponentsLabel);
            $str .= '<br />' . $addLink . '<br />';
        }
        else
        {
            $str = $addLink . '<br />';
            
            $objTable = new htmltable();
            $objTable->cellpadding = '4';
            $objTable->startHeaderRow();
            $objTable->addHeaderCell('<b>' . $componentLabel . '</b>', '15%', '', 'left', 'heading', '');
            $objTable->addHeaderCell('<b>' . $descriptionLabel . '</b>', '55%', '', 'left', 'heading', '');            
            $objTable->addHeaderCell('<b>' . $editLabel . '</b>', '10%', '', 'left', 'heading', '');
            $objTable->addHeaderCell('<b>' . $deleteLabel . '</b>', '10%', '', 'left', 'heading', '');
            $objTable->addHeaderCell('<b>' . $linkLabel . '</b>', '10%', '', 'left', 'heading', '');
            $objTable->endHeaderRow();
            
            $i = 0;
            foreach ($dataArray as $value)
            {
                $class = (($i++ % 2) == 0) ? 'even' : 'odd';
                $this->objIcon->setIcon($deleteImage, 'png');
                $this->objIcon->title = $deleteComponentLabel;
                $this->objIcon->alt = $deleteComponentLabel;
                $icon = $this->objIcon->show();

                $location = $this->uri(array('action' => 'delete', 'type' => $type, 'id' => $value['id']));

                $this->objConfirm->setConfirm($icon, $location, $deleteConfirmLabel);
                $deleteIcon = $this->objConfirm->show();

                $this->objIcon->title = $editComponentLabel;
                $this->objIcon->alt = $editComponentLabel;
                $this->objIcon->setIcon($editImage, 'png');
                $editIcon = $this->objIcon->show();

                $objLink = new link($this->uri(array('action' => 'form', 'type' => $type, 'id' => $value['id'])));
                $objLink->link = $editIcon;
                $editLink = $objLink->show();

                $this->objIcon->title = $linkComponentLabel;
                $this->objIcon->alt = $linkComponentLabel;
                $this->objIcon->setIcon($linkImage, 'png');
                $linkIcon = $this->objIcon->show();

                $objLink = new link($this->uri(array('action' => 'link', 'type' => $type, 'id' => $value['id'])));
                $objLink->link = $linkIcon;
                $linkLink = $objLink->show();

                $objTable->startRow();
                $objTable->addCell($value['name'], '', '', '', $class, '', '');
                $objTable->addCell($value['description'], '', '', '', $class, '', '');
                $objTable->addCell($editLink, '', '', '', $class, '', '');
                $objTable->addCell($deleteIcon, '', '', '', $class, '', '');
                $objTable->addCell($linkLink, '', '', '', $class, '', '');
                $objTable->endRow();
            }
            $gradeTable = $objTable->show();   
            $str .= $gradeTable;
        }
                
        $objLayer = new layer();
        $objLayer->id = 'gradediv';
        $objLayer->str = $str;
        $gradeLayer = $objLayer->show();

        $objFieldset = new fieldset();
        $objFieldset->legend = '<b>' . ucfirst(strtolower($componentsLabel)) . '</b>';
        $objFieldset->contents = $gradeLayer;
        $gradeFieldset = $objFieldset->show();
        
        return $gradeFieldset;
    }
    
    /**
     *
     * Method to show the add or edit template
     * 
     * @access public
     * @return string $string The display string 
     */
    public function showForm()
    {
        $type = $this->getParam('type');
        $id = $this->getParam('id');
        $errorArray = $this->getSession('errors');
        
        if (empty($id))
        {
            $nameValue = NULL;
            $descriptionValue = NULL;
        }
        else
        {
            switch ($type)
            {
                case 'g':
                    $dataArray = $this->objDBgrades->getGrade($id);
                    break;
                case 's':
                    $dataArray = $this->objDBsubjects->getSubject($id);
                    break;
                case 'c':
                    $dataArray = $this->objDBclasses->getClass($id);
                    break;
            }
            $nameValue = $dataArray['name'];
            $descriptionValue = $dataArray['description'];
        }
        $nameValue = (empty($errorArray)) ? $nameValue : $errorArray['data']['name'];
        $descriptionValue = (empty($errorArray)) ? $descriptionValue : $errorArray['data']['description'];
        
        $nameError = (!empty($errorArray) && array_key_exists('name', $errorArray['errors'])) ? $errorArray['errors']['name'] : NULL;
        $descriptionError = (!empty($errorArray) && array_key_exists('description', $errorArray['errors'])) ? $errorArray['errors']['description'] : NULL;
        
        $descriptionLabel = $this->objLanguage->languageText('word_description', 'system', 'ERROR: word_description');
        $saveLabel = $this->objLanguage->languageText('word_save', 'system', 'ERROR: word_save');
        $cancelLabel = $this->objLanguage->languageText('word_cancel', 'system', 'ERROR: word_cancel');
        
        $gradeLabel = $this->objLanguage->code2Txt('mod_grades_grade', 'grades', NULL, 'ERROR: mod_grades_grade');
        $subjectLabel = $this->objLanguage->code2Txt('mod_grades_subject', 'grades', NULL, 'ERROR: mod_grades_subject');
        $classLabel = $this->objLanguage->code2Txt('mod_grades_class', 'grades', NULL, 'ERROR: mod_grades_class');
        
        switch ($type)
        {
            case 'g':
                $nameLabel = ucfirst(strtolower($gradeLabel));
                break;
            case 's':
                $nameLabel = ucfirst(strtolower($subjectLabel));
                break;
            case 'c':
                $nameLabel = ucfirst(strtolower($classLabel));
                break;
        }
        
        $objInput = new textinput('name', $nameValue, '', '50');
        $nameInput = $objInput->show();
  
        $objText = new textarea('description', $descriptionValue);
        $descriptionText = $objText->show();

        $objInput = new textinput('id', $id, 'hidden', '50');
        $idInput = $objInput->show();

        $objButton = new button('save', $saveLabel);
        $objButton->setId('save');
        $objButton->setToSubmit();
        $saveButton = $objButton->show();
        
        $objButton = new button('cancel', $cancelLabel);
        $objButton->setId('cancel');
        $objButton->setToSubmit();
        $cancelButton = $objButton->show();

        $objTable = new htmltable();
        $objTable->cellpadding = '4';
        $objTable->startRow();
        $objTable->addCell($nameLabel, '200px', '', '', 'odd', '', '');
        $objTable->addCell($nameError . $nameInput, '', '', '', 'odd', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($descriptionLabel, '200px', 'top', '', 'even', '', '');
        $objTable->addCell($descriptionError . $descriptionText, '', '', '', 'even', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($idInput . $saveButton . '&nbsp' . $cancelButton, '', '', '', 'odd', 'colspan="7"', '');
        $objTable->endRow();
        $formTable = $objTable->show();

        $objForm = new form('component', $this->uri(array(
            'action' => 'validate', 'type' => $type,
        )));
        $objForm->extra = ' enctype="multipart/form-data"';
        $objForm->addToForm($formTable);
        $form = $objForm->show();
        
        $string = $form;
        
        return $string;        
    }

    /**
     *
     * Method to validate the component data
     * 
     * @access public
     * @param array $data The data to validate
     * @return boolean TRUE on errors | FALSE if no errors
     */
    public function validate($data)
    {
        $errors = array();
        foreach ($data as $fieldname => $value)
        {
            if ($fieldname != 'id')
            {
                if ($value == NULL)
                {
                    $name = explode('_', $fieldname);
                    $name = implode(' ', $name);
                    $array = array('fieldname' => $name);
                    $errorText = $this->objLanguage->code2Txt('mod_schools_error_1', 'schools', $array);
                    $errors[$fieldname] = '<div>' . $this->error(ucfirst(strtolower($errorText))) . '</div>';
                }
            }
        }
        $errorArray = array();
        $errorArray['data'] = $data;
        $errorArray['errors'] = $errors;

        $this->setSession('errors', $errorArray);
        if (empty($errors))
        {
            return FALSE;
        }
        return TRUE;
    }        
    
    /**
     *
     * Method to return the links template
     * 
     * @access public
     * @return string $string The display string 
     */
    public function showLink()
    {
        $nameLabel = $this->objLanguage->languageText('word_name', 'system', 'ERROR: word_name');
        $subjectLabel = $this->objLanguage->code2Txt('mod_grades_subject', 'grades', NULL, 'ERROR: mod_grades_subject');
        $descriptionLabel = $this->objLanguage->languageText('word_description', 'system', 'ERROR: word_description');
        $noLinkLabel = $this->objLanguage->languageText('mod_grades_nolinks', 'grades', 'ERROR: mod_grades_nolinks');
        $deleteLinkLabel = $this->objLanguage->languageText('mod_grades_deletelink', 'grades', 'ERROR: mod_grades_deletelink');
        $addGradeLinkLabel = $this->objLanguage->code2Txt('mod_grades_linktograde', 'grades', NULL, 'ERROR: mod_grades_linktograde');
        $addClassLinkLabel = $this->objLanguage->code2Txt('mod_grades_linktoclass', 'grades', NULL, 'ERROR: mod_grades_linktoclass');
        $addContextLinkLabel = $this->objLanguage->code2Txt('mod_grades_linktocontext', 'grades', NULL, 'ERROR: mod_grades_linktocontext');
        $linkedGradeLabel = $this->objLanguage->code2Txt('mod_grades_linkedgrades', 'grades', NULL, 'ERROR: mod_grades_linkedgrades');
        $linkedClassLabel = $this->objLanguage->code2Txt('mod_grades_linkedclasses', 'grades', NULL, 'ERROR: mod_grades_linkedclasses');
        $linkedContextLabel = $this->objLanguage->code2Txt('mod_grades_linkedcontexts', 'grades', NULL, 'ERROR: mod_grades_linkedcontexts');
        $addLabel = $this->objLanguage->languageText('word_add', 'system', 'ERROR: word_add');
        $deleteLabel = $this->objLanguage->languageText('word_delete', 'system', 'ERROR: word_delete');
        $selectGradeLabel = $this->objLanguage->code2Txt('mod_grades_selectgrade', 'grades', NULL, 'ERROR: mod_grades_selectgrade');
        $selectClassLabel = $this->objLanguage->code2Txt('mod_grades_selectclass', 'grades', NULL, 'ERROR: mod_grades_selectclass');
        $selectContextLabel = $this->objLanguage->code2Txt('mod_grades_selectcontext', 'grades', NULL, 'ERROR: mod_grades_selectcontext');
        $gradeLabel = $this->objLanguage->code2Txt('mod_grades_grade', 'grades', NULL, 'ERROR: mod_grades_grade');
        $classLabel = $this->objLanguage->code2Txt('mod_grades_class', 'grades', NULL, 'ERROR: mod_grades_class');
        $contextLabel = $this->objLanguage->code2Txt('word_context', 'system', NULL, 'ERROR: word_context');
        $saveLabel = $this->objLanguage->languageText('word_save', 'system', 'ERROR: word_save');
        $cancelLabel = $this->objLanguage->languageText('word_cancel', 'system', 'ERROR: word_cancel');
        $noGradesLabel =$this->objLanguage->code2Txt('mod_grades_nogrades', 'grades', NULL, 'ERROR: mod_grades_nogrades');
        $noClassesLabel =$this->objLanguage->code2Txt('mod_grades_noclasses', 'grades', NULL, 'ERROR: mod_grades_noclasses');
        $noContextsLabel =$this->objLanguage->code2Txt('mod_grades_nocontexts', 'grades', NULL, 'ERROR: mod_grades_nocontexts');
        $errorGradeLabel = $this->objLanguage->code2Txt('mod_grades_errorgrade', 'grades', NULL, 'ERROR: mod_grades_errorgrade');
        $errorClassLabel = $this->objLanguage->code2Txt('mod_grades_errorclass', 'grades', NULL, 'ERROR: mod_grades_errorclass');
        $errorContextLabel = $this->objLanguage->code2Txt('mod_grades_errorcontext', 'grades', NULL, 'ERROR: mod_grades_errorcontext');
        $addGradeLabel = $this->objLanguage->code2Txt('mod_grades_addgrade', 'grades', NULL, 'ERROR: mod_grades_addgrade');
        $addClassLabel = $this->objLanguage->code2Txt('mod_grades_addclass', 'grades', NULL, 'ERROR: mod_grades_addclass');
        $addContextLabel = $this->objLanguage->code2Txt('mod_grades_addcontext', 'grades', NULL, 'ERROR: mod_grades_addcontext');
        
        $array = array('item' => $gradeLabel);
        $deleteGradeLabel = $this->objLanguage->code2Txt('mod_grades_deleteconfirm', 'grades', $array, 'ERROR: mod_grades_deleteconfirm');
        $array = array('item' => $classLabel);
        $deleteClassLabel = $this->objLanguage->code2Txt('mod_grades_deleteconfirm', 'grades', $array, 'ERROR: mod_grades_deleteconfirm');
        $array = array('item' => $contextLabel);
        $deleteContextLabel = $this->objLanguage->code2Txt('mod_grades_deleteconfirm', 'grades', $array, 'ERROR: mod_grades_deleteconfirm');
        
        $arrayVars = array();
        $arrayVars['no_grade'] = $errorGradeLabel;
        $arrayVars['no_class'] = $errorClassLabel;
        $arrayVars['no_context'] = $errorContextLabel;
        
        // pass error to javascript.
        $this->objSvars->varsToJs($arrayVars);

        $id = $this->getParam('id');
        $type = $this->getParam('type');
        $tab = $this->getParam('tab', 0);
        $subjectArray = $this->objDBsubjects->getSubject($id);
        $linkedGradesArray = $this->objDBgradesubject->getLinkedGrades($id);
        $unlinkedGradesArray = $this->objDBgradesubject->getUnlinkedGrades($id);
        $linkedClassesArray = $this->objDBsubjectclass->getLinkedClasses($id);
        $unlinkedClassesArray = $this->objDBsubjectclass->getUnlinkedClasses($id);
        $linkedContextsArray = $this->objDBsubjectcontext->getLinkedContexts($id);
        $unlinkedContextsArray = $this->objDBsubjectcontext->getUnlinkedContexts($id);
        
        //-- subject fieldset --//        
        $objTable = new htmltable();
        $objTable->cellpadding = '4';
        $objTable->startRow();
        $objTable->addCell($nameLabel, '200px', '', '', 'odd', '', '');
        $objTable->addCell($subjectArray['name'], '', '', '', 'odd', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($descriptionLabel, '200px', 'top', '', 'even', '', '');
        $objTable->addCell($subjectArray['description'], '', '', '', 'even', '', '');
        $objTable->endRow();
        $subjectTable = $objTable->show();

        $objFieldset = new fieldset();
        $objFieldset->legend = '<b>' . ucfirst(strtolower($subjectLabel)) . '</b>';
        $objFieldset->contents = $subjectTable;
        $subjectFieldset = $objFieldset->show();

        $string = $subjectFieldset;
        
        //-- grade tab --//
        $objDrop = new dropdown('grade_id');
        $objDrop->addOption('', $selectGradeLabel);
        $objDrop->addFromDB($unlinkedGradesArray, 'name', 'id');
        $objDrop->setSelected('');
        $gradesDrop = $objDrop->show();
  
        $objInput = new textinput('subject_id', $id, 'hidden', '50');
        $idInput = $objInput->show();

        $objButton = new button('save', $saveLabel);
        $objButton->setId('save_grade');
        $saveButton = $objButton->show();
        
        $objButton = new button('cancel', $cancelLabel);
        $objButton->setId('cancel_grade');
        $cancelButton = $objButton->show();

        $objTable = new htmltable();
        $objTable->cellpadding = '4';
        $objTable->startRow();
        $objTable->addCell(ucfirst(strtolower($gradeLabel)), '200px', '', '', 'odd', '', '');
        $objTable->addCell($gradesDrop, '', '', '', 'odd', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($idInput . $saveButton . '&nbsp' . $cancelButton, '', '', '', 'even', 'colspan="7"', '');
        $objTable->endRow();
        $formTable = $objTable->show();

        $objForm = new form('grade', $this->uri(array(
            'action' => 'savelink', 'type' => $type, 'link' => 'g',
        )));
        $objForm->extra = ' enctype="multipart/form-data"';
        $objForm->addToForm($formTable);
        $gradeForm = $objForm->show();

        if (!empty($linkedGradesArray))
        {
            if (!empty($unlinkedGradesArray))
            {
                $objLayer = new layer();
                $objLayer->id = 'gradesformdiv';
                $objLayer->display = 'none';
                $objLayer->str = $gradeForm;
                $formLayer = $objLayer->show();

                $this->objIcon->title = $addLabel;
                $this->objIcon->alt = $addLabel;
                $this->objIcon->setIcon('link_add', 'png');
                $linkIcon = $this->objIcon->show();
                
                $addLink = '<a href="#" id="addgradelink">' . $linkIcon . '&nbsp;' . $addGradeLinkLabel . '</a>';

                $objLayer = new layer();
                $objLayer->id = 'addgradesdiv';
                $objLayer->str = $addLink;
                $addLayer = $objLayer->show();
            }
            else
            {
                $formLayer = NULL;
                $addLayer = NULL;
            }

            $objTable = new htmltable();
            $objTable->cellpadding = '4';
            $objTable->startHeaderRow();
            $objTable->addHeaderCell('<b>' . ucfirst(strtolower($gradeLabel)) . '</b>', '15%', '', 'left', 'heading', '');
            $objTable->addHeaderCell('<b>' . $descriptionLabel . '</b>', '55%', '', 'left', 'heading', '');            
            $objTable->addHeaderCell('<b>' . $deleteLabel . '</b>', '10%', '', 'left', 'heading', '');
            $objTable->endHeaderRow();
            
            $i = 0;
            foreach ($linkedGradesArray as $value)
            {
                $class = (($i++ % 2) == 0) ? 'even' : 'odd';
                
                $this->objIcon->setIcon('link_delete', 'png');
                $this->objIcon->title = $deleteLinkLabel;
                $this->objIcon->alt = $deleteLinkLabel;
                $icon = $this->objIcon->show();

                $location = $this->uri(array('action' => 'deletelink', 'type' => $type, 'link' => 'g', 'id' => $id, 'del' => $value['id']));

                $this->objConfirm->setConfirm($icon, $location, $deleteGradeLabel);
                $deleteIcon = $this->objConfirm->show();

                $objTable->startRow();
                $objTable->addCell($value['name'], '', '', '', $class, '', '');
                $objTable->addCell($value['description'], '', '', '', $class, '', '');
                $objTable->addCell($deleteIcon, '', '', '', $class, '', '');
                $objTable->endRow();
            }
            $linkTable = $objTable->show();
            
            $objLayer = new layer();
            $objLayer->id = 'gradestablediv';
            $objLayer->str = $linkTable;
            $tableLayer = $objLayer->show();            

            $objLayer = new layer();
            $objLayer->id = 'gradesdiv';
            $objLayer->str = $formLayer . $addLayer . $tableLayer;
            $gradesLayer = $objLayer->show();            
        }
        else
        {   
            $noLinks = $this->error($noLinkLabel);
            
            if (empty($unlinkedGradesArray))
            {
                $noGrades = $this->error($noGradesLabel);

                $this->objIcon->title = $addLabel;
                $this->objIcon->alt = $addLabel;
                $this->objIcon->setIcon('brick_add', 'png');
                $addIcon = $this->objIcon->show();

                $objLink = new link($this->uri(array('action' => 'form', 'type' => 'g')));
                $objLink->link = $addIcon . '&nbsp;' . $addGradeLabel;
                $addLink = $objLink->show();
            
                $objLayer = new layer();
                $objLayer->id = 'gradesdiv';
                $objLayer->str = $noLinks . '<br />' . $noGrades . '<br />' . $addLink;
                $gradesLayer = $objLayer->show();
            }
            else
            {
                $objLayer = new layer();
                $objLayer->id = 'gradesformdiv';
                $objLayer->display = 'none';
                $objLayer->str = $gradeForm;
                $formLayer = $objLayer->show();
                
                $this->objIcon->title = $addLabel;
                $this->objIcon->alt = $addLabel;
                $this->objIcon->setIcon('link_add', 'png');
                $linkIcon = $this->objIcon->show();

                $addLink = '<a href="#" id="addgradelink">' . $linkIcon . '&nbsp;' . $addGradeLinkLabel . '</a>';

                $objLayer = new layer();
                $objLayer->id = 'addgradesdiv';
                $objLayer->str = $noLinks . '<br />' . $addLink;
                $addLayer = $objLayer->show();

                $objLayer = new layer();
                $objLayer->id = 'gradesdiv';
                $objLayer->str = $formLayer . $addLayer;
                $gradesLayer = $objLayer->show();
            }
        }

        $gradeTabArray = array(
            'name' => ucfirst(strtolower($linkedGradeLabel)),
            'content' => $gradesLayer,
        );

        //-- class tab --//
        $objDrop = new dropdown('class_id');
        $objDrop->addOption('', $selectClassLabel);
        $objDrop->addFromDB($unlinkedClassesArray, 'name', 'id');
        $objDrop->setSelected('');
        $classesDrop = $objDrop->show();
  
        $objInput = new textinput('subject_id', $id, 'hidden', '50');
        $idInput = $objInput->show();

        $objButton = new button('save', $saveLabel);
        $objButton->setId('save_class');
        $saveButton = $objButton->show();
        
        $objButton = new button('cancel', $cancelLabel);
        $objButton->setId('cancel_class');
        $cancelButton = $objButton->show();

        $objTable = new htmltable();
        $objTable->cellpadding = '4';
        $objTable->startRow();
        $objTable->addCell(ucfirst(strtolower($classLabel)), '200px', '', '', 'odd', '', '');
        $objTable->addCell($classesDrop, '', '', '', 'odd', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($idInput . $saveButton . '&nbsp' . $cancelButton, '', '', '', 'even', 'colspan="7"', '');
        $objTable->endRow();
        $formTable = $objTable->show();

        $objForm = new form('class', $this->uri(array(
            'action' => 'savelink', 'type' => $type, 'link' => 'c',
        )));
        $objForm->extra = ' enctype="multipart/form-data"';
        $objForm->addToForm($formTable);
        $classForm = $objForm->show();

        if (!empty($linkedClassesArray))
        {
            if (!empty($unlinkedClassesArray))
            {
                $objLayer = new layer();
                $objLayer->id = 'classesformdiv';
                $objLayer->display = 'none';
                $objLayer->str = $classForm;
                $formLayer = $objLayer->show();

                $this->objIcon->title = $addLabel;
                $this->objIcon->alt = $addLabel;
                $this->objIcon->setIcon('link_add', 'png');
                $linkIcon = $this->objIcon->show();
                
                $addLink = '<a href="#" id="addclasslink">' . $linkIcon . '&nbsp;' . $addGradeLinkLabel . '</a>';

                $objLayer = new layer();
                $objLayer->id = 'addclassesdiv';
                $objLayer->str = $addLink;
                $addLayer = $objLayer->show();
            }
            else
            {
                $formLayer = NULL;
                $addLayer = NULL;
            }

            $objTable = new htmltable();
            $objTable->cellpadding = '4';
            $objTable->startHeaderRow();
            $objTable->addHeaderCell('<b>' . ucfirst(strtolower($classLabel)) . '</b>', '15%', '', 'left', 'heading', '');
            $objTable->addHeaderCell('<b>' . $descriptionLabel . '</b>', '55%', '', 'left', 'heading', '');            
            $objTable->addHeaderCell('<b>' . $deleteLabel . '</b>', '10%', '', 'left', 'heading', '');
            $objTable->endHeaderRow();
            
            $i = 0;
            foreach ($linkedClassesArray as $value)
            {
                $class = (($i++ % 2) == 0) ? 'even' : 'odd';
                
                $this->objIcon->setIcon('link_delete', 'png');
                $this->objIcon->title = $deleteLinkLabel;
                $this->objIcon->alt = $deleteLinkLabel;
                $icon = $this->objIcon->show();

                $location = $this->uri(array('action' => 'deletelink', 'type' => $type, 'link' => 'c', 'id' => $id, 'del' => $value['id']));

                $this->objConfirm->setConfirm($icon, $location, $deleteClassLabel);
                $deleteIcon = $this->objConfirm->show();

                $objTable->startRow();
                $objTable->addCell($value['name'], '', '', '', $class, '', '');
                $objTable->addCell($value['description'], '', '', '', $class, '', '');
                $objTable->addCell($deleteIcon, '', '', '', $class, '', '');
                $objTable->endRow();
            }
            $linkTable = $objTable->show();
            
            $objLayer = new layer();
            $objLayer->id = 'classestablediv';
            $objLayer->str = $linkTable;
            $tableLayer = $objLayer->show();            

            $objLayer = new layer();
            $objLayer->id = 'classesdiv';
            $objLayer->str = $formLayer . $addLayer . $tableLayer;
            $classesLayer = $objLayer->show();            
        }
        else
        {   
            $noLinks = $this->error($noLinkLabel);
            
            if (empty($unlinkedClassesArray))
            {
                $noClasses = $this->error($noClassesLabel);

                $this->objIcon->title = $addLabel;
                $this->objIcon->alt = $addLabel;
                $this->objIcon->setIcon('group_add', 'png');
                $addIcon = $this->objIcon->show();

                $objLink = new link($this->uri(array('action' => 'form', 'type' => 'c')));
                $objLink->link = $addIcon . '&nbsp;' . $addClassLabel;
                $addLink = $objLink->show();
            
                $objLayer = new layer();
                $objLayer->id = 'classesdiv';
                $objLayer->str = $noLinks . '<br />' . $noClasses . '<br />' . $addLink;
                $classesLayer = $objLayer->show();
            }
            else
            {
                $objLayer = new layer();
                $objLayer->id = 'classesformdiv';
                $objLayer->display = 'none';
                $objLayer->str = $classForm;
                $formLayer = $objLayer->show();
                
                $this->objIcon->title = $addLabel;
                $this->objIcon->alt = $addLabel;
                $this->objIcon->setIcon('link_add', 'png');
                $linkIcon = $this->objIcon->show();

                $addLink = '<a href="#" id="addclasslink">' . $linkIcon . '&nbsp;' . $addClassLinkLabel . '</a>';

                $objLayer = new layer();
                $objLayer->id = 'addclassesdiv';
                $objLayer->str = $noLinks . '<br />' . $addLink;
                $addLayer = $objLayer->show();

                $objLayer = new layer();
                $objLayer->id = 'classesdiv';
                $objLayer->str = $formLayer . $addLayer;
                $classesLayer = $objLayer->show();
            }
        }
        
        $classTabArray = array(
            'name' => ucfirst(strtolower($linkedClassLabel)),
            'content' => $classesLayer,
        );

        //-- contexttab --//
        $objDrop = new dropdown('context_id');
        $objDrop->addOption('', $selectContextLabel);
        $objDrop->addFromDB($unlinkedContextsArray, 'title', 'id');
        $objDrop->setSelected('');
        $contextsDrop = $objDrop->show();
  
        $objInput = new textinput('subject_id', $id, 'hidden', '50');
        $idInput = $objInput->show();

        $objButton = new button('save', $saveLabel);
        $objButton->setId('save_context');
        $saveButton = $objButton->show();
        
        $objButton = new button('cancel', $cancelLabel);
        $objButton->setId('cancel_context');
        $cancelButton = $objButton->show();

        $objTable = new htmltable();
        $objTable->cellpadding = '4';
        $objTable->startRow();
        $objTable->addCell(ucfirst(strtolower($contextLabel)), '200px', '', '', 'odd', '', '');
        $objTable->addCell($contextsDrop, '', '', '', 'odd', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($idInput . $saveButton . '&nbsp' . $cancelButton, '', '', '', 'even', 'colspan="7"', '');
        $objTable->endRow();
        $formTable = $objTable->show();

        $objForm = new form('context', $this->uri(array(
            'action' => 'savelink', 'type' => $type, 'link' => 'x',
        )));
        $objForm->extra = ' enctype="multipart/form-data"';
        $objForm->addToForm($formTable);
        $contextForm = $objForm->show();

        if (!empty($linkedContextsArray))
        {
            if (!empty($unlinkedContextsArray))
            {
                $objLayer = new layer();
                $objLayer->id = 'contextsformdiv';
                $objLayer->display = 'none';
                $objLayer->str = $contextForm;
                $formLayer = $objLayer->show();

                $this->objIcon->title = $addLabel;
                $this->objIcon->alt = $addLabel;
                $this->objIcon->setIcon('link_add', 'png');
                $linkIcon = $this->objIcon->show();
                
                $addLink = '<a href="#" id="addcontextlink">' . $linkIcon . '&nbsp;' . $addGradeLinkLabel . '</a>';

                $objLayer = new layer();
                $objLayer->id = 'addcontextsdiv';
                $objLayer->str = $addLink;
                $addLayer = $objLayer->show();
            }
            else
            {
                $formLayer = NULL;
                $addLayer = NULL;
            }

            $objTable = new htmltable();
            $objTable->cellpadding = '4';
            $objTable->startHeaderRow();
            $objTable->addHeaderCell('<b>' . ucfirst(strtolower($contextLabel)) . '</b>', '15%', '', 'left', 'heading', '');
            $objTable->addHeaderCell('<b>' . $descriptionLabel . '</b>', '55%', '', 'left', 'heading', '');            
            $objTable->addHeaderCell('<b>' . $deleteLabel . '</b>', '10%', '', 'left', 'heading', '');
            $objTable->endHeaderRow();
            
            $i = 0;
            foreach ($linkedContextsArray as $value)
            {
                $class = (($i++ % 2) == 0) ? 'even' : 'odd';
                
                $this->objIcon->setIcon('link_delete', 'png');
                $this->objIcon->title = $deleteLinkLabel;
                $this->objIcon->alt = $deleteLinkLabel;
                $icon = $this->objIcon->show();

                $location = $this->uri(array('action' => 'deletelink', 'type' => $type, 'link' => 'x', 'id' => $id, 'del' => $value['id']));

                $this->objConfirm->setConfirm($icon, $location, $deleteContextLabel);
                $deleteIcon = $this->objConfirm->show();

                $objTable->startRow();
                $objTable->addCell($value['title'], '', '', '', $class, '', '');
                $objTable->addCell($value['about'], '', '', '', $class, '', '');
                $objTable->addCell($deleteIcon, '', '', '', $class, '', '');
                $objTable->endRow();
            }
            $linkTable = $objTable->show();
            
            $objLayer = new layer();
            $objLayer->id = 'contextstablediv';
            $objLayer->str = $linkTable;
            $tableLayer = $objLayer->show();            

            $objLayer = new layer();
            $objLayer->id = 'contextsdiv';
            $objLayer->str = $formLayer . $addLayer . $tableLayer;
            $contextsLayer = $objLayer->show();            
        }
        else
        {   
            $noLinks = $this->error($noLinkLabel);
            
            if (empty($unlinkedContextsArray))
            {
                $noContexts = $this->error($noContextsLabel);

                $this->objIcon->title = $addLabel;
                $this->objIcon->alt = $addLabel;
                $this->objIcon->setIcon('group_add', 'png');
                $addIcon = $this->objIcon->show();

                $objLink = new link($this->uri(array('action' => 'form', 'type' => 'c')));
                $objLink->link = $addIcon . '&nbsp;' . $addContextLabel;
                $addLink = $objLink->show();
            
                $objLayer = new layer();
                $objLayer->id = 'contextsdiv';
                $objLayer->str = $noLinks . '<br />' . $noContexts . '<br />' . $addLink;
                $contextsLayer = $objLayer->show();
            }
            else
            {
                $objLayer = new layer();
                $objLayer->id = 'contextsformdiv';
                $objLayer->display = 'none';
                $objLayer->str = $contextForm;
                $formLayer = $objLayer->show();
                
                $this->objIcon->title = $addLabel;
                $this->objIcon->alt = $addLabel;
                $this->objIcon->setIcon('link_add', 'png');
                $linkIcon = $this->objIcon->show();

                $addLink = '<a href="#" id="addcontextlink">' . $linkIcon . '&nbsp;' . $addContextLinkLabel . '</a>';

                $objLayer = new layer();
                $objLayer->id = 'addcontextsdiv';
                $objLayer->str = $noLinks . '<br />' . $addLink;
                $addLayer = $objLayer->show();

                $objLayer = new layer();
                $objLayer->id = 'contextsdiv';
                $objLayer->str = $formLayer . $addLayer;
                $contextsLayer = $objLayer->show();
            }
        }
        
        $contextTabArray = array(
            'name' => ucfirst(strtolower($linkedContextLabel)),
            'content' => $contextsLayer,
        );

        $this->objTab->init();
        $this->objTab->tabId = 'links_tab';
        $this->objTab->setSelected = $tab;
        $this->objTab->addTab($gradeTabArray);
        $this->objTab->addTab($classTabArray);
        $this->objTab->addTab($contextTabArray);
        $linkTab = $this->objTab->show();

        $string .= $linkTab;        

        return $string;
    }
}
?>