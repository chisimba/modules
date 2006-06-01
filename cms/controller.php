<?php
/* -------------------- dbTable class ----------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check
/**
* The controller for the content management
* @package cms
* @category dbcategories
* @copyright 2004, University of the Western Cape & AVOIR Project
* @license GNU GPL
* @version
* @author Wesley  Nitsckie
* @example :
*/

class cms extends controller
{
	
	
	/**
     * The contextcore  object 
     *
     * @access private
     * @var object
    */
    protected $_objContextCore;
    
    /**
     * The sections  object 
     *
     * @access private
     * @var object
    */
    protected $_objSections;
    
    /**
     * The categories  object 
     *
     * @access public
     * @var object
    */
    protected $_objCategories;
    
        
    /**
     * The Content object 
     *
     * @access private
     * @var object
    */
    protected $_objContent;
    
     /**
     * The CMS Utilities object 
     *
     * @access private
     * @var object
    */
    protected $_objUtils;
    
    
    
    
	/**
	* Constructor
	*/
	public function init()
	{
		// instantiate object
        try{
        	//$this->_objContextCore = & $this->newObject('dbcontextcore', 'contextcore');
			$this->_objSections = & $this->newObject('dbsections', 'cmsadmin');
			$this->_objCategories = & $this->newObject('dbcategories', 'cmsadmin');
			$this->_objContent = & $this->newObject('dbcontent', 'cmsadmin');
			$this->_objUtils = & $this->newObject('cmsutils', 'cmsadmin');
        
        }catch (Exception $e){
       	echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
	
	}
	
	/** 
	* This is a method to determine if the user has to be logged in or not
    */
     public function requiresLogin() // overides that in parent class
     {
        return FALSE;

     }
	
	/**
	* The Dispatch  methed that the framework needs to evoke the controller
	*/
	public function dispatch()
	{
		try{
			
			$action = $this->getParam('action');
			$this->setLayoutTemplate('cms_layout_tpl.php');
	        switch ($action){
	            case null:
	            case 'home':
	            	$this->setVar('content', $this->_objUtils->getFrontPageContent());	
	            	return 'cms_main_tpl.php';
	            case 'showsection':
	            	$this->setVar('content', $this->_objUtils->showSection());
	            	return 'cms_section_tpl.php';
	            case 'showcontent':
	            case 'showfulltext':
	            	$this->setVar('content', $this->_objUtils->showBody());
	            	return 'cms_content_tpl.php';
	            
	            
	
	        }
         
        }catch (Exception $e){
       	echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
	}
	
	
	/**
	 * Method to get the Sections on the left side of the menu
	 * 
	 * @access public
	 * @return string
	 */
	public function getSectionMenu()
	{
		return $this->_objUtils->getSectionMenu();
	}


}

?>