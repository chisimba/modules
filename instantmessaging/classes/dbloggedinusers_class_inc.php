<?php
/* ----------- data class extends dbTable for tbl_blog------------*/// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }


/**
* Model class for the table tbl_loggedinusers
* @author Jeremy O'Connor
* @copyright 2004 University of the Western Cape
*/
class dbloggedinusers extends dbTable
{
    /**
    * Constructor method to define the table
    */
    function init() 
    {
        parent::init('tbl_loggedinusers');
        //$this->USE_PREPARED_STATEMENTS=True;
    }

    /**
    * Return all records
	* @return array Logged in users
    */
	function listAll()
	{
		$sql = "SELECT * FROM tbl_loggedinusers";
		return $this->getArray($sql);
		//return $this->getAll();
	}
}
?>