<?php
// security check - must be included in all scripts
if ( !$GLOBALS['kewl_entry_point_run'] ) {
    die( "You cannot view this page directly" );
} 
// end security check
/**
 * The examiners controller manages the examiners module
 * @author Kevin Cyster 
 * @copyright 2008, University of the Western Cape & AVOIR Project
 * @license GNU GPL
 * @package examiners
 */

class examiners extends controller {

    /**
    * @var object $objExamDisplay: The examdisplay class in the examiners module
    * @access public
    */
    public $objExamDisplay;
    
    /**
    * @var object $objExamDb: The dbexams class in the examiners module
    * @access public
    */
    public $objExamDb;
        
    /**
    * @var bool $isExamAdmin: TRUE if the user is in the Exam Admin group FALSE if not
    * @access public
    */
    public $isExamAdmin;

    /**
    * @var bool $isLoggedIn: TRUE if the user is logged in FALSE if not
    * @access public
    */
    public $isLoggedIn;

    /**
    * @var object $filePath: The file path to module description files
    * @access public
    */
    public $filePath;

    /**
    * Method to initialise the controller
    * 
    * @access public
    * @return void
    */
    public function init()
    {
        $this->objExamDisplay = $this->newObject('examdisplay', 'examiners');
        $this->objExamDb = $this->newObject('dbexams', 'examiners');        
        $objGroup = $this->getObject('groupadminmodel', 'groupadmin');
        $groupId = $objGroup->getId('Faculty managers', 'name');
        if(!isset($groupId)){
            $groupId = $objGroup->addGroup('Faculty managers', 'The group for Faculty managers');
        }
        $groupId = $objGroup->getId('Department managers', 'name');
        if(!isset($groupId)){
            $groupId = $objGroup->addGroup('Department managers', 'The group for Faculty managers');
        }
        $objUser = $this->getObject('user', 'security');
        $userId = $objUser->userId();
        $pkId = $objUser->PKId($userId);
        $this->isBookAdmin = $objGroup->isGroupMember($pkId, $groupId);
        $this->isLoggedIn = $objUser->isLoggedIn();
        
        $objConfig = $this->newObject('altconfig', 'config');
        $contentBasePath = $objConfig->getcontentBasePath();
        $this->filePath = $contentBasePath.'modules/examiners/';
        if(!is_dir($this->filePath)){
            mkdir($contentBasePath.'modules/', 0777);
            mkdir($contentBasePath.'modules/examiners', 0777);
        }
    }
    
    /**
    * Method the engine uses to kickstart the module
    * 
    * @access public
    * @param string $action: The action to be performed
    * @return void
    */
    function dispatch($action)
    {
        switch($action){
            case 'home':
                $templateContent = $this->objExamDisplay->showHome();
         		$this->setVarByRef('templateContent', $templateContent);
         		return 'template_tpl.php';
         		break;
                
             case 'faculties':
                $templateContent = $this->objExamDisplay->showFaculties();
         		$this->setVarByRef('templateContent', $templateContent);
         		return 'template_tpl.php';
         		break;
                
            case 'faculty':
                $facId = $this->getParam('f');
                $templateContent = $this->objExamDisplay->showAddEditFaculty($facId);
         		$this->setVarByRef('templateContent', $templateContent);
         		return 'template_tpl.php';
         		break;
                
            case 'save_faculty':                
                $facId = $this->getParam('f');
                $name = $this->getParam('name');
                if($facId == ''){
                    $this->objExamDb->addFaculty($name);
                }else{
                    $this->objExamDb->editFaculty($facId, $name);
                }
                return $this->nextAction('faculties', array(), 'examiners');
                break;
                
            case 'delete_faculty':
                $facId = $this->getParam('f');
                $this->objExamDb->deleteFaculty($facId);
                return $this->nextAction('faculties', array(), 'examiners');
                break;

            case 'departments':
                $facId = $this->getParam('f');
                $templateContent = $this->objExamDisplay->showDepartments($facId);
         		$this->setVarByRef('templateContent', $templateContent);
         		return 'template_tpl.php';
         		break;
                
            case 'department':
                $facId = $this->getParam('f');
                $depId = $this->getParam('d');
                $templateContent = $this->objExamDisplay->showAddEditDepartment($facId, $depId);
         		$this->setVarByRef('templateContent', $templateContent);
         		return 'template_tpl.php';
         		break;
                
            case 'save_department':
                $facId = $this->getParam('f');                
                $depId = $this->getParam('d');
                $name = $this->getParam('name');
                if($depId == ''){
                    $this->objExamDb->addDepartment($facId, $name);
                }else{
                    $this->objExamDb->editDepartment($depId, $name);
                }
                return $this->nextAction('departments', array(
                    'f' => $facId,
                ), 'examiners');
                break;
                
            case 'delete_department':
                $facId = $this->getParam('f');
                $depId = $this->getParam('d');
                $this->objExamDb->deleteDepartment($depId);
                return $this->nextAction('departments', array(
                    'f' => $facId,
                ), 'examiners');
                break;

            case 'subjects':
                $facId = $this->getParam('f');
                $depId = $this->getParam('d');
                $templateContent = $this->objExamDisplay->showSubjects($facId, $depId);
         		$this->setVarByRef('templateContent', $templateContent);
         		return 'template_tpl.php';
         		break;
                
            case 'subject':
                $facId = $this->getParam('f');
                $depId = $this->getParam('d');
                $subjId = $this->getParam('s');
                $templateContent = $this->objExamDisplay->showAddEditSubject($facId, $depId, $subjId);
         		$this->setVarByRef('templateContent', $templateContent);
         		return 'template_tpl.php';
         		break;
                
            case 'save_subject':                
                $facId = $this->getParam('f');
                $depId = $this->getParam('d');
                $subjId = $this->getParam('s');
                $code = $this->getParam('code');
                $name = $this->getParam('name');
                $level = $this->getParam('level');
                $status = $this->getParam('status');
                $file = $_FILES;
                if($subjId == ''){
                    $subjId = $this->objExamDb->addSubject($facId, $depId, $code, $name, $level);
                }else{
                    $this->objExamDb->editSubject($subjId, $code, $name, $level, $status);
                }
                if(is_uploaded_file($file['file']['tmp_name']) && $file['file']['error'] == 0){
                    $filename = explode('.', basename($file['file']['name']));
                    $ext = array_pop($filename);
                    move_uploaded_file($file['file']['tmp_name'], $this->filePath.$code.'.'.$ext);
                }
                return $this->nextAction('subjects', array(
                    'f' => $facId,
                    'd' => $depId,
                ), 'examiners');
                break;
                
            case 'delete_subject':
                $facId = $this->getParam('f');
                $subjId = $this->getParam('s');
                $depId = $this->getParam('d');
                $subject = $this->objExamDb->getSubjectById($subjId);
                $this->objExamDb->deleteSubject($subjId);
                $file = glob($this->filePath.$subject['course_code'].'.*');
                if(!empty($file)){
                    unlink($this->filePath.basename($file[0]));               
                }
                 return $this->nextAction('subjects', array(
                    'f' => $facId,
                    'd' => $depId,
                ), 'examiners');
                break;

            case 'examiners':
                $depId = $this->getParam('d');
                $templateContent = $this->objExamDisplay->showExaminers($depId);
         		$this->setVarByRef('templateContent', $templateContent);
         		return 'template_tpl.php';
         		break;
                
            case 'examiner':
                $depId = $this->getParam('d');
                $userId = $this->getParam('u');
                $templateContent = $this->objExamDisplay->showAddEditExaminer($depId, $userId);
         		$this->setVarByRef('templateContent', $templateContent);
         		return 'template_tpl.php';
         		break;
                
            case 'save_examiner':                
                $depId = $this->getParam('d');
                $userId = $this->getParam('u');
                $title = $this->getParam('title');
                $name = $this->getParam('name');
                $surname = $this->getParam('surname');
                $org = $this->getParam('org');
                $email = $this->getParam('email');
                $tel = $this->getParam('tel');
                $ext = $this->getParam('ext');
                $cell = $this->getParam('cell');
                $address = $this->getParam('address');
                if($userId == ''){
                    $this->objExamDb->addUser($depId, $title, $name, $surname, $org, $email, $tel, $ext, $cell, $address);
                }else{
                    $this->objExamDb->editUser($userId, $title, $name, $surname, $org, $email, $tel, $ext, $cell, $address);
                }
                return $this->nextAction('examiners', array(
                    'd' => $depId,
                ), 'examiners');
                break;
                
            case 'delete_examiner':
                $depId = $this->getParam('d');
                $userId = $this->getParam('u');
                $this->objExamDb->deleteUser($userId);
                return $this->nextAction('examiners', array(
                    'd' => $depId,
                ), 'examiners');
                break;
                
            case 'matrix':
                $depId = $this->getParam('d');
                $subjId = $this->getParam('s');
                $year = $this->getParam('y');
                $templateContent = $this->objExamDisplay->showMatrix($depId, $subjId, $year);
         		$this->setVarByRef('templateContent', $templateContent);
         		return 'template_tpl.php';
         		break;

            case 'edit_matrix':
                $depId = $this->getParam('d');
                $subjId = $this->getParam('s');
                $year = $this->getParam('y');
                $templateContent = $this->objExamDisplay->showEditMatrix($depId, $subjId, $year);
         		$this->setVarByRef('templateContent', $templateContent);
         		return 'template_tpl.php';
         		break;
         		
            case 'save_matrix':
                $depId = $this->getParam('d');
                $subjId = $this->getParam('s');
                $year = $this->getParam('y');
                $first = $this->getParam('first');
                $second = $this->getParam('second');
                $moderate = $this->getParam('moderate');
                $alternate = $this->getParam('alternate');
                $remark = $this->getParam('remarking');
                $firstRemark = $this->getParam('text_first');
                $secondRemark = $this->getParam('text_second');
                $moderateRemark = $this->getParam('text_moderate');
                $alternateRemark = $this->getParam('text_alternate');
                $remarkingRemark = $this->getParam('text_remarking');
                if($first != ''){
                    $this->objExamDb->updateFirst($depId, $subjId, $year, $first, $firstRemark);
                }
                if($second != ''){
                    $this->objExamDb->updateSecond($depId, $subjId, $year, $second, $secondRemark);
                }
                if($moderate != ''){
                    $this->objExamDb->updateModerate($depId, $subjId, $year, $moderate, $moderateRemark);
                }
                if($alternate != ''){
                    $this->objExamDb->updateAlternate($depId, $subjId, $year, $alternate, $alternateRemark);
                }
                if($remark != ''){
                    $this->objExamDb->updateRemarking($depId, $subjId, $year, $remark, $remarkingRemark);
                }
                return $this->nextAction('matrix', array(
                    'd' => $depId,
                    's' => $subjId,
                    'y' => $year,
                ), 'examiners');
                break;
                
            case 'delete_matrix':
                $depId = $this->getParam('d');
                $subjId = $this->getParam('s');
                $year = $this->getParam('y');
                $this->objExamDb->deleteMatrix($depId, $subjId, $year);
                return $this->nextAction('matrix', array(
                    'd' => $depId,
                    's' => $subjId,
                    'y' => $year,
                ), 'examiners');
                break;

 			default:
                return $this->nextAction('faculties', array(), 'examiners');
                break;
        }
    }
}
?>