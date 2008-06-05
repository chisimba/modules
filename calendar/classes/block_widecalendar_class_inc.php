<?php
/**
* Handles attachments to events.
*/
class block_widecalendar extends object
{
    public function init()
    {
        $this->title = 'Big Calendar';
        $this->objCalendarInterface = $this->getObject('calendarinterface');
    }
    
    public function show()
    {
        $month = $this->getParam('month', date('m'));
        $year = $this->getParam('year', date('Y'));
        
        $this->objCalendarInterface->setupCalendar($month, $year);
        
        $eventsCalendar = $this->objCalendarInterface->getCalendar();
        
        return $this->objCalendarInterface->getNav().$this->objCalendarInterface->getCalendar();
    }
}

?>