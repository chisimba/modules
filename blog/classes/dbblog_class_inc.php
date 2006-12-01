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

	public function deleteCat($catid)
	{
		$this->_changeTable('tbl_blog_cats');
		return $this->delete('id',$catid, 'tbl_blog_cats');
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
	 * Method to get a single cat for edit
	 *
	 * @param
	 */
	public function getCatForEdit($userid, $id)
	{
		$this->_changeTable('tbl_blog_cats');
		$ret = $this->getAll("WHERE userid = '$userid' AND id = '$id'");
		return $ret[0];
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
		else {
			foreach ($parents as $p)
			{
				$parent = $p;
				$child = $this->getChildCats($userid, $p['id']);
				if(is_null($p['cat_name']))
				{
					$p['cat_name'] = 0;

				}
				$tree->$p['cat_name'] = array_merge($parent, $child);
			}
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
	public function setCats($userid, $cats = array(), $mode = NULL)
	{
		if(!empty($cats))
		{
			if($mode == 'editcommit')
			{
				$this->_changeTable('tbl_blog_cats');
				return $this->update('id', $cats['id'], $cats, 'tbl_blog_cats');
			}
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

	/**
	 * Method to count the number of posts in a category
	 *
	 * @param string $cat
	 * @return integer
	 */
	public function catCount($cat)
	{
		if($cat == NULL)
		{
			$this->_changeTable('tbl_blog_posts');
			return $this->getRecordCount();
		}
		$this->_changeTable('tbl_blog_posts');
		return $this->getRecordCount("WHERE post_category = '$cat'");
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

	/**
	 * Get the links per category
	 *
	 * @param integer $userid
	 * @param string $cat
	 * @return mixed
	 */
	public function getLinksCats($userid, $cat)
	{
		$this->_changeTable('tbl_blog_links');
		return $this->getAll("WHERE userid = '$userid' AND link_category = '$cat'");
	}

	/**
	 * Add a category to the links section
	 *
	 * @param integer $userid
	 * @param array $linkCats
	 * @return boolean
	 */
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

	/**
	 * Method to add a link to a category
	 *
	 * @param integer $userid
	 * @param array $linkarr
	 * @return boolean
	 */
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
	/**
	 * Method to get all the posts in a category (published posts)
	 *
	 * @param integer $userid
	 * @param mixed $catid
	 * @return array
	 */
	public function getAbsAllPosts($userid)
	{
		$this->_changeTable('tbl_blog_posts');
		return $this->getAll("WHERE userid = '$userid' ORDER BY post_ts DESC");
	}

	/**
	 * Method to get all the posts in a category (published posts as well as drafts)
	 *
	 * @param integer $userid
	 * @param mixed $catid
	 * @return array
	 */
	public function getAbsAllPostsNoDrafts($userid)
	{
		$this->_changeTable('tbl_blog_posts');
		return $this->getAll("WHERE userid = '$userid' AND post_status = '0' ORDER BY post_ts DESC");
	}

	/**
	 * Method to get all the posts in a category (published posts ONLY)
	 *
	 * @param integer $userid
	 * @param mixed $catid
	 * @return array
	 */
	public function getAllPosts($userid, $catid)
	{
		if(!isset($catid))
		{
			$catid = 0;
		}
		$this->_changeTable('tbl_blog_posts');
		return $this->getAll("WHERE userid = '$userid' AND post_category = '$catid' AND post_status = '0' ORDER BY post_ts DESC");
	}

	/**
	 * Method to get all the posts made within a month
	 *
	 * @param mixed $startdate
	 * @param string $userid
	 * @return array
	 */
	public function getPostsMonthly($startdate, $userid)
	{
		$this->_changeTable('tbl_blog_posts');
		$this->objblogOps = &$this->getObject('blogops');
		$times = $this->objblogOps->retDates($startdate);
		//print_r($times);
		$now = date('r',mktime(0,0,0,date("m", time()), date("d", time()), date("y", time())));
		$monthstart =  $times['mbegin'];
		$prevmonth = $times['prevmonth'];
		$nextmonth = $times['nextmonth'];
		//get the entries from the db
		$filter = "WHERE post_ts > '$monthstart' AND post_ts < '$nextmonth' AND userid = '$userid' ORDER BY post_ts DESC";
		$ret = $this->getAll($filter);
		return $ret;
	}

	/**
	 * Method to delete a post
	 *
	 * @param mixed $id
	 * @return boolean
	 */
	public function deletePost($id)
	{
		$this->_changeTable('tbl_blog_posts');
		return $this->delete('id',$id, 'tbl_blog_posts');
	}

	/**
	 * Method to get a post by its ID
	 *
	 * @param mixed $id
	 * @return array
	 */
	public function getPostById($id)
	{
		$this->_changeTable('tbl_blog_posts');
		return $this->getAll("WHERE id = '$id'");
	}

	/**
	 * Method to get all the posts within a category
	 *
	 * @param integer $userid
	 * @param mixed $catid
	 * @return array
	 */
	public function getPostsFromCat($userid, $catid)
	{
		$this->_changeTable('tbl_blog_posts');
		return $this->getAll("WHERE userid = '$userid' AND post_category = '$catid'");
	}

	/**
	 * Method to get the latest post of a user
	 *
	 * @param integer $userid
	 * @return array
	 */
	public function getLatestPost($userid)
	{
		$this->_changeTable('tbl_blog_posts');
		$filter = "WHERE userid = '$userid' ORDER BY post_ts DESC";
		$lastpost = $this->getAll($filter);
		if(isset($lastpost[0]))
		{
			$lastpost = $lastpost[0];
		}
		else {
			$lastpost = NULL;
		}
		return $lastpost;
	}

	/**
	 * Method to return a random blog
	 *
	 * @param void
	 * @return mixed
	 */
	public function getRandBlog()
	{
		$this->_changeTable('tbl_blog_posts');
		$res = $this->getAll();

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

	//post methods

	/**
	 * Method to insert a post to your posts table
	 *
	 * @param integer $userid
	 * @param array $postarr
	 * @param string $mode
	 * @return array
	 */
	public function insertPost($userid, $postarr, $mode = NULL)
	{

		$this->_changeTable("tbl_blog_posts");
		$this->objblogOps = $this->getObject('blogops');

		if($mode == NULL)
		{
			$this->pcleaner = $this->newObject('htmlcleaner', 'utilities');
			$this->ecleaner = $this->newObject('htmlcleaner', 'utilities');
			$insarr = array('userid' => $userid,
							'post_date' => date('r'),
							'post_content' => $this->pcleaner->cleanHtml($this->objblogOps->html2txt($postarr['postcontent'])),
							'post_title' => $postarr['posttitle'],
							'post_category' => $postarr['postcat'],
							'post_excerpt' => $this->ecleaner->cleanHtml($postarr['postexcerpt']),
							'post_status' => $postarr['poststatus'],
							'comment_status' => $postarr['commentstatus'],
							'post_modified' => $postarr['postmodified'],
							'comment_count' => $postarr['commentcount'],
							'post_ts' => time());

			return $this->insert($insarr, 'tbl_blog_posts');
		}
		if($mode == 'import')
		{
			$this->ipcleaner = $this->newObject('htmlcleaner', 'utilities');
			$this->iecleaner = $this->newObject('htmlcleaner', 'utilities');

			$imparr = array('userid' => $userid,
							'post_date' => $postarr['postdate'],
							'post_content' => $this->ipcleaner->cleanHtml($postarr['postcontent']),
							'post_title' => $postarr['posttitle'],
							'post_category' => $postarr['postcat'],
							'post_excerpt' => $this->iecleaner->cleanHtml($postarr['postexcerpt']),
							'post_status' => $postarr['poststatus'],
							'comment_status' => $postarr['commentstatus'],
							'post_modified' => $postarr['postmodified'],
							'comment_count' => $postarr['commentcount'],
							'post_ts' => strtotime($postarr['postdate']));

			return $this->insert($imparr, 'tbl_blog_posts');
		}
		else {
			$this->epcleaner = $this->newObject('htmlcleaner', 'utilities');
			$this->eecleaner = $this->newObject('htmlcleaner', 'utilities');

			$inseditarr = array('userid' => $userid,
							'post_date' => $postarr['postdate'],
							'post_content' => $this->epcleaner->cleanHtml($postarr['postcontent']),
							'post_title' => $postarr['posttitle'],
							'post_category' => $postarr['postcat'],
							'post_excerpt' => $this->eecleaner->cleanHtml($postarr['postexcerpt']),
							'post_status' => $postarr['poststatus'],
							'comment_status' => $postarr['commentstatus'],
							'post_modified' => $postarr['postmodified'],
							'comment_count' => $postarr['commentcount'],
							'post_ts' => strtotime($postarr['postdate']));

			return $this->update('id',$postarr['id'], $inseditarr, 'tbl_blog_posts');
		}
	}

	/**
	 * Method to get the User blogs DISTINCT query
	 *
	 * @param mixed $column
	 * @param mixed $table
	 * @return array
	 */
	public function getUBlogs($column, $table)
	{
		$this->_changeTable('tbl_blog_posts');
		return $this->getArray("SELECT DISTINCT $column from $table");
	}

	public function checkValidUser()
	{
		$this->_changeTable('tbl_users');
		$val = $this->getAll();
		return $val;

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