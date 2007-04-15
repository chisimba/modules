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
* @author Derek Keats
*
* $Id: dbtextblock_class_inc.php,v 1.1 2006/09/14 08:19:14 dkeats
*
*/
class dbtextblock extends dbTable
{

    /**
    * Constructor method to define the table
    */
    public function init() {
        parent::init('tbl_textblock');
    }

    /**
    * Save method for editing a record in this table
    * @param string $mode: edit if coming from edit, add if coming from add
    */
    public function saveRecord($mode, $userId)
    {   try
        {
            $id=$this->getParam('id', NULL);
            $blockid = $this->getParam('blockid', NULL);
            $title = $this->getParam('title', NULL);
            $blocktext = $this->getParam('blocktext', NULL);
			$objUser = $this->getObject("user", "security");
            // if edit use update
            if ($mode=="edit") {
                $this->update("id", $id, array(
                'blockid' => $blockid,
                'title' => $title,
                'blocktext' => $blocktext,
                'datemodified' => $this->now(),
                'modified' => $this->now(),
                'modifierid' => $objUser->userId()));

            }//if
            // if add use insert
            if ($mode=="add") {
                $this->insert(array(
                'blockid' => $blockid,
                'title' => $title,
                'blocktext' => $blocktext,
                'datecreated' => $this->now(),
                'creatorid' => $objUser->userId(),
                'modified' => $this->now()));

            }//if
        } catch (customException $e)
        {
        	echo customException::cleanUp($e);
        	die();
        }
    }//function

} //end of class
?>
