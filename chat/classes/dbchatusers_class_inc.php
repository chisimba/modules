<?php
/* ----------- data class extends dbTable for tbl_blog------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}


/**
* Model class for the table tbl_faq
* @author Jeremy O'Connor
* @copyright 2004 University of the Western Cape
*/
class dbChatUsers extends dbTable
{
    /**
    * Constructor method to define the table
    */
    public function init() 
    {
        parent::init('tbl_chat_users');
        //$this->USE_PREPARED_STATEMENTS=True;
    }
	
	/**
	* Returns a list of the rooms a user is in.
	* @param string The username.
	* @return array The rooms.
	*/
    public function getRoomsUserIsIn($username)
    {
        $now = mktime()-30*60;
        $sql = "SELECT
        tbl_chat_contexts.context
        FROM tbl_chat_contexts, tbl_chat_users
        WHERE
            (tbl_chat_contexts.id = tbl_chat_users.contextId)
            AND (tbl_chat_users.username = '$username')
            AND (tbl_chat_users.lastActive > $now)
        ORDER BY tbl_chat_contexts.context";
		return $this->getArray($sql);
    }
    
    /**
    * Return all records
	* @param string $contextId The context ID
	* @return array The users
    */
	public function listAll($contextId)
	{
        $now = mktime()-30*60;
        $sql = "SELECT 
        tbl_users.userId, 
        tbl_users.username, 
        tbl_users.firstName, 
        tbl_users.surname, 
        tbl_chat_users.lastActive 
        FROM tbl_users, tbl_chat_users"
		. " WHERE (tbl_users.username = tbl_chat_users.username)"
		. " AND (tbl_chat_users.contextId = '" . $contextId . "')"
        . " AND (tbl_chat_users.lastActive > $now)"
		. " ORDER BY tbl_users.username";
		return $this->getArray($sql);
	}

    /**
    * Return one records
	* @param string $contextId The context ID
	* @param string $username The username
	* @return array The user
    */
	public function listsingle($contextId, $username)
	{
		$sql = "SELECT * FROM tbl_chat_users WHERE"
		. " (contextId='" . $contextId . "')"
		. " AND (username='" . $username . "')";
		return $this->getArray($sql);
	}

	/**
	* Return count of users
	* @param string $contextId The context ID
	* @return array The count of users
	*/
	public function listCount($contextId)
	{
        $now = mktime()-30*60;
		$sql = "SELECT count(*) FROM tbl_chat_users
		WHERE (contextId='" . $contextId . "')
        AND (lastActive > $now)";
		return $this->getArray($sql);
	}
	
	/**
	* Insert a record
	* @param string $contextId The context ID
	* @param string $username The username
	* @param integer $start The start timestamp for the user
	*/
	public function insertSingle($contextId, $username, $start, $lastActive)
	{
		$this->insert(array(
			'contextId' => $contextId,
			'username' => $username,
			'start' => $start,
            'lastActive' => $lastActive
		));
		return;	
	}

	/**
	* Updates records
	* @param string $contextId The context ID
	* @param string $username The username
    * @param int $lastActive Timestamp last active
	*/
	public function updateSingle($contextId, $username, $lastActive)
	{
        $sql = "SELECT id FROM tbl_chat_users 
        WHERE (contextId = '".$contextId."') 
        AND (username = '".$username."')";
        $array = $this->getArray($sql);
        if (!empty($array)) {
            $id = $array[0]['id'];
    		$this->update("id", $id, 
    			array(
                    'lastActive' => $lastActive
    			)
    		);
        }
	}

	/**
	* Deletes records
	* @param string $contextId The context ID
	* @param string $username The username
	*/
	public function deleteSingle($contextId, $username)
	{
        $sql = "SELECT id FROM tbl_chat_users 
        WHERE (contextId = '".$contextId."') 
        AND (username = '".$username."')";
        $array = $this->getArray($sql);
        if (!empty($array)) {
            $id = $array[0]['id'];
    		$this->delete("id", $id);
        }
	}
}
?>