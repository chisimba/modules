<?php

/* -------------------- dbTable class ----------------*/
// security check - must be included in all scripts

if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}

// end security check
/**
* Data access class for the cmsadmin module. Used to access data in the sections table. 
*
* @package cmsadmin
* @category chisimba
* @copyright AVOIR 
* @license GNU GPL
* @author Wesley Nitsckie
* @author Warren Windvogel
*/

class dbsections extends dbTable
{

        /**
        * The dbcontent object
        *
        * @access private
        * @var object
        */
        protected $_objDBContent;

        /**
        * The language object
        *
        * @access private
        * @var object
        */
        protected $_objLanguage;
        /**
         * The user object
         *
         * @var object
         */
        protected $_objUser;
        /**
	     * The sections  object
	     *
	     * @access private
	     * @var object
	    */
	    protected $TreeNodes;


	   /**
	    * Class Constructor
	    *
	    * @access public
	    * @return void
	    */
        public function init()
        {
        	try {        
                parent::init('tbl_cms_sections');
                $this->table = 'tbl_cms_sections';
                $this->_objDBContent = $this->getObject('dbcontent', 'cmsadmin');
                $this->_objLanguage = $this->getObject('language', 'language');
                $this->_objUser =  $this->getObject('user', 'security');
                $this->TreeNodes = & $this->newObject('treenodes', 'cmsadmin');
           } catch (Exception $e){
       		    throw customException($e->getMessage());
        	    exit();
     	   }
        }

        /**
         * Method to get the list of sections
         *
         * @access public
         * @param bool $isPublished TRUE | FALSE To get published sections
         * @return array An array of associative arrays of all sections
         */
        public function getSections($isPublished = NULL, $filter = null)
        {
            if ($isPublished||$filter==null) {
                return $this->getAll('WHERE published = 1 AND trash = 0 ORDER BY ordering');
            }elseif(!$isPublished||$filter!=null) {
                return $this->getAll('WHERE title LIKE '%".$filter."%' AND trash = 0 ORDER BY ordering');
            }
           // if ($filter!=null) {
            ///	return $this->getAll('WHERE title LIKE '%".$filter."%' ORDER BY ordering');
           // }
        }

        /**
         * Method to get a filtered list of sections
         *
         * @author Megan Watson
         * @access public
         * @return array An array of associative arrays of the sections
         */
        public function getFilteredSections($text = '', $publish = FALSE)
        {
            $sql = "SELECT * FROM {$this->table} ";
            $filter = '';
            if(!($publish === FALSE)){
                $filter .= "published = {$publish} ";
            }
            
            if(!empty($text)){
                if(!empty($filter)){
                    $filter .= ' AND ';
                }
                $filter .= "(LOWER(title) LIKE '%".strtolower($text)."%' OR LOWER(menutext) LIKE '%".strtolower($text)."%')";
            }
            
            if(!empty($filter)){
                $sql .= "WHERE {$filter} AND trash = 0 ";
            }else{
                $sql .= "WHERE trash = 0 ";
            }
            $sql .= ' ORDER BY ordering';
            
            return $this->getArray($sql);
        }

        /**
         * Method to get the archived content
         *
         * @author Megan Watson
         * @param string $filter The Filter
         * @return  array An array of associative arrays of all content pages in relationto filter specified
         * @access public
         */
        public function getArchiveSections($filter = '')
        {
            $sql = "SELECT * FROM {$this->table} WHERE trash = 1 ";
            
            if(!empty($filter)){
                $sql .= "AND LOWER(title) LIKE '%".strtolower($filter)."%' ";
            }
            
            $sql .= 'ORDER BY ordering';
            return $this->getArray($sql);
        }
        
        /**
         * Method to get the list of root nodes
         *
         * @access public
         * @param bool $isPublished TRUE | FALSE To get published sections
         * @param string contextcode The current context the user is in
         * @return array An array of associative arrays of all root nodes
         */
        public function getRootNodes($isPublished = FALSE, $contextcode = NULL)
        {
            $sql = '';
            // Check for published / visible
            if($isPublished){
                $sql = 'published = 1 ';
            }
            // Check for the context code
            if(!empty($contextcode)){
                if(!empty($sql)){
                    $sql .= 'AND ';
                }
                $sql .= "contextcode = '$contextcode' ";
            }
            
            if(!empty($sql)){
                $sql .= 'AND ';
            }
            
            $filter = "WHERE {$sql} nodelevel = 1 AND trash = 0 ORDER BY ordering";
            $results = $this->getAll($filter);
            return $results;
        }

        /**
         * Method to get a Section
         *
         * @param  string $id The section id
         * @return array An array of the sections details
         * @access public
         */
        public function getSection($id)
        {
            return $this->getRow('id', $id);
        }

        /**
         * Method to get the first sections id(pk)
         *
         * @param bool $isPublished TRUE | FALSE To get published sections
         * @return string First sections id
         * @access public
         */
        public function getFirstSectionId($isPublished = FALSE)
        {
            $firstSectionId = '';
            $firstSection = $this->getAll('WHERE parentid=0 AND trash = 0 ORDER BY ordering');
            if(!empty($firstSection)) {
                if($isPublished) {
                    foreach($firstSection as $section) {
                        if($section['published'] == 1) {
                            $firstSectionId = $section['id'];
                            break;
                        }
                    }
                } else {
                    $firstSectionId = $firstSection['0']['id'];
                }
            }
            return $firstSectionId;
        }

        /**
         * Method to add a section to the database
         *
         * @access public
         * @return bool
         */
        public function add($contextcode=null)
        {
            //get param from dropdown
            $parentSelected = $this->getParam('parent');
            //get parent type "subsection", "root" or "param is null"(new section will be root level) and its id
            $id = $parentSelected;
            $parentid = $id;

            if ($this->getLevel($parentid) == '1' || $this->getLevel($parentid) == '0') {
            	
                $rootid = $parentid;
                $rootnode = $this->checkindex($rootid);
                //Get section details
                $title = $this->getParam('title');
                $menuText = $this->getParam('menutext');
                $access = $this->getParam('access');
                $description = str_ireplace("<br />", " <br /> ",$this->getParam('introtext'));
                $published = $this->getParam('published');
                $layout = $this->getParam('display');
                $showdate = $this->getParam('showdate');
                $hidetitle = $this->getParam('hidetitle');
                $showintroduction = $this->getParam('showintro');
                $user = $this->_objUser->userId();
                if($this->getParam('pagenum') == 'custom') {
                	$numpagedisplay = $this->getParam('customnumber');
                } else {
                	$numpagedisplay = $this->getParam('pagenum');
                }
                $ordertype = $this->getParam('pageorder');
                $ordering = $this->getOrdering($parentid);

                //Add section
                $index = array(
                'rootid' => $rootid,
                'parentid' => $parentid,
                'title' => $title,
                'menutext' => $menuText,
                'access' => $access,
                'layout' => $layout,
                'ordering' => $ordering,
                'description' => $description,
                'published' => $published,
                'hidetitle' => $hidetitle,
                'showdate' => $showdate,
                'showintroduction' => $showintroduction,
                'numpagedisplay' => $numpagedisplay,
                'ordertype' => $ordertype,
                'nodelevel' => $this->getLevel($parentid) + '1',
                'datecreated'=>$this->now(),
                'userid' => $user,
                'link' => $this->getParam('imagesrc'),
                'contextcode' =>$contextcode
                );
                $result = $this->insert($index);
                $this->luceneIndex($index);
                return $result;
               
             
            } else {
                $rootid = $this->getRootNodeId($id);
                $rootnode = $this->checkindex($rootid);
                //Get section details
                $title = $this->getParam('title');
                $menuText = $this->getParam('menutext');
                $access = $this->getParam('access');
                $description = str_ireplace("<br />", " <br /> ",$this->getParam('introtext'));
                $published = $this->getParam('published');
                $layout = $this->getParam('display');
                $showdate = $this->getParam('showdate');
                $hidetitle = $this->getParam('hidetitle');
                $showintroduction = $this->getParam('showintro');
                $user = $this->_objUser->userId();
                if($this->getParam('pagenum') == 'custom') {
                	$numpagedisplay = $this->getParam('customnumber');
                } else {
                	$numpagedisplay = $this->getParam('pagenum');
                }
                $ordertype = $this->getParam('pageorder');
                $ordering = $this->getOrdering($parentid);

                //Add section
                $index = array(
                'rootid' => $rootid,
                'parentid' => $parentid,
                'title' => $title,
                'menutext' => $menuText,
                'access' => $access,
                'layout' => $layout,
                'ordering' => $ordering,
                'description' => $description,
                'published' => $published,
                'showdate' => $showdate,
                'hidetitle' => $hidetitle,
                'showintroduction' => $showintroduction,
                'numpagedisplay' => $numpagedisplay,
                'ordertype' => $ordertype,
                'nodelevel' => $this->getLevel($parentid) + '1',
                'datecreated'=>$this->now(),
                'userid' => $user,
                'link' => $this->getParam('imagesrc'),
                'contextcode' =>$contextcode
                );
                
                $result = $this->insert($index);
                $this->luceneIndex($index);
                return $result;
              
                
            }
            
        }
        
        private function checkindex($rootid=null,$parentid=null){
        	
        	$rootid = $this->TreeNodes->getArtifact($rootid);
        	return $rootid;
        }

        /**
         * Method to add a section to the database
         *
         * @param string $parent The id of the parent node. '0' for root nodes
         * @param string $title The title of the new section
         * @param string $menuText The text that will appear in the tree menu
         * @param bool $published Whether page will be visible or not
         * @param bool $access True if "registered" page False if "public" page
         * @param string $description The introduction text 
         * @param string $layout The layout type of the section
         * @param bool $showdate Whether date will be visible or not
         * @param bool $showintroduction Whether introduction will be visible or not
         * @param int $numpagedisplay Number of pages to display 
         * @param string $ordertype How the page should be ordered
         * @param string $contextCode The context code if you are using the cms as the context content manager
         * @access public
         * @return bool
         */
        public function addNewSection($parent, $title, $menuText, $access, $description, $published, $layout, $showdate, $showintroduction, $numpagedisplay, $ordertype, $contextCode = null)
        {
            //get param from dropdown
            $parentSelected = $parent;
            //get parent type "subsection", "root" or "param is null"(new section will be root level) and its id
            $id = $parentSelected;
            $parentid = $id;

            if ($this->getLevel($parentid) == '1' || $this->getLevel($parentid) == '0') {
                $rootid = $parentid;
            } else {
                $rootid = $this->getRootNodeId($id);
            }
            //Set ordering
            $ordering = $this->getOrdering($parentid);
            //Add section
            $newIndex =array(        'rootid' => $rootid,
                                     'parentid' => $parentid,
                                     'title' => $title,
                                     'menutext' => $menuText,
                                     'access' => $access,
                                     'layout' => $layout,
                                     'ordering' => $ordering,
                                     'description' => str_ireplace("<br />", " <br /> ",$description),
                                     'published' => $published,
                                     'showdate' => $showdate,
                                     'showintroduction' => $showintroduction,
                                     'numpagedisplay' => $numpagedisplay,
                                     'ordertype' => $ordertype,
                                     'nodelevel' => $this->getLevel($parentid) + '1',
                                     'contextcode' => $contextCode
                                 );
            $result = $this->insert($newIndex);
            $this->luceneIndex($newIndex);
            return $result;
        }

        /**
         * Method to edit a section in the database
         *
         * @access public
         * @return bool
         */
        public function edit()
        {
            //Get section details
            $id = $this->getParam('id');
            $parentid = $this->getParam('parent');
            $rootid = $this->getParam('rootid');
            $title = $this->getParam('title');
            $menuText = $this->getParam('menutext');
            $access = $this->getParam('access');
            $description = str_ireplace("<br />", " <br /> ",$this->getParam('introtext'));
            $published = $this->getParam('published');
            $layout = $this->getParam('display');
            $showdate = $this->getParam('showdate');
            $hidetitle = $this->getParam('hidetitle');
            $showintroduction = $this->getParam('showintro');
            if($this->getParam('pagenum') == 'custom') {
                $numpagedisplay = $this->getParam('customnumber');
            } else {
                $numpagedisplay = $this->getParam('pagenum');
            }
            $ordertype = $this->getParam('pageorder');
            $ordering = $this->getParam('ordering');
            $count = $this->getParam('count');
            $arrFields = array(
                             'rootid' => $rootid,
                             'parentid' => $parentid,
                             'title' => $title,
                             'menutext' => $menuText,
                             'access' => $access,
                             'layout' => $layout,
                             'ordering' => $ordering,
                             'showdate' => $showdate,
                             'hidetitle' => $hidetitle,
                             'showintroduction' => $showintroduction,
                             'numpagedisplay' => $numpagedisplay,
                             'ordertype' => $ordertype,
                             'description' => $description,
                             'nodelevel' => $count,
                             'lastupdatedby'=> $this->_objUser->userid(),
                             'updated' => $this->now(),
                             'link' => $this->getParam('imagesrc'),
                             'published' => $published);
            return $this->update('id', $id, $arrFields);
        }

        /**
         * Method to check if there is sections
         *
         * @access public
         * @return boolean
         */
        public function isSections()
        {
            $list = $this->getAll();

            if (count($list) > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        }

        /**
         * Method to get the menutext for a section
         *
         * @return string $menutext The title that will appear on the tree menu
         * @access public
         * @param string $id The id of the section
         */
        public function getMenuText($id)
        {
            $line = $this->getSection($id);
            
            $menutext = $line['menutext'];
            return $menutext;
        }

        /**
         * Method to toggle the publish field 
         * 
         * @param string id The id if the section
         * @access public
         * @return boolean
         * @author Wesley Nitsckie
         */
        public function togglePublish($id)
        {
            $row = $this->getSection($id);

            if ($row['published'] == 1) {
                return $this->update('id', $id , array('published' => 0) );
            } else {
                return $this->update('id', $id , array('published' => 1) );
            }
        }

        /**
         * Method to publish or unpublish sections 
         * 
         * @param string id The id if the section
         * @param string $task Publish or unpublish
         * @access public
         * @return boolean
         * @author Megan Watson
         */
        public function publish($id, $task = 'publish')
        {
            switch($task){
                case 'publish':
                    $fields['published'] = 1;
                    break;
                case 'unpublish':
                    $fields['published'] = 0;
                    break;
            }
            
            return $this->update('id', $id, $fields);
        }

        /**
         * Method to check if a section has child/leaf node(s)
         *
         * @param string $id The id(pk) of the section
         * @return bool True if has nodes else False
         * @access public
         */
        public function hasNodes($id)
        {
            $nodes = $this->getAll("WHERE parentid = '$id' AND trash = 0");

            if (count($nodes) > 0) {
                $hasNodes = True;
            } else {
                $hasNodes = False;
            }
            return $hasNodes;
        }

        /**
         * Method to return the count value of a section
         *
         * @param string $id The id(pk) of the section
         * @return int $count The value of the count field
         * @access public
         */
        public function getLevel($id)
        {
            $count = 0;
            //get entry
            $section = $this->getRow('id', $id);

            if (!empty($section)) {
                //get and return value of count field
                $count = $section['nodelevel'];
            }

            return $count;
        }

        /**
         * Method to return a sections root node id
         *
         * @param string $id The id(pk) of the section
         * @return string $rootId The id(pk) of the sections root node
         * @access public
         */
        public function getRootNodeId($id)
        {
            //get entry
            $section = $this->getRow('id', $id);
            //get and return value of count field
            $rootId = $section['rootid'];
            return $rootId;
        }

        /**
        * Method to return all sections
        *
        * @access public
        */
        public function getAllSections()
        {
            return $this->getAll("WHERE trash = '0'");
        }

        /**
         * Method to get all subsections in a specific section
         *
         * @param string $sectionId The id(pk) of the section
         * @param int $level The node level in question  
         * @param string $order Either DESC or ASC
         * @param bool $isPublished TRUE | FALSE To get published sections
         * @return array $subsections An array of associative arrays for all categories in the section
         * @access public
         */
        public function getSubSectionsInSection($sectionId, $order = 'ASC', $isPublished = FALSE)
        {
            if ($isPublished) {
                //return all subsections
                return $this->getAll("WHERE published = 1 AND parentid = '$sectionId' AND trash = 0 ORDER BY ordering $order");
            } else {
                return $this->getAll("WHERE parentid = '$sectionId' AND trash = 0 ORDER BY ordering $order");
            }
        }

        /**
         * Method to get all subsections in a specific root
         *
         * @param string $rootId The id(pk) of the section
         * @param bool $isPublished TRUE | FALSE To get published sections
         * @return array $subsections An array of associative arrays for all categories in the section
         * @access public
         */
        public function getSubSectionsInRoot($rootId, $order = 'ASC',$isPublished = FALSE)
        {
            if ($isPublished) {
                //return all subsections
                return $this->getAll("WHERE published = 1 AND rootid = '$rootId' AND trash = 0 ORDER BY ordering");
            } else {
                return $this->getAll("WHERE rootid = '$rootId' AND trash = 0 ORDER BY ordering $order");
            }
        }

        /**
         * Method to get all subsections in a specific level
         *
         * @param string $rootId The id(pk) of the sections root node
         * @param int $level The node level in question  
         * @param int $order Either DESC or ASC 
         * @param bool $isPublished TRUE | FALSE To get published sections
         * @return array $subsections An array of associative arrays for all sub sections in the section
         * @access public
         */
        public function getSubSectionsForLevel($rootId, $level, $order = 'ASC', $isPublished = FALSE)
        {
            if ($isPublished) {
                //return all subsections
                return $this->getAll("WHERE published = 1 AND nodelevel = '$level' AND rootid = '$rootId' AND trash = 0 ORDER BY ordering $order");
            } else {
                return $this->getAll("WHERE nodelevel = '$level' AND rootid = '$rootId' AND trash = 0 ORDER BY ordering $order");
            }
        }

        /**
         * Method to get the number of sub sections in a section
         *
         * @param string $sectionId The id(pk) of the section
         * @return int $noSubSecs The number of subsections in the section
         * @access public
         */
        public function getNumSubSections($sectionId)
        {
            $subSecs = $this->getAll("WHERE parentid = '$sectionId' AND trash = 0");
            $noSubSecs = count($subSecs);
            return $noSubSecs;
        }

        /**
         * Method to delete a section
         *
         * @param string $id The id(pk) of the section
         * @return bool
         * @access public
         */
        public function deleteSection($id)
        {
            $sectionData = $this->getSection($id);
            
            // if section is root - archive everything below it
            if($sectionData['nodelevel'] == 1){
                $nodes = $this->getAll("WHERE rootid = '{$id}'");
                
                if(!empty($nodes)){
                    foreach($nodes as $item){
                        $this->_objDBContent->resetSection($item['id']);
                        $this->archive($item['id']);
                    }
                }
                // Restore root node
                $this->_objDBContent->resetSection($id);
                $this->archive($id);
            }else{
                // find nodes below section
                $nodeData = $this->getAll("WHERE parentid = '{$id}'");
                
                if(!empty($nodeData)){
                    foreach($nodeData as $item){
                        $this->deleteSection($item['id']);
                    }
                }
                $this->_objDBContent->resetSection($id);
                $this->archive($id);
            }
        }

        /**
        * Method to archive a section
        *
        * @access private
        * @param string $id The section id
        * @return bool
        */
        private function archive($id, $restore = FALSE)
        {
            $trash = 1;
            $order = '';
            
            if($restore){
                $trash = 0;
                $order = $this->getOrdering($id);
            }
            $fields = array('trash' => $trash, 'ordering' => $order);
            $result =  $this->update('id', $id, $fields);
        }

        /**
        * Method to restore a section
        *
        * @access public
        * @param string $id The section id
        * @return bool
        */
        public function unarchiveSection($id)
        {
            $sectionData = $this->getSection($id);
            
            if($sectionData['nodelevel'] == 1){
                $nodes = $this->getAll("WHERE rootid = '{$id}'");
                
                if(!empty($nodes)){
                    foreach($nodes as $item){
                        $this->unarchiveSectionContent($item['id']);
                        $this->archive($item['id'], TRUE);
                    }
                }
                // Restore root node
                $this->unarchiveSectionContent($id);
                $this->archive($id, TRUE);
            }else{
                // find nodes below section
                $nodeData = $this->getAll("WHERE parentid = '{$id}'");
                
                if(!empty($nodeData)){
                    foreach($nodeData as $item){
                        $this->unarchiveSection($item['id']);
                    }
                }
                $this->unarchiveSectionContent($id);
                $this->archive($id, TRUE);
            }
        }
        
        /**
        * Method to loop through and restore the content in a section
        *
        * @access private
        * @param string $id The section id
        * @return bool
        */
        private function unarchiveSectionContent($id)
        {
            return $this->_objDBContent->unarchiveSection($id);
        }

        /**
        * Method to permanently delete a section
        *
        * @access public
        * @param string $id The section id
        * @return bool
        */
        public function permanentlyDelete($id)
        {
            return $this->delete('id', $id);
        }

        /**
         * Method to return the ordering value of new section (gets added last)
         *
         * @param string $parentid The id(pk) of the parent. Uses root node order if NULL
         * @return int $ordering The value to insert into the ordering field
         * @access public
         * @author Warren Windvogel
         */
        public function getOrdering($parentid = NULL)
        {
            $ordering = 1;
            //get last order value
            $lastOrder = $this->getAll("WHERE parentid = '$parentid' AND trash = 0 ORDER BY ordering DESC LIMIT 1");
            //add after this value

            if (!empty($lastOrder)) {
                $ordering = $lastOrder['0']['ordering'] + 1;
            }

            return $ordering;
        }

        /**
         * Method to return the links to be displayed in the order column on the table
         * 
         * @param string $id The id of the entry 
         * @return string $links The html for the links
         * @access public
         * @return bool
         * @author Warren Windvogel
         */
        public function getOrderingLink($id)
        {
            //Get the parent id
            $entry = $this->getRow('id', $id);
            $parentId = $entry['parentid'];

            if (empty($parentId)) {
                //Get the number of root sections
                $lastOrd = $this->getAll("WHERE nodelevel = 1 AND trash = 0 ORDER BY ordering DESC LIMIT 1");
            } else {
                //Get the number of sub sections in section
                $lastOrd = $this->getAll("WHERE parentid = '$parentId' AND trash = 0 ORDER BY ordering DESC LIMIT 1");
            }

            $topOrder = $lastOrd['0']['ordering'];
            $links = " ";

            if ($topOrder > '1') {
                //Create geticon obj
                $this->objIcon = & $this->newObject('geticon', 'htmlelements');

                if ($entry['ordering'] == '1') {
                    //return down arrow link
                    //icon
                    $this->objIcon->setIcon('downend');
                    $this->objIcon->title = $this->_objLanguage->languageText('mod_cmsadmin_changeorderdown', 'cmsadmin');
                    //link
                    $downLink = & $this->newObject('link', 'htmlelements');
                    $downLink->href = $this->uri(array('action' => 'changesectionorder', 'id' => $id, 'ordering' => 'up', 'parent' => $entry['parentid']));
                    $downLink->link = $this->objIcon->show();
                    $links .= $downLink->show();
                } else if ($entry['ordering'] == $topOrder) {
                    //return up arrow
                    //icon
                    $this->objIcon->setIcon('upend');
                    $this->objIcon->title = $this->_objLanguage->languageText('mod_cmsadmin_changeorderup', 'cmsadmin');
                    //link
                    $upLink = & $this->newObject('link', 'htmlelements');
                    $upLink->href = $this->uri(array('action' => 'changesectionorder', 'id' => $id, 'ordering' => 'down', 'parent' => $entry['parentid']));
                    $upLink->link = $this->objIcon->show();

                    $links .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $upLink->show();
                } else {
                    //return both arrows
                    //icon
                    $this->objIcon->setIcon('down');
                    $this->objIcon->title = $this->_objLanguage->languageText('mod_cmsadmin_changeorderdown', 'cmsadmin');
                    //link
                    $downLink = & $this->newObject('link', 'htmlelements');
                    $downLink->href = $this->uri(array('action' => 'changesectionorder', 'id' => $id, 'ordering' => 'up', 'parent' => $entry['parentid']));
                    $downLink->link = $this->objIcon->show();
                    //icon
                    $this->objIcon->setIcon('up');
                    $this->objIcon->title = $this->_objLanguage->languageText('mod_cmsadmin_changeorderup', 'cmsadmin');
                    //link
                    $upLink = & $this->newObject('link', 'htmlelements');
                    $upLink->href = $this->uri(array('action' => 'changesectionorder', 'id' => $id, 'ordering' => 'down', 'parent' => $entry['parentid']));
                    $upLink->link = $this->objIcon->show();
                    $links .= $downLink->show() . '&nbsp;' . $upLink->show();
                }
            }

            return $links;
        }

        /**
         * Method to update the order of the frontpage
         * 
         * @param string $id The id of the entry to move
         * @param int $ordering How to update the order(up or down).
         * @access public
         * @return bool
         * @author Warren Windvogel
         */
        public function changeOrder($id, $ordering, $parentid)
        {
            //Get array of all sections in level
            $fpContent = $this->getAll("WHERE parentid = '$parentid' AND trash = 0 ORDER BY ordering ");
            //Search for entry to be reordered and update order
            foreach($fpContent as $content) {
                if ($content['id'] == $id) {
                    if ($ordering == 'up') {
                        $changeTo = $content['ordering'];
                        $toChange = $content['ordering'] + 1;
                        $updateArray = array(
                                           'ordering' => $toChange
                                       );
                        $this->update('id', $id, $updateArray);
                    } else {
                        $changeTo = $content['ordering'];
                        $toChange = $content['ordering'] - 1;
                        $updateArray = array(
                                           'ordering' => $toChange
                                       );
                        $this->update('id', $id, $updateArray);
                    }
                }
            }

            //Get other entry to change
            $entries = $this->getAll("WHERE parentid = '$parentid' AND ordering = '$toChange' AND trash = 0");
            foreach($entries as $entry) {
                if ($entry['id'] != $id) {
                    $upArr = array(
                                 'ordering' => $changeTo
                             );
                    $this->update('id', $entry['id'], $upArr);
                }
            }
            $this->reorderSections($parentid);
        }
        
        /**
        * Method to reorder the sections
        *
        * @author Megan Watson
        * @param string $parentid The parent id of the sections to be re ordered
        * @access private
        * @return void
        */
        private function reorderSections($parentid)
        {   
            // Get all pages
            $sectionData = $this->getAll("WHERE parentid = '$parentid' AND trash = 0 ORDER BY ordering ");
            
            if(!empty($sectionData)){
                    
                $i = 1;
                foreach($sectionData as $key => $item){
                    $this->update('id', $item['id'], array('ordering' => $i));
                    $sectionData[$key]['ordering'] = $i++;
                }
                        
                // Get the ordering position of the last page
                $newData = array_reverse($sectionData);
                $lastOrder = $newData[0]['ordering']+1;
                            
                // Remove all null and negative numbers
                foreach($sectionData as $key => $item){
                    if($item['ordering'] < 0 || is_null($item['ordering'])){
                        $this->update('id', $item['id'], array('ordering' => $lastOrder++));
                    }
                }
            }
        }

        /**
        * Method to get the type of section in a human readable format
        *
        * @access public
        * @param string $orderType Type of Order Code
        * @return string containing the type of order in a human readable format.
        */
        public function getPageOrderType($orderType)
        {
            switch ($orderType) {
            case 'pageorder':
                    $order = $this->_objLanguage->languageText('mod_cmsadmin_order_pageorder', 'cmsadmin');
                break;

            case 'pagedate_asc':
                $order = $this->_objLanguage->languageText('mod_cmsadmin_order_pagedate_asc', 'cmsadmin');
                break;

            case 'pagedate_desc':
                $order = $this->_objLanguage->languageText('mod_cmsadmin_order_pagedate_desc', 'cmsadmin');
                break;

            case 'pagetitle_asc':
                $order = $this->_objLanguage->languageText('mod_cmsadmin_order_pagetitle_asc', 'cmsadmin');
                break;

            case 'pagetitle_desc':
                $order = $this->_objLanguage->languageText('mod_cmsadmin_order_pagetitle_desc', 'cmsadmin');
                break;

            default:
                $order = $this->_objLanguage->languageText('word_unknown');
                break;
            }

            return $order;
        }

        public function luceneIndex($data)
        {
        	//print_r($data); die();
        	$this->objConfig = $this->getObject('altconfig', 'config');
        	$this->objUser = $this->getObject('user', 'security');
        	$indexPath = $this->objConfig->getcontentBasePath();
        	if(file_exists($indexPath.'chisimbaIndex/segments'))
        	{
        		chmod($indexPath.'chisimbaIndex', 0777);
        		//we build onto the previous index
        		$index = new Zend_Search_Lucene($indexPath.'chisimbaIndex');
        	}
        	else {
        		//instantiate the lucene engine and create a new index
        		mkdir($indexPath.'chisimbaIndex');
        		chmod($indexPath.'chisimbaIndex', 0777);
        		$index = new Zend_Search_Lucene($indexPath.'chisimbaIndex', true);
        	}
        	//hook up the document parser
        	$document = new Zend_Search_Lucene_Document();
        	//change directory to the index path
        	chdir($indexPath);

        	//set the properties that we want to use in our index
        	//id for the index and optimization
        	$document->addField(Zend_Search_Lucene_Field::Text('docid', $data['id']));
        	//date
        	$document->addField(Zend_Search_Lucene_Field::UnIndexed('date', $data['creation']));
        	//url
        	$document->addField(Zend_Search_Lucene_Field::UnIndexed('url', $this->uri(array('module' => 'cms', 'action' => 'showsection', 'id' => $data['id'], 'sectionid'=> $data['sectionid']))));
        	//createdBy
        	$document->addField(Zend_Search_Lucene_Field::Text('createdBy', $this->objUser->fullName($data['userid'])));
        	//document teaser
        	$document->addField(Zend_Search_Lucene_Field::Text('teaser', $data['description']));
        	//doc title
        	$document->addField(Zend_Search_Lucene_Field::Text('title', $data['title']));
        	//doc author
        	$document->addField(Zend_Search_Lucene_Field::Text('author', $this->objUser->fullName($data['userid'])));
        	//document body
        	//NOTE: this is not actually put into the index, so as to keep the index nice and small
        	//      only a reference is inserted to the index.
        	$document->addField(Zend_Search_Lucene_Field::Text('contents', $data['body']));
        	//what else do we need here???
        	//add the document to the index
        	$index->addDocument($document);
        	//commit the index to disc
        	$index->commit();
        	//optimize the thing
        	//$index->optimize();
        }

        public function luceneReIndex($data)
        {
        	//var_dump($data);
        	$this->objConfig = $this->getObject('altconfig', 'config');
        	$this->objUser = $this->getObject('user', 'security');
        	$indexPath = $this->objConfig->getcontentBasePath();
        	if(file_exists($indexPath.'chisimbaIndex/segments'))
        	{
        		chmod($indexPath.'chisimbaIndex', 0777);
        		//we build onto the previous index
        		$index = new Zend_Search_Lucene($indexPath.'chisimbaIndex');
        	}
        	else {
        		//instantiate the lucene engine and create a new index
        		mkdir($indexPath.'chisimbaIndex');
        		chmod($indexPath.'chisimbaIndex', 0777);
        		$index = new Zend_Search_Lucene($indexPath.'chisimbaIndex', true);
        	}
        	$docid = $data['id'];
        	$removePath = $docid;
        	$hits = $index->find('docid:' . $removePath);
        	foreach ($hits as $hit) {
        		$index->delete($hit->id);
        	}

        	//ok now re-add the doc to the index
        	//hook up the document parser
        	$document = new Zend_Search_Lucene_Document();
        	//change directory to the index path
        	chdir($indexPath);

        	//set the properties that we want to use in our index
        	//id for the index and optimization
        	$document->addField(Zend_Search_Lucene_Field::Text('docid', $data['id']));
        	//date
        	$document->addField(Zend_Search_Lucene_Field::UnIndexed('date', $data['created']));
        	//url
        	$document->addField(Zend_Search_Lucene_Field::UnIndexed('url', $this->uri(array('module' => 'cms', 'action' => 'showsection', 'id' => $data['id'], 'sectionid'=> $data['sectionid']))));
        	//createdBy
        	$document->addField(Zend_Search_Lucene_Field::Text('createdBy', $this->objUser->fullName($this->objUser->userId())));
        	//document teaser
        	$document->addField(Zend_Search_Lucene_Field::Text('teaser', $data['description']));
        	//doc title
        	$document->addField(Zend_Search_Lucene_Field::Text('title', $data['title']));
        	//doc author
        	$document->addField(Zend_Search_Lucene_Field::Text('author', $this->objUser->fullName($this->objUser->userId())));
        	//document body
        	//NOTE: this is not actually put into the index, so as to keep the index nice and small
        	//      only a reference is inserted to the index.
        	$document->addField(Zend_Search_Lucene_Field::Text('contents', $data['body']));
        	//what else do we need here???
        	//add the document to the index
        	$index->addDocument($document);
        	//commit the index to disc
        	$index->commit();
        	//optimize the thing
        	//$index->optimize();

        }
        
        /**
         * Method to get the section id from the 
         * contextcode
         * @param string $contextCode The Context Code
         * @return string
         * @access public
         * @author Wesley Nitsckie
         * 
         */
        public function getSectionByContextCode()
        {
            $objDBContext = $this->getObject('dbcontext', 'context');
            $contextCode = $objDBContext->getContextCode();
            //return $this->getAll("WHERE contextCode='".$contextCode."' AND rootid=0" );
            $ret =  $this->getRow("contextcode", $contextCode);
            
            if($ret == FALSE)
            {
                //create an entry
                //die('no section');
                $this->addNewSection(0,
                                $objDBContext->getTitle(),
                                $objDBContext->getMenuText(),
                                0,
                                $objDBContext->getAbout(),
                                1,
                                'page',
                                1,
                                1,
                                1,
                                'pageorder',
                                $contextCode);
                 return $this->getSectionByContextCode();
            } else {
                return $ret;
            }
        }
        
        


}
?>
