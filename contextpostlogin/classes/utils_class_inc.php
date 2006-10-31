<?php
// security check - must be included in all scripts
if ( !$GLOBALS['kewl_entry_point_run'] ) {
    die( "You cannot view this page directly" );
} 
// end security check
/**
 * The context postlogin controls the information 
 * of courses that a user is registered to and the tools
 * that goes courses
 * 
 * @author Wesley Nitsckie
 * @copyright 2004, University of the Western Cape & AVOIR Project
 * @license GNU GPL
 * @package context
 */

class utils extends object 
{
    
    /**
     * The constructor
     */
    public function init()
    {
        
          $this->_objContextModules = & $this->newObject('dbcontextmodules', 'context');
	      $this->_objLanguage = & $this->newObject('language', 'language');
	      $this->_objUser = & $this->newObject('user', 'security');
	      $this->_objDBContext = & $this->newObject('dbcontext', 'context');
    }
    
    /**
     * Method to get the widgets
     * 
     */
    public function getWidgets()
    {
        
        
    }
    
    /**
     * Method to get the context for this user
     * 
     */
    public function getContexts($userId)
    {
        
        
    }
    
    /**
	   * Method to get the users context that he
	   * is registered to
	   * @return array
	   * @access public
	   */
	  public function getContextList()
	  {
	  	
	  	$objGroups = & $this->newObject('managegroups', 'contextgroups');
	  	$contextCodes = $objGroups->usercontextcodes($this->_objUser->userId());
	  	
	  	$arr = array();
	  	foreach ($contextCodes as $code)
	  	{
	  		$arr[] = $this->_objDBContext->getRow('contextcode',$code); 
	  		
	  	}
	  	//print_r($arr);
	  	return $arr;
	  }
	  
	  /**
	   * Method to get the users context that he
	   * is registered to
	   * @return array
	   * @access public
	   */
	  public function getOtherContextList()
	  {
	  	
	  	$objGroups = & $this->newObject('managegroups', 'contextgroups');
	  	return null;//$objGroups->usercontextcodes($this->_objUser->userId());
	  }
	  
	  /**
	   * Method to get the left widgets
	   * @return string
	   * @access public
	   */
	  public function getLeftContent()
	  {
	  	//Put a block to test the blocks module
		$objBlocks = & $this->newObject('blocks', 'blocks');
		$leftSideColumn = '';
		//Add loginhistory block
		$leftSideColumn .= $objBlocks->showBlock('calendar', 'eventscalendar');
		$leftSideColumn .= $objBlocks->showBlock('loginstats', 'context');
		//Add guestbook block
		$leftSideColumn .= $objBlocks->showBlock('guestinput', 'guestbook');
		//Add latest search block
		$leftSideColumn .= $objBlocks->showBlock('lastsearch', 'websearch');
		//Add the whatsnew block
		$leftSideColumn .= $objBlocks->showBlock('whatsnew', 'whatsnew');
		//Add random quote block
		$leftSideColumn .= $objBlocks->showBlock('rquote', 'quotes');
		$leftSideColumn .= $objBlocks->showBlock('today_weather','weather');
	      return $leftSideColumn;
	  }
	   
	  
	  /**
	   * Method to get the right widgets
	   * @return string
	   * @access public
	   */
	  public function getRightContent()
	  {
	     $rightSideColumn = "";
	     $objBlocks = & $this->newObject('blocks', 'blocks');
		//Add the getting help block
		$rightSideColumn .= $objBlocks->showBlock('dictionary', 'dictionary');
		//Add the latest in blog as a a block
		$rightSideColumn .= $objBlocks->showBlock('latest', 'blog');
		//Add the latest in blog as a a block
		$rightSideColumn .= $objBlocks->showBlock('latestpodcast', 'podcast');
		//Add a block for chat
		$rightSideColumn .= $objBlocks->showBlock('chat', 'chat');
		//Add a block for the google api search
		$rightSideColumn .= $objBlocks->showBlock('google', 'websearch');
		//Put the google scholar google search
		$rightSideColumn .= $objBlocks->showBlock('scholarg', 'websearch');
		//Put a wikipedia search
		$rightSideColumn .= $objBlocks->showBlock('wikipedia', 'websearch');
		//Put a dictionary lookup
		
		return $rightSideColumn;
	  } 
	  
	  
	  /**
	   * Method to get the Lectures for a course
	   * @param string $contextCode The context code
	   * @return array
	   * @access public
	   */
	  public function getContextLecturers($contextCode)
	  {
	  		$objLeaf = $this->newObject('groupadminmodel', 'groupadmin');
	  		$leafId = $objLeaf->getLeafId(array($contextCode,'Lecturers'));
	  		
	  		$arr = $objLeaf->getSubGroupUsers($leafId);
	  		
	  		return $arr;
	  		
	  }
	  
	  /**
	   * Method to get a plugins for a context 
	   * @param string $contextCode The Context Code
	   * @return string 
	   * @access public
	   * 
	   */
	  public function getPlugins($contextCode)
	  {
	  	$str = '';
	  	$arr = $this->_objContextModules->getContextModules($contextCode);
	  	$objIcon = & $this->newObject('geticon', 'htmlelements');
	  	$objModule = & $this->newObject('modules', 'modulecatalogue');
	  	if(is_array($arr))
	  	{
	  		foreach($arr as $plugin)
	  		{
	  			
	  			$modInfo =$objModule->getModuleInfo($plugin['moduleid']);
	  			
	  			$objIcon->setModuleIcon($plugin['moduleid']);
	  			$objIcon->alt = $modInfo['name'];
	  			$str .= $objIcon->show().'   ';
	  		}
	  		
	  		return $str;
	  	} else {
	  		return '';
	  	}
	  	
	  }
}	
?>