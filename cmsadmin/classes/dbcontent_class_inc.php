<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
// end security check

/**
* Data access class for the cmsadmin module. Used to access data in the content table.
*
* @package cmsadmin
* @category chisimba
* @copyright AVOIR
* @license GNU GPL
* @author Wesley Nitsckie
* @author Warren Windvogel
*/

class dbcontent extends dbTable
{

        /**
        * The user object
        *
        * @access private
        * @var object
        */
        protected $_objUser;


        /**
        * The dbfrontpage object
        *
        * @access private
        * @var object
        */
        protected $_objFrontPage;

        /**
        * The language object
        *
        * @access private
        * @var object
        */
        protected $_objLanguage;

        /**
        * The blocks object
        *
        * @access private
        * @var object
        */
        protected $_objBlocks;

	   /**
	    * Class Constructor
	    *
	    * @access public
	    * @return void
	    */
        public function init()
        {
        	try {
                parent::init('tbl_cms_content');
                $this->table = 'tbl_cms_content';
                $this->_objUser = & $this->getObject('user', 'security');
                $this->_objFrontPage = & $this->newObject('dbcontentfrontpage', 'cmsadmin');
                $this->_objLanguage = & $this->newObject('language', 'language');
                $this->_objBlocks = & $this->newObject('dbblocks', 'cmsadmin');
           } catch (Exception $e){
       		    throw customException($e->getMessage());
        	    exit();
     	   }
        }

        /**
         * Method to save a record to the database
         *
         * @access public
         * @return bool
         */
        public function add()
        {
            //Create htmlcleaner object
            $objHtmlCleaner = $this->newObject('htmlcleaner', 'utilities');
            
            //Get details of the new entry
            $title = $this->getParam('title');
            $sectionid = $this->getParam('parent');
            $published = ($this->getParam('published') == '1') ? 1 : 0;
            $override_date = $this->getParam('overide_date',null);
            $start_publish = $this->getParam('publish_date',null);
            if($published == 1){
                $start_publish = $this->now();
            }
            $end_publish = $this->getParam('end_date',null);
            if ($override_date==null) {
            	$override_date =  $this->now();
            }
            $creatorid = $this->getParam('creator',null);
            if ($creatorid==NUll) {
            	 $creatorid = $this->_objUser->userId();
            }
            $hide_title = $this->getParam('hide_title','0');
           
            $access = $this->getParam('access');
			$created_by = $this->getParam('title_alias',null);
			$introText = str_ireplace("<br />", " <br /> ", $this->getParam('intro'));
            $fullText = str_ireplace("<br />", " <br /> ", $this->getParam('body'));
            $metakey = $this->getParam('keyword',null);
			$metadesc = $this->getParam('description',null);
            $ccLicence = $this->getParam('creativecommons',null);

            $newArr = array(
                          'title' => $title ,
                          'sectionid' => $sectionid,
                          'introtext' => addslashes($introText),
                          'body' => addslashes($fullText),
                          'access' => $access,
                          'ordering' => $this->getOrdering($sectionid),
                          'published' => $published,
                          'hide_title' => $hide_title,
                          'created' => $this->now(),
                          'modified' => $this->now(),
                          'post_lic' => $ccLicence,
                          'created' =>$override_date,
                          'created_by' => $creatorid,
                          'created_by_alias'=>$created_by,
                          'checked_out'=> $creatorid,
                          'checked_out_time'=> $this->now(),
                          'metakey'=>$metakey,
                          'metadesc'=>$metadesc,
                          'start_publish'=>$start_publish,
                          'end_publish'=>$end_publish
                          
                      );

            $newId = $this->insert($newArr);
            $newArr['id'] = $newId;
			$this->luceneIndex($newArr);
            //process the forntpage
            $isFrontPage = $this->getParam('frontpage');

            if ($isFrontPage == 1) {
                $this->_objFrontPage->add($newId);
            }

            return $newId;
        }

        /**
         * Method to save a record to the database specifying all params
         *
         * @param string $title The title of the page
         * @param string $sectionid The id of the section in which the content will appear
         * @param bool $published Whether page will be visible or not
         * @param bool $access True if "registered" page False if "public" page
         * @param string $introText The introduction content
         * @param string $fullText The main content of the page
         * @param string $ccLicence The cc licence of the content
         * @param bool $isFrontPage Whether page will appear on the front page or not
         * @access public
         * @return bool
         */
        public function addNewPage($title, $sectionid, $published, $access, $introText, $fullText, $isFrontPage, $ccLicence)
        {
            $introText = str_ireplace("<br />", " <br /> ", $introText);
            $fullText = str_ireplace("<br />", " <br /> ", $fullText);
			$override_date = $this->getParam('overide_date',null);
            $start_publish = $this->getParam('publish_date',null);
            $end_publish = $this->getParam('end_date',null);
            if ($override_date!=null) {
            	$override_date =  $this->now();
            }
            $creatorid = $this->getParam('creator',null);
            if ($creatorid==NUll) {
            	 $creatorid = $this->_objUser->userId();
            }

          	$access = $this->getParam('access');
			$created_by = $this->getParam('author_alias',null);
			
            $introText = str_ireplace("<br />", " <br /> ", $this->getParam('intro'));
            $fullText = str_ireplace("<br />", " <br /> ", $this->getParam('body'));
            $metakey = $this->getParam('keyword',null);
			$metadesc = $this->getParam('description',null);
            $ccLicence = $this->getParam('creativecommons');
            $hide_title = $this->getParam('hide_title','0');

            $newArr = array(
                          'title' => $title ,
                          'sectionid' => $sectionid,
                          'introtext' => addslashes($introText),
                          'body' => addslashes($fullText),
                          'access' => $access,
                          'ordering' => $this->getOrdering($sectionid),
                          'published' => $published,
                          'hide_title' => $hide_title,
                          'created' =>  $this->now(),
                          'modified' => $this->now(),
                          'post_lic' => $ccLicence,
                          'created_by' => $creatorid,
                          'created_by_alias'=>$created_by,
                          'checked_out'=> $creatorid,
                          'checked_out_time'=> $this->now(),
                          'metakey'=>$metakey,
                          'metadesc'=>$metadesc,
                          'start_publish'=>$start_publish,
                          'end_publish'=>$$end_publish
                      );

            $newId = $this->insert($newArr);
            $newArr['id'] = $newId;
			$this->luceneIndex($newArr);
            if ($isFrontPage == 'on') {
                $this->_objFrontPage->add($newId);
            }

            return $newId;
        }

        /**
         * Method to edit a record
         *
         * @access public
         * @return bool
         */
        public function edit()
        {
            $id = $this->getParam('id');
            $title = $this->getParam('title');
            $sectionid = $this->getParam('parent');
            $published = ($this->getParam('published') == '1') ? 1 : 0;
            $access = $this->getParam('access');
            $introText = str_ireplace("<br />", " <br /> ", $this->getParam('intro'));
            $fullText = str_ireplace("<br />", " <br /> ", $this->getParam('body'));
            $override_date = $this->getParam('overide_date',null);
            $start_publish = $this->getParam('publish_date',null);
            if($published == 1 && empty($start_publish)){
                $start_publish = $this->now();
            }
            $end_publish = $this->getParam('end_date',null);
            if ($override_date!=null) {
            	$override_date =  $this->now();
            }
            
          	$access = $this->getParam('access');
          	$modifiedBy = $this->_objUser->userId();
          	$modifiedDate = $this->now();
			
            $introText = str_ireplace("<br />", " <br /> ", $this->getParam('intro'));
            $fullText = str_ireplace("<br />", " <br /> ", $this->getParam('body'));
            $metakey = $this->getParam('keyword',null);
			$metadesc = $this->getParam('description',null);
            $ccLicence = $this->getParam('creativecommons');
            $hide_title = $this->getParam('hide_title','0');

            $newArr = array(
                          'title' => $title ,
                          'sectionid' => $sectionid,
                          'access' => $access,
                          'introtext' => addslashes($introText),
                          'body' => addslashes($fullText),
                          'modified' => $modifiedDate,
                          'modified_by' => $modifiedBy,
                          'published' => $published,
                          'hide_title' => $hide_title,
                          'post_lic' => $ccLicence,
                          'checked_out'=> $modifiedBy,
                          'checked_out_time'=> $this->now(),
                          'metakey'=>$metakey,
                          'metadesc'=>$metadesc,
                          'start_publish'=>$start_publish,
                          'end_publish'=>$end_publish
                      );

            
            $creatorid = $this->getParam('creator',null);
            if(!empty($creatorid)){
                $newArr['created_by'] = $creatorid;
            }
            
            //process the forntpage
            $isFrontPage = $this->getParam('frontpage');
			//$this->luceneReIndex($newArr);
            if ($isFrontPage == '1') {
                $this->_objFrontPage->add($id);
            } else {
                $this->_objFrontPage->removeIfExists($id);
            }

            return $this->update('id', $id, $newArr);
            $this->luceneReIndex($newArr);
        }

        /**
         * Method move a record to trash
         *
         * @param string $id The id of the record that needs to be deleted
         * @access public
         * @return bool
         */
        public function trashContent($id)
        {  
            //First remove from front page
            $this->_objFrontPage->removeIfExists($id);
            
            $fields = array('trash' => 1, 'ordering' => '', 'end_publish' => $this->now());
            $result =  $this->update('id', $id, $fields);
            
            // Get the section id of the page - re order pages
            $pageData = $this->getContentPage($id);
            $sectionId = $pageData['sectionid'];
            $this->reorderContent($sectionId);
            
            return $result;
        }

        /**
        * Method to reorder the content in a section 
        * After a page is trashed, etc
        *
        * @author Megan Watson
        * @param string $sectionId The id of the section containing the content
        * @access private
        * @return bool
        */
        private function reorderContent($sectionId)
        {   
            // Get all pages in the section
            $sectionData = $this->getPagesInSection($sectionId, FALSE);
            
            if(!empty($sectionData)){
                // Reorder the pages
                $i = 1;
                foreach($sectionData as $key => $item){
                    if($item['trash'] == 0){
                        $this->update('id', $item['id'], array('ordering' => $i));
                        $sectionData[$key]['ordering'] = $i++;
                    }
                }
                
                // Get the ordering position of the last section
                $newData = array_reverse($sectionData);
                $lastOrder = $newData[0]['ordering']+1;
                
                // Remove all null and negative numbers
                foreach($sectionData as $key => $item){
                    if(($item['ordering'] < 0 || is_null($item['ordering'])) && $item['trash'] == 0){
                        $this->update('id', $item['id'], array('ordering' => $lastOrder));
                        $sectionData[$key]['ordering'] = $lastOrder++;
                    }
                }
            }
        }

        /**
         * Method to undelete content
         *
         * @param string $id The id of the record that needs to be deleted
         * @access public
         * @return bool
         */
        public function undelete($id)
        {
            $page = $this->getRow('id', $id);
            $order = $this->getOrdering($page['sectionid']);
            $fields = array('trash' => 0, 'ordering' => $order);
            return $this->update('id', $id, $fields);
        }

        /**
        * Method to delete a content page
        *
        * @param string $id The id of the entry
        * @return boolean
        * @access public
        */
        public function deleteContent($id)
        {
            //Re-order other pages in section accordingly
            $page = $this->getRow('id', $id);
            $pageOrderNo = $page['ordering'];
            $sectionId = $page['sectionid'];
            
            //First remove from front page
            $this->_objFrontPage->removeIfExists($id);
            
            //Remove blocks for the page
            $pageBlocks = $this->_objBlocks->getBlocksForPage($id);
            if(!empty($pageBlocks)) {
                foreach($pageBlocks as $pb) {
                    $this->_objBlocks->deleteBlockById($pb['cb_id']);
                }
            }
            
            //Delete page
            $result = $this->delete('id', $id);
            
            // Reorder the content
            $this->reorderContent($sectionId);
            return $result;
        }

        /**
         * Method to get the content
         *
         * @param string $filter The Filter
         * @return  array An array of associative arrays of all content pages in relationto filter specified
         * @access public
         */
        public function getContentPages($filter = '')
        {
            if ($filter == 'trash') {
                $filter = ' WHERE trash=1 ';
            } else {
                $filter = ' WHERE trash=0 ';
            }

            return $this->getAll($filter.' ORDER BY ordering');
        }

        /**
         * Method to get the archived content
         *
         * @author Megan Watson
         * @param string $filter The Filter
         * @return  array An array of associative arrays of all content pages in relationto filter specified
         * @access public
         */
        public function getArchivePages($filter = '')
        {
            $sql = "SELECT * FROM {$this->table} WHERE trash = 1 ";
            
            if(!empty($filter)){
                $sql .= "AND LOWER(title) LIKE '%".strtolower($filter)."%' ";
            }
            
            $sql .= 'ORDER BY ordering';
            return $this->getArray($sql);
        }

        /**
         * Method to get a page content record
         *
         * @param string $id The id of the page content
         * @access public
         * @return array $content An associative array of content page details
         */
        public function getContentPage($id)
        {
           	$content = $this->getRow('id', $id );
           	return $content;
        }

        /**
         * Method to toggle the publish field
         *
         * @param string id The id if the content
         * @access public
         * @return boolean
         * @author Wesley Nitsckie
         */
        public function togglePublish($id)
        {
            $row = $this->getContentPage($id);

            if ($row['published'] == 1) {
                return $this->update('id', $id , array('published' => 0, 'end_publish' => $this->now(), 'start_publish' => '') );
            } else {
                return $this->update('id', $id , array('published' => 1, 'start_publish' => $this->now()) );
            }
        }
        
        /**
         * Method to publish or unpublish content 
         * 
         * @param string id The id if the content
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
                    $fields['start_publish'] = $this->now();
                    $fields['end_publish'] = '';
                    break;
                case 'unpublish':
                    $fields['published'] = 0;
                    $fields['end_publish'] = $this->now();
                    break;
            }
            
            return $this->update('id', $id, $fields);
        }


        /**
        * Method to update all the content with the
        * sections that will be deleted
        *
        * @param string $sectionId The section Id
        * @return boolean
        * @access public
        */
        public function resetSection($sectionId)
        {   
            $arrContent = $this->getAll("WHERE sectionid = '$sectionId'");
            $result = '';
            
            if(!empty($arrContent)){
                foreach ($arrContent as $page) {
                    //First remove from front page
                    $this->_objFrontPage->removeIfExists($page['id']);
                    
                    // Trash / archive
                    $fields = array('trash' => 1, 'ordering' => '');
                    $result =  $this->update('id', $page['id'], $fields);
                }
            }
            return $result;
        }
        
        /**
        * Method to update all the content with the
        * sections that will be deleted
        *
        * @param string $sectionId The section Id
        * @return boolean
        * @access public
        */
        public function unarchiveSection($sectionId)
        {   
            $arrContent = $this->getAll("WHERE sectionid = '$sectionId'");
            $result = '';
            
            if(!empty($arrContent)){
                $order = 1;
                foreach ($arrContent as $page) {
                    // Restore
                    $fields = array('trash' => 0, 'ordering' => $order++);
                    $result =  $this->update('id', $page['id'], $fields);
                }
            }
            return $result;
        }

        /**
         * Method to get all pages in a specific section
         *
         * @param string $sectionId The id of the section
         * @return array $pages An array of all pages in the section
         * @access public
         * @author Warren Windvogel
         */
        public function getPagesInSection($sectionId, $isPublished=FALSE)
        {
            $filter = "WHERE sectionid = '$sectionId' AND trash='0' ";
            if($isPublished){
                $filter .= "AND published='1' ";
            }
            $pages = $this->getAll($filter.' ORDER BY ordering');
            return $pages;
        }

        /**
         * Method to get all pages in a specific section, including those on the front page
         *
         * @access public
         * @author Megan Watson
         * @param string $sectionId The id of the section
         * @return array $data An array of all pages in the section
         */
        public function getPagesInSectionJoinFront($sectionId)
        {
            $sql = "SELECT *, fr.id AS front_id, co.id AS page_id, co.ordering AS co_order 
                FROM tbl_cms_content AS co 
                LEFT JOIN tbl_cms_content_frontpage AS fr ON (fr.content_id = co.id)
                WHERE sectionid = '$sectionId' AND trash='0'
                ORDER BY co.ordering";
                
            $data = $this->getArray($sql);
            return $data;
        }

        /**
         * Method to get the title and id of all pages in a specific section
         *
         * @param string $title The title of the section. Returns pages from all sections if NULL. Defaults to NULL
         * @param int $limit The amount of records to return. Returns all pages if NULL. Defaults to NULL
         * @return array $titles An array of associative arrays containing the id and title of all pages in the section
         * @access public
         * @author Warren Windvogel
         */
        public function getTitles($title = NULL, $limit = NULL)
        {
            //If only the section id is set, return all records in the section
            if($title == NULL && $limit != NULL){
                $sql = "SELECT id, title FROM tbl_cms_content WHERE trash = '0' ORDER BY created DESC LIMIT '$limit'";
            //If only the limit is set, return set amount of pages from all sections
            } else if($title != NULL && $limit == NULL){
                $sql = "SELECT id, title FROM tbl_cms_content WHERE title = '$title' ORDER BY created DESC";
            //If both params are set, return set amount of pages from specified section
            } else if($title != NULL && $limit != NULL){
                $sql = "SELECT id, title FROM tbl_cms_content WHERE title = '$title' ORDER BY created DESC LIMIT '$limit'";
            //Else if neither param is set, return all records
            } else {
                $sql = "SELECT id, title FROM tbl_cms_content WHERE trash = '0' ORDER BY created DESC";
            }
            $titles = $this->getArray($sql);
            return $titles;
        }
        
        /**
         * Method to get the title and id of the last 5 pages added
         *
         * @return array $lastFiveTitles An array of associative arrays containing the id and title of 
         * the last $n pages added
         * @param int $n The number of pages whose titles we should get
         * @access public
         * @author Warren Windvogel / added by Derek Keats 2007 01 17
         */
        public function getLatestTitles($n=5)
        {
            $sql = "SELECT id, title FROM tbl_cms_content WHERE trash = '0' ORDER BY created DESC LIMIT $n";
            return $this->getArray($sql);
        }

        /**
         * Method to get the number of pages in a specific section
         *
         * @param string $sectionId The id of the section
         * @return int $noPages The number of pages in the section
         * @access public
         * @author Warren Windvogel
         */
        public function getNumberOfPagesInSection($sectionId)
        {
            $noPages = '0';
            $pages = $this->getAll("WHERE sectionid = '$sectionId' AND trash='0' ORDER BY ordering");
            $noPages = count($pages);
            return $noPages;
        }

        /**
         * Method to return the ordering value of new content (gets added last)
         *
         * @param string $sectionId The id(pk) of the section the content is attached to
         * @return int $ordering The value to insert into the ordering field
         * @access public
         * @author Warren Windvogel
          */
        public function getPageOrder($pageId)
        {
            //get last order value
            $lastOrder = $this->getRow('id', $pageId);
            //add after this value
            $ordering = $lastOrder['ordering'];
            return $ordering;
        }

        /**
         * Method to return the ordering value of new content (gets added last)
         *
         * @param string $sectionId The id(pk) of the section the content is attached to
         * @return int $ordering The value to insert into the ordering field
         * @access public
         * @author Warren Windvogel
          */
        public function getOrdering($sectionId)
        {
            $ordering = 1;
            //get last order value
            $lastOrder = $this->getAll("WHERE sectionid = '$sectionId' AND trash = '0' ORDER BY ordering DESC LIMIT 1");
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
         * @author Warren Windvogel
         */
        public function getOrderingLink($sectionid, $id)
        {   
            //Get the number of pages in the section
            $lastOrd = $this->getAll("WHERE sectionid = '$sectionid' AND trash = '0' ORDER BY ordering DESC LIMIT 1");
            $topOrder = $lastOrd['0']['ordering'];
            $links = " ";

            if ($topOrder > '1') {
                //Get the order position
                $entry = $this->getRow('id', $id);
                //Create geticon obj
                $this->objIcon = & $this->newObject('geticon', 'htmlelements');

                if ($entry['ordering'] == '1') {
                    //return down arrow link
                    //icon
                    $this->objIcon->setIcon('downend');
                    $this->objIcon->title = $this->_objLanguage->languageText('mod_cmsadmin_changeorderdown', 'cmsadmin');
                    //link
                    $downLink = & $this->newObject('link', 'htmlelements');
                    $downLink->href = $this->uri(array('action' => 'changecontentorder', 'id' => $id, 'ordering' => 'up', 'sectionid' => $sectionid));
                    $downLink->link = $this->objIcon->show();
                    $links .= $downLink->show();
                } else if ($entry['ordering'] == $topOrder) {
                    //return up arrow
                    //icon
                    $this->objIcon->setIcon('upend');
                    $this->objIcon->title = $this->_objLanguage->languageText('mod_cmsadmin_changeorderup', 'cmsadmin');
                    //link
                    $upLink = & $this->newObject('link', 'htmlelements');
                    $upLink->href = $this->uri(array('action' => 'changecontentorder', 'id' => $id, 'ordering' => 'down', 'sectionid' => $sectionid));
                    $upLink->link = $this->objIcon->show();
                    $links .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $upLink->show();
                } else {
                    //return both arrows
                    //icon
                    $this->objIcon->setIcon('down');
                    $this->objIcon->title = $this->_objLanguage->languageText('mod_cmsadmin_changeorderdown', 'cmsadmin');
                    //link
                    $downLink = & $this->newObject('link', 'htmlelements');
                    $downLink->href = $this->uri(array('action' => 'changecontentorder', 'id' => $id, 'ordering' => 'up', 'sectionid' => $sectionid));
                    $downLink->link = $this->objIcon->show();
                    //icon
                    $this->objIcon->setIcon('up');
                    $this->objIcon->title = $this->_objLanguage->languageText('mod_cmsadmin_changeorderup', 'cmsadmin');
                    //link
                    $upLink = & $this->newObject('link', 'htmlelements');
                    $upLink->href = $this->uri(array('action' => 'changecontentorder', 'id' => $id, 'ordering' => 'down', 'sectionid' => $sectionid));
                    $upLink->link = $this->objIcon->show();
                    $links .= $downLink->show() . '&nbsp;' . $upLink->show();
                }
            }

            return $links;
        }

        /**
         * Method to update the order of the frontpage
         *
         * @param string $id The id of the entry
         * @param string $id The id of the entry to move
         * @param int $ordering How to update the order(up or down).
         * @access public
         * @return bool
         * @author Warren Windvogel
         */
        public function changeOrder($sectionid, $id, $ordering)
        {
            //Get array of all page entries
            $fpContent = $this->getAll("WHERE sectionid = '$sectionid' AND trash = '0' ORDER BY ordering");
            //Search for entry to be reordered and update order
            foreach($fpContent as $content) {
                if ($content['id'] == $id) {
                    if ($ordering == 'up') {
                        $changeTo = $content['ordering'];
                        $toChange = $content['ordering'] + 1;
                        $updateArray = array(
                                           'modified' => $this->now(),
                                           'ordering' => $toChange
                                       );
                        $this->update('id', $id, $updateArray);
                    } else {
                        $changeTo = $content['ordering'];
                        $toChange = $content['ordering'] - 1;
                        $updateArray = array(
                                           'ordering' => $toChange,
                                           'modified' => $this->now()
                                       );
                        $this->update('id', $id, $updateArray);
                    }
                }
            }

            //Get other entry to change
            $entries = $this->getAll("WHERE sectionid = '$sectionid' AND ordering = '$toChange' AND trash = '0'");
            foreach($entries as $entry) {
                if ($entry['id'] != $id) {
                    $upArr = array(
                                 'ordering' => $changeTo,
                                 'modified' => $this->now()
                             );
                    $result = $this->update('id', $entry['id'], $upArr);
                }
            }
            
            // Reorder the content
            $this->reorderContent($sectionid);
            return $result;
        }

    /**
	 * Method to scrub grubby html
	 *
	 * @param string $document
	 * @return string
	 */
	public function html2txt($document, $scrub = TRUE)
	{
		if($scrub == TRUE)
		{
			$search = array('@<script[^>]*?>.*?</script>@si',  // Strip out javascript
            	   /*'@<[\/\!]*?[^<>]*?>@si',*/            // Strip out HTML tags
               	   /*'@<style[^>]*?>.*?</style>@siU',*/    // Strip style tags properly
               	   '@<![\s\S]*?--[ \t\n\r]*>@'        // Strip multi-line comments including CDATA
				   );

		}
		else {
			$search = array('@<script[^>]*?>.*?</script>@si',  // Strip out javascript
            	   '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
               	   /*'@<style[^>]*?>.*?</style>@siU',*/    // Strip style tags properly
               	   '@<![\s\S]*?--[ \t\n\r]*>@',        // Strip multi-line comments including CDATA
               	   '!(\n*(.+)\n*!x',                   //strip out newlines...
				   );
		}
		$text = preg_replace($search, '', $document);
		$text = str_replace("<br /><br />", '' ,$text);
		//$text = str_replace("<br />", '' ,$text);
		//$text = str_replace( '\n\n\n' , '\n' ,$text);
		$text = str_replace("<br />  <br />", "<br />", $text);
		$text = str_replace("<br\">","",$text);
		$text = str_replace("<br />", " <br /> ", $text);
		//$text = str_replace("<", " <", $text);
		//$text = str_replace(">", "> ", $text);
		$text = rtrim($text, "\n");
		return $text;
	}
	
	/**
	 * The method implements the lucene indexer
	 * The method accepts an array of data,
	 * generates a document to be indexed based on the
	 * url and content inserted into the database 
	 *
	 * @param array $data
	 */
	public function luceneIndex($data)
        {
        	$this->objConfig = $this->getObject('altconfig', 'config');
        	$this->objUser = $this->getObject('user', 'security');
        	$indexPath = $this->objConfig->getcontentBasePath();
        	
        	// check directory exists
        	if(!is_dir($indexPath.'chisimbaIndex')){
        	    // no then create it.
        	    mkdir($indexPath.'chisimbaIndex');
        	    chmod($indexPath.'chisimbaIndex', 0755);
        	}
        	if(file_exists($indexPath.'chisimbaIndex/segments')){
        		chmod($indexPath.'chisimbaIndex', 0755);
        		//we build onto the previous index
        		$index = new Zend_Search_Lucene($indexPath.'chisimbaIndex');
        	} else {
        		//instantiate the lucene engine and create a new index
        		chmod($indexPath.'chisimbaIndex', 0755);
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
        	$document->addField(Zend_Search_Lucene_Field::UnIndexed('date', $data['created']));
        	//url
        	$document->addField(Zend_Search_Lucene_Field::UnIndexed('url', htmlentities($this->uri(array
        															('module' => 'cms', 
        															'action' => 'showfulltext', 
        															'id' => $data['id'],
        															'sectionid'=> $data['sectionid'])))));
        	//createdBy
        	$document->addField(Zend_Search_Lucene_Field::Text('createdBy', $this->objUser->fullName($data['created_by'])));
        	//document teaser
        	$document->addField(Zend_Search_Lucene_Field::Text('teaser', $data['introtext']));
        	//doc title
        	$document->addField(Zend_Search_Lucene_Field::Text('title', $data['title']));
        	//doc author
        	$document->addField(Zend_Search_Lucene_Field::Text('author', $this->objUser->fullName($data['created_by'])));
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
        	$document->addField(Zend_Search_Lucene_Field::UnIndexed('url', htmlentities($this->uri(array('module' => 'cms', 'action' => 'showfulltext', 'id' => $data['id'], 'sectionid'=> $data['sectionid'])))));
        	//createdBy
        	$document->addField(Zend_Search_Lucene_Field::Text('createdBy', $this->objUser->fullName($this->objUser->userId())));
        	//document teaser
        	$document->addField(Zend_Search_Lucene_Field::Text('teaser', $data['introtext']));
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

}

?>