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

class contextpostlogin extends controller 
{
    
	  /**
	   * Constructor
	   */
	  public function init()
	  {
	      $this->_objUtils = & $this->newObject('utils', 'contextpostlogin');
	      $this->_objLanguage = & $this->newObject('language', 'language');
	      $this->_objUser = & $this->newObject('user', 'security');
	      $this->_objDBContext = & $this->newObject('dbcontext', 'context');
	  }
	    
	  
	  /**
	   * The standard dispatch function
	   */
	  public function dispatch()
	  {
	      
	      $action = $this->getParam('action');
	      
	      switch ($action)
	      {
	          
	          case '':
	          case 'default':
	            $this->setLayoutTemplate('main_layout_tpl.php');
	            $this->setVar('courseList', $this->getContextList());
	            $this->setVar('otherCourses', $this->getOtherContextList());
	            return 'main_tpl.php';
	      }
	  }
	    
	  /**
	   * Method to get the left widgets
	   * @return string
	   * @access public
	   */
	  public function getLeftContent()
	  {
	      
	  }
	   
	  
	  /**
	   * Method to get the right widgets
	   * @return string
	   * @access public
	   */
	  public function getRightContent()
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
	  	print_r($contextCodes);
	  	
	  	return $contextCodes;
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
}

?>