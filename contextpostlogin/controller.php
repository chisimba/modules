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
	      $this->_objUtils = $this->newObject('utils', 'contextpostlogin');
	  } 
	  
	  /**
	   * The standard dispatch function
	   */
	  public function dispatch($action)
	  {
	      switch ($action){
	          default:
	          	$filter = $this->getParam('filter');
	            $this->setLayoutTemplate('main_layout_tpl.php');
	            
	            $tabBox = $this->_objUtils->showBox($filter);
	            $this->setVarByRef('tabBox', $tabBox);
	            
                //$Stories = $this->_objUtils->getStories();
                //$this->setVar('Stories', $Stories);
	            return 'main_tpl.php';
	      }
	  }
}

?>