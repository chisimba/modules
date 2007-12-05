<?php
// security check - must be included in all scripts
if ( !$GLOBALS['kewl_entry_point_run'] ) {
    die( "You cannot view this page directly" );
} 
// end security check
/**
 * The tutorial controller manages the tutorial module
 * @author Kevin Cyster 
 * @copyright 2007, University of the Western Cape & AVOIR Project
 * @license GNU GPL
 * @package tutorials
 */

class tutorials extends controller {

    /**
    * @var object $objLanguage: The language class in the language module
    * @access public
    */
    public $objLangauge;
    
    /**
    * @var object $objTutDisplay: The display class in the tutorials module
    * @access public
    */
    public $objTutDisplay;
    
    /**
    * @var object $objTutDb: The dbtutorials class in the tutorials module
    * @access public
    */
    public $objTutDb;
        
    /**
    * @var object $objModules: The modules class in the modulecatalogue module
    * @access public
    */
    public $objModules;

    /**
    * Method to initialise the controller
    * 
    * @access public
    * @return void
    */
    public function init()
    {
        $this->objLanguage = $this->getObject( 'language', 'language' );
        $this->objTutDisplay = $this->newObject('tutorialsdisplay', 'tutorials');
        $this->objTutDb = $this->newObject('dbtutorials', 'tutorials');        
        $this->objModules = $this->getObject('modules', 'modulecatalogue');
    }
    
    /**
    * Method the engine uses to kickstart the module
    * 
    * @access public
    * @param string $action: The action to be performed
    * @return void
    */
    function dispatch( $action )
    {
        switch($action){
         	case 'show_home':
         		$templateContent = $this->objTutDisplay->showHome();
         		$this->setVarByRef('templateContent', $templateContent);
         		return 'template_tpl.php';
         		break;
         		
        	case 'add_tutorial':
        		$templateContent = $this->objTutDisplay->showAddTutorial();
         		$this->setVarByRef('templateContent', $templateContent);
         		return 'template_tpl.php';
         		break;
         		
        	case 'add_tutorial_update':
        		$name = $this->getParam('name');
        		$type = $this->getParam('type');
        		$description = $this->getParam('description');
        		$percentage = $this->getParam('percentage');
        		$answerOpen = $this->getParam('answer_open');
        		$answerClose = $this->getParam('answer_close');
        		$markingClose = $this->getParam('mark_close');
        		$moderationClose = $this->getParam('moderate_close');
        		$nameSpace = $this->objTutDb->addTutorial($name, $type, $description, $percentage, $answerOpen, $answerClose, $markingClose, $moderationClose);
                return $this->nextAction('view_tutorial', array(
                    'id' => $nameSpace,
                ), 'tutorials');
        		break;
        		
        	case 'edit_tutorial':
	        	$tNameSpace = $this->getParam('id');
        		$templateContent = $this->objTutDisplay->showEditTutorial($tNameSpace);
         		$this->setVarByRef('templateContent', $templateContent);
         		return 'template_tpl.php';
         		break;
         		
        	case 'edit_tutorial_update':
        		$tNameSpace = $this->getParam('id');
        		$name = $this->getParam('name');
        		$type = $this->getParam('type');
        		$description = $this->getParam('description');
        		$percentage = $this->getParam('percentage');
        		$answerOpen = $this->getParam('answer_open');
        		$answerClose = $this->getParam('answer_close');
        		$markingClose = $this->getParam('mark_close');
        		$moderationClose = $this->getParam('moderate_close');
        		$mark = $this->getParam('mark');
        		$nameSpace = $this->objTutDb->editTutorial($tNameSpace, $name, $type, $description, $percentage, $answerOpen, $answerClose, $markingClose, $moderationClose, $mark, 0);
				 return $this->nextAction('view_tutorial', array(
                    'id' => $nameSpace,
                ), 'tutorials');
        		break;
        		
        	case 'delete_tutorial':
        		$tNameSpace = $this->getParam('id');
        		$this->objTutDb->deleteTutorial($tNameSpace);
        		return $this->nextAction('show_home', array(), 'tutorials');
        		break;
         		
        	case 'view_tutorial':
        		$tNameSpace = $this->getParam('id');
        		$templateContent = $this->objTutDisplay->showViewTutorial($tNameSpace);
         		$this->setVarByRef('templateContent', $templateContent);
         		return 'template_tpl.php';
         		break;
         		
        	case 'add_question':
        		$tNameSpace = $this->getParam('id');
        		$templateContent = $this->objTutDisplay->showAddQuestion($tNameSpace);
         		$this->setVarByRef('templateContent', $templateContent);
         		return 'template_tpl.php';
         		break;
         		
        	case 'add_question_update':
        		$tNameSpace = $this->getParam('id');
        		$question = $this->getParam('question');
        		$answer = $this->getParam('answer');
        		$worth = $this->getParam('worth');
        		$value = $this->getParam('value');
        		$this->objTutDb->addQuestion($tNameSpace, $question, $answer, $worth, $value);
        		return $this->nextAction('view_tutorial', array(
					'id' => $nameSpace,
				), 'tutorials');
        		break;
        		
        	case 'edit_question':
        		$qNameSpace = $this->getParam('id');
        		$templateContent = $this->objTutDisplay->showEditQuestion($qNameSpace);
         		$this->setVarByRef('templateContent', $templateContent);
         		return 'template_tpl.php';
         		break;
         		
        	case 'edit_question_update':
        		$tNameSpace = $this->getParam('tutId');
        		$qNameSpace = $this->getParam('id');
        		$question = $this->getParam('question');
        		$answer = $this->getParam('answer');
        		$worth = $this->getParam('worth');
        		$order = $this->getParam('order');
        		$value = $this->getParam('value');
        		$return = $this->getParam('return');
        		$this->objTutDb->editQuestion($tNameSpace, $qNameSpace, $question, $answer, $order, $worth, $value, 0);
        		if($return == 'answers'){
        			return $this->nextAction('view_answers', array(
						'id' => $tNameSpace,
						'qNum' => $order,
					), 'tutorials');
        		}else{
        			return $this->nextAction('view_tutorial', array(
						'id' => $tNameSpace,
					), 'tutorials');
				}
        		break;
        		
        	case 'delete_question':
        		$tNameSpace = $this->getParam('tutId');
        		$qNameSpace = $this->getParam('id');
        		$this->objTutDb->deleteQuestion($qNameSpace);
        		return $this->nextAction('view_tutorial', array(
					'id' => $tNameSpace,
				), 'tutorials');
				break;

        	case 'import_questions':
        		$tNameSpace = $this->getParam('id');
        		$error = $this->getParam('error', FALSE);
        		$templateContent = $this->objTutDisplay->showImportQuestions($tNameSpace, $error);
         		$this->setVarByRef('templateContent', $templateContent);
         		return 'template_tpl.php';
         		break;
         		
        	case 'import_questions_update':
        		$tNameSpace = $this->getParam('id');
        		$overwrite = $this->getParam('overwrite');
                $file = $_FILES;
				if(!isset($file['import'])){
                    return $this->nextAction('import_questions', array(
                        'id' => $tNameSpace,
                        'error' => 1,
                    ), 'tutorials');
				}elseif($file['import']['error'] != 0){
                    return $this->nextAction('import_questions', array(
                        'id' => $tNameSpace,
                        'error' => $file['import']['error'],
                    ), 'tutorials');
                }elseif(substr($file['import']['name'], -3) != 'csv'){
                    return $this->nextAction('import_questions', array(
                        'id' => $tNameSpace,
                        'error' => 5,
                    ), 'tutorials');
                }
                $this->objTutDb->importQuestions($file, $tNameSpace, $overwrite);
                return $this->nextAction('view_tutorial', array(
                    'id' => $tNameSpace,
                ), 'tutorials');
                break;
                
            case 'reorder_questions':
            	$tNameSpace = $this->getParam('id');
            	$list = $this->getParam('sortorder');
            	$list = str_replace('list[]=', '', $list);
            	$list = str_replace('*', '_', $list);
            	$list = explode('&', $list);
				$this->objTutDb->reorderQuestions($list);
                return $this->nextAction('view_tutorial', array(
                    'id' => $tNameSpace,
                ), 'tutorials');
                break;
                
            case 'answer_tutorial':
        		$tNameSpace = $this->getParam('id');
        		$qNum = $this->getParam('qNum', 1);
        		$templateContent = $this->objTutDisplay->showTakeTutorial($tNameSpace, $qNum);
         		$this->setVarByRef('templateContent', $templateContent);
         		return 'template_tpl.php';
         		break;
         		
        	case 'submit_answers':
        		$act = $this->getParam('submit');
        		$qNum = $this->getParam('qNum');
        		$cNum = $this->getParam('cNum');
        		$tNameSpace = $this->getParam('id');
        		$qNameSpace = $this->getParam('qId');
        		$aNameSpace = $this->getParam('aId');
        		if($act == $this->objLanguage->languageText('word_submit')){
				 	foreach($qNameSpace as $key => $line){
						$this->objTutDb->addAnswer($tNameSpace, $line, $this->getParam('answer_'.$key), $aNameSpace[$key]);
					}
					$this->objTutDb->setAnswered($tNameSpace);
					return $this->nextAction('show_home', array(), 'tutorials');
				}elseif($act == $this->objLanguage->languageText('word_next')){
				 	foreach($qNameSpace as $key => $line){
						$this->objTutDb->addAnswer($tNameSpace, $line, $this->getParam('answer_'.$key), $aNameSpace[$key]);
					}
					return $this->nextAction('answer_tutorial', array(
						'id' => $tNameSpace,
						'qNum' => $qNum,
					), 'tutorials');
				}elseif($act == $this->objLanguage->languageText('word_exit')){
				 	foreach($qNameSpace as $key => $line){
						$this->objTutDb->addAnswer($tNameSpace, $line, $this->getParam('answer_'.$key), $aNameSpace[$key]);
					}
					return $this->nextAction('show_home', array(), 'tutorials');
				}elseif($act == $this->objLanguage->languageText('word_previous')){
				 	foreach($qNameSpace as $key => $line){
						$this->objTutDb->addAnswer($tNameSpace, $line, $this->getParam('answer_'.$key), $aNameSpace[$key]);
					}
					return $this->nextAction('answer_tutorial', array(
						'id' => $tNameSpace,
						'qNum' => $cNum,
					), 'tutorials');
				}
				break;  
				
			case 'status_list':
        		$tNameSpace = $this->getParam('id');
        		$templateContent = $this->objTutDisplay->showStatusList($tNameSpace);
         		$this->setVarByRef('templateContent', $templateContent);
         		return 'template_tpl.php';
         		break;         		

            case 'mark_student':
        		$tNameSpace = $this->getParam('id');
        		$qNum = $this->getParam('qNum', 1);
        		$templateContent = $this->objTutDisplay->showMarkByStudent($tNameSpace, $qNum);
         		$this->setVarByRef('templateContent', $templateContent);
         		return 'template_tpl.php';
         		break;

        	case 'submit_student':
        		$act = $this->getParam('submit');
        		$qNum = $this->getParam('qNum');
        		$cNum = $this->getParam('cNum');
        		$tNameSpace = $this->getParam('id');
        		$aNameSpace = $this->getParam('aId');
        		$mark = $this->getParam('mark');
        		$comment = $this->getParam('comment');
        		if($act == $this->objLanguage->languageText('word_submit')){
        		 	$this->objTutDb->addPeerMark($aNameSpace, $mark, $comment);
        		 	$this->objTutDb->setMarked($tNameSpace);
        		 	$this->objTutDb->updateResults($tNameSpace);
					return $this->nextAction('show_home', array(), 'tutorials');
				}elseif($act == $this->objLanguage->languageText('word_next')){
        		 	$this->objTutDb->addPeerMark($aNameSpace, $mark, $comment);
					return $this->nextAction('mark_student', array(
						'id' => $tNameSpace,
						'qNum' => $qNum,
					), 'tutorials');
				}elseif($act == $this->objLanguage->languageText('word_exit')){
        		 	$this->objTutDb->addPeerMark($aNameSpace, $mark, $comment);
					return $this->nextAction('show_home', array(), 'tutorials');
				}elseif($act == $this->objLanguage->languageText('word_previous')){
        		 	$this->objTutDb->addPeerMark($aNameSpace, $mark, $comment);
					return $this->nextAction('mark_student', array(
						'id' => $tNameSpace,
						'qNum' => $cNum,
					), 'tutorials');
				}
				break;
				
			case 'request_moderation':
        		$tNameSpace = $this->getParam('id');
        		$act = $this->getParam('submit');
        		$qNum = $this->getParam('qNum');
        		$cNum = $this->getParam('cNum');
        		if($act == $this->objLanguage->languageText('word_next')){
        		 	$num = $qNum;
				}elseif($act == $this->objLanguage->languageText('word_previous')){
					$num = $cNum;
				}else{
					$num = 1;
				}        		
        		$templateContent = $this->objTutDisplay->showRequestModeration($tNameSpace, $num);
         		$this->setVarByRef('templateContent', $templateContent);
         		return 'template_tpl.php';
         		break;
         		
        	case 'submit_request':
        		$aNameSpace = $this->getParam('id');
        		$reason = $this->getParam('reason');
        		return $this->objTutDb->submitModerationRequest($aNameSpace, $reason);
				
			case 'review_tutorial':
        		$tNameSpace = $this->getParam('id');
        		$act = $this->getParam('submit');
        		$qNum = $this->getParam('qNum');
        		$cNum = $this->getParam('cNum');
        		if($act == $this->objLanguage->languageText('word_next')){
        		 	$num = $qNum;
				}elseif($act == $this->objLanguage->languageText('word_previous')){
					$num = $cNum;
				}else{
					$num = 1;
				}        		
        		$templateContent = $this->objTutDisplay->showReviewTutorial($tNameSpace, $num);
         		$this->setVarByRef('templateContent', $templateContent);
         		return 'template_tpl.php';
         		break;
         		
         	case 'moderate_tutorial':
         		$tNameSpace = $this->getParam('id');
        		$templateContent = $this->objTutDisplay->showModerateTutorial($tNameSpace);
         		$this->setVarByRef('templateContent', $templateContent);
         		return 'template_tpl.php';
         		break;
         		
        	case 'submit_moderation':
        		$act = $this->getParam('submit');
        		$tNameSpace = $this->getParam('id');
        		$aNameSpace = $this->getParam('aId');
        		$studentId = $this->getParam('sId');
        		$mark = $this->getParam('mark');
        		$count = $this->getParam('count');
        		$comment = $this->getParam('comment');
        		if($act == $this->objLanguage->languageText('word_submit')){
        		 	$this->objTutDb->addModeratorMark($aNameSpace, $mark, $comment, TRUE);
        		 	$this->objTutDb->setMarker($tNameSpace, $studentId, TRUE);
        		 	$this->objTutDb->updateResults($tNameSpace, TRUE, $studentId);
					if($count != 1){
						return $this->nextAction('moderate_tutorial', array(
							'id' => $tNameSpace,
						), 'tutorials');
					}
				}elseif($act == $this->objLanguage->languageText('word_exit')){
        		 	$this->objTutDb->addModeratorMark($aNameSpace, $mark, $comment, FALSE);
				}
				return $this->nextAction('view_tutorial', array(
					'id' => $tNameSpace,
				), 'tutorials');
				break;
				
            case 'marking_list':
        		$tNameSpace = $this->getParam('id');
        		$templateContent = $this->objTutDisplay->showMarkingList($tNameSpace);
         		$this->setVarByRef('templateContent', $templateContent);
         		return 'template_tpl.php';
         		break;

            case 'mark_tutorial':
        		$tNameSpace = $this->getParam('id');
        		$studentId = $this->getParam('sId');
        		$qNum = $this->getParam('qNum', 1);
        		$templateContent = $this->objTutDisplay->showMarkByLecturer($tNameSpace, $qNum, $studentId);
         		$this->setVarByRef('templateContent', $templateContent);
         		return 'template_tpl.php';
         		break;

        	case 'submit_lecturer':
        		$act = $this->getParam('submit');
        		$qNum = $this->getParam('qNum');
        		$cNum = $this->getParam('cNum');
        		$tNameSpace = $this->getParam('id');
        		$aNameSpace = $this->getParam('aId');
        		$studentId = $this->getParam('sId');
        		$mark = $this->getParam('mark');
        		$comment = $this->getParam('comment');
        		if($act == $this->objLanguage->languageText('word_submit')){
        		 	$this->objTutDb->addModeratorMark($aNameSpace, $mark, $comment, TRUE);
        		 	$this->objTutDb->setMarker($tNameSpace, $studentId, TRUE);
        		 	$this->objTutDb->updateResults($tNameSpace, TRUE, $studentId);
					return $this->nextAction('marking_list', array(
						'id' => $tNameSpace,
					), 'tutorials');
				}elseif($act == $this->objLanguage->languageText('word_next')){
        		 	$this->objTutDb->addModeratorMark($aNameSpace, $mark, $comment, TRUE);
					return $this->nextAction('mark_tutorial', array(
						'id' => $tNameSpace,
						'sId' => $studentId,
						'qNum' => $qNum,
					), 'tutorials');
				}elseif($act == $this->objLanguage->languageText('word_exit')){
        		 	$this->objTutDb->addModeratorMark($aNameSpace, $mark, $comment, FALSE);
					return $this->nextAction('marking_list', array(
						'id' => $tNameSpace,
					), 'tutorials');
				}elseif($act == $this->objLanguage->languageText('word_previous')){
        		 	$this->objTutDb->addModeratorMark($aNameSpace, $mark, $comment, TRUE);
					return $this->nextAction('mark_tutorial', array(
						'id' => $tNameSpace,
						'sId' => $studentId,
						'qNum' => $cNum,
					), 'tutorials');
				}
				break;
				
			case 'show_instructions':
        		$templateContent = $this->objTutDisplay->showInstructions();
         		$this->setVarByRef('templateContent', $templateContent);
         		return 'template_tpl.php';
         		break;
				
			case 'add_instructions':
        		$templateContent = $this->objTutDisplay->showAddInstructions();
         		$this->setVarByRef('templateContent', $templateContent);
         		return 'template_tpl.php';
         		break;
				
			case 'add_instructions_update':
				$instructions = $this->getParam('instructions');
				$this->objTutDb->addInstructions($instructions);
         		return $this->nextAction('show_instructions', array(), 'tutorials');
         		break;
				
			case 'edit_instructions':
        		$templateContent = $this->objTutDisplay->showEditInstructions();
         		$this->setVarByRef('templateContent', $templateContent);
         		return 'template_tpl.php';
         		break;
				
			case 'edit_instructions_update':
				$instructions = $this->getParam('instructions');
				$this->objTutDb->updateInstructions($instructions);
         		return $this->nextAction('show_instructions', array(), 'tutorials');
         		break;
         		
        	case 'delete_instructions':
				$this->objTutDb->deleteInstructions();
        		return $this->nextAction('show_instructions', array(), 'tutorials');
        		break;
        		
        	case 'view_answers':
        		$tNameSpace = $this->getParam('id');
        		$qNum = $this->getParam('qNum', 1);
        		$aNum = $this->getParam('aNum', 1);
        		$templateContent = $this->objTutDisplay->showAnswers($tNameSpace, $qNum, $aNum);
         		$this->setVarByRef('templateContent', $templateContent);
         		return 'template_tpl.php';
         		break;
 
        	case 'late_submissions':
        		$tNameSpace = $this->getParam('id');
        		$studentId = $this->getParam('sId', NULL);
        		$templateContent = $this->objTutDisplay->showLateSubmissions($tNameSpace, $studentId);
         		$this->setVarByRef('templateContent', $templateContent);
         		return 'template_tpl.php';
         		break;
         		
        	case 'submit_late':
        		$tNameSpace = $this->getParam('id');
        		$studentId = $this->getParam('sId');
        		$answer = $this->getParam('answer_close');
        		$mark = $this->getParam('mark_close');
        		$moderate = $this->getParam('moderate_close');
        		$action = $this->getParam('cancel', NULL);
        		if($action != NULL){
					return $this->nextAction('late_submissions', array(
						'id' => $tNameSpace,
					), 'tutorials');
				}
				$this->objTutDb->addLateSubmission($tNameSpace, $studentId, $answer, $mark, $moderate);
				return $this->nextAction('late_submissions', array(
					'id' => $tNameSpace,
				), 'tutorials');
				break;
				
			case 'delete_late':
				$tNameSpace = $this->getParam('id');
				$studentId = $this->getParam('sId');
				$this->objTutDb->deleteLateSubmissions($tNameSpace, $studentId);
				return $this->nextAction('late_submissions', array(
					'id' => $tNameSpace,
				), 'tutorials');
				break;
				
 			default:
                return $this->nextAction('show_home', array(), 'tutorials');
                break;
        }
    }
}
?>