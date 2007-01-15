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

class contextprelogin extends controller 
{
    
	  /**
	   * Constructor
	   */
	  public function init()
	  {
	      $this->_objUtils = & $this->newObject('utils', 'contextprelogin');
	      $this->_objLanguage = & $this->newObject('language', 'language');
	      $this->_objUser = & $this->newObject('user', 'security');
	      $this->_objDBContext = & $this->newObject('dbcontext', 'context');
	      $this->_objDBContextUtils = & $this->newObject('utilities', 'context');
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
	          	$filter = $this->getParam('filter');
	            $this->setLayoutTemplate('main_layout_tpl.php');
	            $this->setVar('contextList', $this->_objUtils->getContextList());
	           // $this->setVar('otherCourses', $this->_objUtils->getOtherContextList($this->_objUtils->getContextList(),$filter));
	            $this->setVar('publicCourses', $this->_objUtils->getPublicContextList($this->_objUtils->getContextList(),$filter));
	            $this->setVar('filter', $this->_objUtils->getFilterList($this->_objUtils->getContextList()));
	            
	            return 'main_tpl.php';
	            
	      }
	  }
	    
	  
	  
	   /** This is a method to determine if the user has to be logged in or not
    */
     public function requiresLogin() // overides that in parent class
     {
        return FALSE;

     }
}

?>