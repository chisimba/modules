<?php
/* ----------- data class extends dbTable for tbl_blog------------*/// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }


/**
* Model class for the table tbl_homepages
* @author Jeremy O'Connor
* @copyright 2004 University of the Western Cape
*/
class dbHomePages extends dbTable
{
    /**
    * Constructor method to define the table
    */
    function init() 
    {
        parent::init('tbl_homepages');
        //$this->USE_PREPARED_STATEMENTS=True;
    }

    /**
    * Return all records
	* @param string $userId The user ID
	* @return array The contents of the homepage
    */
	function listsingle($userId)
	{
		$sql = "SELECT id, contents FROM tbl_homepages 
		WHERE userId = '" . $userId . "'";
		return $this->getArray($sql);
	}

	/**
	* Insert a record
	* @param string $userId The user ID
	* param string $contents The contents of the homepage
	*/
	function insertSingle($userId, $contents)
	{
		$this->insert(array(
			'userId' => $userId,
			'contents' => $contents,
		));
		return;	
	}

	/**
	* Update a record
	* @param string $userId The user ID
	* param string $contents The contents of the homepage
	*/
	function updateSingle($userId, $contents)
	{
		$this->update("userId", $userId, 
			array(
				'contents' => $contents
			)
		);
	}
}
?>