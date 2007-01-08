<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check
/**
 * The utilities class holds a set of method that the context designer or any other 
 * method can use.
 * 
 * @author Wesley Nitsckie
 * @copyright 2004, University of the Western Cape & AVOIR Project
 * @license GNU GPL
 * @package contextdesigner
 **/

class utilities extends object
{

    /**
     * The Standard Constructor
     * 
     */
    public function init()
    {
//        $this->_objLanguage=& $this->newObject('language', 'language');
        $this->_objDBContext=& $this->newObject('dbcontext','context');
//        $this->_objUser= & $this->newObject('user','security');
//        $this->_objDBContextModules=&$this->newObject('dbcontextmodules','context');
//        $this->_objUtils =  & $this->newObject('utilities', 'context');
//        $this->_objModule = & $this->newObject('modules', 'modulecatalogue');
    }
    
    
    /**
     * Method to go through all the modules 
     * registered to the current context and get the
     * list of links that the module has made available
     * 
     * @return 
     * @access public
     * @package null
     * @author Wesley Nitsckie
     */
    public function getModuleLinks($moduleId)
    {
        $objModuleLink = $this->newObject('modulelinks_'.$moduleId, $moduleId);
        return $objModuleLink->getContextLinks( $this->_objDBContext->getContextCode());
       
        
    }
}
?>