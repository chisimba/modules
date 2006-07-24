<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }


/**
* Model class for the table tbl_users
* @author Jeremy O'Connor
* @copyright 2004 University of the Western Cape
*/
class dbUsers extends dbTable
{
    /**
    * Constructor method to define the table
    */
    function init() 
    {
        parent::init('tbl_users');
        //$this->USE_PREPARED_STATEMENTS=True;
    }

    /**
    * Return all records
	* @return array Users
    */
	function listAll()
	{
		$sql = "SELECT userid, CONCAT(firstname, ' ', surname) AS fullname FROM {$this->_tableName} ORDER BY fullname";
		return $this->getArray($sql);
	}
}
?>