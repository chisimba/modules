<?php
/* ----------- data class extends dbTable for tbl_calendar------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }


/**
* User Calendar Functionality Class
*
* This class is sort of an extension to dbcalendar in the calendarbase module.
*
* It uses a modified version of methods as to that in dbcalendar, but
* because of the structure of KEWL.Nextgen, can't extended the class
*
* Its methods are coded for user events.
*/
class contextcalender extends object
{
    var $eventsList; // an array to store current events
    
    
    /**
    * Constructor method to define the table
    */
    function init() {
        $this->objCalendar =& $this->getObject('dbcalendar', 'calendarbase');
        $this->objLanguage = &$this->getObject('language', 'language');
        $this->eventsList = NULL;
        
        // Set the calling module
        $this->objCalendar->module = 'contextcalendar';
        
        $this->contextObject =& $this->getObject('dbcontext', 'context');
 		$this->contextCode = $this->contextObject->getContextCode();
        
        // If not in context, set code to be 'root' called 'Lobby'
 		$this->contextTitle = $this->contextObject->getTitle();
 		if ($this->contextCode == ''){
 			$this->contextCode = 'root';
			$this->contextTitle = 'Lobby';
 		}
    }
    
    /**
    * Method to get the details of a single event by providing the record Id
    * 
    * @param string $id Record Id of the Event
    * @param string $userId User Id of the person to whom the event belongs
    * @return array|false Associative Array with the record, or false if it doesn't belong to the user
    */
    function getSingle($id, $contextCode)
    {
        $event = $this->objCalendar->getSingle($id);
        
        if ($event['context'] != $contextCode || $event['userorcontext'] != 1) {
            return FALSE;
        } else {
            return $event;
        }
    }

    /**
    * Method to insert a single day event into the database. 
    * 
    * @param string $date - Date of the Event
    * @param string $eventtitle - Title of the Event
    * @param string $eventdetails - Details of the event
    * @param string $eventurl - A URL for more information
    * @param string $contextCode - ContextCode of the Context
    * @param string $user - The user to whom the event belongs to
    * @param string $multidayevent - a flag to indicate whether this is a multiday event or not.
    * @param string $multidaystart - Record ID of the Start (First Day) of the multiday event
    * @return string $lastInsert - Record Id of the event that has just been added
    */
    function insertSingle($date, $eventtitle, $eventdetails, $eventurl, $contextCode, $userFirstEntry, $multidayevent = 0, $multidaystart = NULL)
    {
        $lastInsert = $this->objCalendar->insertSingle(
                $date, // Date of Event
                $multidayevent, // Is this a multiday event
                $multidaystart, // starting record id
                $eventtitle, // Title of Event
                $eventdetails, // Details of the event
                $eventurl, // Url
                1, // userorcontext
                $contextCode, // Context
                NULL, // workgroup
                0, // show users
                $userFirstEntry, // Use First Entry
                NULL, // User Last Modified
                strftime('%Y-%m-%d %H:%M:%S', mktime()), // date first entry
                strftime('%Y-%m-%d %H:%M:%S', mktime()) // date of last entry
            );
        return $lastInsert;
    }
    
    /**
    * Method to insert a multi day event into the database.
    * 
    * @param string $date - Date of the Event
    * @param string $date2 - Date when the event ends
    * @param string $multidaystart - Record ID of the Start (First Day) of the multiday event
    * @param string $eventtitle - Title of the Event
    * @param string $eventdetails - Details of the event
    * @param string $eventurl - A URL for more information
    * @param string $contextCode - ContextCode of the Context
    * @param string $user - The user to whom the event belongs to
    * @param string $userLastModified - User Id of the person who last updated the entry
    * @param string $dateFirstEntry - Date the first entry was made
    * @param string $dateLastModified - Date the entry was last updated.
    * @param string $eventStartId - Record ID of the Start (First Day) of the multiday event. If none is provided, the function will generate one.
    */
    function insertMultiDayEvent ($date, $date2, $eventtitle, $eventdetails, $eventurl, $contextCode, $userFirstEntry, $userLastModified = NULL, $dateFirstEntry = NULL, $dateLastModified = NULL, $eventStartId=NULL)
    {
        
        if ($dateFirstEntry == NULL) {
            $dateFirstEntry = strftime('%Y-%m-%d %H:%M:%S', mktime());
        }
        
        if ($dateLastModified == NULL) {
            $dateLastModified = strftime('%Y-%m-%d %H:%M:%S', mktime());
        }
        
        $lastInsert = $this->objCalendar->insertMultiDayEvent(
                $date, // Date of Event
                $date2, // End Date
                $eventtitle, // Title of Event
                $eventdetails, // Details of the event
                $eventurl, // Url
                1, // userorcontext
                $contextCode, // Context
                NULL, // workgroup
                0, // show users
                $userFirstEntry, // Use First Entry
                $userLastModified, // User Last Modified
                $dateFirstEntry, // date first entry
                $dateLastModified, // date of last entry
                $eventStartId
            );
        
        return $lastInsert;
    }
    
    /**
    * Method to update an event
    * 
    * @param string $id - Record If of the event
    * @param string $multidayevent - A flag to indicate whether this is a multiday event or not
    * @param string $date - Date of the Event
    * @param string $multidaystart - Record ID of the Start (First Day) of the multiday event
    * @param string $eventtitle - Title of the Event
    * @param string $eventdetails - Details of the event
    * @param string $eventurl - A URL for more information
    * @param string $contextCode - ContextCode of the Context
    * @param string $user - The user to whom the event belongs to
    * @param string $multiday_startid - Record ID of the Start (First Day) of the multiday event. If none is provided, the function will generate one.
    */
    function updateEvent ($id, $multidayevent, $date, $eventtitle, $eventdetails, $eventurl, $contextCode, $user, $multiday_startid = NULL)
    {
        if ($multidayevent == 0) {
            $multiday_startid = NULL;
        }
        
        $this->objCalendar->updateSingle(
                $id, 
                $multidayevent, // update
                $date, 
                $multiday_startid, // update
                $eventtitle, 
                $eventdetails, 
                $eventurl, 
                1, // userorcontext
                $contextCode, // Context
                NULL, // workgroup
                0, // show users
                $user, 
                strftime('%Y-%m-%d %H:%M:%S', mktime()) // date LAST entry
            );
        
        return;
    }
    
    /**
    * Method to delete a single event
    * 
    * @param string $id Record Id of the Event
    * @param string $userId User Id of the person to whom the event belongs
    */
    function deleteSingle($id, $contextCode)
    {
        $event = $this->objCalendar->getSingle($id);
        
        if ($event['context'] != $contextCode || $event['userorcontext'] != 1) {
            return;
        } else {
            return $this->objCalendar->deleteSingle($id);
        }
    }
    
    /**
    * Method to delete a multi day event event
    * 
    * @param string $multiEventId Record Id of the start of the multiday event
    */
    function deleteBatch($multiEventId)
    {
        $this->objCalendar->deleteBatch($multiEventId);
        return;	
    }
    
    /**
    * Method to delete a multi day event (children)
    * 
    * @param string $multiEventId Record Id of the start of the multiday event
    */
    function deleteMultiEventsChild($multiEventId)
    {
        $this->objCalendar->deleteMultiEventsChild($multiEventId);
        return;	
    }
    
    /**
    * Method to generate a calendar
    * 
    * @param string $userId User Id of the User
    * @param string $month Month to display the calendar for
    * @param string $year The year to display the calendar for
    */
    function generateContextCalendar($contextCode, $month = NULL, $year = NULL)
    {
        $this->objDT = &$this->getObject( 'decisiontable','decisiontable' );
        // Create the decision table for the current module
        $this->objDT->create('contextcalendar');
        // Collect information from the database.
        $this->objDT->retrieve('contextcalendar');
        
        if ($this->objDT->isValid('edit')) {
            $permission = TRUE;
        } else {
            $permission = FALSE;
        }
        
        $this->objCalendar->showEditDelete = $permission;
        
        return $this->objCalendar->generateCalendar('context', $contextCode, $month, $year);
    }
    
    /**
    * Method to get the start of a multi-day event
    * 
    * @param string $start_id Record Id of the start date
    */
    function getStartMultiDayEvent($start_id)
    {
        return $this->objCalendar->getStartMultiDayEvent($start_id);
    }
    
    /**
    * Method to get the end of a multi-day event
    * 
    * @param string $start_id Record Id of the start date
    */
    function getLastMultiDayEvent($start_id)
    {
        return $this->objCalendar->getLastMultiDayEvent($start_id);
    }
    
    /**
    * Method to show a small calendar with list of events. Used for the Personal Space module.
    */
    function show()
    {
        $objUser =& $this->getObject('user', 'security');
        
        
        $events2 = $this->objCalendar->getEvents('context', $this->contextCode, (date('Y-m-d')), NULL, 5);
        
        $title = '<h1>'.$this->objLanguage->languageText('word_calendar').'</h1>';
        
        $eventsList = $this->objCalendar->generateSmallListing ($events2, 'calendar');
        
        $calendar = $this->objCalendar->generateSmallCalendar('context', $this->contextCode);
        
        $uri = $this->uri(NULL, 'contextcalendar');
        
        $link = '<br><p><a href="'.$uri.'">'.$this->objLanguage->languageText('word_calendar').'</a></p>';
        
        return $title.' '.$calendar.'<br><br>'.$eventsList.$link ;
    }



} #end of class
?>