<?php

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
 * Data access (db model) Class for the karma module
 *
 * This is a database model class for the karma module. All database transaactions will go through
 * this class. This class is derived from the top level dbTable superclass in the framework core.
 *
 * @author Paul Scott
 * @filesource
 * @copyright AVOIR
 * @package tagging
 * @category chisimba
 * @access public
 */

class dbkarma extends dbTable
{

	/**
	 * Standard init function - Class Constructor
	 *
	 * @access public
	 * @param void
	 * @return void
	 */
	public function init()
	{
		$this->objLanguage = $this->getObject("language", "language");

	}
	
	/**
	 * Method to add an arbitary number of points to a users point collection
	 * 
	 * @param string $userid
	 * @param integer $points
	 */
	public function addPoints($userid, $points)
	{
		$this->_changeTable('tbl_karmapoints');
		// check first that the userid exists
		$check = $this->getAll("WHERE userid = '$userid'");
		if(empty($check))
		{
			return $this->insert(array('points' => $points), 'tbl_karmapoints');
		}
		else {
			return $this->update('id', $check[0]['id'], array('points' => $points), 'tbl_karmapoints');
		}
	}
	

	/**
	 * Method to dynamically switch tables
	 *
	 * @param string $table
	 * @return boolean
	 * @access private
	 */
	private function _changeTable($table)
	{
		try {
			parent::init($table);
			return TRUE;
		}
		catch (customException $e)
		{
			customException::cleanUp();
			return FALSE;
		}
	}
}
?>