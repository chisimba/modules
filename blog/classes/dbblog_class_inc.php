<?php

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
 * Data access (db model) Class for the blog module
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

class dbblog extends dbTable
{

	//methods to manipulate the categories table.

	/**
	 * Method to get a list of the users categories as defined by the user
	 *
	 * @param integer $userid
	 * @return arrayunknown_type
	 * @access public
	 */
	public function getCats($userid)
	{
		$this->_changeTable('tbl_blog_cats');
		return $this->getAll("where userid = " . $userid);
	}

	/**
	 * Method to set a category
	 *
	 * @param integer $userid
	 * @param array $cats
	 * @return boolean
	 */
	public function setCats($userid, $cats = array())
	{
		if(!empty($cats))
		{
			$this->_changeTable('tbl_blog_cats');
			return $this->insert($cats, 'tbl_blog_cats');
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