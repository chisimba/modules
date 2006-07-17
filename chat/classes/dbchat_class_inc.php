<?php
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
class dbChat extends dbTable
{
    /**
    * Constructor method to define the table
    */
    public function init()
    {
        parent::init('tbl_chat');
        //$this->USE_PREPARED_STATEMENTS=True;
    }
    
    /**
    * Return records matching a search term.
	* @param string $contextId The context ID
	* @param string $username The username
	* @param integer $start The start timestamp
	* @param boolean $limit Limit the entries returned
	* @return array The posts
    */
	public function search($contextId, $username, $start, $limit, $term)
	{
		$sql = "SELECT id, username, content, recipient, stamp FROM tbl_chat"
		. " WHERE (contextId='" . $contextId . "')"
        . " AND (content LIKE '%".$term."%')"
		. " ORDER BY stamp DESC";
		if ($limit) {
		    $sql .= " LIMIT 10";
		}
        //echo $sql;
		$content = $this->getArray($sql);
		return array_reverse($content);
	}

    /**
    * Return all records
	* @param string $contextId The context ID
	* @param string $username The username
	* @param integer $start The start timestamp
	* @param boolean $limit Limit the entries returned
	* @return array The posts
    */
	public function listAll($contextId, $username, $start, $limit)
	{
		$sql = "SELECT id, username, content, recipient, stamp FROM tbl_chat"
		. " WHERE (contextId='" . $contextId . "')"
		. " AND ((recipient='All')"
		. " OR (recipient='" . $username . "')"
		. " OR (username='" . $username . "'))"
		. " AND (stamp>='" . $start . "')"
		. " ORDER BY stamp DESC";
		if ($limit) {
		    $sql .= " LIMIT 10";
		}
		$content = $this->getArray($sql);
		return array_reverse($content);
	}

    /**
    * Return the last post.
	* @param string $contextId The context ID
	* @param string $username The username
	* @return array The posts
    */
	public function listLast($contextId, $username, $limit='1')
	{
		$sql = "SELECT id, username, content, recipient, stamp FROM tbl_chat"
		. " WHERE (contextId='" . $contextId . "')"
		. " AND ((recipient='All')"
        . " AND (username<>''))" // Don't want meta-posts
		. " AND (stamp>='0')"
		. " ORDER BY stamp DESC"
        . " LIMIT $limit";
		$content = $this->getArray($sql);
		return array_reverse($content);
	}

	/**
	* Insert a record
	* @param string $contextId The context ID
	* @param string $username The username
	* @param string $text The text of the post
	* @param string $recipient The recipient of the post
	* @param integer $timestamp The timestamp of the post
	*/
	public function insertSingle($contextId, $username, $text, $recipient, $timestamp)
	{
		$this->insert(array(
			'contextId' => $contextId,
			'username' => $username,
        	'content' => $text,
			'recipient'=>$recipient,
			'stamp'=>$timestamp
		));
		return;
	}

	/**
	* Deletes records
	* @param string $contextId The context ID
	*/
	public function deleteAll($contextId)
	{
		$this->delete("contextId", $contextId);
	}
}
?>