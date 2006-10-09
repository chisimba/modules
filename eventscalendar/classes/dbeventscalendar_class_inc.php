<?php
/* ----------- data class extends dbTable for tbl_calendar------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }

/**
* Model class for the table tbl_eventscalendar
*
* @author Wesley Nitsckie
* @copyright (c) 2005 University of the Western Cape
* @package eventscalendar
* @version 1
*
* 
*/
class dbeventscalendar extends dbTable
{
    
    /**
     * Constructor
     */
    public function init()
    {
    	
    	
        parent::init('tbl_eventscalendar');
    }
    
    
    /**
     * Method to get the events for a givent user
     * @param userId $the User Id
     * @return array
     * @access public
     * 
     */
    public function getUserEvents($userId, $month = 0 , $yr = 0)
    {
    	
    	if(yr == 0 )
    	{
    		$yr = date("Y");
    	}
    	
    	if($month == 0 )
    	{
    		$month = date("m");
    	}
    	
    
    	//get the start of the month
    	$startOfMonth = mktime(0,0,0,$month,1,$yr);
    
    	$lastDayOfTheMonth = date("t",mktime(23,59,59,$month, 0, $yr));
    	
    	//get the end of the month
    	$endOfMonth = mktime(23,59,59, $month , $lastDayOfTheMonth , $yr);
    	
    	
    	//get the events for the user for the given month and year
   		
    	$sql = 'WHERE userid="'.$userId.'" and event_date BETWEEN  '.$startOfMonth.' AND  '.$endOfMonth;
  
        return $this->getAll($sql);
    }
    
    /**
     * Method to insert an event
     * @param userId The userId
     * @return boolean
     * @access string the new id
     */
    public function addEvent($userId)
    {
        try
        {            
            $title = $this->getParam('title');
            $description = $this->getParam('description');
            $start_time = $this->getParam('start_time');
            $end_time = $this->getParam('end_time');
            $catid = $this->getParam('catid');
            $start_date = $this->getParam('start_date');
            $location = $this->getParam('location');
           
            $fields = array (
                    'title' => $title,
                    'description' => $description,
                    'start_time' => $start_time,
                    'end_time' => $end_time,
                    'event_date' => $start_date,
                    'catid' => $catid,
                    'userid' => $userId,
                    'location' => $location
            
            );
           
            return $this->insert($fields);
        }
        catch (customException $e)
        {
        	echo customException::cleanUp($e);
        	die();
        }
    }
    
    /**
     * Method to edit an event
     * @param eventId The event Id
     * @return boolean
     * @access public
     */
    public function editEvent($eventId)
    {
        try 
        {
            $title = $this->getParam('title');
            $description = $this->getParam('description');
            $start_time = $this->getParam('start_time');
            $end_time = $this->getParam('end_time');
            $catid = $this->getParam('catid');
            $start_date = $this->getParam('start_date');
            $location = $this->getParam('location');
            
            $fields = array (
                    'title' => $title,
                    'description' => $description,
                    'start_time' => $start_time,
                    'end_time' => $end_time,
                    'start_date' => $start_date,
                    'catid' => $catid,
                    'location' => $location
            
            );
            
            return $this->update('id', $eventId, $fields);
        }
        catch (customException $e)
        {
        	echo customException::cleanUp($e);
        	die();
        }
        
    }
    
    /**
    * Method to get an event for a given date
    * @param $year The year
    * @param $month The month
    * @param $day The day
    * @return array|boolean
    * @access public
    */
    public function getEvents($year, $month, $day)
    {
    	$lines = $this->getUserEvents($this->_objUser->userId());
    	foreach ($lines as $line)
    	{
    			print $line['event_date'];;
    			
    	}
    	//mktime(0,0,0,$month, $day, $year);	
    }
}