<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
// end security check

/**
* Data access class for the cmsadmin module. Used to access data in the content front page table.
*
* @package cmsadmin
* @category chisimba
* @copyright AVOIR
* @license GNU GPL
* @author Wesley  Nitsckie
* @author Warren Windvogel
*/

class dbcontentfrontpage extends dbTable
{

        /**
        * The user  object
        *
        * @access private
        * @var object
        */
        protected $_objUser;

        /**
        * The language  object
        *
        * @access private
        * @var object
        */
        protected $_objLanguage;

	   /**
	    * Class Constructor
	    *
	    * @access public
	    * @return void
	    */
        public function init()
        {
        	try {
                parent::init('tbl_cms_content_frontpage');
                $this->_objUser = & $this->getObject('user', 'security');
                $this->_objLanguage =& $this->newObject('language', 'language');
           } catch (Exception $e){
       		    echo 'Caught exception: ',  $e->getMessage();
        	    exit();
     	   }
        }

        /**
         * Method to save a record to the database
         *
         * @param string $contentId The neContent Id
         * @param int $ordering The number of the page as it appears in the front page order
         * @access public
         * @return string New entry id if inserted else False
         */
        public function add($contentId, $ordering = 1)
        {
            //Check for duplicate
            if(!$this->valueExists('content_id',$contentId)) {
                //Insert entry
                return $this->insert(array(
                                         'content_id' => $contentId,
                                         'ordering' => $this->getOrdering()
                                     ));
            } else {
                $bln = FALSE;
                return $bln;
            }
        }

        /**
         * Method to remove a record
         *
         * @param string $id The content Id that must be removed
         * @access public
         * @return bool
         */
        public function remove($id)
        {
            $page = $this->getRow('content_id', $id);
            $pageOrderNo = $page['ordering'];
            $allPages = $this->getFrontPages();
            foreach($allPages as $pg) {
                if($pg['ordering'] > $pageOrderNo) {
                    $newOrder = $pg['ordering'] - '1';
                    $this->update('id', $pg['id'], array('content_id' => $pg['content_id'], 'ordering' => $newOrder));
                }
            }
            return $this->delete('content_id', $id);
        }

        /**
         * Method to get all the front page id's
         *
         * @return array $allFrontPages An array of oll entries in the content front page table
         * @access public
         */
        public function getFrontPages()
        {
            $allFrontPages = $this->getAll('ORDER BY ordering');
            return $allFrontPages;
        }


        /**
         * Method to check if a page is a front page
         *
         * @param string $id The id to be checked
         * @access public
         * @return bool
         */
        public function isFrontPage($id)
        {
            $isFrontPage = $this->valueExists('content_id',$id);
            return $isFrontPage;
        }

        /**
         * Method to change the status of a page
         *
         * @param string $pageId The id of the page to be changed
         * @access public
         * @return bool
         */
        public function changeStatus($pageId)
        {
            //If it is on the front page then remove it
            if($this->isFrontPage($pageId)) {
                return $this->remove($pageId);
            //If it is not on the front page then add it
            } else {
                return $this->add($pageId);
            }
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
        public function changeOrder($id, $ordering)
        {
            //Get array of all front page entries
            $fpContent = $this->getAll('ORDER BY ordering');
            //Search for entry to be reordered and update order
            foreach($fpContent as $content) {
                if($content['id'] == $id) {
                    if($ordering == 'up') {
                        $changeTo = $content['ordering'];
                        $toChange = $content['ordering'] + 1;
                        $updateArray = array(
                                           'content_id' => $content['content_id'],
                                           'ordering' => $toChange
                                       );
                        $this->update('id', $id, $updateArray);
                    } else {
                        $changeTo = $content['ordering'];
                        $toChange = $content['ordering'] - 1;
                        $updateArray = array(
                                           'content_id' => $content['content_id'],
                                           'ordering' => $toChange
                                       );
                        $this->update('id', $id, $updateArray);
                    }
                }
            }
            //Get other entry to change
            $entries = $this->getAll("WHERE ordering = '$toChange'");
            foreach($entries as $entry) {
                if($entry['id'] != $id) {
                    $upArr = array(
                                 'content_id' => $entry['content_id'],
                                 'ordering' => $changeTo
                             );
                    $this->update('id', $entry['id'], $upArr);
                }
            }
        }
        /**
         * Method to return the ordering value of new content (gets added last)
         *
         * @return int $ordering The value to insert into the ordering field
         * @access public
         * @author Warren Windvogel
          */
        public function getOrdering()
        {
            $ordering = 1;
            //get last order value
            $lastOrder = $this->getAll('ORDER BY ordering DESC LIMIT 1');
            if(!empty($lastOrder)) {
                //add after this value
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
        public function getOrderingLink($id)
        {
            //Get the number of pages on the front page
            $lastOrd = $this->getAll('ORDER BY ordering DESC LIMIT 1');
            $topOrder = $lastOrd['0']['ordering'];
            $links = " ";
            if($topOrder > '1') {
                //Get the order position
                $entry = $this->getRow('id', $id);
                //Create geticon obj
                $this->objIcon =& $this->newObject('geticon', 'htmlelements');
                if($entry['ordering'] == '1') {
                    //return down arrow link
                    //icon
                    $this->objIcon->setIcon('downend');
                    $this->objIcon->title = $this->_objLanguage->languageText('mod_cmsadmin_changeorderdown', 'cmsadmin');
                    //link
                    $downLink =& $this->newObject('link', 'htmlelements');
                    $downLink->href = $this->uri(array('action' => 'changefporder', 'id' => $id, 'ordering' => 'up'));
                    $downLink->link = $this->objIcon->show();
                    $links .= $downLink->show();
                } else if($entry['ordering'] == $topOrder) {
                    //return up arrow
                    //icon
                    $this->objIcon->setIcon('upend');
                    $this->objIcon->title = $this->_objLanguage->languageText('mod_cmsadmin_changeorderup', 'cmsadmin');
                    //link
                    $upLink =& $this->newObject('link', 'htmlelements');
                    $upLink->href = $this->uri(array('action' => 'changefporder', 'id' => $id, 'ordering' => 'down'));
                    $upLink->link = $this->objIcon->show();
                    $links .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $upLink->show();
                } else {
                    //return both arrows
                    //icon
                    $this->objIcon->setIcon('down');
                    $this->objIcon->title = $this->_objLanguage->languageText('mod_cmsadmin_changeorderdown', 'cmsadmin');
                    //link
                    $downLink =& $this->newObject('link', 'htmlelements');
                    $downLink->href = $this->uri(array('action' => 'changefporder', 'id' => $id, 'ordering' => 'up'));
                    $downLink->link = $this->objIcon->show();
                    //icon
                    $this->objIcon->setIcon('up');
                    $this->objIcon->title = $this->_objLanguage->languageText('mod_cmsadmin_changeorderup', 'cmsadmin');
                    //link
                    $upLink =& $this->newObject('link', 'htmlelements');
                    $upLink->href = $this->uri(array('action' => 'changefporder', 'id' => $id, 'ordering' => 'down'));
                    $upLink->link = $this->objIcon->show();
                    $links .= $downLink->show() . '&nbsp;' . $upLink->show();
                }
            }
            return $links;
        }
		
		public function hasFrontPageContent()
		{
			$sql = 'SELECT DISTINCT tbl_cms_content.sectionid FROM tbl_cms_content_frontpage, tbl_cms_content, tbl_cms_sections WHERE (tbl_cms_content_frontpage.content_id = tbl_cms_content.id) AND (tbl_cms_content.sectionid = tbl_cms_sections.id) AND (tbl_cms_content.published = 1) AND (tbl_cms_sections.published = 1)';
			
			$result = $this->getArray($sql);
			
			if (count($result) > 0) {
				return TRUE;
			} else {
				return FALSE;
			}
		}
}
?>