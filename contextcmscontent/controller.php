<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
} 
// end security check
/**
 * The class contextcmscontent uses the context as abstracted layer and
 * this class to control the content for the context
 * 
 * @package contextcmscontent
 * @category contextcmscontent
 * @copyright 2004, University of the Western Cape & AVOIR Project
 * @license GNU GPL
 * @version
 * @author Wesley Nitsckie 
 */

class contextcmscontent extends controller {
    
    
     /**
     * The dbcontextcmscontent  object 
     *
     * @access private
     * @var object
    */
    protected $_objDBContextCMSContent;
    
    
	
	
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
     * The FrontPage object 
     *
     * @access private
     * @var object
    */
    protected $_objFrontPage;
    
    
    
     /**
     * The CMS Utilities object 
     *
     * @access private
     * @var object
    */
    protected $_objUtils;
    
    
     /**
     * The user object 
     *
     * @access private
     * @var object
    */
    protected $_objUser;
    
    
    /**
     * The config object 
     *
     * @access private
     * @var object
    */
    protected $_objConfig;
    
    /**
     * The context code
     *
     * @access private
     * @var string
    */
    protected $contextCode;
    
    
    
    
	/**
	* Constructor
	*/
	public function init()
	{
		// instantiate object
        try{
        	
			$this->_objSections = & $this->newObject('dbsections', 'cmsadmin');
			$this->_objCategories = & $this->newObject('dbcategories', 'cmsadmin');
			$this->_objContent = & $this->newObject('dbcontent', 'cmsadmin');
			$this->_objCMSUtils = & $this->newObject('cmsutils', 'cmsadmin');
			$this->_objUtils = & $this->newObject('utils', 'contextcmscontent');
			$this->_objUser = & $this->newObject('user', 'security');
        	$this->_objFrontPage = & $this->newObject('dbcontentfrontpage', 'cmsadmin');
        	$this->_objConfig = & $this->newObject('altconfig', 'config');
        	$this->_objDBContextCMSContent = $this->newObject('dbcontextcmscontent', 'contextcmscontent' );
        	$this->_objContext = & $this->newObject('dbcontext' , 'context');
        	
        	$this->contextCode = $this->_objContext->getContextCode();
        	
        }	
        catch (customException $e)
        {
        	echo customException::cleanUp($e);
        	die();
        }
	
	}
   
    
    /**
     * The standard dispatch function
     */
    public function dispatch()
    {
        try{
            if($this->_objContext->isInContext())
            {
                
            
                $this->setLayoutTemplate('layout_tpl.php');
                
                $action = $this->getParam('action');
        		
                switch ($action){
                           	
                	case null:
                	case 'main':
                	   return 'default_tpl.php';
                	case 'showsection':
	            	  $this->setVar('content', $this->_objCMSUtils->showSection());
	            	  return 'cms_section_tpl.php';
                }
            } else {
                return "error_tpl.php";
            }
        }
        catch (customException $e)
        {
        	echo customException::cleanUp($e);
        	die();
        }
    }
    
    	
	/**
	 * Method to get the menu for thecms admin
	 * 
	 * @access public
	 * @return string
	 */
	public function  getCMSMenu()
	{

		return $this->_objUtils->getNav();
	}
	
	
		
	/**
	 * Method to get the Sections on the left side of the menu
	 * 
	 * @access public
	 * @return string
	 */
	public function getSectionMenu()
	{
	   $calArr =  array('text' => 'Calendar', 'uri' => $this->uri(array('action' => 'ical')));
	   
		//return $this->_objCMSUtils->getSectionMenu('contextcmscontent');
		return $this->_objUtils->getNavigation($this->contextCode);
	}

	
	/**
	 * Method to get the Bread Crumbs
	 * 
	 * @access public
	 * @return string
	 */
	public function getBreadCrumbs()
	{
		return $this->_objCMSUtils->getBreadCrumbs();
	}

}