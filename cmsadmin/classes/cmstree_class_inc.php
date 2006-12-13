<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
* This object is a wrapper class for building a tree using the cms sections
*
* @package cmsadmin
* @category chisimba
* @copyright AVOIR 
* @license GNU GPL
* @author Serge Meunier
*/

class cmstree extends object
{

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
        * The User object
        *
        * @access private
        * @var object
        */
        protected $_objUser;

	   /**
	    * Class Constructor
	    *
	    * @access public
	    * @return void
	    */
        public function init()
        {
        	try {
                $this->_objSections = & $this->newObject('dbsections', 'cmsadmin');
                $this->_objContent = & $this->newObject('dbcontent', 'cmsadmin');
                $this->_objUser = & $this->newObject('user', 'security');
                $this->objLanguage =& $this->newObject('language', 'language');
           } catch (Exception $e){
       		    echo 'Caught exception: ',  $e->getMessage();
        	    exit();
     	   }
        }

        /**
        * Method to return back the tree code
        *
        * @param string $currentNode The currently selected node, which should remain open
        * @param bool $admin Select whether admin user or not
        * @return string
        * @access public
        */
        public function show($currentNode = null, $admin = FALSE)
        {
            $html = $this->buildTree($currentNode, $admin);
            return $html;
        }

        /**
         * Method to build the tree
         *
         * @param string $currentNode The currently selected node, which should remain open
         * @param bool $admin Select whether admin user or not
         * @return string
         * @access public
         */
        public function buildTree($currentNode = null, $admin = FALSE)
        {
            //check if there are any root nodes
            if ($this->getChildNodeCount('0') > 0) {
                $html = '<ul class="treefolder">';
                //start the tree building
                $html .= $this->buildLevel('0', $currentNode, $admin);
                $html .= '</ul>';
            } else {
                $html = '';
            }
            return $html;
        }

        /**
         * Method to build the next level in tree
         *
         * @param string $parentid The node id whose child nodes need to be built
         * @param string $currentNode The currently selected node, which should remain open
         * @param bool $admin Select whether admin user or not
         * @return string
         * @access public
         */
        public function buildLevel($parentId, $currentNode, $admin)
        {
            //gets all the child nodes of id
            $nodes = $this->getChildNodes($parentId);

            if (count($nodes)) {

                $htmlLevel = '';
                foreach($nodes as $node) {
                    if ($this->getChildNodeCount($node['id']) > 0) {
                        //if node has further child nodes, recursively call buildLevel
                        $htmlChildren = $this->buildLevel($node['id'], $currentNode, $admin);

                        //get any content for a section
                        if(($this->getNodeContentCount($node['id']) == 0) || ($node['published'] == 0)) {
                            $htmlLevel .= '';
                        } else {

                            $nodeUri = $this->uri(array('action'=>'showsection', 'id'=>$node['id'], 'sectionid'=>$node['id']), 'cms');
                            $htmlLevel .= '<li class="sectionfolder"><a href="'.$nodeUri.'">'.$node['menutext'].'</a><ul>';
                            $htmlLevel .= $htmlChildren;
                            $htmlLevel .= '</ul></li>';
                        }
                    } else {
                        if(($this->getNodeContentCount($node['id']) == 0) || ($node['published'] == 0)) {
                            $htmlLevel .= '';
                        } else {
                            $nodeUri = $this->uri(array('action'=>'showsection', 'id'=>$node['id'], 'sectionid'=>$node['id']), 'cms');
                            $htmlLevel .= '<li class="sectionfolder"><a href="'.$nodeUri.'">'.$node['menutext'].'</a></li>';
                        }
                    }
                }
                return $htmlLevel;
            } else {
                //if no nodes return empty string
                return '';
            }
        }

        /**
         * Method to get add all content for a particular section node
         *
         * @param string $id The id of the section node
         * @return string
         * @access public
         */
        public function addContent($id)
        {
            $contentNodes = $this->getContent($id);

            if (count($contentNodes)) {
                $htmlContent = '';
                foreach($contentNodes as $contentNode) {
                    $contentUri = $this->uri(array('action'=>'showcontent', 'id'=>$contentNode['id'], 'sectionid'=>$contentNode['sectionid']), 'cms');
                    $htmlContent .= '<li><a href="'.$contentUri.'">'.$contentNode['title'].'</a></li>';
                }
                return $htmlContent;
            } else {
                return '';
            }
        }

        /**
         * Method to get all child nodes for a particular node
         *
         * @param string $parentId The parent node id
         * @return array
         * @access public
         */
        public function getChildNodes($parentId)
        {
            return $this->_objSections->getSubSectionsInSection($parentId);
        }

        /**
         * Method to get node for a particular id
         *
         * @param string $id The node id
         * @return array
         * @access public
         */
        public function getNode($id)
        {
            return $this->_objSections->getSection($id);
        }

        /**
         * Method to get all content nodes for a particular section node
         *
         * @param string $sectionId The section id
         * @return array
         * @access public
         */
        public function getContent($sectionId)
        {
            return $this->_objContent->getPagesInSection($sectionId);
        }

        /**
         * Method to get number of child nodes for a particular node
         *
         * @param string $parentId The parent node id
         * @return int
         * @access public
         */
        public function getChildNodeCount($parentId)
        {
            return $this->_objSections->getNumSubSections($parentId);
        }

        /**
         * Method to get the number of content nodes for a section id
         *
         * @param string $id The section id
         * @return int
         * @access public
         */
        public function getNodeContentCount($sectionId)
        {
            return $this->_objContent->getNumberOfPagesInSection($sectionId);
        }
		
		/**
		* Method to get the tree to display on the CMS module
		* @return string
		*/
		public function getCMSTree()
		{
			$menu = $this->getTree('cms', TRUE);
			
			$list = new htmllist($menu, array('topMostListClass'=>'treefolder'));
            return $list->getMenu();
		}
		
		/**
		* Method to get the tree to display on the CMS Admin module
		* @return string
		*/
		public function getCMSAdminTree()
		{
			
			$menu = $this->getTree('cmsadmin', FALSE);
			
			$list = new htmllist($menu, array('topMostListClass'=>'treefolder'));
            return $list->getMenu();
		}
		
		/**
		* Method to get the tree drop down when creating a section
		* @param string $defaultSelected The Item to be default selccted on the drop down
		* @param boolean $includeRoot Flag on whether to include root or not
		* @return string
		*/
		public function getCMSAdminDropdownTree($defaultSelected=NULL, $includeRoot=TRUE)
		{
			$menu = $this->getTree('cmsadmin', $includeRoot, FALSE);
			
			$this->loadClass('htmldropdown', 'tree');
			
			$htmldropdown = new htmldropdown($menu, array('inputName'=>'parent', 'selected'=>$defaultSelected));
            return $htmldropdown->getMenu();
		}
		
		/**
		* Method to generate trees for the CMS
		* @param string $module Calling Module for which to set the link to
		* @param boolean $includeRoot Flag to add --Root-- to menu. For CMS, this is the front page
		* @param boolean $useLinks Flag whether to generate a URI or pass the ID only
		*/
        private function getTree($module='cmsadmin', $includeRoot=FALSE, $useLinks=TRUE)
        {
            $this->loadClass('treemenu', 'tree');
            $this->loadClass('treenode', 'tree');
            $this->loadClass('htmllist', 'tree');
			
			$action = ($module == 'cms') ? 'showsection' : 'viewsection';
			
			if ($module == 'cmsadmin') {
				$sql = 'SELECT tbl_cms_sections. * , tbl_cms_content.id AS pagevisible
FROM tbl_cms_sections
LEFT JOIN tbl_cms_content ON ( tbl_cms_sections.id = tbl_cms_content.sectionid
AND tbl_cms_content.published = \'1\' )
GROUP BY tbl_cms_sections.id ORDER BY nodelevel, ordering';
				$useIcon = TRUE;
			} else {
				$sql = 'SELECT tbl_cms_sections. * , tbl_cms_content.id AS pagevisible
FROM tbl_cms_sections
LEFT JOIN tbl_cms_content ON ( tbl_cms_sections.id = tbl_cms_content.sectionid
AND tbl_cms_content.published = \'1\' )
WHERE tbl_cms_sections.published = \'1\' AND tbl_cms_content.published = \'1\'
GROUP BY tbl_cms_sections.id ORDER BY nodelevel, ordering';
			}
			
            $where = 'ORDER BY nodelevel, ordering';
            
            $sections = $this->_objSections->getArray($sql);
            
            $menu = new treemenu();
            
            $nodesArray = array();
            $rootNodesArray = array();
			$visibleNodes = array();
            
            if ($includeRoot) {
				if ($module == 'cmsadmin') {
					$rootNode =& new treenode (array('text'=>'[- Root -]', 'link'=>0));
				} else {
					$rootNode =& new treenode (array('text'=>'Front Page', 'link'=>$this->uri(NULL, 'cms'), 'liClass'=>'sectionfolder'));
				}
                $menu->addItem($rootNode);
            }
            
            if (count($sections) > 0) {
                foreach ($sections as $section)
                {
                    if ($useLinks) {
						$link = $this->uri(array('action'=>$action, 'id'=>$section['id']), $module);
					} else {
						$link = $section['id'];
					}
					
					// Determine the Colour Coding for Sections based on settings
					if ($section['published'] == '0') { // If section is not visible - code is orange
						$cssClass = 'orangefolder';
					} else {
						$cssClass = 'sectionfolder'; // Default - Yellow folder
						
						// If section has no content - gets white folder
						if (is_null($section['pagevisible']) || $section['pagevisible'] == '') {
							$cssClass = 'whitefolder';
						} else {
							// Lastly, check if parent will be shown
							if ($section['parentid'] == '0') { // Root Folder. Meets all criteria, so add
								$visibleNodes[] = $section['id'];
							} else {
								// For others check whether parents are visible - give green folder if not
								if (!in_array($section['parentid'], $visibleNodes)) {
									$cssClass = 'greenfolder';
								} else {
									// if parents are visible. add to list of visible items
									$visibleNodes[] = $section['id'];
								}
							}
						}
					}
					
					
					$node =& new treenode(array('text'=>$section['menutext'], 'link'=>$link, 'liClass'=>$cssClass));
                    
					if ($section['parentid'] == '0') {
						$nodesArray[$section['id']] =& $node;
						
						if ($includeRoot && $module == 'cmsadmin') {
                            $rootNode->addItem($node);
                        } else {
                            $menu->addItem($node);
                        }
					} else {
						if (array_key_exists($section['parentid'], $nodesArray)) {
                            $nodesArray[$section['id']] =& $node;
                            $nodesArray[$section['parentid']]->addItem($node);
                        }
					}
                }
            }
            
			return $menu;
            
            
        }
}
?>