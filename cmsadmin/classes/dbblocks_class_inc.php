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

class dbblocks extends dbTable
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
        * Constructor
        */
        public function init()
        {
            parent::init('tbl_cms_blocks');
            $this->_objUser = & $this->getObject('user', 'security');
            $this->_objLanguage = & $this->newObject('language', 'language');
        }

        /**
         * MEthod to save a record to the database
         * @access public
         * @return bool
         */

        public function add()
        {
            try {
                $pageId = $this->getParam('pageid');
                $blockId = $this->getParam('blockid');
                $ordering = $this->getParam('ordering', 0);

                $newArr = array(
                              'pageid' => $pageId ,
                              'blockid' => $blockId,
                              'ordering' => $ordering
                          );

                $newId = $this->insert($newArr);

                return $newId;

            } catch (customException $e) {
                echo customException::cleanUp($e);
                die();
            }


        }

        /**
         * Method to edit a record
         * @access public
         * @return bool
         */
        public function edit()
        {

            try {
                $id = $this->getParam('id');
                $pageId = $this->getParam('pageid');
                $blockId = $this->getParam('blockid');
                $ordering = $this->getParam('ordering', 0);

                $newArr = array(
                              'pageid' => $pageId ,
                              'blockid' => $blockId,
                              'ordering' => $ordering
                          );


                return $this->update('id', $id, $newArr);

                //print 'Saving new record';
            } catch (customException $e) {
                echo customException::cleanUp($e);
                die();
            }


        }


        /**
        * Method to delete a block
        * @param string $id
        * @return boolean
        * @access public
        */
        public function deleteBlock($id)
        {
            try {
                return $this->delete('id', $id);
            } catch (Exception $e) {
                echo 'Caught exception: ', $e->getMessage();
                exit();
            }
        }


        /**
         * Method to get a page content record
         * @param string $id The id of the page content
         * @access public
         * @return array
         */
        public function getBlocksForPage($pageId)
        {

            return $this->getAll("WHERE id = '".$id."' ORDER BY ordering" );
        }


}

?>
