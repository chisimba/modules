<?php
/* -------------------- dbTable class ----------------*/
// security check - must be included in all scripts

if (!$GLOBALS['kewl_entry_point_run'])
{
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
        * The blocks object
        *
        * @var object
        */
        var $_objBlocks;

        /**
         * Method to initialise the cmsadmin object
         * 
         * @access public 
         */
        public function init()
        {
            // instantiate object
                $this->_objSections = & $this->newObject('dbsections', 'cmsadmin');
                $this->_objContent = & $this->newObject('dbcontent', 'cmsadmin');
                $this->_objBlocks = & $this->newObject('dbblocks', 'cmsadmin');
                $this->_objUtils = & $this->newObject('cmsutils', 'cmsadmin');
                $this->_objLayouts = & $this->newObject('dblayouts', 'cmsadmin');
                $this->_objUser = & $this->newObject('user', 'security');
                $this->objLanguage = & $this->newObject('language', 'language');
                $this->_objFrontPage = & $this->newObject('dbcontentfrontpage', 'cmsadmin');
                $this->_objConfig = & $this->newObject('altconfig', 'config');
                $this->_objContext = & $this->newObject('dbcontext', 'context');

                $objModule = & $this->newObject('modules', 'modulecatalogue');

                $this->loadClass('xajax', 'ajaxwrapper');
                $this->loadClass('xajaxresponse', 'ajaxwrapper');

                if ($objModule->checkIfRegistered('context')) {
                    $this->inContextMode = $this->_objContext->getContextCode();
                    $this->contextCode = $this->_objContext->getContextCode();
                } else {
                    $this->inContextMode = FALSE;
                }
        }

        /**
         * Method to handle actions from templates
         * 
         * @access public
         * @param string $action Action to be performed
         * @return mixed Name of template to be viewed or function to call
         */
        public function dispatch()
        {
                $action = $this->getParam('action');
                $this->setLayoutTemplate('cms_layout_tpl.php');
                //$this->setVar('bodyParams', ' id="type-b" ');

                switch ($action) {

                case null:
                        $myid = $this->_objUser->userId();

                    if ($this->_objUser->inAdminGroup($myid) != TRUE) {
                        die('<div id=featurebox>'.$this->objLanguage->languageText('mod_cmsadmin_nopermissionmsg', 'cmsadmin').'</div>');
                    }

                    //----------------------- section section

                case 'sections':
                    $viewType = $this->getParam('viewType', 'root');
                    $this->setVarByRef('viewType', $viewType);
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
                        return $this->nextAction('viewsection', array('id' => $sectionId), 'cmsadmin');
                    } else {
                        return $this->nextAction('sections', array(NULL), 'cmsadmin');
                    }

                case 'addsection':
                    // Generation of Ajax Scripts
                    $ajax = new xajax($this->uri(array('action'=>'addsection'), 'cmsadmin'));
                    $ajax->registerFunction(array($this, 'processSection')); // Register another function in this controller
                    $ajax->processRequests(); // XAJAX method to be called
                    $this->appendArrayVar('headerParams', $ajax->getJavascript()); // Send JS to header
                    //Get form
                    $addEditForm = $this->_objUtils->getAddEditSectionForm($this->getParam('id'), $this->getParam('parentid'));
                    $this->setVarByRef('addEditForm', $addEditForm);
                    $parentid = $this->getParam('parentid');
                    $level = $this->_objSections->getLevel($parentid);
                    $this->setVarByRef('parentid', $parentid);
                    return 'cms_section_add_tpl.php';

                case 'createsection':
                    $this->_objSections->add
                    ();
                    $parent = $this->getParam('parentid');
                    if (!empty($parent)) {
                        return $this->nextAction('viewsection', array('id' => $parent), 'cmsadmin');
                    } else {
                        return $this->nextAction('sections');
                    }

                case 'editsection';
                    $this->_objSections->edit();
                    $id = $this->getParam('id');
                    return $this->nextAction('viewsection', array('id' => $id), 'cmsadmin');

                case 'sectionpublish':
                    $this->_objSections->togglePublish($this->getParam('id'));
                    return $this->nextAction('sections');

                case 'deletesection':
                    $this->_objSections->deleteSection($this->getParam('id'));
                    return $this->nextAction('sections', array(NULL), 'cmsadmin');

                    //----------------------- front page section

                case 'frontpages':
                    $this->setVar('files', $this->_objFrontPage->getFrontPages());
                    return 'cms_frontpage_manager_tpl.php';

                case 'removefromfrontpage':
                    $id = $this->getParam('id');
                    $this->_objFrontPage->remove
                    ($id);
                    return $this->nextAction('frontpages', array(NULL), 'cmsadmin');

                case 'changefpstatus':
                    $pageId = $this->getParam('pageid');
                    $sectionId = $this->getParam('sectionid');
                    $this->_objFrontPage->changeStatus($pageId);
                    return $this->nextAction('viewsection', array('id' => $sectionId), 'cmsadmin');

                case 'changefporder':
                    $id = $this->getParam('id');
                    $ordering = $this->getParam('ordering');
                    $this->_objFrontPage->changeOrder($id, $ordering);
                    $this->setVar('files', $this->_objFrontPage->getFrontPages());
                    return $this->nextAction('frontpages', array(NULL), 'cmsadmin');

                    //----------------------- content section

                case 'addcontent':
                    $parentid = $this->getParam('parent', NULL);
                    $addEditForm = $this->_objUtils->getAddEditContentForm($this->getParam('id'), $parentid);
                    $this->setVarByRef('addEditForm', $addEditForm);
                    $this->setVarByRef('section', $parentid);
                    return 'cms_content_add_tpl.php';

                case 'createcontent':
                    $this->_objContent->add
                    ();
                    $sectionId = $this->getParam('parent', NULL);
                    if(!empty($sectionId)) {
                        return $this->nextAction('viewsection', array('id' => $sectionId), 'cmsadmin');
                    } else {
                        return $this->nextAction('frontpages', array('filter' => 'trash'));
                    }

                case 'editcontent':
                    $this->_objContent->edit();
                    $sectionId = $this->getParam('parent', NULL);

                    if (!empty($sectionId)) {
                        return $this->nextAction('viewsection', array('id' => $sectionId), 'cmsadmin');
                    } else {
                        return $this->nextAction('frontpages', array('action' => 'frontpages'), 'cmsadmin');
                    }

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
                        return $this->nextAction('viewsection', array('id' => $sectionId), 'cmsadmin');
                    } else {
                        return $this->nextAction('frontpages', array('filter' => 'trash'));
                    }

                case 'changecontentorder':
                    $id = $this->getParam('id');
                    $ordering = $this->getParam('ordering');
                    $sectionId = $this->getParam('sectionid');
                    $this->_objContent->changeOrder($sectionId, $id, $ordering);
                    return $this->nextAction('viewsection', array('id' => $sectionId), 'cmsadmin');

                case 'addblock':
                    $closePage = $this->getParam('closePage', FALSE);
                    $this->setVarByRef('closePage', $closePage);
                    $pageId = $this->getParam('pageid', NULL);
                    $blockForm  = $this->_objBlocks->getAddRemoveBlockForm($pageId);
                    $this->setVarByRef('blockForm', $blockForm);
                    return 'cms_blocks_tpl.php';

                case 'saveblock':
                    $pageId = $this->getParam('pageid', NULL);
                    $blocks = $this->_objBlocks->getBlockEntries();
                    $currentBlocks = $this->_objBlocks->getBlocksForPage($pageId);
                    foreach($blocks as $block) {
                        $exists = FALSE;
                        $blockName = $block['blockname'];
                        $blockId = $block['id'];
                        if(!empty($currentBlocks)) {
                            foreach($currentBlocks as $cb) {
                                if($cb['blockid'] == $blockId) {
                                    $exists = TRUE;
                                }
                            }
                        }
                        if($this->getParam($blockId)) {
                            if(!$exists) {
                                $this->_objBlocks->add
                                ($pageId, $blockId);
                            }
                        } else {
                            if($exists) {
                                $this->_objBlocks->deleteBlock($pageId, $blockId);
                            }
                        }
                    }
                    return $this->nextAction('addblock', array('pageid' => $pageId), 'cmsadmin');

                case 'changeblocksorder':
                    $id = $this->getParam('id');
                    $ordering = $this->getParam('ordering');
                    $pageId = $this->getParam('pageid');
                    $this->_objBlocks->changeOrder($id, $ordering, $pageId);
                    return $this->nextAction('addblock', array('pageid' => $pageId), 'cmsadmin');

                }
        }


        /**
        * Method to get the menu for the cms admin
        *
        * @access public
        * @return string 
        */
        public function getCMSMenu()
        {

            return $this->_objUtils->getNav();
        }
        /**
        * Ajax Function to display/hide applicable options based on display type
        *
        * @access public
        * @param string $sectionType Type of Section to base options on
        * @return string The ajax response
        */
        public function processSection($sectionType)
        {
            $objResponse = new xajaxResponse();

            if ($sectionType == 'page') {
                $objResponse->addAssign('pagenumlabel','style.display', 'none');
                $objResponse->addAssign('pagenumcol','style.display', 'none');
                $objResponse->addAssign('dateshowlabel','style.display', 'none');
                $objResponse->addAssign('dateshowcol','style.display', 'none');

            } else {
                $objResponse->addAssign('pagenumlabel','style.display', 'block');
                $objResponse->addAssign('pagenumcol','style.display', 'block');
                $objResponse->addAssign('dateshowlabel','style.display', 'block');
                $objResponse->addAssign('dateshowcol','style.display', 'block');

            }

            if ($sectionType == 'summaries' || $sectionType == 'list') {
                $objResponse->addAssign('showintrolabel','style.display', 'block');
                $objResponse->addAssign('showintrocol','style.display', 'block');
            } else {
                $objResponse->addAssign('showintrolabel','style.display', 'none');
                $objResponse->addAssign('showintrocol','style.display', 'none');
            }

            return $objResponse->getXML();
        }

}

?>
