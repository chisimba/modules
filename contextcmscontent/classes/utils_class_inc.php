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
 * @package utils   
 * @category utils
 * @copyright 2004, University of the Western Cape & AVOIR Project
 * @license GNU GPL
 * @version
 * @author Wesley Nitsckie 
 */

class utils extends object 
{
    
     /**
     * The dbcontextcmscontent  object 
     *
     * @access private
     * @var object
    */
    protected $_objDBContextCMSContent;
    
    
    /**
     * Constructor
     * 
     */
    public function init()
    {
        $this->_objDBContextCMSContent = $this->newObject('dbcontextcmscontent', 'contextcmscontent' );
        $this->_objSections = & $this->newObject('dbsections', 'cmsadmin');
    }
    
    
    /**
     * Method to the the navigation
     * 
     * @access public
     * @param string $contextCode the ContextCode
     * @return string
     */
    public function getNavigation($contextCode)
    {
        try
        {
            //initiate the objects
			$objSideBar = $this->newObject('sidebar', 'navigation');
			
            //first get all the sections for this context
            $arrSections = $this->_objDBContextCMSContent->getSections($contextCode);
             
            $modulename = "contextcmscontent";
            
            //setup the nodes
            $nodes = array();
           
            foreach($arrSections as $sections)
            {
                $section = $this->_objSections->getRow('id',$sections['sectionid']);
              //  print_r($sections);
                $nodes[] = array('text' =>$section['menutext'], 'uri' => $this->uri(array('action' => 'showsection', 'id' => $section['id']), $modulename), 'sectionid' => $section['id']);
            }
           
            return $objSideBar->show($nodes, $this->getParam('id'));
        }
        catch (customException $e)
        {
        	echo customException::cleanUp($e);
        	die();
        }
        
    }
    
}
?>