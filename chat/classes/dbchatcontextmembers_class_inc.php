<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}


/**
* Model class for the table tbl_chat_context_members
* @author Jeremy O'Connor
* @copyright 2004 University of the Western Cape
*/
class dbChatContextMembers extends dbTable
{
    /**
    * Constructor method to define the table
    */
    public function init() 
    {
        parent::init('tbl_chat_context_members');
        //$this->USE_PREPARED_STATEMENTS=True;
    }

    /**
    * Return all contexts for a user
	* @param string $userId The user ID
	* @return array The contexts
    */
    public function getContextsForUser($userId)
    {
        $sql = "SELECT tbl_chat_contexts.context AS context 
        FROM tbl_chat_context_members, tbl_chat_contexts
        WHERE tbl_chat_context_members.contextId = tbl_chat_contexts.id
        AND tbl_chat_context_members.userId = '$userId'";
		return $this->getArray($sql);
    }

    /**
    * Return one records
	* @param string $contextId The context ID
	* @param string $username The username
	* @return array The user
    */
	public function listsingle($contextId, $userId)
	{
		$sql = "SELECT * FROM {$this->_tableName} WHERE"
		. " (contextId='" . $contextId . "')"
		. " AND (userId='" . $userId . "')";
		return $this->getArray($sql);
	}

	/**
	* Insert a record
	* @param string $contextId The context ID
	* @param string $username The username
	* @param integer $start The start timestamp for the user
	*/
	public function insertSingle($contextId, $userId)
	{
		$this->insert(array(
			'contextId' => $contextId,
			'userId' => $userId
		));
		return;	
	}
}
?>