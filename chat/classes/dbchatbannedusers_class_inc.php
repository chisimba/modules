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
class dbChatBannedUsers extends dbTable
{
    /**
    * Constructor method to define the table
    */
    public function init() 
    {
        parent::init('tbl_chat_banned_users');
        //$this->USE_PREPARED_STATEMENTS=True;
    }

    /**
    * Return all records
	* @param string $username The username
	* @return array The expiry timestamp for a username
    */
	public function listsingle($username)
	{
		$sql = "SELECT expire FROM tbl_chat_banned_users WHERE"
		. " username='" . $username . "'";
		return $this->getArray($sql);
	}

	/**
	* Insert a record
	* @param string $username The username
	* @param integer $expire The expiration timestamp
	*/
	public function insertSingle($username, $expire)
	{
		$this->insert(array(
			'username' => $username,
			'expire' => $expire
		));
		return;	
	}

	/**
	* Deletes records
	* @param string $username The username
	*/
	public function deleteSingle($username)
	{
    	$this->delete("username", $username);
	}
}
?>