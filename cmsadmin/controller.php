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
* @author Warren Windvogel
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
* The layout object
*
* @access private
* @var object
*/
    protected $_objLayout;
    
    
/**
* The config object
*
* @access private
* @var object
*/
    protected $_objConfig;
    
/**
* The language object
*
* @var object
*/
    var $objLanguage;
    
/**
* Constructor
*/
    public function init()
    {
        // instantiate object
        try{
            
            $this->_objSections = & $this->newObject('dbsections', 'cmsadmin');
            $this->_objContent = & $this->newObject('dbcontent', 'cmsadmin');
            $this->_objUtils = & $this->newObject('cmsutils', 'cmsadmin');
            $this->_objLayouts = & $this->newObject('dblayouts', 'cmsadmin');
            $this->_objUser = & $this->newObject('user', 'security');
            $this->objLanguage =& $this->newObject('language', 'language');
            $this->_objFrontPage = & $this->newObject('dbcontentfrontpage', 'cmsadmin');
            $this->_objConfig = & $this->newObject('altconfig', 'config');
            $this->_objContext = & $this->newObject('dbcontext', 'context');
            
            $objModule = & $this->newObject('modules', 'modulecatalogue');
            
            if ($objModule->checkIfRegistered('context')) {
                $this->inContextMode = $this->_objContext->getContextCode();
                $this->contextCode = $this->_objContext->getContextCode();
            } else {
                $this->inContextMode = FALSE;
            }
        }
        catch(customException $e)
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
            //$this->setVar('bodyParams', ' id="type-b" ');
            switch ($action) {
                
            case null:
                $myid = $this->_objUser->userId();
                if ($this->_objUser->inAdminGroup($myid) != TRUE) {
                    die("<div id=featurebox>You do not have sufficient permissions to perform this task!</div>");
                }
                
                //----------------------- front page section
            case 'frontpages':
                $this->setVar('files', $this->_objFrontPage->getFrontPages());
                return 'cms_frontpage_manager_tpl.php';
                
            case 'removefromfrontpage':
                $id = $this->getParam('id');
                $this->_objFrontPage->remove($id);
                return $this->nextAction('frontpages', array(NULL), 'cmsadmin');
                
            case 'changefporder':
                $id = $this->getParam('id');
                $ordering = $this->getParam('ordering');
                $this->_objFrontPage->changeOrder($id, $ordering);
                $this->setVar('files', $this->_objFrontPage->getFrontPages());
                return $this->nextAction('frontpages', array(NULL), 'cmsadmin');
                
                //----------------------- content section
                
            case 'addcontent':
                $parentid = $this->getParam('parentid');
                return 'cms_content_add_tpl.php';
                
            case 'createcontent':
                $this->_objContent->add();
                $sectionId = $this->getParam('parent', NULL);
                if (!empty($sectionId)) {
                    return $this->nextAction('viewsection', array('id'=>$sectionId), 'cmsadmin');
                } else {
                    return $this->nextAction('frontpages',array('filter'=>'trash'));
                }
                
            case 'editcontent':
                $this->_objContent->edit();
                return $this->nextAction('frontpages', array('action' => 'frontpages'), 'cmsadmin');
                
            case 'contentpublish':
                $this->_objContent->togglePublish($this->getParam('id'));
                return $this->nextAction('frontpages');
                
            case 'trashcontent':
                $this->_objContent->trashContent($this->getParam('id'));
                return $this->nextAction('frontpages');
                
            case 'deletecontent':
                $this->_objContent->deleteContent($this->getParam('id'));
                $sectionId = $this->getParam('sectionid', NULL);
                if (!empty($sectionId)) {
                    return $this->nextAction('viewsection', array('id'=>$sectionId), 'cmsadmin');
                } else {
                    return $this->nextAction('frontpages',array('filter'=>'trash'));
                }
                
            case 'changecontentorder':
                $id = $this->getParam('id');
                $ordering = $this->getParam('ordering');
                $sectionId = $this->getParam('sectionid');
                $this->_objContent->changeOrder($sectionId, $id, $ordering);
                return $this->nextAction('viewsection', array('id'=>$sectionId), 'cmsadmin');
                
                //----------------------- section section
                
            case 'sections':
                return 'cms_section_list_tpl.php';
                
            case 'viewsection':
                $id = $this->getParam('id');
                //Get section data
                $section = $this->_objSections->getSection($id);
                $this->setVarByRef('section', $section);
                //Get sub sections
                $subSections = $this->_objSections->getSubSectionsInSection($id);
                $this->setVarByRef('subSections', $subSections);
                //Get content pages
                $pages = $this->_objContent->getPagesInSection($id);
                $this->setVarByRef('pages', $pages);
                return 'cms_section_view_tpl.php';
                
            case 'changesectionorder':
                $id = $this->getParam('id');
                $ordering = $this->getParam('ordering');
                $sectionId = $this->getParam('parent');
                $this->_objSections->changeOrder($id, $ordering, $sectionId);
                if (!empty($sectionId)) {
                    return $this->nextAction('viewsection', array('id'=>$sectionId), 'cmsadmin');
                } else {
                    return $this->nextAction('sections', array(NULL), 'cmsadmin');
                }
                
            case 'addsection':
                $parentid = $this->getParam('parentid');
                $level = $this->_objSections->getLevel($parentid);
                $this->setVarByRef('parentid', $parentid);
                return 'cms_section_add_tpl.php';
                
            case 'createsection':
                $this->_objSections->add();
                $parent = $this->getParam('parentid');
                if (!empty($parent)) {
                    return $this->nextAction('viewsection', array('id'=>$parent), 'cmsadmin');
                } else {
                    return $this->nextAction('sections');
                }
                
            case 'editsection';
                $this->_objSections->edit();
                $id = $this->getParam('id');
                return $this->nextAction('viewsection', array('id'=>$id), 'cmsadmin');
                
            case 'sectionpublish':
                $this->_objSections->togglePublish($this->getParam('id'));
                return $this->nextAction('sections');
                
            case 'sectiondelete':
                $this->_objSections->deleteSection($this->getParam('id'));
                return $this->nextAction('sections');
                
            }
        }
        catch(customException $e)
        {
            echo customException::cleanUp($e);
            die();
        }
    }
    
    
/**
* Method to get the menu for the cms admin
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
