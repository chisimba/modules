<?php
/**
* Handles attachments to events.
*/
class block_smallcalendar extends object
{
    public function init()
    {
        $this->title = 'Small Calendar';
        $this->objCalendarInterface = $this->getObject('calendarinterface');
    }
    
    public function show()
    {
        $month = $this->getParam('month', date('m'));
        $year = $this->getParam('year', date('Y'));
        
        $this->objCalendarInterface->calendarSize = 'small';
        
        $this->objCalendarInterface->setupCalendar($month, $year);
        
        return $this->objCalendarInterface->getCalendar('small').$this->objCalendarInterface->getSmallEventsList();
    }
}

?>