<?php

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
 * Data access (db model) Class for the blogcomments module
 *
 * This is a database model class for the blogcomments module. All database transaactions will go through
 * this class. This class is derived from the top level dbTable superclass in the framework core.
 *
 * @author Paul Scott
 * @filesource
 * @copyright AVOIR
 * @package blogcomments
 * @category chisimba
 * @access public
 */

class dbblogcomments extends dbTable
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
		parent::init('tbl_blogcomments');
	}

	public function addComm2Db($commentarray)
	{
		$userid = $commentarray['userid'];
		$commentauthor = $commentarray['commentauthor'];
		$authemail = $commentarray['useremail'];
		$authurl = htmlentities($commentarray['aurl']);
		$authip = $commentarray['ip'];
		$date = time();
		$cont = $commentarray['comment'];
		$agent = $commentarray['useragent'];
		$type = $commentarray['ctype'];
		$pid = $commentarray['postid'];
		$pmod = $commentarray['mod'];
		$ptable = $commentarray['table'];


		return $this->insert(array('userid' => $userid, 'comment_author' => $commentauthor, 'comment_author_email' => $authemail,
								   'comment_author_url' => $authurl, 'comment_author_ip' => $authip, 'comment_date' => $date,
								   'comment_content' => $cont, 'comment_karma' => 0, 'comment_approved' => 1, 'comment_agent' => $agent,
								   'comment_type' => $type, 'comment_parentid' => $pid, 'comment_parentmod' => $pmod, 'comment_parenttbl' => $ptable));

  		//comment_karma varchar(11),
  		//comment_approved varchar(5),

	}

	public function grabComments($pid)
	{
		$filter = "WHERE comment_parentid = '$pid' ORDER BY comment_date DESC";
		return $this->getAll($filter);
	}

}
?>