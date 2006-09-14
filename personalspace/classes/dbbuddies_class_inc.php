<?php
/* ----------- data class extends dbTable for tbl_blog------------*/// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }


/**
* Model class for the table tbl_buddies
* @author Jeremy O'Connor
* @copyright 2004 University of the Western Cape
*/
class dbBuddies extends dbTable
{
    /**
    * Constructor method to define the table
    */
    function init() 
    {
        parent::init('tbl_buddies');
        //$this->USE_PREPARED_STATEMENTS=True;
    }

    /**
    * Return all records
	* @param string $userId The user ID
	* @return array The buddies for a user
    */
	function listAll($userId)
	{
		$sql = "SELECT tbl_buddies.buddyId, tbl_users.emailAddress FROM tbl_buddies, tbl_users
		WHERE (tbl_buddies.userId = '" . $userId . "')
		AND (tbl_buddies.buddyId = tbl_users.userId)";
		return $this->getArray($sql);
	}

    /**
    * Return all records
    */
	function listSingle($userId, $buddyId)
	{
		$sql = "SELECT count(*) FROM tbl_buddies
        WHERE (userId = '" . $userId . "')
        AND (buddyId = '" . $buddyId . "')";
		return $this->getArray($sql);
	}

	/**
	* Insert a record
	* @param string $userId The user ID
	* @param string $buddyId The buddy ID
	*/
	function insertSingle($userId, $buddyId)
	{
		$this->insert(array(
			'userId' => $userId,
			'buddyId' => $buddyId
		));
		return;	
	}

	/**
	* Deletes records
	* @param string $userId The user ID
	* @param string $buddyId The buddy ID
	*/
	function deleteSingle($userId, $buddyId)
	{
        $sql = "DELETE FROM tbl_buddies
		WHERE (userId='" . $userId . "')
		AND (buddyId='" . $buddyId . "')";
        return $this->_execute($sql);
	}
}
?>
