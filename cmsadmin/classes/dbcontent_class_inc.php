<?php

/* -------------------- dbTable class ----------------*/
// security check - must be included in all scripts

if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}

// end security check
/**
* Class to access the ContextCore Tables
* @package cms
* @category cmsadmin
* @copyright 2004, University of the Western Cape & AVOIR Project
* @license GNU GPL
* @version
* @author Wesley  Nitsckie
* @author Warren Windvogel
* @example :
*/

class dbcontent extends dbTable
{

        /**
         * @var object $_objUser;
         */
        protected $_objUser;


        /**
         * @var object $_objFrontPage t
         * 
         * @access protected
         */
        protected $_objFrontPage;

        /**
         * @var object $_objLanguage
         * @access protected
         */
        protected $_objLanguage;

        /**
         * @var object $_objFrontPage t
         * 
         * @access protected
         */
        protected $_objBlocks;

        /**
        * Constructor
        */
        public function init()
        {
            parent::init('tbl_cms_content');
            $this->_objUser = & $this->getObject('user', 'security');
            $this->_objFrontPage = & $this->newObject('dbcontentfrontpage', 'cmsadmin');
            $this->_objLanguage = & $this->newObject('language', 'language');
            $this->_objBlocks = & $this->newObject('dbblocks', 'cmsadmin');
        }

        /**
         * Method to save a record to the database
         *
         * @access public
         * @return bool
         */

        public function add()
        {
                $title = $this->getParam('title');
                $sectionid = $this->getParam('parent');
                $published = ($this->getParam('published') == 'on') ? 1 : 0;
                $creatorid = $this->_objUser->userId();
                $access = $this->getParam('access');
                $introText = $this->getParam('intro');
                $fullText = $this->getParam('body');

                $newArr = array(
                              'title' => $title ,
                              'sectionid' => $sectionid,
                              'introtext' => $introText,
                              'body' => $fullText,
                              'access' => $access,
                              'ordering' => $this->getOrdering($sectionid),
                              'published' => $published,
                              'created' => $this->now(),
                              'modified' => $this->now(),
                              'created_by' => $creatorid
                          );

                $newId = $this->insert($newArr);

                //process the forntpage
                $isFrontPage = $this->getParam('frontpage');

                if ($isFrontPage == 'on') {

                    $this->_objFrontPage->add
                    ($newId);
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
                $published = ($this->getParam('published') == 'on') ? '1' : '0';
                $creatorid = $this->_objUser->userId();
                $access = $this->getParam('access');
                $introText = $this->getParam('intro');
                $fullText = $this->getParam('body');
                $ordering = $this->getParam('ordering');

                $newArr = array(
                              'title' => $title ,
                              'sectionid' => $sectionid,
                              'access' => $access,
                              'introtext' => $introText,
                              'body' => $fullText,
                              'modified' => $this->now(),
                              'ordering' => $ordering,
                              'published' => $published,
                              'created_by' => $creatorid
                          );

                //process the forntpage
                $isFrontPage = $this->getParam('frontpage');

                if ($isFrontPage == 'on') {

                    $this->_objFrontPage->add
                    ($id);
                } else {

                    $this->_objFrontPage->remove
                    ($id);
                }


                return $this->update('id', $id, $newArr);
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
            return $this->update('id', $id, array('trash' => 1));
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
            return $this->update('id', $id, array('trash' => 0));
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
                $allPagesInSection = $this->getPagesInSection($sectionId);
                foreach($allPagesInSection as $pg) {
                    if ($pg['ordering'] > $pageOrderNo) {
                        $newOrder = $pg['ordering'] - '1';
                        $this->update('id', $pg['id'], array('title' => $pg['title'],
                                                             'sectionid' => $pg['sectionid'],
                                                             'introtext' => $pg['introtext'],
                                                             'body' => $pg['body'],
                                                             'access' => $pg['access'],
                                                             'ordering' => $newOrder,
                                                             'published' => $pg['published'],
                                                             'created' => $pg['created'],
                                                             'modified' => $this->now(),
                                                             'created_by' => $pg['created_by']
                                                            ));
                    }
                }
                //First remove from front page
                if ($this->_objFrontPage->isFrontPage($id)) {
                    $fpEntry = $this->_objFrontPage->getRow('content_id', $id);
                    $fpEntryId = $fpEntry['id'];

                    $this->_objFrontPage->remove($fpEntryId);
                }
                //Remove blocks for the page
                $pageBlocks = $this->_objBlocks->getBlocksForPage($id);
                if(!empty($pageBlocks)){
                   foreach($pageBlocks as $pb){
                      $this->_objBlocks->deleteBlock($pb['pageid'], $pb['blockid']);
                   }
                }
                //Delete page
                return $this->delete('id', $id);
        }

        /**
         * Method to get the content
         *
         * @return  array
         * @access public
         * @param string filter The Filter 
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
         * Method to get a page content record
         *
         * @param string $id The id of the page content
         * @access public
         * @return array
         */
        public function getContentPage($id)
        {

            return $this->getRow('id', $id );
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
                    return $this->update('id', $id , array('published' => 0) );
                } else {
                    return $this->update('id', $id , array('published' => 1) );
                }
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
                $arrContent = $this->getAll('WHERE sectionid = \''.$sectionId.'\'');
                $bln = TRUE;
                foreach ($arrContent as $page) {
                    $this->delete('id', $page['id']);
                }
                return $bln;
        }

        /**
         * Method to get all pages in a specific section
         * 
         * @param string $sectionId The id of the section 
         * @return array $pages An array of all pages in the section
         * @access public
         * @author Warren Windvogel
         */
        public function getPagesInSection($sectionId)
        {
                $pages = $this->getAll('WHERE sectionid = \''.$sectionId.'\' ORDER BY ordering');
                return $pages;
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
                $pages = $this->getAll('WHERE sectionid = \''.$sectionId.'\' ORDER BY ordering');
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
                $lastOrder = $this->getAll('WHERE sectionid = \''.$sectionId.'\' ORDER BY ordering DESC LIMIT 1');
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
                $lastOrd = $this->getAll('WHERE sectionid = \''.$sectionid.'\' ORDER BY ordering DESC LIMIT 1');
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
                $fpContent = $this->getAll('WHERE sectionid = \''.$sectionid.'\' ORDER BY ordering');
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
                $entries = $this->getAll('WHERE sectionid = \''.$sectionid.'\' AND ordering = \''.$toChange.'\'');
                foreach($entries as $entry) {
                    if ($entry['id'] != $id) {
                        $upArr = array(
                                     'ordering' => $changeTo,
                                     'modified' => $this->now()
                                 );
                        return $this->update('id', $entry['id'], $upArr);
                    }
                }
        }
}

?>
