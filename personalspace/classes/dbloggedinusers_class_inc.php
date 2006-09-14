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
	* Return a single record
	* @param string $userId The user ID
	* @return array Logged in user
	*/	
	function listSingle($userId)
	{
		$sql = "SELECT * FROM tbl_loggedinusers WHERE userId = '" . $userId . "'";
		return $this->getArray($sql);
		//return $this->getRow("id", $id);
	}
    
    /**
    * Method to get a list of logged in users
    * @param string $order Order Clause
    * @return array List of Users Online
    */
    function getListOnlineUsers($order = 'WhenLastActive DESC')
    {
        $sql = 'SELECT DISTINCT tbl_users.userId, username, firstName, surname FROM tbl_loggedinusers INNER JOIN tbl_users ON (tbl_loggedinusers.userId = tbl_users.userId) ORDER BY '.$order;
        
        return $this->getArray($sql);
    }
}
?>