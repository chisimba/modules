<?php

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
 * Data access (db model) Class for the geonames module
 *
 * This is a database model class for the blog module. All database transaactions will go through
 * this class. This class is derived from the top level dbTable superclass in the framework core.
 *
 * @author Paul Scott
 * @filesource
 * @copyright AVOIR
 * @package blog
 * @category chisimba
 * @access public
 */

class dbgeonames extends dbTable
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
		parent::init('tbl_geonames');

	}
	
	public function insertRecord($insarr)
	{
		return $this->insert($insarr, 'tbl_geonames');
	}
	
}
?>