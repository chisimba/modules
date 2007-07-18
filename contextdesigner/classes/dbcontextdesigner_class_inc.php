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
                $description = $selectedItemsArr[2];
                $linkid = $selectedItemsArr[0];
                $params = $this->getParam($linkid);//print  $selectedItemsArr[1];
             
                $fields = array(
                    'contextcode' => $contextCode,
                    'moduleid' => $moduleId,
                    'title' => $description,
                    'menutext' => $menutext,
                    'linkid' => $linkid,
                    'linkorder' => $this->getLastOrderPosition($contextCode),
                    'access' => 'Published',
                    'params' => $params
                );
                
				 $this->insert($fields);             
            
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
        
        $linksArr = $this->getAll("WHERE contextcode = '".$contextCode."' ORDER BY linkorder");
        if(count($linksArr) > 0)
        { 
            return $linksArr;
        } else {
            return false;
        }
            
    }
    
    /**
     * Method to get a the links for a current context
     * 
     * @param string $contextCode The ContextCode
     * @return array|boolean
     * @access public
     */
    public function getPublishedContextLinks($contextCode = null)
    {
       
         $contextCode = (is_null($contextCode)) ? $this->_contextCode : $contextCode;
  
        $linksArr = $this->getAll("WHERE contextcode = '{$contextCode}' AND access='Published' ORDER BY linkorder");
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
    public function reOrder()
    {
        $recordsArr = $this->getParam('reorder');
       
        $all = $this->getAllLinks();
       
        $i=0;
        foreach ($all as $record)
        {
          
            $this->update('id',$record['id'],array('linkorder' => $recordsArr[$i]));
            $i++;
        }
        
        $this->ReOrderLinks();
        
    }
    
    /**
     * Method to get the last order position
     * @return integer
     * @access public 
     */
    public function getLastOrderPosition($contextCode)
    {
        $rows = $this->getAll("WHERE contextcode = '".$contextCode."'");
        return count($rows) + 1;
    }
    
    
    /**
     * Method to check if a record exists
     * 
     * @return integer
     * @return boolean
     */
    public function checkExist($contextCode, $params, $moduleid)
    {
        
        $record = $this->getAll("WHERE contextcode='".$contextCode."' AND params='".$params."' AND moduleid='".$moduleid."'");
        
        if(count($record) > 0 )
        {
            return TRUE;
        } else {
            return FALSE;
            
        }
    }
    
    /**
     * Method to update the access field of a link
     * @param string $id The Id
     * @param string $value
     * @return boolean
     * @access public
     */
    public function updateAccess($id, $value)
    {
        
        return $this->update('id', $id, array('access' => $value));
    }
    
    /**
     * Method to delete a link
     * and reorder the links
     * @param string $id
     * @access public
     * @return boolean
     */
    public function deleteLink($id)
    {
        
        $this->delete('id', $id);
        $this->ReOrderLinks();
        
        return TRUE;    
    }
    
    
    /**
     * Method to reorder the links
     * 
     * @param 
     * @return boolean
     * @access public
     * 
     */
    public function ReOrderLinks()
    {
        $records = $this->getAllLinks();
       
        $i = 1;
        foreach($records as $record)
        {
            $this->update('id',$record['id'],array('linkorder' => $i, 'updated' => microtime()));
            $i++;
           
        }
        
        return TRUE;
    }
    
    /**
     * Methods to get all the current context links
     * @return array
     * @access public
     * 
     */
    public function getAllLinks()
    {
        
        return $this->getAll("WHERE contextcode = '".$this->_contextCode."' ORDER BY linkorder, updated");
    }
    
    /**
     * Method to move a record one position up
     * 
     * @param string $id
     * @return boolean
     * @access public
     */
    public function moveUp($id)
    {
        //get the two records involved with the move
        $lowerRecord = $this->getRow('id', $id);
     
        $position = intval($lowerRecord['linkorder']) - 1;
        $upperRecord = $this->getAll("'WHERE contextcode='".$this->_contextCode."' AND linkorder= ". $position );
        
        if(count($upperRecord[0]) > 0)
        {
           
            //do the swop
            $this->update('id',$upperRecord[0]['id'],array('linkorder' => $lowerRecord['linkorder']));
            $this->update('id',$lowerRecord['id'],array('linkorder' => $upperRecord[0]['linkorder']));
        }
        
        return true;
       
    }
    
    /**
     * Method to move a record one position up
     * 
     * @param string $id
     * @return boolean
     * @access public
     */
    public function moveDown($id)
    {
        //get the two records involved with the move
        $upperRecord = $this->getRow('id', $id);
     
        $position = intval($upperRecord['linkorder']) + 1;
        $lowerRecord = $this->getAll("WHERE contextcode='".$this->_contextCode."' AND linkorder= ". $position );
        
        if(count($lowerRecord[0]) > 0)
        {
           
            //do the swop
            $this->update('id',$upperRecord['id'],array('linkorder' => $lowerRecord[0]['linkorder']));
            $this->update('id',$lowerRecord[0]['id'],array('linkorder' => $upperRecord['linkorder']));
            
        }
        
        return true;
     
    }
}