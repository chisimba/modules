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
    public function addLink($contextCode)
    {
        try 
        {
        
            $moduleId = $this->getParam('moduleid');
            $menutext = $this->getParam('menutext');
            $linkid = $this->getParam('linkid');
            
            $fields = array(
                'contextcode' => $contextCode,
                'moduleid' => $moduleId,
                'menutext' => $menutext,
                'linkid' => $linkid,
                'linkorder' => $this->getLastOrderPosition(),
                'access' => 'Pubblished'
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
    
}