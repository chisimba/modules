<?php

/* -------------------- cmstree class ----------------*/
// security check - must be included in all scripts

if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}

// end security check
/**
* This object is a wrapper class for building a tree using the cms sections
* @package cms
* @category cmsadmin
* @copyright 2004, University of the Western Cape & AVOIR Project
* @license GNU GPL
* @version
* @author Serge Meunier, Prince Mbekwa
* @example :
*/

class contenttree extends object
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
         * Constructor
         */
        public function init()
        {
            try {
                $this->_objSections = & $this->newObject('dbsectiongroup', 'cmsadmin');
                $this->_objContent = & $this->newObject('dbcontent', 'cmsadmin');
                $this->_objUser = & $this->newObject('user', 'security');
                $this->objLanguage = & $this->newObject('language', 'language');

            } catch (Exception $e) {
                throw customException($e->getMessage());
                exit();
            }

        }

        /**
        * Method to return back the tree code
        * @param string $currentNode The currently selected node, which should remain open
           * @param bool $admin Select whether admin user or not
        * @return string
        * @access public
        */
        public function show($currentNode, $admin, $module = 'cms', $sectionAction = 'showsection', $contentAction = 'showcontent')
        {
            $html = $this->buildTree($currentNode, $admin, $module, $sectionAction, $contentAction);
            return $html;
        }

        /**
         * Method to build the tree
         * @param string $currentNode The currently selected node, which should remain open
            * @param bool $admin Select whether admin user or not
         * @return string
         * @access public
         */
        public function buildTree($currentNode, $admin, $module = 'cms', $sectionAction = 'showsection', $contentAction = 'showcontent')
        {
            //check if there are any root nodes
				
            if ($this->getChildNodeCount(0) > 0) {
            	$html = '<div id="productsandservices" class="yuimenu">
                		 <div class="bd">
                		 <ul class="first-of-type">';
                //start the tree building
                $html .= $this->buildLevel(0, $currentNode, $admin, $module, $sectionAction, $contentAction);
                $html .= '</ul></div></div><!-- end: tree div -->';
            } else {
                $html = '';
            }
			return $html;
        }

        /**
         * Method to build the next level in tree
            * @param string $parentid The node id whose child nodes need to be built
         * @param string $currentNode The currently selected node, which should remain open
            * @param bool $admin Select whether admin user or not
         * @return string
         * @access public
         */
        public function buildLevel($parentId, $currentNode, $admin, $module, $sectionAction, $contentAction)
        {
            //gets all the child nodes of id
            $nodes = $this->getChildNodes($parentId, $admin);
        
            //get the list of nodes that need to stay open for the currently selected node
                    
            if (!empty($nodes)) {

                $htmlLevel = '';
                foreach($nodes as $node) {
                    $item = '';
                  

                    if (!empty($sectionAction)) {
                        $nodeUri = $this->uri(array('action' => $sectionAction, 'id' => $node['id'], 'sectionid' => $node['id']), $module);
                        $link = '<a href="'.$nodeUri.'">'.htmlentities($node['title']).'</a>';
                    } else {
                        $link = htmlentities($node['title']);
                    }
                     // if node has further child nodes, recursively call buildLevel
                    if ($this->getChildNodes($node['id'], $admin)) {
                    	$htmlLevel .= "<li class='yuimenuitem first-of-type'>".$link;
                    	$item = $this->addContent($node['id'], $module, $contentAction, $admin);
                    	$htmlLevel .= $item.'</li>' ;
                        
                        $this->buildLevel($node['id'], $currentNode, $admin, $module, $sectionAction, $contentAction);
                    }else{
                    	  $htmlLevel .= "<li class='yuimenuitem first-of-type'>".$link;
                    	  $item .= $this->addContent($node['id'], $module, $contentAction, $admin);
                    	  $htmlLevel .= $item.'</li>' ;
                    }
                                       
                }

                return $htmlLevel;
            }
            // if no nodes return empty string
            return '';
        }

        /**
         * Method to get a list of all nodes to remain open
         * @param string $currentNode The currently selected node
         * @return array
         * @access public
         */
        public function getOpenNodes($currentNode)
        {
            $nodeId = $currentNode;

            $openNodes = array();

            $openNodes[0] = $currentNode;
            $i = 1;

            while ($nodeId != '0') {
                $node = $this->getNode($nodeId);

                if (count($node)) {
                    $nodeId = $node['parentid'];
                    $openNodes[$i] = $nodeId;
                    $i++;
                } else {
                    $nodeId = '0';
                }
            }

            return $openNodes;
        }

        /**
         * Method to get add all content for a particular section node
         * @param string $id The id of the section node
         * @return string
         * @access public
         */
     public function addContent($id, $module, $action, $admin = FALSE)
        {       	
            
            $contentNodes = $this->getContent($id, $admin);

            $htmlContent = '';
            if (!empty($contentNodes)) {
            	$htmlContent =  '<div id="'.htmlentities($contentNodes[0]['title']).'" class="yuimenu">';
            	$htmlContent .=		'<div class="bd">';
		        $htmlContent .=		'<ul>';	
                foreach($contentNodes as $contentNode) {
                    if (!empty($action)) {
                        $url = $this->uri(array('action' => $action, 'id' => $contentNode['id'], 'sectionid' => $contentNode['sectionid']), $module);
                        $link = '<a href="'.$url.'">'.$contentNode['title'].'</a>';
                    } else {
                        $link = $contentNode['title'];
                    }
                    $htmlContent .='<li class="yuimenuitem">'.$link.'</li>';
                }
                 if ($this->getChildNodes($id, $admin)) {
		         
                 	$htmlContent .= $this->addChildren($id, $module, $action, $admin);
		          }
                return $htmlContent .'</ul></div></div>';
            }
            return '';
        }
        
        /**
         * Method to get add all content for a particular child node
         * @param string $id The id of the section node
         * @return string
         * @access public
         */
        public function addChildren($id, $module, $action, $admin = FALSE)
        {
        	 //gets all the child nodes of id
            $nodes = $this->getChildNodes($id, $admin);
            $htmlContent = '';
        	foreach($nodes as $node) {
                  
                    if (!empty($action)) {
                        $nodeUri = $this->uri(array('action' => $action, 'id' => $node['id'], 'sectionid' => $node['id']), $module);
                        $link = '<a href="'.$nodeUri.'">'.$node['title'].'</a>';
                    } else {
                        $link = htmlentities($node['title']);
                    }

                   	$htmlContent .= "<li class='yuimenuitem'>".$link;
                        	                        
                    $contentNodes = $this->getContent($node['id'], $admin);
			         
		            if (!empty($contentNodes)) {
		            	$htmlContent .='<div id="'.$contentNodes[0]['title'].'" class="yuimenu">';
		            	$htmlContent .=	'<div class="bd">';
		            	$htmlContent .=	'<ul class="first-of-type">';		
			             foreach($contentNodes as $contentNode) {
			                    if (!empty($action)) {
			                        $url = $this->uri(array('action' => $action, 'id' => $contentNode['id'], 'sectionid' => $contentNode['sectionid']), $module);
			                        $link = '<a href="'.$url.'">'.$contentNode['title'].'</a>';
			                    } else {
			                        $link = $contentNode['title'];
			                    }
			                    $htmlContent .='<li class="yuimenuitem">'.$link.'</li>';
			                }
		               
		                $htmlContent .='</ul>';
		                $htmlContent .='</div>';
		                $htmlContent .='</div>';
		                $htmlContent .='</li>';
		               
		                
		            }
                  

                }
                       
           
            return $htmlContent;
        }


        /**
         * Method to get all child nodes for a particular node
         * @param string $parentId The parent node id
         * @return array
         * @access public
         */
        public function getChildNodes($parentId, $noPermissions = FALSE)
        {
            return $this->_objSections->getChildNodes($parentId, $noPermissions);
        }

        /**
         * Method to get node for a particular id
         * @param string $id The node id
         * @return array
         * @access public
         */
        public function getNode($id, $noPermissions = FALSE)
        {
            return $this->_objSections->getNode($id, $noPermissions);
        }

        /**
         * Method to get all content nodes for a particular section node
         * @param string $sectionId The section id
         * @return array
         * @access public
         */
        public function getContent($sectionId, $admin)
        {
            $published = $admin;
            return $this->_objContent->getPagesInSection($sectionId, $published);
        }

        /**
         * Method to get number of child nodes for a particular node
         * @param string $parentId The parent node id
         * @return int
         * @access public
         */
        public function getChildNodeCount($parentId, $noPermissions = FALSE)
        {
        	
            return $this->_objSections->getChildNodeCount($parentId, $noPermissions);
        }

        /**
         * Method to get the number of content nodes for a section id
         * @param string $id The section id
         * @return int
         * @access public
         */
        public function getNodeContentCount($sectionId)
        {
            return $this->_objContent->getNumberOfPagesInSection($sectionId);
        }

}

?>