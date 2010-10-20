<?php

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
 * Data access (db model) Class for the qrreview module
 *
 * This is a database model class for the qrreview module. All database transaactions will go through
 * this class. This class is derived from the top level dbTable superclass in the framework core.
 *
 * @author Paul Scott
 * @filesource
 * @copyright AVOIR
 * @package qrreview
 * @category chisimba
 * @access public
 */

class dbqrreview extends dbTable
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
		parent::init('tbl_qrreview_prods');
	}
	
    /**
    * Method to add a record
    * @param array $insarr Array with record fields
    * @return string Last Insert Id
    */
	public function insertRecord($insarr)
	{
	    $insarr['creationdate'] = $this->now();
		return $this->insert($insarr, 'tbl_qrreview_prods');
	}
	
	/**
	 * Method to retrieve a specific record by ID from the table
	 * 
	 * @param string id
	 * @access public
	 * @return array
	 */
	 public function getRecord($id) {
	     return $this->getAll("WHERE id = '$id'");
	 }
}
?>
