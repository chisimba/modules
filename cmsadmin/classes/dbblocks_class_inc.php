<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
* Data access class for the cmsadmin module. Used to access data in the blocks table. 
*
* @package cmsadmin
* @category chisimba
* @copyright AVOIR 
* @license GNU GPL
* @author Wesley Nitsckie
* @author Warren Windvogel
*/

class dbblocks extends dbTable
{

        /**
        * The user  object
        *
        * @access private
        * @var object
        */
        protected $_objUser;


        /**
        * The frontpage  object
        *
        * @access private
        * @var object
        */
        protected $_objFrontPage;

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
                parent::init('tbl_cms_blocks');
                $this->_objUser = & $this->getObject('user', 'security');
                $this->_objLanguage = & $this->newObject('language', 'language');
           } catch (Exception $e){
       		    echo 'Caught exception: ',  $e->getMessage();
        	    exit();
     	   }
        }

        /**
         * Method to save a record to the database
         *
         * @param string $pageId The id of the page
         * @param string $blockId The id of the block
         * @access public
         * @return bool
         */
        public function add($pageId, $blockId)
        {
            $ordering = $this->getOrdering($pageId);
            $newArr = array(
                          'pageid' => $pageId ,
                          'blockid' => $blockId,
                          'ordering' => $ordering
                      );
            $newId = $this->insert($newArr);

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
            //Get entry details
            $id = $this->getParam('id');
            $pageId = $this->getParam('pageid');
            $blockId = $this->getParam('blockid');
            $ordering = $this->getParam('ordering', 1);

            $newArr = array(
                          'pageid' => $pageId ,
                          'blockid' => $blockId,
                          'ordering' => $ordering
                      );
            //Update entry
            return $this->update('id', $id, $newArr);
        }

        /**
        * Method to delete a block
        *
        * @param string $pageId The id of the page
        * @param string $blockId The id of the block
        * @return boolean
        * @access public
        */
        public function deleteBlock($pageId, $blockId)
        {
            $block = $this->getAll('WHERE pageid = \''.$pageId.'\' AND blockid = \''.$blockId.'\'');
            if(!empty($block)) {
                $id = $block['0']['id'];
                $blockOrderNo = $block['0']['ordering'];
                $pageBlocks = $this->getBlocksForPage($pageId);
                if(count($pageBlocks) > 1) {
                    foreach($pageBlocks as $pb) {
                        if($pb['ordering'] > $blockOrderNo) {
                            $newOrder = $pb['ordering'] - '1';
                            $this->update('id', $pb['id'], array('pageid' => $pb['pageid'], 'blockid' => $pb['blockid'], 'ordering' => $newOrder));
                        }
                    }
                }
                return $this->delete('id', $id);
            }
        }

        /**
         * Method to get a page content record
         *
         * @param string $pageId The id of the page content
         * @access public
         * @return array $pageBlocks An array of associative arrays of all blocks on the page
         */
        public function getBlocksForPage($pageId)
        {
            $pageBlocks = $this->getAll('WHERE pageid = \''.$pageId.'\' ORDER BY ordering');
            return $pageBlocks;
        }

        /**
         * Method to return the ordering value of new blocks (gets added last)
         *
         * @param string $pageid The id(pk) of the page the block is attached to
         * @return int $ordering The value to insert into the ordering field
         * @access public
         * @author Warren Windvogel
          */
        public function getOrdering($pageid)
        {
            $ordering = 1;
            //get last order value
            $lastOrder = $this->getAll('WHERE pageid = \''.$pageid.'\' ORDER BY ordering DESC LIMIT 1');

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
         * @return string The html for the links
         * @author Warren Windvogel
         */
        public function getOrderingLink($id)
        {
            //Get the parent id
            $entry = $this->getRow('id', $id);
            $pageId = $entry['pageid'];

            //Get the number of sub sections in section
            $lastOrd = $this->getAll('WHERE pageid = \''.$pageId.'\' ORDER BY ordering DESC LIMIT 1');

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
                    $downLink->href = $this->uri(array('action' => 'changeblocksorder', 'id' => $id, 'ordering' => 'up', 'pageid' => $pageId));
                    $downLink->link = $this->objIcon->show();
                    $links .= $downLink->show();
                } else if ($entry['ordering'] == $topOrder) {
                    //return up arrow
                    //icon
                    $this->objIcon->setIcon('upend');
                    $this->objIcon->title = $this->_objLanguage->languageText('mod_cmsadmin_changeorderup', 'cmsadmin');
                    //link
                    $upLink = & $this->newObject('link', 'htmlelements');
                    $upLink->href = $this->uri(array('action' => 'changeblocksorder', 'id' => $id, 'ordering' => 'down', 'pageid' => $pageId));
                    $upLink->link = $this->objIcon->show();

                    $links .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $upLink->show();
                } else {
                    //return both arrows
                    //icon
                    $this->objIcon->setIcon('down');
                    $this->objIcon->title = $this->_objLanguage->languageText('mod_cmsadmin_changeorderdown', 'cmsadmin');
                    //link
                    $downLink = & $this->newObject('link', 'htmlelements');
                    $downLink->href = $this->uri(array('action' => 'changeblocksorder', 'id' => $id, 'ordering' => 'up', 'pageid' => $pageId));
                    $downLink->link = $this->objIcon->show();
                    //icon
                    $this->objIcon->setIcon('up');
                    $this->objIcon->title = $this->_objLanguage->languageText('mod_cmsadmin_changeorderup', 'cmsadmin');
                    //link
                    $upLink = & $this->newObject('link', 'htmlelements');
                    $upLink->href = $this->uri(array('action' => 'changeblocksorder', 'id' => $id, 'ordering' => 'down', 'pageid' => $pageId));
                    $upLink->link = $this->objIcon->show();
                    $links .= $downLink->show() . '&nbsp;' . $upLink->show();
                }
            }

            return $links;
        }

        /**
         * Method to update the order of the blocks
         * 
         * @param string $id The id of the entry to move
         * @param int $ordering How to update the order(up or down).
         * @access public
         * @return bool
         * @author Warren Windvogel
         */
        public function changeOrder($id, $ordering, $pageId)
        {
            //Get array of all blocks in level
            $fpContent = $this->getAll('WHERE pageid = \''.$pageId.'\' ORDER BY ordering');
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
            $entries = $this->getAll('WHERE pageid = \''.$pageId.'\' AND ordering = \''.$toChange.'\'');
            foreach($entries as $entry) {
                if ($entry['id'] != $id) {
                    $upArr = array(
                                 'ordering' => $changeTo
                             );
                    $this->update('id', $entry['id'], $upArr);
                }
            }
        }

        /**
         * Method to return the ordering value of new blocks (gets added last)
         *
         * @param string $pageid The id(pk) of the page the block is attached to
         * @return string $middleColumnContent The form to add remove blocks from a page
         * @access public
         * @author Warren Windvogel
          */
        public function getAddRemoveBlockForm($pageid)
        {
            //Load the checkbox class
            $this->loadClass('checkbox', 'htmlelements');

            //Create heading
            $objH =& $this->newObject('htmlheading', 'htmlelements');
            $objH->type = '3';
            $objH->str = $this->_objLanguage->languageText('mod_cmsadmin_blocksforcontent', 'cmsadmin');

            //Create the form
            $objForm =& $this->newObject('form', 'htmlelements');
            $objForm->name = 'addblockform';
            $objForm->id = 'addblockform';
            $objForm->setAction($this->uri(array('action' => 'saveblock', 'pageid' => $pageid), 'cmsadmin'));

            //Create table to store form elements
            $objTable =& $this->newObject('htmltable', 'htmlelements');
            $objTable->cellspacing = '2';
            $objTable->cellpadding = '2';
            $objTable->border = '1';
            $objTable->width = '70%';
            //Create header cell
            $objTable->startHeaderRow();
            $objTable->addHeaderCell($this->_objLanguage->languageText('phrase_blockname'));
            $objTable->addHeaderCell($this->_objLanguage->languageText('word_order'));
            $objTable->endHeaderRow();

            //Get current blocks on page
            $currentBlocks = $this->getBlocksForPage($pageid);

            if(!empty($currentBlocks)) {
                foreach($currentBlocks as $tbk) {
                    $tb = $this->getBlock($tbk['blockid']);
                    //Add entry to table for changing order
                    $objTable->startRow();
                    $objTable->addCell($tb['blockname']);
                    $objTable->addCell($this->getOrderingLink($tbk['id']));
                    $objTable->endRow();
                }
            }

            $boxes = "";

            //Get all entries in blocks table
            $blocks = $this->getBlockEntries();
            foreach($blocks as $block) {
                $blockName = $block['blockname'];
                $blockId = $block['id'];

                $checked = FALSE;
                if(!empty($currentBlocks)) {
                    foreach($currentBlocks as $blk) {
                        if($blk['blockid'] == $blockId) {
                            $checked = TRUE;
                        }
                    }
                }
                $checkbox = new checkbox($blockId, $blockName, $checked);

                $boxes .= $checkbox->show().'&nbsp;'.$blockName.'&nbsp;'.'&nbsp;';
            }

            //Create submit button
            $objButton =& $this->newObject('button', 'htmlelements');
            $objButton->setToSubmit();
            $objButton->value = $this->_objLanguage->languageText('word_save');
            $objButton->id = 'submit';
            $objButton->name = 'submit';

            $objForm->addToForm($boxes);
            $objForm->addToForm('<br/>'.'&nbsp;'.'<br/>'.$objButton->show());

            $middleColumnContent = "";
            $middleColumnContent .= $objH->show();
            $middleColumnContent .= $objTable->show();
            $middleColumnContent .= '<br/>'.'&nbsp;'.'<br/>';
            $objH->str = $this->_objLanguage->languageText('mod_cmsadmin_addremoveblocks', 'cmsadmin');
            $middleColumnContent .= $objH->show();
            $middleColumnContent .= $objForm->show();

            return $middleColumnContent;
        }

        /************************ tbl_module_block methods *************************/

        /**
         * Method to return all entries in blocks table
         *
         * @return array $entries An array of all entries in the module_blocks table
         * @access public
         */
        public function getBlockEntries()
        {
            $sql = 'SELECT * FROM tbl_module_blocks';
            $entries = $this->getArray($sql);

            return $entries;
        }
        
        /**
         * Method to return an entries in blocks table
         *
         * @param string $blockId The id of the block
         * @return array $entry An associative array of the blocks details
         * @access public
         */
        public function getBlock($blockId)
        {
            $entry = $this->getArray('SELECT * FROM tbl_module_blocks WHERE id = \''.$blockId.'\'');
            $entry = $entry['0'];

            return $entry;
        }
        
        /**
         * Method to return an entries in blocks table
         *
         * @param string $blockName The name of the block
         * @return array $entry An associative array of the blocks details
         * @access public
         */
        public function getBlockByName($blockName)
        {
            $entry = $this->getArray('SELECT * FROM tbl_module_blocks WHERE blockname = \''.$blockName.'\'');
            $entry = $entry['0'];

            return $entry;
        }
}

?>
