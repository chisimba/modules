<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
// end security check

/**
 * The controller for the cmsadmin module that extends the base controller
 *
 * @package cmsadmin
 * @category chisimba
 * @copyright AVOIR
 * @license GNU GPL
 * @author Wesley  Nitsckie
 * @author Warren Windvogel
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
        * @access private
        * @var object
        */
        public $objLanguage;

        /**
        * The blocks object
        *
        * @access private
        * @var object
        */
        public $_objBlocks;
        
       /** the id of the current page
       *
       * @access public
       * @var object
       */
       public $currentPageId;
        
       public $objTreeMenu;
       
       public $objTreeNodes;

	   /**
	    * Class Constructor
	    *
	    * @access public
	    * @return void
	    */
        public function init()
        {
        	try {       
                // instantiate object
                $this->_objSections =  $this->newObject('dbsections', 'cmsadmin');
                $this->_objDBContext = $this->getObject('dbcontext', 'context');
                $this->_objContent =  $this->newObject('dbcontent', 'cmsadmin');
                $this->_objBlocks =  $this->newObject('dbblocks', 'cmsadmin');
                $this->_objHtmlBlock =  $this->newObject('dbhtmlblock', 'cmsadmin');
                $this->_objUtils =  $this->newObject('cmsutils', 'cmsadmin');
                $this->_objLayouts =  $this->newObject('dblayouts', 'cmsadmin');
                $this->_objUser =  $this->newObject('user', 'security');
                $this->objLanguage =  $this->newObject('language', 'language');
                $this->_objFrontPage =  $this->newObject('dbcontentfrontpage', 'cmsadmin');
                $this->_objConfig =  $this->newObject('altconfig', 'config');
                $this->_objContext =  $this->newObject('dbcontext', 'context');
				$this->objProxy = $this->newObject('proxyparser', 'utilities');
                $this->objModule = $this->newObject('modules', 'modulecatalogue');
                $this->objTreeMenu = $this->newObject('buildtree', 'cmsadmin');
                $this->objTreeNodes =  $this->newObject('treenodes', 'cmsadmin');
                $this->dbMenuStyle =  $this->newObject('dbmenustyles', 'cmsadmin');
                $this->_objCMSLayouts =  $this->newObject('cmslayouts', 'cms');
                //feeds classes
            	$this->objFeed = $this->getObject('feeds', 'feed');
                
                //Load the ajax classes
                //$this->loadClass('xajax', 'ajaxwrapper');
                //$this->loadClass('xajaxresponse', 'ajaxwrapper');

                if ($this->objModule->checkIfRegistered('context')) {
                    $this->inContextMode = $this->_objContext->isInContext();
                    $this->contextCode = $this->_objContext->getContextCode();
                } else {
                    $this->inContextMode = FALSE;
                    $this->contextCode = NULL;
                }
                
                //Get the activity logger class and log this module call
                $objLog = $this->getObject('logactivity', 'logger');
                $objLog->log();
            
		   } catch (Exception $e){
       		    throw customException($e->getMessage());
        	    exit();
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

                switch ($action) {

                default:
                    if ($this->inContextMode){
                    	return 'cms_context_view_tpl.php';//continue;// 'cms_notincontext_view_tpl.php';
                    }
                    $myid = $this->_objUser->userId();

                    if ($this->_objUser->inAdminGroup($myid) != TRUE) {
                        die('<div id=featurebox>'.$this->objLanguage->languageText('mod_cmsadmin_nopermissionmsg', 'cmsadmin').'</div>');
                    }
                    $topNav = $this->_objUtils->topNav('home');
                    $cpanel =  $this->_objUtils->getControlPanel();
                    $this->setVarByRef('topNav',$topNav);
                    $this->setVarByRef('cpanel',$cpanel);
                    return 'cms_main_tpl.php';

                /* ** Trash manager section ** */
                case 'trashmanager':
                    $text = $this->getParam('txtfilter');
                    $data = $this->_objContent->getArchivePages($text); // Get trashed content data
                    $sectionData = $this->_objSections->getArchiveSections($text); // Get trashed section data
                    $topNav = $this->_objUtils->topNav('trash');
                    $this->setVarByRef('topNav',$topNav);
                    $this->setVarByRef('data', $data);
                    $this->setVarByRef('sectionData', $sectionData);
                    return 'cms_trash_list_tpl.php';
                    
                case 'restore':
                    $items = $this->getParam('arrayList');
                    $this->unarchiveContentPages($items);
                    return $this->nextAction('trashmanager');
                
                case 'restoresections':
                    $items = $this->getParam('arrayList');
                    $this->unarchiveSections($items);
                    return $this->nextAction('trashmanager');

                //----------------------- section section
                case 'sections':
                    $this->getsections();
                    return 'cms_section_list_tpl.php';

                case 'viewsection':
                
                    $this->viewsections();
                    return 'cms_section_view_tpl.php';

                case 'changesectionorder':
                    //Get the sections details
                    $id = $this->getParam('id');
                    $ordering = $this->getParam('ordering');
                    $sectionId = $this->getParam('parent');
                    //Change the ordering
                    $this->_objSections->changeOrder($id, $ordering, $sectionId);

                    if (!empty($sectionId)) {
                        return $this->nextAction('viewsection', array('id' => $sectionId), 'cmsadmin');
                    } else {
                        return $this->nextAction('sections', array(NULL), 'cmsadmin');
                    }

                case 'addsection':
                    $this->addEditsection();
                    return 'cms_section_add_tpl.php';

                case 'createsection':
                    //Save the section
                    $this->_objSections->add($this->contextCode);
                    $parent = $this->getParam('parentid');
                    if (!empty($parent)) {
                        return $this->nextAction('viewsection', array('id' => $parent), 'cmsadmin');
                    } else {
                        return $this->nextAction('sections');
                    }

                case 'editsection':
                    $this->_objSections->edit();
                    $id = $this->getParam('id');
                    return $this->nextAction('viewsection', array('id' => $id), 'cmsadmin');
                    
                case 'select':
                	$item = $this->getParam('arrayList');
					$task = $this->getParam('task');
					$this->processSelection($item, $task);
					return $this->nextAction('sections');
					
				case 'filter':
				       	$text = $this->getParam('txtfilter',null);
				     	$drop_filter = $this->getParam('drp_filter',null);
				     	
				     	//echo '<pre>'; print_r($_POST); echo '</pre>';
				     	$publish = FALSE;
				     	if($drop_filter == 'published'){
				     	    $publish = '1';
				     	}else if($drop_filter == 'unpublished'){
				     	    $publish = '0';
				     	}
				     	
				     	$arrSections = $this->_objSections->getFilteredSections($text, $publish);
				     	
				     	/*get filtered sections
				     	if ($txt_filter!=null) {
				     		$arrSections = $this->_objSections->getFilteredSections(FALSE,$txt_filter);
				     	}elseif ($drop_filter!='select'){
				     		    $arrSections = $this->_objSections->getFilteredSections(TRUE,$txt_filter);
				     	}
				     	*/
				     	
                		 $topNav = $this->_objUtils->topNav('sections');
                    	 $this->setVarByRef('topNav',$topNav);
                		 $this->setVarByRef('arrSections', $arrSections);
                    	 $this->setVar('viewType', 'root');
                    	 return 'cms_section_list_tpl.php';            			
				     	
                case 'sectionpublish':
                    $this->_objSections->togglePublish($this->getParam('id'));
                    return $this->nextAction('sections');

                case 'deletesection':
                    $this->_objSections->deleteSection($this->getParam('id'));
                    return $this->nextAction('sections', array(NULL), 'cmsadmin');

                //----------------------- front page section
                case 'frontpages':
                    $topNav = $this->_objUtils->topNav('frontpage');
        	        $this->setVarByRef('topNav',$topNav);
                    $this->setVar('files', $this->_objFrontPage->getFrontPages());
                    return 'cms_frontpage_manager_tpl.php';
                    
                case 'publishfrontpage':
                    $list = $this->getParam('arrayList');
                    $task = $this->getParam('task');
                    $this->publishFrontContent($list, $task);
                    return $this->nextAction('frontpages');

                case 'removefromfrontpage':
                    $id = $this->getParam('id');
                    $this->_objFrontPage->remove($id);
                    return $this->nextAction('frontpages', array(NULL), 'cmsadmin');

                case 'changefpstatus':
                    $pageId = $this->getParam('pageid');
                    $sectionId = $this->getParam('sectionid');
                    $this->_objFrontPage->changeStatus($pageId);
                    return $this->nextAction('viewsection', array('id' => $sectionId), 'cmsadmin');

                case 'changefporder':
                    //Get front page details
                    $id = $this->getParam('id');
                    $ordering = $this->getParam('ordering');
                    //Change the ordering on the front page
                    $this->_objFrontPage->changeOrder($id, $ordering);
                    $this->setVar('files', $this->_objFrontPage->getFrontPages());
                    return $this->nextAction('frontpages', array(NULL), 'cmsadmin');

                    //----------------------- content section

                case 'addcontent':
                	
                    $parentid = $this->getParam('parent', NULL);
                    //Get top navigation
                    $topNav = $this->_objUtils->topNav('createcontent');
                    $this->setVarByRef('topNav',$topNav);
                    $addEditForm = $this->_objUtils->getAddEditContentForm($this->getParam('id'), $parentid);
                    $this->setVarByRef('addEditForm', $addEditForm);
                    $this->setVarByRef('section', $parentid);
                    $this->setVarByRef('id', $this->getParam('id'));
                    return 'cms_content_add_tpl.php';

                case 'createcontent':
                    //Save the content page
                    $this->_objContent->add();
                    $sectionId = $this->getParam('parent', NULL);
                    if(!empty($sectionId)) {
                        return $this->nextAction('viewsection', array('id' => $sectionId), 'cmsadmin');
                    } else {
                        return $this->nextAction('frontpages', array('filter' => 'trash'));
                    }
				                    
                case 'editcontent':
                    $this->_objContent->edit();
                    $sectionId = $this->getParam('parent', NULL);
                    $is_front = $this->getParam('frontpage', FALSE);

                    if (!empty($sectionId) && !$is_front) {
                        return $this->nextAction('viewsection', array('id' => $sectionId), 'cmsadmin');
                    } else {
                        return $this->nextAction('frontpages', array('action' => 'frontpages'), 'cmsadmin');
                    }
                    break;
                    
                case 'viewcontent':
//                    $this->_objContent->edit();
                    $sectionId = $this->getParam('sectionid', NULL);

                    if (!empty($sectionId)) {
                        return $this->nextAction('viewsection', array('id' => $sectionId), 'cmsadmin');
                    } else {
                        return $this->nextAction('frontpages', array('action' => 'frontpages'), 'cmsadmin');
                    }

                case 'contentpublish':
                    //Change state between published and not published
                    $this->_objContent->togglePublish($this->getParam('id'));
                    return $this->nextAction('frontpages');

                case 'trashcontent':
                    $sectionId = $this->getParam('sectionid', NULL);
                    $return = $this->_objContent->trashContent($this->getParam('id'));
                    
                    if (!empty($sectionId)) {
                        return $this->nextAction('viewsection', array('id' => $sectionId), 'cmsadmin');
                    }
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
                    //Get content details
                    $id = $this->getParam('id');
                    $ordering = $this->getParam('ordering');
                    $sectionId = $this->getParam('sectionid');
                    //Change content order
                    $this->_objContent->changeOrder($sectionId, $id, $ordering);
                    return $this->nextAction('viewsection', array('id' => $sectionId), 'cmsadmin');

                case 'addblock':
                    $blockCat = $this->getParam('blockcat', FALSE);
                    $sectionId = $this->getParam('sectionid', NULL);
                    $pageId = $this->getParam('pageid', NULL);
                    $closePage = $this->getParam('closePage', FALSE);
                    switch ($blockCat) {
                    
                    case 'frontpage':
                        $blockForm  = $this->_objBlocks->getAddRemoveBlockForm(NULL, NULL, 'frontpage');
                        break;
                    
                    case 'section':
                        $blockForm  = $this->_objBlocks->getAddRemoveBlockForm(NULL, $sectionId, 'section');
                        break;    

                    case 'content':
                        $blockForm  = $this->_objBlocks->getAddRemoveBlockForm($pageId, NULL, 'content');
                        break;
                            
                    }
                    $this->setVarByRef('closePage', $closePage);
                    $this->setVarByRef('blockForm', $blockForm);
                    return 'cms_blocks_tpl.php';

                case 'saveblock':
                    $blockCat = $this->getParam('blockcat', NULL);
                    //Get the page id of the page to add the block to
                    $pageId = $this->getParam('pageid', NULL);
                    //Get the section id of the page to add the block to
                    $sectionId = $this->getParam('sectionid', NULL);
                    
                    if ($blockCat == 'frontpage') {
                        //Get blocks on the frontpage
                        $currentBlocks = $this->_objBlocks->getBlocksForFrontPage();
                    } else if ($blockCat == 'content') {
                        //Get all blocks already on the page
                        $currentBlocks = $this->_objBlocks->getBlocksForPage($pageId);
                    } else {
                        //Get all blocks already on the section
                        $currentBlocks = $this->_objBlocks->getBlocksForSection($sectionId);
                    }
                    
                    //Get all available blocks
                    $blocks = $this->_objBlocks->getBlockEntries();
                    
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
                        //Get all blocks to be added
                        if($this->getParam($blockId)) {
                            //Check if it already exists before adding it
                            if(!$exists) {
                                $this->_objBlocks->add($pageId, $sectionId, $blockId, $blockCat);
                            }
                        //If block isn't in list to be added check if it exists and delete it    
                        } else {
                            if($exists) {
                                $this->_objBlocks->deleteBlock($pageId, $sectionId, $blockId, $blockCat);
                            }
                        }
                    }
                    if ($blockCat == 'frontpage') {
                        return $this->nextAction('addblock', array('blockcat' => 'frontpage'), 'cmsadmin');
                    } else if ($blockCat == 'content') {
                        //Get all blocks already on the page
                        return $this->nextAction('addblock', array('blockcat' => 'content', 'pageid' => $pageId), 'cmsadmin');
                    } else {
                        //Get all blocks already on the section
                        return $this->nextAction('addblock', array('blockcat' => 'section', 'sectionid' => $sectionId), 'cmsadmin');
                    }

							
                case 'changeblocksorder':
                    //Get block entry details
                    $id = $this->getParam('id');
                    $ordering = $this->getParam('ordering');
                    $pageId = $this->getParam('pageid', NULL);
                    $sectionId = $this->getParam('sectionid', NULL);
                    //Change order of blocks on page
                    $this->_objBlocks->changeOrder($id, $ordering, $pageId, $sectionId);
                    if ($blockCat == 'frontpage') {
                        return $this->nextAction('addblock', array('blockcat' => 'frontpage'), 'cmsadmin');
                    } else if ($blockCat == 'content') {
                        //Get all blocks already on the page
                        return $this->nextAction('addblock', array('blockcat' => 'content', 'pageid' => $pageId), 'cmsadmin');
                    } else {
                        //Get all blocks already on the section
                        return $this->nextAction('addblock', array('blockcat' => 'section', 'sectionid' => $sectionId), 'cmsadmin');
                    }
                case 'adddynamicpageblock':
                    $pageId = $this->getParam('pageid');
                    $blockId = $this->getParam('blockid');
                    $this->_objBlocks->add($pageId, NULL, $blockId, 'content');
                    
                    echo $this->createReturnBlock($blockId, 'usedblock');

                    break;
                case 'removedynamicpageblock':
                    $pageId = $this->getParam('pageid');
                    $blockId = $this->getParam('blockid');
                    $this->_objBlocks->deleteBlock($pageId, NULL, $blockId, 'content');
                    
                    echo $this->createReturnBlock($blockId, 'addblocks');
                    break;
                case 'adddynamicfrontpageblock':
                    $blockId = $this->getParam('blockid');
                    $this->_objBlocks->add(NULL, NULL, $blockId, 'frontpage');
                    
                    echo $this->createReturnBlock($blockId, 'usedblock');

                    break;
                case 'removedynamicfrontpageblock':
                    $blockId = $this->getParam('blockid');
                    $this->_objBlocks->deleteBlock(NULL, NULL, $blockId, 'frontpage');
                    
                    echo $this->createReturnBlock($blockId, 'addblocks');
                    break;
                 case 'uploadimage':
                 	  return 'cms_attachment_popup.php';
                 	  break;
                 case 'createfeed':
                 		             		
                 		return 'rssedit_tpl.php';
                 		break;
                 case 'addrss':
					$this->addRss();
        			$this->nextAction('createfeed');
        			break;

        		case 'rssedit':
	        		$mode = $this->getParam('mode');
	        		$rssname = $this->getParam('name');
	        		$rssurl = $this->getParam('rssurl');
	        		$rssdesc = $this->getParam('description');
	        		$userid = $this->objUser->userId();
	        		$id = $this->getParam('id');
	
	        		if($mode == 'edit')
	        		{
	        			$addarr = array('id' => $id, 'userid' => $userid, 'url' => $rssurl, 'name' => $rssname, 'description' => $rssdesc);
	        			$this->objDbBlog->addRss($addarr, 'edit');
	        		}
	        		$userid = $this->objUser->userid();
	        		$this->setVarByRef('userid', $userid);
		        	return 'rssedit_tpl.php';
		        	break;

        		case 'deleterss':
		        	$id = $this->getParam('id');
		
		        	$this->objDbBlog->delRSS($id);
		        	$this->nextAction('rssedit');
		        	break;
		        	
		        // Switch menu style - Megan Watson 26/03/2007
		        case 'menustyle':
		            $data = $this->dbMenuStyle->getStyles();
		            $topNav = $this->_objUtils->topNav('menu');
                    $this->setVarByRef('topNav', $topNav);
                    $this->setVarByRef('data', $data);
					return 'cms_menu_switch_tpl.php';
					break;
					
		        case 'updatemenustyle':
		            $styleId = $this->getParam('style');
		            $this->dbMenuStyle->updateActive($styleId);
		            $this->_objCMSLayouts->getMenuStyle(TRUE);
		            return $this->nextAction('menustyle');
		            break;
		            
		        case 'configleftblocks':
		            $block = $this->_objHtmlBlock->getBlock($this->contextCode);
		            $topNav = $this->_objUtils->topNav('menu');
                    $this->setVarByRef('topNav', $topNav);
                    $this->setVarByRef('block', $block);
		            return 'cms_config_leftblocks_tpl.php';
		            break;
		            
		        case 'createblock':
		            $id = $this->getParam('id');
		            $this->_objHtmlBlock->updateBlock($id);
		            return $this->nextAction('configleftblocks');
		            break;
		        
		        // End switch menu style
		        	
		        case 'managemenus':
					$pageId = $this->getParam('pageid',null);
					$content = $this->objTreeNodes->getRootNodes();
					$this->setVar('content',$content);
					$this->currentPageId = $pageId;
					$this->setVar('pageId', $pageId);
					$topNav = $this->_objUtils->topNav('menu');
                    $this->setVarByRef('topNav',$topNav);
					return 'cms_menu_list_tpl.php';
					break;
				case 'savemenu':
	                if (($this->getParam('published') == 'on') || ($this->getParam('published') == TRUE)){
	                    $published = 1;
	                } else {
	                    $published = 0;
	                }
	                $pageId = $this->getParam('id');
	                $this->objTreeNodes->edit($pageId, $this->getParam('title'), $this->getParam('nodetype'), $this->getParam('linkreference'), $this->getParam('banner'), $this->getParam('parentid'), $this->getParam('layout'), $this->getParam('css'), $published, $this->getParam('publisherid'), $this->getParam('ordering'));
	                return $this->nextAction('managemenus', array('pageid'=>$pageId), 'cmsadmin');
            	case 'moveup':
	                $pageId = $this->getParam('pageid');
	                $this->objTreeNodes->moveNodeUp($pageId);
	                return $this->nextAction('managemenus', array('pageid'=>$pageId), 'cmsadmin');
            	case 'movedown':
	                $pageId = $this->getParam('pageid');
	                $this->objTreeNodes->moveNodeDown($pageId);
	                return $this->nextAction('managemenus', array('pageid'=>$pageId), 'cmsadmin');
	            case 'addnewmenu':
	            	 $this->currentPageId = $this->getParam('pageid',NULL);
	            	 return $this->menuAdd();
            	case 'addmenu':
	                try {
	                    if (($this->getParam('published') == 'on') || ($this->getParam('published') == TRUE)){
	                        $published = 1;
	                    } else {
	                        $published = 0;
	                    }
	                    $orderNum = $this->objTreeNodes->getNewOrderNum($this->getParam('parentid'));
	                    $this->objTreeNodes->add($this->getParam('title'), $this->getParam('nodetype'), $this->getParam('linkreference'), $this->getParam('banner'), $this->getParam('parentid'), $this->getParam('layout'), $this->getParam('css'), $published, $this->getParam('publisherid'), $orderNum);
	
	                    $pageId = $this->objTreeNodes->getLastInsertId();
	                    return $this->nextAction('addnewmenu', array('pageid'=>$pageId), 'cmsadmin');
	                }catch (Exception $e){
	                    //if add fails do this
	                    return $this->nextAction('addnewmenu', array(), 'cmsadmin');
	                }

            	case 'save':
                	$this->objContent->edit();
                	return $this->nextAction('addnewmenu', array('pageid'=>$pageId), 'cmsadmin');
                	break;
                	
                 case 'deletemenu':
                    $heading = '<h1><center>'.$this->objLanguage->languageText('phrase_menuadmin').'</center></h1><br /><br />';
                    $this->setVarByRef('heading', $heading);

                    $pageId = $this->getParam('pageid');
                    if (!isset($pageId)) {
                    	$pageId = $this->getParam('id');
                    }
                    $node = $this->objTreeNodes->getNode($pageId);
                    //var_dump($node);
                    if(isset($node[0]['parent_id']))
                    {
                    	$parentId = $node[0]['parent_id'];
                    	$this->objTreeNodes->deleteWithChildren($pageId);
                    	return $this->nextAction('managemenus', array('pageid'=>$parentId), 'cmsadmin');
                    }
                    else {
                    	$parentId = array();
                    	return $this->nextAction('managemenus', array('pageid'=>$parentId), 'cmsadmin');
                    }
            	case 'showcmspages':
	                $contentId = $this->getParam('id');
	                $pageId = $this->getParam('pageid');
	                $page = $this->_objContent->getContentPage($contentId);
	                
	    		    $this->setVar('content', $this->_objUtils->showBody($contentId, $pageId, FALSE));
	
	                if (count($page) == 0) {
	                    $contentId = NULL;
	                    $sectionId = NULL;
	                } else {
	                    $sectionId = $page['sectionid'];
	                }
	        	    $this->setVar('contentId', $contentId);
	        	    $this->setVar('sectionId', $sectionId);
	        	    $this->setLayoutTemplate('menu_cms_layout_tpl.php');
	    			return  'cms_test_tpl.php';

            	case 'showgrouplist':
	                $groupId = $this->getParam('groupid');
	        	    $this->setVar('groupId', $groupId);
	        	    $this->setLayoutTemplate('');
	    		    $this->setVar('content', $this->_objUtils->showGroupContent($groupId));
	    			return  'cms_test_tpl.php';
	                break;		       
                }
                
                
        }
        /**
         * Method to query for sections
         * This method can be used for queries related to sections
         *
         * @param string $contextcode
         * @param string $rootType: this can be 'all' or 'root' depending what 
         * type of results you want
         * @return array of sections
         */
        
        public function getsections($contextcode=null,$rootType=null){
        	//Check whether the contextcode is set 
        	if (isset($contextcode)) {
        		$this->contextCode = $contextcode;
        	}
        	//Check whether to display all nodes or root nodes only
        	if (isset($rootType)) {
        		$viewType = $rootType;
        	}else{
        		$viewType = $this->getParam('viewType', 'all');
        	}
        	$topNav = $this->_objUtils->topNav('sections');
        	$this->setVarByRef('topNav',$topNav);
        	
        	if($viewType == 'root') {
        		$arrSections = $this->_objSections->getRootNodes(false,$this->contextCode);
        	} elseif($this->objModule->checkIfRegistered('context', 'context')) {        	
        		$arrSections = $this->_objUtils->getSectionLinks(TRUE,$this->contextCode);
        	}
        	$this->setVarByRef('arrSections', $arrSections);
        	$this->setVarByRef('viewType', $viewType);
        	
        	return $arrSections;
        }
        /**
         * This method accepts a section id and
         * returns an array of sections.For cms purposes
         * it sets by reference subsections as well for 
         * the cms display template
         *
         * @param int $sectionid
         * @param int $subsectionid same as the section id
         * @return array sections
         */
        public function viewsections($sectionid=null,$subsectionid=null){
        	
            
        	
            if (isset($sectionid) && ($this->inContextMode == FALSE)) {
        		$id = $sectionid;
        	}else{
        		$id = $this->getParam('id');
        	}
        	
        	if($id == NULL && $this->_objDBContext->isInContext())
            {
                $arrSection = $this->_objSections->getSectionByContextCode($this->contextCode);
                $id = $arrSection['id'];
            }
            
            
        	//Get section data
        	$section = $this->_objSections->getSection($id);
        	
        	$this->setVarByRef('section', $section);
        	//Get sub sections
        	$subSections = $this->_objSections->getSubSectionsInSection($id);
        	$this->setVarByRef('subSections', $subSections);
        	//Get content pages
        	$pages = $this->_objContent->getPagesInSection($id);
        	//Get top Nav
        	$topNav = $this->_objUtils->topNav('viewsection');
        	$this->setVarByRef('topNav',$topNav);
        	$this->setVarByRef('pages', $pages);
        	return $section;
        }
        /**
         * Method to return the sections form. The form comes with
         * ajax attached so no need to get that as well.
         * The method accepts two params that of the parent
         * and section ids. This is so you can have (n)levels
         *
         * @param int $sectionid
         * @param int $parentid
         * @return string form
         */
        public function addEditsection($sectionid=null,$parentid=null){
        	 // Generation of Ajax Scripts
             //$ajax = $this->getObject('xajax', 'ajaxwrapper');
             //$ajax->setRequestURI($this->uri(array('action'=>'addsection'), 'cmsadmin'));
        	 //$ajax->registerFunction(array($this, 'processSection')); // Register another function in this controller
        	 //$ajax->processRequests(); // XAJAX method to be called
        	 //$this->appendArrayVar('headerParams', $ajax->getJavascript()); // Send JS to header
        	 //Get form
        	 if (!isset($sectionid) ) {
        	 	$sectionid = $this->getParam('id');
        	 }
        	 if (!isset($parentid)) {
        	 	$parentid = $this->getParam('parentid');
        	 }
        	 $addEditForm = $this->_objUtils->getAddEditSectionForm($sectionid, $parentid);

        	 //Get Edit Form
        	 $this->setVarByRef('addEditForm', $addEditForm);
        	 $parentid = $this->getParam('parentid');
        	 $level = $this->_objSections->getLevel($parentid);
        	 $this->setVarByRef('parentid', $parentid);
             return $addEditForm;
        }
        
        /**
         * Method creates the return blcks layout
         * Facilitates the ajax return type for a block
         *
         * @param string $blockId: which bock is dragged
         * @param string $cssClass: css layout class
         * @return string block
         */
        
        private function createReturnBlock($blockId, $cssClass)
        {
            $objModuleBlocks =& $this->getObject('dbmoduleblocks', 'modulecatalogue');
            $objBlocks =& $this->getObject('blocks', 'blocks');
            
            $blockRow = $objModuleBlocks->getRow('id', $blockId);
            
            $str = trim($objBlocks->showBlock($blockRow['blockname'], $blockRow['moduleid']));
            $str = preg_replace('/type\\s??=\\s??"submit"/', 'type="button"', $str);
            $str = preg_replace('/href=".+?"/', 'href="javascript:alert(\''.$this->objLanguage->languageText('mod_cmsadmin_linkdisabled', 'cmsadmin', 'Link is Disabled.').'\');"', $str);
            
            return '<div class="'.$cssClass.'" id="'.$blockRow['id'].'" style="border: 1px solid lightgray; padding: 5px; width:150px; float: left; z-index:20;">'.$str.'</div>';
        }

        /**
        * Method to get the menu for the cms admin
        *
        * @access public
        * @return string The html to produce the navigation
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
        * @deprecated
        *
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
            
            $objResponse->addScript('adjustLayout();');

            return $objResponse->getXML();
        }
        
         /**
         * fuction to process publish or unpublish in sections
         *
         * @param array $itemsArray
         */
        private function processSelection($itemsArray, $publish)
        {
            if(!empty($itemsArray)){
            	 foreach ($itemsArray as $line){
               		 $this->_objSections->publish($line, $publish);
            	 }
            }
        }
        
        /**
        * Method to undelete a set of content pages
        *
        * @author Megan Watson
        * @access private
        * @param array $itemsArray The pages to undelete/unarchive
        * @return
        */
        private function publishFrontContent($itemsArray, $publish = 'publish')
        {
            if(!empty($itemsArray)){
            	 foreach ($itemsArray as $item){
               		 $this->_objContent->publish($item, $publish);
            	 }
            }
        }

        /**
        * Method to undelete a set of content pages
        *
        * @author Megan Watson
        * @access private
        * @param array $itemsArray The pages to undelete/unarchive
        * @return
        */
        private function unarchiveContentPages($itemsArray)
        {
            if(!empty($itemsArray)){
            	 foreach ($itemsArray as $item){
               		 $this->_objContent->undelete($item);
            	 }
            }
        }
        
        /**
        * Method to undelete a set of sections with their contents
        *
        * @author Megan Watson
        * @access private
        * @param array $itemsArray The sections to undelete/unarchive
        * @return
        */
        private function unarchiveSections($itemsArray)
        {
            if(!empty($itemsArray)){
               foreach ($itemsArray as $item){
                   $this->_objSections->unarchiveSection($item);
               }
            }
        }
        
        private function addRss()
        {
        	
        	$rssname = $this->getParam('name');
        	$rssurl = $this->getParam('rssurl');
        	$rssdesc = $this->getParam('description');
        	$userid = $this->_objUser->userId();
        	$mode = $this->getParam('mode');
        	if($mode == 'edit')
        	{
        		$id = $this->getParam('id');
        		$rdata = $this->_objLayouts->getRssById($id);
        		$this->setVarByRef('rdata', $rdata);
        		return 'rssedit_tpl.php';
        	}

        	//get the cache
        	//get the proxy info if set
			$proxyArr = $this->objProxy->getProxy();

			$ch = curl_init();
   			curl_setopt($ch, CURLOPT_URL, $rssurl);
   			//curl_setopt($ch, CURLOPT_HEADER, 1);
   			curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
   			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
   			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   			if(!empty($proxyArr) && $proxyArr['proxy_protocol'] != '')
            {
            	curl_setopt($ch, CURLOPT_PROXY, $proxyArr['proxy_host'].":".$proxyArr['proxy_port']);
            	curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyArr['proxy_user'].":".$proxyArr['proxy_pass']);
            }
            
   			$rsscache = curl_exec($ch);
   			curl_close($ch);
   			//put in a timestamp
        	$addtime = time();
        	$addarr = array('userid' => $userid, 'url' => $rssurl, 'name' => $rssname, 'description' => $rssdesc, 'rsscache' => htmlentities($rsscache), 'rsstime' => $addtime);

        	//write the file down for caching
        	$path = $this->_objConfig->getContentBasePath() . "/cms/rsscache/";
        	$path =  str_replace('\\', '/',$path);
        	$rsstime = time();
        	if(!file_exists($path))
        	{

        		mkdir($path);
        		chmod($path, 0777);
        		$filename = $path . $userid . "_" . $rsstime . ".xml";
        		if(!file_exists($filename))
        		{
        			touch($filename);

        		}
        		$handle = fopen($filename, 'wb');
        		fwrite($handle, $rsscache);
        	}
        	else {
        		$filename = $path . $userid . "_" . $rsstime . ".xml";
        		$handle = fopen($filename, 'wb');
        		fwrite($handle, $rsscache);
        	}
        	
        	//add into the db

        	$rssurl = htmlentities($rssurl, ENT_QUOTES);
        	$rssname = htmlentities($rssname, ENT_QUOTES);
        	$rssdesc = htmlentities($rssdesc, ENT_QUOTES);


        	$addarr = array('userid' => $userid, 'url' => $rssurl, 'name' => $rssname, 'description' => $rssdesc, 'rsscache' => $filename, 'rsstime' => $rsstime);
			$result = $this->_objLayouts->addRss($addarr);
			
			return ;
        }
        /**
         * The method is designed to handle the menu form
         * sets up the add/edit of menus
         * @access private
         * @return admin template
         */
        
        private function menuAdd(){
        	 $add = $this->getParam('add',FALSE);
        	 
			 $menuNode = $this->objTreeMenu->getNode($this->currentPageId, FALSE); 
			 
             if (!$add) {
                    if (count($menuNode) > 0) {
                    	
        					$this->setVar('editForm', $this->_objUtils->showEditNode($this->currentPageId));
                        }
             } else {
    					$this->setVar('editForm', $this->_objUtils->showAddNode($this->currentPageId));
             }
             if (count($menuNode) > 0) {
             	 $this->setVar('menuNodeParent', $menuNode[0]['parent_id']);
                    if ($menuNode[0]['node_type'] == 1)
                    {
                    	$this->setVar('content', $this->_objUtils->showBody($menuNode[0]['link_reference'], $this->currentPageId));
                    }
             }
             return  'cms_menuadmin_tpl.php';	
        }
        

}

?>