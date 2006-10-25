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
		return $this->getAll("where userid = " . $userid . " AND cat_parent = '0'");
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
		$child = $this->getAll("where userid = '$userid' AND cat_parent = '$cat'");
		return array('child' => $child);
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
		$tree = new stdClass();
		if(empty($parents))
		{
			$tree = NULL;
		}
		foreach ($parents as $p)
		{
			$parent = $p;
			$child = $this->getChildCats($userid, $p['id']);
			$tree->$p['cat_name'] = array_merge($parent, $child);
		}
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
	 * Method to map the child id of a category to a nice name
	 *
	 * @param mixed $childId
	 * @return array
	 */
	public function mapKid2Parent($childId)
	{
		$this->_changeTable('tbl_blog_cats');
		$ret = $this->getAll("WHERE id = '$childId'");
		return $ret;
	}


	//Methods to manipulate the link categories

	/**
	 * Method to get a list of the users link categories as defined by the user
	 *
	 * @param integer $userid
	 * @return array
	 * @access public
	 */
	public function getAllLinkCats($userid)
	{
		$this->_changeTable('tbl_blog_linkcats');
		return $this->getAll("where userid = " . $userid);
	}

	public function getLinksCats($userid, $cat)
	{
		$this->_changeTable('tbl_blog_links');
		return $this->getAll("WHERE userid = '$userid' AND link_category = '$cat'");
	}

	public function setLinkCats($userid, $linkCats = array())
	{
		if(!empty($linkCats))
		{
			$this->_changeTable('tbl_blog_linkcats');
			return $this->insert($linkCats, 'tbl_blog_linkcats');
		}
		else {
			return FALSE;
		}
	}

	public function setLink($userid, $linkarr)
	{
		$this->_changeTable('tbl_blog_links');
		if(!empty($linkarr))
		{
			return $this->insert($linkarr, 'tbl_blog_links');
		}
		else {
			return FALSE;
		}
	}

	// posts section

	public function getAllPosts($userid)
	{
		$this->_changeTable('tbl_blog_posts');
		return $this->getAll("WHERE userid = '$userid' ORDER BY menu_order ASC");
	}

	public function getRandBlog()
	{
		$this->_changeTable('tbl_blog_posts');
		$res = $this->getAll();
		//print_r($res);
		if(!empty($res))
        {
        	foreach($res as $blogs)
        	{
        		$blo[] = $blogs['userid'];
        	}
        	$rand_keys = array_rand($blo,1);
			return $res[$rand_keys];
        }
        else {
        	return NULL;
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