<?php
/* -------------------- dbfoafusers class ----------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
 * Model class to get as much FOAF useable information from tbl_users
 *
 * @author Paul Scott
 * @access public
 * @package foaf
 * @category foaf
 */
class dbfoaf extends dbtable
{
	/**
	 * The config Object
	 *
	 * @var object
	 */
	public $objConfig;

	/**
	 * User object
	 *
	 * @var object
	 */
    private $objUser;

    /**
     * Language subsystem
     *
     * @var object
     */
    private $objLanguage;

    /**
     * Initialise the required objects
     *
     * @param void
     * @return void
     */
    public function init()
    {
	    try {
	    	//initialize the parent table
    		parent::init('tbl_users');
    		//get the config object
        	$this->objConfig=&$this->getObject('altconfig','config');
        	//get the user stuff
        	$this->objUser=&$this->getObject('user','security');
        	//load up the language stuff
        	$this->objLanguage=&$this->getObject('language','language');
	    }
	    //catch any exceptions
	    catch (customException $e)
	    {
	    	//clean up memory
	    	customException::cleanUp();
	    }
    }

    /**
     * Method to get a recordset from tbl_users for a particular userId
     *
     * @param integer $userId
     * @return array
     */
    public function getRecordSet($userId, $table)
    {
		$this->_changeTable($table);
    	$sql = "WHERE userid = $userId";
    	return $this->getAll($sql);
    }

    /**
     * Method to insert the users filled in details to tbl_foaf_myfoaf
     *
     * @param integer $userid
     * @param array $array
     * @return bool
     */
    public function insertMyDetails($userid, $array)
    {
    	$this->_changeTable('tbl_foaf_myfoaf');
    	$checker = $this->getRecordSet($userid, 'tbl_foaf_myfoaf');
    	if(empty($checker))
    	{
    		return $this->insert($array);
    	}
    	else {
    		return $this->update('id', $checker[0]['id'], $array, 'tbl_foaf_myfoaf');
    	}
    }

    /**
     * Method to get all known users from tbl_users
     *
     * @param void
     * @return array
     */
    public function getAllUsers()
    {
    	$this->_changeTable('tbl_users');
    	return $this->getAll();
    }

    /**
     * Method to get the friends of a partiucular userid
     *
     * @param void
     * @return array
     */
    public function getFriends()
    {
    	$this->_changeTable('tbl_foaf_friends');
    	$userid = $this->objUser->userId();
    	$frie = $this->getAll('WHERE userid = '. $userid);
    	foreach($frie as $friends)
    	{
    		//echo $friends['fuserid'];
    		//lookup the userid and get a name for display
    		$this->_changeTable('tbl_users');
    		$ret = $this->getAll('WHERE userid = ' . $friends['fuserid']);
    		$fullname = $ret[0]['firstname'] . " " . $ret[0]['surname'];
    		$pkid = $friends['id'];
    		$fid = $friends['fuserid'];

    		$det[] = array('id' => $pkid, 'name' => $fullname, 'fuserid' => $fid);
    	}
    	if(isset($det))
    	{
    		return $det;
    	}
    }

    /**
     * Method to insert a new friend into the friends table according to a userid
     *
     * @param array $friend
     * @return bool
     */
    public function insertFriend($friend)
    {
    	$this->_changeTable('tbl_foaf_friends');
    	return $this->insert($friend);
    }

    /**
     * Method to remove a friend associated with the current userid
     *
     * @param array $friend
     * @return bool
     */
    public function removeFriend($friend)
    {
    	$this->_changeTable('tbl_foaf_friends');
    	//print_r($friend);
    	return $this->delete('id',$friend['fuserid']);
    }

    /**
     * Method to insert an organization according to the current userid
     *
     * @param string $name
     * @param string $url
     * @return bool
     */
    public function insertOrg($name, $url)
    {
    	$this->_changeTable('tbl_foaf_organization');
    	$uid = $this->objUser->userId();
    	$ins = array('userid' => $uid, 'homepage' => $url, 'name' => $name);
    	return $this->insert($ins);
    }

    /**
     * Method to remove an associated organization from a userid
     * This is step one of two to get the org details
     *
     * @param void
     * @return array
     */
    public function remOrg()
    {
    	$userid = $this->objUser->userId();
    	$this->_changeTable('tbl_foaf_organization');
    	$ret = $this->getAll("WHERE userid = " . $userid);
    	//print_r($ret);
    	foreach ($ret as $returns)
    	{
    		$retarr[] = array('id' => $returns['id'], 'name' => $returns['name']);
    	}
    	return $retarr;
    }

    /**
     * Part two of org removal
     *
     * @param string $orgid
     * @return bool
     */
    public function removeOrg($orgid)
    {
    	$this->_changeTable('tbl_foaf_organization');
    	return $this->delete('id', $orgid);
    }

    /**
     * Method to change database tables
     *
     * @param string $table
     * @return void
     */
    private function _changeTable($table)
    {
    	parent::init($table);
    }

}
?>