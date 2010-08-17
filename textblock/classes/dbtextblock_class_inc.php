<?php
/* ----------- data class extends dbTable for tbl_quotes------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }


/**
* Model class for the table tbl_textblock
*
* @author Charl Mert
*
* $Id: dbtextblock_class_inc.php,v 1.1 2006/09/14 08:19:14 dkeats
*
*/
class dbtextblock extends dbTable {

    /**
    * Constructor method to define the table
    */
    public function init() {
        parent::init('tbl_textblock');

		$this->objBlock = $this->getObject('dbblocksdata', 'blocks');
		$this->objUser = $this->getObject('user', 'security');
    }

    /**
    * Method to return the block row
    * @param string $blockId the blockId as per tbl_module_blocks.id.
    */
    public function getBlock($blockId) {   
		$blockArr = $this->objBlock->getBlock($blockId);;
		$txtBlockId = trim($blockArr['blockname']);
		return $this->getRow('blockid', $txtBlockId);
    }

    /**
    * Save method for editing a record in this table
    * @param string $mode: edit if coming from edit, add if coming from add
    */
    public function saveRecord($id, $mode, $blockid, $title, $blocktext, $cssId, $cssClass, $showTitle) {
		try {
            
            // if edit use update
            if ($mode=="edit") {
                $this->update("id", $id, array(
                'blockid' => $blockid,
                'title' => $title,
                'blocktext' => $blocktext,
                'datemodified' => $this->now(),
                'modified' => $this->now(),
                'modifierid' => $this->objUser->userId(),
                'css_id' => $cssId,
                'css_class' => $cssClass,
                'show_title' => $showTitle));

            }
            // if add use insert
            if ($mode=="add") {
                $this->insert(array(
                'blockid' => $blockid,
                'title' => $title,
                'blocktext' => $blocktext,
                'datecreated' => $this->now(),
                'creatorid' => $this->objUser->userId(),
                'modified' => $this->now(),
                'css_id' => $cssId,
                'css_class' => $cssClass,
                'show_title' => $showTitle));

            }
        } catch (customException $e) {
        	echo customException::cleanUp($e);
        	die();
        }
    }

}