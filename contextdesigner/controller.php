<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check
/**
 * The context designer biulds the context by means of getting a setting of links from each of the modules that the current context has registered. It
 * does this by going around to each module's utilities class and calling the getContentLinks($contextCode) method. This will return an array of links
 * that the module provides to biuld the content for a context.
 * 
 * @author Wesley Nitsckie
 * @copyright 2004, University of the Western Cape & AVOIR Project
 * @license GNU GPL
 * @package contextdesigner
 **/

class contextdesigner extends controller
{
	
    /**
     * The standard constructor
     */
    public function init()
    {
       // $this->_objContextUtils =  $this->newObject('utilities','context');
        $this->_objLanguage= & $this->getObject('language', 'language');
        $this->_objDBContext= & $this->getObject('dbcontext','context');
        $this->_objContextAdminUtils = & $this->getObject('utils','contextadmin');
        $this->_objDBContextDesigner = & $this->getObject('dbcontextdesigner','contextdesigner');
//        $this->_objUser= & $this->newObject('user','security');
        $this->_objDBContextModules = &$this->getObject('dbcontextmodules','context');
        $this->_objUtils = &  $this->newObject('utilities', 'contextdesigner');
        $this->_objModule = & $this->getObject('modules', 'modulecatalogue');
        
    }
    
    /**
     * The Standard dispatch method
     */
    public function dispatch()
    {
       
        $action = $this->getParam('action');
        $this->setLayoutTemplate('main_layout_tpl.php');
        switch ($action)
        {
            default:
            case 'main':
                $this->setVar('linkList', $this->_objDBContextDesigner->getContextLinks());
                return 'main_tpl.php';
                
            //admin section
            case 'list':
                return 'list_tpl.php';
            case 'add':
                $this->setVar('modules', $this->_objDBContextModules->getContextModules($this->_objDBContext->getContextCode()));
                return 'add_tpl.php';
            case 'addstep2':
          
                $this->setVar('links',$this->_objUtils->getModuleLinks($this->getParam('moduleid')));
                return 'addstep2_tpl.php';
                
            case 'saveadd':
                $this->_objDBContextDesigner->addLinks($this->_objDBContext->getContextCode());
                return $this->nextAction(null);
                
            case 'changeaccess':
                $this->_objDBContextDesigner->updateAccess($this->getParam('id'), ucwords(($this->getParam('value'))));
                return $this->nextAction('main');
            case 'deletelink':
                $this->_objDBContextDesigner->deleteLink($this->getParam('id'));
                return $this->nextAction('main');
            case 'moveup':
                $this->_objDBContextDesigner->moveUp($this->getParam('id'));
                 return $this->nextAction('main');
            case 'movedown':
                $this->_objDBContextDesigner->moveDown($this->getParam('id'));
                return $this->nextAction('main');
            case 'reorder':
                $this->_objDBContextDesigner->reOrder();
                return $this->nextAction('main');
        }
    }
}

