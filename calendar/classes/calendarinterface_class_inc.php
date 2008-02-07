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
class calendarinterface extends object
{
    
    private $preparedEventsArray = array();
    
    public $month;
    public $year;
    
    
    /**
    * Constructor method to define the table
    */
    function init()
    {
        $this->objCalendar = $this->getObject('dbcalendar', 'calendarbase');
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objDateFunctions = $this->getObject('dateandtime','utilities');
        
        $this->month = date('m');
        $this->year = date('Y');
    }
    
    public function resetEventsArray()
    {
        $this->preparedEventsArray = array();
    }
    
    
    
    private function getEvents($origin, $id, $month, $year)
    {
        if (!isset($month)) {
            $month = $this->month;
        }

        if (!isset($year)) {
            $year = $this->year;
        }
        
        $startDate = $year.'-'.$month.'-01';
        $endDate = $this->objDateFunctions->lastDateMonth($month, $year);
        
        return $this->objCalendar->getEvents($origin, $id, $startDate, $endDate);
    }
    
    public function getUserEvents($userId, $month=NULL, $year=NULL)
    {
        return $this->getEvents('user', $userId, $month, $year);
    }
    
    public function getContextEvents($contextCode, $month=NULL, $year=NULL)
    {
        return $this->getEvents('context', $contextCode, $month, $year);
    }
    
    public function getSiteEvents($month=NULL, $year=NULL)
    {
        return $this->getEvents('context', 'root', $month, $year);
    }
    
    /**
    * Method to take a list of events and prepare them in a format for adding to the calendar class.
    * Amongst others, it removed duplication in events, etc.
    *
    * @param array $events List of Events
    * @return array $preparedArray List of events ready to be sent to the calendar class
    */
    public function prepareEventsForCalendar (&$events, $type)
    {
        $objTrim =& $this->getObject('trimstr', 'strings');

        foreach ($events as $event)
        {
            $day = $this->objDateFunctions->dayofMonth($event['eventdate']);

            switch ($type)
            {
                case 'user':       $css = 'event_user'; break;
                case 'context':    $css = 'event_context'; break;
                case 'site':       $css = 'event_site'; break;
                case 'other':      $css = 'event_other'; break;
                case 'workgroup':  $css = 'event_workgroup'; break;
                default:           $css = ''; break;
            }

            if (array_key_exists($day, $this->preparedEventsArray)) {
                $temp = rtrim ($this->preparedEventsArray[$day], '</ul>');
                $this->preparedEventsArray[$day] = $temp.'<li class="'.$css.'" title="'.stripslashes($event['eventtitle']).'">'.$objTrim->strTrim(stripslashes($event['eventtitle']), 8).'</li></ul>';
            } else {
                $this->preparedEventsArray[$day] = '<ul><li class="'.$css.'">'.stripslashes($event['eventtitle']).'</li></ul>';
            }
        }
    }
    
    public function getEventsArray()
    {
        return $this->preparedEventsArray;
    }
    
    public function getCalendar($size = 'big')
    {
        $objCalendarGenerator = $this->getObject('calendargenerator', 'calendarbase');
        
        $objCalendarGenerator->year = $this->year;
        $objCalendarGenerator->month = $this->month;
        $objCalendarGenerator->events = $this->preparedEventsArray;

        $objCalendarGenerator->size = $size;

        return $objCalendarGenerator->show ();
    }
    
    public function getNav()
    {
        $objIcon = $this->newObject('geticon', 'htmlelements');
        $objIcon->setIcon('prev');
        $previous = $this->objDateFunctions->previousMonthYear($this->month, $this->year);
        $prevLink = new link ($this->uri(array('month'=>$previous['month'], 'year'=>$previous['year'])));
        $prevLink->link = $objIcon->show().' '.$this->objDateFunctions->monthFull($previous['month']).' '.$previous['year'];
        
        $objIcon->setIcon('next');
        $next = $this->objDateFunctions->nextMonthYear($this->month, $this->year);
        $nextLink = new link ($this->uri(array('month'=>$next['month'], 'year'=>$next['year'])));
        $nextLink->link = $this->objDateFunctions->monthFull($next['month']).' '.$next['year'].' '.$objIcon->show();
        
        $this->loadClass('htmlheading', 'htmlelements');
        $header = new htmlheading();
        $header->str = $this->objDateFunctions->monthFull($this->month).' '.$this->year;
        
        //nextMonthYear
        $table = $this->newObject('htmltable', 'htmlelements');
        $table->startRow();
        $table->addCell($prevLink->show(), '30%', NULL, 'left');
        $table->addCell($header->show(), '40%', NULL, 'center');
        $table->addCell($nextLink->show(), '30%', NULL, 'right');
        $table->endRow();
        
        return $table->show();
    }
    

} #end of class
?>