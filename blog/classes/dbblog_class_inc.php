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
		$this->objLanguage = $this->getObject("language", "language");

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

	/**
	 * Method to grab a cat name by the id
	 *
	 * @param string $catid
	 * @return string
	 */
	public function getCatById($catid)
	{
		if($catid == '0')
		{
			return $this->objLanguage->languageText("mod_blog_defcat", "blog");
		}
		else {
			$this->_changeTable('tbl_blog_cats');
			$catname = $this->getAll("WHERE id = '$catid'");
			if(!empty($catname))
			{
				return $catname[0]['cat_name'];
			}
			else {
				return NULL;
			}
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
			$this->_changeTable('tbl_blog_posts');
			return $this->getAll("WHERE userid = '$userid' AND post_status = '0' ORDER BY post_ts DESC");
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

	public function getPostByPostID($postid)
	{
		$this->_changeTable('tbl_blog_posts');
		$filter = "WHERE id = '$postid'";
		return $this->getAll($filter);
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
		//delete the post
		$this->delete('id',$id, 'tbl_blog_posts');
		//change tables to the postmeta table to delete the tags
		$this->_changeTable('tbl_blog_postmeta');
		//get all the entries where the post_id matches the deleted post id
		$tagstodelete = $this->getAll("WHERE post_id = '$id'");
		if(!empty($tagstodelete))
		{
			foreach($tagstodelete as $deltags)
			{
				//print_r($deltags);
				$this->delete('id', $deltags['id'], 'tbl_blog_postmeta');
			}
		}
		//change table and sort out the comments now
		$this->_changeTable('tbl_blogcomments');
		$commstodelete = $this->getAll("WHERE comment_parentid = '$id'");
		if(!empty($commstodelete))
		{
			foreach($commstodelete as $ctd)
			{
				//print_r($ctd);
				$this->delete('id', $ctd['id'], 'tbl_blogcomments');
			}
		}
		//clean up the trackbacks now
		$this->_changeTable("tbl_blog_trackbacks");
		$tbtodel = $this->getAll("WHERE postid = '$id'");
		if(!empty($tbtodel))
		{
			foreach ($tbtodel as $tbs)
			{
				//print_r($tbs);
				$this->delete('id', $tbs['id'], 'tbl_blog_trackbacks');
			}
		}
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
	 * Method to get the sticky posts of a user
	 *
	 * @param integer $userid
	 * @return array
	 */
	public function getStickyPosts($userid)
	{
		$this->_changeTable('tbl_blog_posts');
		$filter = "WHERE userid = '$userid' AND stickypost= '1' ORDER BY post_ts DESC";
		$stickyposts = $this->getAll($filter);
		return $stickyposts;
	}
	
	/**
	 * Method to get the latest posts
	 *
	 * @author Megan Watson
	 * @param integer $userid
	 * @return array
	 */
	public function getLastPosts($num = 10)
	{
		$this->_changeTable('tbl_blog_posts');
		$filter = "ORDER BY post_ts DESC LIMIT {$num}";
		$posts = $this->getAll($filter);
		
		return $posts;
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
			//$this->pcleaner = $this->newObject('htmlcleaner', 'utilities');
			//$this->ecleaner = $this->newObject('htmlcleaner', 'utilities');
			//$postarr['postcontent'] = preg_replace("/(\r\n|\n|\r)/", "", $postarr['postcontent']);
			$pc = preg_replace('=<br */?>=i', "\n", $postarr['postcontent']);
			$postarr['postcontent'] = str_ireplace("<br />", " <br /> ", $postarr['postcontent']);
			$insarr = array('userid' => $userid,
							'post_date' => date('r'),
							'post_content' => addslashes($postarr['postcontent']), //$pc), //$this->pcleaner->cleanHtml($this->objblogOps->html2txt($postarr['postcontent'])),
							'post_title' => htmlentities($postarr['posttitle']),
							'post_category' => $postarr['postcat'],
							'post_excerpt' => addslashes(htmlentities($postarr['postexcerpt'])), //$this->ecleaner->cleanHtml(addslashes($postarr['postexcerpt'])),
							'post_status' => $postarr['poststatus'],
							'comment_status' => $postarr['commentstatus'],
							'post_modified' => $postarr['postmodified'],
							'comment_count' => $postarr['commentcount'],
							'post_ts' => time(),
							'post_lic' => $postarr['cclic'],
							'stickypost' => $postarr['stickypost']);

			$insarr['id'] = $this->insert($insarr, 'tbl_blog_posts');
			return $this->luceneIndex($insarr);
		}
		if($mode == 'editpost')
		{
			//$this->pcleaner = $this->newObject('htmlcleaner', 'utilities');
			//$this->ecleaner = $this->newObject('htmlcleaner', 'utilities');
			//$postarr['postcontent'] = preg_replace("/(\r\n|\n|\r)/", " ", $postarr['postcontent']);
			$postarr['postcontent'] = str_ireplace("<br />", " <br /> ", $postarr['postcontent']);
			$pc = $postarr['postcontent'];
			$edarr = array('userid' => $userid,
							'post_date' => date('r'),
							'post_content' => addslashes($pc),
							'post_title' => htmlentities($postarr['posttitle']),
							'post_category' => $postarr['postcat'],
							'post_excerpt' => addslashes(htmlentities($postarr['postexcerpt'])), //$this->ecleaner->cleanHtml($postarr['postexcerpt']),
							'post_status' => $postarr['poststatus'],
							'comment_status' => $postarr['commentstatus'],
							'post_modified' => $postarr['postmodified'],
							'comment_count' => $postarr['commentcount'],
							'post_ts' => $postarr['postts'], //time(),
							'post_lic' => $postarr['cclic'],
							'stickypost' => $postarr['stickypost']);

			$this->update('id',$postarr['id'], $edarr, 'tbl_blog_posts');
			return $this->luceneReIndex($postarr);
		}
		if($mode == 'import')
		{
			//$this->ipcleaner = $this->newObject('htmlcleaner', 'utilities');
			//$this->iecleaner = $this->newObject('htmlcleaner', 'utilities');
			$postarr['cclic'] = NULL;
			$postarr['postcontent'] = str_ireplace("<br />", " <br /> ", $postarr['postcontent']);
			$imparr = array('userid' => $userid,
							'post_date' => $postarr['postdate'],
							'post_content' => addslashes($postarr['postcontent']), //$this->ipcleaner->cleanHtml($postarr['postcontent']),
							'post_title' => $postarr['posttitle'],
							'post_category' => $postarr['postcat'],
							'post_excerpt' => addslashes($postarr['postexcerpt']), //$this->iecleaner->cleanHtml($postarr['postexcerpt']),
							'post_status' => $postarr['poststatus'],
							'comment_status' => $postarr['commentstatus'],
							'post_modified' => $postarr['postmodified'],
							'comment_count' => $postarr['commentcount'],
							'post_ts' => strtotime($postarr['postdate']),
							'post_lic' => $postarr['cclic']);

			$imparr['id'] = $this->insert($imparr, 'tbl_blog_posts');
			return $this->luceneIndex($imparr);

		}
		if($mode == 'mail')
		{
			$this->ipcleaner = $this->newObject('htmlcleaner', 'utilities');
			$this->iecleaner = $this->newObject('htmlcleaner', 'utilities');
			$postarr['postcontent'] = $this->ipcleaner->cleanHtml(nl2br($postarr['postcontent']));
			$postarr['postcontent'] = str_ireplace("<br />", " <br /> ", $postarr['postcontent']);
			$postarr['cclic'] = NULL;
			$mparr = array('userid' => $userid,
							'post_date' => $postarr['postdate'],
							'post_content' => $postarr['postcontent'],
							'post_title' => $postarr['posttitle'],
							'post_category' => $postarr['postcat'],
							'post_excerpt' => $this->iecleaner->cleanHtml($postarr['postexcerpt']),
							'post_status' => $postarr['poststatus'],
							'comment_status' => $postarr['commentstatus'],
							'post_modified' => $postarr['postmodified'],
							'comment_count' => $postarr['commentcount'],
							'post_ts' => strtotime($postarr['postdate']),
							'post_lic' => $postarr['cclic']);

			$mparr['id'] = $this->insert($mparr, 'tbl_blog_posts');
			return $this->luceneIndex($mparr);
		}
		else {
			//$this->epcleaner = $this->newObject('htmlcleaner', 'utilities');
			//$this->eecleaner = $this->newObject('htmlcleaner', 'utilities');
			$postarr['postcontent'] = str_ireplace("<br />", " <br /> ", $postarr['postcontent']);
			$inseditarr = array('userid' => $userid,
							'post_date' => $postarr['postdate'],
							'post_content' => addslashes($postarr['postcontent']), //$this->epcleaner->cleanHtml($postarr['postcontent']),
							'post_title' => htmlentities($postarr['posttitle']),
							'post_category' => $postarr['postcat'],
							'post_excerpt' => addslashes(htmlentities($postarr['postexcerpt'])), //$this->eecleaner->cleanHtml($postarr['postexcerpt']),
							'post_status' => $postarr['poststatus'],
							'comment_status' => $postarr['commentstatus'],
							'post_modified' => $postarr['postmodified'],
							'comment_count' => $postarr['commentcount'],
							'post_ts' => strtotime($postarr['postdate']),
							'post_lic' => $postarr['cclic'],
							'stickypost' => $postarr['stickypost']);

			$this->update('id',$postarr['id'], $inseditarr, 'tbl_blog_posts');
			return $this->luceneReIndex($postarr);
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
	 * Method to insert trackback from remote to table
	 *
	 * @param array $data
	 * @return bool
	 */
	public function setTrackback($data)
	{
		$this->_changeTable('tbl_blog_trackbacks');
		$userid = $data['userid'];
		$postid = $data['id'];
		$remhost = $data['host'];
		$title = $data['title'];
		$excerpt = $data['excerpt'];
		$tburl = $data['url'];
		$blog_name = $data['blog_name'];

		$insarr = array('userid' => $userid, 'postid' => $postid, 'remhost' => $remhost, 'title' => $title, 'excerpt' => $excerpt, 'tburl' => $tburl, 'blog_name' => $blog_name);
		return $this->insert($insarr, 'tbl_blog_trackbacks');
	}

	/**
	 * Method to get the count of trackbacks associated with a particular post
	 *
	 * @param post id string $pid
	 * @return integer
	 */
	public function getTrackbacksPerPost($pid)
	{
		$this->_changeTable('tbl_blog_trackbacks');
		$filter = "WHERE postid = '$pid'";
		return $this->getRecordCount($filter);
	}

	/**
	 * Method to get the actual trackback text per post
	 *
	 * @param postid string $pid
	 * @return array
	 */
	public function grabTrackbacks($pid)
	{
		$this->_changeTable('tbl_blog_trackbacks');
		$filter = "WHERE postid = '$pid'";
		return $this->getAll($filter);
	}

	/**
	 * Method to get all of the tags associated with a particular post
	 *
	 * @param string $postid
	 * @return array
	 */
	public function getPostTags($postid)
	{
		$this->_changeTable("tbl_blog_postmeta");
		return $this->getAll("WHERE post_id = '$postid'");
	}

	/**
	 * Insert a set of tags into the database associated with the post
	 *
	 * @param array $tagarray
	 * @param string $userid
	 * @param String $postid
	 */
	public function insertTags($tagarray, $userid, $postid)
	{
		$this->_changeTable("tbl_blog_postmeta");
		foreach($tagarray as $tins)
		{
			$tins = trim($tins);
			if(!empty($tins))
			{
				$this->insert(array('userid' => $userid, 'post_id' => $postid, 'meta_key' => 'tag', 'meta_value' => $tins));
			}
		}

	}

	/**
	 * Method to remove all the tags associated with a post
	 *
	 * @param string $postid
	 * @return void
	 */
	public function removeAllTags($postid)
	{
		$this->_changeTable("tbl_blog_postmeta");
		return $this->delete('id', $postid, 'tbl_blog_postmeta');
	}

	/**
	 * Method to retrieve the tags associated with a userid
	 *
	 * @param string $userid
	 * @return array
	 */
	public function getTagsByUser($userid)
	{
		$this->_changeTable("tbl_blog_postmeta");
		return $this->getAll("WHERE userid = '$userid' and meta_key = 'tag'");
	}

	/**
	 * Method to get a tag weight by counting the tags
	 *
	 * @param string $tag
	 * @param string $userid
	 * @return integer
	 */
	public function getTagWeight($tag, $userid)
	{
		$this->_changeTable("tbl_blog_postmeta");
		$count = $this->getRecordCount("WHERE meta_value = '$tag' AND userid = '$userid'");
		return $count;
	}

	/**
	 * Method to return an array of posts associated with a tag
	 *
	 * @param string $userid
	 * @param string $tag
	 * @return array
	 */
	public function getAllPostsByTag($userid, $tag)
	{
		//first do a lookup and see what the post(s) id is/are
		$this->_changeTable("tbl_blog_postmeta");
		$poststoget = $this->getAll("WHERE meta_value = '$tag' AND userid = '$userid'");
		//print_r($poststoget);
		foreach($poststoget as $gettables)
		{
			$ptg[] = $gettables['post_id'];
		}
		//print_r($ptg); die();
		//now get the posts and return them
		$this->_changeTable("tbl_blog_posts");
		foreach($ptg as $pos)
		{
			$posts[] = $this->getAll("WHERE id = '$pos'");
		}
		//print_r($posts); die();
		return $posts;
	}

	/**
	 * Method to add a RSS feed to the database
	 *
	 * @param string $userid
	 * @param string $name
	 * @param string $desc
	 * @param string $url
	 * @return bool
	 */
	public function addRss($rssarr, $mode = NULL)
	{
		$this->_changeTable("tbl_blog_userrss");
		if($mode == NULL)
		{
			return $this->insert($rssarr);
		}
		elseif($mode == 'edit') {
			return $this->update('id', $rssarr['id'], $rssarr, "tbl_blog_userrss");
		}
		else {
			return FALSE;
		}
	}

	public function getUserRss($userid)
	{
		$this->_changeTable("tbl_blog_userrss");
		return $this->getAll("WHERE userid = '$userid'");
	}

	public function getRssById($id)
	{
		$this->_changeTable("tbl_blog_userrss");
		return $this->getAll("WHERE id = '$id'");
	}

	public function delRss($id)
	{
		$this->_changeTable("tbl_blog_userrss");
		return $this->delete('id', $id, "tbl_blog_userrss");
	}

	public function updateRss($rssarr, $id)
	{
		$this->_changeTable("tbl_blog_userrss");
		return $this->update('id', $id,$rssarr);
	}

	public function saveProfile($profile)
	{
		$this->_changeTable("tbl_blog_profile");
		return $this->insert($profile);
	}

	public function checkProfile($userid)
	{
		$this->_changeTable("tbl_blog_profile");
		$ret = $this->getAll("WHERE userid = '$userid'");
		if(empty($ret))
		{
			//this user has no profile yet
			return FALSE;
		}
		else {
			return $ret[0];
		}
	}

	public function updateProfile($profile)
	{
		$this->_changeTable("tbl_blog_profile");
		return $this->update('id', $profile['id'], $profile);
	}

	public function luceneIndex($data)
    {
    	//print_r($data); die();
    	$this->objConfig = $this->getObject('altconfig', 'config');
    	$this->objUser = $this->getObject('user', 'security');
    	$indexPath = $this->objConfig->getcontentBasePath();
    	if(file_exists($indexPath.'chisimbaIndex/segments'))
    	{
    		chmod($indexPath.'chisimbaIndex', 0777);
    		//we build onto the previous index
    		$index = new Zend_Search_Lucene($indexPath.'chisimbaIndex');
    	}
    	else {
    		//instantiate the lucene engine and create a new index
    		mkdir($indexPath.'chisimbaIndex');
    		chmod($indexPath.'chisimbaIndex', 0777);
    		$index = new Zend_Search_Lucene($indexPath.'chisimbaIndex', true);
    	}
    	//hook up the document parser
    	$document = new Zend_Search_Lucene_Document();
    	//change directory to the index path
    	chdir($indexPath);

    	//set the properties that we want to use in our index
    	//id for the index and optimization
		$document->addField(Zend_Search_Lucene_Field::UnStored('docid', $data['id']));
    	//date
    	$document->addField(Zend_Search_Lucene_Field::UnIndexed('date', $data['post_date']));
    	//url
    	$document->addField(Zend_Search_Lucene_Field::UnIndexed('url', $this->uri(array('module' => 'blog', 'action' => 'viewsingle', 'postid' => $data['id'], 'userid'=> $data['userid']))));
    	//createdBy
    	$document->addField(Zend_Search_Lucene_Field::Text('createdBy', $this->objUser->fullName($data['userid'])));
    	//document teaser
    	$document->addField(Zend_Search_Lucene_Field::Text('teaser', $data['post_excerpt']));
    	//doc title
    	$document->addField(Zend_Search_Lucene_Field::Text('title', $data['post_title']));
    	//doc author
    	$document->addField(Zend_Search_Lucene_Field::Text('author', $this->objUser->fullName($data['userid'])));
    	//document body
    	//NOTE: this is not actually put into the index, so as to keep the index nice and small
    	//      only a reference is inserted to the index.
    	$document->addField(Zend_Search_Lucene_Field::UnStored('contents', $data['post_content']));
    	//what else do we need here???
    	//add the document to the index
    	$index->addDocument($document);
    	//commit the index to disc
    	$index->commit();
    	//optimize the thing
    	//$index->optimize();
    }

    public function luceneReIndex($data)
    {
    	//var_dump($data);
    	$this->objConfig = $this->getObject('altconfig', 'config');
    	$this->objUser = $this->getObject('user', 'security');
    	$indexPath = $this->objConfig->getcontentBasePath();
    	if(file_exists($indexPath.'chisimbaIndex/segments'))
    	{
    		chmod($indexPath.'chisimbaIndex', 0777);
    		//we build onto the previous index
    		$index = new Zend_Search_Lucene($indexPath.'chisimbaIndex');
    	}
    	else {
    		//instantiate the lucene engine and create a new index
    		mkdir($indexPath.'chisimbaIndex');
    		chmod($indexPath.'chisimbaIndex', 0777);
    		$index = new Zend_Search_Lucene($indexPath.'chisimbaIndex', true);
    	}
    	$docid = $data['id'];
    	$removePath = $docid;
		$hits = $index->find('docid:' . $removePath);
		foreach ($hits as $hit) {
    		$index->delete($hit->id);
		}

		//ok now re-add the doc to the index
		//hook up the document parser
    	$document = new Zend_Search_Lucene_Document();
    	//change directory to the index path
    	chdir($indexPath);

    	//set the properties that we want to use in our index
    	//id for the index and optimization
		$document->addField(Zend_Search_Lucene_Field::UnStored('docid', $data['id']));
    	//date
    	$document->addField(Zend_Search_Lucene_Field::UnIndexed('date', $data['postdate']));
    	//url
    	$document->addField(Zend_Search_Lucene_Field::UnIndexed('url', $this->uri(array('module' => 'blog', 'action' => 'viewsingle', 'postid' => $data['id'], 'userid'=> $this->objUser->userId()))));
    	//createdBy
    	$document->addField(Zend_Search_Lucene_Field::Text('createdBy', $this->objUser->fullName($this->objUser->userId())));
    	//document teaser
    	$document->addField(Zend_Search_Lucene_Field::Text('teaser', $data['postexcerpt']));
    	//doc title
    	$document->addField(Zend_Search_Lucene_Field::Text('title', $data['posttitle']));
    	//doc author
    	$document->addField(Zend_Search_Lucene_Field::Text('author', $this->objUser->fullName($this->objUser->userId())));
    	//document body
    	//NOTE: this is not actually put into the index, so as to keep the index nice and small
    	//      only a reference is inserted to the index.
    	$document->addField(Zend_Search_Lucene_Field::UnStored('contents', $data['postcontent']));
    	//what else do we need here???
    	//add the document to the index
    	$index->addDocument($document);
    	//commit the index to disc
    	$index->commit();
    	//optimize the thing
    	//$index->optimize();

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