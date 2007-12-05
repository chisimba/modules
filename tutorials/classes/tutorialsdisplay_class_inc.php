<?php
/* ----------- templates class extends object ----------*/

// security check - must be included in all scripts
if(!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* Templates class for tutorials module
* @author Kevin Cyster
*/

class tutorialsdisplay extends object
{
    /**
    * @var object $objLanguage: The language class of the language module
    * @access private
    */
    private $objLanguage;
     
    /**
    * @var object $objTutDb: The dbtutorials class in the tutorials module
    * @access public
    */
    public $objTutDb;

    /**
    * @var object $objIcon: The geticon class in the htmlelements module
    * @access public
    */
    public $objIcon;

    /**
    * @var object $objFeature: The featurebox class in the navigation module
    * @access public
    */
    public $objFeature;

    /**
    * @var object $objPopup: The windowpop class in the htmlelements module
    * @access public
    */
    public $objPopup;

    /**
    * @var object $objDate: The dateandtime class in the utilities module
    * @access public
    */
    public $objDate;

    /**
    * @var object $objTab: The tabpane class in the htmlelements module
    * @access public
    */
    public $objTab;

    /**
    * @var object $objPopupcal: The datepickajax class in the popupcalendar module
    * @access public
    */
    public $objPopupcal;

    /**
    * @var object $objEditor: The htmlarea class in the htmlelements module
    * @access public
    */
    public $objEditor;

    /**
    * @var object $objContext: The dbcontext class in the context module
    * @access public
    */
    public $objContext;

    /**
    * @var string $contextCode: The active context of the current logged in user
    * @access public
    */
    public $contextCode;

    /**
    * @var object $objUser: The user class in the securities module
    * @access public
    */
    public $objUser;

    /**
    * @var string $userId: The user id of the current logged in user
    * @access public
    */
    public $userId;

    /**
    * @var string $pkId: The primary id of the current logged in user
    * @access public
    */
    public $pkId;

    /**
    * @var string $isLoggedIn: The login status of the user
    * @access public
    */
    public $isLoggedIn;

    /**
    * @var bool $isAdmin: TRUE if the user is in the Site Admin group FALSE if not
    * @access public
    */
    public $isAdmin;

    /**
    * @var object $objGroup: The groupadminmodel class in the groupadmin module
    * @access public
    */
    public $objGroup;
        
    /**
    * @var string $status: The ststus of the current tutorial
    * @access public
    */
    public $status;
        
    /**
    * Method to construct the class
    *
    * @access public
    * @return
    */
    public function init()
    {   
        // load html element classes
        $this->loadClass('htmlheading', 'htmlelements');
        $this->loadClass('htmltable', 'htmlelements');
        $this->loadClass('link', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('dropdown', 'htmlelements');
        $this->loadClass('textarea', 'htmlelements');
        $this->loadClass('button', 'htmlelements');
        $this->loadClass('radio', 'htmlelements');
        $this->loadClass('form', 'htmlelements');
        $this->loadClass('layer', 'htmlelements');
        $this->loadClass('checkbox', 'htmlelements');
		$this->loadClass('tabbedbox', 'htmlelements');
		$this->loadClass('fieldset', 'htmlelements');
		
        // system classes
        $this->objLanguage = $this->getObject('language','language');
        $this->objTutDb = $this->getObject('dbtutorials', 'tutorials');
        $this->objIcon = $this->newObject('geticon', 'htmlelements');
        $this->objFeature = $this->newObject('featurebox', 'navigation');
        $this->objPopup = $this->newObject('windowpop', 'htmlelements');
        $this->objEditor = $this->newObject('htmlarea', 'htmlelements');
        $this->objDate = $this->getObject('dateandtime', 'utilities');
        $this->objUser = $this->getObject('user', 'security');
        $this->objPopupcal = $this->newObject('datepickajax', 'popupcalendar');
        $this->objTab = $this->newObject('tabber', 'htmlelements');
        $this->objContext = $this->getObject('dbcontext', 'context');
        $this->contextCode = $this->objContext->getContextCode();

        $this->objUser = $this->getObject('user', 'security');
        $this->userId = $this->objUser->userId();
        $this->pkId = $this->objUser->PKId($this->userId);
        $this->isLoggedIn = $this->objUser->isLoggedIn();
        $this->isAdmin = $this->objUser->inAdminGroup($this->userId);

        $this->objGroup = $this->getObject('groupadminmodel', 'groupadmin');
    }
    
	/**
    * Method to determin if the user logged in
    *
    * @access private
    * @return bool $isAdmin: TRUE if the user is Logged in | FALSE if not
    */
    private function _isLoggedIn()
    {
		return $this->isLoggedIn;
	}
    
    /**
    * Method to determin if the user is in the Admin group
    *
    * @access private
    * @return bool $isAdmin: TRUE if the user is in the admin group | FALSE if not
    */
    private function _isAdmin()
    {
		return $this->isAdmin;
	}
    
    /**
    * Method to determin if the user is a lecturer of the current context
    *
    * @access private
    * @return bool $isLecturer: TRUE, if user is a lecturer in the current context | FALSE if not
    */
    private function _isLecturer()
    {
		$groupId = $this->objGroup->getLeafId(array(
			$this->contextCode,
			'Lecturers',
		));
		$isLecturer = $this->objGroup->isGroupMember($this->pkId, $groupId);
		
		return $isLecturer;
	}

    /**
    * Method to determin if the user is a student of the current context
    *
    * @access private
    * @return bool $isStudent: TRUE, if user is a student in the current context | FALSE if not
    */
    private function _isStudent()
    {
		$groupId = $this->objGroup->getLeafId(array(
			$this->contextCode,
			'Students',
		));
		$isStudent = $this->objGroup->isGroupMember($this->pkId, $groupId);
		
		return $isStudent;
	}

    /**
    * Method to determin if the user is a guest of the current context
    *
    * @access private
    * @return bool $isGuest: TRUE, if user is a guest in the current context | FALSE if not
    */
    private function _isGuest()
    {
		$groupId = $this->objGroup->getLeafId(array(
			$this->contextCode,
			'Guest',
		));
		$isGuest = $this->objGroup->isGroupMember($this->pkId, $groupId);
		
		return $isGuest;
	}
	
	/**
	* Method to list students of this context
	*
	* @access private
	* @return array|bool $data: An array of user data on success | FALSE on failure
	*/
	private function _listStudents()
	{
		$groupId = $this->objGroup->getLeafId(array(
			$this->contextCode,
			'Students',
		));
		//getGroupUsers($groupId, $fields = null, $filter = null)
		$data = $this->objGroup->getGroupUsers($groupId, NULL, NULL);
		if($data != FALSE){
			return $data;
		}
		return FALSE;
	}
	
	/**
	* Method to show a redirect message on error
	*
	* @access private
	* @return string $str: The output string
	*/
	private function _redirect()
	{
		$lblHeading = $this->objLanguage->languageText('mod_tutorials_name', 'tutorials');
		$lblError = $this->objLanguage->code2Txt('mod_tutorials_accesserror', 'tutorials');
		$lblBack = $this->objLanguage->languageText('word_back');
		$lblBackTitle = $this->objLanguage->languageText('mod_tutorials_titleback', 'tutorials');

		$string = '<b><font class="error">'.$lblError.'</font></b>';
		$objLink = new link($this->uri(array(), '_default'));
		$objLink->link = $lblBack;
		$objLink->title = $lblBackTitle;
		$string .= '<br />'.$objLink->show();

		$str = $this->objFeature->show($lblHeading, $string).'<br />';
		return $str;
	}

	/**
	* Method to show the tutorials home page
	*
	* @access public
	* @return string $str: The output string
	*/
	public function showHome()
	{
		// text elements
		$lblHeading = $this->objLanguage->languageText('mod_tutorials_name', 'tutorials');
		$lblError = $this->objLanguage->code2Txt('mod_tutorials_homeerror', 'tutorials');
		$lblBack = $this->objLanguage->languageText('word_back');
		$lblBackTitle = $this->objLanguage->languageText('mod_tutorials_titleback', 'tutorials');
		
		if($this->_isLecturer() || $this->_isAdmin()){
			$str = $this->_showLecturerHome();
		}elseif($this->_isStudent()){
			$str = $this->_showStudentHome();
		}else{
			$string = '<b><font class="error">'.$lblError.'</font></b>';
			$objLink = new link($this->uri(array(), '_default'));
			$objLink->link = $lblBack;
			$objLink->title = $lblBackTitle;
			$string .= '<br />'.$objLink->show();

			$str = $this->objFeature->show($lblHeading, $string).'<br />';
		}
		return $str;
	}

    /**
    * Method to show the tutorial home page for lecturers
    *
    * @access private
    * @return string $str: The output string
    */
    private function _showLecturerHome()
    {	
		// data to be displayed
		$arrData = $this->objTutDb->listTutorials($this->contextCode);
		
		// text elements
		$lblHeading = $this->objLanguage->languageText('mod_tutorials_name', 'tutorials');
		$lblNoRecords = $this->objLanguage->languageText('phrase_norecordsfound');
		$lblName = $this->objLanguage->languageText('word_name');
		$lblStatus = $this->objLanguage->languageText('word_status');
		$lblPercentage = $this->objLanguage->languageText('word_percentage');
		$lblMark = $this->objLanguage->languageText('phrase_totalmark');
		$lblAdd = $this->objLanguage->languageText('word_add');
		$lblAddTitle = $this->objLanguage->languageText('mod_tutorials_titleadd', 'tutorials');
		$lblEdit = $this->objLanguage->languageText('word_edit');
		$lblDelete = $this->objLanguage->languageText('word_delete');
		$lblEditTitle = $this->objLanguage->languageText('mod_tutorials_titleedittut', 'tutorials');
		$lblDeleteTitle = $this->objLanguage->languageText('mod_tutorials_titledeletetut', 'tutorials');
		$lblDelConfirm = $this->objLanguage->languageText('mod_tutorials_deleteconfirm', 'tutorials');
		$lblBack = $this->objLanguage->languageText('word_back');
		$lblBackTitle = $this->objLanguage->languageText('mod_tutorials_titleback', 'tutorials');
		$lblNameTitle = $this->objLanguage->languageText('mod_tutorials_titlename', 'tutorials');
		$lblInstructionsTitle = $this->objLanguage->languageText('mod_tutorials_titleinstructions', 'tutorials');
		$lblInstructions = $this->objLanguage->languageText('word_instructions');
		
		// links
		$objLink = new link($this->uri(array(
			'action' => 'add_tutorial'
		), 'tutorials'));
		$objLink->link = $lblAdd;
		$objLink->title = $lblAddTitle;
		$string = $objLink->show();
		
		$objLink = new link($this->uri(array(
			'action' => 'show_instructions',
		), 'tutorials'));
		$objLink->link = $lblInstructions;
		$objLink->title = $lblInstructionsTitle;
		$string .= '&#160;<b>|</b>&#160;'.$objLink->show();

		// display table
		$objTable = new htmltable();
		$objTable->id = 'tutorialList';
		$objTable->css_class = 'sorttable';
		$objTable->cellpadding = '4';
		$objTable->row_attributes = 'name="row_'.$objTable->id.'"';
		
		$objTable->startRow();
		$objTable->addCell($lblName, '', '', '', 'heading', '');
		$objTable->addCell($lblStatus, '', '', '', 'heading', '');
		$objTable->addCell($lblPercentage, '', '', '', 'heading', '');
		$objTable->addCell($lblMark, '', '', '', 'heading', '');
		$objTable->addCell('', '', '', '', 'heading', '');
		$objTable->endRow();

		if($arrData == FALSE){
			$objTable->startRow();
			$objTable->addCell($lblNoRecords, '', '', '', 'noRecordsMessage', 'colspan="5"');
			$objTable->endRow();
		}else{
			foreach($arrData as $line){
			 	$name = $line['name'];
			 	$status = $this->_getStatus($line);
			 	$percentage = $line['percentage'];
			 	$mark = $line['total_mark'];
			 	
			 	// name link
				 $objLink = new link($this->uri(array(
				 	'action' => 'view_tutorial',
					'id' => $line['name_space'],
				), 'tutorials'));
				$objLink->link = $name;
				$objLink->title = $lblNameTitle;
				$nameLink = $objLink->show();
				
			 	// edit link
			 	$objLink = new link($this->uri(array(
				 	'action' => 'edit_tutorial',
					'id' => $line['name_space'],
				), 'tutorials'));
				$objLink->link = $lblEdit;
				$objLink->title = $lblEditTitle;
				$links = $objLink->show();
				
			 	// delete link
			 	$objLink = new link($this->uri(array(
					'action' => 'delete_tutorial',
					'id' => $line['name_space'],
				), 'tutorials'));
				$objLink->link = $lblDelete;
				$objLink->title = $lblDeleteTitle;
				$objLink->extra = 'onclick="javascript:if(!confirm(\''.$lblDelConfirm.'\')){return false;}"';
				$links .= '&#160;|&#160;'.$objLink->show();
			 	
				$objTable->startRow();
				$objTable->addCell($nameLink, '', '', '', '', '');
				$objTable->addCell($status, '', '', '', '', '');
				$objTable->addCell($percentage, '', '', '', '', '');
				$objTable->addCell($mark, '', '', '', '', '');
				$objTable->addCell($links, '10%', '', '', '', '');
				$objTable->endRow();
			}		
		}
		$string .= $objTable->show();	
		
		// exit link
		$objLink = new link($this->uri(array(), '_default'));
		$objLink->link = $lblBack;
		$objLink->title = $lblBackTitle;
		$string .= '<br />'.$objLink->show();

		$str = $this->objFeature->show($lblHeading, $string).'<br />';
		
		return $str;
	}
	
    /**
    * Method to show the tutorial home page for lecturers
    *
    * @access private
    * @return string $str: The output string
    */
    private function _showStudentHome()
    {	
		// data to be displayed
		$tData = $this->objTutDb->listTutorials();
		$iData = $this->objTutDb->getInstructions();
		
		// text elements
		$lblHeading = $this->objLanguage->languageText('mod_tutorials_name', 'tutorials');
		$lblNoRecords = $this->objLanguage->languageText('phrase_norecordsfound');
		$lblName = $this->objLanguage->languageText('word_name');
		$lblStatus = $this->objLanguage->languageText('word_status');
		$lblPercentageYear = $this->objLanguage->languageText('mod_tutorials_percentage', 'tutorials');
		$lblPercentage = $this->objLanguage->languageText('word_percentage');
		$lblTotal = $this->objLanguage->languageText('phrase_totalmark');
		$lblBack = $this->objLanguage->languageText('word_back');
		$lblBackTitle = $this->objLanguage->languageText('mod_tutorials_titleback', 'tutorials');
		$lblTakeTitle = $this->objLanguage->languageText('mod_tutorials_titletake', 'tutorials');
		$lblMarkTitle = $this->objLanguage->languageText('mod_tutorials_titlemark', 'tutorials');
		$lblModerateTitle = $this->objLanguage->languageText('mod_tutorials_titlemoderate', 'tutorials');
		$lblViewTitle = $this->objLanguage->languageText('mod_tutorials_titleview', 'tutorials');
		$lblHasSubmitted = $this->objLanguage->languageText('mod_tutorial_hassubmitted', 'tutorials');
		$lblHasMarked = $this->objLanguage->code2Txt('mod_tutorial_hasmarked', 'tutorials');
		$lblNoStudent = $this->objLanguage->code2Txt('mod_tutorial_nostudent', 'tutorials');
		$lblMarkingUnavailable = $this->objLanguage->languageText('mod_tutorial_markingunavailable', 'tutorials');
		$lblModerationUnavailable = $this->objLanguage->languageText('mod_tutorial_moderationunavailable', 'tutorials');
		$lblNoMarked = $this->objLanguage->languageText('mod_tutorial_nomarked', 'tutorials');
		$lblNotMarked = $this->objLanguage->languageText('mod_tutorial_notmarked', 'tutorials');
		$lblAnswer = $this->objLanguage->languageText('word_answer');
		$lblMark = $this->objLanguage->languageText('word_mark');
		$lblModerate = $this->objLanguage->languageText('phrase_requestmoderation');
		$lblView = $this->objLanguage->languageText('word_view');
		$lblReviewUnavailable = $this->objLanguage->languageText('mod_tutorial_reviewunavailable', 'tutorials');
		$lblInstructions = $this->objLanguage->languageText('word_instructions');
		
		$string = '';
		if($iData != FALSE){
			$objTable = new htmltable();
			$objTable->cellpadding = '2';
			$objTable->cellspacing = '2';
			
			$objTable->startRow();
			$objTable->addCell($iData['instructions'], '', '', '', 'even', '');
			$objTable->endRow();
			$displayTable = $objTable->show();
			
			$objTabbedbox = new tabbedbox();
			$objTabbedbox->extra = 'style="padding: 10px;"';
			$objTabbedbox->addTabLabel('<b>'.$lblInstructions.'</b>');
			$objTabbedbox->addBoxContent($displayTable);
			$string = $objTabbedbox->show();
		}
		
		// display table
		$objTable = new htmltable();
		$objTable->cellpadding = '2';
		$objTable->cellspacing = '2';
		
		$objTable->startRow();
		$objTable->addCell($lblName, '', '', '', 'heading', '');
		$objTable->addCell($lblStatus, '', '', '', 'heading', '');
		$objTable->addCell($lblPercentageYear, '', '', '', 'heading', '');
		$objTable->addCell($lblTotal, '', '', '', 'heading', '');
		$objTable->addCell($lblPercentage, '', '', '', 'heading', '');
		$objTable->endRow();

		if($tData == FALSE){
			$objTable->startRow();
			$objTable->addCell($lblNoRecords, '', '', '', 'noRecordsMessage', 'colspan="5"');
			$objTable->endRow();
		}else{
			foreach($tData as $line){
			 	$hasSubmitted = $this->objTutDb->hasSubmitted($line['name_space']);
			 	$hasMarked = $this->objTutDb->hasMarked($line['name_space']);
			 	$rData = $this->objTutDb->getResult($line['name_space']);
				$student = $this->objTutDb->getStudentToMark($line['name_space']);
			 	
			 	$name = $line['name'];
			 	$status = $this->_getStatus($line, $this->userId);
			 	$percentage = $line['percentage'];
			 	$mark = $line['total_mark'];
			 	$result = round(($rData['mark_obtained']/$mark)*100).'%';
			 	
			 	// name link
				if($this->status == 1){
					$link = '';
				}elseif($this->status == 2){
				 	if(!$hasSubmitted){
						$objLink = new link($this->uri(array(
					 		'action' => 'answer_tutorial',
							'id' => $line['name_space'],
						), 'tutorials'));
						$objLink->link = $lblAnswer;
						$objLink->title = $lblTakeTitle;
						$link = $objLink->show();
					}else{
						$link = '<b><font class="warning">'.$lblHasSubmitted.'</font></b>';
					}
				}elseif($this->status == 3){
				 	if($hasSubmitted){
					  	if(!$hasMarked){
						   	if($student != FALSE){				 	 
								$objLink = new link($this->uri(array(
				 					'action' => 'mark_student',
									'id' => $line['name_space'],
								), 'tutorials'));
								$objLink->link = $lblMark;
								$objLink->title = $lblMarkTitle;
								$link = $objLink->show();
							}else{
								$link = '<b><font class="warning">'.$lblNoStudent.'</font></b>';;
							}
						}else{
							$link = '<b><font class="warning">'.$lblHasMarked.'</font></b>';
						}
					}else{
						$link = '<b><font class="warning">'.$lblMarkingUnavailable.'</font></b>';
					}
				}elseif($this->status == 4){
				 	if($hasSubmitted){
				 	 	if((isset($rData['moderator_id']) && !empty($rData['moderator_id'])) || (isset($rData['peer_id']) && !empty($rData['peer_id']))){
							$objLink = new link($this->uri(array(
				 				'action' => 'request_moderation',
								'id' => $line['name_space'],
							), 		'tutorials'));
							$objLink->link = $lblModerate;
							$objLink->title = $lblModerateTitle;
							$link = $objLink->show();
						}else{
							$link = '<b><font class="warning">'.$lblNotMarked.'</font></b>';
						}
					}else{
						$link = '<b><font class="warning">'.$lblModerationUnavailable.'</font></b>';
					}
				}elseif($this->status == 5){
				 	if($hasSubmitted){
						if((isset($rData['moderator_id']) && !empty($rData['moderator_id'])) || (isset($rData['peer_id']) && !empty($rData['peer_id']))){
							$objLink = new link($this->uri(array(
				 				'action' => 'review_tutorial',
								'id' => $line['name_space'],
							), 		'tutorials'));
							$objLink->link = $lblView;
							$objLink->title = $lblViewTitle;
							$link = $objLink->show();
						}else{
							$link = '<b><font class="warning">'.$lblNoMarked.'</font></b>';
						}
					}else{
						$link = '<b><font class="warning">'.$lblReviewUnavailable.'</font></b>';
					}
				}
				
				$objTable->startRow();
				$objTable->addCell($name, '', '', '', '', '');
				$objTable->addCell($status, '', '', '', '', '');
				$objTable->addCell($percentage, '', '', '', '', '');
				$objTable->addCell($mark, '', '', '', '', '');
				if($this->status == 5){
					$objTable->addCell($result, '', '', '', '', '');
				}
				$objTable->endRow();
				$objTable->startRow();
				$objTable->addCell($link, '', '', '', '', 'colspan="4"');
				$objTable->endRow();
			}		
		}
		$string .= $objTable->show();	
		
		// exit link
		$objLink = new link($this->uri(array(), '_default'));
		$objLink->link = $lblBack;
		$objLink->title = $lblBackTitle;
		$string .= '<br />'.$objLink->show();

		$str = $this->objFeature->show($lblHeading, $string).'<br />';
		
		return $str;
	}

	/**
	* Method to show the tutorial status
	*
	* @access private
	* @param array $line: The array of tutorial data
	* @param string $studentId: The id of the student if applicable
	* @reurn string $status: The status of the tutorial
	*/
	private function _getStatus($line, $studentId = NULL)
	{
		// data to be displayed
		if($studentId != NULL && !empty($studentId)){
			$lData = $this->objTutDb->getLateSubmissions($line['name_space'], $studentId);
		}else{
			$lData = FALSE;
		}
		$date = strtotime(date('Y-m-d H:i:s'));
        $openDate = strtotime($line['answer_open_date']);
        if($lData != FALSE){
        	$closeDate = $lData['answer_close_date'];
        	$markDate = $lData['marking_close_date'];
        	$moderateDate = $lData['moderating_close_date'];
		}else{
        	$closeDate = $line['answer_close_date'];
        	$markDate = $line['marking_close_date'];
        	$moderateDate = $line['moderating_close_date'];
        }

		// text elements
		$lblInactive = $this->objLanguage->languageText('word_inactive');
		$lblOpen = $this->objLanguage->languageText('word_open');
		$lblCompleted = $this->objLanguage->languageText('word_completed');
		$array = array(
			'date' => $this->_formatDate($closeDate), 
		);
		$lblAnswerClose = $this->objLanguage->code2Txt('mod_tutorials_answersclosing', 'tutorials', $array);
		$array = array(
			'date' => $this->_formatDate($markDate),
		);
		$lblMarkClose = $this->objLanguage->code2Txt('mod_tutorials_markingclosing', 'tutorials', $array);
		$array = array(
			'date' => $this->_formatDate($moderateDate),
		);
		$lblModerateClose = $this->objLanguage->code2Txt('mod_tutorials_moderatingclosing', 'tutorials', $array);
		
		if($line['type'] == 2){
		 	if($date < $openDate){
				$status = $lblInactive;
				$this->status = 1;
			}elseif($date >= $openDate && $date < strtotime($closeDate)){
				$status = $lblAnswerClose;
				$this->status = 2;
			}elseif($date >= $closeDate && $date < strtotime($markDate)){
				$status = $lblMarkClose;
				$this->status = 3;
			}elseif($date >= $markDate && $date < strtotime($moderateDate)){
				$status = $lblModerateClose;
				$this->status = 4;
			}else{
				$status = $lblCompleted;
				$this->status = 5;
			}			
		}else{
			if($date < $openDate){
				$status = $lblInactive;			
				$this->status = 1;
			}elseif($date >= $openDate && $date < strtotime($closeDate)){
				$status = $lblAnswerClose;
				$this->status = 2;
			}else{
				$status = $lblCompleted;
				$this->status = 5;
			}
		}	
		return $status;
	}
	
	/**
	* Method to format a date
	*
	* @access private
	* @param string $date: The date to be formatted
	* @return string $str: The formatted date string
	*/
	private function _formatDate($date)
	{
		$months = $this->objDate->getMonthsAsArray('3letter');
		
		$arrString = explode(' ', $date);
		$arrDate = explode('-', $arrString[0]);
		
		$str = $arrDate[2].' ';
		$str .= $months[$arrDate[1] - 1].' ';
		$str .= $arrDate[0];
		
        if(isset($arrString[1]) && $arrString[1] != 0){
            $str .= ' '.substr($arrString[1], 0, 5);
        }
        return $str;
	}
	
	/** 
	* Method to show the add tutorial page
	*
	* @access public
	* @return string $str: The output string
	*/
	public function showAddTutorial()
	{
        // redirect
		if(!$this->_isLecturer() || !$this->_isAdmin()){
			return $this->_redirect();
		}
		
		// add  javascript
        $headerParams = $this->getJavascriptFile('tutorials.js', 'tutorials');
        $this->appendArrayVar('headerParams', $headerParams);

		// text elements
		$lblAdd = $this->objLanguage->languageText('word_add');
		$lblTut = $this->objLanguage->languageText('mod_tutorials_tutorial', 'tutorials');
		$lblSelect = $this->objLanguage->languageText('word_select');
		$lblStandard = $this->objLanguage->languageText('word_standard');
		$lblInteractive = $this->objLanguage->languageText('word_interactive');
		$lblName = $this->objLanguage->languageText('word_name');
		$lblType = $this->objLanguage->languageText('word_type');
		$lblDescription = $this->objLanguage->languageText('word_description');
		$lblPercentage = $this->objLanguage->languageText('mod_tutorials_percentage', 'tutorials');
		$lblOpen = $this->objLanguage->languageText('mod_tutorials_dateopen', 'tutorials');
		$lblClose = $this->objLanguage->languageText('mod_tutorials_dateclose', 'tutorials');
		$lblMark = $this->objLanguage->languageText('mod_tutorials_datemark', 'tutorials');
		$lblModerate = $this->objLanguage->languageText('mod_tutorials_datemoderate', 'tutorials');
		$lblSubmit = $this->objLanguage->languageText('word_submit');
		$lblCancel = $this->objLanguage->languageText('word_cancel');
		$lblNameError = $this->objLanguage->languageText('mod_tutorials_requiredname', 'tutorials');		
		$heading = $lblAdd.' '.strtolower($lblTut);
		
		// form elements
		$objInput = new textinput('name', '', '', 68);
		$nameInput = $objInput->show();
		
		$objDrop = new dropdown('type');
		$objDrop->addOption(NULL, '- '.$lblSelect.' -');
		$objDrop->addOption(1, $lblStandard);
		$objDrop->addOption(2, $lblInteractive);
		$objDrop->extra = 'onchange="javascript:toggleDateDisplay(this);"';
		$typeDrop = $objDrop->show();

		$objText = new textarea('description', '', 4, 50);
		$descriptionText = $objText->show();

		$objInput = new textinput('percentage', '', '', '');
		$percentageInput = $objInput->show();
		
        // date inputs
        $openField = $this->objPopupcal->show('answer_open', 'yes', 'no', date('Y-m-d H:i'));
        $closeField = $this->objPopupcal->show('answer_close', 'yes', 'no', date('Y-m-d H:i'));
        $markField = $this->objPopupcal->show('mark_close', 'yes', 'no', date('Y-m-d H:i'));
        $moderateField = $this->objPopupcal->show('moderate_close', 'yes', 'no', date('Y-m-d H:i'));
        
		// form buttons
		$objButton = new button('submit', $lblSubmit);
		$objButton->setToSubmit();
		$submitButton = $objButton->show();
		
		$objButton = new button('cancel', $lblCancel);
		$objButton->extra = 'onclick="javascript:$(\'form_cancelform\').submit();"';
		$cancelButton = $objButton->show();
		
		// display table
		$objTable = new htmltable();
        $objTable->cellpadding = '2';
        $objTable->cellspacing = '2';
        //$objTable->border = '1';
		
		$objTable->startRow();
		$objTable->addCell($lblName, '25%', '', '', '', '');
		$objTable->addCell($nameInput, '', '', '', '', '');
		$objTable->endRow();

		$objTable->startRow();
		$objTable->addCell($lblType, '25%', '', '', '', '');
		$objTable->addCell($typeDrop, '', '', '', '', '');
		$objTable->endRow();
		
		$objTable->startRow();
		$objTable->addCell($lblDescription, '25%', '', '', '', '');
		$objTable->addCell($descriptionText, '', '', '', '', '');
		$objTable->endRow();

		$objTable->startRow();
		$objTable->addCell($lblPercentage, '25%', '', '', '', '');
		$objTable->addCell($percentageInput, '', '', '', '', '');
		$objTable->endRow();

		$objTable->startRow();
		$objTable->addCell($lblOpen, '25%', '', '', '', '');
		$objTable->addCell($openField, '', '', '', '', '');
		$objTable->endRow();

		$objTable->startRow();
		$objTable->addCell($lblClose, '25%', '', '', '', '');
		$objTable->addCell($closeField, '', '', '', '', '');
		$objTable->endRow();
		$displayTable = $objTable->show();

		
		// dates table
		$objTable = new htmltable();
        $objTable->cellpadding = '2';
        $objTable->cellspacing = '2';

		$objTable->startRow();
		$objTable->addCell($lblMark, '25%', '', '', '', '');
		$objTable->addCell($markField, '', '', '', '', '');
		$objTable->endRow();

		$objTable->startRow();
		$objTable->addCell($lblModerate, '25%', '', '', '', '');
		$objTable->addCell($moderateField, '', '', '', '', '');
		$objTable->endRow();
		$datesTable = $objTable->show();

		// dates layer
		$objLayer = new layer();
		$objLayer->id = 'datesDiv';
		$objLayer->display = 'none';
		$objLayer->addToStr($datesTable);
		$datesLayer = $objLayer->show();
		
		// form
		$objForm = new form('addtut', $this->uri(array(
			'action' => 'add_tutorial_update',
		), 'tutorials'));
		$objForm->addToForm($displayTable);
		$objForm->addToForm($datesLayer);
		$objForm->addToForm($submitButton.'&#160;&#160;'.$cancelButton);		
		$objForm->addRule('name', $lblNameError, 'required');
		$form = $objForm->show();
				
		$objForm = new form('cancelform', $this->uri(array(
			'action' => 'show_home',
		), 'tutorials'));
		$hiddenForm = $objForm->show();

		$str = $this->objFeature->show($heading, $form.$hiddenForm).'<br />';
		
		return $str;
	}	

	/** 
	* Method to show the edit tutorial page
	*
	* @access public
	* @param string $tNameSpace: The name space of the tutorial to edit
	* @return string $str: The output string
	*/
	public function showEditTutorial($tNameSpace)
	{
        // redirect
		if(!$this->_isLecturer() || !$this->_isAdmin()){
			return $this->_redirect();
		}
		
        // add  javascript
        $headerParams = $this->getJavascriptFile('tutorials.js', 'tutorials');
        $this->appendArrayVar('headerParams', $headerParams);

		// data to be displayed
		$tData = $this->objTutDb->getTutorial($tNameSpace);
		$name = $tData['name'];
		$type = $tData['type'];
		$description = $tData['description'];
		$percentage = $tData['percentage'];
		$answerOpen = $tData['answer_open_date'];
		$answerClose = $tData['answer_close_date'];
		$markClose = $tData['marking_close_date'];
		$moderateClose = $tData['moderating_close_date'];
		$totalMark = $tData['total_mark'];
		
		// text elements
		$lblEdit = $this->objLanguage->languageText('word_edit');
		$lblTut = $this->objLanguage->languageText('mod_tutorials_tutorial', 'tutorials');
		$lblSelect = $this->objLanguage->languageText('word_select');
		$lblStandard = $this->objLanguage->languageText('word_standard');
		$lblInteractive = $this->objLanguage->languageText('word_interactive');
		$lblName = $this->objLanguage->languageText('word_name');
		$lblType = $this->objLanguage->languageText('word_type');
		$lblDescription = $this->objLanguage->languageText('word_description');
		$lblPercentage = $this->objLanguage->languageText('mod_tutorials_percentage', 'tutorials');
		$lblOpen = $this->objLanguage->languageText('mod_tutorials_dateopen', 'tutorials');
		$lblClose = $this->objLanguage->languageText('mod_tutorials_dateclose', 'tutorials');
		$lblMark = $this->objLanguage->languageText('mod_tutorials_datemark', 'tutorials');
		$lblModerate = $this->objLanguage->languageText('mod_tutorials_datemoderate', 'tutorials');
		$lblSubmit = $this->objLanguage->languageText('word_submit');
		$lblCancel = $this->objLanguage->languageText('word_cancel');
		$lblNameError = $this->objLanguage->languageText('mod_tutorials_requiredname', 'tutorials');		
		$heading = $lblEdit.' '.strtolower($lblTut);
		
		// form elements
		$objInput = new textinput('name', $name, '', 68);
		$nameInput = $objInput->show();
		
		$objDrop = new dropdown('type');
		$objDrop->addOption(NULL, '- '.$lblSelect.' -');
		$objDrop->addOption(1, $lblStandard);
		$objDrop->addOption(2, $lblInteractive);
		$objDrop->setSelected($type);
		$objDrop->extra = 'onchange="javascript:toggleDateDisplay(this);"';
		$typeDrop = $objDrop->show();

		$objText = new textarea('description', $description, 4, 50);
		$descriptionText = $objText->show();

		$objInput = new textinput('percentage', $percentage, '', '');
		$percentageInput = $objInput->show();
		
        // date inputs
        $openField = $this->objPopupcal->show('answer_open', 'yes', 'no', date('Y-m-d H:i', strtotime($answerOpen)));
        $closeField = $this->objPopupcal->show('answer_close', 'yes', 'no', date('Y-m-d H:i', strtotime($answerClose)));
        $markField = $this->objPopupcal->show('mark_close', 'yes', 'no', date('Y-m-d H:i', strtotime($markClose)));
        $moderateField = $this->objPopupcal->show('moderate_close', 'yes', 'no', date('Y-m-d H:i', strtotime($moderateClose)));
        
		// form buttons
		$objButton = new button('submit', $lblSubmit);
		$objButton->setToSubmit();
		$submitButton = $objButton->show();
		
		$objButton = new button('cancel', $lblCancel);
		$objButton->extra = 'onclick="javascript:$(\'form_cancelform\').submit();"';
		$cancelButton = $objButton->show();
		
		// display table
		$objTable = new htmltable();
        $objTable->cellpadding = '2';
        $objTable->cellspacing = '2';
        //$objTable->border = '1';
		
		$objTable->startRow();
		$objTable->addCell($lblName, '25%', '', '', '', '');
		$objTable->addCell($nameInput, '', '', '', '', '');
		$objTable->endRow();

		$objTable->startRow();
		$objTable->addCell($lblType, '25%', '', '', '', '');
		$objTable->addCell($typeDrop, '', '', '', '', '');
		$objTable->endRow();
		
		$objTable->startRow();
		$objTable->addCell($lblDescription, '25%', '', '', '', '');
		$objTable->addCell($descriptionText, '', '', '', '', '');
		$objTable->endRow();

		$objTable->startRow();
		$objTable->addCell($lblPercentage, '25%', '', '', '', '');
		$objTable->addCell($percentageInput, '', '', '', '', '');
		$objTable->endRow();

		$objTable->startRow();
		$objTable->addCell($lblOpen, '25%', '', '', '', '');
		$objTable->addCell($openField, '', '', '', '', '');
		$objTable->endRow();

		$objTable->startRow();
		$objTable->addCell($lblClose, '25%', '', '', '', '');
		$objTable->addCell($closeField, '', '', '', '', '');
		$objTable->endRow();
		$displayTable = $objTable->show();

		
		// dates table
		$objTable = new htmltable();
        $objTable->cellpadding = '2';
        $objTable->cellspacing = '2';

		$objTable->startRow();
		$objTable->addCell($lblMark, '25%', '', '', '', '');
		$objTable->addCell($markField, '', '', '', '', '');
		$objTable->endRow();

		$objTable->startRow();
		$objTable->addCell($lblModerate, '25%', '', '', '', '');
		$objTable->addCell($moderateField, '', '', '', '', '');
		$objTable->endRow();
		$datesTable = $objTable->show();

		// dates layer
		$objLayer = new layer();
		$objLayer->id = 'datesDiv';
		if($type != 2){
			$objLayer->display = 'none';
		}
		$objLayer->addToStr($datesTable);
		$datesLayer = $objLayer->show();
		
		// form
		$objForm = new form('edittut', $this->uri(array(
			'action' => 'edit_tutorial_update',
			'id' => $tNameSpace,
			'mark' => $totalMark,
		), 'tutorials'));
		$objForm->addToForm($displayTable);
		$objForm->addToForm($datesLayer);
		$objForm->addToForm($submitButton.'&#160;&#160;'.$cancelButton);		
		$objForm->addRule('name', $lblNameError, 'required');
		$form = $objForm->show();
				
		$objForm = new form('cancelform', $this->uri(array(
			'action' => 'view_tutorial',
			'id' => $tNameSpace,
		), 'tutorials'));
		$hiddenForm = $objForm->show();

		$str = $this->objFeature->show($heading, $form.$hiddenForm).'<br />';
		
		return $str;
	}	

	/** 
	* Method to show the view tutorial page
	*
	* @access public
	* @param string $tNameSpace: The nameSpace of the tutorial to display
	* @return string $str: The output string
	*/
	public function showViewTutorial($tNameSpace)
	{
        // redirect
		if(!$this->_isLecturer() || !$this->_isAdmin()){
			return $this->_redirect();
		}
		
		// data to be displayed
		$tData = $this->objTutDb->getTutorial($tNameSpace);
		$name = $tData['name'];
		$type = $tData['type'];
		$description = $tData['description'];
		$percentage = $tData['percentage'];
		$totalMark = $tData['total_mark'] < 1 ? 0 : $tData['total_mark'];
		$answerOpen = $tData['answer_open_date'];
		$answerClose = $tData['answer_close_date'];
		$markClose = $tData['marking_close_date'];
		$moderateClose = $tData['moderating_close_date'];
		
		$qData = $this->objTutDb->listQuestions($tNameSpace);
		$mData = $this->objTutDb->getAnswerToModerate($tNameSpace);

		// text elements
		$lblView = $this->objLanguage->languageText('word_view');
		$lblEdit = $this->objLanguage->languageText('word_edit');
		$lblEditTutTitle = $this->objLanguage->languageText('mod_tutorials_titleedittut', 'tutorials');
		$lblEditQuestionTitle = $this->objLanguage->languageText('mod_tutorials_titleeditquestion', 'tutorials');
		$lblDelete = $this->objLanguage->languageText('word_delete');
		$lblDeleteTitle = $this->objLanguage->languageText('mod_tutorials_titledeletequestion', 'tutorials');
		$lblTut = $this->objLanguage->languageText('mod_tutorials_tutorial', 'tutorials');
		$lblStandard = $this->objLanguage->languageText('word_standard');
		$lblInteractive = $this->objLanguage->languageText('word_interactive');
		$lblName = $this->objLanguage->languageText('word_name');
		$lblType = $this->objLanguage->languageText('word_type');
		$lblDescription = $this->objLanguage->languageText('word_description');
		$lblPercentage = $this->objLanguage->languageText('mod_tutorials_percentage', 'tutorials');
		$lblOpen = $this->objLanguage->languageText('mod_tutorials_dateopen', 'tutorials');
		$lblClose = $this->objLanguage->languageText('mod_tutorials_dateclose', 'tutorials');
		$lblMark = $this->objLanguage->languageText('mod_tutorials_datemark', 'tutorials');
		$lblModerate = $this->objLanguage->languageText('mod_tutorials_datemoderate', 'tutorials');
		$lblAddQuestions = $this->objLanguage->languageText('phrase_addquestions');
		$lblAddQuestionsTitle = $this->objLanguage->languageText('mod_tutorials_titleaddquestions', 'tutorials');
		$lblImportQuestions = $this->objLanguage->languageText('phrase_importquestions');
		$lblImportQuestionsTitle = $this->objLanguage->languageText('mod_tutorials_titleimportquestions', 'tutorials');
		$lblTutorial = $this->objLanguage->languageText('word_tutorial');
		$lblQuestions = $this->objLanguage->languageText('word_questions');
		$lblQuestion = $this->objLanguage->languageText('word_question');
		$lblWordMark = $this->objLanguage->languageText('word_mark');
		$lblTotalMark = $this->objLanguage->languageText('phrase_totalmark');
		$lblNo = $this->objLanguage->languageText('word_no');
		$lblNoRecords = $this->objLanguage->languageText('phrase_norecordsfound');
		$heading = $lblView.' '.strtolower($lblTut);
		$lblMore = $this->objLanguage->languageText('word_more');
		$lblDelConfirm = $this->objLanguage->languageText('mod_tutorials_deleteconfirm', 'tutorials');
		$lblBack = $this->objLanguage->languageText('word_back');
		$lblBackTitle = $this->objLanguage->languageText('mod_tutorials_back', 'tutorials');
		$lblDrag = $this->objLanguage->languageText('mod_tutorials_drag', 'tutorials');
		$lblSave = $this->objLanguage->languageText('mod_tutorials_savequestionorder', 'tutorials');
		$lblWarning = $this->objLanguage->languageText('mod_tutorials_warningquestionorder', 'tutorials');
		$lblStudent = $this->objLanguage->code2Txt('word_student');
		$lblStatus = $this->objLanguage->languageText('word_status');
		$lblList = ucfirst($lblStudent).'&#160;'.strtolower($lblStatus);
		$lblListTitle = $this->objLanguage->code2Txt('mod_tutorials_titlelist', 'tutorials');
		$lblModerate = $this->objLanguage->languageText('word_moderate');
		$lblModerateTitle = $this->objLanguage->languageText('mod_tutorials_titlemoderate', 'tutorials');
		$lblMarkTitle = $this->objLanguage->languageText('mod_tutorials_titlemark', 'tutorials');
		$lblMarkTut = $this->objLanguage->languageText('word_mark');
		$lblExportTitle = $this->objLanguage->languageText('mod_tutorials_titleexport', 'tutorials');
		$lblExport = $this->objLanguage->languageText('phrase_exportresults');
		$lblLateTitle = $this->objLanguage->languageText('mod_tutorials_titlelate', 'tutorials');
		$lblLate = $this->objLanguage->languageText('phrase_latesubmissions');
		$lblAnswersTitle = $this->objLanguage->languageText('mod_tutorials_titleanswers', 'tutorials');
		$lblAnswers = $this->objLanguage->languageText('phrase_viewanswers');

		if($type == 2){
			$lblTutType = $lblInteractive;
		}else{
			$lblTutType = $lblStandard;
		}
		
		// tutorial tabbed box
		
		//links
		$objLink = new link($this->uri(array(
			'action' => 'edit_tutorial',
			'id' => $tNameSpace,
		), 'tutorials'));
		$objLink->link = $lblEdit;
		$objLink->title = $lblEditTutTitle;
		$string = $objLink->show();

		$objLink = new link($this->uri(array(
			'action' => 'status_list',
			'id' => $tNameSpace,
		), 'tutorials'));
		$objLink->link = $lblList;
		$objLink->title = $lblListTitle;
		$string .= '&#160;|&#160;'.$objLink->show();

		$objLink = new link($this->uri(array(
			'action' => 'view_answers',
			'id' => $tNameSpace,
		), 'tutorials'));
		$objLink->link = $lblAnswers;
		$objLink->title = $lblAnswersTitle;
		$string .= '&#160;|&#160;'.$objLink->show();

		$objLink = new link($this->uri(array(
			'action' => 'marking_list',
			'id' => $tNameSpace,
		), 'tutorials'));
		$objLink->link = $lblMarkTut;
		$objLink->title = $lblMarkTitle;
		$string .= '&#160;|&#160;'.$objLink->show();

		$objLink = new link($this->uri(array(
			'action' => 'late_submissions',
			'id' => $tNameSpace,
		), 'tutorials'));
		$objLink->link = $lblLate;
		$objLink->title = $lblLateTitle;
		$string .= '&#160;|&#160;'.$objLink->show();

		if($type == 2 && $mData != FALSE){
		 	$objLink = new link($this->uri(array(
				'action' => 'moderate_tutorial',
				'id' => $tNameSpace,
			), 'tutorials'));
			$objLink->link = $lblModerate;
			$objLink->title = $lblModerateTitle;
			$string .= '&#160;|&#160;'.$objLink->show();
		}

		$objLink = new link($this->uri(array(
			'action' => 'export_results',
			'id' => $tNameSpace,
		), 'tutorials'));
		$objLink->link = $lblExport;
		$objLink->title = $lblExportTitle;
		$string .= '&#160;|&#160;'.$objLink->show();

		// display table
		$objTable = new htmltable();
        $objTable->cellpadding = '2';
        $objTable->cellspacing = '2';
		
		$objTable->startRow();
		$objTable->addCell('<b>'.$lblName.'</b>', '30%', '', '', '', '');
		$objTable->addCell($name, '', '', '', '', '');
		$objTable->endRow();

		$objTable->startRow();
		$objTable->addCell('<b>'.$lblType.'</b>', '30%', '', '', '', '');
		$objTable->addCell($lblTutType, '', '', '', '', '');
		$objTable->endRow();
		
		$objTable->startRow();
		$objTable->addCell('<b>'.$lblDescription.'</b>', '30%', 'top', '', '', '');
		$objTable->addCell(nl2br($description), '', '', '', '', '');
		$objTable->endRow();

		$objTable->startRow();
		$objTable->addCell('<b>'.$lblPercentage.'</b>', '30%', '', '', '', '');
		$objTable->addCell($percentage, '', '', '', '', '');
		$objTable->endRow();

		$objTable->startRow();
		$objTable->addCell('<b>'.$lblTotalMark.'</b>', '30%', '', '', '', '');
		$objTable->addCell($totalMark, '', '', '', '', '');
		$objTable->endRow();

		$objTable->startRow();
		$objTable->addCell('<b>'.$lblOpen.'</b>', '30%', '', '', '', '');
		$objTable->addCell($this->_formatDate($answerOpen), '', '', '', '', '');
		$objTable->endRow();

		$objTable->startRow();
		$objTable->addCell('<b>'.$lblClose.'</b>', '30%', '', '', '', '');
		$objTable->addCell($this->_formatDate($answerClose), '', '', '', '', '');
		$objTable->endRow();
		$string .= $objTable->show();

		
		// dates table
		$objTable = new htmltable();
        $objTable->cellpadding = '2';
        $objTable->cellspacing = '2';

		$objTable->startRow();
		$objTable->addCell('<b>'.$lblMark.'</b>', '30%', '', '', '', '');
		$objTable->addCell($this->_formatDate($markClose), '', '', '', '', '');
		$objTable->endRow();

		$objTable->startRow();
		$objTable->addCell('<b>'.$lblModerate.'</b>', '30%', '', '', '', '');
		$objTable->addCell($this->_formatDate($moderateClose), '', '', '', '', '');
		$objTable->endRow();
		$datesTable = $objTable->show();

		// dates layer
		$objLayer = new layer();
		$objLayer->id = 'datesDiv';
		if($type != 2){
			$objLayer->display = 'none';
		}
		$objLayer->addToStr($datesTable);
		$string .= $objLayer->show();
		
		$objTabbedbox = new tabbedbox();
		$objTabbedbox->extra = 'style="padding: 10px;"';
		$objTabbedbox->addTabLabel('<b>'.$lblTutorial.'</b>');
		$objTabbedbox->addBoxContent($string);
		$tabbedboxes = $objTabbedbox->show();

		// Questions tabbed box
		
		//links
		$objLink = new link($this->uri(array(
			'action' => 'add_question',
			'id' => $tNameSpace,
		), 'tutorials'));
		$objLink->link = $lblAddQuestions;
		$objLink->title = $lblAddQuestionsTitle;
		$links = $objLink->show();

		$objLink = new link($this->uri(array(
			'action' => 'import_questions',
			'id' => $tNameSpace,
		), 'tutorials'));
		$objLink->link = $lblImportQuestions;
		$objLink->title = $lblImportQuestionsTitle;
		$links .= '&#160;|&#160;'.$objLink->show();

		// display table
		$objTable = new htmltable();
        $objTable->cellpadding = '2';
        $objTable->cellspacing = '2';
		
		$objTable->startRow();
		$objTable->addCell($lblNo, '5%', '', '', 'heading', '');
		$objTable->addCell($lblQuestion, '', '', '', 'heading', '');
		$objTable->addCell($lblWordMark, '5%', '', '', 'heading', '');
		$objTable->addCell('', '10%', '', '', 'heading', '');
		$objTable->endRow();
		
		if($qData == FALSE){
		 	$str = '';
			$objLayer = new layer();
			$objLayer->cssClass = 'noRecordsMessage';
			$objLayer->addToStr($lblNoRecords);
			$string = $objLayer->show();
		}else{
		 	$str = '';
			 if(count($qData) > 1){
        		// load javascript
				$headerParams = $this->getJavascriptFile('tutorials.js', 'tutorials');
        		$this->appendArrayVar('headerParams', $headerParams);

	        	$body = 'Sortable.create("list", {onUpdate: doUpdate , handle:"handle"});SORTLIST = "list";';
    	    	$this->appendArrayVar('bodyOnLoad', $body);
		            
				// css styles
				$style = '<style type="text/css" media="screen">
					#list {
						list-style-type: none;
					}
					#list li {
						margin:5px;
					}
					#list li div.handle {
			 			background: green;
						padding: 2px;
						border-bottom: 2px solid #fff;
						width: 200px;
						display: inline;
						color: #fff;
						cursor: pointer;
					}
				</style>';
  				$str = $style;
  			}
  		
			$string = '<ul id="list">';
			foreach($qData as $line){
		 	 	$objLink = new link($this->uri(array(
				  	'action' => 'edit_question',
					'id' => $line['name_space'],
				), 'tutorials'));
				$objLink->link = $lblEdit;
				$objLink->title = $lblEditQuestionTitle;
				$qLinks = $objLink->show();
				
		 	 	$objLink = new link($this->uri(array(
				  	'action' => 'delete_question',
					'tutId' => $tNameSpace,
					'id' => $line['name_space'],
				), 'tutorials'));
				$objLink->link = $lblDelete;
				$objLink->title = $lblDeleteTitle;
				$objLink->extra = 'onclick="javascript:if(!confirm(\''.$lblDelConfirm.'\')){return false;}"';

				$qLinks .= '&#160;|&#160;'.$objLink->show();
				
				// replace _ with *
				$id = str_replace('_', '*', $line['name_space']);
				
        		$string .= '<li id="sort_'.$id.'">';
        		
				if(count($qData) > 1){
				 	$objLayer = new layer();
					$objLayer->cssClass = 'handle" ondragstart="return false';
					$objLayer->display = 'inline';
					$objLayer->addToStr($lblDrag);
					$handle = $objLayer->show();
				}
				
				$objTable = new htmltable();
				$objTable->cellspacing = 2;
				$objTable->cellpadding = 2;
				$objTable->startRow();
				$objTable->addCell($line['question'], '', '', '', '', '');
				$objTable->addCell($line['question_value'], '5%', '', '', '', '');
				$objTable->addCell($qLinks, '10%', '', '', '', '');
				if(count($qData) > 1){
					$objTable->addCell($handle, '15%', '', '', '', '');
				}
				$objTable->endRow();
				$string .= $objTable->show();
				
				$string .= '</li>';
			}
			$string .= '</ul>';
			
			// sort order form
			$objInput = new textinput('sortorder', '', 'hidden');
			$sortInput = $objInput->show();
			
			$objButton = new button('save', $lblSave);
			$objButton->setToSubmit();
			$submitButton = $objButton->show();
			
			$warning = '<span class="warning" id="warning">';
			$warning .= $lblWarning;
			$warning .= '</span>';
			
			$objForm = new form('sortform', $this->uri(array(
				'action' => 'reorder_questions',
				'id' => $tNameSpace,
			), 'tutorials'));
			$objForm->addToForm($sortInput.$warning.'<br />'.$submitButton);
			$objForm->extra = 'style="display: none;"';
			$string .= $objForm->show();			
		}

		$objTabbedbox = new tabbedbox();
		$objTabbedbox->extra = 'style="padding: 10px;"';
		$objTabbedbox->addTabLabel('<b>'.$lblQuestions.'</b>');
		$objTabbedbox->addBoxContent($links.$string);
		$tabbedboxes .= $objTabbedbox->show();
		
		// exit link
		$objLink = new link($this->uri(array(
			'action' => 'show_home',
		), 'tutorials'));
		$objLink->link = $lblBack;
		$objLink->title = $lblBackTitle;
		$string = $objLink->show();

		$str .= $this->objFeature->show($heading, $tabbedboxes.$string).'<br />';
		
		return $str;
	}	

	/** 
	* Method to show the add question page
	*
	* @access public
	* @param string $tNameSpace: The nameSpace of the tutorial the question is being added to
	* @return string $str: The output string
	*/
	public function showAddQuestion($tNameSpace)
	{
        // redirect
		if(!$this->_isLecturer() || !$this->_isAdmin()){
			return $this->_redirect();
		}
		
		// text elements
		$lblAdd = $this->objLanguage->languageText('word_add');
		$lblQuestion = $this->objLanguage->languageText('word_question');
		$heading = $lblAdd.'&#160;'.strtolower($lblQuestion);
		$lblWorth = $this->objLanguage->languageText('mod_tutorials_questionworth', 'tutorials');
		$lblAnswer = $this->objLanguage->languageText('mod_tutorials_modelanswer', 'tutorials');
		$lblSubmit = $this->objLanguage->languageText('word_submit');
		$lblCancel = $this->objLanguage->languageText('word_cancel');
		
		// form elements
		$this->objEditor->init('question', '', '', '');
		$this->objEditor->setDefaultToolBarSetWithoutSave();
		$questionArea = $this->objEditor->showFCKEditor();
		
		$this->objEditor->init('answer', '', '', '');
		$this->objEditor->setDefaultToolBarSetWithoutSave();
		$answerArea = $this->objEditor->showFCKEditor();
		
		$objInput = new textinput('worth', '', '', '');
		$worthInput = $objInput->show();		
		
		// form buttons
		$objButton = new button('submit', $lblSubmit);
		$objButton->setToSubmit();
		$submitButton = $objButton->show();
		
		$objButton = new button('cancel', $lblCancel);
		$objButton->extra = 'onclick="javascript:$(\'form_cancelform\').submit();"';
		$cancelButton = $objButton->show();
		
		// display table
		$objTable = new htmltable();
        $objTable->cellpadding = '2';
        $objTable->cellspacing = '2';
		
		$objTable->startRow();
		$objTable->addCell('<b>'.$lblQuestion.': </b><br />'.$questionArea, '', '', '', '', '');
		$objTable->endRow();
		
		$objTable->startRow();
		$objTable->addCell('<b>'.$lblAnswer.':</b><br />'.$answerArea, '', '', '', '', '');
		$objTable->endRow();

		$objTable->startRow();
		$objTable->addCell('<b>'.$lblWorth.':</b><br />'.$worthInput, '', '', '', '', '');
		$objTable->endRow();
		$displayTable = $objTable->show();
		
		// form
		$objForm = new form('addquestion', $this->uri(array(
			'action' => 'add_question_update',
			'id' => $tNameSpace,
		), 'tutorials'));
		$objForm->addToForm($displayTable);
		$objForm->addToForm($submitButton.'&#160;&#160;'.$cancelButton);		
		$form = $objForm->show();
				
		$objForm = new form('cancelform', $this->uri(array(
			'action' => 'view_tutorial',
			'id' => $tNameSpace,
		), 'tutorials'));
		$hiddenForm = $objForm->show();

		$str = $this->objFeature->show($heading, $form.$hiddenForm).'<br />';
		
		return $str;
	}

	/** 
	* Method to show the edit question page
	*
	* @access public
	* @param string $tNameSpace: The nameSpace of the tutorial the question is being added to
	* @return string $str: The output string
	*/
	public function showEditQuestion($qNameSpace)
	{
        // redirect
		if(!$this->_isLecturer() || !$this->_isAdmin()){
			return $this->_redirect();
		}
		
		// Data to be displayed
		$qData = $this->objTutDb->getQuestion($qNameSpace);
		
		// text elements
		$lblEdit = $this->objLanguage->languageText('word_edit');
		$lblQuestion = $this->objLanguage->languageText('word_question');
		$heading = $lblEdit.'&#160;'.strtolower($lblQuestion);
		$lblWorth = $this->objLanguage->languageText('mod_tutorials_questionworth', 'tutorials');
		$lblAnswer = $this->objLanguage->languageText('mod_tutorials_modelanswer', 'tutorials');
		$lblSubmit = $this->objLanguage->languageText('word_submit');
		$lblCancel = $this->objLanguage->languageText('word_cancel');
		
		// form elements
		$this->objEditor->init('question', $qData['question'], '', '');
		$this->objEditor->setDefaultToolBarSetWithoutSave();
		$questionArea = $this->objEditor->showFCKEditor();
		
		$this->objEditor->init('answer', $qData['model_answer'], '', '');
		$this->objEditor->setDefaultToolBarSetWithoutSave();
		$answerArea = $this->objEditor->showFCKEditor();
		
		$objInput = new textinput('worth', $qData['question_value'], '', '');
		$worthInput = $objInput->show();		
		
		// form buttons
		$objButton = new button('submit', $lblSubmit);
		$objButton->setToSubmit();
		$submitButton = $objButton->show();
		
		$objButton = new button('cancel', $lblCancel);
		$objButton->extra = 'onclick="javascript:$(\'form_cancelform\').submit();"';
		$cancelButton = $objButton->show();
		
		// display table
		$objTable = new htmltable();
        $objTable->cellpadding = '2';
        $objTable->cellspacing = '2';
		
		$objTable->startRow();
		$objTable->addCell('<b>'.$lblQuestion.': </b><br />'.$questionArea, '', '', '', '', '');
		$objTable->endRow();
		
		$objTable->startRow();
		$objTable->addCell('<b>'.$lblAnswer.':</b><br />'.$answerArea, '', '', '', '', '');
		$objTable->endRow();

		$objTable->startRow();
		$objTable->addCell('<b>'.$lblWorth.':</b><br />'.$worthInput, '', '', '', '', '');
		$objTable->endRow();
		$displayTable = $objTable->show();
		
		// form
		$objForm = new form('editquestion', $this->uri(array(
			'action' => 'edit_question_update',
			'tutId' => $qData['tutorial_name_space'],
			'id' => $qNameSpace,
			'order' => $qData['question_order'],
			'value' => $qData['question_value'],
		), 'tutorials'));
		$objForm->addToForm($displayTable);
		$objForm->addToForm($submitButton.'&#160;&#160;'.$cancelButton);		
		$form = $objForm->show();
				
		$objForm = new form('cancelform', $this->uri(array(
			'action' => 'view_tutorial',
			'id' => $qData['tutorial_name_space'],
		), 'tutorials'));
		$hiddenForm = $objForm->show();

		$str = $this->objFeature->show($heading, $form.$hiddenForm).'<br />';
		
		return $str;
	}
	
	/**
	* Method to show the import questions page
	*
	* @access public
	* @param string $tNameSpace: The name space of the tutorial to import questions to
	* @param integer $error: The error status of the upload
	* @return string $str: the output string
	*/
	public function showImportQuestions($tNameSpace, $error)
	{
        // redirect
		if(!$this->_isLecturer() || !$this->_isAdmin()){
			return $this->_redirect();
		}
		
		// text elements
		$lblSubmit = $this->objLanguage->languageText('word_submit');
		$lblCancel = $this->objLanguage->languageText('word_cancel');
		$lblOverwrite = $this->objLanguage->languageText('mod_tutorials_overwrite', 'tutorials');
		$lblYes = $this->objLanguage->languageText('word_yes');
		$lblNo = $this->objLanguage->languageText('word_no');
		$heading = $this->objLanguage->languageText('phrase_importquestions');
		$lblTypeError = $this->objLanguage->languageText('mod_tutorials_filetypeerror', 'tutorials');
		$lblSizeError = $this->objLanguage->languageText('mod_tutorials_maxsizeerror', 'tutorials');
		$lblNoFileError = $this->objLanguage->languageText('mod_tutorials_nofileerror', 'tutorials');
		$lblPartialError = $this->objLanguage->languageText('mod_tutorials_partialerror', 'tutorials');
						
		if($error == 1){
			$string = '<font class="error"><b>'.$lblSizeError.'</b></font>';	
		}elseif($error == 3){
			$string = '<font class="error"><b>'.$lblPartialError.'</b></font>';
		}elseif($error == 4){
			$string = '<font class="error"><b>'.$lblNoFileError.'</b></font>';
		}elseif($error == 5){
			$string = '<font class="error"><b>'.$lblTypeError.'</b></font>';
		}else{
			$string = '';
		}
		
		// form elements
		$objRadio = new radio('overwrite');
		$objRadio->addOption(1, '&#160;'.$lblYes);
		$objRadio->addOption(2, '&#160;'.$lblNo);
		$objRadio->setSelected(1);
		$objRadio->setBreakSpace('<br />');
		$objRadio->extra = 'style="vertical-align: middle;"';
		$overwriteRadio = $objRadio->show();
		
		$objInput = new textinput('import', '', 'file', 70);
		$fileInput = $objInput->show();
		
		// form buttons
		$objButton = new button('submit', $lblSubmit);
		$objButton->setToSubmit();
		$submitButton = $objButton->show();
		
		$objButton = new button('cancel', $lblCancel);
		$objButton->extra = 'onclick="javascript:$(\'form_cancelform\').submit();"';
		$cancelButton = $objButton->show();
		
		// display table
		$objTable = new htmltable();
        $objTable->cellpadding = '2';
        $objTable->cellspacing = '2';
		
		$objTable->startRow();
		$objTable->addCell('<b><font class="warning">'.$lblOverwrite.'</font></b><br />'.$overwriteRadio, '', '', '', '', '');
		$objTable->endRow();
		
		$objTable->startRow();
		$objTable->addCell($fileInput, '', '', '', '', '');
		$objTable->endRow();
		
		$displayTable = $objTable->show();

		// form
		$objForm = new form('importquestion', $this->uri(array(
			'action' => 'import_questions_update',
			'id' => $tNameSpace,
		), 'tutorials'));
		$objForm->addToForm($displayTable);
		$objForm->addToForm($submitButton.'&#160;&#160;'.$cancelButton);		
		$objForm->extra = 'enctype="multipart/form-data"';
		$form = $objForm->show();
				
		$objForm = new form('cancelform', $this->uri(array(
			'action' => 'view_tutorial',
			'id' => $tNameSpace,
		), 'tutorials'));
		$hiddenForm = $objForm->show();

		$str = $this->objFeature->show($heading, $string.$form.$hiddenForm).'<br />';
		
		return $str;
	}
	
	/**
	* Method to show the page for the student to take the tutorial
	*
	* @access public
	* @param string $tNameSpace: The name space of the tutorial to take
	* @param integer $qNum: The start number of the questions to get
	* @return string $str: The output string
	*/
	public function showAnswerTutorial($tNameSpace, $qNum)
	{
		// data to be displayed
		$this->objTutDb->checkResult($tNameSpace);
		$tData = $this->objTutDb->getTutorial($tNameSpace);
		$qData = $this->objTutDb->listQuestionsForAnswering($tNameSpace, $qNum);

		// text elelments
		$lblAnswer = $this->objLanguage->languageText('word_answer');
		$lblTutorial = $this->objLanguage->languageText('word_tutorial');
		$lblHeading = $lblAnswer.'&#160;'.strtolower($lblTutorial);
		$lblBack = $this->objLanguage->languageText('word_back');
		$lblBackTitle = $this->objLanguage->languageText('mod_tutorials_back', 'tutorials');
		$lblNoRecords = $this->objLanguage->languageText('phrase_norecordsfound');
		$lblQuestion = $this->objLanguage->languageText('word_question');
		$lblValue = $this->objLanguage->languageText('word_value');
		$lblSubmit = $this->objLanguage->languageText('word_submit');
		$lblNext = $this->objLanguage->languageText('word_next');
		$lblExit = $this->objLanguage->languageText('word_exit');
		$lblCancel = $this->objLanguage->languageText('word_cancel');
		$lblPrevious = $this->objLanguage->languageText('word_previous');
		$lblOf = $this->objLanguage->languageText('word_of');
		
		$heading = $lblHeading.':&#160;'.$tData['name'];
		
		if($qData == FALSE){
			$string = '<b><font class="noRecordsMessage">'.$lblNoRecords.'</font></b><br />';					
		}else{
			$questions = '';
			$lastQuestion = FALSE;
			$hiddenInput = '';
			foreach($qData as $key => $line){
			 	// form elements
				$objInput = new textinput('qId[]', $line['name_space'], 'hidden', '');
				$hiddenInput .= $objInput->show();
				
				$aData = $this->objTutDb->getAnswer($line['name_space']);
				if($aData != FALSE){
					$answer = $aData['answer'];
					$aId = $aData['name_space'];
				}else{
					$answer = '';
					$aId = '';
				}
			
				$objInput = new textinput('aId[]', $aId, 'hidden', '');
				$hiddenInput .= $objInput->show();
				
				$this->objEditor->init('answer_'.$key, $answer, '', '');
				$this->objEditor->setDefaultToolBarSetWithoutSave();
				$answerArea = $this->objEditor->showFCKEditor();
		
				$objTable = new htmltable();
				$objTable->cellspacing = 2;
				$objTable->cellpadding = 2;
				$objTable->startRow();
				$objTable->addCell('<b>'.$lblQuestion.':</b><br />'.nl2br($line['question']), '', '', '', 'odd', '');
				$objTable->endRow();
				$objTable->startRow();
				$objTable->addCell('<b>'.$lblValue.':</b><br />'.nl2br($line['question_value']), '', '', '', 'even', '');
				$objTable->endRow();
				$objTable->startRow();
				$objTable->addCell($answerArea, '', '', '', '', '');
				$objTable->endRow();
				$displayTable = $objTable->show();

				$objTabbedbox = new tabbedbox();
				$objTabbedbox->extra = 'style="padding: 10px;"';
				$objTabbedbox->addTabLabel('<b>'.$lblQuestion.'&#160;'.$line['question_order'].'&#160;'.strtolower($lblOf).'&#160;'.$qData[0]['total'].'</b>');
				$objTabbedbox->addBoxContent($displayTable);
				$questions .= $objTabbedbox->show();
				
				if($line['question_order'] == $qData[0]['total']){
					$lastQuestion = TRUE;
				}
			}
			// buttons
			$objButton = new button('submit', $lblSubmit);
			$objButton->setToSubmit();
			$submitButton = $objButton->show();
			
			$objButton = new button('cancel', $lblCancel);
			$objButton->extra = 'onclick="javascript:$(\'form_cancelform\').submit();"';
			$cancelButton = $objButton->show();
			
			$objButton = new button('submit', $lblExit);
			$objButton->setToSubmit();
			$exitButton = $objButton->show();
			
			$objButton = new button('submit', $lblNext);
			$objButton->setToSubmit();
			$nextButton = $objButton->show();
			
			$objButton = new button('submit', $lblPrevious);
			$objButton->setToSubmit();
			$previousButton = $objButton->show();
			
			if($lastQuestion){
				$buttons = $previousButton.'&#160;'.$submitButton.'&#160;'.$exitButton.'&#160;'.$cancelButton;
			}elseif($qNum == 1){
				$buttons = $nextButton.'&#160;'.$exitButton.'&#160;'.$cancelButton;
			}else{
				$buttons = $previousButton.'&#160;'.$nextButton.'&#160;'.$exitButton.'&#160;'.$cancelButton;
			}
		
			// hidden input
			$cNum = $qNum - 4;
			$objInput = new textinput('cNum', $cNum, 'hidden', '');
			$hiddenInput .= $objInput->show();
			
			$qNum = $qNum + count($qData);
			$objInput = new textinput('qNum', $qNum, 'hidden', '');
			$hiddenInput .= $objInput->show();

			// form
			$objForm = new form('answer', $this->uri(array(
				'action' => 'submit_answers',
				'id' => $tNameSpace,
			), 'tutorials'));
			$objForm->addToForm($questions);
			$objForm->addToForm($hiddenInput);
			$objForm->addToForm($buttons);
			$string = $objForm->show();
		
			$objForm = new form('cancelform', $this->uri(array(
				'action' => 'show_home',
			), 'tutorials'));
			$string .= $objForm->show();
		}
	
		// exit link
		$objLink = new link($this->uri(array(
			'action' => 'show_home',
		), 'tutorials'));
		$objLink->link = $lblBack;
		$objLink->title = $lblBackTitle;
		$string .= $objLink->show();

		$str = $this->objFeature->show($heading, $string).'<br />';
		
		return $str;
	}
	
	/**
	* Method to display a page with list the status of students on this tutorial
	*
	* @access public
	* @param string $tNameSpace: The name space of this tutorial
	* @return string $str: The output string
	*/
	public function showStatusList($tNameSpace)
	{
        // redirect
		if(!$this->_isLecturer() || !$this->_isAdmin()){
			return $this->_redirect();
		}
		
		// load javascript
		$headerParams = $this->getJavascriptFile('new_sorttable.js', 'htmlelements');
        $this->appendArrayVar('headerParams', $headerParams);
        
        // data to be displayed
        $tData = $this->objTutDb->getTutorial($tNameSpace);
        $sData = $this->_listStudents();
		      
        // text elements
		$lblStudent = $this->objLanguage->code2Txt('word_student');
		$lblStatus = $this->objLanguage->languageText('word_status');
		$lblList = ucfirst($lblStudent).'&#160;'.strtolower($lblStatus);
        $lblStudentNo = $this->objLanguage->languageText('phrase_studentnumber');
        $lblName = $this->objLanguage->languageText('word_name');
        $lblSurname = $this->objLanguage->languageText('word_surname');
        $lblPercentage = $this->objLanguage->languageText('word_percentage');
        $lblStatus = $this->objLanguage->languageText('word_status');
        $lblNoRecords = $this->objLanguage->languageText('phrase_norecordsfound');
		$lblBack = $this->objLanguage->languageText('word_back');
		$lblBackTitle = $this->objLanguage->languageText('mod_tutorials_back', 'tutorials');
		$lblAnswersSaved = $this->objLanguage->languageText('mod_tutorials_answerssaved', 'tutorials');
		$lblAnswersSubmitted = $this->objLanguage->languageText('mod_tutorials_answerssubmitted', 'tutorials');
		$lblMarksSaved = $this->objLanguage->languageText('mod_tutorials_markssaved', 'tutorials');
		$lblMarksSubmitted = $this->objLanguage->languageText('mod_tutorials_markssubmitted', 'tutorials');
		$lblDidNotAccess = $this->objLanguage->languageText('mod_tutorials_didnotaccess', 'tutorials');
		$lblDidNotMark = $this->objLanguage->languageText('mod_tutorials_didnotmark', 'tutorials');
		$lblMarkingStatus = $this->objLanguage->languageText('mod_tutorials_markingstatus', 'tutorials');
		$lblMarker = $this->objLanguage->languageText('phrase_markedby');
		$lblModerator = $this->objLanguage->languageText('phrase_moderatedby');
		
		$lblHeading = $lblList.':&#160;'.$tData['name'];
        
        // display table
		$objTable = new htmltable();
        $objTable->id = 'studentList';
        $objTable->css_class = 'sorttable';
        $objTable->cellpadding = '2';
        $objTable->row_attributes = ' name="row_'.$objTable->id.'"';
        
        $objTable->startRow();
        $objTable->addCell($lblStudentNo, '', '', '', 'heading', '');
        $objTable->addCell($lblName, '', '', '', 'heading', '');
        $objTable->addCell($lblSurname, '', '', '', 'heading', '');
        $objTable->addCell($lblPercentage, '', '', '', 'heading', '');
        $objTable->addCell($lblStatus, '', '', '', 'heading', '');
        if($tData['type'] == 2){
        	$objTable->addCell($lblMarkingStatus, '', '', '', 'heading', '');
		}
        $objTable->addCell($lblMarker, '', '', '', 'heading', '');
        if($tData['type'] == 2){
        	$objTable->addCell($lblModerator, '', '', '', 'heading', '');
		}
        $objTable->addCell('', '', '', '', 'heading', '');
        $objTable->endRow();
        
        if($sData == FALSE){
	        $objTable->startRow();
    	    $objTable->addCell($lblNoRecords, '', '', '', 'noRecordsMessage', 'colspan="9"');
        	$objTable->endRow();
		}else{
		 	foreach($sData as $line){
		 	 	// student data
				$rData = $this->objTutDb->getResult($tNameSpace, $line['userid']);
		 	 	if($rData == FALSE){
					$percentage = '0%';
					$aStatus = $lblDidNotAccess;
					$mStatus = $lblDidNotMark;
					$peer = '';
					$moderator = '';
				}else{
					$percentage = round($rData['mark_obtained']/$tData['total_mark']*100).'%';
					if($rData['completed'] == 1){
						$aStatus = $lblAnswersSubmitted;
					}else{
						$aStatus = $lblAnswersSaved;
					}
					if($rData['marked'] == 1){
						$mStatus = $lblMarksSubmitted;
					}else{					 	
					 	if($rData['student_marked'] != ''){
							$mStatus = $lblMarksSaved;
						}else{
							$mStatus = $lblDidNotMark;
						}
					}
					if($rData['peer_id'] != NULL){
						$peer = $this->objUser->fullname($rData['peer_id']);
					}else{
						$peer = '';
					}
					if($rData['moderator_id'] != NULL){
						$moderator = $this->objUser->fullname($rData['moderator_id']);
					}else{
						$moderator = '';
					}
				}
        		$objTable->startRow();
		        $objTable->addCell($line['userid'], '', '', '', '', '');
        		$objTable->addCell($line['firstname'], '', '', '', '', '');
		        $objTable->addCell($line['surname'], '', '', '', '', '');
        		$objTable->addCell($percentage, '', '', '', '', '');
		        $objTable->addCell($aStatus, '', '', '', '', '');
		        if($tData['type'] == 2){
		        	$objTable->addCell($mStatus, '', '', '', '', '');					
				}
				if($tData['type'] == 2){
				 	$objTable->addCell($peer);
				 	$objTable->addCell($moderator);
				}else{
				 	$objTable->addCell($moderator);
				}
        		$objTable->addCell('', '', '', '', '', '');
        		$objTable->endRow();
			}			
		}		
		$string = $objTable->show();	
		
		// exit link
		$objLink = new link($this->uri(array(
			'action' => 'view_tutorial',
			'id' => $tNameSpace,
		), 'tutorials'));
		$objLink->link = $lblBack;
		$objLink->title = $lblBackTitle;
		$string .= '<br />'.$objLink->show();

		$str = $this->objFeature->show($lblHeading, $string).'<br />';
		
		return $str;
	}
	
	/**
	* Method to show the page for marking of the tutorial
	*
	* @access public
	* @param string $tNameSpace: The name space of the tutorial to take
	* @param integer $qNum: The start number of the questions to get
	* @return string $str: The output string
	*/
	public function showMarkByStudent($tNameSpace, $qNum)
	{
		// data to be displayed
		$tData = $this->objTutDb->getTutorial($tNameSpace);
		$qData = $this->objTutDb->getQuestionForMarking($tNameSpace, $qNum);
		$studentId = $this->objTutDb->getStudentToMark($tNameSpace, FALSE);
		$aData = $this->objTutDb->getQuestionAnswer($qData['name_space'], $studentId);
		
		// text elelments
		$lblMark = $this->objLanguage->languageText('word_mark');
		$lblTutorial = $this->objLanguage->languageText('word_tutorial');
		$lblHeading = $lblMark.'&#160;'.strtolower($lblTutorial);
		$lblBack = $this->objLanguage->languageText('word_back');
		$lblBackTitle = $this->objLanguage->languageText('mod_tutorials_back', 'tutorials');
		$lblQuestion = $this->objLanguage->languageText('word_question');
		$lblValue = $this->objLanguage->languageText('word_value');
		$lblSubmit = $this->objLanguage->languageText('word_submit');
		$lblNext = $this->objLanguage->languageText('word_next');
		$lblExit = $this->objLanguage->languageText('word_exit');
		$lblCancel = $this->objLanguage->languageText('word_cancel');
		$lblPrevious = $this->objLanguage->languageText('word_previous');
		$lblModelAnswer = $this->objLanguage->languageText('mod_tutorials_modelanswer', 'tutorials');
		$lblComment = $this->objLanguage->languageText('word_comment');
		$lblMark = $this->objLanguage->languageText('word_mark');
		$lblAnswer = $this->objLanguage->languageText('word_answer');
		$lblOf = $this->objLanguage->languageText('word_of');
		$lblStudent = $this->objLanguage->languageText('word_student');
						
		$heading = $lblHeading.':&#160;'.$tData['name'];
		
		$lastQuestion = FALSE;
		$hiddenInput = '';
		
		// form elements
		$objInput = new textinput('aId', $aData['name_space'], 'hidden', '');
		$hiddenInput .= $objInput->show();
				
		$this->objEditor->init('comment', $aData['peer_comment'], '', '');
		$this->objEditor->setDefaultToolBarSetWithoutSave();
		$commentArea = $this->objEditor->showFCKEditor();
		
		$objDrop = new dropdown('mark');
		for($i = 0; $i <= $qData['question_value']; $i++){
			$objDrop->addOption($i, $i);
		}
		$objDrop->setSelected($aData['peer_mark']);
		$markDrop = $objDrop->show();
		
		$objTable = new htmltable();
		$objTable->cellspacing = 2;
		$objTable->cellpadding = 2;
		$objTable->startRow();
		$objTable->addCell('<b>'.$lblQuestion.':</b><br />'.nl2br($qData['question']), '', '', '', 'odd', '');
		$objTable->endRow();
		$objTable->startRow();
		$objTable->addCell('<b>'.$lblValue.':</b><br />'.nl2br($qData['question_value']), '', '', '', 'even', '');
		$objTable->endRow();
		$objTable->startRow();
		$objTable->addCell('<b>'.$lblModelAnswer.':</b><br />'.nl2br($qData['model_answer']), '', '', '', 'odd', '');
		$objTable->endRow();
		$objTable->startRow();
		$objTable->addCell('<b>'.$lblAnswer.':</b><br />'.nl2br($aData['answer']), '', '', '', 'even', '');
		$objTable->endRow();
		$objTable->startRow();
		$objTable->addCell('<b>'.$lblMark.':</b><br />'.$markDrop, '', '', '', 'odd', '');
		$objTable->endRow();
		$objTable->startRow();
		$objTable->addCell('<b>'.$lblComment.':</b><br />'.$commentArea, '', '', '', '', '');
		$objTable->endRow();
		$displayTable = $objTable->show();

		$objTabbedbox = new tabbedbox();
		$objTabbedbox->extra = 'style="padding: 10px;"';
		$objTabbedbox->addTabLabel('<b>'.$lblQuestion.'&#160;'.$qData['question_order'].'&#160;'.strtolower($lblOf).'&#160;'.$qData['total'].'</b>');
		$objTabbedbox->addBoxContent($displayTable);
		$question = $objTabbedbox->show();
				
		if($qData['question_order'] == $qData['total']){
			$lastQuestion = TRUE;
		}

		// buttons
		$objButton = new button('submit', $lblSubmit);
		$objButton->setToSubmit();
		$submitButton = $objButton->show();
			
		$objButton = new button('cancel', $lblCancel);
		$objButton->extra = 'onclick="javascript:$(\'form_cancelform\').submit();"';
		$cancelButton = $objButton->show();
			
		$objButton = new button('submit', $lblExit);
		$objButton->setToSubmit();
		$exitButton = $objButton->show();
			
		$objButton = new button('submit', $lblNext);
		$objButton->setToSubmit();
		$nextButton = $objButton->show();
			
		$objButton = new button('submit', $lblPrevious);
		$objButton->setToSubmit();
		$previousButton = $objButton->show();
			
		if($lastQuestion){
			$buttons = $previousButton.'&#160;'.$submitButton.'&#160;'.$exitButton.'&#160;'.$cancelButton;
		}elseif($qNum == 1){
			$buttons = $nextButton.'&#160;'.$exitButton.'&#160;'.$cancelButton;
		}else{
			$buttons = $previousButton.'&#160;'.$nextButton.'&#160;'.$exitButton.'&#160;'.$cancelButton;
		}
		
		// hidden input
		$cNum = $qNum - 1;
		$objInput = new textinput('cNum', $cNum, 'hidden', '');
		$hiddenInput .= $objInput->show();
			
		$qNum = $qNum + 1;
		$objInput = new textinput('qNum', $qNum, 'hidden', '');
		$hiddenInput .= $objInput->show();

		// form
		$objForm = new form('answer', $this->uri(array(
			'action' => 'submit_student',
			'id' => $tNameSpace,
		), 'tutorials'));
		$objForm->addToForm($question);
		$objForm->addToForm($hiddenInput);
		$objForm->addToForm($buttons);
		$string = $objForm->show();
		
		$objForm = new form('cancelform', $this->uri(array(
			'action' => 'show_home',
		), 'tutorials'));
		$string .= $objForm->show();
		
		// exit link
		$objLink = new link($this->uri(array(
			'action' => 'show_home',
		), 'tutorials'));
		$objLink->link = $lblBack;
		$objLink->title = $lblBackTitle;
		$string .= $objLink->show();

		$str = $this->objFeature->show($heading, $string).'<br />';
		
		return $str;
	}
	
	/**
	* Method to show the request moderation page
	*
	* @access public
	* @param string $tNameSpace: The name space of the tutorial to request moderation for
	* @param integer $qNum: The start number of the questions to get
	* @return string $str: The output string
	*/
	public function showRequestModeration($tNameSpace, $qNum)
	{
		// add  javascript
        $headerParams = $this->getJavascriptFile('tutorials.js', 'tutorials');
        $this->appendArrayVar('headerParams', $headerParams);

		// data to be displayed
		$tData = $this->objTutDb->getTutorial($tNameSpace);
		$qData = $this->objTutDb->listQuestionsForAnswering($tNameSpace, $qNum);

		// text elelments
		$lblRequestModeration = $this->objLanguage->languageText('phrase_requestmoderation');
		$lblTutorial = $this->objLanguage->languageText('word_tutorial');
		$lblHeading = $lblRequestModeration;
		$lblBack = $this->objLanguage->languageText('word_back');
		$lblBackTitle = $this->objLanguage->languageText('mod_tutorials_back', 'tutorials');
		$lblNoRecords = $this->objLanguage->languageText('phrase_norecordsfound');
		$lblQuestion = $this->objLanguage->languageText('word_question');
		$lblValue = $this->objLanguage->languageText('word_value');
		$lblSubmit = $this->objLanguage->languageText('word_submit');
		$lblNext = $this->objLanguage->languageText('word_next');
		$lblCancel = $this->objLanguage->languageText('word_cancel');
		$lblPrevious = $this->objLanguage->languageText('word_previous');
		$lblModelAnswer = $this->objLanguage->languageText('mod_tutorials_modelanswer', 'tutorials');
		$lblYourAnswer = $this->objLanguage->languageText('mod_tutorials_youranswer', 'tutorials');
		$lblRequest = $this->objLanguage->languageText('mod_tutorials_request', 'tutorials');
		$lblMarker = $this->objLanguage->languageText('word_marker');
		$lblModerator = $this->objLanguage->languageText('word_moderator');
		$lblMark = $this->objLanguage->languageText('word_mark');
		$lblCommentBy = $this->objLanguage->languageText('mod_tutorials_commentby', 'tutorials');
		$lblAllocatedBy = $this->objLanguage->languageText('mod_tutorials_allocatedby', 'tutorials');
		$lblModComment = $lblCommentBy.'&#160;'.strtolower($lblModerator);
		$lblModAllocate = $lblMark.'&#160;'.strtolower($lblAllocatedBy).'&#160;'.strtolower($lblModerator);
		$lblMarkComment = $lblCommentBy.'&#160;'.strtolower($lblMarker);
		$lblMarkAllocate = $lblMark.'&#160;'.strtolower($lblAllocatedBy).'&#160;'.strtolower($lblMarker);
		$lblRequestReason = $this->objLanguage->languageText('mod_tutorials_requestreason', 'tutorials');
		$lblOf = $this->objLanguage->languageText('word_of');
		
		$heading = $lblHeading.':&#160;'.$tData['name'];
		
		if($qData == FALSE){
			$string = '<b><font class="noRecordsMessage">'.$lblNoRecords.'</font></b><br />';					
		}else{
			$questions = '';
			$lastQuestion = FALSE;
			$hiddenInput = '';
			foreach($qData as $key => $line){
				$aData = $this->objTutDb->getQuestionAnswer($line['name_space']);
				
				// display table
				$displayTable = '';
				$objTable = new htmltable();
				$objTable->cellspacing = 2;
				$objTable->cellpadding = 2;
				$objTable->startRow();
				$objTable->addCell('<b>'.$lblQuestion.':</b><br />'.nl2br($line['question']), '', '', '', 'odd', '');
				$objTable->endRow();
				$objTable->startRow();
				$objTable->addCell('<b>'.$lblValue.':</b><br />'.nl2br($line['question_value']), '', '', '', 'even', '');
				$objTable->endRow();
				$objTable->startRow();
				$objTable->addCell('<b>'.$lblModelAnswer.':</b><br />'.nl2br($line['model_answer']), '', '', '', 'odd', '');
				$objTable->endRow();
				$objTable->startRow();
				$objTable->addCell('<b>'.$lblYourAnswer.':</b><br />'.nl2br($aData['answer']), '', '', '', 'even', '');
				$objTable->endRow();
				if($aData['peer_id'] != ''){
					$objTable->startRow();
					$objTable->addCell('<b>'.$lblMarkAllocate.':</b><br />'.nl2br($aData['peer_mark']), '', '', '', 'odd', '');
					$objTable->endRow();
					$objTable->startRow();
					$objTable->addCell('<b>'.$lblMarkComment.':</b><br />'.nl2br($aData['peer_comment']), '', '', '', 'even', '');
					$objTable->endRow();
				}
				if($aData['peer_id'] != $aData['moderator_id']){
					$objTable->startRow();
					$objTable->addCell('<b>'.$lblModAllocate.':</b><br />'.nl2br($aData['moderator_mark']), '', '', '', 'odd', '');
					$objTable->endRow();
					$objTable->startRow();
					$objTable->addCell('<b>'.$lblModComment.':</b><br />'.nl2br($aData['moderator_comment']), '', '', '', 'even', '');
					$objTable->endRow();
				}
				$displayTable .= $objTable->show();
				
				if($aData['request_moderation'] != 1 && $aData['moderation_complete'] != 1){
				 	// form element
				 	$objText = new textarea('request', '', 4, 70);
				 	$requestText = $objText->show();

					$objButton = new button('submit', $lblSubmit);
					$objButton->extra = 'onclick="javascript:submitRequest(\''.$lblRequestReason.'\', \''.$aData['name_space'].'\');"';
					$submitButton = $objButton->show();

				 	$content = '<b><font class="warning">'.$lblRequestReason.'</font></b><br />'.$requestText.'<br />'.$submitButton;
				 	
					$objTabbedbox = new tabbedbox();
					$objTabbedbox->extra = 'style="padding: 10px;"';
					$objTabbedbox->addTabLabel('<b>'.$lblRequest.'</b>');
					$objTabbedbox->addBoxContent($content);
				 	$requestBox = $objTabbedbox->show();
				 	
					$objTable = new htmltable();
					$objTable->cellspacing = 2;
					$objTable->cellpadding = 2;
					$objTable->startRow();
					$objTable->addCell($requestBox, '', '', '', '', '');
					$objTable->endRow();					
					
					$objLayer = new layer();
					$objLayer->id = $aData['name_space'];
					$objLayer->addToStr($objTable->show());
					$displayTable .= $objLayer->show();		
				}

				$objTabbedbox = new tabbedbox();
				$objTabbedbox->extra = 'style="padding: 10px;"';
				$objTabbedbox->addTabLabel('<b>'.$lblQuestion.'&#160;'.$line['question_order'].'&#160;'.strtolower($lblOf).'&#160;'.$qData[0]['total'].'</b>');
				$objTabbedbox->addBoxContent($displayTable);
				$questions .= $objTabbedbox->show();
				
				if($line['question_order'] == $qData[0]['total']){
					$lastQuestion = TRUE;
				}
			}
			// buttons
			$objButton = new button('cancel', $lblCancel);
			$objButton->extra = 'onclick="javascript:$(\'form_cancelform\').submit();"';
			$cancelButton = $objButton->show();
			
			$objButton = new button('submit', $lblNext);
			$objButton->setToSubmit();
			$nextButton = $objButton->show();
			
			$objButton = new button('submit', $lblPrevious);
			$objButton->setToSubmit();
			$previousButton = $objButton->show();
			
			if($lastQuestion){
				$buttons = $previousButton.'&#160;'.$cancelButton;
			}elseif($qNum == 1){
				$buttons = $nextButton.'&#160;'.$cancelButton;
			}else{
				$buttons = $previousButton.'&#160;'.$nextButton.'&#160;'.$cancelButton;
			}
		
			// hidden input
			$cNum = $qNum - 4;
			$objInput = new textinput('cNum', $cNum, 'hidden', '');
			$hiddenInput .= $objInput->show();
			
			$qNum = $qNum + count($qData);
			$objInput = new textinput('qNum', $qNum, 'hidden', '');
			$hiddenInput .= $objInput->show();

			// form
			$objForm = new form('answer', $this->uri(array(
				'action' => 'request_moderation',
				'id' => $tNameSpace,
			), 'tutorials'));
			$objForm->addToForm($questions);
			$objForm->addToForm($hiddenInput);
			$objForm->addToForm($buttons);
			$string = $objForm->show();
		
			$objForm = new form('cancelform', $this->uri(array(
				'action' => 'show_home',
			), 'tutorials'));
			$string .= $objForm->show();
		}
	
		// exit link
		$objLink = new link($this->uri(array(
			'action' => 'show_home',
		), 'tutorials'));
		$objLink->link = $lblBack;
		$objLink->title = $lblBackTitle;
		$string .= $objLink->show();

		$str = $this->objFeature->show($heading, $string).'<br />';
		
		return $str;
	}

	/**
	* Method to show the review tutorial page
	*
	* @access public
	* @param string $tNameSpace: The name space of the tutorial to request moderation for
	* @param integer $qNum: The start number of the questions to get
	* @return string $str: The output string
	*/
	public function showReviewTutorial($tNameSpace, $qNum)
	{
		// data to be displayed
		$tData = $this->objTutDb->getTutorial($tNameSpace);
		$qData = $this->objTutDb->listQuestionsForAnswering($tNameSpace, $qNum);
		$rData = $this->objTutDb->getResult($tNameSpace);
		$score = round(($rData['mark_obtained']/$tData['total_mark'])*100);

		// text elelments
		$lblView = $this->objLanguage->languageText('word_view');
		$lblTutorial = $this->objLanguage->languageText('word_tutorial');
		$lblHeading = $lblView.'&#160;'.strtolower($lblTutorial);
		$lblBack = $this->objLanguage->languageText('word_back');
		$lblBackTitle = $this->objLanguage->languageText('mod_tutorials_back', 'tutorials');
		$lblNoRecords = $this->objLanguage->languageText('phrase_norecordsfound');
		$lblQuestion = $this->objLanguage->languageText('word_question');
		$lblValue = $this->objLanguage->languageText('word_value');
		$lblNext = $this->objLanguage->languageText('word_next');
		$lblCancel = $this->objLanguage->languageText('word_cancel');
		$lblPrevious = $this->objLanguage->languageText('word_previous');
		$lblModelAnswer = $this->objLanguage->languageText('mod_tutorials_modelanswer', 'tutorials');
		$lblYourAnswer = $this->objLanguage->languageText('mod_tutorials_youranswer', 'tutorials');
		$lblMarker = $this->objLanguage->languageText('word_marker');
		$lblModerator = $this->objLanguage->languageText('word_moderator');
		$lblMark = $this->objLanguage->languageText('word_mark');
		$lblCommentBy = $this->objLanguage->languageText('mod_tutorials_commentby', 'tutorials');
		$lblAllocatedBy = $this->objLanguage->languageText('mod_tutorials_allocatedby', 'tutorials');
		$lblMarkComment = $lblCommentBy.'&#160;'.strtolower($lblMarker);
		$lblModComment = $lblCommentBy.'&#160;'.strtolower($lblModerator);
		$lblMarkAllocate = $lblMark.'&#160;'.strtolower($lblAllocatedBy).'&#160;'.strtolower($lblMarker);
		$lblModAllocate = $lblMark.'&#160;'.strtolower($lblAllocatedBy).'&#160;'.strtolower($lblModerator);
		$lblScore = $this->objLanguage->languageText('word_score');
		$lblPercentage = $this->objLanguage->languageText('word_percentage');
		$lblOf = $this->objLanguage->languageText('word_of');

		$heading = $lblHeading.':&#160;'.$tData['name'].'<br />'.$lblScore.':&#160;'.$rData['mark_obtained'].'<br />'.$lblPercentage.':&#160;'.$score.'%';
		
		if($qData == FALSE){
			$string = '<b><font class="noRecordsMessage">'.$lblNoRecords.'</font></b><br />';					
		}else{
			$questions = '';
			$lastQuestion = FALSE;
			$hiddenInput = '';
			foreach($qData as $key => $line){
				$aData = $this->objTutDb->getQuestionAnswer($line['name_space']);
				
				// display table
				$objTable = new htmltable();
				$objTable->cellspacing = 2;
				$objTable->cellpadding = 2;
				$objTable->startRow();
				$objTable->addCell('<b>'.$lblQuestion.':</b><br />'.nl2br($line['question']), '', '', '', 'odd', '');
				$objTable->endRow();
				$objTable->startRow();
				$objTable->addCell('<b>'.$lblValue.':</b><br />'.nl2br($line['question_value']), '', '', '', 'even', '');
				$objTable->endRow();
				$objTable->startRow();
				$objTable->addCell('<b>'.$lblModelAnswer.':</b><br />'.nl2br($line['model_answer']), '', '', '', 'odd', '');
				$objTable->endRow();
				$objTable->startRow();
				$objTable->addCell('<b>'.$lblYourAnswer.':</b><br />'.nl2br($aData['answer']), '', '', '', 'even', '');
				$objTable->endRow();
				if($tData['type'] == 2){
				 	if($aData['peer_id'] != ''){
						$objTable->startRow();
						$objTable->addCell('<b>'.$lblMarkAllocate.':</b><br />'.nl2br($aData['peer_mark']), '', '', '', 'odd', '');
						$objTable->endRow();
				 		$objTable->startRow();
						$objTable->addCell('<b>'.$lblMarkComment.':</b><br />'.nl2br($aData['peer_comment']), '', '', '', 'even', '');
						$objTable->endRow();
					}
					if($aData['peer_id'] != $aData['moderator_id']){
						$objTable->startRow();
						$objTable->addCell('<b>'.$lblModAllocate.':</b><br />'.nl2br($aData['moderator_mark']), '', '', '', 'odd', '');
						$objTable->endRow();
						$objTable->startRow();
						$objTable->addCell('<b>'.$lblModComment.':</b><br />'.nl2br($aData['moderator_comment']), '', '', '', 'even', '');
						$objTable->endRow();
					}
				}else{
					$objTable->startRow();
					$objTable->addCell('<b>'.$lblMarkAllocate.':</b><br />'.nl2br($aData['moderator_mark']), '', '', '', 'odd', '');
					$objTable->endRow();
				 	$objTable->startRow();
					$objTable->addCell('<b>'.$lblMarkComment.':</b><br />'.nl2br($aData['moderator_comment']), '', '', '', 'even', '');
					$objTable->endRow();
				}
				$displayTable = $objTable->show();

				$objTabbedbox = new tabbedbox();
				$objTabbedbox->extra = 'style="padding: 10px;"';
				$objTabbedbox->addTabLabel('<b>'.$lblQuestion.'&#160;'.$line['question_order'].'&#160;'.strtolower($lblOf).'&#160;'.$qData[0]['total'].'</b>');
				$objTabbedbox->addBoxContent($displayTable);
				$questions .= $objTabbedbox->show();
				
				if($line['question_order'] == $qData[0]['total']){
					$lastQuestion = TRUE;
				}
			}
			// buttons
			$objButton = new button('cancel', $lblCancel);
			$objButton->extra = 'onclick="javascript:$(\'form_cancelform\').submit();"';
			$cancelButton = $objButton->show();
			
			$objButton = new button('submit', $lblNext);
			$objButton->setToSubmit();
			$nextButton = $objButton->show();
			
			$objButton = new button('submit', $lblPrevious);
			$objButton->setToSubmit();
			$previousButton = $objButton->show();
			
			if($lastQuestion){
				$buttons = $previousButton.'&#160;'.$cancelButton;
			}elseif($qNum == 1){
				$buttons = $nextButton.'&#160;'.$cancelButton;
			}else{
				$buttons = $previousButton.'&#160;'.$nextButton.'&#160;'.$cancelButton;
			}
		
			// hidden input
			$cNum = $qNum - 4;
			$objInput = new textinput('cNum', $cNum, 'hidden', '');
			$hiddenInput .= $objInput->show();
			
			$qNum = $qNum + count($qData);
			$objInput = new textinput('qNum', $qNum, 'hidden', '');
			$hiddenInput .= $objInput->show();

			// form
			$objForm = new form('answer', $this->uri(array(
				'action' => 'review_tutorial',
				'id' => $tNameSpace,
			), 'tutorials'));
			$objForm->addToForm($questions);
			$objForm->addToForm($hiddenInput);
			$objForm->addToForm($buttons);
			$string = $objForm->show();
		
			$objForm = new form('cancelform', $this->uri(array(
				'action' => 'show_home',
			), 'tutorials'));
			$string .= $objForm->show();
		}
	
		// exit link
		$objLink = new link($this->uri(array(
			'action' => 'show_home',
		), 'tutorials'));
		$objLink->link = $lblBack;
		$objLink->title = $lblBackTitle;
		$string .= $objLink->show();

		$str = $this->objFeature->show($heading, $string).'<br />';
		
		return $str;
	}
	
	/**
	* Method to show the moderate page template
	*
	* @access public
	* @param string $tNameSpace: The tutorial name space
	* @return string $str: The output string
	*/
	public function showModerateTutorial($tNameSpace)
	{
        // redirect
		if(!$this->_isLecturer() || !$this->_isAdmin()){
			return $this->_redirect();
		}
		
		// data to be displayed
		$tData = $this->objTutDb->getTutorial($tNameSpace);
		$aData = $this->objTutDb->getAnswerToModerate($tNameSpace);
		$qData = $this->objTutDb->getQuestion($aData['question_name_space']);
		
		// text elelments
		$lblModerate = $this->objLanguage->languageText('word_moderate');
		$lblTutorial = $this->objLanguage->languageText('word_tutorial');
		$lblHeading = $lblModerate.'&#160;'.strtolower($lblTutorial);
		$lblBack = $this->objLanguage->languageText('word_back');
		$lblBackTitle = $this->objLanguage->languageText('mod_tutorials_back', 'tutorials');
		$lblQuestion = $this->objLanguage->languageText('word_question');
		$lblValue = $this->objLanguage->languageText('word_value');
		$lblSubmit = $this->objLanguage->languageText('word_submit');
		$lblExit = $this->objLanguage->languageText('word_exit');
		$lblCancel = $this->objLanguage->languageText('word_cancel');
		$lblModelAnswer = $this->objLanguage->languageText('mod_tutorials_modelanswer', 'tutorials');
		$lblComment = $this->objLanguage->languageText('word_comment');
		$lblMark = $this->objLanguage->languageText('word_mark');
		$lblAnswer = $this->objLanguage->languageText('word_answer');
		$lblLeft = $this->objLanguage->languageText('mod_tutorials_requestsleft', 'tutorials');
		$lblMarker = $this->objLanguage->languageText('word_marker');
		$lblCommentBy = $this->objLanguage->languageText('mod_tutorials_commentby', 'tutorials');
		$lblAllocatedBy = $this->objLanguage->languageText('mod_tutorials_allocatedby', 'tutorials');
		$lblMarkComment = $lblCommentBy.'&#160;'.strtolower($lblMarker);
		$lblMarkAllocate = $lblMark.'&#160;'.strtolower($lblAllocatedBy).'&#160;'.strtolower($lblMarker);
		$lblStudent = $this->objLanguage->code2Txt('word_student');
						
		$heading = $lblHeading.':&#160;'.$tData['name'].'<br />'.$lblLeft.':&#160;'.$aData['total'];
		
		$string = '<b>'.ucfirst($lblStudent).':&#160;'.$this->objUser->fullname($aData['student_id']).'</b>';
		
		if($aData['peer_id'] != $aData['moderator_id']){
			$comment = $aData['moderator_comment'];
			$mark = $aData['moderator_mark'];		
		}else{
			$comment = '';
			$mark = '';		
		}
	
		// form elements
		$objInput = new textinput('aId', $aData['name_space'], 'hidden', '');
		$hiddenInput = $objInput->show();
				
		$objInput = new textinput('count', $aData['total'], 'hidden', '');
		$hiddenInput .= $objInput->show();
				
		$this->objEditor->init('comment', $comment, '', '');
		$this->objEditor->setDefaultToolBarSetWithoutSave();
		$commentArea = $this->objEditor->showFCKEditor();
		
		$objDrop = new dropdown('mark');
		for($i = 0; $i <= $qData['question_value']; $i++){
			$objDrop->addOption($i, $i);
		}
		$objDrop->setSelected($mark);
		$markDrop = $objDrop->show();
		
		// display table
		$objTable = new htmltable();
		$objTable->cellspacing = 2;
		$objTable->cellpadding = 2;
		$objTable->startRow();
		$objTable->addCell('<b>'.$lblQuestion.':</b><br />'.nl2br($qData['question']), '', '', '', 'odd', '');
		$objTable->endRow();
		$objTable->startRow();
		$objTable->addCell('<b>'.$lblValue.':</b><br />'.nl2br($qData['question_value']), '', '', '', 'even', '');
		$objTable->endRow();
		$objTable->startRow();
		$objTable->addCell('<b>'.$lblModelAnswer.':</b><br />'.nl2br($qData['model_answer']), '', '', '', 'odd', '');
		$objTable->endRow();
		$objTable->startRow();
		$objTable->addCell('<b>'.$lblAnswer.':</b><br />'.nl2br($aData['answer']), '', '', '', 'even', '');
		$objTable->endRow();
		$objTable->startRow();
		$objTable->addCell('<b>'.$lblMarkAllocate.':</b><br />'.$aData['peer_mark'], '', '', '', 'odd', '');
		$objTable->endRow();
		$objTable->startRow();
		$objTable->addCell('<b>'.$lblMarkComment.':</b><br />'.nl2br($aData['peer_comment']), '', '', '', 'even', '');
		$objTable->endRow();
		$objTable->startRow();
		$objTable->addCell('<b>'.$lblMark.':</b><br />'.$markDrop, '', '', '', 'odd', '');
		$objTable->endRow();
		$objTable->startRow();
		$objTable->addCell('<b>'.$lblComment.':</b><br />'.$commentArea, '', '', '', '', '');
		$objTable->endRow();
		$displayTable = $objTable->show();

		$objTabbedbox = new tabbedbox();
		$objTabbedbox->extra = 'style="padding: 10px;"';
		$objTabbedbox->addTabLabel('<b>'.$lblQuestion.'&#160;'.$qData['question_order'].'</b>');
		$objTabbedbox->addBoxContent($displayTable);
		$questions = $objTabbedbox->show();
				
		// buttons
		$objButton = new button('submit', $lblSubmit);
		$objButton->setToSubmit();
		$submitButton = $objButton->show();
			
		$objButton = new button('cancel', $lblCancel);
		$objButton->extra = 'onclick="javascript:$(\'form_cancelform\').submit();"';
		$cancelButton = $objButton->show();
			
		$objButton = new button('submit', $lblExit);
		$objButton->setToSubmit();
		$exitButton = $objButton->show();
			
			
		$buttons = $submitButton.'&#160;'.$exitButton.'&#160;'.$cancelButton;

		// form
		$objForm = new form('moderation', $this->uri(array(
			'action' => 'submit_moderation',
			'id' => $tNameSpace,
			'sId' => $aData['student_id'],
		), 'tutorials'));
		$objForm->addToForm($questions);
		$objForm->addToForm($hiddenInput);
		$objForm->addToForm($buttons);
		$string .= $objForm->show();
		
		$objForm = new form('cancelform', $this->uri(array(
			'action' => 'view_tutorial',
			'id' => $tNameSpace,
		), 'tutorials'));
		$string .= $objForm->show();
		
		// exit link
		$objLink = new link($this->uri(array(
			'action' => 'view_tutorial',
			'id' => $tNameSpace,
		), 'tutorials'));
		$objLink->link = $lblBack;
		$objLink->title = $lblBackTitle;
		$string .= $objLink->show();

		$str = $this->objFeature->show($heading, $string).'<br />';
		
		return $str;
	}

	/**
	* Method to display a page which lists the status of students on this tutorial
	*
	* @access public
	* @param string $tNameSpace: The name space of this tutorial
	* @return string $str: The output string
	*/
	public function showMarkingList($tNameSpace)
	{
        // redirect
		if(!$this->_isLecturer() || !$this->_isAdmin()){
			return $this->_redirect();
		}
		
		// load javascript
		$headerParams = $this->getJavascriptFile('new_sorttable.js', 'htmlelements');
        $this->appendArrayVar('headerParams', $headerParams);
        
        // data to be displayed
        $tData = $this->objTutDb->getTutorial($tNameSpace);
        $sData = $this->_listStudents();
		      
        // text elements
        $lblListStudents = $this->objLanguage->code2txt('phrase_markinglist');
        $lblStudentNo = $this->objLanguage->languageText('phrase_studentnumber');
        $lblName = $this->objLanguage->languageText('word_name');
        $lblSurname = $this->objLanguage->languageText('word_surname');
        $lblPercentage = $this->objLanguage->languageText('word_percentage');
        $lblStatus = $this->objLanguage->languageText('word_status');
        $lblNoRecords = $this->objLanguage->languageText('phrase_norecordsfound');
		$lblBack = $this->objLanguage->languageText('word_back');
		$lblBackTitle = $this->objLanguage->languageText('mod_tutorials_back', 'tutorials');
		$lblAnswersSaved = $this->objLanguage->languageText('mod_tutorials_answerssaved', 'tutorials');
		$lblAnswersSubmitted = $this->objLanguage->languageText('mod_tutorials_answerssubmitted', 'tutorials');
		$lblMarksSaved = $this->objLanguage->languageText('mod_tutorials_markssaved', 'tutorials');
		$lblMarksSubmitted = $this->objLanguage->languageText('mod_tutorials_markssubmitted', 'tutorials');
		$lblDidNotAccess = $this->objLanguage->languageText('mod_tutorials_didnotaccess', 'tutorials');
		$lblDidNotMark = $this->objLanguage->languageText('mod_tutorials_didnotmark', 'tutorials');
		$lblMarkingStatus = $this->objLanguage->languageText('mod_tutorials_markingstatus', 'tutorials');
		$lblMarkTitle = $this->objLanguage->languageText('mod_tutorials_titlemark', 'tutorials');
		$lblMark = $this->objLanguage->languageText('word_mark');
				
		$lblHeading = $lblListStudents.':&#160;'.$tData['name'];
        
        // display table
		$objTable = new htmltable();
        $objTable->id = 'studentList';
        $objTable->css_class = 'sorttable';
        $objTable->cellpadding = '2';
        $objTable->row_attributes = ' name="row_'.$objTable->id.'"';
        
        $objTable->startRow();
        $objTable->addCell($lblStudentNo, '', '', '', 'heading', '');
        $objTable->addCell($lblName, '', '', '', 'heading', '');
        $objTable->addCell($lblSurname, '', '', '', 'heading', '');
        $objTable->addCell($lblPercentage, '', '', '', 'heading', '');
        $objTable->addCell($lblStatus, '', '', '', 'heading', '');
        if($tData['type'] == 2){
        	$objTable->addCell($lblMarkingStatus, '', '', '', 'heading', '');
		}
        $objTable->addCell('', '', '', '', 'heading', '');
        $objTable->endRow();
        
        if($sData == FALSE){
	        $objTable->startRow();
    	    $objTable->addCell($lblNoRecords, '', '', '', 'noRecordsMessage', 'colspan="9"');
        	$objTable->endRow();
		}else{
		 	foreach($sData as $line){
		 	 	// student data
				$rData = $this->objTutDb->getResult($tNameSpace, $line['userid']);

		 	 	if($rData == FALSE){
					$percentage = '0%';
					$aStatus = $lblDidNotAccess;
					$mStatus = $lblDidNotMark;
					$link = '';
				}else{
					$percentage = round($rData['mark_obtained']/$tData['total_mark']*100).'%';
					if($rData['completed'] == 1){
						$aStatus = $lblAnswersSubmitted;
						$objLink = new link($this->uri(array(
							'action' => 'mark_tutorial',
							'id' => $tData['name_space'],
							'sId' => $line['userid'],
						), 'tutorials'));
						$objLink->link = $lblMark;
						$objLink->title = $lblMarkTitle;
						$link = $objLink->show();
					}else{
						$aStatus = $lblAnswersSaved;
						$libk = '';
					}
					if($rData['marked'] == 1){
						$mStatus = $lblMarksSubmitted;
					}else{
					 	$hasMarked = $this->objTutDb->checkHasMarked($tNameSpace, $line['userid']);
					 	if($hasMarked){
							$mStatus = $lblMarksSaved;
						}else{
							$mStatus = $lblDidNotMark;
						}
					}
				}
        		$objTable->startRow();
		        $objTable->addCell($line['userid'], '', '', '', '', '');
        		$objTable->addCell($line['firstname'], '', '', '', '', '');
		        $objTable->addCell($line['surname'], '', '', '', '', '');
        		$objTable->addCell($percentage, '', '', '', '', '');
		        $objTable->addCell($aStatus, '', '', '', '', '');
		        if($tData['type'] == 2){
		        	$objTable->addCell($mStatus, '', '', '', '', '');					
				}
        		$objTable->addCell($link, '', '', '', '', '');
        		$objTable->endRow();
			}			
		}		
		$string = $objTable->show();	
		
		// exit link
		$objLink = new link($this->uri(array(
			'action' => 'view_tutorial',
			'id' => $tNameSpace,
		), 'tutorials'));
		$objLink->link = $lblBack;
		$objLink->title = $lblBackTitle;
		$string .= '<br />'.$objLink->show();

		$str = $this->objFeature->show($lblHeading, $string).'<br />';
		
		return $str;
	}

	/**
	* Method to show the page for marking of the tutorial
	*
	* @access public
	* @param string $tNameSpace: The name space of the tutorial to take
	* @param integer $qNum: The start number of the questions to get
	* @return string $str: The output string
	*/
	public function showMarkByLecturer($tNameSpace, $qNum, $studentId)
	{
		// data to be displayed
        // redirect
		if(!$this->_isLecturer() || !$this->_isAdmin()){
			return $this->_redirect();
		}
		
		// display data
		$tData = $this->objTutDb->getTutorial($tNameSpace);
		$qData = $this->objTutDb->getQuestionForMarking($tNameSpace, $qNum);
		$aData = $this->objTutDb->getQuestionAnswer($qData['name_space'], $studentId);
		
		// text elelments
		$lblMark = $this->objLanguage->languageText('word_mark');
		$lblTutorial = $this->objLanguage->languageText('word_tutorial');
		$lblHeading = $lblMark.'&#160;'.strtolower($lblTutorial);
		$lblBack = $this->objLanguage->languageText('word_back');
		$lblBackTitle = $this->objLanguage->languageText('mod_tutorials_back', 'tutorials');
		$lblQuestion = $this->objLanguage->languageText('word_question');
		$lblValue = $this->objLanguage->languageText('word_value');
		$lblSubmit = $this->objLanguage->languageText('word_submit');
		$lblNext = $this->objLanguage->languageText('word_next');
		$lblExit = $this->objLanguage->languageText('word_exit');
		$lblCancel = $this->objLanguage->languageText('word_cancel');
		$lblPrevious = $this->objLanguage->languageText('word_previous');
		$lblModelAnswer = $this->objLanguage->languageText('mod_tutorials_modelanswer', 'tutorials');
		$lblComment = $this->objLanguage->languageText('word_comment');
		$lblMark = $this->objLanguage->languageText('word_mark');
		$lblAnswer = $this->objLanguage->languageText('word_answer');
		$lblOf = $this->objLanguage->languageText('word_of');
		$lblStudent = $this->objLanguage->code2Txt('word_student');
						
		$heading = $lblHeading.':&#160;'.$tData['name'];
		
		$string = '<b>'.ucfirst($lblStudent).':&#160;'.$this->objUser->fullname($aData['student_id']).'</b>';			

		if($aData['peer_id'] != $aData['moderator_id']){
			$comment = $aData['moderator_comment'];
			$mark = $aData['moderator_mark'];
		}else{
			$comment = '';
			$mark = '';
		}
		$lastQuestion = FALSE;
		$hiddenInput = '';
		
		// form elements
		$objInput = new textinput('aId', $aData['name_space'], 'hidden', '');
		$hiddenInput .= $objInput->show();
				
		$this->objEditor->init('comment', $comment, '', '');
		$this->objEditor->setDefaultToolBarSetWithoutSave();
		$commentArea = $this->objEditor->showFCKEditor();
		
		$objDrop = new dropdown('mark');
		for($i = 0; $i <= $qData['question_value']; $i++){
			$objDrop->addOption($i, $i);
		}
		$objDrop->setSelected($mark);
		$markDrop = $objDrop->show();
		
		// display table
		$objTable = new htmltable();
		$objTable->cellspacing = 2;
		$objTable->cellpadding = 2;
		$objTable->startRow();
		$objTable->addCell('<b>'.$lblQuestion.':</b><br />'.nl2br($qData['question']), '', '', '', 'odd', '');
		$objTable->endRow();
		$objTable->startRow();
		$objTable->addCell('<b>'.$lblValue.':</b><br />'.nl2br($qData['question_value']), '', '', '', 'even', '');
		$objTable->endRow();
		$objTable->startRow();
		$objTable->addCell('<b>'.$lblModelAnswer.':</b><br />'.nl2br($qData['model_answer']), '', '', '', 'odd', '');
		$objTable->endRow();
		$objTable->startRow();
		$objTable->addCell('<b>'.$lblAnswer.':</b><br />'.nl2br($aData['answer']), '', '', '', 'even', '');
		$objTable->endRow();
		$objTable->startRow();
		$objTable->addCell('<b>'.$lblMark.':</b><br />'.$markDrop, '', '', '', 'odd', '');
		$objTable->endRow();
		$objTable->startRow();
		$objTable->addCell('<b>'.$lblComment.':</b><br />'.$commentArea, '', '', '', '', '');
		$objTable->endRow();
		$displayTable = $objTable->show();

		$objTabbedbox = new tabbedbox();
		$objTabbedbox->extra = 'style="padding: 10px;"';
		$objTabbedbox->addTabLabel('<b>'.$lblQuestion.'&#160;'.$qData['question_order'].'&#160;'.strtolower($lblOf).'&#160;'.$qData['total'].'</b>');
		$objTabbedbox->addBoxContent($displayTable);
		$string .= $objTabbedbox->show();
				
		if($qData['question_order'] == $qData['total']){
			$lastQuestion = TRUE;
		}

		// buttons
		$objButton = new button('submit', $lblSubmit);
		$objButton->setToSubmit();
		$submitButton = $objButton->show();
			
		$objButton = new button('cancel', $lblCancel);
		$objButton->extra = 'onclick="javascript:$(\'form_cancelform\').submit();"';
		$cancelButton = $objButton->show();
			
		$objButton = new button('submit', $lblExit);
		$objButton->setToSubmit();
		$exitButton = $objButton->show();
			
		$objButton = new button('submit', $lblNext);
		$objButton->setToSubmit();
		$nextButton = $objButton->show();
			
		$objButton = new button('submit', $lblPrevious);
		$objButton->setToSubmit();
		$previousButton = $objButton->show();
			
		if($lastQuestion){
			$buttons = $previousButton.'&#160;'.$submitButton.'&#160;'.$exitButton.'&#160;'.$cancelButton;
		}elseif($qNum == 1){
			$buttons = $nextButton.'&#160;'.$exitButton.'&#160;'.$cancelButton;
		}else{
			$buttons = $previousButton.'&#160;'.$nextButton.'&#160;'.$exitButton.'&#160;'.$cancelButton;
		}
		
		// hidden input
		$cNum = $qNum - 1;
		$objInput = new textinput('cNum', $cNum, 'hidden', '');
		$hiddenInput .= $objInput->show();
			
		$qNum = $qNum + 1;
		$objInput = new textinput('qNum', $qNum, 'hidden', '');
		$hiddenInput .= $objInput->show();

		// form
		$objForm = new form('answer', $this->uri(array(
			'action' => 'submit_lecturer',
			'id' => $tNameSpace,
			'sId' => $studentId,
		), 'tutorials'));
		$objForm->addToForm($string);
		$objForm->addToForm($hiddenInput);
		$objForm->addToForm($buttons);
		$string = $objForm->show();
		
		$objForm = new form('cancelform', $this->uri(array(
			'action' => 'marking_list',
			'id' => $tData['name_space'],
		), 'tutorials'));
		$string .= $objForm->show();
		
		// exit link
		$objLink = new link($this->uri(array(
			'action' => 'marking_list',
			'id' => $tData['name_space'],
		), 'tutorials'));
		$objLink->link = $lblBack;
		$objLink->title = $lblBackTitle;
		$string .= $objLink->show();

		$str = $this->objFeature->show($heading, $string).'<br />';
		
		return $str;
	}
	
	/**
	* Method to show the page to show the tutorial instructions
	* 
	* @access public
	* @return string $str: The output string
	*/
	public function showInstructions()
	{
        // redirect
		if(!$this->_isLecturer() || !$this->_isAdmin()){
			return $this->_redirect();
		}
		
		// data to be diaplayed
		$iData = $this->objTutDb->getInstructions();
		
		// text elements
		$lblAdd = $this->objLanguage->languageText('word_add');
		$lblAddTitle = $this->objLanguage->languageText('mod_tutorials_titleaddinstructions', 'tutorials');
		$lblEdit = $this->objLanguage->languageText('word_edit');
		$lblEditTitle = $this->objLanguage->languageText('mod_tutorials_titleeditinstructions', 'tutorials');
		$lblDelete = $this->objLanguage->languageText('word_delete');
		$lblDeleteTitle = $this->objLanguage->languageText('mod_tutorials_titledeleteinstructions', 'tutorials');
		$lblDelConfirm = $this->objLanguage->languageText('mod_tutorials_deleteconfirm', 'tutorials');
		$lblBack = $this->objLanguage->languageText('word_back');
		$lblBackTitle = $this->objLanguage->languageText('mod_tutorials_back', 'tutorials');
		$lblHeading = $this->objLanguage->languageText('word_instructions');
		$lblNoRecords = $this->objLanguage->languageText('phrase_norecordsfound');
		
		$heading = $lblHeading;

		// links
		if($iData == FALSE){
			$objLink = new link($this->uri(array(
				'action' => 'add_instructions',
			), 'tutorials'));
			$objLink->link = $lblAdd;
			$objLink->title = $lblAddTitle;
			$string = $objLink->show();

			$string .= '<ul><li><font class="noRecordsMessage">'.$lblNoRecords.'</font></li></ul>';
			
		}else{
			$objLink = new link($this->uri(array(
				'action' => 'edit_instructions',
			), 'tutorials'));
			$objLink->link = $lblEdit;
			$objLink->title = $lblEditTitle;
			$string = $objLink->show();

			$objLink = new link($this->uri(array(
				'action' => 'delete_instructions',
			), 'tutorials'));
			$objLink->link = $lblDelete;
			$objLink->title = $lblDeleteTitle;
			$objLink->extra = 'onclick="javascript:if(!confirm(\''.$lblDelConfirm.'\')){return false;}"';
			$string .= '&#160;|&#160;'.$objLink->show();

			// display table
			$objTable = new htmltable();
        	$objTable->cellpadding = '2';
        	$objTable->cellspacing = '2';
		
			$objTable->startRow();
			$objTable->addCell(nl2br($iData['instructions']), '', '', '', 'even', '');
			$objTable->endRow();
			$string .= $objTable->show();				
		}
		
		// display table
		
		// exit link
		$objLink = new link($this->uri(array(
			'action' => 'show_home',
		), 'tutorials'));
		$objLink->link = $lblBack;
		$objLink->title = $lblBackTitle;
		$string .= $objLink->show();

		$str = $this->objFeature->show($heading, $string).'<br />';
		return $str;		
	}
	
	/**
	* Method to show the page to add the tutorial instructions
	* 
	* @access public
	* @return string $str: The output string
	*/
	public function showAddInstructions()
	{
        // redirect
		if(!$this->_isLecturer() || !$this->_isAdmin()){
			return $this->_redirect();
		}
		
		// text elements
		$lblAdd = $this->objLanguage->languageText('word_add');
		$lblSubmit = $this->objLanguage->languageText('word_submit');
		$lblCancel = $this->objLanguage->languageText('word_cancel');
		$lblInstructions = $this->objLanguage->languageText('word_instructions');
		$lblHeading = $this->objLanguage->languageText('word_instructions');
		
		$heading = $lblAdd.'&#160;'.strtolower($lblInstructions);

		// form elements
		$this->objEditor->init('instructions', '', '', '');
		$this->objEditor->setDefaultToolBarSetWithoutSave();
		$instructionsArea = $this->objEditor->showFCKEditor();
		
		// form buttons
		$objButton = new button('submit', $lblSubmit);
		$objButton->setToSubmit();
		$submitButton = $objButton->show();
		
		$objButton = new button('cancel', $lblCancel);
		$objButton->extra = 'onclick="javascript:$(\'form_cancelform\').submit();"';
		$cancelButton = $objButton->show();
		
		// display table
		$objTable = new htmltable();
        $objTable->cellpadding = '2';
        $objTable->cellspacing = '2';
		
		$objTable->startRow();
		$objTable->addCell($instructionsArea, '', '', '', '', '');
		$objTable->endRow();
		$displayTable = $objTable->show();
				
		// form
		$objForm = new form('addinstructions', $this->uri(array(
			'action' => 'add_instructions_update',
		), 'tutorials'));
		$objForm->addToForm($displayTable);
		$objForm->addToForm($submitButton.'&#160;&#160;'.$cancelButton);		
		$form = $objForm->show();
				
		$objForm = new form('cancelform', $this->uri(array(
			'action' => 'show_instructions',
		), 'tutorials'));
		$hiddenForm = $objForm->show();

		$str = $this->objFeature->show($heading, $form.$hiddenForm).'<br />';
		return $str;		
	}

	/**
	* Method to show the page to edit the tutorial instructions
	* 
	* @access public
	* @return string $str: The output string
	*/
	public function showEditInstructions()
	{
        // redirect
		if(!$this->_isLecturer() || !$this->_isAdmin()){
			return $this->_redirect();
		}
		
		// data to be displayed
		$iData = $this->objTutDb->getInstructions();
		
		// text elements
		$lblEdit = $this->objLanguage->languageText('word_edit');
		$lblSubmit = $this->objLanguage->languageText('word_submit');
		$lblCancel = $this->objLanguage->languageText('word_cancel');
		$lblInstructions = $this->objLanguage->languageText('word_instructions');
		$lblHeading = $this->objLanguage->languageText('word_instructions');
		
		$heading = $lblEdit.'&#160;'.strtolower($lblInstructions);

		// form elements
		$this->objEditor->init('instructions', $iData['instructions'], '', '');
		$this->objEditor->setDefaultToolBarSetWithoutSave();
		$instructionsArea = $this->objEditor->showFCKEditor();
		
		// form buttons
		$objButton = new button('submit', $lblSubmit);
		$objButton->setToSubmit();
		$submitButton = $objButton->show();
		
		$objButton = new button('cancel', $lblCancel);
		$objButton->extra = 'onclick="javascript:$(\'form_cancelform\').submit();"';
		$cancelButton = $objButton->show();
		
		// display table
		$objTable = new htmltable();
        $objTable->cellpadding = '2';
        $objTable->cellspacing = '2';
		
		$objTable->startRow();
		$objTable->addCell($instructionsArea, '', '', '', '', '');
		$objTable->endRow();
		$displayTable = $objTable->show();
				
		// form
		$objForm = new form('editinstructions', $this->uri(array(
			'action' => 'edit_instructions_update',
		), 'tutorials'));
		$objForm->addToForm($displayTable);
		$objForm->addToForm($submitButton.'&#160;&#160;'.$cancelButton);		
		$form = $objForm->show();
				
		$objForm = new form('cancelform', $this->uri(array(
			'action' => 'show_instructions',
		), 'tutorials'));
		$hiddenForm = $objForm->show();

		$str = $this->objFeature->show($heading, $form.$hiddenForm).'<br />';
		return $str;		
	}
	
	/**
	* Method to display the view answers page
	*
	* @access public
	* @param string $tNameSpace: The tutorial name space
	* @param string $qNum: The question order number
	* @param string $aNum: The answer count number
	* @return string $str: The output string
	*/
	public function showAnswers($tNameSpace, $qNum, $aNum)
	{
        // redirect
		if(!$this->_isLecturer() || !$this->_isAdmin()){
			return $this->_redirect();
		}
		
		// data to be displayed
		$tData = $this->objTutDb->getTutorial($tNameSpace);
		$qData = $this->objTutDb->getQuestionForMarking($tNameSpace, $qNum);
		$aData = $this->objTutDb->listAnswers($qData['name_space'], $aNum);
		$aCount = count($aData);
				
		// text elements
		$lblViewAnswers = $this->objLanguage->languageText('phrase_viewanswers');
		$lblQuestion = $this->objLanguage->languageText('word_question');
		$lblOf = $this->objLanguage->languageText('word_of');
		$lblTo = $this->objLanguage->languageText('word_to');
		$lblAnswers = $this->objLanguage->languageText('word_answers');
		$lblFirst = $this->objLanguage->languageText('word_first');
		$lblLast = $this->objLanguage->languageText('word_last');
		$lblNext = $this->objLanguage->languageText('word_next');
		$lblPrevious = $this->objLanguage->languageText('word_previous');
		$lblSubmit = $this->objLanguage->languageText('word_submit');
		$lblCancel = $this->objLanguage->languageText('word_cancel');
		$lblBack = $this->objLanguage->languageText('word_back');
		$lblBackTitle = $this->objLanguage->languageText('mod_tutorials_back', 'tutorials');
		$lblNoRecords= $this->objLanguage->languageText('phrase_norecordsfound');
		$lblWorth = $this->objLanguage->languageText('mod_tutorials_questionworth', 'tutorials');
		$lblAnswer = $this->objLanguage->languageText('mod_tutorials_modelanswer', 'tutorials');
		
		$heading = $lblViewAnswers.':&#160;'.$tData['name'];
		
		if($qData == FALSE){
			$display = '<ul>';
			$display .= '<li>';
			$display .= $lblNoRecords;
			$display .= '</li>';
			$display .= '</ul>';
		}else{		 	
		 	// navigation icons
		 	if($qData['question_order'] == 1){
    			$this->objIcon->title = $lblFirst.' '.$lblQuestion;
    			$this->objIcon->setIcon('first_grey');
    			$icons = $this->objIcon->show();
    
    			$this->objIcon->title = $lblPrevious.' '.$lblQuestion;
    			$this->objIcon->setIcon('prev_grey');
    			$icons .= $this->objIcon->show();

    			$this->objIcon->title = $lblNext.' '.$lblQuestion;
    			$icons .= $this->objIcon->getLinkedIcon($this->uri(array(
        			'action' => 'view_answers',
        			'id' => $tNameSpace,
        			'qNum' => ($qNum + 1),
        			'aNum' => 1,
    			)), 'next');

    			$this->objIcon->title = $lblLast.' '.$lblQuestion;
    			$icons .= $this->objIcon->getLinkedIcon($this->uri(array(
        			'action' => 'view_answers',
        			'id' => $tNameSpace,
        			'qNum' => $qData['total'],
        			'aNum' => 1,
    			)), 'last');
			}elseif($qData['question_order'] == $qData['total']){
    			$this->objIcon->title = $lblFirst.' '.$lblQuestion;
    			$icons = $this->objIcon->getLinkedIcon($this->uri(array(
        			'action' => 'view_answers',
        			'id' => $tNameSpace,
        			'qNum' => 1,
        			'aNum' => 1,
    			)), 'first');

    			$this->objIcon->title = $lblPrevious.' '.$lblQuestion;
    			$icons .= $this->objIcon->getLinkedIcon($this->uri(array(
        			'action' => 'view_answers',
        			'id' => $tNameSpace,
        			'qNum' => ($qData['total'] - 1),
        			'aNum' => 1,
    			)), 'prev');    

    			$this->objIcon->title = $lblNext.' '.$lblQuestion;
    			$this->objIcon->setIcon('next_grey');
    			$icons .= $this->objIcon->show();
    
    			$this->objIcon->title = $lblLast.' '.$lblQuestion;
    			$this->objIcon->setIcon('last_grey');
    			$icons .= $this->objIcon->show();
			}else{
    			$this->objIcon->title = $lblFirst.' '.$lblQuestion;
    			$icons = $this->objIcon->getLinkedIcon($this->uri(array(
        			'action' => 'view_answers',
        			'id' => $tNameSpace,
        			'qNum' => 1,
        			'aNum' => 1,
    			)), 'first');

    			$this->objIcon->title = $lblPrevious.' '.$lblQuestion;
    			$icons .= $this->objIcon->getLinkedIcon($this->uri(array(
        			'action' => 'view_answers',
        			'id' => $tNameSpace,
        			'qNum' => ($qNum - 1),
        			'aNum' => 1,
    			)), 'prev');    

    			$this->objIcon->title = $lblNext.' '.$lblQuestion;
    			$icons .= $this->objIcon->getLinkedIcon($this->uri(array(
        			'action' => 'view_answers',
        			'id' => $tNameSpace,
       			 	'qNum' => ($qNum + 1),
        			'aNum' => 1,
    			)), 'next');

    			$this->objIcon->title = $lblLast.' '.$lblQuestion;
    			$icons .= $this->objIcon->getLinkedIcon($this->uri(array(
        			'action' => 'view_answers',
        			'id' => $tNameSpace,
        			'qNum' => $qData['total'],
        			'aNum' => 1,
    			)), 'last');				
			}
		 	
			//form elements
			$this->objEditor->init('answer', $qData['model_answer'], '', '');
			$this->objEditor->setDefaultToolBarSetWithoutSave();
			$answerArea = $this->objEditor->showFCKEditor();
		
			$objInput = new textinput('worth', $qData['question_value'], '', '');
			$worthInput = $objInput->show();		
		
			// form buttons
			$objButton = new button('submit', $lblSubmit);
			$objButton->setToSubmit();
			$submitButton = $objButton->show();
		
			$objButton = new button('cancel', $lblCancel);
			$objButton->extra = 'onclick="javascript:$(\'form_cancelform\').submit();"';
			$cancelButton = $objButton->show();
		
			// display table
			$objTable = new htmltable();
			$objTable->cellpadding = 2;
			$objTable->cellspacing = 2;
			$objTable->startRow();
			$objTable->addCell('<b>'.$lblQuestion.':</b><br />'.nl2br($qData['question']), '', '', '', '', '');
			$objTable->endRow();
			$objTable->startRow();
			$objTable->addCell('<b>'.$lblAnswer.'</b><br />'.$answerArea, '', '', '', '', '');
			$objTable->endRow();
			$objTable->startRow();
			$objTable->addCell('<b>'.$lblWorth.'</b><br />'.$worthInput, '', '', '', '', '');
			$objTable->endRow();
			$displayTable = $objTable->show();
			
			// form
			$objForm = new form('editquestion', $this->uri(array(
				'action' => 'edit_question_update',
				'tutId' => $tNameSpace,
				'id' => $qData['name_space'],
				'order' => $qData['question_order'],
				'value' => $qData['question_value'],
				'return' => 'answers',
			), 'tutorials'));
			$objForm->addToForm($displayTable);
			$objForm->addToForm($submitButton.'&#160;&#160;'.$cancelButton);		
			$form = $objForm->show();
					
			$objForm = new form('cancelform', $this->uri(array(
				'action' => 'view_answers',
				'id' => $tNameSpace,
				'qNum' => $qNum,
			), 'tutorials'));
			$hiddenForm = $objForm->show();
			
			$display = $form.$hiddenForm;		
		}
		
		$questionLabel = '<b>'.$lblQuestion.'&#160;'.$qData['question_order'].'&#160;'.strtolower($lblOf).'&#160;'.$qData['total'].'</b>';
		$objTabbedbox = new tabbedbox();
		$objTabbedbox->extra = 'style="padding: 10px;"';
		$objTabbedbox->addTabLabel($questionLabel);
		$objTabbedbox->addBoxContent($icons.$display);
		$string = $objTabbedbox->show();

		if($aData == FALSE){
			$display = '<ul>';
			$display .= '<li>';
			$display .= $lblNoRecords;
			$display .= '</li>';
			$display .= '</ul>';
		}else{
		 	// navigation icons
    		$max = (intval($aData[0]['total'] / 20) * 20) + 1;
    		if($max == 1){
        		$this->objIcon->title = $lblFirst.' '.$lblAnswers;
        		$this->objIcon->setIcon('first_grey');
        		$icons = $this->objIcon->show();
    
        		$this->objIcon->title = $lblPrevious.' 20 '.$lblAnswers;
        		$this->objIcon->setIcon('prev_grey');
        		$icons .= $this->objIcon->show();

        		$this->objIcon->title = $lblNext.' 20 '.$lblAnswers;
        		$this->objIcon->setIcon('next_grey');
        		$icons .= $this->objIcon->show();
    
        		$this->objIcon->title = $lblLast.' '.$lblAnswers;
        		$this->objIcon->setIcon('last_grey');
        		$icons .= $this->objIcon->show();
    		}elseif($aNum == 1){
        		$this->objIcon->title = $lblFirst.' '.$lblAnswers;
        		$this->objIcon->setIcon('first_grey');
        		$icons = $this->objIcon->show();
    
        		$this->objIcon->title = $lblPrevious.' 20 '.$lblAnswers;
        		$this->objIcon->setIcon('prev_grey');
        		$icons .= $this->objIcon->show();

        		$this->objIcon->title = $lblNext.' 20 '.$lblAnswers;
        		$icons .= $this->objIcon->getLinkedIcon($this->uri(array(
            		'action' => 'view_answers',
            		'id' => $tNameSpace,
            		'qNum' => $qNum,
            		'aNum' => ($aNum + 20),
        		)), 'next');

        		$this->objIcon->title = $lblLast.' '.$lblAnswers;
        		$icons .= $this->objIcon->getLinkedIcon($this->uri(array(
            		'action' => 'view_answers',
            		'id' => $tNameSpace,
            		'qNum' => $qNum,
            		'aNum' => $max,
        		)), 'last');
    		}elseif($aNum == $max){
        		$this->objIcon->title = $lblFirst.' '.$lblAnswers;
        		$icons = $this->objIcon->getLinkedIcon($this->uri(array(
           			'action' => 'view_answers',
            		'id' => $tNameSpace,
            		'qNum' => $qNum,
            		'aNum' => 1,
        		)), 'first');

        		$this->objIcon->title = $lblPrevious.' 20 '.$lblAnswers;
        		$icons .= $this->objIcon->getLinkedIcon($this->uri(array(
            		'action' => 'view_answers',
            		'id' => $tNameSpace,
            		'qNum' => $qNum,
            		'aNum' => ($max - 20),
        		)), 'prev');    

        		$this->objIcon->title = $lblNext.' 20 '.$lblAnswers;
        		$this->objIcon->setIcon('next_grey');
        		$icons .= $this->objIcon->show();
    
        		$this->objIcon->title = $lblLast.' '.$lblAnswers;
        		$this->objIcon->setIcon('last_grey');
        		$icons .= $this->objIcon->show();
    		}else{
        		$this->objIcon->title = $lblFirst.' '.$lblAnswers;
        		$icons = $this->objIcon->getLinkedIcon($this->uri(array(
            		'action' => 'view_answers',
            		'id' => $tNameSpace,
            		'qNum' => $qNum,
            		'aNum' => 1,
        		)), 'first');

        		$this->objIcon->title = $lblPrevious.' 20 '.$lblAnswers;
        		$icons .= $this->objIcon->getLinkedIcon($this->uri(array(
            		'action' => 'view_answers',
            		'id' => $tNameSpace,
            		'qNum' => $qNum,
            		'aNum' => ($aNum - 20),
        		)), 'prev');    

        		$this->objIcon->title = $lblNext.' 20 '.$lblAnswers;
        		$icons .= $this->objIcon->getLinkedIcon($this->uri(array(
            		'action' => 'view_answers',
            		'id' => $tNameSpace,
            		'qNum' => $qNum,
            		'aNum' => ($aNum + 20),
        		)), 'next');

        		$this->objIcon->title = $lblLast.' '.$lblAnswers;
        		$icons .= $this->objIcon->getLinkedIcon($this->uri(array(
            		'action' => 'view_answers',
            		'id' => $tNameSpace,
            		'qNum' => $qNum,
            		'aNum' => $max,
        		)), 'last');
    		}

			// display table
			$objTable = new htmltable();
			$objTable->cellpadding = 2;
			$objTable->cellspacing = 2;
			$i = 0;
		 	foreach($aData as $key => $line){
                $class = (($i++%2) == 0) ? 'even' : 'odd';
				$objTable->startRow();
				$objTable->addCell('<b>'.($aNum + $key).'.</b>', '5%', '', '', $class, '');
				$objTable->addCell(nl2br($line['answer']), '', '', '', $class, '');
				$objTable->endRow();
			}
			$display = $objTable->show();
		}
		
		$answerLabel = '<b>'.$lblAnswers.'&#160;'.$aNum.'&#160;'.strtolower($lblTo).'&#160;'.$aCount.'&#160;'.strtolower($lblOf).'&#160;'.$aData[0]['total'].'</b>';
		$objTabbedbox = new tabbedbox();
		$objTabbedbox->extra = 'style="padding: 10px;"';
		$objTabbedbox->addTabLabel($answerLabel);
		$objTabbedbox->addBoxContent($icons.$display);
		$string .= $objTabbedbox->show();

		// exit link
		$objLink = new link($this->uri(array(
			'action' => 'view_tutorial',
			'id' => $tNameSpace,
		), 'tutorials'));
		$objLink->link = $lblBack;
		$objLink->title = $lblBackTitle;
		$string .= $objLink->show();

		$str = $this->objFeature->show($heading, $string).'<br />';
		return $str;		
	}
	
	/**
	* Method to display the late submissions page
	*
	* @access public
	* @param string $tNameSpace: The tutorial name space
	* @param string $studentId: The id of the student to edit
	* @return string $str: The output string
	*/
	public function showLateSubmissions($tNameSpace, $studentId = NULL)
	{
        // redirect
		if(!$this->_isLecturer() || !$this->_isAdmin()){
			return $this->_redirect();
		}
		
		// load javascript
		$headerParams = $this->getJavascriptFile('new_sorttable.js', 'htmlelements');
        $this->appendArrayVar('headerParams', $headerParams);
        
		// data to be displayed
		$tData = $this->objTutDb->getTutorial($tNameSpace);
		$name = $tData['name'];
		$type = $tData['type'];
		$description = $tData['description'];
		$percentage = $tData['percentage'];
		$totalMark = $tData['total_mark'] < 1 ? 0 : $tData['total_mark'];
		$answerOpen = $tData['answer_open_date'];
		$answerClose = $tData['answer_close_date'];
		$markClose = $tData['marking_close_date'];
		$moderateClose = $tData['moderating_close_date'];
		
        $sData = $this->_listStudents();

		// text elements
		$lblStandard = $this->objLanguage->languageText('word_standard');
		$lblInteractive = $this->objLanguage->languageText('word_interactive');
		$lblName = $this->objLanguage->languageText('word_name');
		$lblType = $this->objLanguage->languageText('word_type');
		$lblDescription = $this->objLanguage->languageText('word_description');
		$lblPercentage = $this->objLanguage->languageText('mod_tutorials_percentage', 'tutorials');
		$lblOpen = $this->objLanguage->languageText('mod_tutorials_dateopen', 'tutorials');
		$lblClose = $this->objLanguage->languageText('mod_tutorials_dateclose', 'tutorials');
		$lblMark = $this->objLanguage->languageText('mod_tutorials_datemark', 'tutorials');
		$lblModerate = $this->objLanguage->languageText('mod_tutorials_datemoderate', 'tutorials');
		$lblTutorial = $this->objLanguage->languageText('word_tutorial');
		$lblNoRecords = $this->objLanguage->languageText('phrase_norecordsfound');
		$heading = $this->objLanguage->languageText('phrase_latesubmissions');
		$lblBack = $this->objLanguage->languageText('word_back');
		$lblBackTitle = $this->objLanguage->languageText('mod_tutorials_back', 'tutorials');
		$lblStudents = $this->objLanguage->code2Txt('word_students');
		$lblTotalMark = $this->objLanguage->languageText('phrase_totalmark');
        $lblStudentNo = $this->objLanguage->languageText('phrase_studentnumber');
        $lblName = $this->objLanguage->languageText('word_name');
        $lblSurname = $this->objLanguage->languageText('word_surname');
		$lblLateClose = $this->objLanguage->languageText('mod_tutorials_lateanswersclosing', 'tutorials');
		$lblLateMark = $this->objLanguage->languageText('mod_tutorials_latemarkingclosing', 'tutorials');
		$lblLateModerate = $this->objLanguage->languageText('mod_tutorials_latemoderatingclosing', 'tutorials');
		$lblDelete = $this->objLanguage->languageText('word_delete');
		$lblDeleteTitle = $this->objLanguage->languageText('mod_tutorials_titledeletelate', 'tutorials');
		$lblDelConfirm = $this->objLanguage->languageText('mod_tutorials_deleteconfirm', 'tutorials');
		$lblSubmit = $this->objLanguage->languageText('word_submit');
		$lblCancel = $this->objLanguage->languageText('word_cancel');
		$lblWarning = $this->objLanguage->languageText('mod_tutorials_latewarning', 'tutorials');

		if($type == 2){
			$lblTutType = $lblInteractive;
		}else{
			$lblTutType = $lblStandard;
		}
		
		// tutorial tabbed box
		// display table
		$objTable = new htmltable();
        $objTable->cellpadding = '2';
        $objTable->cellspacing = '2';
		
		$objTable->startRow();
		$objTable->addCell('<b>'.$lblName.'</b>', '30%', '', '', '', '');
		$objTable->addCell($name, '', '', '', '', '');
		$objTable->endRow();

		$objTable->startRow();
		$objTable->addCell('<b>'.$lblType.'</b>', '30%', '', '', '', '');
		$objTable->addCell($lblTutType, '', '', '', '', '');
		$objTable->endRow();
		
		$objTable->startRow();
		$objTable->addCell('<b>'.$lblDescription.'</b>', '30%', 'top', '', '', '');
		$objTable->addCell(nl2br($description), '', '', '', '', '');
		$objTable->endRow();

		$objTable->startRow();
		$objTable->addCell('<b>'.$lblPercentage.'</b>', '30%', '', '', '', '');
		$objTable->addCell($percentage, '', '', '', '', '');
		$objTable->endRow();

		$objTable->startRow();
		$objTable->addCell('<b>'.$lblTotalMark.'</b>', '30%', '', '', '', '');
		$objTable->addCell($totalMark, '', '', '', '', '');
		$objTable->endRow();

		$objTable->startRow();
		$objTable->addCell('<b>'.$lblOpen.'</b>', '30%', '', '', '', '');
		$objTable->addCell($this->_formatDate($answerOpen), '', '', '', '', '');
		$objTable->endRow();

		$objTable->startRow();
		$objTable->addCell('<b>'.$lblClose.'</b>', '30%', '', '', '', '');
		$objTable->addCell($this->_formatDate($answerClose), '', '', '', '', '');
		$objTable->endRow();
		$string = $objTable->show();

		
		// dates table
		$objTable = new htmltable();
        $objTable->cellpadding = '2';
        $objTable->cellspacing = '2';

		$objTable->startRow();
		$objTable->addCell('<b>'.$lblMark.'</b>', '30%', '', '', '', '');
		$objTable->addCell($this->_formatDate($markClose), '', '', '', '', '');
		$objTable->endRow();

		$objTable->startRow();
		$objTable->addCell('<b>'.$lblModerate.'</b>', '30%', '', '', '', '');
		$objTable->addCell($this->_formatDate($moderateClose), '', '', '', '', '');
		$objTable->endRow();
		$datesTable = $objTable->show();

		// dates layer
		$objLayer = new layer();
		$objLayer->id = 'datesDiv';
		if($type != 2){
			$objLayer->display = 'none';
		}
		$objLayer->addToStr($datesTable);
		$string .= $objLayer->show();
		
		$objTabbedbox = new tabbedbox();
		$objTabbedbox->extra = 'style="padding: 10px;"';
		$objTabbedbox->addTabLabel('<b>'.$lblTutorial.'</b>');
		$objTabbedbox->addBoxContent($string);
		$tabbedboxes = $objTabbedbox->show();

		// students tabbed box
		if($sData == FALSE){
			$string = '<ul>';
			$string .= '<li>';
			$string .= '<b><font class="noRecordsMessage">'.$lblNoRecords.'</font></b>';
			$string .= '</ul>';
			$string .= '</li>';
		}else{
			$objTable = new htmltable();
        	$objTable->id = 'studentList';
        	$objTable->css_class = 'sorttable';
        	$objTable->cellpadding = '2';
        	$objTable->row_attributes = ' name="row_'.$objTable->id.'"';
        
        	$objTable->startRow();
        	$objTable->addCell($lblStudentNo, '', '', '', 'heading', '');
        	$objTable->addCell($lblName, '', '', '', 'heading', '');
        	$objTable->addCell($lblSurname, '', '', '', 'heading', '');
	        $objTable->addCell($lblLateClose, '', '', '', 'heading', '');
	        if($type == 2){
	        	$objTable->addCell($lblLateMark, '', '', '', 'heading', '');
	        	$objTable->addCell($lblLateModerate, '', '', '', 'heading', '');
			}
	        $objTable->addCell('', '', '', '', 'heading', '');
	        $objTable->endRow();
			foreach($sData as $line){
			 	$lData = $this->objTutDb->getLateSubmissions($tNameSpace, $line['userid']);
			 	
			 	// edit link
				 $objLink = new link($this->uri(array(
			 		'action' => 'late_submissions',
				 	'id' => $tNameSpace,
					 'sId' => $line['userid'],
				), 'tutorials'));
				$objLink->link = $line['userid'];
				$objLink->name = $line['userid'];
				$objLink->anchor = $line['userid'];
				$link = $objLink->show();
				
				if($studentId == $line['userid']){
					if($lData != FALSE){
			 			// delete link
					 	$objLink = new link($this->uri(array(
					 		'action' => 'delete_late',
						 	'id' => $tNameSpace,
							 'sId' => $line['userid'],
						), 'tutorials'));
						$objLink->link = $lblDelete;
						$objLink->title = $lblDeleteTitle;
						$objLink->extra = 'onclick="javascript:if(!confirm(\''.$lblDelConfirm.'\')){return false;}"';
						$delete = $objLink->show();
				
    		   			$closeField = $this->objPopupcal->show('answer_close', 'yes', 'no', date('Y-m-d H:i', strtotime($lData['answer_close_date'])));
        				$markField = $this->objPopupcal->show('mark_close', 'yes', 'no', date('Y-m-d H:i', strtotime($lData['marking_close_date'])));
        				$moderateField = $this->objPopupcal->show('moderate_close', 'yes', 'no', date('Y-m-d H:i', strtotime($lData['moderating_close_date'])));        
        			}else{
    		   			$closeField = $this->objPopupcal->show('answer_close', 'yes', 'no', date('Y-m-d H:i'));
        				$markField = $this->objPopupcal->show('mark_close', 'yes', 'no', date('Y-m-d H:i'));
        				$moderateField = $this->objPopupcal->show('moderate_close', 'yes', 'no', date('Y-m-d H:i'));
						$delete = '';        
					}
			
					// hidden input
					$objInput = new textinput('sId', $line['userid'], 'hidden');
					$hidden = $objInput->show();
					
					// form buttons
					$objButton = new button('submit', $lblSubmit);
					$objButton->setToSubmit();
					$buttons = $objButton->show();
		
					$objButton = new button('cancel', $lblCancel);
					$objButton->setToSubmit();
					$buttons .= '&#160;'.$objButton->show();		
				}else{
					if($lData != FALSE){
			 			// delete link
					 	$objLink = new link($this->uri(array(
					 		'action' => 'delete_late',
						 	'id' => $tNameSpace,
							 'sId' => $line['userid'],
						), 'tutorials'));
						$objLink->link = $lblDelete;
						$objLink->title = $lblDeleteTitle;
						$objLink->extra = 'onclick="javascript:if(!confirm(\''.$lblDelConfirm.'\')){return false;}"';
						$delete = $objLink->show();
				
						$closeField = $this->_formatDate($lData['answer_close_date']);	
        				$markField = $this->_formatDate($lData['marking_close_date']);
        				$moderateField = $this->_formatDate($lData['moderating_close_date']);        
        			}else{
						$closeField = '';
						$markField = '';
						$moderateField = '';
						$delete = '';	
					}
				}
				 	
				$objTable->startRow();
				$objTable->addCell($link, '', '', '', '', '');
				$objTable->addCell($line['firstname'], '', '', '', '', '');
				$objTable->addCell($line['surname'], '', '', '', '', '');
				$objTable->addCell($closeField, '', '', '', '', '');
				if($type == 2){
					$objTable->addCell($markField, '', '', '', '', '');
					$objTable->addCell($moderateField, '', '', '', '', '');
				}
				$objTable->addCell($delete, '', '', '', '', '');
				$objTable->endRow();
				if($studentId == $line['userid']){
					$objTable->startRow();
					$objTable->addCell($hidden.$buttons, '', '', '', '', 'colspan="7"');
					$objTable->endRow();
				}
			}
			$string = $objTable->show();	
		}
		
		$objForm = new form('addlate', $this->uri(array(
			'action' => 'submit_late',
			'id' => $tNameSpace,
		), 'tutorials'));
		$objForm->addToForm($string);
		$form = $objForm->show();
		
		$objTabbedbox = new tabbedbox();
		$objTabbedbox->extra = 'style="padding: 10px;"';
		$objTabbedbox->addTabLabel('<b>'.$lblStudents.'</b>');
		$objTabbedbox->addBoxContent('<b><font class="warning">'.$lblWarning.'</font></b>'.$form);
		$tabbedboxes .= $objTabbedbox->show();

		// exit link
		$objLink = new link($this->uri(array(
			'action' => 'view_tutorial',
			'id' => $tNameSpace,
		), 'tutorials'));
		$objLink->link = $lblBack;
		$objLink->title = $lblBackTitle;
		$string = $objLink->show();

		$str = $this->objFeature->show($heading, $tabbedboxes.$string).'<br />';
		
		return $str;
	}
}
?>