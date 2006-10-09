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
class dbeventscalendarcategories extends dbTable
{
    
    /**
     * Constructor
     */
    public function init()
    {
        parent::init('tbl_eventscalendar_categories');
    }
    
    
    /**
     * Method to get all the categories for a user
     * @param string userid The user id
     * @return array
     * @access public
     * 
     */
    public function getUserCategories($userid)
    {
        
        return $this->getAll('WHERE userid = "'.$userid.'"');
    }
   
    
    /**
     * Method to insert an event category
     * @param userId The userId
     * @return boolean
     * @access string the new id
     */
    public function addCategory($userId)
    {
        try
        {            
            $title = $this->getParam('title');
            $colour = $this->getParam('pick1159262324');
          
            $fields = array (
                    'title' => $title,
                    'colour' => $colour,
                    'userid' => $userId                
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
     * Method to edit an event category
     * @param catId The event Id
     * @return boolean
     * @access public
     */
    public function editCategory($catId)
    {
        try 
        {
            $title = $this->getParam('title');
            $colour = $this->getParam('pick1159262324');
            
            $fields = array (
                    'title' => $title,
                    'colour' => $colour    
            
            );
            
            return $this->update('id', $catId, $fields);
        }
        catch (customException $e)
        {
        	echo customException::cleanUp($e);
        	die();
        }
        
    }
    
    /**
     * Method to get the colour of a category
     * @param stirng $catid The catigory id
     * @return string
     * @access public
     */
    public function getCategoryColour($catId)
    {
        
        $line = $this->getRow('id', $catId);
        return $line['colour'];
    }
    
    /**
     * Method to check if there is any records in categories tables
     * 
     * @return bool
     * @access public
     */
    public function isCategories()
    {
        if($this->getRecordCount() > 0)
        {
        	return true;
        } else {
        	return false;
        }
        
    }
}