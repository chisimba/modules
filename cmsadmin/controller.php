<?php
/* -------------------- dbTable class ----------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check
/**
* The controller for the cmsadmin
* @package dbcategories
* @category dbcategories
* @copyright 2004, University of the Western Cape & AVOIR Project
* @license GNU GPL
* @version
* @author Wesley  Nitsckie
* @example :
*/

class cmsadmin extends controller
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
			$this->_objUser = & $this->newObject('user', 'security');
        	$this->_objFrontPage = & $this->newObject('dbcontentfrontpage', 'cmsadmin');
        	$this->_objConfig = & $this->newObject('altconfig', 'config');
        }	
        catch (customException $e)
        {
        	echo customException::cleanUp($e);
        	die();
        }
	
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
                	
                
                case 'content':
                	return 'cms_content_list_tpl.php';            	
                case 'addcontent':
    				return 'cms_content_add_tpl.php';			
    			case 'createcontent':
    				$this->_objContent->add();
    				return $this->nextAction('content');
    			case 'editcontent':
    				$this->_objContent->edit();
    				return $this->nextAction('content');
    			
    			
    			
    			case 'sections':
    				return 'cms_section_list_tpl.php';
    			case 'addsection':
    				return 'cms_section_add_tpl.php';
    			case 'createsection':			
    				$this->_objSections->add();
    				return $this->nextAction('sections');
    			case 'editsection';
    				$this->_objSections->edit();
    				return $this->nextAction('sections');
    			case 'sectionpublish':
    			    $this->_objSections->togglePublish($this->getParam('id'));
    			    return $this->nextAction('sections');
    				
    				
    			case 'categories':
    				return 'cms_category_list_tpl.php';
    			case 'addcategory':
    				return 'cms_category_add_tpl.php';
    			case 'createcategory';
    				$this->_objCategories->add();
    				return $this->nextAction('categories');
    			case 'editcategory':
    				$this->_objCategories->edit();
    				return $this->nextAction('categories');
    				
    		    case 'frontpages':
    		        $this->setVar('files', $this->_objFrontPage->getFrontPages());
    		        return 'cms_frontpage_manager_tpl.php';
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

}

?>