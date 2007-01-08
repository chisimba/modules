<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check
/**
 * The context designer database base object
 * 
 * @author Wesley Nitsckie
 * @copyright 2004, University of the Western Cape & AVOIR Project
 * @license GNU GPL
 * @package contextdesigner
 **/

class dbcontextdesigner extends dbTable
{
    
    /**
     * The Constructor
     * 
     */
    public function init()
    {
        
        parent::init('tbl_contextdesigner_links');

        $this->_objLanguage=& $this->newObject('language', 'language');
        $this->_objDBContext=& $this->newObject('dbcontext', 'context');
        $this->_contextCode = $this->_objDBContext->getContextCode();
    }
    
    /**
     * The add method 
     * 
     * @param string $contextCode The ContextCode
     * @return boolean
     * @abstract 
     * @access public
     */
    public function addLinks($contextCode)
    {
        try 
        {
            
            foreach($this->getParam('selecteditems') as $row)
            {
                $selectedItemsArr = split('===',$row);    
               
           
                
                $moduleId = $this->getParam('moduleid');
                $menutext = $selectedItemsArr[1];
                $linkid = $selectedItemsArr[0];
                //print  $selectedItemsArr[1];
                
                $fields = array(
                    'contextcode' => $contextCode,
                    'moduleid' => $moduleId,
                    'menutext' => $menutext,
                    'linkid' => $linkid,
                    'linkorder' => $this->getLastOrderPosition($contextCode),
                    'access' => 'Published'
                );
                
                if(!$this->checkExist($contextCode, $linkid, $moduleId))
                {
                    $this->insert($fields);
                }
            
            }
        }                        
        catch (customException $e)
        {
        	echo customException::cleanUp($e);
        	die();
        }
        
    }
    
    /**
     * Method to get a the links for a current context
     * 
     * @param string $contextCode The ContextCode
     * @return array|boolean
     * @access public
     */
    public function getContextLinks($contextCode = null)
    {
        $contextCode = (is_null($contextCode)) ? $this->_contextCode : $contextCode;
        
        $linksArr = $this->getAll('WHERE contextcode = "'.$contextCode.'" ORDER BY linkorder');
        if(count($linksArr) > 0)
        { 
            return $linksArr;
        } else {
            return false;
        }
            
    }
    
    /**
     * Method to reorder the links
     * 
     * @return boolean
     * @access public
     */
    public function reoder()
    {
        
    }
    
    /**
     * Method to get the last order position
     */
    public function getLastOrderPosition($contextCode)
    {
        $rows = $this->getAll('WHERE contextcode = "'.$contextCode.'"');
        return count($rows) + 1;
    }
    
    
    /**
     * Method to check if a record exists
     * 
     * 
     */
    public function checkExist($contextCode, $linkid, $moduleid)
    {
        
        $record = $this->getAll('WHERE contextcode="'.$contextCode.'" AND linkid="'.$linkid.'" AND moduleid="'.$moduleid.'"');
        if(count($record) > 0 )
        {
            return TRUE;
        } else {
            return FALSE;
            
        }
    }
}