<?php
/* ----------- data class extends dbTable for tbl_calendar------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }

/**
* class to control the utilty method for the events calendar
*
* @author Wesley Nitsckie
* @copyright (c) 2005 University of the Western Cape
* @package eventscalendar
* @version 1
*
* 
*/
class utils extends object
{
    
    /**
     * Constructor
     */
    public function init()
    {
       
    }
    
    /**
     * Method to get the navigation nodes
     * @return array
     * @access public
     * 
     */
   public function getNavNodes()
	{
	    $nodes = array();
		$nodes[] = array('text' => 'Events', 'uri' => $this->uri(null,'eventscalendar'));
		$nodes[] = array('text' => 'Event Categories', 'uri' => $this->uri(array('action' => 'categories'), 'eventscalendar'));
		
		
		return  $nodes;

	}
    
	/**
	 * Method to get the navigation
	 * @access public
	 * @return string
	 */
	public function getNav()
	{
	    $nodes = $this->getNavNodes();
	    $objNav = $this->newObject('sidebar', 'navigation');
		
		return $objNav->show($nodes);
	}
	
	
	  /**
   * Method to get a time dropdown
   * @return string
   * @param $minute The selected minute
   * @param $hour The selected hour
   */
   public function getTimeDropDown($hour = null , $minute = null)
   {
   		
   		
   		$str = '<select name="hours" id="hours">';
   		for($i = 0; $i < 24; $i++)
   		{
			$str .= '<option value="'.$i.'">'.$i.'</option>';   				
   		}
   		$str .= '</select>h ';
   		
   		
   		
   		$str .= '<select name="minutes" id="minutes">';
   		for($i = 0; $i < 60; $i++)
   		{
			$str .= '<option value="'.$i.'">'.$i.'</option>';   				
   		}
   		$str .= '</select>';
   		return $str;
   }
}
?>