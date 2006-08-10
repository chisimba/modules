<?php
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
/**
* Database class for the table tbl_im_options
* @author Jeremy O'Connor
* @copyright 2004,2005 University of the Western Cape
*/
class dboptions extends dbTable
{
    /**
    * Constructor method to define the table
    */
    function init() 
    {
        parent::init('tbl_im_options');
        //$this->USE_PREPARED_STATEMENTS=True;
		$this->objUser =& $this->getObject('user','security');
    }
	/**
	* Check if a record exists. If not, create one.
	* @param string The userId
	*/
	function checkExists($userId)
	{
		// Insert a record if not found.
		if (!$this->valueExists('userid',$userId)) {
			$this->insert(array(
		       	'userid'=>$userId
			));
		}
		/*
		$sql = "SELECT COUNT(*) FROM {$this->_tableName}
		WHERE userId='{$userId}'";
		$array = $this->getArray($sql);
		if (empty($array)) {
		}
		*/
	}
	/**
	* Set the option
	* @param string The option
	* @param bool The value
	* @param string The userId
	*/
	function set($option,$value,$userId=null)
	{
		if (is_null($userId)) {
		    $userId = $this->objUser->userId();
		}
		$this->checkExists($userId);
		// Now update the option		
		$this->update('userid',$userId,array(
        	$option => ($value ? '1' : '0')
		));
		return;	
	}
	/**
	* Get the option
	* @param string The option
	* @param string The userId
	* @return bool The value
	*/
	function get($option,$userId=null)
	{
		if (is_null($userId)) {
		    $userId = $this->objUser->userId();
		}
		$this->checkExists($userId);
		$row = $this->getRow('userid',$userId);
		return $row[$option] == '1';
		/*
		$sql = "SELECT * FROM {$this->_tableName}
		WHERE userId='{$userId}'";
		$array = $this->getArray($sql);
		if (empty($array)) {
			return $default;
		}
		else {
			return ($array[0][$option] == '1') ? true : false;
		}
		*/
	}
}
?>