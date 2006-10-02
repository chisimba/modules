<?php
/* ----------- data class extends dbTable for tbl_quotes------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }


/**
* Model class for the table tbl_quotes
*/
class dbquotes extends dbTable
{

    /**
    * Constructor method to define the table
    */
    public function init() {
        parent::init('tbl_quotes');
        $this->objUser = & $this->getObject("user", "security");
    }

    /**
    * Save method for editing a record in this table
    *@param string $mode: edit if coming from edit, add if coming from add
    */
    public function saveRecord($mode, $userId)
    {
        $id=$this->getParam('id', NULL);
        $quote = $this->getParam('quote', NULL);
        $whosaidit = $this->getParam('whosaidit', NULL);

        // if edit use update
        if ($mode=="edit") {
            $this->update("id", $id, array(
            'quote' => $quote,
            'whosaidit' => $whosaidit,
            'datemodified' => date("Y/m/d H:i:s"),
            'modifierid' => $this->objUser->userId()));

        }#if
        // if add use insert
        if ($mode=="add") {
            $this->insert(array(
            'quote' => $quote,
            'whosaidit' => $whosaidit,
            'datecreated' => date("Y/m/d H:i:s"),
            'creatorid' => $this->objUser->userId()));

        }#if
    }#function

    public function getRandom()
    {
        $sql = "select * from tbl_quotes order by rand() limit 1";
        return $this->getArray($sql);

    }


} #end of class
?>
