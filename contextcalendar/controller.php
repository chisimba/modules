<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
// end security check

/**
* Context Calendar Controller
* This class controls all functionality to run the context calendar module
* @author Tohir Solomons
* @copyright (c) 2004 University of the Western Cape
* @package calendar
* @version 1
*/
class contextcalendar extends controller
{

    /**
	* Constructor method to instantiate objects and get variables
	*/
    function init()
    {
        $this->contextCalendar =& $this->getObject('contextcalender');
        $this->dateFunctions =& $this->getObject('datefunctions', 'calendarbase');
        
        // User Details
        $this->objUser =& $this->getObject('user', 'security');
        $this->userId =& $this->objUser->userId();
        
        // Get Context Code Settings
        $this->contextObject =& $this->getObject('dbcontext', 'context');
 		$this->contextCode = $this->contextObject->getContextCode();
        
        // If not in context, set code to be 'root' called 'Lobby'
 		$this->contextTitle = $this->contextObject->getTitle();
 		if ($this->contextCode == ''){
 			$this->contextCode = 'root';
			$this->contextTitle = 'Lobby';
 		}
        
        $this->setVarByRef('fullname', $this->contextTitle);
        
        // Load Language Class
        $this->objLanguage = &$this->getObject('language', 'language');
        $this->setVarByRef('objLanguage', $this->objLanguage);
        
        //Get the activity logger class
        $this->objLog=$this->newObject('logactivity', 'logger');
        //Log this module call
        $this->objLog->log();
    }
    
    /**
	* Method to process actions to be taken
    *
    * @param string $action String indicating action to be taken
	*/
    function dispatch($action=Null)
    {
        // Only show Context Layout, if not in root
        if ($this->contextCode != 'root') {
            $this->setLayoutTemplate('context_layout_tpl.php');
        }
        
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
        
        $contextEvents = $this->contextCalendar->generateContextCalendar($this->contextCode, $month, $year);
        $this->setVarByRef('eventsCalendar', $contextEvents);
        return 'calendar_tpl.php';
    }
    
    /**
    * Method to show the Add Event Form
    */
    function showAddForm()
    {
        $this->setVar('mode', 'add');
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
        
        // Check if day is a multiday event or not
        if (($multidayevent == 1) && ($date != '') && ($date2 != '')) {
            // Insert Multidate event
            $this->contextCalendar->insertMultiDayEvent ($date, $date2, $eventtitle, $eventdetails, $eventurl, $this->contextCode, $this->userId, $this->userId);
        } else {
            // Insert Single Day event
            $event = $this->contextCalendar->insertSingle($date, $eventtitle, $eventdetails, $eventurl, $this->contextCode, $this->userId);
        }
        
        //print_r($_POST);
        $monthYear = $this->dateFunctions->getMonthYear($date);
        
        return $this->nextAction(NULL, array('message'=>'eventadded', 'month'=>$monthYear['month'], 'year'=>$monthYear['year']));
    }
    
    /**
	* Method to prepare a form for editing an event
    *
    * @param string $id Record Id of the event to edit
	*/
    function showEditForm($id)
    {
        $event = $this->contextCalendar->getSingle($id, $this->contextCode);
        
        // If the event does not exists, return to the Calendar
        if ($event == FALSE) {
            return $this->nextAction(NULL, array('message'=>'eventeditnotexists'));
        }
        
        // Check if the event is a multiday event or a single day
        if ($event['multiday_event'] == '1') {
            // If event start id is NULL, get the date of the first event
            if ($event['multiday_event_start_id'] != '') {
                $event['eventdate'] = $this->contextCalendar->getStartMultiDayEvent($event['multiday_event_start_id']);
            } else {
                // Else set event start id to current id
                $event['multiday_event_start_id'] = $event['id'];
            }
            // get date of last event
            $event['eventdate2']  = $this->contextCalendar->getLastMultiDayEvent($event['multiday_event_start_id']);
            
        } else {
            $event['eventdate2'] = '';
        }
        $this->setVarByRef('event', $event);
        $this->setVar('mode', 'edit');
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
        
        
        // To keep proper track, get the user who made the first entry
        $originalEvent = $this->contextCalendar->getSingle($id, $this->contextCode);
        $userFirstEntry = $originalEvent['userFirstEntry'];
        $dateFirstEntry = $originalEvent['dateFirstEntry'];
        
        if ($date2 != NULL) {
            $this->dateFunctions->smallDateBigDate($date, $date2);
        }
        
        if ($multiday_event_original != '') {
        
            if ($multidayevent == 1) { // continue with multi update
                
                
                /* 
                * The steps taken here are:
                * 1) It updates the existing record
                * 2) It deletes the child events
                * 3) It re-inserts the events
                */
                $this->contextCalendar->updateEvent($id, 1, $date, $eventtitle, $eventdetails, $eventurl, $this->contextCode, $this->userId);
                $this->contextCalendar->deleteMultiEventsChild($multiday_event_original);
                
                $this->contextCalendar->insertMultiDayEvent ($date, $date2, $eventtitle, $eventdetails, $eventurl, $this->contextCode, $userFirstEntry,  $this->userId,  $dateFirstEntry, NULL, $eventStartId=$id);
                
                
            } else { // switch to single day
                $this->contextCalendar->deleteMultiEventsChild($multiday_event_original);
                $this->contextCalendar->updateEvent($id, 0, $date, $eventtitle, $eventdetails, $eventurl, $this->contextCode, $this->userId);
            }
        } else {
            if ($multidayevent == 0) {
                $this->contextCalendar->updateEvent($id, 0, $date, $eventtitle, $eventdetails, $eventurl, $this->contextCode, $this->userId);
            } else {
            
                /* 
                * The steps taken here are:
                * 1) It updates the existing record
                * 3) Insert Multi Event
                */
                $this->contextCalendar->updateEvent($id, 1, $date, $eventtitle, $eventdetails, $eventurl, $this->contextCode, $this->userId);
                $this->contextCalendar->insertMultiDayEvent ($date, $date2, $eventtitle, $eventdetails, $eventurl, $this->contextCode,$userFirstEntry,  $this->userId,  $dateFirstEntry, NULL, $eventStartId=$id);
            }
        }
        
        $monthYear = $this->dateFunctions->getMonthYear($date);
        
        return $this->nextAction(NULL, array('message'=>'eventupdated', 'month'=>$monthYear['month'], 'year'=>$monthYear['year']));
        
       
    }
    
    /**
	* Method to delete an event
    *
    * @param string $id Record Id of the Event
	*/
    function deleteEvent($id)
    {
        // Get the event from the database
        $event = $this->contextCalendar->getSingle($id, $this->contextCode);
        
        $returnArray = array();
        
        // get the date - this is necessary for the redirect
        $date = $event['eventdate'];
        
        if (count ($event) != 0) {
            $monthYear = $this->dateFunctions->getMonthYear($date);
            $returnArray['year'] = $monthYear['year'];
            $returnArray['month'] = $monthYear['month'];
        }
        
        // Check if the event belongs to the user - to prevent hacking via URL
        if ($event['userFirstEntry'] == $this->userId) {
            $this->contextCalendar->deleteBatch($id);
            $returnArray['message'] = 'eventdeleted';
        } 
        
        // Return to Calendar
        return $this->nextAction(NULL, $returnArray);
    }
    
    
}

?>