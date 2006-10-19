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

	/**
	 * Standard init function - Class Constructor
	 *
	 * @access public
	 * @param void
	 * @return void
	 */
	public function init()
	{

	}

	//methods to manipulate the categories table.

	/**
	 * Method to get a list of the users categories as defined by the user
	 *
	 * @param integer $userid
	 * @return arrayunknown_type
	 * @access public
	 */
	public function getAllCats($userid)
	{
		$this->_changeTable('tbl_blog_cats');
		return $this->getAll("where userid = " . $userid);
	}

	/**
	 * Method to grab the top level parent categories per user id
	 *
	 * @param integer $userid
	 * @return array
	 */
	public function getParentCats($userid)
	{
		$this->_changeTable('tbl_blog_cats');
		return $this->getAll("where userid = " . $userid . "AND cat_parent = 0");
	}

	/**
	 * Grab the child categories as a userl, according to the parent category
	 *
	 * @param integer $userid
	 * @param string $cat
	 * @return unknown
	 */
	public function getChildCats($userid, $cat)
	{
		$this->_changeTable('tbl_blog_cats');
		return $this->getAll("where userid = '$userid' AND cat_parent = '$cat'");
	}

	/**
	 * Method to create a merged array of the parent and child categories per user id
	 *
	 * @param integer $userid
	 * @return array
	 */
	public function getCatsTree($userid)
	{
		$parents = $this->getParentCats($userid);
		foreach ($parents as $p)
		{
			$tree[] = $this->getChildCats($userid, $p['id']);
		}
		$tree = array_merge($parents, $tree);
		return $tree;
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