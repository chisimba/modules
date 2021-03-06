<?php

/* ----------- data class extends dbTable for tbl_contentblocks------------ */
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

/**
 * Model class for the table tbl_contentblocks
 *
 * @version    0.001
 * @package    contentblocks
 * @author     Derek Keats derek@dkeats.com
 * @copyright  2012 AVOIR
 *
 *
 */
class dbcontentblocks extends dbTable {

    /**
     * Constructor method to define the table
     */
    public function init() {
        parent::init('tbl_contentblocks');

        $this->objBlock = $this->getObject('dbblocksdata', 'blocks');
        $this->objUser = $this->getObject("user", "security");
        $this->userId = $this->objUser->userId();
    }

    /**
     * Method to return the block row
     * @param string $blockId the blockId as per tbl_module_blocks.id.
     */
    public function getBlock($blockId) {
        $blockArr = $this->objBlock->getBlock($blockId);
        ;
        $txtBlockId = trim($blockArr['blockname']);
        $result = $this->getAll(" WHERE blockid = '$txtBlockId'");
        return $result[0];
    }

    /**
     * Method to return the block row by id
     * @param string $id the id as per tbl_module_blocks.blockid.
     */
    public function getBlockById($id) {
        $result = $this->getAll(" WHERE id = '$id'");
        return $result;
    }

    /**
     *
     * Get an array blocks of a particular type
     *
     * @param string $blockType  Type of block (content_text, content_widetext)
     * @return array of used blocks
     * @access public
     *
     */
    public function getBlocksArr($blockType) {        
        $sql = "SELECT * from tbl_contentblocks WHERE blockid = '"
                . $blockType . "' and (deleted !=1 or deleted is null or deleted = 0)";
        $ar = $this->getArray($sql);
        if (!empty($ar)) {         
            return $ar;
        } else {
            return NULL;
        }
    }
    /**
     *
     * Get an array blocks of a particular type including deleted ones
     *
     * @param string $blockType  Type of block (content_text, content_widetext)
     * @return array of used blocks
     * @access public
     *
     */
    public function getAllBlocksArr($blockType) {
        $sql = "SELECT blockid from tbl_contentblocks WHERE blockid LIKE '"
                . $blockType . "%'";
        $ar = $this->getArray($sql);
        if (!empty($ar)) {
            foreach ($ar as $entry) {
                $newAr[] = $entry['blockid'];
            }
            return $newAr;
        } else {
            return NULL;
        }
    }

    /**
     * Save method for editing a record in this table
     * @param string $mode: edit if coming from edit, add if coming from add
     */
    public function deleteRecord($id) {
        try {
            $this->update("id", $id, array(
                'datemodified' => $this->now(),
                'modified' => $this->now(),
                'modifierid' => $this->userId,
                'deleted' => 1));
        } catch (customException $e) {
            echo customException::cleanUp($e);
            die();
        }
    }

    /**
     * Save method for editing a record in this table
     * @param string $mode: edit if coming from edit, add if coming from add
     */
    public function saveRecord($mode) {
        try {
            $id = $this->getParam('id', NULL);
            $blockid = $this->getParam('blockid', NULL);
            $title = $this->getParam('title', NULL);
            $blocktext = $this->getParam('blocktext', NULL);
            $showTitle = $this->getParam('show_title', '1');

            $showTitle = ($showTitle == 'on') ? '1' : '0';

            $cssId = $this->getParam('css_id', NULL);
            $cssClass = $this->getParam('css_class', NULL);
            // if edit use update
            if ($mode == "edit") {
                $this->update("id", $id, array(
                    'blockid' => $blockid,
                    'title' => $title,
                    'blocktext' => $blocktext,
                    'datemodified' => $this->now(),
                    'modified' => $this->now(),
                    'modifierid' => $this->userId,
                    'css_id' => $cssId,
                    'css_class' => $cssClass,
                    'show_title' => $showTitle));
            }//if
            // if add use insert
            if ($mode == "add") {
                $this->insert(array(
                    'blockid' => $blockid,
                    'title' => $title,
                    'blocktext' => $blocktext,
                    'datecreated' => $this->now(),
                    'creatorid' => $this->userId,
                    'modified' => $this->now(),
                    'css_id' => $cssId,
                    'css_class' => $cssClass,
                    'show_title' => $showTitle));
            }//if
        } catch (customException $e) {
            echo customException::cleanUp($e);
            die();
        }
    }

//function
}

//end of class
?>