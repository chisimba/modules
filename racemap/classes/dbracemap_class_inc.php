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

class dbracemap extends dbTable
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
		$this->objUser = $this->getObject('user', 'security');
		parent::init('tbl_racemap_meta');
	}
	
    /**
     * Method to add a record to the metadata table
     * @param array $insarr Array with record fields
     * @return string Last Insert Id
     */
	public function insertMetaRecord($insarr)
	{
		return $this->insert($insarr, 'tbl_racemap_meta');
	}
	
	/**
     * Method to add a record to the track table
     * @param array $insarr Array with record fields
     * @return string Last Insert Id
     */
	public function insertTrkPoints($insarr) {
	    $this->changeTable('tbl_racemap_tracks');
	    $this->insert($insarr, 'tbl_racemap_tracks');
	    $this->changeTable('tbl_racemap_meta');
	}
	
	public function getMetaFromId($id) {
	    $this->changeTable('tbl_racemap_meta');
	    return $this->getAll("WHERE id = '$id'");
	}
	
	public function getUserTracks($userid) {
	    $this->changeTable('tbl_racemap_meta');
	    return $this->getAll("WHERE userid = '$userid'");
	}
	
	public function updateMeta($updatearr) {
	    $this->changeTable('tbl_racemap_meta');
	    $updatearr['author'] = $this->objUser->fullName();
	    $updatearr['creationtime'] = $this->now();
	    
	    $this->update('id', $updatearr['id'], $updatearr);
	    return;
	}
	
	public function countPoints($metaid) {
	    $this->changeTable('tbl_racemap_tracks');
	    $stop = $this->getRecordCount("WHERE metaid = '$metaid'");
	    $this->changeTable('tbl_racemap_meta');
	    return $stop;
	}
	
	public function getPoints($metaid, $start, $stop) {
	    $this->changeTable('tbl_racemap_tracks');
	    $ret = $this->getAll("WHERE metaid = '$metaid' LIMIT $start, $stop"); 
	    $this->changeTable('tbl_racemap_meta');
	    return $ret;
	}
	
	public function changeTable($table) {
	    parent::init($table);
	}
}
?>
