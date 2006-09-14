<?php
/* ----------- data class extends dbTable for tbl_blog------------*/// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }


/**
* Model class for the table tbl_kng_email
* @author Jeremy O'Connor
* @copyright 2004 University of the Western Cape
*/
class dbEmail extends dbTable
{
    /**
    * Constructor method to define the table
    */
    function init() 
    {
        parent::init('tbl_email');
        //$this->USE_PREPARED_STATEMENTS=True;
    }

    /**
    * Return all records
	* @param string $userId The user ID
	* @return array The count of new emails
    */
	function listAll($userId)
	{
		$sql = "select count(sender_id) from tbl_email where user_id='".$userId."' and folder='new'";
		return $this->getArray($sql);
	}
}
?>