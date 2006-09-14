<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }


/**
* Model class for the table tbl_personal_info_import
* @author Jeremy O'Connor
* @copyright 2004 University of the Western Cape
*/
class dbPersonalInfoImport extends dbTable
{
    /**
    * Constructor method to define the table
    */
    function init() 
    {
        parent::init('tbl_personal_info');
        //$this->USE_PREPARED_STATEMENTS=True;
    }

    /**
    * Return all records
	* @return array All users
    */
	function listSingle($userId)
	{
		$sql = "SELECT * FROM {$this->_tableName}
        WHERE userId = '$userId'";
		return $this->getArray($sql);
	}

	/**
	* Update a record
	* @param string $id ID
	* @param array The data
	*/
	function updateSingle($userId, $data)
	{
		$this->update("userId", $userId, $data);
	}
}
?>