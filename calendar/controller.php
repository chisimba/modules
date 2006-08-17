<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
// end security check

/**
* Calendar Controller
* This class controls all functionality to run the calendar module. It now integrates user calendar and contextcalendar
* @author Tohir Solomons
* @copyright (c) 2004 University of the Western Cape
* @package calendar
* @version 2
*/
class calendar extends controller
{

    /**
	* Constructor method to instantiate objects and get variables
	*/
    function init()
    {
        $this->objCalendar =& $this->getObject('managecalendar');
		//$this->oabjCalendar =& $this->getObject('dbcalendar', 'calendarbase');
		$this->objContext =& $this->getObject('dbcontext', 'context');
        $this->dateFunctions =& $this->getObject('datefunctions', 'calendarbase');
        
        // User Details
        $this->objUser =& $this->getObject('user', 'security');
        $this->setVarByRef('fullname', $this->objUser->fullname());
        $this->userId = $this->objUser->userId();
		
		// Determine if user is in a context
		$this->contextCode = $this->objContext->getContextCode();
		$this->contextTitle = $this->objContext->getTitle();
		if ($this->contextCode == NULL) {
			$this->contextCode = 'root';
			$this->isInContext = FALSE;
			$this->contextTitle = 'Lobby';
		} else {
			$this->isInContext = TRUE;
		}
		$this->setVarByRef('contextCode', $this->contextCode);
		$this->setVarByRef('courseTitle', $this->contextTitle);
		$this->setVarByRef('isInContext', $this->isInContext);
		
		
		$objContextCondition = &$this->getObject('contextcondition','contextpermissions');
        $this->isContextLecturer = $objContextCondition->isContextMember('Lecturers');
		
		$this->objDT = &$this->getObject( 'decisiontable','decisiontable' );
        $this->objDT->create('calendar');
        $this->objDT->retrieve('calendar');
		
		// Give User Lecturer Rights if User is Admin
		if ($this->objDT->isValid('manage_course_event')) {
			$this->isContextLecturer = TRUE;
		} else {
			$this->isContextLecturer = FALSE;
		}
		
		
		if ($this->objDT->isValid('manage_site_event')) {
			$this->isContextLecturer = TRUE;
			$this->manageSiteCalendar = TRUE;
		} else {
			$this->manageSiteCalendar = FALSE;
		}
		
		
		$this->setVarByRef('isAdmin', $this->manageSiteCalendar);
		
		//echo $this->manageSiteCalendar;
		
		$this->setVarByRef('isContextLecturer', $this->isContextLecturer);
		
		$this->objCalendar->setContextPermissions($this->isContextLecturer);
		
        
        // Load Language Class
        $this->objLanguage = &$this->getObject('language', 'language');
        $this->setVarByRef('objLanguage', $this->objLanguage);
        
        //Get the activity logger class
        $this->objLog=$this->newObject('logactivity', 'logger');
        //Log this module call
        $this->objLog->log();

		$this->objAttachments =& $this->getObject('attachments');
		
		// Load File Upload Class for attachments
//        $this->objUploader =& $this->getObject('fileupload'/*, 'filestore'*/);
//		$this->objTempAttachments =& $this->getObject('dbtempattachments', 'calendarbase');
//        $this->objEventAttachments =& $this->getObject('dbeventattachments', 'calendarbase');
	}
    
    /**
	* Method to process actions to be taken
    *
    * @param string $action String indicating action to be taken
	*/
    function dispatch($action=Null)
    {
		$this->setVar('pageSuppressXML',true);
        $this->setLayoutTemplate('calendar_layout_tpl.php');
        
        switch ($action)
        {
            case 'add':
                return $this->showAddForm();
                
            case 'edit':
                return $this->showEditForm($this->getParam('id'));
                
            case 'saveevent':
                return $this->saveEvent();
                
            case 'updateevent':
                return $this->updateEvent();
                
            case 'delete':
                return $this->deleteEvent($this->getParam('id'));
				
			case 'tempframe':
				return $this->attachmentWindow($this->getParam('id'), $this->getParam('mode'));
				
			case 'uploadattachment':
				return $this->uploadAttachment();
				
			case 'downloadattachment':
				return $this->downloadAttachment($this->getParam('id'), $this->getParam('event'));
				
			case 'deleteattachment':
				return $this->deleteAttachment($this->getParam('id'), $this->getParam('mode'), $this->getParam('filename'));
                
            default:
                return $this->showEvents();
        }
    }
    
    /**
	* Method to show events for the current month. This is the default action
	*/
    function showEvents()
    {
        $month = $this->getParam('month', date('m'));
        $year = $this->getParam('year', date('Y'));
		$this->setVarByRef('month', $month);
		$this->setVarByRef('year', $year);
		
		// Determines the default list of views available
		if ($this->isInContext) {
			$defaultList = 'all';
		} else {
			$defaultList = 'user';
		}
		
		$eventsList = $this->getParam('events', $defaultList);
		$this->setVarByRef('currentList', $eventsList);
		$this->objCalendar->setEventsTag($eventsList);
        
        switch ($eventsList)
		{
			case 'user':
				$user = $this->userId;
				$context = NULL;
				break;
			case 'context':
				$user = NULL;
				$context = $this->contextCode;
				break;
			case 'site':
				$user = NULL;
				$context = 'root';
				break;
			default:
				$user = $this->userId;
				$context = $this->contextCode;
				break;
		}
		
		$events = $this->objCalendar->getEventsCalendar($user, $context, $month, $year);
		$this->setVarByRef('eventsCalendar', $events);
		
		if ($this->getParam('error') == 'attachment') {
			$this->setErrorMessage('Calendar attachment could not be found');
		}
		
        return 'calendar_tpl.php';
    }
    
    /**
    * Method to show the Add Event Form
    */
    function showAddForm()
    {
        // Determines the default list of views available
		if ($this->isInContext) {
			$defaultList = 'all';
		} else {
			$defaultList = 'user';
		}
		
		$eventsList = $this->getParam('events', $defaultList);
		$this->setVarByRef('currentList', $eventsList);
		
		$month = $this->getParam('month', date('m'));
        $year = $this->getParam('year', date('Y'));
		$this->setVarByRef('month', $month);
		$this->setVarByRef('year', $year);
		
		$this->setVar('mode', 'add');
		
		$temporaryId = $this->objUser->userId().'_'.mktime();
		$this->setVarByRef('temporaryId', $temporaryId);
		
        return 'addedit_event_tpl.php';
    }
    
    /**
    * Method to process a form and save an event
    */
    function saveEvent()
    {
        $date = $this->getParam('date');
        $date2 = $this->getParam('date2');
        $eventtitle = $this->getParam('title');
        $eventdetails = $this->getParam('details');
        $eventurl  = $this->getParam('url');
        $multidayevent  = $this->getParam('multidayevent');
		$eventFor = $this->getParam('eventfor', '0');
		$timeFrom = $this->getParam('timefrom');
		$timeTo = $this->getParam('timeto');
		
		
		
		$eventsList = 'all';
        
        // Check if day is a multiday event or not
        if (($multidayevent == 1) && ($date != '') && ($date2 != '')) {
            // Insert Multidate event
            //$this->objCalendar->insertMultiDayEvent ($date, $date2, $eventtitle, $eventdetails, $eventurl, $this->userId, $this->userId);
			// Insert Single Day event
			switch ($eventFor)
			{
				case 0: // Save User Event
					$event = $this->objCalendar->insertMultiDayUserEvent ($date, $date2, $eventtitle, $eventdetails, $eventurl, $this->userId, $this->userId, $timeFrom, $timeTo);
					break;
				case 1: // Save Course Event
					$event = $this->objCalendar->insertMultiDayContextEvent ($date, $date2, $eventtitle, $eventdetails, $eventurl, $this->contextCode, $this->userId, $this->userId);
					break;
				case 3: // Save Site Event
					$event = $this->objCalendar->insertMultiDayContextEvent ($date, $date2, $eventtitle, $eventdetails, $eventurl, 'root', $this->userId, $this->userId);
					$eventsList = 'site';
					break;
			}
        } else {
            // Insert Single Day event
			switch ($eventFor)
			{
				case 0: // Save Single User Event
				
					$event = $this->objCalendar->insertSingleUserEvent($date, $eventtitle, $eventdetails, $eventurl,null,0, $this->userId, $timeFrom, $timeTo);
					break;
				case 1: // Save Single Course Event
					$event = $this->objCalendar->insertSingleContextEvent($date, $eventtitle, $eventdetails, $eventurl, $this->contextCode, $this->userId);
					break;
				case 3:  // Save Single Site Event
					$event = $this->objCalendar->insertSingleContextEvent($date, $eventtitle, $eventdetails, $eventurl, 'root', $this->userId);
					$eventsList = 'site';
					break;
			}
            //$event = $this->objCalendar->insertUserEvent($date, $eventtitle, $eventdetails, $eventurl, $this->userId);
        }
        
        $monthYear = $this->dateFunctions->getMonthYear($date);
		
		// Get List of Temporary Files
//		$files = $this->objTempAttachments->getTransferList($_POST['temporary_id']);
//
		// Transfer as Proper Attachment
//		foreach ($files as $file)
//		{
//			$this->objEventAttachments->insertSingle($file['attachment_id'], $event, $file['userId']);
//			$this->objTempAttachments->deleteAttachment($file['id'], $_POST['temporary_id']);
//		}
//      
		$this->objAttachments->transfer($_POST['temporary_id'],$event);

        return $this->nextAction(NULL, array('message'=>'eventadded', 'month'=>$monthYear['month'], 'year'=>$monthYear['year'], 'events'=>$eventsList));
    }
    
    /**
	* Method to prepare a form for editing an event
    *
    * @param string $id Record Id of the event to edit
	*/
    function showEditForm($id)
    {
        // Determines the default list of views available
		if ($this->isInContext) {
			$defaultList = 'all';
		} else {
			$defaultList = 'user';
		}
		
		$eventsList = $this->getParam('events', $defaultList);
		$this->setVarByRef('currentList', $eventsList);
		
		$event = $this->objCalendar->getSingle($id);
		
		$monthYear = $this->dateFunctions->getMonthYear($event['eventdate']);
		$this->setVarByRef('month', $monthYear['month']);
		$this->setVarByRef('year', $monthYear['year']);
		
		// Check if user is able to edit event
		$this->checkEventEditPermission($event);
        
        // Check if the event is a multiday event or a single day
        if ($event['multiday_event'] == '1') {
            // If event start id is NULL, get the date of the first event
            if ($event['multiday_event_start_id'] != '') {
                $event['eventdate'] = $this->objCalendar->getStartMultiDayEvent($event['multiday_event_start_id']);
            } else {
                // Else set event start id to current id
                $event['multiday_event_start_id'] = $event['id'];
            }
            // get date of last event
            $event['eventdate2']  = $this->objCalendar->getLastMultiDayEvent($event['multiday_event_start_id']);
            
        } else {
            $event['eventdate2'] = '';
        }
        $this->setVarByRef('event', $event);
        $this->setVar('mode', 'edit');
		
		$this->setVarByRef('temporaryId', $id);
		
        return 'addedit_event_tpl.php';
    }
    
    
    /**
	* Method to process a form and update an event
	*/
    function updateEvent()
    {

		$id  = $this->getParam('id');
        $date = $this->getParam('date');
        $date2 = $this->getParam('date2');
        $eventtitle = $this->getParam('title');
        $eventdetails = $this->getParam('details');
        $eventurl  = $this->getParam('url');
        $multidayevent  = $this->getParam('multidayevent');
        $multiday_event_original = $this->getParam('multiday_event_original');
        
        $returnevents = 'all';
		
        if ($date2 != NULL) {
            $this->dateFunctions->smallDateBigDate($date, $date2);
        }
        
        // To keep proper track, get the user who made the first entry
        $originalEvent = $this->objCalendar->getSingle($id);
        $userFirstEntry = $originalEvent['userFirstEntry'];
        $dateFirstEntry = $originalEvent['dateFirstEntry'];
		
		        // Check if user is able to edit event
		$this->checkEventEditPermission($originalEvent);
        
		if ($originalEvent['userorcontext'] == '0') {
			if ($multiday_event_original != '') {
			
				if ($multidayevent == 1) { // continue with multi update
					
					/* 
					* The steps taken here are:
					* 1) It updates the existing record
					* 2) It deletes the child events
					* 3) It re-inserts the events
					*/
					$this->objCalendar->updateUserEvent($id, 1, $date, $eventtitle, $eventdetails, $eventurl, $this->userId);
					$this->objCalendar->deleteMultiEventsChild($multiday_event_original);
					
					$this->objCalendar->insertMultiDayUserEvent ($date, $date2, $eventtitle, $eventdetails, $eventurl, $userFirstEntry, $this->userId,  $dateFirstEntry, NULL, $eventStartId=$id);
					
					
				} else { // switch to single day
					$this->objCalendar->deleteMultiEventsChild($multiday_event_original);
					$this->objCalendar->updateUserEvent($id, 0, $date, $eventtitle, $eventdetails, $eventurl, $this->userId);
				}
			} else {
				if ($multidayevent == 0) {
					$this->objCalendar->updateUserEvent($id, 0, $date, $eventtitle, $eventdetails, $eventurl, $this->userId);
				} else {
				
					/* 
					* The steps taken here are:
					* 1) It updates the existing record
					* 3) Insert Multi Event
					*/
					$this->objCalendar->updateUserEvent($id, 1, $date, $eventtitle, $eventdetails, $eventurl, $this->userId);
					$this->objCalendar->insertMultiDayUserEvent ($date, $date2, $eventtitle, $eventdetails, $eventurl, $userFirstEntry, $this->userId,  $dateFirstEntry, NULL, $eventStartId=$id);
				}
			}
			
			
		/* END IF USER EVENT */
		
		} else if ($originalEvent['userorcontext'] == '1') {
		
		/* ELSE IF CONTEXT EVENT */
		
			$originalContext = $originalEvent['context'];
			
			if ($multiday_event_original != '') {
			
				if ($multidayevent == 1) { // continue with multi update
					
					
					/* 
					* The steps taken here are:
					* 1) It updates the existing record
					* 2) It deletes the child events
					* 3) It re-inserts the events
					*/
					$this->objCalendar->updateContextEvent($id, 1, $date, $eventtitle, $eventdetails, $eventurl, $originalContext, $this->userId);
					$this->objCalendar->deleteMultiEventsChild($multiday_event_original);
					
					$this->objCalendar->insertMultiDayContextEvent ($date, $date2, $eventtitle, $eventdetails, $eventurl, $originalContext, $userFirstEntry,  $this->userId,  $dateFirstEntry, NULL, $eventStartId=$id);
					
					
				} else { // switch to single day
					$this->objCalendar->deleteMultiEventsChild($multiday_event_original);
					$this->objCalendar->updateContextEvent($id, 0, $date, $eventtitle, $eventdetails, $eventurl, $originalContext, $this->userId);
				}
			} else {
				if ($multidayevent == 0) {
					$this->objCalendar->updateContextEvent($id, 0, $date, $eventtitle, $eventdetails, $eventurl, $originalContext, $this->userId);
				} else {
				
					/* 
					* The steps taken here are:
					* 1) It updates the existing record
					* 3) Insert Multi Event
					*/
					$this->objCalendar->updateContextEvent($id, 1, $date, $eventtitle, $eventdetails, $eventurl, $originalContext, $this->userId);
					$this->objCalendar->insertMultiDayContextEvent ($date, $date2, $eventtitle, $eventdetails, $eventurl, $originalContext, $userFirstEntry,  $this->userId,  $dateFirstEntry, NULL, $eventStartId=$id);
				}
			}
			
			if ($originalContext == 'root') {
				$returnevents = 'site';
			}
		}
        
        $monthYear = $this->dateFunctions->getMonthYear($date);
        
        return $this->nextAction(NULL, array('message'=>'eventupdated', 'month'=>$monthYear['month'], 'year'=>$monthYear['year'], 'events'=>$returnevents));
        
       
    }
    
    /**
	* Method to delete an event
    *
    * @param string $id Record Id of the Event
	*/
    function deleteEvent($id)
    {
        // Get the event from the database
        $event = $this->objCalendar->getSingle($id);
        
        $returnArray = array();
        
        // get the date - this is necessary for the redirect
        $date = $event['eventdate'];
        
        if (count ($event) != 0) {
            $monthYear = $this->dateFunctions->getMonthYear($date);
            $returnArray['year'] = $monthYear['year'];
            $returnArray['month'] = $monthYear['month'];
        }
        
        // Check if the event belongs to the user - to prevent hacking via URL
        if ($this->checkEventEditPermission($event)) {
            $this->objCalendar->deleteBatch($id);
            $returnArray['message'] = 'eventdeleted'; 
			if ($event['userorcontext'] == '1' && $event['context'] == 'root') {
				$returnArray['events'] = 'site';
			}
			
        } else {
			$returnArray['message'] = 'notallowedtodelete';
		}

		$this->objAttachments->deleteAllFiles($id);

        
        // Return to Calendar
        return $this->nextAction(NULL, $returnArray);
    }
	
	/**
	* Method to show the iframe containing the attachments when adding or editing events
	* @param string $id Temporary Id of event when adding, or Record Id when editing
	* @param string $mode - either 'add' or 'edit'
	*/
	function attachmentWindow($id, $mode)
	{
		$this->setLayoutTemplate(NULL);
		
		$this->setVar('pageSuppressIM', TRUE);
        $this->setVar('pageSuppressToolbar', TRUE);
        $this->setVar('pageSuppressBanner', TRUE);
        $this->setVar('pageSuppressContainer', TRUE);
        $this->setVar('suppressFooter', TRUE);
		
		$this->setVarByRef('id', $id);
		$this->setVarByRef('mode', $mode);
		
		if ($mode == 'add') {
			$files = $this->objAttachments->listFiles($id);
		} else if ($mode == 'edit') {
			$files = $this->objAttachments->listFiles($id);
		}
		
		$this->setVarByRef('files', $files);
		
		return 'attachment_window.php';
	}
	
	/**
	* Method to upload an attachment
	*/
	function uploadAttachment()
	{
		$id = $_POST['id'];
		$mode = $_POST['mode'];
		try {
			$this->objAttachments->uploadFile($id);
		}
		catch (CustomException $e)
		{
			die($e);
		}
//		if ($_FILES['userFile']['error'] != 4) {
//			$fileId = 
//			if ($mode == 'add') {
//				$this->objTempAttachments->insertSingle($id, $fileId, $this->userId);
//			} else if ($mode == 'edit') {
//				$this->objEventAttachments->insertSingle($fileId, $id, $this->userId);
//			}
//		} 
		return $this->nextAction('tempframe', array('id'=>$id, 'mode'=>$mode));
	}
	
	/**
	* Method to Download an Attachment
	* @param string $id Record Id of the attachment
	* @param string $event Record Id of the Event
	*/
	function downloadAttachment($id, $event)
	{
		$file = $this->objEventAttachments->getFile($id, $event);
		
		if ($file == FALSE) {
			return $this->nextAction(NULL, array('error'=>'attachment'));
		} else {
			$this->objUploader->outputFile($file['attachment_id'], TRUE);
		}
	}
	
	function deleteAttachment($id, $mode, $filename)
	{
		$this->objAttachments->deleteFile($id, $filename);
		return $this->nextAction('tempframe', array('id'=>$tempId, 'mode'=>$mode));
	}
	
	/**
	* Method to check whether the user has access to edit an event
	* @param array $event Event Details
	* @return True if user has access, or redirects to screen with pop up message
	*/
	function checkEventEditPermission($event)
	{
		// If the event does not exists, return to the Calendar
        if ($event == FALSE) {
            return $this->nextAction(NULL, array('message'=>'eventeditnotexists'));
        }
		
		// Default to Access
		$okToEdit = TRUE;
		
		
		// Check if User has access to edit the event
//		if ($event['userorcontext'] == 0 && $event['userFirstEntry'] != $this->userId) {
//			$okToEdit = FALSE;
//		}
		//echo $okToEdit;
		// Check that event is either for the current context or a site event
//		if ($event['userorcontext'] == 1 && ($event['context'] == $this->contextCode || $event['context'] == 'root')) {
//			$okToEdit = TRUE;
//		} else if ($event['userorcontext'] == 1) { // Additional if to only use context events
//			$okToEdit = FALSE;
//		}
		
		// If event is for current event - check if user is lecturer
//		if ($event['userorcontext'] == 1 && $event['context'] == $this->contextCode && !$this->isContextLecturer) {
//			$okToEdit = FALSE;
//		}
		
		// If event is a site event - check if user is admin
//		if ($event['userorcontext'] == 1 && $event['context'] == 'root' && !$this->manageSiteCalendar) {
//			$okToEdit = FALSE;
//		}
		
		// Redirect if no permission
		if ($okToEdit == FALSE) {
			return $this->nextAction(NULL, array('message'=>'notallowedtoedit'));
		} else {
			return TRUE;
		}
		
		
	}
    
    
}

?>