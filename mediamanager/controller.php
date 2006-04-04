<?php
/* -------------------- dbTable class ----------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check
/**
* The controller for the media manager. The media manager is a document manager that organisors images, documents , flash and folders
* @package mediamanager
* @category mediamanager
* @copyright 2004, University of the Western Cape & AVOIR Project
* @license GNU GPL
* @version
* @author Wesley  Nitsckie
* @example :
*/

class mediamanager extends controller
{
	
	
    
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
	* Constructor
	*/
	public function init()
	{
		// instantiate object
        try{
        	
			$this->_objSections = & $this->newObject('dbsections', 'cmsadmin');
			$this->_objCategories = & $this->newObject('dbcategories', 'cmsadmin');
			$this->_objContent = & $this->newObject('dbcontent', 'cmsadmin');
			$this->_objUtils = & $this->newObject('cmsutils', 'cmsadmin');
			$this->_objUser = & $this->newObject('user', 'security');
        	$this->_objFrontPage = & $this->newObject('dbcontentfrontpage', 'cmsadmin');
        	$this->_objMedia = & $this->newObject('mmutils', 'mediamanager');
        }catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
	
	}
	
	
	/**
	* The Dispatch  methed that the framework needs to evoke the controller
	*/
	public function dispatch()
	{
		$action = $this->getParam('action');

        switch ($action){
                   	
        	case null:
        	case 'frontpage':
        		$this->setLayoutTemplate('mm_layout_tpl.php');
        		return 'mm_main_tpl.php';
        	case 'showmedia':
        		$this->setVar('pageSuppressContainer', TRUE);
	            $this->setVar('pageSuppressBanner', TRUE);
	            $this->setVar('pageSuppressToolbar', TRUE);
	            $this->setVar('suppressFooter', TRUE);
	            $this->setVar('pageSuppressIM', TRUE);
	            
	            $this->setVar('files', $this->_objMedia->getFiles($this->getParam('folder')));
        		return 'mm_listmedia_tpl.php';
        	case 'upload':        
        		$this->_objMedia->upload();
        		return $this->nextAction('frontpage', array('folder' => $this->getParam('folder')));
            case 'createfolder':
            	if($this->_objMedia->createFolder())
            	{
            		$newFolder = '/'.$this->getParam('newfolder');
            	} else {
            		$newFolder = '';
            	}
            	return $this->nextAction('frontpage', array('folder' => $this->getParam('folder').$newFolder));
           
        }
	}

	
	

}
?>